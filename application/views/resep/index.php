<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Resep Obat</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar riwayat peresepan obat kepada pasien.</p>
    </div>
    
    <?php if($this->session->userdata('permissions') && in_array('create_resep', $this->session->userdata('permissions'))): ?>
    <a href="<?= base_url('resep/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Resep Baru
    </a>
    <?php endif; ?>
</div>

<!-- ============================================== -->
<!-- FORM PENCARIAN & FILTER                        -->
<!-- ============================================== -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('resep') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Cari Pasien</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Ketik Nama atau No RM..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Filter Tanggal</label>
            <input type="date" name="tanggal" value="<?= htmlspecialchars($tanggal ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Terapkan
            </button>
            <?php if(!empty($keyword) || !empty($tanggal)): ?>
                <a href="<?= base_url('resep') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL DATA RESEP                               -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-16 text-center">No</th>
                    <th class="py-3 px-4 font-semibold">Tanggal</th>
                    <th class="py-3 px-4 font-semibold">Pasien (RM)</th>
                    <?php if($this->session->userdata('role') != 'dokter'): ?>
                    <th class="py-3 px-4 font-semibold">Dokter</th>
                    <?php endif; ?>
                    <th class="py-3 px-4 font-semibold text-right">Total Tagihan</th>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($resep)): ?>
                    <?php $no = $start + 1; foreach($resep as $r): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-medium text-gray-800"><?= date('d M Y', strtotime($r->tanggal_resep)) ?></td>
                        <td class="py-3 px-4">
                            <span class="font-bold block text-gray-800"><?= htmlspecialchars($r->nama_pasien ?? 'Tidak diketahui') ?></span>
                            <span class="text-xs text-primary font-mono"><?= isset($r->no_rekam_medis) ? htmlspecialchars($r->no_rekam_medis) : '' ?></span>
                        </td>
                        
                        <?php if($this->session->userdata('role') != 'dokter'): ?>
                        <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($r->nama_dokter ?? 'Tidak diketahui') ?></td>
                        <?php endif; ?>

                        <td class="py-3 px-4 text-right font-bold text-gray-800">
                            Rp <?= number_format($r->total_harga, 0, ',', '.') ?>
                        </td>
                        
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('resep/show/'.$r->id_resep) ?>" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Detail</a>
                                
                                <?php if($this->session->userdata('permissions') && in_array('delete_resep', $this->session->userdata('permissions'))): ?>
                                <a href="<?= base_url('resep/delete/'.$r->id_resep) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= ($this->session->userdata('role') == 'dokter') ? 5 : 6 ?>" class="text-center py-8 text-gray-500">
                            Belum ada riwayat resep obat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================== -->
<!-- PAGINATION                                     -->
<!-- ============================================== -->
<?= $pagination ?>