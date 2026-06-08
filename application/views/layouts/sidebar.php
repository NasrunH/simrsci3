<?php 
    $role = strtolower($this->session->userdata('role') ?? ''); 
    $permissions = $this->session->userdata('permissions') ?? []; 
?>

<aside id="sidebar" class="w-64 bg-sidebar text-white flex flex-col shadow-xl z-30 shrink-0 transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 -translate-x-full md:translate-x-0">

    <!-- Prevent flash of uncollapsed sidebar -->
    <script>
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            const sidebar = document.getElementById('sidebar') || document.currentScript.parentElement;
            sidebar.classList.replace('w-64', 'w-20');
            sidebar.classList.add('sidebar-collapsed');
        }
    </script>

    <style>
        @media (min-width: 768px) {
            .sidebar-collapsed { width: 5rem !important; }
            .sidebar-collapsed .sidebar-text { display: none !important; }
            .sidebar-collapsed .sidebar-group-header { display: none !important; }
            .sidebar-collapsed .sidebar-divider { display: block !important; }
            .sidebar-collapsed nav a {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                gap: 0 !important;
            }
            .sidebar-collapsed .logo-container {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                gap: 0 !important;
            }
            .sidebar-collapsed .btn-logout {
                justify-content: center !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
                gap: 0 !important;
                width: auto !important;
            }
        }
    </style>

    <!-- Logo -->
    <div class="logo-container p-6 border-b border-primary-hover flex items-center gap-3 transition-all duration-300">
        <div class="bg-accent text-sidebar p-2 rounded-lg shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="sidebar-text transition-opacity duration-300 whitespace-nowrap">
            <h1 class="text-xl font-bold text-accent tracking-wide leading-none mb-1">SIRS Medika</h1>
            <p class="text-[10px] text-gray-300">Role: <?= ucfirst($role) ?></p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">

        <!-- ==================================================================== -->
        <!-- MENU KHUSUS ROLE PASIEN                                              -->
        <!-- ==================================================================== -->
        <?php if($role === 'pasien'): ?>
            
            <div class="pt-2 pb-1 sidebar-group-header">
                <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Layanan Mandiri</p>
            </div>
            <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

            <!-- Dashboard Pasien -->
            <a href="<?= base_url('portal_pasien') ?>" title="Dashboard Pasien"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'portal_pasien' && empty($this->uri->segment(2))) ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="sidebar-text font-medium text-sm">Dashboard Pasien</span>
            </a>

            <!-- MENU BARU: MONITOR ANTRIAN AKTIF -->
            <a href="<?= base_url('portal_pasien/antrean_saat_ini') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(2) == 'antrean_saat_ini') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                <span class="sidebar-text font-medium text-sm">Monitor Antrean Live</span>
            </a>

            <!-- Daftar Berobat (Poli) -->
            <a href="<?= base_url('portal_pasien/buat_antrean') ?>" title="Daftar Berobat (Poli)"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(2) == 'buat_antrean') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="sidebar-text font-medium text-sm">Daftar Berobat (Poli)</span>
            </a>

            <!-- Rekam Medis Saya -->
            <a href="<?= base_url('portal_pasien/rekam_medis') ?>" title="Rekam Medis Saya"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(2) == 'rekam_medis') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span class="sidebar-text font-medium text-sm">Rekam Medis Saya</span>
            </a>

            <!-- Riwayat Pembayaran -->
            <a href="<?= base_url('portal_pasien/billing') ?>" title="Riwayat Pembayaran"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(2) == 'billing') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <span class="sidebar-text font-medium text-sm">Riwayat Pembayaran</span>
            </a>

        <!-- ==================================================================== -->
        <!-- MENU KHUSUS ROLE ADMIN & DOKTER (LAMA)                               -->
        <!-- ==================================================================== -->
        <?php else: ?>

            <!-- ==================== DASHBOARD ==================== -->
            <a href="<?= base_url('dashboard') ?>" title="Dashboard"
               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'dashboard') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                </svg>
                <span class="sidebar-text font-medium text-sm">Dashboard</span>
            </a>

            <!-- ==================== LAYANAN (ANTRIAN & POLIKLINIK) ==================== -->
            <?php if (in_array('view_antrean', $permissions)): ?>
                <div class="pt-4 pb-1 sidebar-group-header">
                    <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Layanan</p>
                </div>
                <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

                <a href="<?= base_url('antrean') ?>" title="Antrean Poli"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'antrean') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Antrean Poli</span>
                </a>
            <?php endif; ?>

            <!-- ==================== REKAM MEDIS (EMR) ==================== -->
            <?php if (in_array('view_rm', $permissions)): ?>
                <div class="pt-4 pb-1 sidebar-group-header">
                    <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Rekam Medis (EMR)</p>
                </div>
                <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

                <a href="<?= base_url('rekam_medis') ?>" title="Pemeriksaan (SOAP)"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'rekam_medis') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Pemeriksaan (SOAP)</span>
                </a>
            <?php endif; ?>

            <!-- ==================== TRANSAKSI & RESEP ==================== -->
            <?php if (in_array('view_resep', $permissions) || in_array('view_billing', $permissions)): ?>
                <div class="pt-4 pb-1 sidebar-group-header">
                    <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Transaksi</p>
                </div>
                <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

                <?php if (in_array('view_penerimaan', $permissions)): ?>
                <a href="<?= base_url('penerimaan') ?>" title="Penerimaan Obat"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'penerimaan') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <!-- Icon Truck / Delivery -->
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Penerimaan Obat</span>
                </a>
                <?php endif; ?>

                <?php if (in_array('view_resep', $permissions)): ?>
                <a href="<?= base_url('resep') ?>" title="Resep & Peresepan"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'resep') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Resep & Peresepan</span>
                </a>
                <?php endif; ?>

                <?php if (in_array('view_billing', $permissions)): ?>
                <a href="<?= base_url('billing') ?>" title="Kasir & Billing"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'billing') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Kasir & Billing</span>
                </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- ==================== MASTER DATA ==================== -->
            <?php if (in_array('view_pasien', $permissions) || in_array('view_dokter', $permissions) || in_array('view_obat', $permissions) || in_array('view_layanan', $permissions)): ?>
                <div class="pt-4 pb-1 sidebar-group-header">
                    <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Master Data</p>
                </div>
                <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

                <?php if (in_array('view_pasien', $permissions)): ?>
                <a href="<?= base_url('pasien') ?>" title="Data Pasien"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'pasien') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Data Pasien</span>
                </a>
                <?php endif; ?>

                <?php if (in_array('view_dokter', $permissions)): ?>
                <a href="<?= base_url('dokter') ?>" title="Data Dokter"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'dokter') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 10.5V20a2 2 0 01-2 2H7a2 2 0 01-2-2v-9.5m14 0V9a2 2 0 00-2-2h-2M5 10.5V9a2 2 0 012-2h2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6m-4 6v4m-2-2h4"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Data Dokter</span>
                </a>
                <?php endif; ?>

                <?php if (in_array('view_obat', $permissions)): ?>
                <a href="<?= base_url('obat') ?>" title="Manajemen Obat"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'obat') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l7.5-7.5a5.828 5.828 0 118.243 8.243l-7.5 7.5a5.828 5.828 0 11-8.243-8.243zM9.5 9.5l5 5"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Manajemen Obat</span>
                </a>
                <?php endif; ?>

                <?php if (in_array('view_layanan', $permissions)): ?>
                <a href="<?= base_url('layanan') ?>" title="Tarif & Layanan Poli"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'layanan') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Tarif & Layanan Poli</span>
                </a>
                <?php endif; ?>

                <!-- DI DALAM SIDEBAR.PHP (Grup Master Data) -->
                <?php if (in_array('view_supplier', $permissions)): ?>
                <a href="<?= base_url('supplier') ?>" title="Data Supplier"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'supplier') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <!-- Icon Supplier (Users/Truck Group) -->
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Data Supplier</span>
                </a>
                <?php endif; ?>
            <?php endif; ?>

            <!-- ==================== LAPORAN ==================== -->
            <?php if (in_array('view_laporan', $permissions)): ?>
                <div class="pt-4 pb-1 sidebar-group-header">
                    <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Laporan</p>
                </div>
                <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

                <a href="<?= base_url('laporan') ?>" title="Laporan Pendapatan"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'laporan') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Laporan Pendapatan</span>
                </a>
            <?php endif; ?>

            <!-- ==================== SISTEM (ADMIN) ==================== -->
            <?php if (in_array('view_users', $permissions) || in_array('view_roles', $permissions)): ?>
                <div class="pt-4 pb-1 sidebar-group-header">
                    <p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Sistem</p>
                </div>
                <hr class="border-t border-primary-hover/30 my-2 sidebar-divider hidden"/>

                <?php if (in_array('view_users', $permissions)): ?>
                <a href="<?= base_url('users') ?>" title="Manajemen User"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'users') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Manajemen User</span>
                </a>
                <?php endif; ?>

                <?php if (in_array('view_roles', $permissions)): ?>
                <a href="<?= base_url('roles') ?>" title="Manajemen Role"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'roles') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span class="sidebar-text font-medium text-sm">Manajemen Role</span>
                </a>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-primary-hover bg-sidebar transition-all duration-300">
        <a href="<?= base_url('auth/logout') ?>" title="Logout"
           class="btn-logout w-full bg-danger/90 hover:bg-danger text-white py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-sm">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span class="sidebar-text transition-opacity duration-300">Logout</span>
        </a>
    </div>

</aside>

<!-- Overlay mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-20 hidden md:hidden"></div>