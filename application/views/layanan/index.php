<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_layanan', $permissions);
    $can_edit    = in_array('edit_layanan', $permissions);
    $can_delete  = in_array('delete_layanan', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Layanan Medis</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar unit poliklinik beserta tarif konsultasi dokter.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('layanan/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Layanan
    </a>
    <?php endif; ?>
</div>

<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('layanan') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Cari Layanan</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Nama layanan..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">Terapkan</button>
            <?php if(!empty($keyword)): ?>
                <a href="<?= base_url('layanan') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300">Reset</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold">Nama Poliklinik / Layanan</th>
                    <th class="py-3 px-4 font-semibold">Deskripsi</th>
                    <th class="py-3 px-4 font-semibold text-right">Tarif Jasa Medis</th>
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($layanan)): ?>
                    <?php $no = $start + 1; foreach($layanan as $l): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-bold text-gray-800"><?= htmlspecialchars($l->nama_layanan) ?></td>
                        <td class="py-3 px-4 text-gray-500 text-xs sm:text-sm"><?= htmlspecialchars($l->deskripsi ?: '-') ?></td>
                        <td class="py-3 px-4 text-right font-bold text-primary">Rp <?= number_format($l->tarif, 0, ',', '.') ?></td>
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('layanan/edit/'.$l->id_layanan) ?>" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg border border-amber-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Edit Layanan">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <?php endif; ?>
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('layanan/delete/'.$l->id_layanan) ?>" class="btn-delete inline-flex items-center gap-1 bg-red-50 text-red-700 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Hapus Layanan">
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
                        <td colspan="5" class="text-center py-8 text-gray-500">Belum ada layanan terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="text-sm text-gray-500">Total Data: <span class="font-bold"><?= $total_rows ?></span></div>
    <div><?= $pagination ?></div>
</div>