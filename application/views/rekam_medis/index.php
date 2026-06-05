<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_rm', $permissions);
    $can_delete  = in_array('delete_rm', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Catatan Rekam Medis (EMR)</h1>
        <p class="text-gray-500 text-sm mt-1">Riwayat pemeriksaan fisik dan diagnosa pasien.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('rekam_medis/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Pemeriksaan Baru (SOAP)
    </a>
    <?php endif; ?>
</div>

<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('rekam_medis') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/2">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Pencarian</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari Nama Pasien, No RM, atau Diagnosa..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">Cari</button>
            <?php if(!empty($keyword)): ?>
                <a href="<?= base_url('rekam_medis') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium border border-gray-300">Reset</a>
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
                    <th class="py-3 px-4 font-semibold">Tgl Periksa</th>
                    <th class="py-3 px-4 font-semibold">Pasien (No RM)</th>
                    <th class="py-3 px-4 font-semibold">Dokter Pemeriksa</th>
                    <th class="py-3 px-4 font-semibold">Diagnosa Akhir</th>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($rekam_medis)): ?>
                    <?php $no = $start + 1; foreach($rekam_medis as $rm): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-medium text-gray-800"><?= date('d M Y', strtotime($rm->tanggal_periksa)) ?></td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-gray-800 block"><?= htmlspecialchars($rm->nama_lengkap) ?></span>
                            <span class="text-xs text-primary font-mono"><?= htmlspecialchars($rm->no_rekam_medis) ?></span>
                        </td>
                        <td class="py-3 px-4 text-gray-600"><?= htmlspecialchars($rm->nama_dokter) ?></td>
                        <td class="py-3 px-4 font-medium text-red-600"><?= htmlspecialchars($rm->diagnosa) ?></td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('rekam_medis/show/'.$rm->id_rm) ?>" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Buka RM</a>
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('rekam_medis/delete/'.$rm->id_rm) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">Belum ada catatan rekam medis.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="flex justify-between items-center gap-4">
    <div class="text-sm text-gray-500">Total Data: <span class="font-bold"><?= $total_rows ?></span></div>
    <div><?= $pagination ?></div>
</div>