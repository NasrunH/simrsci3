<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_obat');
        $this->load->model('Obat_model');
        $this->load->model('Supplier_model'); // Load Supplier_model
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Manajemen Obat & Alkes';

        $keyword = $this->input->get('keyword', TRUE);

        $config['base_url'] = base_url('obat/index');
        $config['total_rows'] = $this->Obat_model->count_all_results($keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

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
        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $data['obat'] = $this->Obat_model->get_paginated($config['per_page'], $start, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;
        $data['keyword'] = $keyword;

        $this->load->view('layouts/template', ['view_name' => 'obat/index', 'view_data' => $data]);
    }

    public function create() {
        $this->require_permission('create_obat');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('kode_obat', 'Kode Obat', 'required|is_unique[obat.kode_obat]');
        $this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');
        $this->form_validation->set_rules('harga', 'Harga Jual', 'required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'kode_obat'   => strtoupper($this->input->post('kode_obat', TRUE)),
                'nama_obat'   => $this->input->post('nama_obat', TRUE),
                'kategori'    => $this->input->post('kategori', TRUE),
                'satuan'      => $this->input->post('satuan', TRUE),
                'id_supplier' => $this->input->post('id_supplier', TRUE) ?: NULL, // Ambil ID Supplier (Opsional)
                'stok'        => (float) $this->input->post('stok', TRUE),
                'harga'       => (float) $this->input->post('harga', TRUE)
            ];

            $this->Obat_model->insert($data);
            $this->session->set_flashdata('success', 'Data obat baru berhasil ditambahkan.');
            redirect('obat');
        }

        $data['title'] = 'Tambah Obat / Alkes';
        // Memuat rekomendasi satuan obat unik & supplier aktif
        $data['distinct_satuan'] = $this->Obat_model->get_distinct_satuan();
        $data['suppliers']       = $this->Supplier_model->get_all();

        $this->load->view('layouts/template', ['view_name' => 'obat/create', 'view_data' => $data]);
    }

    public function edit($id) {
        $this->require_permission('edit_obat');

        $obat = $this->Obat_model->get_by_id($id);
        if (!$obat) show_404();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('satuan', 'Satuan', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');
        $this->form_validation->set_rules('harga', 'Harga Jual', 'required|numeric');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'nama_obat'   => $this->input->post('nama_obat', TRUE),
                'kategori'    => $this->input->post('kategori', TRUE),
                'satuan'      => $this->input->post('satuan', TRUE),
                'id_supplier' => $this->input->post('id_supplier', TRUE) ?: NULL, // Ambil ID Supplier (Opsional)
                'stok'        => (float) $this->input->post('stok', TRUE),
                'harga'       => (float) $this->input->post('harga', TRUE)
            ];

            $this->Obat_model->update($id, $data);
            $this->session->set_flashdata('success', 'Data obat berhasil diperbarui.');
            redirect('obat');
        }

        $data['title'] = 'Edit Data Obat';
        $data['obat']  = $obat;
        // Memuat rekomendasi satuan obat unik & supplier aktif
        $data['distinct_satuan'] = $this->Obat_model->get_distinct_satuan();
        $data['suppliers']       = $this->Supplier_model->get_all();

        $this->load->view('layouts/template', ['view_name' => 'obat/edit', 'view_data' => $data]);
    }

    public function delete($id) {
        $this->require_permission('delete_obat');
        
        $this->Obat_model->delete($id);
        $this->session->set_flashdata('success', 'Data obat berhasil dihapus dari sistem.');
        redirect('obat');
    }
}