<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrean extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_antrean');
        $this->load->model('Antrean_model');
        $this->load->model('Pasien_model');
        $this->load->model('Dokter_model');
        $this->load->model('Layanan_model');
    }

    public function index() {
        $data['title'] = 'Antrean Poli Hari Ini';

        $role = strtolower($this->session->userdata('role'));
        $user_id = $this->session->userdata('id_user');
        $tanggal_hari_ini = date('Y-m-d');

        $id_pasien_filter = null;
        $id_dokter_filter = null;

        if ($role === 'pasien') {
            $pasien = $this->Pasien_model->get_by_user_id($user_id);
            $id_pasien_filter = $pasien ? $pasien->id_pasien : -1;
        } elseif ($role === 'dokter') {
            $dokter = $this->Dokter_model->get_by_user_id($user_id);
            $id_dokter_filter = $dokter ? $dokter->id_dokter : -1;
        }

        $antrean = $this->Antrean_model->get_hari_ini($tanggal_hari_ini, $id_pasien_filter, $id_dokter_filter);
        $layanan_all = $this->Layanan_model->get_all();

        $data['antrean']        = $antrean;
        $data['layanan_all']    = $layanan_all;
        $data['grouped']        = $this->Antrean_model->group_by_layanan_and_status($antrean);
        $data['tanggal']        = $tanggal_hari_ini;
        $data['role']           = $role;
        $data['active_poli']    = $this->input->get('poli', TRUE) ?: 'all';
        $data['active_subtab']  = $this->input->get('tab', TRUE) ?: 'menunggu';
        $data['can_call']       = ($role !== 'pasien');

        $this->load->view('layouts/template', ['view_name' => 'antrean/index', 'view_data' => $data]);
    }

    public function get_dokter_by_layanan($id_layanan) {
        $this->db->select('id_dokter, nama_dokter, spesialisasi');
        $this->db->where('id_layanan', $id_layanan);
        echo json_encode($this->db->get('dokter')->result());
    }

    public function panggil_selanjutnya() {
        $this->_require_can_call();
        $this->output->set_content_type('application/json');

        $id_layanan = (int) $this->input->post('id_layanan', TRUE);
        $tanggal    = date('Y-m-d');

        if (!$id_layanan) {
            echo json_encode(['success' => false, 'message' => 'Poliklinik tidak valid.']);
            return;
        }

        $next = $this->Antrean_model->get_next_menunggu($id_layanan, $tanggal);
        if (!$next) {
            echo json_encode(['success' => false, 'message' => 'Tidak ada antrean yang menunggu di poli ini.']);
            return;
        }

        $this->Antrean_model->update_status($next->id_antrean, 'Diperiksa');
        $called = $this->Antrean_model->get_by_id_detail($next->id_antrean);

        echo json_encode([
            'success' => true,
            'message' => 'Antrean nomor ' . $called->no_antrean . ' dipanggil.',
            'antrean' => $this->Antrean_model->to_call_payload($called),
            'redirect' => base_url('antrean?poli=' . $id_layanan . '&tab=dipanggil'),
        ]);
    }

    public function panggil_ulang($id_antrean) {
        $this->_require_can_call();
        $this->output->set_content_type('application/json');

        $row = $this->Antrean_model->get_by_id_detail((int) $id_antrean);
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Data antrean tidak ditemukan.']);
            return;
        }

        if (!in_array($row->status, ['Menunggu', 'Diperiksa'])) {
            echo json_encode(['success' => false, 'message' => 'Hanya antrean menunggu atau sedang dipanggil yang dapat dipanggil ulang.']);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'Memanggil ulang nomor ' . $row->no_antrean,
            'antrean' => $this->Antrean_model->to_call_payload($row),
        ]);
    }

    public function create() {
        $this->require_permission('create_antrean');

        if ($this->input->post()) {
            $role = strtolower($this->session->userdata('role'));

            $id_pasien = null;
            if ($role === 'pasien') {
                $pasien = $this->Pasien_model->get_by_user_id($this->session->userdata('id_user'));
                $id_pasien = $pasien ? $pasien->id_pasien : null;
            } else {
                $id_pasien = $this->input->post('id_pasien', TRUE);
            }

            if (!$id_pasien) {
                $this->session->set_flashdata('error', 'Gagal memproses pendaftaran: Data pasien tidak valid.');
                redirect('antrean/create');
            }

            $id_layanan = $this->input->post('id_layanan', TRUE);
            $id_dokter  = $this->input->post('id_dokter', TRUE);
            $tanggal    = $this->input->post('tanggal_antrean', TRUE);

            $this->db->where('id_pasien', $id_pasien);
            $this->db->where('tanggal_antrean', $tanggal);
            $this->db->where('id_layanan', $id_layanan);
            if ($this->db->get('antrean')->row()) {
                $this->session->set_flashdata('error', 'Pasien sudah terdaftar dalam antrean poliklinik ini untuk tanggal tersebut.');
                redirect('antrean/create');
            }

            $data = [
                'no_antrean'      => $this->Antrean_model->generate_nomor($id_layanan, $tanggal),
                'id_pasien'       => $id_pasien,
                'id_dokter'       => $id_dokter,
                'id_layanan'      => $id_layanan,
                'tanggal_antrean' => $tanggal,
                'keluhan_awal'    => $this->input->post('keluhan_awal', TRUE),
                'status'          => 'Menunggu',
            ];

            $this->db->insert('antrean', $data);
            $this->session->set_flashdata('success', 'Pendaftaran antrean sukses. Nomor Antrean Anda: ' . $data['no_antrean']);
            redirect('antrean?poli=' . $id_layanan . '&tab=menunggu');
        }

        $data['title']   = 'Daftar Kunjungan Poliklinik';
        $data['layanan'] = $this->Layanan_model->get_all();
        $data['pasien']  = $this->Pasien_model->get_all();

        $this->load->view('layouts/template', ['view_name' => 'antrean/create', 'view_data' => $data]);
    }

    public function update_status($id, $status) {
        $valid_status = ['Menunggu', 'Diperiksa', 'Selesai', 'Batal'];
        if (!in_array($status, $valid_status)) {
            show_error('Status tidak valid', 400);
        }

        $row = $this->Antrean_model->get_by_id_detail((int) $id);
        $this->Antrean_model->update_status((int) $id, $status);

        $tab_map = [
            'Menunggu'  => 'menunggu',
            'Diperiksa' => 'dipanggil',
            'Selesai'   => 'selesai',
            'Batal'     => 'batal',
        ];
        $poli = $row ? ($row->id_layanan ?: 'all') : 'all';
        $tab  = $tab_map[$status] ?? 'menunggu';

        $this->session->set_flashdata('success', 'Status antrean diperbarui: ' . $status);
        redirect('antrean?poli=' . $poli . '&tab=' . $tab);
    }

    private function _require_can_call() {
        if (strtolower($this->session->userdata('role')) === 'pasien') {
            show_error('Akses ditolak.', 403);
        }
    }
}
