<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
    }

    protected function restrict_to($allowed_roles = []) {
        $user_role = $this->session->userdata('role'); 
        if (!in_array($user_role, $allowed_roles)) {
            show_error('Akses Ditolak: Anda tidak memiliki wewenang untuk fitur ini.', 403, 'Akses Terlarang');
        }
    }

    // UPDATE: Pengecekan Permission menggunakan Session (Lebih Cepat!)
    protected function has_permission($permission_name) {
        $user_permissions = $this->session->userdata('permissions');
        
        // Jika session kosong (belum login/error), kembalikan false
        if (!is_array($user_permissions)) {
            return false;
        }

        // Cek apakah permission yang diminta ada di dalam array session
        return in_array($permission_name, $user_permissions);
    }
    
    // Fungsi bantuan untuk menendang user jika tidak punya permission tertentu
    protected function require_permission($permission_name) {
        if (!$this->has_permission($permission_name)) {
            show_error('Akses Ditolak: Anda tidak memiliki hak akses ('.$permission_name.') untuk melihat atau mengeksekusi halaman ini.', 403, 'Akses Terlarang');
        }
    }
}