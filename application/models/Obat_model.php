<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat_model extends CI_Model {

    // Ambil data obat dengan paginasi, relasi supplier, dan filter pencarian
    public function get_paginated($limit, $start, $keyword = null) {
        $this->db->select('obat.*, supplier.nama_supplier');
        $this->db->from('obat');
        $this->db->join('supplier', 'supplier.id_supplier = obat.id_supplier', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('obat.kode_obat', $keyword);
            $this->db->or_like('obat.nama_obat', $keyword);
            $this->db->or_like('obat.kategori', $keyword);
            $this->db->or_like('supplier.nama_supplier', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('obat.nama_obat', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    // Hitung jumlah baris data obat untuk saringan paginasi
    public function count_all_results($keyword = null) {
        $this->db->from('obat');
        $this->db->join('supplier', 'supplier.id_supplier = obat.id_supplier', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('obat.kode_obat', $keyword);
            $this->db->or_like('obat.nama_obat', $keyword);
            $this->db->or_like('supplier.nama_supplier', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_all() {
        $this->db->order_by('nama_obat', 'ASC');
        return $this->db->get('obat')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('obat', ['id_obat' => $id])->row();
    }

    // ====================================================================
    // FITUR BARU: Mengambil histori daftar satuan obat unik (DISTINCT)
    // ====================================================================
    public function get_distinct_satuan() {
        $this->db->select('DISTINCT(satuan) as satuan');
        $this->db->from('obat');
        $this->db->where('satuan IS NOT NULL');
        $this->db->where("satuan != ''");
        $this->db->order_by('satuan', 'ASC');
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert('obat', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id_obat', $id)->update('obat', $data);
    }

    public function delete($id) {
        return $this->db->where('id_obat', $id)->delete('obat');
    }
}