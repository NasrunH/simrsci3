<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Otorisasi halaman: Hanya user dengan hak akses view_supplier yang bisa masuk
        $this->require_permission('view_supplier');
        $this->load->model('Supplier_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
    }

    // Tampilan Tabel Supplier Utama
    public function index() {
        $data['title'] = 'Master Supplier Farmasi';

        $keyword = $this->input->get('keyword', TRUE);

        // Konfigurasi Paginasi CodeIgniter 3
        $config['base_url'] = base_url('supplier/index');
        $config['total_rows'] = $this->Supplier_model->count_all_results($keyword);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;

        // Custom Styling Paginasi CSS Tailwind
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

        $data['suppliers'] = $this->Supplier_model->get_paginated($config['per_page'], $start, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;
        $data['keyword'] = $keyword;

        $this->load->view('layouts/template', ['view_name' => 'supplier/index', 'view_data' => $data]);
    }

    // Proses Tambah Supplier Baru
    public function create() {
        $this->require_permission('create_supplier');

        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'trim|max_length[20]');
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'trim');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'nama_supplier' => $this->input->post('nama_supplier', TRUE),
                'no_telp'       => $this->input->post('no_telp', TRUE),
                'alamat'        => $this->input->post('alamat', TRUE)
            ];

            $this->Supplier_model->insert($data);
            $this->session->set_flashdata('success', 'Data supplier baru berhasil didaftarkan.');
            redirect('supplier');
        }

        $data['title'] = 'Daftarkan Supplier Baru';
        $this->load->view('layouts/template', ['view_name' => 'supplier/create', 'view_data' => $data]);
    }

    // Proses Edit Supplier
    public function edit($id) {
        $this->require_permission('edit_supplier');

        $supplier = $this->Supplier_model->get_by_id($id);
        if (!$supplier) {
            show_404();
        }

        $this->form_validation->set_rules('nama_supplier', 'Nama Supplier', 'required|trim|max_length[150]');
        $this->form_validation->set_rules('no_telp', 'No. Telepon', 'trim|max_length[20]');
        $this->form_validation->set_rules('alamat', 'Alamat Lengkap', 'trim');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'nama_supplier' => $this->input->post('nama_supplier', TRUE),
                'no_telp'       => $this->input->post('no_telp', TRUE),
                'alamat'        => $this->input->post('alamat', TRUE)
            ];

            $this->Supplier_model->update($id, $data);
            $this->session->set_flashdata('success', 'Informasi supplier berhasil diperbarui.');
            redirect('supplier');
        }

        $data['title'] = 'Perbarui Informasi Supplier';
        $data['s'] = $supplier;
        $this->load->view('layouts/template', ['view_name' => 'supplier/edit', 'view_data' => $data]);
    }

    // Proses Hapus Supplier
    public function delete($id) {
        $this->require_permission('delete_supplier');

        // Proteksi integritas database: Cek apakah supplier ini sudah memiliki riwayat penerimaan obat
        $this->db->where('id_supplier', $id);
        $terpakai = $this->db->count_all_results('penerimaan_obat');

        if ($terpakai > 0) {
            $this->session->set_flashdata('error', 'Gagal menghapus: Supplier ini masih terikat dengan log riwayat transaksi penerimaan obat.');
            redirect('supplier');
        }

        $this->Supplier_model->delete($id);
        $this->session->set_flashdata('success', 'Supplier berhasil dihapus dari sistem.');
        redirect('supplier');
    }
}