<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Pendapatan</h1>
        <p class="text-gray-500 text-sm mt-1">Statistik resep obat dan pendapatan rumah sakit.</p>
    </div>
    
    <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Cetak Laporan
    </button>
</div>

<!-- ============================================== -->
<!-- FORM FILTER TANGGAL (Sembunyikan saat cetak)   -->
<!-- ============================================== -->
<div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 mb-6 print:hidden">
    <form action="<?= base_url('laporan') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm" required>
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm" required>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Tampilkan
            </button>
            <a href="<?= base_url('laporan') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                Bulan Ini
            </a>
        </div>
    </form>
</div>

<!-- Header Cetak Khusus Print -->
<div class="hidden print:block mb-8 border-b-2 border-gray-800 pb-4">
    <h2 class="text-2xl font-black text-gray-800 tracking-tight">LAPORAN PENDAPATAN APOTEK SIRS</h2>
    <p class="text-gray-600">Periode: <?= date('d M Y', strtotime($start_date)) ?> s/d <?= date('d M Y', strtotime($end_date)) ?></p>
</div>

<!-- ============================================== -->
<!-- WIDGET RINGKASAN                               -->
<!-- ============================================== -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-blue-500">
        <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Transaksi Resep</h3>
        <div class="flex items-end gap-3">
            <span class="text-4xl font-black text-gray-800"><?= number_format($total_transaksi, 0, ',', '.') ?></span>
            <span class="text-gray-500 font-medium mb-1">Lembar</span>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-green-500">
        <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Pendapatan</h3>
        <div class="flex items-end gap-3">
            <span class="text-4xl font-black text-green-600">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- ============================================== -->
    <!-- TABEL PENDAPATAN HARIAN                        -->
    <!-- ============================================== -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-bold text-gray-800 text-sm uppercase">Rincian Pendapatan Per Hari</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase border-b border-gray-200">
                        <th class="py-3 px-6 font-semibold">Tanggal</th>
                        <th class="py-3 px-6 font-semibold text-center">Jml Transaksi</th>
                        <th class="py-3 px-6 font-semibold text-right">Pendapatan (Rp)</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php if(!empty($laporan)): ?>
                        <?php foreach($laporan as $lap): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-6 font-medium"><?= date('d F Y', strtotime($lap->tanggal_resep)) ?></td>
                            <td class="py-3 px-6 text-center">
                                <span class="bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold">
                                    <?= $lap->total_transaksi ?>
                                </span>
                            </td>
                            <td class="py-3 px-6 text-right font-bold text-green-600">
                                <?= number_format($lap->total_pendapatan, 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-8 text-gray-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- TABEL TOP OBAT                                 -->
    <!-- ============================================== -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-amber-50">
            <h3 class="font-bold text-amber-900 text-sm uppercase flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Top 10 Obat Terlaris
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase border-b border-gray-200">
                        <th class="py-3 px-6 font-semibold">Nama Obat</th>
                        <th class="py-3 px-6 font-semibold text-right">Terjual</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?php if(!empty($obat_terlaris)): ?>
                        <?php foreach($obat_terlaris as $ot): ?>
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-3 px-6 font-medium"><?= htmlspecialchars($ot->nama_obat) ?></td>
                            <td class="py-3 px-6 text-right font-bold text-gray-800">
                                <?= $ot->total_terjual ?> <span class="text-xs text-gray-500 font-normal">Pcs</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center py-6 text-gray-500">Belum ada data penjualan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tambahkan sedikit style khusus print CSS untuk menghilangkan sidebar di template -->
<style>
    @media print {
        aside, header, nav, .btn-logout, .btn-delete { display: none !important; }
        main { background: white !important; padding: 0 !important; margin: 0 !important; overflow: visible !important; }
        body { background: white !important; }
        .max-w-7xl { max-w: 100% !important; margin: 0 !important; padding: 0 !important; }
        .shadow-sm, .border { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>