<?php 
    $can_create = in_array('create_dokter', $this->session->userdata('permissions') ?? []);
    $can_edit   = in_array('edit_dokter', $this->session->userdata('permissions') ?? []);
    $can_delete = in_array('delete_dokter', $this->session->userdata('permissions') ?? []);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Data Dokter</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data profil dokter beserta akses akunnya.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('dokter/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Dokter Baru
    </a>
    <?php endif; ?>
</div>

<!-- ============================================== -->
<!-- FORM PENCARIAN & FILTER                        -->
<!-- ============================================== -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('dokter') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Cari Dokter</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Ketik Nama atau Spesialisasi..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Filter Spesialisasi</label>
            <select name="spesialisasi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
                <option value="">-- Semua --</option>
                <?php foreach($daftar_spesialisasi as $spec): ?>
                <option value="<?= htmlspecialchars($spec->spesialisasi) ?>" <?= ($spesialisasi ?? '') == $spec->spesialisasi ? 'selected' : '' ?>>
                    <?= htmlspecialchars($spec->spesialisasi) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Terapkan
            </button>
            <?php if(!empty($keyword) || !empty($spesialisasi)): ?>
                <a href="<?= base_url('dokter') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL DATA DOKTER                              -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold">Nama Dokter</th>
                    <th class="py-3 px-4 font-semibold">Spesialisasi</th>
                    <th class="py-3 px-4 font-semibold">No. Telepon</th>
                    
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($dokter)): ?>
                    <?php $no = $start + 1; foreach($dokter as $d): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-medium"><?= htmlspecialchars($d->nama_dokter) ?></td>
                        <td class="py-3 px-4">
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <?= htmlspecialchars($d->spesialisasi) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4"><?= htmlspecialchars($d->no_telp) ?></td>
                        
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('dokter/edit/'.$d->id_dokter) ?>" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Edit</a>
                                <?php endif; ?>
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('dokter/delete/'.$d->id_dokter) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= ($can_edit || $can_delete) ? 5 : 4 ?>" class="text-center py-8 text-gray-500">Belum ada data dokter terdaftar.</td>
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