<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    // Fungsi untuk Pagination dan Search
    public function get_paginated($limit, $start, $keyword = null) {
        $this->db->select('*');
        $this->db->from('roles');

        if (!empty($keyword)) {
            $this->db->like('name', $keyword);
        }

        $this->db->order_by('id', 'ASC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_all_results($keyword = null) {
        $this->db->from('roles');
        if (!empty($keyword)) {
            $this->db->like('name', $keyword);
        }
        return $this->db->count_all_results();
    }

    public function get_all() {
        return $this->db->get('roles')->result();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('roles')->row();
    }

    public function insert($data) {
        // Karena postgres menggunakan nama tabel dengan schema public (biasanya otomatis)
        return $this->db->insert('roles', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('roles', $data);
    }

    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete('roles');
    }




    // ========================================================
    // FUNGSI BARU UNTUK MANAJEMEN PERMISSION
    // ========================================================

    // 1. Ambil semua master permissions di sistem
    public function get_all_permissions() {
        return $this->db->order_by('name', 'ASC')->get('permissions')->result();
    }

    // 2. Ambil hanya ID permission yang dimiliki sebuah role (untuk form edit)
    public function get_role_permission_ids($role_id) {
        $this->db->where('role_id', $role_id);
        $query = $this->db->get('role_has_permissions')->result_array();
        
        // Ekstrak hanya kolom 'permission_id' menjadi single array: [1, 2, 5, 8]
        return array_column($query, 'permission_id'); 
    }

    // 3. Update permissions untuk role tertentu dengan Database Transaction
    public function sync_permissions($role_id, $permission_ids = []) {
        $this->db->trans_start();

        // Hapus semua hak akses lama untuk role ini
        $this->db->where('role_id', $role_id)->delete('role_has_permissions');

        // Masukkan hak akses baru (jika ada yang dicentang)
        if (!empty($permission_ids)) {
            $data_insert = [];
            foreach ($permission_ids as $p_id) {
                $data_insert[] = [
                    'role_id' => $role_id,
                    'permission_id' => $p_id
                ];
            }
            // Gunakan insert_batch agar lebih efisien (1x query)
            $this->db->insert_batch('role_has_permissions', $data_insert);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Fungsi untuk mendapatkan permission dari role tertentu
    public function get_role_permissions($role_id) {
        $this->db->select('permissions.name');
        $this->db->from('role_has_permissions');
        $this->db->join('permissions', 'permissions.id = role_has_permissions.permission_id');
        $this->db->where('role_has_permissions.role_id', $role_id);
        
        $query = $this->db->get();
        $permissions = [];
        foreach ($query->result() as $row) {
            $permissions[] = $row->name;
        }
        return $permissions; 
    }
}