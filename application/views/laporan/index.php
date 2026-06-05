<?php
$r = $ringkasan;
$p = $perbandingan;

function laporan_pct_badge($pct) {
    if ($pct > 0) {
        return '<span class="inline-flex items-center gap-0.5 text-xs font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded-full">↑ ' . abs($pct) . '%</span>';
    }
    if ($pct < 0) {
        return '<span class="inline-flex items-center gap-0.5 text-xs font-bold text-red-700 bg-red-50 px-2 py-0.5 rounded-full">↓ ' . abs($pct) . '%</span>';
    }
    return '<span class="text-xs font-medium text-gray-400">— stabil</span>';
}
?>

<!-- Header -->
<div class="print:hidden mb-6">
    <div class="bg-gradient-to-r from-sidebar via-primary to-secondary rounded-2xl p-6 md:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <p class="text-white/80 text-sm font-medium uppercase tracking-widest">SIRS Medika — Analitik</p>
                <h1 class="text-2xl md:text-3xl font-black mt-1">Laporan Operasional & Keuangan</h1>
                <p class="text-white/90 text-sm mt-2 max-w-xl">
                    Periode <strong><?= date('d M Y', strtotime($start_date)) ?></strong>
                    s/d <strong><?= date('d M Y', strtotime($end_date)) ?></strong>
                    <span class="opacity-75">(<?= (int)$r->hari_periode ?> hari)</span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="<?= base_url('dashboard') ?>" class="bg-white/15 hover:bg-white/25 border border-white/30 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                    Dashboard
                </a>
                <button type="button" onclick="window.print()" class="bg-white text-primary hover:bg-gray-50 px-5 py-2 rounded-lg text-sm font-bold shadow-md flex items-center gap-2 transition-colors">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6 print:hidden">
    <form action="<?= base_url('laporan') ?>" method="GET" class="space-y-4">
        <div class="flex flex-wrap gap-2">
            <?php
            $presets = [
                'hari_ini'   => 'Hari Ini',
                'minggu_ini' => 'Minggu Ini',
                'bulan_ini'  => 'Bulan Ini',
            ];
            foreach ($presets as $key => $label):
                $active = ($preset ?? '') === $key || ($key === 'bulan_ini' && empty($_GET));
            ?>
            <button type="submit" name="preset" value="<?= $key ?>"
                class="px-4 py-2 rounded-lg text-sm font-semibold border transition-colors <?= $active ? 'bg-primary text-white border-primary' : 'bg-gray-50 text-gray-700 border-gray-200 hover:border-primary hover:text-primary' ?>">
                <?= $label ?>
            </button>
            <?php endforeach; ?>
        </div>
        <div class="flex flex-col md:flex-row gap-4 items-end border-t border-gray-100 pt-4">
            <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal Akhir</label>
                    <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary/30 focus:border-primary text-sm">
                </div>
            </div>
            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-8 py-2.5 rounded-lg text-sm font-bold shadow-sm w-full md:w-auto">
                Terapkan Filter
            </button>
        </div>
        <p class="text-xs text-gray-400">
            Perbandingan otomatis vs periode sebelumnya (<?= date('d M', strtotime($p->prev_start)) ?> – <?= date('d M Y', strtotime($p->prev_end)) ?>).
        </p>
    </form>
</div>

<!-- Print Header -->
<div class="hidden print:block mb-6 pb-4 border-b-2 border-gray-900">
    <h2 class="text-2xl font-black uppercase tracking-tight">Laporan Operasional SIRS Medika</h2>
    <p class="text-gray-700 mt-1">Periode: <?= date('d/m/Y', strtotime($start_date)) ?> – <?= date('d/m/Y', strtotime($end_date)) ?></p>
    <p class="text-sm text-gray-500">Dicetak: <?= date('d/m/Y H:i') ?> oleh <?= htmlspecialchars($this->session->userdata('username')) ?></p>
</div>

<!-- KPI -->
<div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm col-span-2 lg:col-span-2">
        <div class="flex items-start justify-between">
            <p class="text-xs font-bold text-gray-500 uppercase">Pendapatan Resep</p>
            <?= laporan_pct_badge($p->pendapatan_pct) ?>
        </div>
        <p class="text-2xl font-black text-green-600 mt-2">Rp <?= number_format($r->pendapatan_resep, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-1">Rata-rata/hari: Rp <?= number_format($r->rata_pendapatan_hari, 0, ',', '.') ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <p class="text-xs font-bold text-gray-500 uppercase">Transaksi</p>
            <?= laporan_pct_badge($p->transaksi_pct) ?>
        </div>
        <p class="text-2xl font-black text-gray-800 mt-2"><?= number_format($r->transaksi_resep, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-1">lembar resep</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <p class="text-xs font-bold text-gray-500 uppercase">Billing Lunas</p>
            <?= laporan_pct_badge($p->billing_lunas_pct) ?>
        </div>
        <p class="text-2xl font-black text-teal-700 mt-2">Rp <?= number_format($r->nominal_lunas, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= (int)$r->billing_lunas ?> invoice</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <p class="text-xs font-bold text-gray-500 uppercase">Tagihan Pending</p>
        <p class="text-2xl font-black text-amber-600 mt-2">Rp <?= number_format($r->nominal_pending, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= (int)$r->billing_pending ?> belum lunas</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <p class="text-xs font-bold text-gray-500 uppercase">Antrean</p>
            <?= laporan_pct_badge($p->antrean_pct) ?>
        </div>
        <p class="text-2xl font-black text-blue-700 mt-2"><?= number_format($r->total_antrean, 0, ',', '.') ?></p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
        <div class="flex items-start justify-between">
            <p class="text-xs font-bold text-gray-500 uppercase">Rekam Medis</p>
            <?= laporan_pct_badge($p->rm_pct) ?>
        </div>
        <p class="text-2xl font-black text-indigo-700 mt-2"><?= number_format($r->total_rm, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= (int)$r->pasien_baru ?> pasien baru</p>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6 print:break-inside-avoid">
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-800">Grafik Pendapatan Harian</h3>
                <p class="text-xs text-gray-500">Resep apotek vs billing lunas per tanggal transaksi</p>
            </div>
        </div>
        <div class="h-80">
            <canvas id="chartPendapatan"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Volume Transaksi</h3>
        <p class="text-xs text-gray-500 mb-4">Jumlah resep per hari</p>
        <div class="h-80">
            <canvas id="chartTransaksi"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6 print:break-inside-avoid">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Status Antrean</h3>
        <p class="text-xs text-gray-500 mb-4">Seluruh periode filter</p>
        <div class="h-64 flex items-center justify-center">
            <canvas id="chartAntrean"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Kunjungan per Poliklinik</h3>
        <p class="text-xs text-gray-500 mb-4">Berdasarkan antrean</p>
        <div class="h-64">
            <canvas id="chartLayanan"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:col-span-2 xl:col-span-1">
        <h3 class="font-bold text-gray-800 mb-1">Performa Dokter</h3>
        <p class="text-xs text-gray-500 mb-4">Top resep & pendapatan</p>
        <div class="h-64">
            <canvas id="chartDokter"></canvas>
        </div>
    </div>
</div>

<!-- Charts Row 3 + Table Obat -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-1">Top Obat Terlaris</h3>
        <p class="text-xs text-gray-500 mb-4">Kuantitas dalam periode</p>
        <div class="h-72">
            <canvas id="chartObat"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-amber-50/80 flex justify-between items-center">
            <h3 class="font-bold text-amber-900 text-sm uppercase">Ranking Obat</h3>
            <span class="text-xs text-amber-700 font-medium"><?= count($obat_terlaris) ?> item</span>
        </div>
        <div class="overflow-x-auto max-h-80 overflow-y-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 sticky top-0">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Obat</th>
                        <th class="py-3 px-4 text-right">Qty</th>
                        <th class="py-3 px-4 text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (!empty($obat_terlaris)): $no = 1; foreach ($obat_terlaris as $ot): ?>
                    <tr class="hover:bg-gray-50/80">
                        <td class="py-2.5 px-4 text-gray-400 font-bold"><?= $no++ ?></td>
                        <td class="py-2.5 px-4">
                            <span class="font-medium text-gray-800"><?= htmlspecialchars($ot->nama_obat) ?></span>
                            <?php if (!empty($ot->kategori)): ?>
                            <span class="block text-xs text-gray-400"><?= htmlspecialchars($ot->kategori) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2.5 px-4 text-right font-bold"><?= (int)$ot->total_terjual ?></td>
                        <td class="py-2.5 px-4 text-right text-green-700 font-semibold">Rp <?= number_format($ot->nilai_penjualan ?? 0, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-8 text-gray-500">Tidak ada data penjualan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Tabel Harian + Dokter -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <div class="xl:col-span-2 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-sm uppercase">Rincian Pendapatan Harian</h3>
            <span class="text-xs font-semibold text-gray-500"><?= count($laporan) ?> hari aktif</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase border-b border-gray-200 bg-white">
                    <tr>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-center">Transaksi</th>
                        <th class="py-3 px-6 text-right">Pendapatan</th>
                        <th class="py-3 px-6 text-right print:hidden">% Total</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 divide-y divide-gray-50">
                    <?php if (!empty($laporan)): foreach ($laporan as $lap):
                        $pct_row = $total_pendapatan > 0 ? round(($lap->total_pendapatan / $total_pendapatan) * 100, 1) : 0;
                    ?>
                    <tr class="hover:bg-gray-50/80">
                        <td class="py-3 px-6 font-medium"><?= date('D, d M Y', strtotime($lap->tanggal_resep)) ?></td>
                        <td class="py-3 px-6 text-center">
                            <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-full text-xs font-bold"><?= (int)$lap->total_transaksi ?></span>
                        </td>
                        <td class="py-3 px-6 text-right font-bold text-green-600">Rp <?= number_format($lap->total_pendapatan, 0, ',', '.') ?></td>
                        <td class="py-3 px-6 text-right print:hidden">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-16 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-primary rounded-full" style="width:<?= min(100, $pct_row) ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500 w-10"><?= $pct_row ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="4" class="text-center py-10 text-gray-500">Tidak ada transaksi pada periode ini.</td></tr>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($laporan)): ?>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200 font-bold text-sm">
                    <tr>
                        <td class="py-4 px-6 uppercase text-gray-600">Total</td>
                        <td class="py-4 px-6 text-center text-blue-700"><?= number_format($total_transaksi, 0, ',', '.') ?></td>
                        <td class="py-4 px-6 text-right text-green-700">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></td>
                        <td class="py-4 px-6 print:hidden"></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-indigo-50/80">
            <h3 class="font-bold text-indigo-900 text-sm uppercase">Dokter Teraktif</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-xs text-gray-500 uppercase border-b">
                    <tr>
                        <th class="py-3 px-4 text-left">Dokter</th>
                        <th class="py-3 px-4 text-right">Resep</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php if (!empty($dokter_teramai)): foreach ($dokter_teramai as $d): ?>
                    <tr class="hover:bg-gray-50/80">
                        <td class="py-3 px-4">
                            <p class="font-medium text-gray-800"><?= htmlspecialchars($d->nama_dokter) ?></p>
                            <p class="text-xs text-gray-400"><?= htmlspecialchars($d->spesialisasi ?? '-') ?></p>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <span class="font-bold"><?= (int)$d->jumlah_resep ?></span>
                            <p class="text-xs text-green-600">Rp <?= number_format($d->pendapatan, 0, ',', '.') ?></p>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="2" class="text-center py-8 text-gray-500">Belum ada data.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ringkasan Eksekutif (print-friendly) -->
<div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-6 print:break-inside-avoid">
    <h3 class="font-bold text-slate-800 uppercase text-sm tracking-wider mb-3">Ringkasan Eksekutif</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-700 leading-relaxed">
        <p>
            Selama <strong><?= (int)$r->hari_periode ?> hari</strong>, sistem mencatat
            <strong><?= number_format($r->transaksi_resep) ?> transaksi resep</strong> dengan total pendapatan apotek
            <strong>Rp <?= number_format($r->pendapatan_resep, 0, ',', '.') ?></strong>
            (rata-rata transaksi Rp <?= number_format($r->rata_transaksi, 0, ',', '.') ?>).
        </p>
        <p>
            Billing lunas mencapai <strong>Rp <?= number_format($r->nominal_lunas, 0, ',', '.') ?></strong>
            dari <?= (int)$r->billing_lunas ?> invoice, sementara tagihan tertunda
            <strong><?= (int)$r->billing_pending ?></strong> senilai
            <strong>Rp <?= number_format($r->nominal_pending, 0, ',', '.') ?></strong>.
            Terdapat <?= (int)$r->total_antrean ?> antrean dan <?= (int)$r->total_rm ?> kunjungan rekam medis.
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fmtRp = (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
    const primary = '#16A34A';
    const teal = '#14B8A6';

    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = '#64748B';

    const series = <?= json_encode($chart_series) ?>;

    if (series.labels.length) {
        new Chart(document.getElementById('chartPendapatan'), {
            type: 'bar',
            data: {
                labels: series.labels,
                datasets: [
                    {
                        label: 'Pendapatan Resep',
                        data: series.pendapatan,
                        backgroundColor: 'rgba(22, 163, 74, 0.85)',
                        borderRadius: 4,
                        order: 2,
                    },
                    {
                        label: 'Billing Lunas',
                        data: series.billing,
                        type: 'line',
                        borderColor: teal,
                        backgroundColor: 'rgba(20, 184, 166, 0.1)',
                        fill: true,
                        tension: 0.35,
                        yAxisID: 'y',
                        order: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y),
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: (v) => v >= 1e6 ? (v/1e6).toFixed(1)+'jt' : (v >= 1e3 ? (v/1e3)+'rb' : v) },
                    },
                },
            },
        });

        new Chart(document.getElementById('chartTransaksi'), {
            type: 'line',
            data: {
                labels: series.labels,
                datasets: [{
                    label: 'Jumlah Resep',
                    data: series.transaksi,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.12)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            },
        });
    } else {
        ['chartPendapatan', 'chartTransaksi'].forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.parentElement.innerHTML = '<p class="text-gray-400 text-sm flex items-center justify-center h-full">Tidak ada data pada periode ini.</p>';
        });
    }

    const antrean = <?= json_encode($chart_antrean) ?>;
    const antEl = document.getElementById('chartAntrean');
    if (antrean.labels.length) {
        new Chart(antEl, {
            type: 'doughnut',
            data: {
                labels: antrean.labels,
                datasets: [{ data: antrean.data, backgroundColor: antrean.colors, borderWidth: 2, borderColor: '#fff' }],
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } },
        });
    } else {
        antEl.parentElement.innerHTML = '<p class="text-gray-400 text-sm">Tidak ada data antrean.</p>';
    }

    const layanan = <?= json_encode($chart_layanan) ?>;
    new Chart(document.getElementById('chartLayanan'), {
        type: 'bar',
        data: {
            labels: layanan.labels.length ? layanan.labels : ['—'],
            datasets: [{
                label: 'Kunjungan',
                data: layanan.data.length ? layanan.data : [0],
                backgroundColor: 'rgba(99, 102, 241, 0.8)',
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } },
        },
    });

    const dokter = <?= json_encode($chart_dokter) ?>;
    new Chart(document.getElementById('chartDokter'), {
        type: 'bar',
        data: {
            labels: dokter.labels.length ? dokter.labels : ['—'],
            datasets: [
                { label: 'Resep', data: dokter.resep.length ? dokter.resep : [0], backgroundColor: 'rgba(59, 130, 246, 0.85)', borderRadius: 4 },
                { label: 'Pendapatan', data: dokter.pendapatan.length ? dokter.pendapatan : [0], backgroundColor: 'rgba(22, 163, 74, 0.75)', borderRadius: 4 },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12 } },
                tooltip: {
                    callbacks: {
                        label: (ctx) => ctx.datasetIndex === 1 ? ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y) : ctx.dataset.label + ': ' + ctx.parsed.y,
                    },
                },
            },
            scales: { y: { beginAtZero: true } },
        },
    });

    const obat = <?= json_encode($chart_obat) ?>;
    new Chart(document.getElementById('chartObat'), {
        type: 'bar',
        data: {
            labels: obat.labels.length ? obat.labels : ['—'],
            datasets: [{
                label: 'Terjual (Pcs)',
                data: obat.qty.length ? obat.qty : [0],
                backgroundColor: 'rgba(245, 158, 11, 0.9)',
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } },
        },
    });

    if (typeof lucide !== 'undefined') lucide.createIcons();
});
</script>

<style>
@media print {
    aside, header, nav, .btn-logout, .btn-delete, form, button { display: none !important; }
    main { background: white !important; padding: 0 !important; overflow: visible !important; }
    body { background: white !important; font-size: 11pt; }
    .max-w-\[1600px\] { max-width: 100% !important; }
    .shadow-sm, .shadow-lg { box-shadow: none !important; }
    .bg-gradient-to-r { background: #14532d !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    canvas { max-height: 220px !important; }
}
</style>
