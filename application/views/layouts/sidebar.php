<?php 
    $role = $this->session->userdata('role'); 
    $permissions = $this->session->userdata('permissions') ?? []; 
?>
<aside class="w-64 bg-sidebar text-white flex flex-col shadow-xl z-20 shrink-0">
    <!-- Logo -->
    <div class="p-6 border-b border-primary-hover flex items-center gap-3">
        <div class="bg-accent text-sidebar p-2 rounded-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-accent tracking-wide">SIRS Medika</h1>
            <p class="text-xs text-gray-300 capitalize">Role: <?= ucfirst($role) ?></p>
        </div>
    </div>

    <!-- Menu Links -->
    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
        <a href="<?= base_url('dashboard') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors <?= ($this->uri->segment(1) == 'dashboard') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
            <span class="font-medium text-sm">Dashboard</span>
        </a>

        <!-- GRUP MASTER DATA -->
        <?php if(in_array('view_pasien', $permissions) || in_array('view_dokter', $permissions) || in_array('view_obat', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Master Data</p></div>
            
            <?php if(in_array('view_pasien', $permissions)): ?>
            <a href="<?= base_url('pasien') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'pasien') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>"><span class="font-medium text-sm">Data Pasien</span></a>
            <?php endif; ?>

            <?php if(in_array('view_dokter', $permissions)): ?>
            <a href="<?= base_url('dokter') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'dokter') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>"><span class="font-medium text-sm">Data Dokter</span></a>
            <?php endif; ?>

            <?php if(in_array('view_obat', $permissions)): ?>
            <a href="<?= base_url('obat') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'obat') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>"><span class="font-medium text-sm">Manajemen Obat</span></a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- GRUP SISTEM (KHUSUS ADMIN) -->
        <?php if(in_array('view_users', $permissions) || in_array('view_roles', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Sistem</p></div>
            
            <?php if(in_array('view_users', $permissions)): ?>
            <a href="<?= base_url('users') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'users') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>"><span class="font-medium text-sm">Manajemen User</span></a>
            <?php endif; ?>

            <?php if(in_array('view_roles', $permissions)): ?>
            <a href="<?= base_url('roles') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'roles') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>"><span class="font-medium text-sm">Manajemen Role</span></a>
            <?php endif; ?>
        <?php endif; ?>

        <!-- GRUP TRANSAKSI -->
        <?php if(in_array('view_resep', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Transaksi & Riwayat</p></div>
            <a href="<?= base_url('resep') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'resep') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>">
                <span class="font-medium text-sm">Resep & Peresepan</span>
            </a>
        <?php endif; ?>

        <!-- GRUP LAPORAN -->
        <?php if(in_array('view_laporan', $permissions)): ?>
            <div class="pt-4 pb-1"><p class="px-4 text-xs font-semibold text-accent uppercase opacity-60">Laporan</p></div>
            <a href="<?= base_url('laporan') ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?= ($this->uri->segment(1) == 'laporan') ? 'bg-primary border-l-4 border-accent' : 'hover:bg-primary-hover' ?>"><span class="font-medium text-sm">Laporan Pendapatan</span></a>
        <?php endif; ?>
    </nav>

    <!-- Logout Area -->
    <div class="p-4 border-t border-primary-hover bg-sidebar">
        <a href="<?= base_url('auth/logout') ?>" class="btn-logout w-full bg-danger/90 hover:bg-danger text-white py-2.5 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            Logout
        </a>
    </div>
</aside>