<?php
$b = $b ?? null;
$resep_items = $detail_resep ?? [];
$tanggal = $b->updated_at ?? $b->created_at ?? date('Y-m-d H:i:s');
?>

<div class="mb-4 flex justify-end print:hidden">
    <button type="button" onclick="window.print()" class="bg-gray-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl flex items-center gap-2">
        <i data-lucide="printer" class="w-4 h-4"></i> Cetak
    </button>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 print:shadow-none print:border-none">
    <div class="text-center border-b border-dashed border-gray-200 pb-4 mb-4">
        <h2 class="text-xl font-black text-gray-900">SIRS MEDIKA</h2>
        <p class="text-xs text-gray-500 mt-1">Kuitansi Pembayaran</p>
        <span class="inline-block mt-2 bg-green-100 text-green-700 text-[10px] font-bold px-3 py-1 rounded-full uppercase">Lunas</span>
    </div>

    <div class="space-y-3 text-sm mb-4">
        <div class="flex justify-between gap-2">
            <span class="text-gray-500">No. Invoice</span>
            <span class="font-mono font-bold text-right"><?= htmlspecialchars($b->no_invoice) ?></span>
        </div>
        <div class="flex justify-between gap-2">
            <span class="text-gray-500">Tanggal</span>
            <span class="font-medium"><?= date('d/m/Y H:i', strtotime($tanggal)) ?></span>
        </div>
        <div class="flex justify-between gap-2">
            <span class="text-gray-500">Pasien</span>
            <span class="font-bold text-right"><?= htmlspecialchars($b->nama_lengkap) ?></span>
        </div>
        <div class="flex justify-between gap-2">
            <span class="text-gray-500">No. RM</span>
            <span class="font-mono text-primary"><?= htmlspecialchars($b->no_rekam_medis) ?></span>
        </div>
    </div>

    <div class="border-t border-gray-100 pt-3 space-y-2 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-600 pr-2">Jasa dokter (<?= htmlspecialchars($b->nama_layanan ?? 'Poli') ?>)</span>
            <span class="font-semibold shrink-0">Rp <?= number_format($b->biaya_jasa_dokter ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600">Obat apotek</span>
            <span class="font-semibold">Rp <?= number_format($b->biaya_obat ?? 0, 0, ',', '.') ?></span>
        </div>
        <?php if (!empty($resep_items)): ?>
        <div class="bg-gray-50 rounded-xl p-3 mt-2 space-y-1.5">
            <?php foreach ($resep_items as $d): ?>
            <div class="flex justify-between text-xs text-gray-600">
                <span class="truncate pr-2"><?= htmlspecialchars($d->nama_obat) ?> ×<?= (int)$d->jumlah ?></span>
                <span>Rp <?= number_format($d->jumlah * $d->harga_satuan, 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="mt-4 pt-4 border-t-2 border-gray-900 flex justify-between items-center">
        <span class="font-bold text-gray-800">TOTAL</span>
        <span class="text-xl font-black text-primary">Rp <?= number_format($b->total_tagihan, 0, ',', '.') ?></span>
    </div>

    <p class="text-[10px] text-gray-400 text-center mt-6">Terima kasih atas kepercayaan Anda.</p>
</div>

<style>@media print { header, nav { display: none !important; } main { padding: 0 !important; } }</style>
