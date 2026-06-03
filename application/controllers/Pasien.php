<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->restrict_to(['admin', 'dokter']);
        $this->load->model('Pasien_model');
        // Load library form validation untuk mengecek username duplikat
        $this->load->library('form_validation'); 
    }

    public function index() {
        $data['title']  = 'Data Pasien';
        $data['pasien'] = $this->Pasien_model->get_all();
        
        $template_data = [
            'view_name' => 'pasien/index',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function create() {
        if ($this->input->post()) {
            
            // 1. Validasi Form
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', [
                'is_unique' => 'Username ini sudah digunakan, silakan pilih yang lain.'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]', [
                'min_length' => 'Password minimal 6 karakter.'
            ]);

            if ($this->form_validation->run() == FALSE) {
                // Jika validasi gagal, kembalikan ke form dengan pesan error
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                // 2. Siapkan Data Akun User (Role ID 3 = Pasien)
                $data_user = [
                    'username' => $this->input->post('username', TRUE),
                    'password' => $this->input->post('password', TRUE),
                    'role_id'  => 3 
                ];

                // 3. Siapkan Data Profil Pasien
                $data_pasien = [
                    'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                    'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                    'alamat'        => $this->input->post('alamat', TRUE)
                ];
                
                // 4. Proses Insert via Transaction Model
                if ($this->Pasien_model->insert_with_user($data_user, $data_pasien)) {
                    $this->session->set_flashdata('success', 'Data pasien dan akun berhasil dibuat.');
                    redirect('pasien');
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan pada sistem saat menyimpan data.');
                }
            }
        }

        $data['title'] = 'Tambah Pasien Baru';
        $template_data = [
            'view_name' => 'pasien/create',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

        // Mengedit data pasien
        public function edit($id) {
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
            
            // Jika data tidak ditemukan
            if (!$data['pasien']) {
                show_404();
            }

            $template_data = [
                'view_name' => 'pasien/edit',
                'view_data' => $data
            ];
            $this->load->view('layouts/template', $template_data);
        }

        // Menghapus data pasien
        public function delete($id) {
            // Proteksi Tambahan: Hanya ADMIN yang boleh menghapus pasien
            $this->restrict_to(['admin']);
            
            $this->Pasien_model->delete($id);
            $this->session->set_flashdata('success', 'Data pasien berhasil dihapus.');
            redirect('pasien');
        }
    }