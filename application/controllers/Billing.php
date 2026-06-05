<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_billing');
        $this->load->model('Billing_model');
        $this->load->model('Pasien_model');
        $this->load->model('Resep_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Kasir & Billing Transaksi';

        $keyword = $this->input->get('keyword', TRUE);
        $status = $this->input->get('status', TRUE);

        $config['base_url'] = base_url('billing/index');
        $config['total_rows'] = $this->Billing_model->count_all_results($keyword, $status);
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

        $data['billing'] = $this->Billing_model->get_paginated($config['per_page'], $start, $keyword, $status);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;
        $data['keyword'] = $keyword;
        $data['status'] = $status;

        $this->load->view('layouts/template', ['view_name' => 'billing/index', 'view_data' => $data]);
    }

    public function pay($id) {
        $this->require_permission('pay_billing');
        $billing = $this->Billing_model->get_by_id($id);
        if (!$billing) show_404();

        if ($billing->status == 'Lunas') {
            $this->session->set_flashdata('error', 'Tagihan ini sudah diselesaikan (Lunas).');
            redirect('billing');
        }

        if ($this->input->post()) {
            $metode = $this->input->post('metode_pembayaran', TRUE);
            $uang_diterima = (float) $this->input->post('uang_diterima', TRUE);
            $total_tagihan = (float) $billing->total_tagihan;

            if ($metode == 'Tunai' && $uang_diterima < $total_tagihan) {
                $this->session->set_flashdata('error', 'Dana yang diterima kurang dari total tagihan.');
                redirect('billing/pay/' . $id);
            }

            $uang_kembalian = ($metode == 'Tunai') ? ($uang_diterima - $total_tagihan) : 0;

            $data_update = [
                'status' => 'Lunas',
                'metode_pembayaran' => $metode,
                'uang_diterima' => $uang_diterima,
                'uang_kembalian' => $uang_kembalian,
                'id_kasir' => $this->session->userdata('id_user'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id_billing', $id)->update('billing', $data_update);
            $this->session->set_flashdata('success', 'Pembayaran kasir sukses diproses.');
            redirect('billing/invoice/' . $id);
        }

        $data['title'] = 'Proses Pembayaran Pasien';
        
        // SINKRONISASI: Sediakan b dan billing agar kompatibel dengan versi view mana pun
        $data['b'] = $billing;
        $data['billing'] = $billing;
        
        $data['detail_resep'] = $this->Resep_model->get_detail_resep($billing->id_resep);

        $this->load->view('layouts/template', ['view_name' => 'billing/pay', 'view_data' => $data]);
    }

    public function invoice($id) {
        $billing = $this->Billing_model->get_by_id($id);
        if (!$billing) show_404();

        $data['title'] = 'Kuitansi Invoice Tagihan';
        $data['b'] = $billing;
        $data['billing'] = $billing;
        $data['detail_resep'] = $this->Resep_model->get_detail_resep($billing->id_resep);

        $this->load->view('layouts/template', ['view_name' => 'billing/invoice', 'view_data' => $data]);
    }
}