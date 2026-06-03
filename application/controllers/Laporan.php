<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Wajib memiliki hak akses view_laporan (Biasanya hanya Admin)
        $this->require_permission('view_laporan');
        $this->load->model('Laporan_model');
    }

    public function index() {
        $data['title'] = 'Laporan Pendapatan & Statistik';
        
        // Tangkap filter tanggal dari URL (GET), default ke bulan ini jika kosong
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date   = $this->input->get('end_date') ?: date('Y-m-t');

        $data['start_date'] = $start_date;
        $data['end_date']   = $end_date;
        
        // Ambil data dari model
        $data['laporan']       = $this->Laporan_model->get_pendapatan_by_date($start_date, $end_date);
        $data['obat_terlaris'] = $this->Laporan_model->get_obat_terlaris(10); // Top 10 Obat
        
        // Hitung akumulasi total untuk ditampilkan di widget atas
        $total_transaksi = 0;
        $total_pendapatan = 0;
        foreach($data['laporan'] as $l) {
            $total_transaksi += $l->total_transaksi;
            $total_pendapatan += $l->total_pendapatan;
        }
        $data['total_transaksi']  = $total_transaksi;
        $data['total_pendapatan'] = $total_pendapatan;

        // Render ke template
        $template_data = [
            'view_name' => 'laporan/index',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }
}