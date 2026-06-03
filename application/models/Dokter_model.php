<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter_model extends CI_Model {

    public function get_all() {
        return $this->db->get('dokter')->result();
    }

    public function get_by_id($id) {
        $this->db->where('id_dokter', $id);
        return $this->db->get('dokter')->row();
    }

    public function get_by_user_id($user_id) {
        $this->db->where('id_user', $user_id);
        return $this->db->get('dokter')->row();
    }

    // FUNGSI BARU: Insert User dan Dokter sekaligus dengan Transaction
    public function insert_with_user($data_user, $data_dokter) {
        $this->db->trans_start(); // Mulai Transaksi

        // 1. Simpan ke tabel Users (Role ID 2 = Dokter)
        $data_user['password'] = password_hash($data_user['password'], PASSWORD_DEFAULT);
        $this->db->insert('users', $data_user);
        $user_id = $this->db->insert_id(); // Ambil ID user yang baru dibuat

        // 2. Simpan ke tabel Dokter
        $data_dokter['id_user'] = $user_id; // Hubungkan dengan akun user
        $this->db->insert('dokter', $data_dokter);

        $this->db->trans_complete(); // Selesaikan Transaksi

        return $this->db->trans_status();
    }

    public function update($id, $data) {
        $this->db->where('id_dokter', $id);
        return $this->db->update('dokter', $data);
    }

    // Update Delete agar menghapus akun usernya juga
    public function delete($id) {
        $dokter = $this->get_by_id($id);
        if ($dokter) {
            $this->db->trans_start();
            $this->db->where('id_dokter', $id)->delete('dokter');
            $this->db->where('id_user', $dokter->id_user)->delete('users');
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }
}