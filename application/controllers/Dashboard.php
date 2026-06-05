<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Laporan_model');
    }

    public function index() {
        $role = $this->session->userdata('role');
        $permissions = $this->session->userdata('permissions') ?? [];

        $data = [
            'title'       => 'Dashboard Utama',
            'role'        => $role,
            'permissions' => $permissions,
        ];

        $is_admin_dashboard = ($role === 'admin' || in_array('view_laporan', $permissions));

        if ($is_admin_dashboard) {
            $data['admin_dashboard'] = true;
            $data['ringkasan']       = $this->Laporan_model->get_ringkasan_hari_ini();
            $data['statistik']       = $this->Laporan_model->get_statistik_utama();
            $data['trend']           = $this->Laporan_model->get_trend_harian(7);
            $data['obat_terlaris']   = $this->Laporan_model->get_obat_terlaris(8);
            $data['obat_stok_rendah']= $this->Laporan_model->get_obat_stok_rendah(5, 8);
            $data['kunjungan_layanan'] = $this->Laporan_model->get_kunjungan_per_layanan(30);
            $data['aktivitas']       = $this->Laporan_model->get_aktivitas_terbaru(10);

            $data['chart_antrean']   = $this->Laporan_model->chart_data_antrean_status();
            $data['chart_billing']   = $this->Laporan_model->chart_data_billing_status();
            $data['chart_demografi'] = $this->Laporan_model->chart_data_demografi();

            $layanan_labels = [];
            $layanan_data   = [];
            foreach ($data['kunjungan_layanan'] as $k) {
                $layanan_labels[] = $k->nama_layanan ?: 'Tanpa Poli';
                $layanan_data[]   = (int) $k->jumlah;
            }
            $data['chart_layanan'] = [
                'labels' => $layanan_labels,
                'data'   => $layanan_data,
            ];

            $obat_labels = [];
            $obat_data   = [];
            foreach ($data['obat_terlaris'] as $ot) {
                $obat_labels[] = $ot->nama_obat;
                $obat_data[]   = (int) $ot->total_terjual;
            }
            $data['chart_obat'] = [
                'labels' => $obat_labels,
                'data'   => $obat_data,
            ];
        }

        $template_data = [
            'view_name' => 'dashboard/index',
            'view_data' => $data,
        ];

        $this->load->view('layouts/template', $template_data);
    }
}
