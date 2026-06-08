<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_dokter', $permissions);
    $can_edit    = in_array('edit_dokter', $permissions);
    $can_delete  = in_array('delete_dokter', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Dokter</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data dokter terdaftar beserta unit tugas poliklinik.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('dokter/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Dokter
    </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold">Nama Dokter</th>
                    <th class="py-3 px-4 font-semibold">Poliklinik / Layanan</th>
                    <th class="py-3 px-4 font-semibold">Spesialisasi</th>
                    <th class="py-3 px-4 font-semibold">No. Telepon</th>
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($dokter)): ?>
                    <?php $no = 1; foreach($dokter as $d): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-bold text-gray-800"><?= htmlspecialchars($d->nama_dokter) ?></td>
                        <td class="py-3 px-4">
                            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 rounded-md text-xs font-bold">
                                <?= htmlspecialchars($d->nama_layanan ?: 'Belum Diatur') ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($d->spesialisasi) ?></td>
                        <td class="py-3 px-4 text-gray-500"><?= htmlspecialchars($d->no_telp) ?></td>
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('dokter/edit/'.$d->id_dokter) ?>" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg border border-amber-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Edit Dokter">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                    <span>Edit</span>
                                </a>
                                <?php endif; ?>
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('dokter/delete/'.$d->id_dokter) ?>" class="btn-delete inline-flex items-center gap-1 bg-red-50 text-red-700 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Hapus Dokter">
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
                        <td colspan="6" class="text-center py-8 text-gray-500">Belum ada data dokter terdaftar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>