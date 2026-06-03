<?php 
    $role = $this->session->userdata('role'); 
    $permissions = $this->session->userdata('permissions') ?? []; 
?>
<aside id="sidebar" class="w-64 bg-sidebar text-white flex flex-col shadow-xl z-20 shrink-0 transition-transform duration-300 fixed left-0 h-screen -translate-x-full md:translate-x-0 md:static">
    <!-- Logo -->
    <div class="p-6 border-b border-primary-hover flex items-center justify-between">
        <div class="flex items-center gap-3 flex-1">
            <div class="bg-accent text-sidebar p-2 rounded-lg">
                <i data-lucide="building-2" class="w-6 h-6"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-accent tracking-wide">SIRS Medika</h1>
                <p class="text-xs text-gray-300 capitalize">Role: <?= ucfirst($role) ?></p>
            </div>
        </div>
        <!-- Close Button (Mobile) -->
        <button id="closeSidebar" class="md:hidden text-gray-300 hover:text-white transition-colors">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Menu Links -->
    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
        <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'dashboard') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
            <i data-lucide="gauge" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        <!-- GRUP MASTER DATA -->
        <?php if(in_array('view_pasien', $permissions) || in_array('view_dokter', $permissions) || in_array('view_obat', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Master Data</p></div>
            
            <?php if(in_array('view_pasien', $permissions)): ?>
            <a href="<?= base_url('pasien') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'pasien') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Data Pasien</span>
            </a>
            <?php endif; ?>

            <?php if(in_array('view_dokter', $permissions)): ?>
            <a href="<?= base_url('dokter') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'dokter') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="stethoscope" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Data Dokter</span>
            </a>
            <?php endif; ?>

            <?php if(in_array('view_obat', $permissions)): ?>
            <a href="<?= base_url('obat') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'obat') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="pill" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Manajemen Obat</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- GRUP SISTEM (KHUSUS ADMIN) -->
        <?php if(in_array('view_users', $permissions) || in_array('view_roles', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Sistem</p></div>
            
            <?php if(in_array('view_users', $permissions)): ?>
            <a href="<?= base_url('users') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'users') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="user-cog" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Manajemen User</span>
            </a>
            <?php endif; ?>

            <?php if(in_array('view_roles', $permissions)): ?>
            <a href="<?= base_url('roles') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'roles') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="shield" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Manajemen Role</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- GRUP TRANSAKSI -->
        <?php if(in_array('view_resep', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Transaksi & Riwayat</p></div>
            <a href="<?= base_url('resep') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'resep') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="receipt" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Resep & Peresepan</span>
            </a>
        <?php endif; ?>

        <!-- GRUP LAPORAN -->
        <?php if(in_array('view_laporan', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Laporan</p></div>
            <a href="<?= base_url('laporan') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'laporan') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <i data-lucide="bar-chart-3" class="w-5 h-5 flex-shrink-0"></i>
                <span class="font-medium text-sm">Laporan Pendapatan</span>
            </a>
        <?php endif; ?>
    </nav>

    <!-- Logout Area -->
    <div class="p-4 border-t border-primary-hover bg-sidebar">
        <a href="<?= base_url('auth/logout') ?>" class="btn-logout w-full bg-danger/90 hover:bg-danger text-white py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-sm">
            <i data-lucide="log-out" class="w-4 h-4"></i>
            Logout
        </a>
    </div>
</aside>