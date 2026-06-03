<?php 
    $can_create = in_array('create_pasien', $this->session->userdata('permissions') ?? []);
    $can_edit   = in_array('edit_pasien', $this->session->userdata('permissions') ?? []);
    $can_delete = in_array('delete_pasien', $this->session->userdata('permissions') ?? []);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Pasien</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola data profil dan rekam medis pasien.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('pasien/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Pasien
    </a>
    <?php endif; ?>
</div>

<!-- ============================================== -->
<!-- FORM PENCARIAN & FILTER                        -->
<!-- ============================================== -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('pasien') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Cari Pasien</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Ketik Nama atau No RM..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Filter Jenis Kelamin</label>
            <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
                <option value="">-- Semua --</option>
                <option value="L" <?= ($jenis_kelamin ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                <option value="P" <?= ($jenis_kelamin ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Terapkan
            </button>
            <?php if(!empty($keyword) || !empty($jenis_kelamin)): ?>
                <a href="<?= base_url('pasien') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL DATA PASIEN                              -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">No RM</th>
                    <th class="py-3 px-4 font-semibold">Nama Lengkap</th>
                    <th class="py-3 px-4 font-semibold">Tgl Lahir / Umur</th>
                    <th class="py-3 px-4 font-semibold">J. Kelamin</th>
                    
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($pasien)): ?>
                    <?php $no = $start + 1; foreach($pasien as $p): ?>
                    <?php 
                        $tgl_lahir = new DateTime($p->tanggal_lahir);
                        $sekarang = new DateTime('today');
                        $umur = $tgl_lahir->diff($sekarang)->y;
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-primary"><?= htmlspecialchars($p->no_rekam_medis) ?></span>
                        </td>
                        <td class="py-3 px-4 font-medium"><?= htmlspecialchars($p->nama_lengkap) ?></td>
                        <td class="py-3 px-4">
                            <?= date('d/m/Y', strtotime($p->tanggal_lahir)) ?> 
                            <span class="text-xs text-gray-500 block">(<?= $umur ?> tahun)</span>
                        </td>
                        <td class="py-3 px-4">
                            <?php if($p->jenis_kelamin == 'L'): ?>
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-semibold">Laki-laki</span>
                            <?php else: ?>
                                <span class="bg-pink-100 text-pink-700 px-2 py-1 rounded-md text-xs font-semibold">Perempuan</span>
                            <?php endif; ?>
                        </td>
                        
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <?php if($can_edit): ?>
                                <a href="<?= base_url('pasien/edit/'.$p->id_pasien) ?>" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Edit</a>
                                <?php endif; ?>
                                
                                <?php if($can_delete): ?>
                                <a href="<?= base_url('pasien/delete/'.$p->id_pasien) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <?php $colspan = ($can_edit || $can_delete) ? 6 : 5; ?>
                        <td colspan="<?= $colspan ?>" class="text-center py-8 text-gray-500">
                            Tidak ada data pasien yang sesuai dengan filter pencarian.
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
        Menampilkan <span class="font-bold text-gray-800"><?= !empty($pasien) ? $start + 1 : 0 ?></span> 
        sampai <span class="font-bold text-gray-800"><?= $start + count($pasien) ?></span> 
        dari total <span class="font-bold text-gray-800"><?= $total_rows ?></span> pasien
    </div>
    
    <div>
        <?= $pagination ?>
    </div>
</div>