<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // HAPUS restrict_to(['admin', 'dokter', 'pasien'])
        // Ganti dengan pengecekan permission, atau biarkan semua role yang sudah login masuk
        
        $this->load->model('Laporan_model');
    }

    public function index() {
        // ... kode index Anda sebelumnya ...
        $role = $this->session->userdata('role');
        
        // Data dasar untuk dilempar ke view
        $data = [
            'title' => 'Dashboard Utama',
            'role'  => $role
        ];

        // Jika user adalah ADMIN, ambil data statistik ringkasan
        if ($role == 'admin') {
            $data['ringkasan'] = $this->Laporan_model->get_ringkasan_hari_ini();
            $data['obat_terlaris'] = $this->Laporan_model->get_obat_terlaris(5); // Ambil Top 5
        }

        // --- SETUP TEMPLATE CI3 ---
        $template_data = [
            'view_name' => 'dashboard/index', 
            'view_data' => $data              
        ];

        $this->load->view('layouts/template', $template_data);
    }
}