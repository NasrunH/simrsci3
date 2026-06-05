<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal_pasien extends MY_Controller {

    private $pasien_id;
    private $pasien_profile;

    public function __construct() {
        parent::__construct();
        $role = strtolower($this->session->userdata('role') ?? '');
        if ($role !== 'pasien') {
            show_error('Akses Ditolak: Halaman ini hanya diperuntukkan bagi akun Pasien.', 403, 'Akses Khusus Pasien');
        }

        $this->load->model('Pasien_model');
        $this->load->model('Layanan_model');
        $this->load->model('Billing_model');
        $this->load->model('Resep_model');
        $this->load->model('Antrean_model');

        $user_id = $this->session->userdata('id_user');
        $this->pasien_profile = $this->Pasien_model->get_by_user_id($user_id);

        if (!$this->pasien_profile) {
            show_error('Profil medis Anda tidak ditemukan. Silakan hubungi admin.', 404, 'Profil Medis Kosong');
        }

        $this->pasien_id = $this->pasien_profile->id_pasien;
    }

    private function _render($view, $data = []) {
        $segment = $this->uri->segment(2);
        $nav_map = [
            ''                 => 'home',
            'antrean_saat_ini' => 'live',
            'buat_antrean'       => 'daftar',
            'rekam_medis'        => 'medis',
            'billing'            => 'tagihan',
            'invoice'            => 'tagihan',
        ];

        $data['title']         = $data['title'] ?? 'Portal Pasien';
        $data['pasien']        = $data['pasien'] ?? $this->pasien_profile;
        $data['portal_nav']    = $nav_map[$segment ?? ''] ?? 'home';
        $data['tagihan_badge'] = $this->db
            ->where(['id_pasien' => $this->pasien_id, 'status' => 'Belum Lunas'])
            ->count_all_results('billing');

        $this->load->view('layouts/portal_mobile', [
            'view_name' => $view,
            'view_data' => $data,
            'title'           => $data['title'],
            'pasien'          => $data['pasien'],
            'portal_nav'      => $data['portal_nav'],
            'tagihan_badge'   => $data['tagihan_badge'],
            'hide_bottom_nav' => $data['hide_bottom_nav'] ?? false,
            'show_back'       => $data['show_back'] ?? false,
            'back_url'        => $data['back_url'] ?? base_url('portal_pasien'),
        ]);
    }

    public function index() {
        $data['title'] = 'Beranda';
        $data['pasien'] = $this->pasien_profile;

        $this->db->select('antrean.*, dokter.nama_dokter, layanan.nama_layanan');
        $this->db->from('antrean');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = antrean.id_layanan', 'left');
        $this->db->where('antrean.id_pasien', $this->pasien_id);
        $this->db->where('antrean.tanggal_antrean', date('Y-m-d'));
        $data['antrean_hari_ini'] = $this->db->get()->row();

        if ($data['antrean_hari_ini']) {
            $a = $data['antrean_hari_ini'];
            $this->db->where('id_layanan', $a->id_layanan);
            $this->db->where('tanggal_antrean', $a->tanggal_antrean);
            $this->db->where('status', 'Menunggu');
            $this->db->where('no_antrean <', $a->no_antrean);
            $data['antrean_di_depan'] = $this->db->count_all_results('antrean');
        } else {
            $data['antrean_di_depan'] = 0;
        }

        $data['total_kunjungan'] = $this->db->where('id_pasien', $this->pasien_id)->count_all_results('rekam_medis');
        $data['tagihan_aktif'] = $this->db
            ->where(['id_pasien' => $this->pasien_id, 'status' => 'Belum Lunas'])
            ->count_all_results('billing');

        $this->db->select('rekam_medis.*, dokter.nama_dokter');
        $this->db->from('rekam_medis');
        $this->db->join('dokter', 'dokter.id_dokter = rekam_medis.id_dokter');
        $this->db->where('rekam_medis.id_pasien', $this->pasien_id);
        $this->db->order_by('rekam_medis.tanggal_periksa', 'DESC');
        $this->db->limit(3);
        $data['kunjungan_terakhir'] = $this->db->get()->result();

        $this->_render('portal_pasien/dashboard', $data);
    }

    public function status_antrean_ajax() {
        $this->output->set_content_type('application/json');
        $tanggal = date('Y-m-d');

        $this->db->select('antrean.*, dokter.nama_dokter, layanan.nama_layanan');
        $this->db->from('antrean');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = antrean.id_layanan', 'left');
        $this->db->where('antrean.id_pasien', $this->pasien_id);
        $this->db->where('antrean.tanggal_antrean', $tanggal);
        $antrean = $this->db->get()->row();

        if (!$antrean) {
            echo json_encode(['has_queue' => false]);
            return;
        }

        $di_depan = 0;
        if ($antrean->status === 'Menunggu') {
            $this->db->where('id_layanan', $antrean->id_layanan);
            $this->db->where('tanggal_antrean', $tanggal);
            $this->db->where('status', 'Menunggu');
            $this->db->where('no_antrean <', $antrean->no_antrean);
            $di_depan = $this->db->count_all_results('antrean');
        }

        $this->db->select('no_antrean');
        $this->db->where('id_layanan', $antrean->id_layanan);
        $this->db->where('tanggal_antrean', $tanggal);
        $this->db->where('status', 'Diperiksa');
        $this->db->order_by('id_antrean', 'DESC');
        $dipanggil = $this->db->get('antrean')->row();

        echo json_encode([
            'has_queue'      => true,
            'no_antrean'     => (int) $antrean->no_antrean,
            'status'         => $antrean->status,
            'nama_layanan'   => $antrean->nama_layanan,
            'nama_dokter'    => $antrean->nama_dokter,
            'di_depan'       => $di_depan,
            'sedang_dipanggil' => $dipanggil ? (int) $dipanggil->no_antrean : null,
        ]);
    }

    public function buat_antrean() {
        if ($this->input->post()) {
            $id_layanan = $this->input->post('id_layanan', TRUE);
            $id_dokter  = $this->input->post('id_dokter', TRUE);
            $tanggal    = $this->input->post('tanggal_antrean', TRUE);

            $this->db->where('id_pasien', $this->pasien_id);
            $this->db->where('tanggal_antrean', $tanggal);
            $this->db->where('id_layanan', $id_layanan);
            if ($this->db->get('antrean')->row()) {
                $this->session->set_flashdata('error', 'Anda sudah terdaftar di poliklinik ini untuk tanggal tersebut.');
                redirect('portal_pasien/buat_antrean');
            }

            $nomor_baru = $this->Antrean_model->generate_nomor($id_layanan, $tanggal);
            $this->db->insert('antrean', [
                'no_antrean'      => $nomor_baru,
                'id_pasien'       => $this->pasien_id,
                'id_dokter'       => $id_dokter,
                'id_layanan'      => $id_layanan,
                'tanggal_antrean' => $tanggal,
                'keluhan_awal'    => $this->input->post('keluhan_awal', TRUE),
                'status'          => 'Menunggu',
            ]);

            $this->session->set_flashdata('success', 'Nomor antrean Anda: ' . $nomor_baru);
            redirect('portal_pasien');
        }

        $this->_render('portal_pasien/buat_antrean', [
            'title'   => 'Daftar Antrean',
            'layanan' => $this->Layanan_model->get_all(),
        ]);
    }

    public function get_dokter($id_layanan) {
        $this->db->select('id_dokter, nama_dokter, spesialisasi');
        $this->db->where('id_layanan', $id_layanan);
        echo json_encode($this->db->get('dokter')->result());
    }

    public function antrean_saat_ini() {
        $this->_render('portal_pasien/antrean_saat_ini', [
            'title'   => 'Monitor Antrean',
            'layanan' => $this->Layanan_model->get_all(),
        ]);
    }

    public function get_active_queues_ajax() {
        $tanggal_hari_ini = date('Y-m-d');
        $layanan = $this->Layanan_model->get_all();
        $result = [];

        foreach ($layanan as $l) {
            $this->db->select('no_antrean, dokter.nama_dokter');
            $this->db->from('antrean');
            $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
            $this->db->where('antrean.id_layanan', $l->id_layanan);
            $this->db->where('antrean.tanggal_antrean', $tanggal_hari_ini);
            $this->db->where('antrean.status', 'Diperiksa');
            $this->db->order_by('antrean.no_antrean', 'DESC');
            $active = $this->db->get()->row();

            $this->db->where('id_layanan', $l->id_layanan);
            $this->db->where('tanggal_antrean', $tanggal_hari_ini);
            $this->db->where('status', 'Menunggu');
            $waiting_count = $this->db->count_all_results('antrean');

            $this->db->select('no_antrean');
            $this->db->where('id_layanan', $l->id_layanan);
            $this->db->where('tanggal_antrean', $tanggal_hari_ini);
            $this->db->order_by('no_antrean', 'DESC');
            $last_registered = $this->db->get('antrean')->row();

            $result[] = [
                'id_layanan'       => $l->id_layanan,
                'nama_layanan'     => $l->nama_layanan,
                'tarif'            => number_format($l->tarif, 0, ',', '.'),
                'dokter_bertugas'  => $active ? $active->nama_dokter : 'Menunggu Dokter',
                'sedang_diperiksa' => $active ? $active->no_antrean : '-',
                'sisa_antrean'     => $waiting_count,
                'antrean_terakhir' => $last_registered ? $last_registered->no_antrean : 0,
            ];
        }

        echo json_encode($result);
    }

    public function rekam_medis() {
        $this->db->select('rekam_medis.*, dokter.nama_dokter, layanan.nama_layanan');
        $this->db->from('rekam_medis');
        $this->db->join('dokter', 'dokter.id_dokter = rekam_medis.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = dokter.id_layanan', 'left');
        $this->db->where('rekam_medis.id_pasien', $this->pasien_id);
        $this->db->order_by('rekam_medis.tanggal_periksa', 'DESC');
        $data['rekam_medis'] = $this->db->get()->result();

        $this->_render('portal_pasien/rekam_medis', [
            'title' => 'Rekam Medis',
        ] + $data);
    }

    public function billing() {
        $this->db->select('billing.*, users.username as nama_kasir');
        $this->db->from('billing');
        $this->db->join('users', 'users.id_user = billing.id_kasir', 'left');
        $this->db->where('billing.id_pasien', $this->pasien_id);
        $this->db->order_by('billing.created_at', 'DESC');
        $data['billing'] = $this->db->get()->result();

        $this->_render('portal_pasien/billing', [
            'title' => 'Tagihan',
        ] + $data);
    }

    public function invoice($id) {
        $billing = $this->Billing_model->get_by_id($id);
        if (!$billing || $billing->id_pasien != $this->pasien_id) {
            show_error('Akses ditolak.', 403);
        }

        $this->_render('portal_pasien/invoice', [
            'title'          => 'Kuitansi',
            'hide_bottom_nav'=> true,
            'show_back'      => true,
            'back_url'       => base_url('portal_pasien/billing'),
            'b'              => $billing,
            'detail_resep'   => $this->Resep_model->get_detail_resep($billing->id_resep),
        ]);
    }
}
