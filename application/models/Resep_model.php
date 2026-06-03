<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resep_model extends CI_Model {

    // Ambil resep beserta relasi nama pasien dan dokter
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