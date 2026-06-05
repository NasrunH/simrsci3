<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrean extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_antrean');
        $this->load->model('Pasien_model');
        $this->load->model('Dokter_model');
        $this->load->model('Layanan_model');
    }

    public function index() {
        $data['title'] = 'Antrean Poli Hari Ini';
        
        $role = strtolower($this->session->userdata('role'));
        $user_id = $this->session->userdata('id_user');
        
        $tanggal_hari_ini = date('Y-m-d');

        // ==============================================================================
        // FIX 1: Ambil data profile SEBELUM mengompilasi Active Record query utama.
        // Ini mencegah kebocoran state query (Query Builder leakage/overlap) di CI3.
        // ==============================================================================
        $id_pasien_filter = null;
        $id_dokter_filter = null;

        if ($role == 'pasien') {
            $pasien = $this->Pasien_model->get_by_user_id($user_id);
            $id_pasien_filter = $pasien ? $pasien->id_pasien : -1;
        } elseif ($role == 'dokter') {
            $dokter = $this->Dokter_model->get_by_user_id($user_id);
            $id_dokter_filter = $dokter ? $dokter->id_dokter : -1;
        }

        // ==============================================================================
        // FIX 2: Tambahkan dokter.spesialisasi ke select list agar tidak Undefined di View
        // ==============================================================================
        $this->db->select('antrean.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis, dokter.nama_dokter, dokter.spesialisasi, layanan.nama_layanan');
        $this->db->from('antrean');
        $this->db->join('pasien', 'pasien.id_pasien = antrean.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = antrean.id_layanan', 'left');
        $this->db->where('antrean.tanggal_antrean', $tanggal_hari_ini);

        // Terapkan filter yang sudah didapatkan sebelumnya
        if ($id_pasien_filter !== null) {
            $this->db->where('antrean.id_pasien', $id_pasien_filter);
        }
        if ($id_dokter_filter !== null) {
            $this->db->where('antrean.id_dokter', $id_dokter_filter);
        }

        $this->db->order_by('antrean.id_layanan', 'ASC');
        $this->db->order_by('antrean.no_antrean', 'ASC');
        
        $data['antrean'] = $this->db->get()->result();

        $this->load->view('layouts/template', ['view_name' => 'antrean/index', 'view_data' => $data]);
    }

    // Fungsi AJAX Endpoint untuk menyaring dokter berdasarkan layanan poliklinik
    public function get_dokter_by_layanan($id_layanan) {
        $this->db->select('id_dokter, nama_dokter, spesialisasi');
        $this->db->where('id_layanan', $id_layanan);
        $dokter = $this->db->get('dokter')->result();
        
        echo json_encode($dokter);
    }

    public function create() {
        $this->require_permission('create_antrean');

        if ($this->input->post()) {
            $role = strtolower($this->session->userdata('role'));
            
            $id_pasien = null;
            if ($role == 'pasien') {
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

            // Cek apakah antrean sudah ada
            $this->db->where('id_pasien', $id_pasien);
            $this->db->where('tanggal_antrean', $tanggal);
            $this->db->where('id_layanan', $id_layanan);
            $ada = $this->db->get('antrean')->row();

            if ($ada) {
                $this->session->set_flashdata('error', 'Pasien sudah terdaftar dalam antrean poliklinik ini untuk tanggal tersebut.');
                redirect('antrean/create');
            }

            // Generate nomor antrean dinamis berdasarkan poli dan tanggal
            $nomor_baru = $this->generate_nomor($id_layanan, $tanggal);

            $data = [
                'no_antrean'      => $nomor_baru,
                'id_pasien'       => $id_pasien,
                'id_dokter'       => $id_dokter,
                'id_layanan'      => $id_layanan,
                'tanggal_antrean' => $tanggal,
                'keluhan_awal'         => $this->input->post('keluhan_awal', TRUE),
                'status'          => 'Menunggu'
            ];

            $this->db->insert('antrean', $data);
            $this->session->set_flashdata('success', 'Pendaftaran antrean sukses. Nomor Antrean Anda: ' . $nomor_baru);
            redirect('antrean');
        }

        $data['title']   = 'Daftar Kunjungan Poliklinik';
        $data['layanan'] = $this->Layanan_model->get_all();
        $data['pasien']  = $this->Pasien_model->get_all();

        $this->load->view('layouts/template', ['view_name' => 'antrean/create', 'view_data' => $data]);
    }

    private function generate_nomor($id_layanan, $tanggal) {
        $this->db->where('id_layanan', $id_layanan);
        $this->db->where('tanggal_antrean', $tanggal);
        $jumlah = $this->db->count_all_results('antrean');
        return $jumlah + 1;
    }

    public function update_status($id, $status) {
        $valid_status = ['Menunggu', 'Diperiksa', 'Selesai', 'Batal'];
        if (!in_array($status, $valid_status)) {
            show_error('Status tidak valid', 400);
        }

        $this->db->where('id_antrean', $id)->update('antrean', ['status' => $status]);
        $this->session->set_flashdata('success', 'Status antrean berhasil diperbarui menjadi: ' . $status);
        redirect('antrean');
    }
}