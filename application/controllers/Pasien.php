<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_pasien');
        $this->load->model('Pasien_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title']  = 'Data Pasien';
        
        // 1. Tangkap Parameter Search & Filter
        $keyword = $this->input->get('keyword', TRUE);
        $jenis_kelamin = $this->input->get('jenis_kelamin', TRUE);

        // 2. Konfigurasi Pagination CI3
        $config['base_url'] = base_url('pasien/index');
        $config['total_rows'] = $this->Pasien_model->count_all_results($keyword, $jenis_kelamin);
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

        $data['pasien']     = $this->Pasien_model->get_paginated($config['per_page'], $start, $keyword, $jenis_kelamin);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start']      = $start;
        
        // Kembalikan filter ke view
        $data['keyword']       = $keyword;
        $data['jenis_kelamin'] = $jenis_kelamin;

        $template_data = [
            'view_name' => 'pasien/index',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function create() {
        $this->require_permission('create_pasien');
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', [
                'is_unique' => 'Username ini sudah digunakan, silakan pilih yang lain.'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]', [
                'min_length' => 'Password minimal 6 karakter.'
            ]);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data_user = [
                    'username' => $this->input->post('username', TRUE),
                    'password' => $this->input->post('password', TRUE),
                    'role_id'  => 3 // Pasien
                ];

                $data_pasien = [
                    'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                    'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                    'alamat'        => $this->input->post('alamat', TRUE)
                ];
                
                if ($this->Pasien_model->insert_with_user($data_user, $data_pasien)) {
                    $this->session->set_flashdata('success', 'Data pasien dan akun berhasil dibuat.');
                    redirect('pasien');
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan pada sistem saat menyimpan data.');
                }
            }
        }

        $data['title'] = 'Tambah Pasien Baru';
        $this->load->view('layouts/template', ['view_name' => 'pasien/create', 'view_data' => $data]);
    }

    public function edit($id) {
        $this->require_permission('edit_pasien');
        
        if ($this->input->post()) {
            $data = [
                'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                'alamat'        => $this->input->post('alamat', TRUE)
            ];
            
            $this->Pasien_model->update($id, $data);
            
            $this->session->set_flashdata('success', 'Data pasien berhasil diperbarui.');
            redirect('pasien');
        }

        $data['title']  = 'Edit Data Pasien';
        $data['pasien'] = $this->Pasien_model->get_by_id($id);
        
        if (!$data['pasien']) show_404();

        $this->load->view('layouts/template', ['view_name' => 'pasien/edit', 'view_data' => $data]);
    }

    public function delete($id) {
        $this->require_permission('delete_pasien');
        
        if($this->Pasien_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data pasien beserta akunnya berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data pasien.');
        }
        redirect('pasien');
    }
}