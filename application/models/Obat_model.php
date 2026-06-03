<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat_model extends CI_Model {

    public function get_all() {
        return $this->db->get('obat')->result();
    }

    public function get_by_id($id) {
        $this->db->where('id_obat', $id);
        return $this->db->get('obat')->row();
    }

    public function insert($data) {
        return $this->db->insert('obat', $data);
    }

    public function update($id, $data) {
        $this->db->where('id_obat', $id);
        return $this->db->update('obat', $data);
    }

    public function delete($id) {
        $this->db->where('id_obat', $id);
        return $this->db->delete('obat');
    }

        // Fungsi untuk mengambil data dengan Paginasi, Search, dan Filter
    public function get_paginated($limit, $start, $keyword = null, $kategori = null) {
        $this->db->select('*');
        $this->db->from('obat');

        // Jika ada pencarian (Search)
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_obat', $keyword);
            $this->db->or_like('kode_obat', $keyword);
            $this->db->group_end();
        }

        // Jika ada filter (Kategori)
        if (!empty($kategori)) {
            $this->db->where('kategori', $kategori);
        }

        $this->db->order_by('nama_obat', 'ASC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    // Fungsi untuk menghitung total data (untuk konfigurasi pagination CI3)
    public function count_all_results($keyword = null, $kategori = null) {
        $this->db->from('obat');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_obat', $keyword);
            $this->db->or_like('kode_obat', $keyword);
            $this->db->group_end();
        }

        if (!empty($kategori)) {
            $this->db->where('kategori', $kategori);
        }

        return $this->db->count_all_results();
    }

    // Kurangi stok setelah diresepkan
    public function kurangi_stok($id_obat, $jumlah) {
        $this->db->set('stok', 'stok - ' . (int)$jumlah, FALSE);
        $this->db->where('id_obat', $id_obat);
        return $this->db->update('obat');
    }
}