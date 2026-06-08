<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_obat', $permissions);
    $can_edit    = in_array('edit_obat', $permissions);
    $can_delete  = in_array('delete_obat', $permissions);

    if (!function_exists('format_stok')) {
        function format_stok($stok) {
            $stok = (float)$stok;
            $formatted = number_format($stok, 2, ',', '.');
            if (strpos($formatted, ',') !== false) {
                $formatted = rtrim(rtrim($formatted, '0'), ',');
            }
            return $formatted;
        }
    }
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Master Obat</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar sediaan farmasi, alkes, ketersediaan stok, serta rekanan supplier.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('obat/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Obat & Alkes
    </a>
    <?php endif; ?>
</div>

<!-- FILTER -->
<div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
    <form action="<?= base_url('obat') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari obat berdasarkan kode, nama, kategori, atau nama supplier..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors shrink-0">
            Saring Data
        </button>
        <?php if(!empty($keyword)): ?>
            <a href="<?= base_url('obat') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- DATA TABEL -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <th class="py-3 px-6 w-12 text-center">No</th>
                    <th class="py-3 px-6">Kode Item</th>
                    <th class="py-3 px-6">Nama Obat / Alkes</th>
                    <th class="py-3 px-6">Kategori</th>
                    <th class="py-3 px-6">Supplier Utama</th> <!-- KOLOM BARU -->
                    <th class="py-3 px-6 text-center">Sisa Stok</th>
                    <th class="py-3 px-6">Satuan</th>
                    <th class="py-3 px-6 text-right">Harga Jual</th>
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-6 text-center w-32">Pilihan</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                <?php if(!empty($obat)): ?>
                    <?php $no = $start + 1; foreach($obat as $o): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 text-center font-medium text-gray-400"><?= $no++ ?></td>
                        <td class="py-4 px-6 font-mono font-bold text-gray-800"><?= htmlspecialchars($o->kode_obat) ?></td>
                        <td class="py-4 px-6 font-semibold"><?= htmlspecialchars($o->nama_obat) ?></td>
                        <td class="py-4 px-6">
                            <span class="bg-slate-100 text-slate-700 text-xs px-2.5 py-1 rounded-full font-medium"><?= htmlspecialchars($o->kategori) ?></span>
                        </td>
                        <!-- FITUR BARU: Tampilkan Nama Supplier Utama -->
                        <td class="py-4 px-6">
                            <span class="text-gray-600 font-medium text-xs">
                                <?= htmlspecialchars($o->nama_supplier ?? 'Tanpa Supplier') ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center font-bold">
                            <span class="<?= ($o->stok <= 5) ? 'text-red-500 font-extrabold animate-pulse' : 'text-slate-800' ?>">
                                <?= format_stok($o->stok) ?>
                            </span>
                        </td>
                        <td class="py-4 px-6 text-gray-500"><?= htmlspecialchars($o->satuan) ?></td>
                        <td class="py-4 px-6 text-right font-bold text-primary">Rp <?= number_format($o->harga, 0, ',', '.') ?></td>
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('obat/edit/'.$o->id_obat) ?>" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg border border-amber-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Edit Obat">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <?php endif; ?>
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('obat/delete/'.$o->id_obat) ?>" class="btn-delete inline-flex items-center gap-1 bg-red-50 text-red-700 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Hapus Obat">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    <span>Hapus</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-12 text-gray-400">
                            Tidak ada data obat atau alkes yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINASI -->
    <?php if(!empty($pagination)): ?>
    <div class="py-4 border-t border-gray-100 bg-gray-50">
        <?= $pagination ?>
    </div>
    <?php endif; ?>
</div>