<?php
    // Sinkronisasi variabel penampung data agar kebal error
    $b = $b ?? $billing ?? null;
    if (!$b) {
        show_error("Data transaksi billing tidak ditemukan atau gagal dimuat.", 500, "Kesalahan Sistem");
    }

    // Gabungkan kemungkinan nama variabel detail resep
    $resep_items = $detail_resep ?? $resep_detail ?? [];

    // Pengaman penanggalan untuk PHP 8.1+
    $tanggal_transaksi = $b->updated_at ?? $b->created_at ?? date('Y-m-d H:i:s');
?>

<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <div class="flex items-center gap-3">
            <a href="<?= base_url('billing') ?>" class="text-gray-500 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Kuitansi Pembayaran</h1>
        </div>
        
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Bukti Pembayaran
        </button>
    </div>

    <!-- FORMAT STRUK KWITANSI RESMI -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 sm:p-12 print:border-none print:shadow-none print:p-0">
        
        <!-- Kop Klinik -->
        <div class="border-b-2 border-dashed border-gray-300 pb-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-gray-800 tracking-tight">SIRS MEDIKA</h2>
                <p class="text-sm text-gray-500 mt-1">Sistem Manajemen Pelayanan Medis Terpadu</p>
                <p class="text-xs text-gray-400">Jl. Kesehatan No. 123, Kota Medika | Telp: (021) 12345678</p>
            </div>
            <div class="text-right sm:text-right">
                <span class="bg-green-100 text-green-700 border border-green-200 px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider">LUNAS</span>
                <p class="text-xs text-gray-400 mt-3">Invoice No: <span class="font-mono font-bold text-gray-700"><?= htmlspecialchars($b->no_invoice ?? '') ?></span></p>
                <p class="text-xs text-gray-400">Tanggal: <?= date('d M Y, H:i', strtotime($tanggal_transaksi)) ?></p>
            </div>
        </div>

        <!-- Detail Pasien -->
        <div class="grid grid-cols-2 gap-8 mb-8 text-sm">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Diterima Dari (Pasien)</p>
                <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($b->nama_lengkap ?? '') ?></p>
                <p class="text-xs text-primary font-mono mt-0.5">No RM: <?= htmlspecialchars($b->no_rekam_medis ?? '') ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Petugas Kasir</p>
                <p class="text-lg font-bold text-gray-800"><?= htmlspecialchars($b->nama_kasir ?? 'Petugas Administrasi') ?></p>
                <p class="text-xs text-gray-400 mt-0.5">Metode: <?= htmlspecialchars($b->metode_pembayaran ?? 'Tunai') ?></p>
            </div>
        </div>

        <!-- Tabel Rincian -->
        <div class="mb-8">
            <table class="w-full text-left text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 font-bold border-b border-gray-200">
                        <th class="py-3 px-4">Deskripsi Layanan / Item</th>
                        <th class="py-3 px-4 text-center w-20">Qty</th>
                        <th class="py-3 px-4 text-right w-36">Harga Satuan (Rp)</th>
                        <th class="py-3 px-4 text-right w-36">Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <!-- Jasa Periksa Dinamis -->
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4">
                            <span class="font-bold text-gray-800 block">Jasa Periksa Dokter & Pelayanan <?= htmlspecialchars($b->nama_layanan ?? 'Poli') ?></span>
                            <span class="text-xs text-gray-400">Dokter: <?= htmlspecialchars($b->nama_dokter ?? '-') ?></span>
                        </td>
                        <td class="py-3 px-4 text-center">1</td>
                        <td class="py-3 px-4 text-right"><?= number_format($b->biaya_jasa_dokter ?? 0, 0, ',', '.') ?></td>
                        <td class="py-3 px-4 text-right font-medium"><?= number_format($b->biaya_jasa_dokter ?? 0, 0, ',', '.') ?></td>
                    </tr>
                    <!-- Item Obat -->
                    <?php if(!empty($resep_items)): ?>
                        <?php foreach($resep_items as $d): ?>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4">
                                <span class="font-bold text-gray-800 block"><?= htmlspecialchars($d->nama_obat ?? '') ?></span>
                                <span class="text-xs text-gray-400">Aturan pakai: <?= htmlspecialchars($d->aturan_pakai ?? '') ?></span>
                            </td>
                            <td class="py-3 px-4 text-center"><?= $d->jumlah ?></td>
                            <td class="py-3 px-4 text-right"><?= number_format($d->harga_satuan, 0, ',', '.') ?></td>
                            <td class="py-3 px-4 text-right font-medium"><?= number_format($d->subtotal, 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Perhitungan Akhir -->
        <div class="border-t border-gray-200 pt-6 flex flex-col items-end gap-3 text-sm">
            
            <div class="flex justify-between w-full max-w-xs">
                <span class="text-gray-500 font-medium">Biaya Jasa Dokter (<?= htmlspecialchars($b->nama_layanan ?? 'Poli') ?>):</span>
                <span class="text-gray-800 font-semibold">Rp <?= number_format($b->biaya_jasa_dokter ?? 0, 0, ',', '.') ?></span>
            </div>

            <div class="flex justify-between w-full max-w-xs">
                <span class="text-gray-500 font-medium">Total Harga Obat:</span>
                <span class="text-gray-800 font-semibold">Rp <?= number_format($b->biaya_obat ?? 0, 0, ',', '.') ?></span>
            </div>

            <div class="flex justify-between w-full max-w-xs border-t border-gray-200 pt-3 text-lg font-black text-gray-800">
                <span>TOTAL BAYAR:</span>
                <span class="text-primary">Rp <?= number_format($b->total_tagihan ?? 0, 0, ',', '.') ?></span>
            </div>

            <?php if(($b->metode_pembayaran ?? '') == 'Tunai'): ?>
            <div class="flex justify-between w-full max-w-xs text-xs text-gray-500">
                <span>Jumlah Diterima:</span>
                <span>Rp <?= number_format($b->uang_diterima ?? 0, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between w-full max-w-xs text-xs text-gray-500">
                <span>Uang Kembalian:</span>
                <span class="font-bold text-green-600">Rp <?= number_format($b->uang_kembalian ?? 0, 0, ',', '.') ?></span>
            </div>
            <?php endif; ?>

        </div>

        <!-- Footer Struk -->
        <div class="mt-12 text-center text-xs text-gray-400 border-t border-dashed border-gray-200 pt-6">
            <p>Terima kasih atas pembayaran Anda. Bukti transaksi ini sah dan dikeluarkan oleh sistem SIRS Medika.</p>
            <p class="mt-1">Semoga lekas sembuh.</p>
        </div>

    </div>
</div>

<style>
    @media print {
        aside, header, nav, .btn-logout { display: none !important; }
        main { background: white !important; padding: 0 !important; margin: 0 !important; }
        body { background: white !important; }
    }
</style>