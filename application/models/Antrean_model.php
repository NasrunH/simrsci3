<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrean_model extends CI_Model {

    private function _base_query_hari_ini() {
        $this->db->select('antrean.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis, dokter.nama_dokter, dokter.spesialisasi, layanan.nama_layanan');
        $this->db->from('antrean');
        $this->db->join('pasien', 'pasien.id_pasien = antrean.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');
        $this->db->join('layanan', 'layanan.id_layanan = antrean.id_layanan', 'left');
    }

    public function get_hari_ini($tanggal, $id_pasien = null, $id_dokter = null) {
        $this->_base_query_hari_ini();
        $this->db->where('antrean.tanggal_antrean', $tanggal);

        if ($id_pasien !== null) {
            $this->db->where('antrean.id_pasien', $id_pasien);
        }
        if ($id_dokter !== null) {
            $this->db->where('antrean.id_dokter', $id_dokter);
        }

        $this->db->order_by('antrean.id_layanan', 'ASC');
        $this->db->order_by('antrean.no_antrean', 'ASC');
        return $this->db->get()->result();
    }

    public function get_by_id_detail($id_antrean) {
        $this->_base_query_hari_ini();
        $this->db->where('antrean.id_antrean', $id_antrean);
        return $this->db->get()->row();
    }

    public function get_next_menunggu($id_layanan, $tanggal) {
        $this->_base_query_hari_ini();
        $this->db->where('antrean.id_layanan', $id_layanan);
        $this->db->where('antrean.tanggal_antrean', $tanggal);
        $this->db->where('antrean.status', 'Menunggu');
        $this->db->order_by('antrean.no_antrean', 'ASC');
        $this->db->limit(1);
        return $this->db->get()->row();
    }

    public function update_status($id, $status) {
        $this->db->where('id_antrean', $id);
        return $this->db->update('antrean', ['status' => $status]);
    }

    public function get_paginated($limit, $start, $tanggal = null, $id_dokter = null, $id_pasien = null) {
        $this->db->select('antrean.*, pasien.nama_lengkap, pasien.no_rekam_medis, dokter.nama_dokter, dokter.spesialisasi');
        $this->db->from('antrean');
        $this->db->join('pasien', 'pasien.id_pasien = antrean.id_pasien');
        $this->db->join('dokter', 'dokter.id_dokter = antrean.id_dokter');

        if (!empty($tanggal)) {
            $this->db->where('tanggal_antrean', $tanggal);
        }

        if (!empty($id_dokter)) $this->db->where('antrean.id_dokter', $id_dokter);
        if (!empty($id_pasien)) $this->db->where('antrean.id_pasien', $id_pasien);

        $this->db->order_by('tanggal_antrean', 'DESC');
        $this->db->order_by('no_antrean', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    public function count_all_results($tanggal = null, $id_dokter = null, $id_pasien = null) {
        $this->db->from('antrean');
        if (!empty($tanggal)) $this->db->where('tanggal_antrean', $tanggal);
        if (!empty($id_dokter)) $this->db->where('id_dokter', $id_dokter);
        if (!empty($id_pasien)) $this->db->where('id_pasien', $id_pasien);
        return $this->db->count_all_results();
    }

    public function generate_nomor($id_layanan, $tanggal) {
        $this->db->where('id_layanan', $id_layanan);
        $this->db->where('tanggal_antrean', $tanggal);
        $jumlah = $this->db->count_all_results('antrean');
        return $jumlah + 1;
    }

    public function insert($data) {
        if (!isset($data['no_antrean'])) {
            $data['no_antrean'] = $this->generate_nomor($data['id_layanan'], $data['tanggal_antrean']);
        }
        $this->db->insert('antrean', $data);
        return $this->db->insert_id();
    }

    public function group_by_layanan_and_status(array $antrean_list) {
        $grouped = [];
        foreach ($antrean_list as $a) {
            $lid = $a->id_layanan ?: 0;
            $status = $a->status ?? 'Menunggu';
            if (!isset($grouped[$lid])) {
                $grouped[$lid] = [
                    'nama_layanan' => $a->nama_layanan ?? 'Tanpa Poli',
                    'Menunggu'  => [],
                    'Diperiksa' => [],
                    'Selesai'   => [],
                    'Batal'     => [],
                ];
            }
            if (!isset($grouped[$lid][$status])) {
                $grouped[$lid][$status] = [];
            }
            $grouped[$lid][$status][] = $a;
        }
        return $grouped;
    }

    public function to_call_payload($row) {
        if (!$row) {
            return null;
        }
        return [
            'id_antrean'    => (int) $row->id_antrean,
            'no_antrean'    => (int) $row->no_antrean,
            'nama_pasien'   => $row->nama_pasien ?? '',
            'no_rekam_medis'=> $row->no_rekam_medis ?? '',
            'nama_layanan'  => $row->nama_layanan ?? 'Poliklinik',
            'nama_dokter'   => $row->nama_dokter ?? '',
            'status'        => $row->status ?? '',
        ];
    }
}
