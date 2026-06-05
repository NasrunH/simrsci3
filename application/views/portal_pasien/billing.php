<p class="text-sm text-gray-500 mb-4">Riwayat tagihan konsultasi dan obat.</p>

<?php if (!empty($billing)): ?>
<div class="space-y-3">
    <?php foreach ($billing as $b): ?>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 flex justify-between items-start gap-3 border-b border-gray-50">
            <div class="min-w-0">
                <p class="text-[10px] text-gray-400 font-bold uppercase">Invoice</p>
                <p class="font-mono font-bold text-sm text-gray-800 truncate"><?= htmlspecialchars($b->no_invoice) ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= date('d M Y, H:i', strtotime($b->created_at)) ?></p>
            </div>
            <?php if ($b->status === 'Lunas'): ?>
            <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full shrink-0">Lunas</span>
            <?php else: ?>
            <span class="bg-amber-100 text-amber-700 text-[10px] font-bold px-2.5 py-1 rounded-full shrink-0 animate-pulse">Belum Lunas</span>
            <?php endif; ?>
        </div>
        <div class="p-4 flex items-center justify-between">
            <span class="text-sm text-gray-600 font-medium">Total</span>
            <span class="text-lg font-black text-gray-900">Rp <?= number_format($b->total_tagihan, 0, ',', '.') ?></span>
        </div>
        <div class="px-4 pb-4">
            <?php if ($b->status === 'Lunas'): ?>
            <a href="<?= base_url('portal_pasien/invoice/'.$b->id_billing) ?>"
               class="flex items-center justify-center gap-2 w-full bg-primary/10 text-primary font-bold py-3 rounded-xl text-sm active:bg-primary/20">
                <i data-lucide="receipt" class="w-4 h-4"></i> Lihat Kuitansi
            </a>
            <?php else: ?>
            <div class="w-full bg-amber-50 border border-amber-200 text-amber-800 font-semibold py-3 rounded-xl text-sm text-center">
                Bayar di kasir rumah sakit
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="bg-white rounded-2xl border border-dashed border-gray-200 py-14 text-center">
    <i data-lucide="credit-card" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
    <p class="text-sm text-gray-500">Belum ada transaksi.</p>
</div>
<?php endif; ?>
