<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model'); // Pastikan Role_model dimuat
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/login');
    }

    public function login_process() {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        $user = $this->User_model->get_by_username($username);

        if ($user && password_verify($password, $user->password)) {
            
            // AMBIL SEMUA PERMISSIONS MILIK USER INI
            $permissions = $this->Role_model->get_role_permissions($user->role_id);

            $session_data = [
                'id_user'     => $user->id_user,
                'username'    => $user->username,
                'role_id'     => $user->role_id,
                'role'        => $user->role_name,
                'permissions' => $permissions, // SIMPAN ARRAY PERMISSION KE SESSION
                'logged_in'   => TRUE
            ];
            $this->session->set_userdata($session_data);
            
            $this->session->set_flashdata('success', 'Selamat datang kembali, ' . $user->username);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('error', 'Username atau password salah.');
            redirect('auth');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth');
    }
}