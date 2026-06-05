<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrean_model extends CI_Model {

    public function get_paginated($limit, $start, $tanggal = null, $id_dokter = null, $id_pasien = null) {
        $this->db->select('antrean.*, pasien.nama_lengkap, pasien.no_rekam_medis, dokter.nama_dokter, dokter.spesialisasi');
        $this->db->from('antrean');
        $this->db->join('pasien', 'pasien.id_pasien = antrean.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');

        // Default filter ke hari ini jika tidak ada tanggal spesifik
        if (!empty($tanggal)) {
            $this->db->where('tanggal_antrean', $tanggal);
        }

        if (!empty($id_dokter)) $this->db->where('antrean.id_dokter', $id_dokter);
        if (!empty($id_pasien)) $this->db->where('antrean.id_pasien', $id_pasien);

        // Urutkan berdasarkan nomor antrean
        $this->db->order_by('tanggal_antrean', 'DESC');
        $this->db->order_by('no_antrean', 'ASC');
        
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function count_all_results($tanggal = null, $id_dokter = null, $id_pasien = null) {
        $this->db->from('antrean');
        if (!empty($tanggal)) $this->db->where('tanggal_antrean', $tanggal);
        if (!empty($id_dokter)) $this->db->where('id_dokter', $id_dokter);
        if (!empty($id_pasien)) $this->db->where('id_pasien', $id_pasien);
        return $this->db->count_all_results();
    }

    // Mengubah status antrean (Menunggu -> Diperiksa -> Selesai)
    public function update_status($id, $status) {
        $this->db->where('id_antrean', $id);
        return $this->db->update('antrean', ['status' => $status]);
    }

    // Fungsi otomatis membuat nomor antrean berurutan per hari per dokter
    public function generate_nomor($id_dokter, $tanggal) {
        $this->db->where('id_dokter', $id_dokter);
        $this->db->where('tanggal_antrean', $tanggal);
        $jumlah = $this->db->count_all_results('antrean');
        
        return $jumlah + 1; // Jika ada 5 orang, dia dapat nomor 6
    }

    public function insert($data) {
        $data['no_antrean'] = $this->generate_nomor($data['id_dokter'], $data['tanggal_antrean']);
        $this->db->insert('antrean', $data);
        return $this->db->insert_id();
    }
}