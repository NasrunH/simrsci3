<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
*/

// ==========================================
// DEFAULT CONTROLLER & SETUP
// ==========================================
// Mengarahkan akses root (domain.com/) langsung ke Auth Controller
$route['default_controller']   = 'auth'; 
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

// ==========================================
// AUTHENTICATION ROUTES
// ==========================================
$route['login']            = 'auth/index';
$route['login_process']    = 'auth/login_process';
$route['logout']           = 'auth/logout';
$route['register']         = 'auth/register';          // <--- Tambahkan Baris Ini
$route['register_process'] = 'auth/register_process';  // <--- Tambahkan Baris Ini
// ==========================================
// DASHBOARD
// ==========================================
$route['dashboard'] = 'dashboard/index';

// ==========================================
// MANAJEMEN USER & ROLE (ADMIN ONLY)
// ==========================================
// Users
$route['users']               = 'users/index';
$route['users/create']        = 'users/create';
$route['users/edit/(:num)']   = 'users/edit/$1';
$route['users/delete/(:num)'] = 'users/delete/$1';

// Roles
$route['roles']               = 'roles/index';
$route['roles/create']        = 'roles/create';
$route['roles/edit/(:num)']   = 'roles/edit/$1';
$route['roles/delete/(:num)'] = 'roles/delete/$1';

// ==========================================
// MASTER DATA (PASIEN, DOKTER, OBAT)
// ==========================================
// Pasien
$route['pasien']               = 'pasien/index';
$route['pasien/create']        = 'pasien/create';
$route['pasien/edit/(:num)']   = 'pasien/edit/$1';
$route['pasien/delete/(:num)'] = 'pasien/delete/$1';
$route['pasien/show/(:num)']   = 'pasien/show/$1';

// Dokter
$route['dokter']               = 'dokter/index';
$route['dokter/create']        = 'dokter/create';
$route['dokter/edit/(:num)']   = 'dokter/edit/$1';
$route['dokter/delete/(:num)'] = 'dokter/delete/$1';
$route['dokter/show/(:num)']   = 'dokter/show/$1';

// Obat
$route['obat']               = 'obat/index';
$route['obat/create']        = 'obat/create';
$route['obat/edit/(:num)']   = 'obat/edit/$1';
$route['obat/delete/(:num)'] = 'obat/delete/$1';

// ==========================================
// TRANSAKSI (RESEP)
// ==========================================
$route['resep']               = 'resep/index';
$route['resep/create']        = 'resep/create';
$route['resep/show/(:num)']   = 'resep/show/$1';
// Catatan: Biasanya resep yang sudah dicetak/ditebus tidak boleh diedit/dihapus 
// demi rekam medis yang valid. Jika butuh, tambahkan route edit/delete di sini.

// ==========================================
// LAPORAN
// ==========================================
$route['laporan'] = 'laporan/index';