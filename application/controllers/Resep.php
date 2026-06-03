<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Resep extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_resep');
        
        $this->load->model('Resep_model');
        $this->load->model('Pasien_model');
        $this->load->model('Dokter_model');
        $this->load->model('Obat_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Daftar Resep & Riwayat';
        $role = strtolower($this->session->userdata('role'));
        $user_id = $this->session->userdata('id_user');

        // 1. Tangkap Parameter Search & Filter
        $keyword = $this->input->get('keyword', TRUE);
        $tanggal = $this->input->get('tanggal', TRUE);

        // 2. Konfigurasi Pagination CI3
        $config['base_url'] = base_url('resep/index');
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['per_page'] = 10;
        
        // Style Pagination Tailwind CSS
        $config['full_tag_open']    = '<nav class="flex items-center justify-center mt-4"><ul class="inline-flex items-center -space-x-px">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['first_tag_open']   = '<li>'; $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li>'; $config['last_tag_close']   = '</li>';
        $config['next_tag_open']    = '<li>'; $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li>'; $config['prev_tag_close']   = '</li>';
        $config['num_tag_open']     = '<li>'; $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li><span class="px-3 py-2 text-sm font-medium text-white bg-primary border border-primary hover:bg-primary-hover cursor-default">';
        $config['cur_tag_close']    = '</span></li>';
        $config['attributes']       = ['class' => 'px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'];

        // 3. Ambil data sesuai role
        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        if ($role == 'admin') {
            $config['total_rows'] = $this->Resep_model->count_all_resep($keyword, $tanggal);
            $data['resep'] = $this->Resep_model->get_all_resep_paginated($config['per_page'], $start, $keyword, $tanggal);
        } elseif ($role == 'dokter') {
            $dokter = $this->Dokter_model->get_by_user_id($user_id);
            $id_dokter = $dokter ? $dokter->id_dokter : null;
            $config['total_rows'] = $this->Resep_model->count_resep_by_dokter($id_dokter, $keyword, $tanggal);
            $data['resep'] = $dokter ? $this->Resep_model->get_resep_by_dokter_paginated($id_dokter, $config['per_page'], $start, $keyword, $tanggal) : [];
        } elseif ($role == 'pasien') {
            $pasien = $this->Pasien_model->get_by_user_id($user_id);
            $id_pasien = $pasien ? $pasien->id_pasien : null;
            $config['total_rows'] = $this->Resep_model->count_resep_by_pasien($id_pasien, $keyword, $tanggal);
            $data['resep'] = $pasien ? $this->Resep_model->get_resep_by_pasien_paginated($id_pasien, $config['per_page'], $start, $keyword, $tanggal) : [];
        }

        $this->pagination->initialize($config);

        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'] ?? 0;
        $data['start'] = $start;
        $data['keyword'] = $keyword;
        $data['tanggal'] = $tanggal;

        $this->load->view('layouts/template', ['view_name' => 'resep/index', 'view_data' => $data]);
    }

    public function create() {
        $this->require_permission('create_resep');

        if ($this->input->post()) {
            $user_id = $this->session->userdata('id_user');
            $role    = strtolower($this->session->userdata('role'));
            
            $id_dokter = null;

            // 1. LOGIKA PENENTUAN DOKTER
            if ($role == 'admin') {
                // Jika Admin, wajib pilih dokter dari dropdown form
                $id_dokter = $this->input->post('id_dokter', TRUE);
                if (empty($id_dokter)) {
                    $this->session->set_flashdata('error', 'Admin wajib memilih Dokter Pemeriksa untuk resep ini.');
                    redirect('resep/create');
                }
            } else {
                // Jika Dokter, ambil otomatis dari profil akun yang sedang login
                $dokter = $this->Dokter_model->get_by_user_id($user_id);
                $id_dokter = $dokter ? $dokter->id_dokter : null;

                if (!$id_dokter) {
                    $this->session->set_flashdata('error', 'Akun Anda tidak terhubung dengan profil Dokter. Silakan hubungi Admin.');
                    redirect('resep/create');
                }
            }

            // 2. Siapkan Data Header Resep
            $data_resep = [
                'tanggal_resep' => date('Y-m-d'),
                'id_pasien'     => $this->input->post('id_pasien', TRUE),
                'id_dokter'     => $id_dokter,
                'id_user'       => $user_id, // Catat user (admin/dokter) yang menginputkan resep ke sistem
                'total_harga'   => 0
            ];

            // 3. Siapkan Data Detail Resep & Validasi Stok
            $id_obat_arr      = $this->input->post('id_obat');
            $jumlah_arr       = $this->input->post('jumlah');
            $aturan_pakai_arr = $this->input->post('aturan_pakai');

            $data_detail = [];
            $total_harga = 0;

            if (!empty($id_obat_arr)) {
                for ($i = 0; $i < count($id_obat_arr); $i++) {
                    $obat = $this->Obat_model->get_by_id($id_obat_arr[$i]);
                    
                    if ($obat && $obat->stok >= $jumlah_arr[$i]) {
                        $subtotal = $obat->harga * $jumlah_arr[$i];
                        $total_harga += $subtotal;

                        $data_detail[] = [
                            'id_obat'      => $id_obat_arr[$i],
                            'jumlah'       => $jumlah_arr[$i],
                            'harga_satuan' => $obat->harga,
                            'subtotal'     => $subtotal,
                            'aturan_pakai' => $aturan_pakai_arr[$i]
                        ];
                    } else {
                        $this->session->set_flashdata('error', "Stok obat {$obat->nama_obat} tidak mencukupi.");
                        redirect('resep/create');
                    }
                }
            }

            $data_resep['total_harga'] = $total_harga;

            // 4. Proses Simpan
            if ($this->Resep_model->insert_resep_lengkap($data_resep, $data_detail)) {
                $this->session->set_flashdata('success', 'Resep berhasil dibuat dan stok obat telah dikurangi.');
                redirect('resep');
            } else {
                $this->session->set_flashdata('error', 'Gagal membuat resep. Silakan coba lagi.');
                redirect('resep/create');
            }
        }

        $data['title'] = 'Buat Resep Baru';
        $data['pasien'] = $this->Pasien_model->get_all();
        $data['obat'] = $this->Obat_model->get_all();
        
        // Kirim daftar dokter khusus jika yang login adalah admin
        if (strtolower($this->session->userdata('role')) == 'admin') {
            $data['dokters'] = $this->Dokter_model->get_all();
        }
        
        $this->load->view('layouts/template', ['view_name' => 'resep/create', 'view_data' => $data]);
    }
    public function show($id) {
        $data['title'] = 'Detail Resep';
        $data['resep'] = $this->db->select('resep.*, pasien.nama_lengkap as nama_pasien, pasien.no_rekam_medis, dokter.nama_dokter, dokter.spesialisasi')
                                  ->from('resep')
                                  ->join('pasien', 'pasien.id_pasien = resep.id_pasien')
                                  ->join('dokter', 'dokter.id_dokter = resep.id_dokter')
                                  ->where('resep.id_resep', $id)
                                  ->get()->row();
        
        if (!$data['resep']) show_404();

        // Validasi Kepemilikan (Opsional tapi penting untuk keamanan)
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('id_user');
        
        if ($role == 'pasien') {
            $pasien = $this->Pasien_model->get_by_user_id($user_id);
            if ($data['resep']->id_pasien != $pasien->id_pasien) show_error('Akses Ditolak', 403);
        } elseif ($role == 'dokter') {
            $dokter = $this->Dokter_model->get_by_user_id($user_id);
            if ($data['resep']->id_dokter != $dokter->id_dokter) show_error('Akses Ditolak', 403);
        }

        $data['detail'] = $this->Resep_model->get_detail_resep($id);
        
        $this->load->view('layouts/template', ['view_name' => 'resep/show', 'view_data' => $data]);
    }

    public function delete($id) {
        // Wajib punya permission delete_resep
        $this->require_permission('delete_resep');
        
        // Catatan: Di dunia medis nyata, resep tidak boleh dihapus jika sudah ditebus.
        // Di sini kita hapus header (karena ON DELETE CASCADE, detail akan ikut terhapus otomatis di DB).
        if ($this->db->where('id_resep', $id)->delete('resep')) {
            $this->session->set_flashdata('success', 'Data resep berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus resep.');
        }
        redirect('resep');
    }
}