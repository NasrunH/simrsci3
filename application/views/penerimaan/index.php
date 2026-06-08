<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_penerimaan', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Penerimaan Obat Supplier</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola dan input log obat masuk untuk menjaga ketersediaan apotek.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('penerimaan/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Input Obat Masuk
    </a>
    <?php endif; ?>
</div>

<!-- FILTER PENCARIAN -->
<div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm mb-6">
    <form action="<?= base_url('penerimaan') ?>" method="GET" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1">
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari nomor faktur atau nama supplier..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
        </div>
        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-semibold transition-colors shrink-0">
            Saring Data
        </button>
        <?php if(!empty($keyword)): ?>
            <a href="<?= base_url('penerimaan') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold transition-colors flex items-center justify-center">Reset</a>
        <?php endif; ?>
    </form>
</div>

<!-- TABEL HISTORI -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200">
                    <th class="py-3 px-6 w-12 text-center">No</th>
                    <th class="py-3 px-6">No. Faktur</th>
                    <th class="py-3 px-6">Nama Supplier</th>
                    <th class="py-3 px-6">Tanggal Terima</th>
                    <th class="py-3 px-6 text-center">Total Item</th>
                    <th class="py-3 px-6 text-right">Nilai Faktur</th>
                    <th class="py-3 px-6">Penerima (Kasir)</th>
                    <th class="py-3 px-6 text-center w-28">Tindakan</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 divide-y divide-gray-100">
                <?php if(!empty($penerimaan)): ?>
                    <?php $no = $start + 1; foreach($penerimaan as $p): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 text-center font-medium text-gray-400"><?= $no++ ?></td>
                        <td class="py-4 px-6 font-mono font-bold text-gray-800"><?= htmlspecialchars($p->no_faktur) ?></td>
                        <td class="py-4 px-6 font-semibold"><?= htmlspecialchars($p->nama_supplier) ?></td>
                        <td class="py-4 px-6 text-gray-500"><?= date('d M Y', strtotime($p->tanggal_penerimaan)) ?></td>
                        <td class="py-4 px-6 text-center font-bold text-slate-800"><?= number_format($p->total_item) ?> unit</td>
                        <td class="py-4 px-6 text-right font-black text-primary">Rp <?= number_format($p->total_harga, 0, ',', '.') ?></td>
                        <td class="py-4 px-6 text-gray-500"><?= htmlspecialchars($p->nama_petugas ?? 'Petugas Apotek') ?></td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center">
                                <a href="<?= base_url('penerimaan/show/'.$p->id_penerimaan) ?>" class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 hover:bg-blue-100 px-2.5 py-1.5 rounded-lg border border-blue-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Lihat Detail Rincian">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    <span>Lihat Rincian</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-12 text-gray-400">
                            Belum ada log data penerimaan obat yang tercatat.
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