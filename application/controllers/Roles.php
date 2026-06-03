<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Syarat dasar: harus punya permission view_roles
        $this->require_permission('view_roles');
        
        $this->load->model('Role_model');
        $this->load->library('pagination');
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Manajemen Role';

        $keyword = $this->input->get('keyword', TRUE);

        // Konfigurasi Pagination CI3
        $config['base_url'] = base_url('roles/index');
        $config['total_rows'] = $this->Role_model->count_all_results($keyword);
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

        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $data['roles']      = $this->Role_model->get_paginated($config['per_page'], $start, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start']      = $start;
        $data['keyword']    = $keyword;

        $this->load->view('layouts/template', ['view_name' => 'roles/index', 'view_data' => $data]);
    }

    public function create() {
        // Harus punya permission create_roles
        $this->require_permission('create_roles');

        if ($this->input->post()) {
            $this->form_validation->set_rules('name', 'Nama Role', 'required|is_unique[roles.name]|alpha_dash', [
                'is_unique' => 'Role ini sudah ada.',
                'alpha_dash'=> 'Nama Role hanya boleh berisi huruf, angka, underscore, atau strip tanpa spasi.'
            ]);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data = [
                    'name' => strtolower($this->input->post('name', TRUE))
                ];
                $this->Role_model->insert($data);
                $this->session->set_flashdata('success', 'Role baru berhasil ditambahkan.');
                redirect('roles');
            }
        }
        
        $data['title'] = 'Tambah Role Baru';
        $this->load->view('layouts/template', ['view_name' => 'roles/create', 'view_data' => $data]);
    }

    public function edit($id) {
        // Harus punya permission edit_roles
        $this->require_permission('edit_roles');

        if (in_array($id, [1, 2, 3])) {
            $this->session->set_flashdata('error', 'Role sistem bawaan (Admin, Dokter, Pasien) tidak boleh diedit namanya demi integritas sistem.');
            redirect('roles');
        }

        if ($this->input->post()) {
            $original_role = $this->Role_model->get_by_id($id);
            if ($this->input->post('name') != $original_role->name) {
                $this->form_validation->set_rules('name', 'Nama Role', 'required|is_unique[roles.name]|alpha_dash');
            } else {
                $this->form_validation->set_rules('name', 'Nama Role', 'required|alpha_dash');
            }

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $data = [
                    'name' => strtolower($this->input->post('name', TRUE))
                ];
                $this->Role_model->update($id, $data);
                $this->session->set_flashdata('success', 'Role berhasil diperbarui.');
                redirect('roles');
            }
        }

        $data['title'] = 'Edit Data Role';
        $data['role']  = $this->Role_model->get_by_id($id);
        if (!$data['role']) show_404();

        $this->load->view('layouts/template', ['view_name' => 'roles/edit', 'view_data' => $data]);
    }

    public function delete($id) {
        // Harus punya permission delete_roles
        $this->require_permission('delete_roles');

        if (in_array($id, [1, 2, 3])) {
            $this->session->set_flashdata('error', 'Role sistem bawaan (Admin, Dokter, Pasien) tidak boleh dihapus.');
            redirect('roles');
        }

        if ($this->Role_model->delete($id)) {
            $this->session->set_flashdata('success', 'Role berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus role. Role mungkin masih digunakan oleh User.');
        }
        redirect('roles');
    }

    public function permissions($id) {
        // Asumsi: mengelola hak akses (permissions) tergabung ke dalam hak 'edit_roles'
        $this->require_permission('edit_roles');

        // Role Admin (ID 1) tidak boleh diedit permission-nya agar tidak terkunci
        if ($id == 1) {
            $this->session->set_flashdata('error', 'Role Admin adalah Superadmin. Hak aksesnya mutlak dan tidak dapat dimodifikasi.');
            redirect('roles');
        }

        $role = $this->Role_model->get_by_id($id);
        if (!$role) show_404();

        if ($this->input->post()) {
            $permission_ids = $this->input->post('permissions') ?? [];
            
            if ($this->Role_model->sync_permissions($id, $permission_ids)) {
                $this->session->set_flashdata('success', 'Hak akses (Permissions) untuk role ' . ucfirst($role->name) . ' berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Terjadi kesalahan sistem saat memperbarui hak akses.');
            }
            redirect('roles');
        }

        $data['title'] = 'Kelola Hak Akses';
        $data['role'] = $role;
        $data['all_permissions'] = $this->Role_model->get_all_permissions();
        $data['role_permissions'] = $this->Role_model->get_role_permission_ids($id); 

        $this->load->view('layouts/template', ['view_name' => 'roles/permissions', 'view_data' => $data]);
    }
}