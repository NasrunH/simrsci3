<?php 
    // Ambil permissions dari session untuk mengatur tampilan tombol
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_obat', $permissions);
    $can_edit    = in_array('edit_obat', $permissions);
    $can_delete  = in_array('delete_obat', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Inventaris Obat</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data stok dan harga obat di apotek.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('obat/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Obat
    </a>
    <?php endif; ?>
</div>

<!-- ============================================== -->
<!-- FORM PENCARIAN & FILTER                        -->
<!-- ============================================== -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('obat') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Cari Obat</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Kode atau Nama Obat..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Filter Kategori</label>
            <select name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
                <option value="">-- Semua Kategori --</option>
                <option value="Tablet" <?= ($kategori ?? '') == 'Tablet' ? 'selected' : '' ?>>Tablet</option>
                <option value="Kapsul" <?= ($kategori ?? '') == 'Kapsul' ? 'selected' : '' ?>>Kapsul</option>
                <option value="Sirup" <?= ($kategori ?? '') == 'Sirup' ? 'selected' : '' ?>>Sirup</option>
                <option value="Salep" <?= ($kategori ?? '') == 'Salep' ? 'selected' : '' ?>>Salep</option>
                <option value="Injeksi" <?= ($kategori ?? '') == 'Injeksi' ? 'selected' : '' ?>>Injeksi</option>
                <option value="Lainnya" <?= ($kategori ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Terapkan
            </button>
            <?php if(!empty($keyword) || !empty($kategori)): ?>
                <a href="<?= base_url('obat') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL DATA OBAT                                -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">Kode Obat</th>
                    <th class="py-3 px-4 font-semibold">Nama Obat</th>
                    <th class="py-3 px-4 font-semibold">Kategori</th>
                    <th class="py-3 px-4 font-semibold text-center">Stok</th>
                    <th class="py-3 px-4 font-semibold text-right">Harga (Rp)</th>
                    
                    <!-- Sembunyikan kolom Aksi jika user tidak punya izin edit ATAU delete -->
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($obat)): ?>
                    <?php $no = $start + 1; foreach($obat as $o): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-mono text-xs font-semibold text-gray-500"><?= htmlspecialchars($o->kode_obat) ?></td>
                        <td class="py-3 px-4 font-bold text-gray-800"><?= htmlspecialchars($o->nama_obat) ?></td>
                        <td class="py-3 px-4">
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-md text-xs font-medium">
                                <?= htmlspecialchars($o->kategori) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <?php if($o->stok <= 5): ?>
                                <span class="bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-xs font-bold" title="Stok Menipis!"><?= $o->stok ?></span>
                            <?php else: ?>
                                <span class="font-bold text-gray-700"><?= $o->stok ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-right font-medium text-primary">
                            <?= number_format($o->harga, 0, ',', '.') ?>
                        </td>
                        
                        <!-- Logika Dinamis Tombol Aksi -->
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('obat/edit/'.$o->id_obat) ?>" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Edit</a>
                                <?php endif; ?>
                                
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('obat/delete/'.$o->id_obat) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                        
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <?php $colspan = ($can_edit || $can_delete) ? 7 : 6; ?>
                        <td colspan="<?= $colspan ?>" class="text-center py-8 text-gray-500">
                            Data obat tidak ditemukan. Coba sesuaikan kata kunci atau filter pencarian Anda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================== -->
<!-- PAGINATION & INFO                              -->
<!-- ============================================== -->
<div class="flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="text-sm text-gray-500">
        Menampilkan <span class="font-bold text-gray-800"><?= !empty($obat) ? $start + 1 : 0 ?></span> 
        sampai <span class="font-bold text-gray-800"><?= $start + count($obat) ?></span> 
        dari total <span class="font-bold text-gray-800"><?= $total_rows ?></span> obat
    </div>
    
    <div>
        <?= $pagination ?>
    </div>
</div>