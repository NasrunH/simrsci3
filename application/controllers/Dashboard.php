<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Mengizinkan admin, dokter, dan pasien masuk ke dashboard
        $this->restrict_to(['admin', 'dokter', 'pasien']);
        
        // Load model yang dibutuhkan untuk statistik dashboard
        $this->load->model('Laporan_model');
    }

    public function index() {
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
        // Kita menggunakan layout template, bukan meload view secara langsung
        $template_data = [
            'view_name' => 'dashboard/index', // View yang akan disisipkan ke tengah template
            'view_data' => $data              // Variabel data yang dikirim ke view tersebut
        ];

        $this->load->view('layouts/template', $template_data);
    }
}