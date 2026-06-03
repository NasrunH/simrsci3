<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Hanya Admin yang boleh mengelola modul Users
        $this->restrict_to(['admin']);
        
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Manajemen Akses User';

        // 1. Tangkap parameter Search & Filter
        $keyword = $this->input->get('keyword', TRUE);
        $role_id = $this->input->get('role_id', TRUE);

        // 2. Konfigurasi Pagination CI3
        $config['base_url'] = base_url('users/index');
        $config['total_rows'] = $this->User_model->count_all_results($keyword, $role_id);
        $config['per_page'] = 10;
        
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE; 
        
        // Style Pagination Tailwind CSS
        $config['full_tag_open']    = '<nav class="flex items-center justify-center mt-4"><ul class="inline-flex items-center -space-x-px">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['first_tag_open']   = '<li>';
        $config['first_tag_close']  = '</li>';
        $config['last_tag_open']    = '<li>';
        $config['last_tag_close']   = '</li>';
        $config['next_tag_open']    = '<li>';
        $config['next_tag_close']   = '</li>';
        $config['prev_tag_open']    = '<li>';
        $config['prev_tag_close']   = '</li>';
        $config['num_tag_open']     = '<li>';
        $config['num_tag_close']    = '</li>';
        $config['cur_tag_open']     = '<li><span class="px-3 py-2 text-sm font-medium text-white bg-primary border border-primary hover:bg-primary-hover cursor-default">';
        $config['cur_tag_close']    = '</span></li>';
        $config['attributes']       = ['class' => 'px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'];

        $this->pagination->initialize($config);

        // 3. Ambil data dengan Limit dan Offset
        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $data['users']      = $this->User_model->get_paginated($config['per_page'], $start, $keyword, $role_id);
        $data['roles']      = $this->Role_model->get_all(); // Untuk dropdown filter
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start']      = $start;
        
        $data['keyword']    = $keyword;
        $data['role_id']    = $role_id;

        $template_data = [
            'view_name' => 'users/index',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

     public function create() {
        if ($this->input->post()) {
            
            // 1. Validasi Akun Dasar (Wajib untuk semua role)
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('role_id', 'Role', 'required');

            // Ambil nama role yang dipilih dari database
            $role_id = $this->input->post('role_id', TRUE);
            $role = $this->Role_model->get_by_id($role_id);
            $role_name = strtolower($role->name);

            // 2. Validasi Dinamis Berdasarkan Role
            if ($role_name == 'admin') {
                $this->form_validation->set_rules('nama_admin', 'Nama Admin', 'required');
            } elseif ($role_name == 'dokter') {
                $this->form_validation->set_rules('nama_dokter', 'Nama Dokter', 'required');
                $this->form_validation->set_rules('spesialisasi', 'Spesialisasi', 'required');
                $this->form_validation->set_rules('no_telp', 'No Telepon', 'required');
            } elseif ($role_name == 'pasien') {
                $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
                $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
                $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                
                // 3. Susun Data Akun
                $data_user = [
                    'username' => $this->input->post('username', TRUE),
                    'password' => $this->input->post('password', TRUE),
                    'role_id'  => $role_id
                ];

                // 4. Susun Data Profil Secara Dinamis
                $data_profile = [];
                if ($role_name == 'admin') {
                    $data_profile = [
                        'nama_admin' => $this->input->post('nama_admin', TRUE)
                    ];
                } elseif ($role_name == 'dokter') {
                    $data_profile = [
                        'nama_dokter'  => $this->input->post('nama_dokter', TRUE),
                        'spesialisasi' => $this->input->post('spesialisasi', TRUE),
                        'no_telp'      => $this->input->post('no_telp', TRUE)
                    ];
                } elseif ($role_name == 'pasien') {
                    $data_profile = [
                        'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                        'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                        'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                        'alamat'        => $this->input->post('alamat', TRUE)
                    ];
                }

                // 5. Simpan ke database melalui Model
                if ($this->User_model->insert_with_profile($data_user, $role_name, $data_profile)) {
                    $this->session->set_flashdata('success', 'Akun beserta Profil ' . ucfirst($role_name) . ' berhasil dibuat.');
                    redirect('users');
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan sistem saat menyimpan data.');
                }
            }
        }
        
        $data['title'] = 'Tambah User & Profil Baru';
        $data['roles'] = $this->Role_model->get_all();
        $this->load->view('layouts/template', ['view_name' => 'users/create', 'view_data' => $data]);
    }

    public function edit($id) {
        if ($this->input->post()) {
            // Validasi username hanya jika diubah (hindari error is_unique)
            $original_user = $this->User_model->get_by_id($id);
            if ($this->input->post('username') != $original_user->username) {
                $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
            } else {
                $this->form_validation->set_rules('username', 'Username', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data = [
                    'username' => $this->input->post('username', TRUE),
                    'role_id'  => $this->input->post('role_id', TRUE)
                ];
                
                // Masukkan password ke array jika tidak kosong
                if (!empty($this->input->post('password'))) {
                    $data['password'] = $this->input->post('password', TRUE);
                }

                $this->User_model->update($id, $data);
                $this->session->set_flashdata('success', 'Data user berhasil diperbarui.');
                redirect('users');
            }
        }

        $data['title'] = 'Edit Data User';
        $data['user']  = $this->User_model->get_by_id($id);
        $data['roles'] = $this->Role_model->get_all();
        
        if (!$data['user']) show_404();

        $this->load->view('layouts/template', ['view_name' => 'users/edit', 'view_data' => $data]);
    }

    public function delete($id) {
        // Proteksi agar admin tidak menghapus dirinya sendiri secara tidak sengaja
        if ($id == $this->session->userdata('id_user')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
            redirect('users');
        }

        if ($this->User_model->delete($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user. User mungkin terhubung dengan data lain.');
        }
        redirect('users');
    }
}