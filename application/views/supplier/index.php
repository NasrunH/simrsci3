<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_supplier', $permissions);
    $can_edit    = in_array('edit_supplier', $permissions);
    $can_delete  = in_array('delete_supplier', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Master Data Supplier</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar distributor utama dan pemasok resmi sediaan farmasi klinik.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('supplier/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Daftarkan Supplier
    </a>
    <?php endif; ?>
</div>

<!-- PANEL PENCARIAN -->
<div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
    <form action="<?= base_url('supplier') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari berdasarkan nama, telepon, atau alamat supplier..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors shrink-0">
            Saring Data
        </button>
        <?php if(!empty($keyword)): ?>
            <a href="<?= base_url('supplier') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- TABEL UTAMA SUPPLIER -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <th class="py-3.5 px-6 w-12 text-center">No</th>
                    <th class="py-3.5 px-6">Nama Supplier / Perusahaan</th>
                    <th class="py-3.5 px-6">No. Telepon</th>
                    <th class="py-3.5 px-6">Alamat Lengkap</th>
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3.5 px-6 text-center w-36">Tindakan</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                <?php if(!empty($suppliers)): ?>
                    <?php $no = $start + 1; foreach($suppliers as $s): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 text-center font-medium text-gray-400"><?= $no++ ?></td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-gray-800 text-base block"><?= htmlspecialchars($s->nama_supplier) ?></span>
                            <span class="text-[10px] text-gray-400 block font-mono">ID: SPL-<?= str_pad($s->id_supplier, 4, '0', STR_PAD_LEFT) ?></span>
                        </td>
                        <td class="py-4 px-6 font-semibold text-gray-600">
                            <?= htmlspecialchars($s->no_telp ?: '-') ?>
                        </td>
                        <td class="py-4 px-6 text-gray-500 leading-relaxed max-w-sm truncate" title="<?= htmlspecialchars($s->alamat) ?>">
                            <?= htmlspecialchars($s->alamat ?: '-') ?>
                        </td>
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('supplier/edit/'.$s->id_supplier) ?>" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg border border-amber-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Edit Supplier">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <?php endif; ?>
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('supplier/delete/'.$s->id_supplier) ?>" class="btn-delete inline-flex items-center gap-1 bg-red-50 text-red-700 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Hapus Supplier">
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
                        <td colspan="5" class="text-center py-12 text-gray-400">
                            Belum ada mitra supplier yang terdaftar di dalam database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINASI DATA -->
    <?php if(!empty($pagination)): ?>
    <div class="py-4 border-t border-gray-100 bg-gray-50">
        <?= $pagination ?>
    </div>
    <?php endif; ?>
</div>