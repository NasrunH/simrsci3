<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_medis extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_rm');
        
        $this->load->model('Rekam_medis_model');
        $this->load->model('Pasien_model');
        $this->load->model('Dokter_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Rekam Medis Pasien';

        $role = strtolower($this->session->userdata('role'));
        $user_id = $this->session->userdata('id_user');

        $keyword = $this->input->get('keyword', TRUE);
        
        // Filter spesifik: Pasien hanya lihat RM miliknya, Dokter hanya lihat periksaannya (atau semua tergantung SOP RS)
        $id_pasien_filter = null;
        $id_dokter_filter = null;

        if ($role == 'pasien') {
            $pasien = $this->Pasien_model->get_by_user_id($user_id);
            $id_pasien_filter = $pasien ? $pasien->id_pasien : -1;
        } elseif ($role == 'dokter') {
            $dokter = $this->Dokter_model->get_by_user_id($user_id);
            $id_dokter_filter = $dokter ? $dokter->id_dokter : -1;
        }

        $config['base_url'] = base_url('rekam_medis/index');
        $config['total_rows'] = $this->Rekam_medis_model->count_all_results($keyword, $id_pasien_filter, $id_dokter_filter);
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE; 
        
        // Tailwind Pagination
        $config['full_tag_open'] = '<nav class="flex items-center justify-center mt-4"><ul class="inline-flex items-center -space-x-px">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_tag_open'] = '<li>'; $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>'; $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>'; $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>'; $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>'; $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li><span class="px-3 py-2 text-sm font-medium text-white bg-primary border border-primary hover:bg-primary-hover cursor-default">';
        $config['cur_tag_close'] = '</span></li>';
        $config['attributes'] = ['class' => 'px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700'];

        $this->pagination->initialize($config);
        $start = $this->input->get('per_page') ? $this->input->get('per_page') : 0;

        $data['rekam_medis'] = $this->Rekam_medis_model->get_paginated($config['per_page'], $start, $keyword, $id_pasien_filter, $id_dokter_filter);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;
        $data['keyword'] = $keyword;

        $this->load->view('layouts/template', ['view_name' => 'rekam_medis/index', 'view_data' => $data]);
    }

public function create() {
        $this->require_permission('create_rm');

        if ($this->input->post()) {
            $this->form_validation->set_rules('id_pasien', 'Pasien', 'required');
            $this->form_validation->set_rules('keluhan_utama', 'Keluhan Utama (Subjective)', 'required');
            $this->form_validation->set_rules('diagnosa', 'Diagnosa (Assessment)', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
            } else {
                $role = strtolower($this->session->userdata('role'));
                $id_dokter = null;

                if ($role == 'admin') {
                    $id_dokter = $this->input->post('id_dokter', TRUE);
                } else {
                    $dokter = $this->Dokter_model->get_by_user_id($this->session->userdata('id_user'));
                    $id_dokter = $dokter ? $dokter->id_dokter : null;
                }

                if (!$id_dokter) {
                    $this->session->set_flashdata('error', 'Gagal memproses: Profil dokter pemeriksa tidak ditemukan.');
                    redirect('rekam_medis/create');
                }

                $data = [
                    'id_pasien'         => $this->input->post('id_pasien', TRUE),
                    'id_dokter'         => $id_dokter,
                    'tanggal_periksa'   => $this->input->post('tanggal_periksa', TRUE),
                    'tekanan_darah'     => $this->input->post('tekanan_darah', TRUE),
                    'suhu_tubuh'        => $this->input->post('suhu_tubuh', TRUE),
                    'berat_badan'       => $this->input->post('berat_badan', TRUE),
                    'catatan_alergi'    => $this->input->post('catatan_alergi', TRUE),
                    'keluhan_utama'     => $this->input->post('keluhan_utama', TRUE),
                    'pemeriksaan_fisik' => $this->input->post('pemeriksaan_fisik', TRUE),
                    'diagnosa'          => $this->input->post('diagnosa', TRUE),
                    'tindakan_rencana'  => $this->input->post('tindakan_rencana', TRUE)
                ];
                
                $this->Rekam_medis_model->insert($data);
                $this->session->set_flashdata('success', 'Data Rekam Medis (SOAP) berhasil disimpan.');
                
                // LEMPAR DATA KE RESEP JIKA DICENTANG
                if ($this->input->post('lanjut_resep') == 'yes') {
                    // Gunakan redirect dengan parameter query string
                    redirect('resep/create?pasien=' . $data['id_pasien']);
                } else {
                    redirect('rekam_medis');
                }
            }
        }

        $data['title'] = 'Catat Rekam Medis Baru';
        $data['pasien'] = $this->Pasien_model->get_all();
        
        // AMBIL PARAMETER DARI URL JIKA ADA (misal dari halaman antrean)
        $data['selected_pasien'] = $this->input->get('pasien', TRUE); 

        if (strtolower($this->session->userdata('role')) == 'admin') {
            $data['dokters'] = $this->Dokter_model->get_all();
            $data['selected_dokter'] = $this->input->get('dokter', TRUE); 
        }

        $this->load->view('layouts/template', ['view_name' => 'rekam_medis/create', 'view_data' => $data]);
    }


    public function show($id) {
        $data['title'] = 'Detail Rekam Medis';
        $data['rm'] = $this->Rekam_medis_model->get_by_id($id);
        
        if (!$data['rm']) show_404();

        // Keamanan: Pastikan pasien hanya bisa melihat miliknya
        if (strtolower($this->session->userdata('role')) == 'pasien') {
            $pasien = $this->Pasien_model->get_by_user_id($this->session->userdata('id_user'));
            if ($data['rm']->id_pasien != $pasien->id_pasien) show_error('Akses Ditolak', 403);
        }

        $this->load->view('layouts/template', ['view_name' => 'rekam_medis/show', 'view_data' => $data]);
    }

    public function delete($id) {
        $this->require_permission('delete_rm');
        if ($this->Rekam_medis_model->delete($id)) {
            $this->session->set_flashdata('success', 'Rekam medis berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data.');
        }
        redirect('rekam_medis');
    }
}