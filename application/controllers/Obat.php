<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Syarat dasar: harus punya permission view_obat
        $this->require_permission('view_obat');
        
        $this->load->model('Obat_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Manajemen Data Obat';

        // Tangkap parameter Search & Filter dari URL
        $keyword  = $this->input->get('keyword', TRUE);
        $kategori = $this->input->get('kategori', TRUE);

        // Konfigurasi Pagination CI3
        $config['base_url'] = base_url('obat/index');
        $config['total_rows'] = $this->Obat_model->count_all_results($keyword, $kategori);
        $config['per_page'] = 10;
        
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE; 
        
        // Style Tailwind CSS untuk Pagination
        $config['full_tag_open']    = '<nav class="flex items-center justify-center space-x-1 mt-4"><ul class="inline-flex items-center -space-x-px">';
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

        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        // Ambil data
        $data['obat']       = $this->Obat_model->get_paginated($config['per_page'], $start, $keyword, $kategori);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start']      = $start;
        
        $data['keyword']    = $keyword;
        $data['kategori']   = $kategori;

        $template_data = [
            'view_name' => 'obat/index',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function create() {
        // Cek secara spesifik: apakah ia punya izin membuat data obat?
        $this->require_permission('create_obat');

        if ($this->input->post()) {
            $this->form_validation->set_rules('kode_obat', 'Kode Obat', 'required|is_unique[obat.kode_obat]');
            $this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data = [
                    'kode_obat' => strtoupper($this->input->post('kode_obat', TRUE)), // Otomatis jadikan huruf besar
                    'nama_obat' => $this->input->post('nama_obat', TRUE),
                    'kategori'  => $this->input->post('kategori', TRUE),
                    'stok'      => (int) $this->input->post('stok', TRUE),
                    'harga'     => (float) $this->input->post('harga', TRUE)
                ];
                
                $this->Obat_model->insert($data);
                $this->session->set_flashdata('success', 'Data obat berhasil ditambahkan.');
                redirect('obat');
            }
        }

        $data['title'] = 'Tambah Obat Baru';
        $template_data = [
            'view_name' => 'obat/create',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function edit($id) {
        // Cek secara spesifik: apakah ia punya izin mengedit data obat?
        $this->require_permission('edit_obat');

        if ($this->input->post()) {
            $data = [
                'nama_obat' => $this->input->post('nama_obat', TRUE),
                'kategori'  => $this->input->post('kategori', TRUE),
                'stok'      => (int) $this->input->post('stok', TRUE),
                'harga'     => (float) $this->input->post('harga', TRUE)
            ];
            
            $this->Obat_model->update($id, $data);
            $this->session->set_flashdata('success', 'Data obat berhasil diperbarui.');
            redirect('obat');
        }

        $data['title'] = 'Edit Data Obat';
        $data['obat']  = $this->Obat_model->get_by_id($id);
        
        if (!$data['obat']) show_404();

        $template_data = [
            'view_name' => 'obat/edit',
            'view_data' => $data
        ];
        $this->load->view('layouts/template', $template_data);
    }

    public function delete($id) {
        // Cek secara spesifik: apakah ia punya izin menghapus data obat?
        $this->require_permission('delete_obat');

        if ($this->Obat_model->delete($id)) {
            $this->session->set_flashdata('success', 'Data obat berhasil dihapus.');
        } else {
            // Error ini biasanya terjadi jika constraint foreign key database memblokir (obat sudah diresepkan)
            $this->session->set_flashdata('error', 'Gagal menghapus data obat. Obat ini mungkin masih tercatat dalam riwayat resep pasien.');
        }
        redirect('obat');
    }
}