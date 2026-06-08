<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {

    // Ambil data supplier dengan batasan paginasi dan filter pencarian
    public function get_paginated($limit, $start, $keyword = null) {
        $this->db->select('*');
        $this->db->from('supplier');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_supplier', $keyword);
            $this->db->or_like('no_telp', $keyword);
            $this->db->or_like('alamat', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('nama_supplier', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Hitung jumlah baris data supplier untuk penyesuaian paginasi
    public function count_all_results($keyword = null) {
        $this->db->from('supplier');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_supplier', $keyword);
            $this->db->or_like('no_telp', $keyword);
            $this->db->or_like('alamat', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    // Ambil seluruh data supplier tanpa batasan limit
    public function get_all() {
        $this->db->order_by('nama_supplier', 'ASC');
        return $this->db->get('supplier')->result();
    }

    // Ambil satu data supplier berdasarkan ID unik
    public function get_by_id($id) {
        return $this->db->get_where('supplier', ['id_supplier' => $id])->row();
    }

    // Tambah supplier baru
    public function insert($data) {
        return $this->db->insert('supplier', $data);
    }

    // Update data supplier berdasarkan ID
    public function update($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id_supplier', $id)->update('supplier', $data);
    }

    // Hapus supplier berdasarkan ID
    public function delete($id) {
        return $this->db->where('id_supplier', $id)->delete('supplier');
    }
}