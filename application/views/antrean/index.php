<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_antrean', $permissions);
    $can_edit    = in_array('edit_rm', $permissions); // Akses dokter mengisi EMR
    $role        = strtolower($this->session->userdata('role'));
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Antrean Pelayanan Poliklinik</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar kunjungan pasien hari ini: <strong><?= date('d M Y') ?></strong></p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('antrean/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Daftar Kunjungan Poli
    </a>
    <?php endif; ?>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-16 text-center">No Antrean</th>
                    <th class="py-3 px-4 font-semibold">Nama Pasien / No RM</th>
                    <th class="py-3 px-4 font-semibold">Tujuan Poli</th>
                    <th class="py-3 px-4 font-semibold">Dokter / Spesialisasi</th>
                    <th class="py-3 px-4 font-semibold">Keluhan Awal</th>
                    <th class="py-3 px-4 font-semibold text-center w-32">Status</th>
                    <?php if($role !== 'pasien'): ?>
                    <th class="py-3 px-4 font-semibold text-center w-48">Tindakan Medis / Panggilan</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($antrean)): ?>
                    <?php foreach($antrean as $a): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-4 text-center font-mono font-bold text-lg text-primary"><?= htmlspecialchars($a->no_antrean ?? '') ?></td>
                        <td class="py-4 px-4">
                            <!-- PHP 8.1 Fix: Gunakan ?? '' untuk mengantisipasi nilai null dari database -->
                            <span class="font-bold text-gray-800 block"><?= htmlspecialchars($a->nama_pasien ?? '') ?></span>
                            <span class="text-xs text-primary font-mono block"><?= htmlspecialchars($a->no_rekam_medis ?? '') ?></span>
                        </td>
                        <td class="py-4 px-4">
                            <span class="bg-blue-50 text-blue-700 border border-blue-100 px-2.5 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                <?= htmlspecialchars($a->nama_layanan ?? 'Poli Umum') ?>
                            </span>
                        </td>
                        <td class="py-4 px-4 text-gray-600">
                            <span class="font-semibold block"><?= htmlspecialchars($a->nama_dokter ?? '') ?></span>
                            <span class="text-xs text-gray-400 block"><?= htmlspecialchars($a->spesialisasi ?? 'Umum') ?></span>
                        </td>
                        <td class="py-4 px-4 text-gray-500 text-xs sm:text-sm italic max-w-xs truncate" title="<?= htmlspecialchars($a->keluhan_awal ?? '') ?>">
                            "<?= htmlspecialchars($a->keluhan_awal ?? '') ?>"
                        </td>
                        <td class="py-4 px-4 text-center">
                            <?php 
                                $statusVal = $a->status ?? 'Menunggu';
                                $statusClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                if($statusVal == 'Menunggu') $statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                elseif($statusVal == 'Diperiksa') $statusClass = 'bg-blue-100 text-blue-700 border-blue-200';
                                elseif($statusVal == 'Selesai') $statusClass = 'bg-green-100 text-green-700 border-green-200';
                                elseif($statusVal == 'Batal') $statusClass = 'bg-red-100 text-red-700 border-red-200';
                            ?>
                            <span class="<?= $statusClass ?> border px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap"><?= $statusVal ?></span>
                        </td>
                        
                        <?php if($role !== 'pasien'): ?>
                        <td class="py-4 px-4 text-center">
                            <div class="flex justify-center gap-1.5">
                                <?php if(($a->status ?? 'Menunggu') == 'Menunggu'): ?>
                                    <a href="<?= base_url('antrean/update_status/'.$a->id_antrean.'/Diperiksa') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm">Panggil & Periksa</a>
                                    <a href="<?= base_url('antrean/update_status/'.$a->id_antrean.'/Batal') ?>" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg text-xs font-bold">Batal</a>
                                <?php elseif(($a->status ?? 'Menunggu') == 'Diperiksa'): ?>
                                    <?php if($can_edit): ?>
                                        <a href="<?= base_url('rekam_medis/create?pasien='.$a->id_pasien.'&dokter='.$a->id_dokter) ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm">Isi Rekam Medis (SOAP)</a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic">Sedang Diperiksa</span>
                                    <?php endif; ?>
                                    <a href="<?= base_url('antrean/update_status/'.$a->id_antrean.'/Selesai') ?>" class="text-gray-500 hover:text-gray-700 hover:underline text-xs font-semibold ml-2">Tandai Selesai</a>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs font-semibold">-</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= $role !== 'pasien' ? 7 : 6 ?>" class="text-center py-8 text-gray-500">
                            Belum ada pasien yang mengantre pada poliklinik Anda hari ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>