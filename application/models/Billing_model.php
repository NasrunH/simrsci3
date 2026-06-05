<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {

    // ... (Fungsi get_paginated, count_all_results, get_by_id, dll tetap sama) ...
    public function get_paginated($limit, $start, $keyword = null, $status = null) {
        $this->db->select('billing.*, pasien.nama_lengkap, pasien.no_rekam_medis, users.username as nama_kasir');
        $this->db->from('billing');
        $this->db->join('pasien', 'pasien.id_pasien = billing.id_pasien');
        $this->db->join('users', 'users.id_user = billing.id_kasir', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('billing.no_invoice', $keyword);
            $this->db->or_like('pasien.nama_lengkap', $keyword);
            $this->db->or_like('pasien.no_rekam_medis', $keyword);
            $this->db->group_end();
        }

        if (!empty($status)) $this->db->where('billing.status', $status);

        $this->db->order_by('billing.status', 'ASC'); 
        $this->db->order_by('billing.created_at', 'DESC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_all_results($keyword = null, $status = null) {
        $this->db->from('billing');
        $this->db->join('pasien', 'pasien.id_pasien = billing.id_pasien');
        if (!empty($keyword)) {
            $this->db->group_start(); $this->db->like('billing.no_invoice', $keyword); $this->db->or_like('pasien.nama_lengkap', $keyword); $this->db->group_end();
        }
        if (!empty($status)) $this->db->where('billing.status', $status);
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        // Gabungkan relasi untuk mengambil informasi layanan poliklinik dokter
        $this->db->select('billing.*, pasien.nama_lengkap, pasien.no_rekam_medis, pasien.tanggal_lahir, pasien.jenis_kelamin, dokter.nama_dokter, resep.tanggal_resep, users.username as nama_kasir, layanan.nama_layanan');
        $this->db->from('billing');
        $this->db->join('pasien', 'pasien.id_pasien = billing.id_pasien');
        $this->db->join('resep', 'resep.id_resep = billing.id_resep', 'left');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter', 'left');
        $this->db->join('layanan', 'layanan.id_layanan = dokter.id_layanan', 'left');
        $this->db->join('users', 'users.id_user = billing.id_kasir', 'left');
        $this->db->where('billing.id_billing', $id);
        return $this->db->get()->row();
    }

    // UPDATE PENTING: Kalkulasi Biaya Jasa Dokter secara dinamis dari Poliklinik tempat Dokter bertugas
    public function create_from_resep($id_resep) {
        $resep = $this->db->get_where('resep', ['id_resep' => $id_resep])->row();
        if (!$resep) return false;

        // 1. Ambil data dokter, lalu tarik tarif layanannya
        $this->db->select('dokter.id_layanan, layanan.tarif');
        $this->db->from('dokter');
        $this->db->join('layanan', 'layanan.id_layanan = dokter.id_layanan', 'left');
        $this->db->where('dokter.id_dokter', $resep->id_dokter);
        $layanan_query = $this->db->get()->row();

        // 2. Jika dokter memiliki poliklinik, ambil tarifnya. Jika tidak ada, gunakan fallback Rp 50.000
        $biaya_jasa = ($layanan_query && $layanan_query->tarif) ? (float) $layanan_query->tarif : 50000.00;

        // Ambil ID Rekam Medis (SOAP) terakhir untuk transaksi ini
        $this->db->where('id_pasien', $resep->id_pasien);
        $this->db->where('tanggal_periksa', date('Y-m-d'));
        $this->db->order_by('id_rm', 'DESC');
        $rm = $this->db->get('rekam_medis')->row();

        $biaya_obat = (float) $resep->total_harga;
        $total = $biaya_obat + $biaya_jasa;

        $data_billing = [
            'no_invoice'        => $this->generate_no_invoice(),
            'id_pasien'         => $resep->id_pasien,
            'id_resep'          => $id_resep,
            'id_rm'             => $rm ? $rm->id_rm : null,
            'biaya_obat'        => $biaya_obat,
            'biaya_jasa_dokter' => $biaya_jasa, // Masukkan nominal dinamis
            'total_tagihan'     => $total,
            'status'            => 'Belum Lunas'
        ];

        return $this->db->insert('billing', $data_billing);
    }

    private function generate_no_invoice() {
        $prefix = 'INV-' . date('Ymd') . '-';
        $this->db->like('no_invoice', $prefix, 'after');
        $this->db->order_by('id_billing', 'DESC');
        $terakhir = $this->db->get('billing')->row();

        if ($terakhir) {
            $nomorTerakhir = (int) substr($terakhir->no_invoice, -4);
            $nomorBaru = str_pad($nomorTerakhir + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nomorBaru = '0001';
        }

        return $prefix . $nomorBaru;
    }
}