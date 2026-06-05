<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_laporan');
        $this->load->model('Laporan_model');
    }

    public function index() {
        $preset = $this->input->get('preset', TRUE);
        $start_date = $this->input->get('start_date', TRUE);
        $end_date   = $this->input->get('end_date', TRUE);

        if ($preset === 'hari_ini') {
            $start_date = $end_date = date('Y-m-d');
        } elseif ($preset === 'minggu_ini') {
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date   = date('Y-m-d', strtotime('sunday this week'));
        } elseif ($preset === 'bulan_ini' || empty($start_date)) {
            $start_date = date('Y-m-01');
            $end_date   = date('Y-m-t');
        }

        if (empty($end_date)) {
            $end_date = date('Y-m-t');
        }
        if (empty($start_date)) {
            $start_date = date('Y-m-01');
        }
        if (strtotime($start_date) > strtotime($end_date)) {
            $tmp = $start_date;
            $start_date = $end_date;
            $end_date = $tmp;
        }

        $laporan = $this->Laporan_model->get_pendapatan_by_date($start_date, $end_date);
        $billing_harian = $this->Laporan_model->get_billing_harian($start_date, $end_date);

        $total_transaksi = 0;
        $total_pendapatan = 0;
        foreach ($laporan as $l) {
            $total_transaksi += (int) $l->total_transaksi;
            $total_pendapatan += (float) $l->total_pendapatan;
        }

        $ringkasan = $this->Laporan_model->get_ringkasan_periode($start_date, $end_date);
        $perbandingan = $this->Laporan_model->get_perbandingan_periode($start_date, $end_date);
        $obat_terlaris = $this->Laporan_model->get_obat_terlaris_periode($start_date, $end_date, 12);
        $dokter_teramai = $this->Laporan_model->get_dokter_teramai_periode($start_date, $end_date, 8);
        $kunjungan_layanan = $this->Laporan_model->get_kunjungan_per_layanan_periode($start_date, $end_date, 10);
        $chart_series = $this->Laporan_model->series_from_laporan_harian($laporan, $billing_harian);
        $chart_antrean = $this->Laporan_model->chart_antrean_periode($start_date, $end_date);

        $layanan_labels = [];
        $layanan_data = [];
        foreach ($kunjungan_layanan as $k) {
            $layanan_labels[] = $k->nama_layanan ?: 'Tanpa Poli';
            $layanan_data[] = (int) $k->jumlah;
        }

        $obat_labels = [];
        $obat_qty = [];
        $obat_nilai = [];
        foreach ($obat_terlaris as $ot) {
            $obat_labels[] = $ot->nama_obat;
            $obat_qty[] = (int) $ot->total_terjual;
            $obat_nilai[] = (float) ($ot->nilai_penjualan ?? 0);
        }

        $dokter_labels = [];
        $dokter_resep = [];
        $dokter_pendapatan = [];
        foreach ($dokter_teramai as $d) {
            $dokter_labels[] = $d->nama_dokter;
            $dokter_resep[] = (int) $d->jumlah_resep;
            $dokter_pendapatan[] = (float) $d->pendapatan;
        }

        $data = [
            'title'              => 'Laporan & Analitik',
            'start_date'         => $start_date,
            'end_date'           => $end_date,
            'preset'             => $preset,
            'laporan'            => $laporan,
            'total_transaksi'    => $total_transaksi,
            'total_pendapatan'   => $total_pendapatan,
            'ringkasan'          => $ringkasan,
            'perbandingan'       => $perbandingan,
            'obat_terlaris'      => $obat_terlaris,
            'dokter_teramai'     => $dokter_teramai,
            'kunjungan_layanan'  => $kunjungan_layanan,
            'chart_series'       => $chart_series,
            'chart_antrean'      => $chart_antrean,
            'chart_layanan'      => ['labels' => $layanan_labels, 'data' => $layanan_data],
            'chart_obat'         => ['labels' => $obat_labels, 'qty' => $obat_qty, 'nilai' => $obat_nilai],
            'chart_dokter'       => ['labels' => $dokter_labels, 'resep' => $dokter_resep, 'pendapatan' => $dokter_pendapatan],
        ];

        $this->load->view('layouts/template', [
            'view_name' => 'laporan/index',
            'view_data' => $data,
        ]);
    }
}
