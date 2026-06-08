<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->require_permission('view_penerimaan');
        $this->load->model('Penerimaan_model');
        $this->load->model('Obat_model');
        $this->load->library('pagination');
    }

    public function index() {
        $data['title'] = 'Penerimaan Obat Supplier';

        $keyword = $this->input->get('keyword', TRUE);

        $config['base_url'] = base_url('penerimaan/index');
        $config['total_rows'] = $this->Penerimaan_model->count_all_results($keyword);
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

        $data['penerimaan'] = $this->Penerimaan_model->get_paginated($config['per_page'], $start, $keyword);
        $data['pagination'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];
        $data['start'] = $start;
        $data['keyword'] = $keyword;

        $this->load->view('layouts/template', ['view_name' => 'penerimaan/index', 'view_data' => $data]);
    }

    public function create() {
        $this->require_permission('create_penerimaan');

        if ($this->input->post()) {
            $id_obat = $this->input->post('id_obat[]', TRUE);
            $jumlah  = $this->input->post('jumlah[]', TRUE);
            $harga   = $this->input->post('harga_beli[]', TRUE); 

            // Tangkap keputusan persetujuan update supplier utama
            $update_main_supplier = $this->input->post('update_main_supplier') === 'yes';

            if (empty($id_obat)) {
                $this->session->set_flashdata('error', 'Silakan tambahkan minimal 1 item obat.');
                redirect('penerimaan/create');
            }

            $total_item = 0;
            $total_harga = 0;
            $detail_items = [];

            for ($i = 0; $i < count($id_obat); $i++) {
                if (empty($id_obat[$i])) continue;

                $clean_price = str_replace('.', '', $harga[$i]); 
                $clean_price = str_replace(',', '.', $clean_price); 
                
                $qty   = (float)$jumlah[$i];
                $price = (float)$clean_price;
                $subtotal = $qty * $price;

                $total_item += $qty;
                $total_harga += $subtotal;

                $detail_items[] = [
                    'id_obat'    => $id_obat[$i],
                    'jumlah'     => $qty,
                    'harga_beli' => $price,
                    'subtotal'   => $subtotal
                ];
            }

            $data_penerimaan = [
                'no_faktur'          => $this->input->post('no_faktur', TRUE),
                'id_supplier'        => $this->input->post('id_supplier', TRUE),
                'tanggal_penerimaan' => $this->input->post('tanggal_penerimaan', TRUE),
                'total_item'         => $total_item,
                'total_harga'        => $total_harga,
                'catatan'            => $this->input->post('catatan', TRUE),
                'id_user'            => $this->session->userdata('id_user')
            ];

            $cek = $this->db->get_where('penerimaan_obat', ['no_faktur' => $data_penerimaan['no_faktur']])->row();
            if ($cek) {
                $this->session->set_flashdata('error', 'Nomor Faktur ini sudah pernah diinput sebelumnya.');
                redirect('penerimaan/create');
            }

            // Jalankan transaksi di model dengan parameter baru
            $proses = $this->Penerimaan_model->insert_transaksi($data_penerimaan, $detail_items, $update_main_supplier);

            if ($proses) {
                $this->session->set_flashdata('success', 'Transaksi penerimaan obat berhasil disimpan. Relasi supplier obat disesuaikan.');
                redirect('penerimaan/show/' . $proses);
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan transaksi. Terjadi kesalahan database.');
                redirect('penerimaan/create');
            }
        }

        $data['title']     = 'Input Stok Masuk Supplier';
        $data['suppliers'] = $this->Penerimaan_model->get_all_suppliers();
        $data['obat']      = $this->Obat_model->get_all();

        $this->load->view('layouts/template', ['view_name' => 'penerimaan/create', 'view_data' => $data]);
    }

    public function show($id) {
        $penerimaan = $this->Penerimaan_model->get_by_id($id);
        if (!$penerimaan) show_404();

        $data['title']  = 'Detail Penerimaan Faktur ' . $penerimaan->no_faktur;
        $data['p']      = $penerimaan;
        $data['detail'] = $this->Penerimaan_model->get_detail_penerimaan($id);

        $this->load->view('layouts/template', ['view_name' => 'penerimaan/show', 'view_data' => $data]);
    }
}