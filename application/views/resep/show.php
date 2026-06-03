<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <div class="flex items-center gap-3">
            <a href="<?= base_url('resep') ?>" class="text-gray-500 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Rekam Resep</h1>
        </div>
        
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Resep
        </button>
    </div>

    <!-- KERTAS RESEP -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 sm:p-10 mb-6 print:shadow-none print:border-none print:p-0">
        
        <!-- Header RS -->
        <div class="border-b-2 border-gray-800 pb-4 mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">SIRS MEDIKA</h2>
                <p class="text-sm text-gray-600 mt-1">Jl. Kesehatan No. 123, Kota Medika</p>
                <p class="text-sm text-gray-600">Telp: (021) 12345678</p>
            </div>
            <div class="text-right">
                <p class="text-xl font-bold text-primary mb-1">RESEP DOKTER</p>
                <p class="text-sm text-gray-600">No. Transaksi: <span class="font-bold">#RSP-<?= str_pad($resep->id_resep, 5, '0', STR_PAD_LEFT) ?></span></p>
                <p class="text-sm text-gray-600">Tanggal: <?= date('d M Y', strtotime($resep->tanggal_resep)) ?></p>
            </div>
        </div>

        <!-- Info Pasien & Dokter -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Diberikan Kepada (Pasien):</h3>
                <p class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($resep->nama_pasien) ?></p>
                <p class="text-sm text-gray-600 font-mono mt-1">No RM: <?= htmlspecialchars($resep->no_rekam_medis) ?></p>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <h3 class="text-xs font-bold text-blue-500 uppercase tracking-wider mb-2">Dokter Pemeriksa:</h3>
                <p class="font-bold text-blue-900 text-lg"><?= htmlspecialchars($resep->nama_dokter) ?></p>
                <p class="text-sm text-blue-700 mt-1"><?= htmlspecialchars($resep->spesialisasi) ?></p>
            </div>
        </div>

        <!-- Tabel Detail Obat -->
        <div class="mb-8">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm border-b-2 border-gray-300">
                        <th class="py-3 px-4 font-bold">Nama Obat</th>
                        <th class="py-3 px-4 font-bold text-center">Qty</th>
                        <th class="py-3 px-4 font-bold">Aturan Pakai</th>
                        <th class="py-3 px-4 font-bold text-right print:hidden">Harga Satuan</th>
                        <th class="py-3 px-4 font-bold text-right print:hidden">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-800">
                    <?php foreach($detail as $d): ?>
                    <tr class="border-b border-gray-200">
                        <td class="py-3 px-4">
                            <span class="font-bold block"><?= htmlspecialchars($d->nama_obat) ?></span>
                            <span class="text-xs text-gray-500 font-mono"><?= htmlspecialchars($d->kode_obat) ?></span>
                        </td>
                        <td class="py-3 px-4 text-center font-bold text-primary text-base">
                            <?= $d->jumlah ?>
                        </td>
                        <td class="py-3 px-4">
                            <span class="bg-gray-100 px-3 py-1 rounded text-gray-700 font-medium whitespace-nowrap">
                                <?= htmlspecialchars($d->aturan_pakai) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right text-gray-600 print:hidden">
                            Rp <?= number_format($d->harga_satuan, 0, ',', '.') ?>
                        </td>
                        <td class="py-3 px-4 text-right font-bold print:hidden">
                            Rp <?= number_format($d->subtotal, 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Total Tagihan -->
        <div class="flex justify-end print:hidden">
            <div class="bg-green-50 p-4 rounded-xl border border-green-200 text-right min-w-[250px]">
                <p class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Total Tagihan Apotek</p>
                <p class="text-3xl font-black text-green-700">Rp <?= number_format($resep->total_harga, 0, ',', '.') ?></p>
            </div>
        </div>
        
        <!-- Footer Print -->
        <div class="mt-12 text-center text-sm text-gray-500 hidden print:block">
            <p>Terima kasih atas kepercayaan Anda.</p>
            <p>Semoga lekas sembuh.</p>
        </div>

    </div>
</div>

<!-- Tambahkan sedikit style khusus print CSS untuk menghilangkan sidebar di template -->
<style>
    @media print {
        aside, header, nav, .btn-logout, .btn-delete { display: none !important; }
        main { background: white !important; padding: 0 !important; margin: 0 !important; }
        body { background: white !important; }
    }
</style>