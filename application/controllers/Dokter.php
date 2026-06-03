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
    }

    public function index() {
        $data['title']  = 'Data Dokter';
        $data['dokter'] = $this->Dokter_model->get_all();
        
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