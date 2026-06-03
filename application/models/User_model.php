<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Fungsi untuk mengambil data dengan Paginasi, Search, dan Filter
    public function get_paginated($limit, $start, $keyword = null, $role_id = null) {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');

        if (!empty($keyword)) {
            $this->db->like('users.username', $keyword);
        }

        if (!empty($role_id)) {
            $this->db->where('users.role_id', $role_id);
        }

        $this->db->order_by('users.id_user', 'DESC'); // Tampilkan yang terbaru di atas
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    // Fungsi menghitung total baris untuk Pagination CI3
    public function count_all_results($keyword = null, $role_id = null) {
        $this->db->from('users');

        if (!empty($keyword)) {
            $this->db->like('username', $keyword);
        }

        if (!empty($role_id)) {
            $this->db->where('role_id', $role_id);
        }

        return $this->db->count_all_results();
    }

    public function get_all() {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->where('users.id_user', $id);
        return $this->db->get()->row();
    }

    public function get_by_username($username) {
        $this->db->select('users.*, roles.name as role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id', 'left');
        $this->db->where('users.username', $username);
        return $this->db->get()->row();
    }

    public function insert($data) {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }
     public function insert_with_profile($data_user, $role_name, $data_profile) {
        $this->db->trans_start(); // Mulai Transaksi

        // 1. Simpan ke tabel users
        $data_user['password'] = password_hash($data_user['password'], PASSWORD_DEFAULT);
        $this->db->insert('users', $data_user);
        $user_id = $this->db->insert_id(); // Dapatkan ID user yang baru terdaftar

        // 2. Masukkan ID User ke data profil
        $data_profile['id_user'] = $user_id;

        // 3. Simpan ke tabel spesifik berdasarkan Role
        if (strtolower($role_name) === 'admin') {
            $this->db->insert('admin', $data_profile);
            
        } elseif (strtolower($role_name) === 'dokter') {
            $this->db->insert('dokter', $data_profile);
            
        } elseif (strtolower($role_name) === 'pasien') {
            // Jika pasien, generate nomor RM
            if (!isset($data_profile['no_rekam_medis'])) {
                $data_profile['no_rekam_medis'] = $this->generate_no_rm();
            }
            $this->db->insert('pasien', $data_profile);
        }

        $this->db->trans_complete(); // Selesaikan Transaksi
        return $this->db->trans_status();
    }

    // Fungsi bantuan untuk nomor RM Pasien
    private function generate_no_rm() {
        $tahunBulan = date('Ym');
        $this->db->like('no_rekam_medis', 'RM-'.$tahunBulan, 'after');
        $this->db->order_by('id_pasien', 'DESC');
        $pasienTerakhir = $this->db->get('pasien')->row();

        if ($pasienTerakhir) {
            $nomorTerakhir = (int) substr($pasienTerakhir->no_rekam_medis, -4);
            $nomorBaru = str_pad($nomorTerakhir + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nomorBaru = '0001';
        }
        return 'RM-' . $tahunBulan . '-' . $nomorBaru;
    }


    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            // Hapus key password dari array jika kosong (agar tidak mengubah password lama)
            unset($data['password']); 
        }
        $this->db->where('id_user', $id);
        return $this->db->update('users', $data);
    }

    public function delete($id) {
        $this->db->where('id_user', $id);
        return $this->db->delete('users');
    }
}