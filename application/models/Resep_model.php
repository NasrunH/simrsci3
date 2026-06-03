<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resep_model extends CI_Model {

    // ==========================================
    // FUNGSI UNTUK PAGINATION, SEARCH & FILTER
    // ==========================================
    public function get_all_resep_paginated($limit, $start, $keyword = null, $tanggal = null) {
        $this->db->select('resep.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis, dokter.nama_dokter');
        $this->db->from('resep');
        $this->db->join('pasien', 'pasien.id_pasien = resep.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');

        // Filter Pencarian (Search by Nama Pasien atau No RM) - Case Insensitive
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->where("LOWER(pasien.nama_lengkap) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->or_where("LOWER(pasien.no_rekam_medis) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->group_end();
        }

        // Filter Tanggal
        if (!empty($tanggal)) {
            $this->db->where('DATE(resep.tanggal_resep)', $tanggal);
        }

        $this->db->order_by('resep.tanggal_resep', 'DESC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_all_resep($keyword = null, $tanggal = null) {
        $this->db->from('resep');
        $this->db->join('pasien', 'pasien.id_pasien = resep.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->where("LOWER(pasien.nama_lengkap) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->or_where("LOWER(pasien.no_rekam_medis) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->group_end();
        }

        if (!empty($tanggal)) {
            $this->db->where('DATE(resep.tanggal_resep)', $tanggal);
        }

        return $this->db->count_all_results();
    }

    public function get_resep_by_dokter_paginated($id_dokter, $limit, $start, $keyword = null, $tanggal = null) {
        $this->db->select('resep.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis');
        $this->db->from('resep');
        $this->db->join('pasien', 'pasien.id_pasien = resep.id_pasien');
        $this->db->where('resep.id_dokter', $id_dokter);

        // Filter Pencarian - Case Insensitive
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->where("LOWER(pasien.nama_lengkap) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->or_where("LOWER(pasien.no_rekam_medis) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->group_end();
        }

        // Filter Tanggal
        if (!empty($tanggal)) {
            $this->db->where('DATE(resep.tanggal_resep)', $tanggal);
        }

        $this->db->order_by('resep.tanggal_resep', 'DESC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_resep_by_dokter($id_dokter, $keyword = null, $tanggal = null) {
        $this->db->from('resep');
        $this->db->join('pasien', 'pasien.id_pasien = resep.id_pasien');
        $this->db->where('resep.id_dokter', $id_dokter);

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->where("LOWER(pasien.nama_lengkap) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->or_where("LOWER(pasien.no_rekam_medis) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->group_end();
        }

        if (!empty($tanggal)) {
            $this->db->where('DATE(resep.tanggal_resep)', $tanggal);
        }

        return $this->db->count_all_results();
    }

    public function get_resep_by_pasien_paginated($id_pasien, $limit, $start, $keyword = null, $tanggal = null) {
        $this->db->select('resep.*, dokter.nama_dokter');
        $this->db->from('resep');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');
        $this->db->where('resep.id_pasien', $id_pasien);

        // Filter Tanggal
        if (!empty($tanggal)) {
            $this->db->where('DATE(resep.tanggal_resep)', $tanggal);
        }

        $this->db->order_by('resep.tanggal_resep', 'DESC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_resep_by_pasien($id_pasien, $keyword = null, $tanggal = null) {
        $this->db->from('resep');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');
        $this->db->where('resep.id_pasien', $id_pasien);

        if (!empty($tanggal)) {
            $this->db->where('DATE(resep.tanggal_resep)', $tanggal);
        }

        return $this->db->count_all_results();
    }

    // ==========================================
    // FUNGSI BAWAAN (Non-Pagination)
    // ==========================================
    public function get_all_resep() {
        $this->db->select('resep.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis, dokter.nama_dokter');
        $this->db->from('resep');
        $this->db->join('pasien', 'pasien.id_pasien = resep.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');
        $this->db->order_by('resep.tanggal_resep', 'DESC');
        return $this->db->get()->result();
    }

    public function get_resep_by_pasien($id_pasien) {
        $this->db->select('resep.*, dokter.nama_dokter');
        $this->db->from('resep');
        $this->db->join('dokter', 'dokter.id_dokter = resep.id_dokter');
        $this->db->where('resep.id_pasien', $id_pasien);
        $this->db->order_by('resep.tanggal_resep', 'DESC');
        return $this->db->get()->result();
    }

    public function get_resep_by_dokter($id_dokter) {
        $this->db->select('resep.*, pasien.nama_lengkap as nama_pasien');
        $this->db->from('resep');
        $this->db->join('pasien', 'pasien.id_pasien = resep.id_pasien');
        $this->db->where('resep.id_dokter', $id_dokter);
        $this->db->order_by('resep.tanggal_resep', 'DESC');
        return $this->db->get()->result();
    }

    public function get_detail_resep($id_resep) {
        $this->db->select('detail_resep.*, obat.nama_obat, obat.kode_obat');
        $this->db->from('detail_resep');
        $this->db->join('obat', 'obat.id_obat = detail_resep.id_obat');
        $this->db->where('detail_resep.id_resep', $id_resep);
        return $this->db->get()->result();
    }

    // Insert menggunakan Database Transaction agar aman jika proses detail gagal
    public function insert_resep_lengkap($data_resep, $data_detail) {
        $this->db->trans_start(); // Mulai Transaksi
        
        // 1. Simpan Header Resep
        $this->db->insert('resep', $data_resep);
        $id_resep = $this->db->insert_id();

        // 2. Simpan Detail Resep dan kurangi stok obat
        foreach ($data_detail as $detail) {
            $detail['id_resep'] = $id_resep;
            $this->db->insert('detail_resep', $detail);
            
            // Kurangi stok di tabel obat (Opsional, tergantung aturan bisnis Anda)
            $this->db->set('stok', 'stok - ' . (int)$detail['jumlah'], FALSE);
            $this->db->where('id_obat', $detail['id_obat']);
            $this->db->update('obat');
        }

        $this->db->trans_complete(); // Selesaikan Transaksi

        if ($this->db->trans_status() === FALSE) {
            return false; // Rollback jika ada yang gagal
        }
        return $id_resep;
    }
}