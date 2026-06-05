<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan_model extends CI_Model {

    // Mengambil data ber-paginasi beserta pencarian kata kunci
    public function get_paginated($limit, $start, $keyword = null) {
        $this->db->select('*');
        $this->db->from('layanan');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_layanan', $keyword);
            $this->db->or_like('deskripsi', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('nama_layanan', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Menghitung jumlah baris data layanan untuk paginasi
    public function count_all_results($keyword = null) {
        $this->db->from('layanan');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_layanan', $keyword);
            $this->db->or_like('deskripsi', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Fungsi get_all yang dicari oleh Controller Antrean & Dokter
    public function get_all() {
        $this->db->order_by('nama_layanan', 'ASC');
        return $this->db->get('layanan')->result();
    }

    // Mengambil satu data layanan berdasarkan ID
    public function get_by_id($id) {
        return $this->db->get_where('layanan', ['id_layanan' => $id])->row();
    }

    // Menambahkan data layanan baru
    public function insert($data) {
        return $this->db->insert('layanan', $data);
    }

    // Mengubah data layanan berdasarkan ID
    public function update($id, $data) {
        return $this->db->where('id_layanan', $id)->update('layanan', $data);
    }

    // Menghapus data layanan berdasarkan ID
    public function delete($id) {
        return $this->db->where('id_layanan', $id)->delete('layanan');
    }
}