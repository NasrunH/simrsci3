<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layanan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_layanan');
        $this->load->model('Layanan_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Manajemen Master Layanan';

        $keyword = $this->input->get('keyword', TRUE);

        $config['base_url'] = base_url('layanan/index');
        $config['total_rows'] = $this->Layanan_model->count_all_results($keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // Styling Tailwind
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

        $data['layanan'] = $this->Layanan_model->get_paginated($config['per_page'], $start, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;
        $data['keyword'] = $keyword;

        $this->load->view('layouts/template', ['view_name' => 'layanan/index', 'view_data' => $data]);
    }

    public function create() {
        $this->require_permission('create_layanan');

        if ($this->input->post()) {
            $this->form_validation->set_rules('nama_layanan', 'Nama Layanan', 'required|is_unique[layanan.nama_layanan]');
            $this->form_validation->set_rules('tarif', 'Tarif Layanan', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data = [
                    'nama_layanan' => $this->input->post('nama_layanan', TRUE),
                    'tarif'        => (float) $this->input->post('tarif', TRUE),
                    'deskripsi'    => $this->input->post('deskripsi', TRUE)
                ];

                $this->Layanan_model->insert($data);
                $this->session->set_flashdata('success', 'Layanan medis baru berhasil ditambahkan.');
                redirect('layanan');
            }
        }

        $data['title'] = 'Tambah Layanan Medis';
        $this->load->view('layouts/template', ['view_name' => 'layanan/create', 'view_data' => $data]);
    }

    public function edit($id) {
        $this->require_permission('edit_layanan');
        $layanan = $this->Layanan_model->get_by_id($id);
        if (!$layanan) show_404();

        if ($this->input->post()) {
            if ($this->input->post('nama_layanan') != $layanan->nama_layanan) {
                $this->form_validation->set_rules('nama_layanan', 'Nama Layanan', 'required|is_unique[layanan.nama_layanan]');
            } else {
                $this->form_validation->set_rules('nama_layanan', 'Nama Layanan', 'required');
            }
            $this->form_validation->set_rules('tarif', 'Tarif Layanan', 'required|numeric');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data = [
                    'nama_layanan' => $this->input->post('nama_layanan', TRUE),
                    'tarif'        => (float) $this->input->post('tarif', TRUE),
                    'deskripsi'    => $this->input->post('deskripsi', TRUE)
                ];

                $this->Layanan_model->update($id, $data);
                $this->session->set_flashdata('success', 'Data layanan berhasil diperbarui.');
                redirect('layanan');
            }
        }

        $data['title'] = 'Edit Layanan Medis';
        $data['layanan'] = $layanan;
        $this->load->view('layouts/template', ['view_name' => 'layanan/edit', 'view_data' => $data]);
    }

    public function delete($id) {
        $this->require_permission('delete_layanan');
        
        try {
            if ($this->Layanan_model->delete($id)) {
                $this->session->set_flashdata('success', 'Layanan berhasil dihapus.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus layanan.');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal menghapus. Layanan ini masih terhubung dengan data dokter atau antrean.');
        }
        redirect('layanan');
    }
}