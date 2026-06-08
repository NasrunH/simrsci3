<?php
    // ==========================================
    // HELPER INDONESIAN SMART DECIMAL FORMATTER
    // ==========================================
    if (!function_exists('format_stok_tampil')) {
        function format_stok_tampil($stok) {
            $stok = (float)$stok;
            $formatted = number_format($stok, 2, ',', '.');
            if (strpos($formatted, ',') !== false) {
                $formatted = rtrim(rtrim($formatted, '0'), ',');
            }
            return $formatted;
        }
    }
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <div class="flex items-center gap-3">
            <a href="<?= base_url('penerimaan') ?>" class="text-gray-500 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Bukti Penerimaan</h1>
        </div>
        
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <!-- DOKUMEN TIKET MASUK (LOGISTIK APOTEK) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 sm:p-12 print:border-none print:shadow-none print:p-0">
        
        <div class="border-b-2 border-dashed border-gray-200 pb-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-800 tracking-tight">SIRS MEDIKA</h2>
                <p class="text-sm text-gray-500 mt-1">Gudang & Logistik Farmasi Rumah Sakit</p>
            </div>
            <div class="text-right">
                <span class="bg-blue-100 text-blue-700 border border-blue-200 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">MASUK APOTEK</span>
                <p class="text-xs text-gray-400 mt-3">No. Faktur: <span class="font-mono font-bold text-gray-700"><?= htmlspecialchars($p->no_faktur ?? '') ?></span></p>
                <p class="text-xs text-gray-400">Tanggal Terima: <?= date('d M Y', strtotime($p->tanggal_penerimaan)) ?></p>
            </div>
        </div>

        <!-- Detail Entitas -->
        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Pemasok / Supplier</p>
                <p class="text-base font-bold text-gray-800"><?= htmlspecialchars($p->nama_supplier ?? '') ?></p>
                <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($p->alamat_supplier ?? '-') ?></p>
                <p class="text-xs text-gray-500">Telp: <?= htmlspecialchars($p->telp_supplier ?? '-') ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Diterima Oleh Petugas</p>
                <p class="text-base font-bold text-gray-800"><?= htmlspecialchars($p->nama_petugas ?? 'Petugas Apotek') ?></p>
                <p class="text-xs text-gray-500 mt-1">Status: Terverifikasi Masuk</p>
            </div>
        </div>

        <!-- Tabel Detail Item -->
        <div class="mb-8">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                        <th class="py-3 px-4">Nama Obat / Kode</th>
                        <th class="py-3 px-4 text-center w-28">Jumlah Qty</th>
                        <th class="py-3 px-4 text-right w-36">Harga Beli Satuan</th>
                        <th class="py-3 px-4 text-right w-36">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-100">
                    <?php if(!empty($detail)): ?>
                        <?php foreach($detail as $d): ?>
                        <tr>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-800 block"><?= htmlspecialchars($d->nama_obat ?? '') ?></span>
                                <span class="text-xs text-primary font-mono block">Kode: <?= htmlspecialchars($d->kode_obat ?? '') ?></span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800 font-mono">
                                <!-- Gunakan format desimal pintar -->
                                <?= format_stok_tampil($d->jumlah) ?> unit
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono">Rp <?= number_format($d->harga_beli, 0, ',', '.') ?></td>
                            <td class="py-3.5 px-4 text-right font-semibold font-mono">Rp <?= number_format($d->subtotal, 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Total -->
        <div class="border-t border-gray-200 pt-6 flex flex-col items-end gap-3 text-sm">
            <div class="flex justify-between w-full max-w-xs">
                <span class="text-gray-500 font-medium">Total Volume Obat:</span>
                <span class="text-gray-800 font-bold font-mono"><?= format_stok_tampil($p->total_item) ?> Unit</span>
            </div>
            <div class="flex justify-between w-full max-w-xs border-t border-gray-200 pt-3 text-lg font-black text-gray-800">
                <span>TOTAL NILAI FAKTUR:</span>
                <span class="text-primary font-mono">Rp <?= number_format($p->total_harga, 0, ',', '.') ?></span>
            </div>
        </div>

        <?php if(!empty($p->catatan)): ?>
        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-100 text-xs text-gray-600 italic">
            <strong>Catatan Faktur:</strong><br/>
            "<?= htmlspecialchars($p->catatan) ?>"
        </div>
        <?php endif; ?>

        <!-- Area Tanda Tangan -->
        <div class="mt-12 grid grid-cols-2 gap-8 text-center text-xs text-gray-400 print:block print:mt-16">
            <div>
                <p>Pengirim (Supplier),</p>
                <br/><br/><br/>
                <p class="font-bold text-gray-700">( .................................... )</p>
            </div>
            <div class="text-right print:mt-12">
                <p>Penerima (Petugas Gudang),</p>
                <br/><br/><br/>
                <p class="font-bold text-gray-700"><?= htmlspecialchars($p->nama_petugas ?? 'Petugas Gudang') ?></p>
            </div>
        </div>

    </div>
</div>

<style>
    @media print {
        aside, header, nav, .btn-logout, .print\:hidden { display: none !important; }
        main { background: white !important; padding: 0 !important; margin: 0 !important; }
        body { background: white !important; }
    }
</style>