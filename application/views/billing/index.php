<?php 
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_manage  = in_array('manage_billing', $permissions);
    $can_print   = in_array('print_invoice', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kasir & Billing Pasien</h1>
        <p class="text-gray-500 text-sm mt-1">Pembayaran obat apotek dan biaya pemeriksaan dokter.</p>
    </div>
</div>

<!-- ============================================== -->
<!-- FORM PENCARIAN & FILTER                        -->
<!-- ============================================== -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('billing') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Pencarian</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Cari No. Invoice, Nama, atau No. RM..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Status Bayar</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
                <option value="">-- Semua Status --</option>
                <option value="Belum Lunas" <?= ($status ?? '') == 'Belum Lunas' ? 'selected' : '' ?>>Belum Lunas</option>
                <option value="Lunas" <?= ($status ?? '') == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Terapkan
            </button>
            <?php if(!empty($keyword) || !empty($status)): ?>
                <a href="<?= base_url('billing') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL DATA TAGIHAN                             -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold whitespace-nowrap">No. Invoice</th>
                    <th class="py-3 px-4 font-semibold">Nama Pasien (RM)</th>
                    <th class="py-3 px-4 font-semibold text-right">Total Tagihan</th>
                    <th class="py-3 px-4 font-semibold text-center">Status</th>
                    <th class="py-3 px-4 font-semibold text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($billing)): ?>
                    <?php $no = $start + 1; foreach($billing as $b): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-mono font-bold text-gray-600"><?= $b->no_invoice ?></td>
                        <td class="py-3 px-4">
                            <span class="font-bold text-gray-800 block"><?= htmlspecialchars($b->nama_lengkap) ?></span>
                            <span class="text-xs text-gray-500 font-mono">RM: <?= htmlspecialchars($b->no_rekam_medis) ?></span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold text-primary">
                            Rp <?= number_format($b->total_tagihan, 0, ',', '.') ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <?php if($b->status == 'Lunas'): ?>
                                <span class="bg-green-100 text-green-700 border border-green-200 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Lunas</span>
                            <?php else: ?>
                                <span class="bg-amber-100 text-amber-700 border border-amber-200 px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider animate-pulse">Belum Lunas</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                <?php if($b->status == 'Belum Lunas'): ?>
                                    <?php if($can_manage): ?>
                                        <a href="<?= base_url('billing/pay/'.$b->id_billing) ?>" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-md transition-colors text-xs font-bold shadow-sm">Bayar</a>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs">Menunggu Kasir</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($can_print): ?>
                                        <a href="<?= base_url('billing/invoice/'.$b->id_billing) ?>" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md transition-colors text-xs font-bold">Kuitansi</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-10 text-gray-500">
                            Tidak ada transaksi tagihan medis yang ditemukan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="text-sm text-gray-500">
        Menampilkan <span class="font-bold text-gray-800"><?= !empty($billing) ? $start + 1 : 0 ?></span> 
        sampai <span class="font-bold text-gray-800"><?= $start + count($billing) ?></span> 
        dari total <span class="font-bold text-gray-800"><?= $total_rows ?></span> transaksi
    </div>
    <div><?= $pagination ?></div>
</div>