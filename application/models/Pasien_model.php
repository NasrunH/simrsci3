<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

    // ==========================================
    // FUNGSI UNTUK PAGINATION, SEARCH & FILTER
    // ==========================================
    public function get_paginated($limit, $start, $keyword = null, $jenis_kelamin = null) {
        $this->db->select('*');
        $this->db->from('pasien');

        // Filter Pencarian (Search by Nama atau No RM) - Case Insensitive
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->where("LOWER(nama_lengkap) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->or_where("LOWER(no_rekam_medis) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->group_end();
        }

        // Filter Jenis Kelamin
        if (!empty($jenis_kelamin)) {
            $this->db->where('jenis_kelamin', $jenis_kelamin);
        }

        $this->db->order_by('id_pasien', 'DESC'); // Tampilkan data terbaru di atas
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_all_results($keyword = null, $jenis_kelamin = null) {
        $this->db->from('pasien');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->where("LOWER(nama_lengkap) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->or_where("LOWER(no_rekam_medis) LIKE LOWER('%".$this->db->escape_like_str($keyword)."%')", NULL, FALSE);
            $this->db->group_end();
        }

        if (!empty($jenis_kelamin)) {
            $this->db->where('jenis_kelamin', $jenis_kelamin);
        }

        return $this->db->count_all_results();
    }

    // ==========================================
    // FUNGSI CRUD STANDAR BAWAAN
    // ==========================================
    public function get_all() {
        return $this->db->get('pasien')->result();
    }

    public function get_by_id($id) {
        $this->db->where('id_pasien', $id);
        return $this->db->get('pasien')->row();
    }
    
    public function get_by_user_id($user_id) {
        $this->db->where('id_user', $user_id);
        return $this->db->get('pasien')->row();
    }

    public function insert_with_user($data_user, $data_pasien) {
        $this->db->trans_start(); 
        
        $data_user['password'] = password_hash($data_user['password'], PASSWORD_DEFAULT);
        $this->db->insert('users', $data_user);
        $user_id = $this->db->insert_id(); 

        if (!isset($data_pasien['no_rekam_medis'])) {
            $data_pasien['no_rekam_medis'] = $this->generate_no_rm();
        }
        $data_pasien['id_user'] = $user_id; 
        $this->db->insert('pasien', $data_pasien);

        $this->db->trans_complete(); 
        return $this->db->trans_status(); 
    }

    public function update($id, $data) {
        $this->db->where('id_pasien', $id);
        return $this->db->update('pasien', $data);
    }

    public function delete($id) {
        $pasien = $this->get_by_id($id);
        if ($pasien) {
            $this->db->trans_start();
            $this->db->where('id_pasien', $id)->delete('pasien');
            $this->db->where('id_user', $pasien->id_user)->delete('users');
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }

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
}