<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Live_board extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Layanan_model');
    }

    public function index() {
        $data['title'] = 'SIRS MEDIKA - Monitor Antrean Utama';
        $this->load->view('live_board/index', $data);
    }

    // API JSON untuk auto-polling AJAX di TV (Menarik Nama Pasien)
    public function get_active_queues_ajax() {
        $tanggal_hari_ini = date('Y-m-d');
        
        $this->db->order_by('nama_layanan', 'ASC');
        $layanan = $this->db->get('layanan')->result();
        
        $result = [];
        $latest_called_anywhere = null;
        $latest_id = 0; 

        foreach ($layanan as $l) {
            // TARIK DATA ANTREAN + DOKTER + NAMA PASIEN AKTIF
            $this->db->select('antrean.id_antrean, antrean.no_antrean, dokter.nama_dokter, pasien.nama_lengkap as nama_pasien');
            $this->db->from('antrean');
            $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
            $this->db->join('pasien', 'pasien.id_pasien = antrean.id_pasien');
            $this->db->where('antrean.id_layanan', $l->id_layanan);
            $this->db->where('antrean.tanggal_antrean', $tanggal_hari_ini);
            $this->db->where('antrean.status', 'Diperiksa');
            $this->db->order_by('antrean.id_antrean', 'DESC'); 
            $active = $this->db->get()->row();

            // Hitung sisa antrean
            $this->db->where('id_layanan', $l->id_layanan);
            $this->db->where('tanggal_antrean', $tanggal_hari_ini);
            $this->db->where('status', 'Menunggu');
            $waiting_count = $this->db->count_all_results('antrean');

            // Ambil nomor pendaftaran terakhir
            $this->db->select('no_antrean');
            $this->db->where('id_layanan', $l->id_layanan);
            $this->db->where('tanggal_antrean', $tanggal_hari_ini);
            $this->db->order_by('no_antrean', 'DESC');
            $last_registered = $this->db->get('antrean')->row();

            $sedang_diperiksa = $active ? $active->no_antrean : '-';

            $result[] = [
                'id_layanan'       => $l->id_layanan,
                'nama_layanan'     => $l->nama_layanan,
                'tarif'            => number_format($l->tarif, 0, ',', '.'),
                'dokter_bertugas'  => $active ? $active->nama_dokter : 'Dokter Bersiap',
                'sedang_diperiksa' => $sedang_diperiksa,
                'nama_pasien'      => $active ? $active->nama_pasien : 'Tidak ada panggilan',
                'sisa_antrean'     => $waiting_count,
                'antrean_terakhir' => $last_registered ? $last_registered->no_antrean : 0 
            ];

            if ($active && ($active->id_antrean > $latest_id)) {
                $latest_id = $active->id_antrean;
                $latest_called_anywhere = [
                    'no_antrean'   => $active->no_antrean,
                    'nama_layanan' => $l->nama_layanan,
                    'nama_dokter'  => $active->nama_dokter,
                    'nama_pasien'  => $active->nama_pasien // Nama pasien terpanggil terbaru
                ];
            }
        }

        echo json_encode([
            'layanan'        => $result,
            'panggilan_baru' => $latest_called_anywhere
        ]);
    }
}