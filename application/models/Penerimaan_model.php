<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_model extends CI_Model {

    public function get_paginated($limit, $start, $keyword = null) {
        $this->db->select('penerimaan_obat.*, supplier.nama_supplier, users.username as nama_petugas');
        $this->db->from('penerimaan_obat');
        $this->db->join('supplier', 'supplier.id_supplier = penerimaan_obat.id_supplier');
        $this->db->join('users', 'users.id_user = penerimaan_obat.id_user', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('penerimaan_obat.no_faktur', $keyword);
            $this->db->or_like('supplier.nama_supplier', $keyword);
            $this->db->or_like('penerimaan_obat.catatan', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('penerimaan_obat.tanggal_penerimaan', 'DESC');
        $this->db->order_by('penerimaan_obat.id_penerimaan', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function count_all_results($keyword = null) {
        $this->db->from('penerimaan_obat');
        $this->db->join('supplier', 'supplier.id_supplier = penerimaan_obat.id_supplier');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('penerimaan_obat.no_faktur', $keyword);
            $this->db->or_like('supplier.nama_supplier', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->select('penerimaan_obat.*, supplier.nama_supplier, supplier.no_telp as telp_supplier, supplier.alamat as alamat_supplier, users.username as nama_petugas');
        $this->db->from('penerimaan_obat');
        $this->db->join('supplier', 'supplier.id_supplier = penerimaan_obat.id_supplier');
        $this->db->join('users', 'users.id_user = penerimaan_obat.id_user', 'left');
        $this->db->where('penerimaan_obat.id_penerimaan', $id);
        return $this->db->get()->row();
    }

    public function get_detail_penerimaan($id_penerimaan) {
        $this->db->select('penerimaan_obat_detail.*, obat.nama_obat, obat.kode_obat');
        $this->db->from('penerimaan_obat_detail');
        $this->db->join('obat', 'obat.id_obat = penerimaan_obat_detail.id_obat');
        $this->db->where('penerimaan_obat_detail.id_penerimaan', $id_penerimaan);
        return $this->db->get()->result();
    }

    public function get_all_suppliers() {
        $this->db->order_by('nama_supplier', 'ASC');
        return $this->db->get('supplier')->result();
    }

    // ====================================================================
    // SIMPAN TRANSAKSI & UPDATE RELASI SUPPLIER OBAT SECARA OTOMATIS
    // ====================================================================
    public function insert_transaksi($data_penerimaan, $detail_items, $update_main_supplier = false) {
        $this->db->trans_start(); // Memulai transaksi database (Aman dari data korup)

        // 1. Simpan data master transaksi penerimaan
        $this->db->insert('penerimaan_obat', $data_penerimaan);
        $id_penerimaan = $this->db->insert_id();

        $id_supplier_transaksi = (int)$data_penerimaan['id_supplier'];

        // 2. Loop detail item obat yang masuk
        foreach ($detail_items as $item) {
            $item['id_penerimaan'] = $id_penerimaan;
            $this->db->insert('penerimaan_obat_detail', $item);

            // 3. Tambahkan jumlah stok fisik obat
            $this->db->set('stok', 'stok + ' . (float)$item['jumlah'], FALSE);

            // 4. Update Relasi Supplier Utama di tabel obat secara pintar
            if ($update_main_supplier) {
                // Paksa update supplier utama obat ke supplier transaksi saat ini
                $this->db->set('id_supplier', $id_supplier_transaksi);
            } else {
                // Hanya update jika kolom id_supplier di tabel obat masih kosong (NULL)
                $this->db->set('id_supplier', "COALESCE(id_supplier, " . $id_supplier_transaksi . ")", FALSE);
            }

            $this->db->where('id_obat', $item['id_obat']);
            $this->db->update('obat');
        }

        $this->db->trans_complete(); 

        if ($this->db->trans_status() === FALSE) {
            return false; // Rollback otomatis jika ada query yang gagal
        }

        return $id_penerimaan;
    }
}