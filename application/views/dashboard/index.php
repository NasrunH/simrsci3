<?php
$permissions = $permissions ?? ($this->session->userdata('permissions') ?? []);
$admin_dashboard = !empty($admin_dashboard);
?>

<!-- Header -->
<div class="mb-6 bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, <?= htmlspecialchars($this->session->userdata('username')) ?>!</h1>
        <p class="text-gray-500 mt-1">
            <?php if ($admin_dashboard): ?>
                Ringkasan operasional rumah sakit — pembaruan real-time.
            <?php else: ?>
                Anda login sebagai <span class="font-semibold text-primary capitalize"><?= htmlspecialchars($role) ?></span>.
            <?php endif; ?>
        </p>
    </div>
    <div class="flex items-center gap-3">
        <?php if ($admin_dashboard): ?>
        <a href="<?= base_url('laporan') ?>" class="text-sm font-semibold text-primary hover:underline">Laporan Lengkap &rarr;</a>
        <?php endif; ?>
        <div class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-medium text-sm border border-primary/20 whitespace-nowrap">
            <?= date('l, d M Y') ?>
        </div>
    </div>
</div>

<?php if ($admin_dashboard): ?>
<?php
    $r = $ringkasan;
    $s = $statistik;
    include __DIR__ . '/_admin_dashboard.php';
?>

<?php else: ?>
<!-- Dashboard non-admin: shortcut berdasarkan permission -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php if (in_array('create_resep', $permissions)): ?>
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:border-primary transition-colors">
        <div class="w-16 h-16 mx-auto bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
            <i data-lucide="pill" class="w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Buat Resep Baru</h3>
        <p class="text-sm text-gray-500 mt-2 mb-4">Mulai proses peresepan obat untuk pasien.</p>
        <a href="<?= base_url('resep/create') ?>" class="inline-block bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded-lg font-medium transition-colors">Buat Resep</a>
    </div>
    <?php endif; ?>

    <?php if (in_array('view_resep', $permissions)): ?>
    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:border-blue-500 transition-colors">
        <div class="w-16 h-16 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="clipboard-list" class="w-8 h-8"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Riwayat Resep</h3>
        <p class="text-sm text-gray-500 mt-2 mb-4">Lihat daftar resep di sistem.</p>
        <a href="<?= base_url('resep') ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">Lihat Riwayat</a>
    </div>
    <?php endif; ?>

    <?php if (!in_array('create_resep', $permissions) && !in_array('view_resep', $permissions) && $role !== 'admin'): ?>
    <div class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center">
        <div class="w-20 h-20 mx-auto bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-6">
            <i data-lucide="info" class="w-10 h-10"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Informasi Akses</h3>
        <p class="text-gray-500">Anda login sebagai <strong><?= ucfirst(htmlspecialchars($role)) ?></strong>. Gunakan menu navigasi untuk mengakses fitur yang diizinkan.</p>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
