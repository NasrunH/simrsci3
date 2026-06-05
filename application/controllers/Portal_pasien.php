<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal_pasien extends MY_Controller {

    private $pasien_id;
    private $pasien_profile;

    public function __construct() {
        parent::__construct();
        // Pastikan hanya user dengan role pasien yang bisa mengakses portal ini
        $role = strtolower($this->session->userdata('role') ?? '');
        if ($role !== 'pasien') {
            show_error('Akses Ditolak: Halaman ini hanya diperuntukkan bagi akun Pasien.', 403, 'Akses Khusus Pasien');
        }

        $this->load->model('Pasien_model');
        $this->load->model('Layanan_model');
        $this->load->model('Billing_model');
        $this->load->model('Resep_model');

        // Tarik data profil pasien berdasarkan user_id login
        $user_id = $this->session->userdata('id_user');
        $this->pasien_profile = $this->Pasien_model->get_by_user_id($user_id);
        
        if (!$this->pasien_profile) {
            show_error('Profil medis Anda tidak ditemukan. Silakan hubungi admin untuk menautkan akun Anda ke data Rekam Medis.', 404, 'Profil Medis Kosong');
        }

        $this->pasien_id = $this->pasien_profile->id_pasien;
    }

    // ==========================================
    // DASHBOARD PASIEN
    // ==========================================
    public function index() {
        $data['title'] = 'Portal Pasien - SIMRS';
        $data['pasien'] = $this->pasien_profile;
        
        // 1. Ambil antrean aktif hari ini (jika ada)
        $this->db->select('antrean.*, dokter.nama_dokter, layanan.nama_layanan');
        $this->db->from('antrean');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = antrean.id_layanan', 'left');
        $this->db->where('antrean.id_pasien', $this->pasien_id);
        $this->db->where('antrean.tanggal_antrean', date('Y-m-d'));
        $data['antrean_hari_ini'] = $this->db->get()->row();

        // 2. Ambil ringkasan statistik
        $data['total_kunjungan'] = $this->db->where('id_pasien', $this->pasien_id)->count_all_results('rekam_medis');
        $data['tagihan_aktif'] = $this->db->where(['id_pasien' => $this->pasien_id, 'status' => 'Belum Lunas'])->count_all_results('billing');

        // 3. Ambil 3 Kunjungan Medis Terakhir
        $this->db->select('rekam_medis.*, dokter.nama_dokter');
        $this->db->from('rekam_medis');
        $this->db->join('dokter', 'dokter.id_dokter = rekam_medis.id_dokter');
        $this->db->where('rekam_medis.id_pasien', $this->pasien_id);
        $this->db->order_by('rekam_medis.tanggal_periksa', 'DESC');
        $this->db->limit(3);
        $data['kunjungan_terakhir'] = $this->db->get()->result();

        $this->load->view('layouts/template', ['view_name' => 'portal_pasien/dashboard', 'view_data' => $data]);
    }

    // ==========================================
    // PENDAFTARAN ANTREAN MANDIRI
    // ==========================================
    public function buat_antrean() {
        if ($this->input->post()) {
            $id_layanan = $this->input->post('id_layanan', TRUE);
            $id_dokter  = $this->input->post('id_dokter', TRUE);
            $tanggal    = $this->input->post('tanggal_antrean', TRUE);

            // Cek apakah hari ini sudah terdaftar di poli yang sama
            $this->db->where('id_pasien', $this->pasien_id);
            $this->db->where('tanggal_antrean', $tanggal);
            $this->db->where('id_layanan', $id_layanan);
            $ada = $this->db->get('antrean')->row();

            if ($ada) {
                $this->session->set_flashdata('error', 'Anda sudah terdaftar di poliklinik ini untuk tanggal tersebut.');
                redirect('portal_pasien/buat_antrean');
            }

            // Hitung nomor antrean berikutnya
            $this->db->where('id_layanan', $id_layanan);
            $this->db->where('tanggal_antrean', $tanggal);
            $nomor_baru = $this->db->count_all_results('antrean') + 1;

            $data = [
                'no_antrean'      => $nomor_baru,
                'id_pasien'       => $this->pasien_id,
                'id_dokter'       => $id_dokter,
                'id_layanan'      => $id_layanan,
                'tanggal_antrean' => $tanggal,
                'keluhan_awal'    => $this->input->post('keluhan_awal', TRUE),
                'status'          => 'Menunggu'
            ];

            $this->db->insert('antrean', $data);
            $this->session->set_flashdata('success', 'Pendaftaran antrean berhasil! Nomor Antrean Anda: ' . $nomor_baru);
            redirect('portal_pasien');
        }

        $data['title'] = 'Ambil Antrean Poli';
        $data['layanan'] = $this->Layanan_model->get_all();
        $this->load->view('layouts/template', ['view_name' => 'portal_pasien/buat_antrean', 'view_data' => $data]);
    }

    // AJAX endpoint penyaring dokter
    public function get_dokter($id_layanan) {
        $this->db->select('id_dokter, nama_dokter, spesialisasi');
        $this->db->where('id_layanan', $id_layanan);
        $dokter = $this->db->get('dokter')->result();
        echo json_encode($dokter);
    }

    // ==========================================
    // MENU BARU: MONITOR ANTREAN REAL-TIME
    // ==========================================
    public function antrean_saat_ini() {
        $data['title'] = 'Papan Monitor Antrean Live';
        $data['layanan'] = $this->Layanan_model->get_all();
        $this->load->view('layouts/template', ['view_name' => 'portal_pasien/antrean_saat_ini', 'view_data' => $data]);
    }

    // Endpoint API internal untuk mensuplai data real-time ke AJAX JS
    public function get_active_queues_ajax() {
        $tanggal_hari_ini = date('Y-m-d');
        
        // Ambil semua poliklinik/layanan yang terdaftar
        $layanan = $this->Layanan_model->get_all();
        $result = [];

        foreach ($layanan as $l) {
            // 1. Cari nomor antrean yang STATUSNYA 'Diperiksa' hari ini (panggilan aktif)
            $this->db->select('no_antrean, dokter.nama_dokter');
            $this->db->from('antrean');
            $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
            $this->db->where('antrean.id_layanan', $l->id_layanan);
            $this->db->where('antrean.tanggal_antrean', $tanggal_hari_ini);
            $this->db->where('antrean.status', 'Diperiksa');
            $this->db->order_by('antrean.no_antrean', 'DESC'); // Yang terbaru dipanggil
            $active = $this->db->get()->row();

            // 2. Hitung jumlah sisa antrean yang masih mengantre (status 'Menunggu')
            $this->db->where('id_layanan', $l->id_layanan);
            $this->db->where('tanggal_antrean', $tanggal_hari_ini);
            $this->db->where('status', 'Menunggu');
            $waiting_count = $this->db->count_all_results('antrean');

            // 3. Ambil nomor antrean pendaftaran terakhir hari ini
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
                'antrean_terakhir' => $last_registered ? $last_registered->no_antrean : 0
            ];
        }

        echo json_encode($result);
    }

    // ==========================================
    // RIWAYAT REKAM MEDIS & SOAP
    // ==========================================
    public function rekam_medis() {
        $data['title'] = 'Riwayat Pemeriksaan Medis';
        
        $this->db->select('rekam_medis.*, dokter.nama_dokter, layanan.nama_layanan');
        $this->db->from('rekam_medis');
        $this->db->join('dokter', 'dokter.id_dokter = rekam_medis.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = dokter.id_layanan', 'left');
        $this->db->where('rekam_medis.id_pasien', $this->pasien_id);
        $this->db->order_by('rekam_medis.tanggal_periksa', 'DESC');
        $data['rekam_medis'] = $this->db->get()->result();

        $this->load->view('layouts/template', ['view_name' => 'portal_pasien/rekam_medis', 'view_data' => $data]);
    }

    // ==========================================
    // RIWAYAT TRANSAKSI & BILLING
    // ==========================================
    public function billing() {
        $data['title'] = 'Riwayat Transaksi Keuangan';

        $this->db->select('billing.*, users.username as nama_kasir');
        $this->db->from('billing');
        $this->db->join('users', 'users.id_user = billing.id_kasir', 'left');
        $this->db->where('billing.id_pasien', $this->pasien_id);
        $this->db->order_by('billing.created_at', 'DESC');
        $data['billing'] = $this->db->get()->result();

        $this->load->view('layouts/template', ['view_name' => 'portal_pasien/billing', 'view_data' => $data]);
    }

    public function invoice($id) {
        $billing = $this->Billing_model->get_by_id($id);
        if (!$billing || $billing->id_pasien != $this->pasien_id) {
            show_error('Akses Terlarang: Anda tidak diizinkan melihat invoice milik pasien lain.', 403, 'Akses Ditolak');
        }

        $data['title'] = 'Kuitansi Pembayaran';
        $data['b'] = $billing;
        $data['detail_resep'] = $this->Resep_model->get_detail_resep($billing->id_resep);

        $this->load->view('layouts/template', ['view_name' => 'billing/invoice', 'view_data' => $data]);
    }
}