<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

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

    // FUNGSI BARU: Insert User dan Pasien sekaligus dengan Transaction
    public function insert_with_user($data_user, $data_pasien) {
        $this->db->trans_start(); // Mulai Transaksi

        // 1. Simpan ke tabel Users
        // Hash password menggunakan bcrypt bawaan PHP
        $data_user['password'] = password_hash($data_user['password'], PASSWORD_DEFAULT);
        $this->db->insert('users', $data_user);
        $user_id = $this->db->insert_id(); // Ambil ID user yang baru dibuat

        // 2. Simpan ke tabel Pasien
        if (!isset($data_pasien['no_rekam_medis'])) {
            $data_pasien['no_rekam_medis'] = $this->generate_no_rm();
        }
        $data_pasien['id_user'] = $user_id; // Hubungkan dengan akun yang baru dibuat
        $this->db->insert('pasien', $data_pasien);

        $this->db->trans_complete(); // Selesaikan Transaksi

        return $this->db->trans_status(); // Mengembalikan TRUE jika berhasil, FALSE jika gagal
    }

    public function update($id, $data) {
        $this->db->where('id_pasien', $id);
        return $this->db->update('pasien', $data);
    }

    public function delete($id) {
        // Karena Foreign Key di DB menggunakan ON DELETE CASCADE (opsional di tabel user)
        // Sebaiknya hapus dari user, maka pasien otomatis terhapus jika direlasikan.
        // Namun di sini kita hapus pasiennya, atau hapus usernya juga.
        
        $pasien = $this->get_by_id($id);
        if ($pasien) {
            $this->db->trans_start();
            $this->db->where('id_pasien', $id)->delete('pasien');
            $this->db->where('id_user', $pasien->id_user)->delete('users'); // Hapus akunnya juga
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
        return false;
    }

    // Logika Generate RM Otomatis
    private function generate_no_rm() {
        $tahunBulan = date('Ym'); // Output: 202606
        
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