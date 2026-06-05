<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_medis_model extends CI_Model {

    public function get_paginated($limit, $start, $keyword = null, $id_pasien = null, $id_dokter = null) {
        $this->db->select('rekam_medis.*, pasien.nama_lengkap, pasien.no_rekam_medis, dokter.nama_dokter, dokter.spesialisasi');
        $this->db->from('rekam_medis');
        $this->db->join('pasien', 'pasien.id_pasien = rekam_medis.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = rekam_medis.id_dokter');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pasien.nama_lengkap', $keyword);
            $this->db->or_like('pasien.no_rekam_medis', $keyword);
            $this->db->or_like('rekam_medis.diagnosa', $keyword);
            $this->db->group_end();
        }

        if (!empty($id_pasien)) $this->db->where('rekam_medis.id_pasien', $id_pasien);
        if (!empty($id_dokter)) $this->db->where('rekam_medis.id_dokter', $id_dokter);

        $this->db->order_by('rekam_medis.tanggal_periksa', 'DESC');
        $this->db->order_by('rekam_medis.id_rm', 'DESC');
        $this->db->limit($limit, $start);
        
        return $this->db->get()->result();
    }

    public function count_all_results($keyword = null, $id_pasien = null, $id_dokter = null) {
        $this->db->from('rekam_medis');
        $this->db->join('pasien', 'pasien.id_pasien = rekam_medis.id_pasien');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('pasien.nama_lengkap', $keyword);
            $this->db->or_like('pasien.no_rekam_medis', $keyword);
            $this->db->or_like('rekam_medis.diagnosa', $keyword);
            $this->db->group_end();
        }

        if (!empty($id_pasien)) $this->db->where('rekam_medis.id_pasien', $id_pasien);
        if (!empty($id_dokter)) $this->db->where('rekam_medis.id_dokter', $id_dokter);

        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->select('rekam_medis.*, pasien.nama_lengkap, pasien.no_rekam_medis, pasien.tanggal_lahir, pasien.jenis_kelamin, dokter.nama_dokter, dokter.spesialisasi');
        $this->db->from('rekam_medis');
        $this->db->join('pasien', 'pasien.id_pasien = rekam_medis.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = rekam_medis.id_dokter');
        $this->db->where('id_rm', $id);
        return $this->db->get()->row();
    }

    public function insert($data) {
        return $this->db->insert('rekam_medis', $data);
    }

    public function update($id, $data) {
        return $this->db->where('id_rm', $id)->update('rekam_medis', $data);
    }

    public function delete($id) {
        return $this->db->where('id_rm', $id)->delete('rekam_medis');
    }
}