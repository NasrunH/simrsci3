<!-- Header Selamat Datang -->
<div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, <?= $this->session->userdata('username') ?>!</h1>
        <p class="text-gray-500 mt-1">Anda login ke sistem sebagai <span class="font-semibold text-primary capitalize"><?= $role ?></span>.</p>
    </div>
    <div class="hidden md:block">
        <div class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-medium text-sm border border-primary/20">
            <?= date('d M Y') ?>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- TAMPILAN KHUSUS ADMIN                          -->
<!-- ============================================== -->
<?php if($role == 'admin'): ?>
    
    <!-- Widget Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 hover:shadow-md transition-shadow">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Resep Hari Ini</h3>
            <p class="text-3xl font-bold text-gray-800 mt-3"><?= isset($ringkasan->jumlah_resep) ? $ringkasan->jumlah_resep : 0 ?></p>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500 hover:shadow-md transition-shadow">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Pendapatan Hari Ini</h3>
            <p class="text-3xl font-bold text-gray-800 mt-3">Rp <?= number_format($ringkasan->pendapatan ?? 0, 0, ',', '.') ?></p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-teal-500 hover:shadow-md transition-shadow">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Akses Kelola</h3>
            <div class="mt-3">
                <a href="<?= base_url('pasien') ?>" class="text-teal-600 text-sm font-semibold hover:underline block">Kelola Pasien &rarr;</a>
                <a href="<?= base_url('obat') ?>" class="text-teal-600 text-sm font-semibold hover:underline block mt-1">Kelola Obat &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Tabel 5 Obat Terlaris -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800 text-sm uppercase">5 Obat Paling Sering Diresepkan</h3>
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-gray-200">
                    <th class="py-3 px-6 font-semibold">Nama Obat</th>
                    <th class="py-3 px-6 font-semibold text-right">Total Diresepkan</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($obat_terlaris)): ?>
                    <?php foreach($obat_terlaris as $ot): ?>
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="py-3 px-6 font-medium"><?= htmlspecialchars($ot->nama_obat) ?></td>
                        <td class="py-3 px-6 text-right">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-bold">
                                <?= $ot->total_terjual ?> Pcs
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center py-6 text-gray-500">Belum ada data resep.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<!-- ============================================== -->
<!-- TAMPILAN KHUSUS DOKTER                         -->
<!-- ============================================== -->
<?php elseif($role == 'dokter'): ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:border-primary transition-colors">
            <div class="w-16 h-16 mx-auto bg-primary/10 text-primary rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Buat Resep Baru</h3>
            <p class="text-sm text-gray-500 mt-2 mb-4">Mulai proses peresepan obat untuk pasien setelah melakukan diagnosa.</p>
            <a href="<?= base_url('resep/create') ?>" class="inline-block bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Buat Resep
            </a>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 text-center hover:border-primary transition-colors">
            <div class="w-16 h-16 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Riwayat Resep</h3>
            <p class="text-sm text-gray-500 mt-2 mb-4">Lihat daftar resep yang pernah Anda berikan kepada pasien sebelumnya.</p>
            <a href="<?= base_url('resep') ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Lihat Riwayat
            </a>
        </div>
    </div>

<!-- ============================================== -->
<!-- TAMPILAN KHUSUS PASIEN                         -->
<!-- ============================================== -->
<?php elseif($role == 'pasien'): ?>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center max-w-2xl mx-auto mt-10">
        <div class="w-20 h-20 mx-auto bg-teal-50 text-teal-600 rounded-full flex items-center justify-center mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Riwayat Rekam Medis</h3>
        <p class="text-gray-500 mb-6">Anda dapat melihat daftar riwayat obat yang telah diresepkan oleh dokter kepada Anda pada menu resep.</p>
        <a href="<?= base_url('resep') ?>" class="inline-block bg-teal-600 hover:bg-teal-700 text-white px-8 py-2.5 rounded-lg font-medium transition-colors shadow-sm">
            Lihat Riwayat Obat Saya
        </a>
    </div>

<?php endif; ?>