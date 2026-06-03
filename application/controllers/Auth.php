<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Role_model');
        $this->load->model('Pasien_model');
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

    public function register() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->load->view('auth/register');
    }

    public function register_process() {
        if ($this->input->post()) {
            
            // Validasi Data Akun
            $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]', [
                'is_unique' => 'Username sudah terdaftar, silakan pilih yang lain.'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]', [
                'min_length' => 'Password minimal 6 karakter.'
            ]);
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]', [
                'matches' => 'Konfirmasi password tidak sesuai dengan password.'
            ]);
            
            // Validasi Profil
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required');
            $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
            $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors(' ', ' '));
                redirect('register');
            } else {
                
                // Siapkan Data User (Role ID 3 = Pasien)
                // PENTING: Pastikan Role 'pasien' di tabel `roles` Anda adalah ID ke-3. 
                // Jika berbeda, sesuaikan angka ini.
                $data_user = [
                    'username' => $this->input->post('username', TRUE),
                    'password' => $this->input->post('password', TRUE),
                    'role_id'  => 3 
                ];

                // Siapkan Data Pasien
                $data_pasien = [
                    'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                    'tanggal_lahir' => $this->input->post('tanggal_lahir', TRUE),
                    'jenis_kelamin' => $this->input->post('jenis_kelamin', TRUE),
                    'alamat'        => $this->input->post('alamat', TRUE)
                ];
                
                // Proses Insert (Transaction)
                if ($this->Pasien_model->insert_with_user($data_user, $data_pasien)) {
                    $this->session->set_flashdata('success', 'Pendaftaran berhasil! Silakan login menggunakan akun Anda.');
                    redirect('login'); // Lempar ke halaman login
                } else {
                    $this->session->set_flashdata('error', 'Terjadi kesalahan sistem saat mendaftar.');
                    redirect('register');
                }
            }
        }
    }
}