<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Ubah pengecekan dari Role menjadi Permission
        // Syarat masuk ke Controller ini: minimal harus punya hak 'view_dokter'
        $this->require_permission('view_dokter');
        
        $this->load->model('Dokter_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title']  = 'Data Dokter';
        
        // 1. Tangkap Parameter Search & Filter
        $keyword = $this->input->get('keyword', TRUE);
        $spesialisasi = $this->input->get('spesialisasi', TRUE);

        // 2. Konfigurasi Pagination CI3
        $config['base_url'] = base_url('dokter/index');
        $config['total_rows'] = $this->Dokter_model->count_all_results($keyword, $spesialisasi);
        $config['per_page'] = 10;
        
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE; 
        
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

        $this->pagination->initialize($config);

        // 3. Ambil data dengan Limit dan Offset
        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $data['dokter']     = $this->Dokter_model->get_paginated($config['per_page'], $start, $keyword, $spesialisasi);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start']      = $start;
        
        // Kembalikan filter ke view
        $data['keyword']       = $keyword;
        $data['spesialisasi']  = $spesialisasi;
        
        // Ambil daftar unik spesialisasi untuk filter dropdown
        $data['daftar_spesialisasi'] = $this->Dokter_model->get_unique_spesialisasi();
        
        $template_data = [
            'view_name' => 'dokter/index',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function create() {
        // Cek secara spesifik: apakah dia punya izin membuat data?
        $this->require_permission('create_dokter');

        if ($this->input->post()) {
            
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data_user = [
                    'username' => $this->input->post('username', TRUE),
                    'password' => $this->input->post('password', TRUE),
                    'role_id'  => 2 
                ];

                $data_dokter = [
                    'nama_dokter'  => $this->input->post('nama_dokter', TRUE),
                    'spesialisasi' => $this->input->post('spesialisasi', TRUE),
                    'no_telp'      => $this->input->post('no_telp', TRUE)
                ];
                
                if ($this->Dokter_model->insert_with_user($data_user, $data_dokter)) {
                    $this->session->set_flashdata('success', 'Data dokter dan akun berhasil dibuat.');
                    redirect('dokter');
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan sistem saat menyimpan data.');
                }
            }
        }

        $data['title'] = 'Tambah Dokter Baru';
        $template_data = [
            'view_name' => 'dokter/create',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function edit($id) {
        // Cek secara spesifik: apakah dia punya izin mengedit data?
        $this->require_permission('edit_dokter');

        if ($this->input->post()) {
            $data = [
                'nama_dokter'  => $this->input->post('nama_dokter', TRUE),
                'spesialisasi' => $this->input->post('spesialisasi', TRUE),
                'no_telp'      => $this->input->post('no_telp', TRUE)
            ];
            
            $this->Dokter_model->update($id, $data);
            $this->session->set_flashdata('success', 'Profil dokter berhasil diperbarui.');
            redirect('dokter');
        }

        $data['title']  = 'Edit Data Dokter';
        $data['dokter'] = $this->Dokter_model->get_by_id($id);
        
        if (!$data['dokter']) show_404();

        $template_data = [
            'view_name' => 'dokter/edit',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function delete($id) {
        // Cek secara spesifik: apakah dia punya izin menghapus data?
        $this->require_permission('delete_dokter');

        if ($this->Dokter_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data dokter dan akun terkait berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data dokter.');
        }
        redirect('dokter');
    }
}