<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Pasien</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data profil dan rekam medis pasien.</p>
    </div>
    
    <!-- Tombol Tambah hanya untuk role tertentu (opsional, saat ini semua yang masuk bisa tambah) -->
    <a href="<?= base_url('pasien/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pasien
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">No RM</th>
                    <th class="py-3 px-4 font-semibold">Nama Lengkap</th>
                    <th class="py-3 px-4 font-semibold">Tgl Lahir / Umur</th>
                    <th class="py-3 px-4 font-semibold">J. Kelamin</th>
                    <th class="py-3 px-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($pasien)): ?>
                    <?php foreach($pasien as $p): ?>
                    <?php 
                        // Hitung Umur
                        $tgl_lahir = new DateTime($p->tanggal_lahir);
                        $sekarang = new DateTime('today');
                        $umur = $tgl_lahir->diff($sekarang)->y;
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4">
                            <span class="font-bold text-primary"><?= htmlspecialchars($p->no_rekam_medis) ?></span>
                        </td>
                        <td class="py-3 px-4 font-medium"><?= htmlspecialchars($p->nama_lengkap) ?></td>
                        <td class="py-3 px-4">
                            <?= date('d/m/Y', strtotime($p->tanggal_lahir)) ?> 
                            <span class="text-xs text-gray-500 block">(<?= $umur ?> tahun)</span>
                        </td>
                        <td class="py-3 px-4">
                            <?= $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('pasien/edit/'.$p->id_pasien) ?>" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Edit</a>
                                
                                <!-- Hanya admin yang melihat tombol hapus -->
                                <?php if($this->session->userdata('role') == 'admin'): ?>
                                <a href="<?= base_url('pasien/delete/'.$p->id_pasien) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                Belum ada data pasien terdaftar.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>