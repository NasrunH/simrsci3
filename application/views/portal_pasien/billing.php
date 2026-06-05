<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi & Tagihan</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar biaya konsultasi poliklinik dan penebusan obat apotek.</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                        <th class="py-4 px-6">No. Invoice</th>
                        <th class="py-4 px-6">Tanggal</th>
                        <th class="py-4 px-6 text-right">Total Tagihan</th>
                        <th class="py-4 px-6 text-center w-36">Status</th>
                        <th class="py-4 px-6 text-center w-32">Kuitansi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if(!empty($billing)): ?>
                        <?php foreach($billing as $b): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-gray-700"><?= htmlspecialchars($b->no_invoice) ?></td>
                            <td class="py-4 px-6 text-gray-500"><?= date('d M Y, H:i', strtotime($b->created_at)) ?></td>
                            <td class="py-4 px-6 text-right font-bold text-gray-800">Rp <?= number_format($b->total_tagihan, 0, ',', '.') ?></td>
                            <td class="py-4 px-6 text-center">
                                <?php if($b->status == 'Lunas'): ?>
                                    <span class="bg-green-100 text-green-700 border border-green-200 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">Lunas</span>
                                <?php else: ?>
                                    <span class="bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap animate-pulse">Belum Lunas</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php if($b->status == 'Lunas'): ?>
                                    <a href="<?= base_url('portal_pasien/invoice/'.$b->id_billing) ?>" class="text-primary hover:underline font-bold hover:text-green-700 text-xs flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        Struk Kwitansi
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400 italic">Harap Bayar di Kasir</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                Belum ada riwayat pencatatan transaksi keuangan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>