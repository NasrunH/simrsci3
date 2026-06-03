<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model {

    // Contoh: Laporan Pendapatan Berdasarkan Tanggal
    public function get_pendapatan_by_date($start_date, $end_date) {
        $this->db->select('tanggal_resep, COUNT(id_resep) as total_transaksi, SUM(total_harga) as total_pendapatan');
        $this->db->from('resep');
        $this->db->where('tanggal_resep >=', $start_date);
        $this->db->where('tanggal_resep <=', $end_date);
        $this->db->group_by('tanggal_resep');
        $this->db->order_by('tanggal_resep', 'ASC');
        
        return $this->db->get()->result();
    }

    // Contoh: Laporan Obat Paling Banyak Diresepkan
    public function get_obat_terlaris($limit = 10) {
        $this->db->select('obat.nama_obat, SUM(detail_resep.jumlah) as total_terjual');
        $this->db->from('detail_resep');
        $this->db->join('obat', 'obat.id_obat = detail_resep.id_obat');
        $this->db->group_by('detail_resep.id_obat, obat.nama_obat');
        $this->db->order_by('total_terjual', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    // Total keseluruhan untuk ringkasan di halaman atas (Dashboard)
    public function get_ringkasan_hari_ini() {
        $hari_ini = date('Y-m-d');
        
        $this->db->select('COUNT(id_resep) as jumlah_resep, SUM(total_harga) as pendapatan');
        $this->db->where('tanggal_resep', $hari_ini);
        return $this->db->get('resep')->row();
    }
}