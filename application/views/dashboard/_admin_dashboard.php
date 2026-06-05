<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- KPI Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-6">
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-green-500 col-span-2 lg:col-span-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendapatan Resep Hari Ini</p>
        <p class="text-xl font-black text-gray-800 mt-1">Rp <?= number_format($r->pendapatan ?? 0, 0, ',', '.') ?></p>
        <p class="text-xs text-gray-400 mt-1"><?= (int)($r->jumlah_resep ?? 0) ?> transaksi</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-teal-500 col-span-2 lg:col-span-2">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Billing Lunas Hari Ini</p>
        <p class="text-xl font-black text-teal-700 mt-1">Rp <?= number_format($r->billing_lunas ?? 0, 0, ',', '.') ?></p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-blue-500">
        <p class="text-xs font-semibold text-gray-500 uppercase">Antrean Hari Ini</p>
        <p class="text-2xl font-black text-gray-800 mt-1"><?= (int)($r->antrean_hari_ini ?? 0) ?></p>
        <p class="text-xs text-amber-600 mt-1"><?= (int)($s->antrean_aktif ?? 0) ?> aktif</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-indigo-500">
        <p class="text-xs font-semibold text-gray-500 uppercase">Kunjungan RM</p>
        <p class="text-2xl font-black text-gray-800 mt-1"><?= (int)($r->rm_hari_ini ?? 0) ?></p>
        <p class="text-xs text-gray-400 mt-1">hari ini</p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-purple-500">
        <p class="text-xs font-semibold text-gray-500 uppercase">Total Pasien</p>
        <p class="text-2xl font-black text-gray-800 mt-1"><?= number_format($s->total_pasien ?? 0, 0, ',', '.') ?></p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-cyan-500">
        <p class="text-xs font-semibold text-gray-500 uppercase">Dokter</p>
        <p class="text-2xl font-black text-gray-800 mt-1"><?= (int)($s->total_dokter ?? 0) ?></p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-amber-500">
        <p class="text-xs font-semibold text-gray-500 uppercase">Tagihan Pending</p>
        <p class="text-2xl font-black text-amber-700 mt-1"><?= (int)($s->billing_belum_lunas ?? 0) ?></p>
    </div>
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 border-l-4 border-l-red-500">
        <p class="text-xs font-semibold text-gray-500 uppercase">Stok Kritis</p>
        <p class="text-2xl font-black text-red-600 mt-1"><?= (int)($s->obat_stok_rendah ?? 0) ?></p>
        <p class="text-xs text-gray-400 mt-1">obat &le; 5</p>
    </div>
</div>

<!-- Ringkasan Bulan -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-gradient-to-br from-primary to-primary-hover text-white p-5 rounded-xl shadow-md">
        <p class="text-sm font-medium opacity-90">Pendapatan Resep Bulan Ini</p>
        <p class="text-2xl font-black mt-2">Rp <?= number_format($s->pendapatan_bulan ?? 0, 0, ',', '.') ?></p>
    </div>
    <div class="bg-gradient-to-br from-secondary to-teal-700 text-white p-5 rounded-xl shadow-md">
        <p class="text-sm font-medium opacity-90">Billing Lunas Bulan Ini</p>
        <p class="text-2xl font-black mt-2">Rp <?= number_format($s->billing_lunas_bulan ?? 0, 0, ',', '.') ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-gray-500">Total Rekam Medis</p>
            <p class="text-2xl font-black text-gray-800 mt-1"><?= number_format($s->total_rm ?? 0, 0, ',', '.') ?></p>
        </div>
        <div class="text-right text-sm">
            <a href="<?= base_url('pasien') ?>" class="text-primary font-semibold hover:underline block">Pasien</a>
            <a href="<?= base_url('obat') ?>" class="text-primary font-semibold hover:underline block mt-1">Obat</a>
            <a href="<?= base_url('antrean') ?>" class="text-primary font-semibold hover:underline block mt-1">Antrean</a>
        </div>
    </div>
</div>

<!-- Grafik Baris 1 -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
    <div class="xl:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-bold text-gray-800">Tren 7 Hari Terakhir</h3>
                <p class="text-xs text-gray-500">Pendapatan resep, jumlah resep, antrean & kunjungan RM</p>
            </div>
        </div>
        <div class="h-72 relative">
            <canvas id="chartTrend"></canvas>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-800 mb-1">Status Antrean Hari Ini</h3>
        <p class="text-xs text-gray-500 mb-4">Distribusi per status</p>
        <div class="h-56 flex items-center justify-center">
            <canvas id="chartAntrean"></canvas>
        </div>
    </div>
</div>

<!-- Grafik Baris 2 -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-800 mb-1">Kunjungan per Poliklinik</h3>
        <p class="text-xs text-gray-500 mb-4">30 hari terakhir</p>
        <div class="h-64">
            <canvas id="chartLayanan"></canvas>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-800 mb-1">Demografi Pasien</h3>
        <p class="text-xs text-gray-500 mb-4">Berdasarkan jenis kelamin</p>
        <div class="h-64 flex items-center justify-center">
            <canvas id="chartDemografi"></canvas>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 lg:col-span-2 xl:col-span-1">
        <h3 class="font-bold text-gray-800 mb-1">Status Billing</h3>
        <p class="text-xs text-gray-500 mb-4">Semua tagihan</p>
        <div class="h-64 flex items-center justify-center">
            <canvas id="chartBilling"></canvas>
        </div>
    </div>
</div>

<!-- Grafik Baris 3 + Tabel -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <h3 class="font-bold text-gray-800 mb-1">Top Obat Terlaris</h3>
        <p class="text-xs text-gray-500 mb-4">Berdasarkan jumlah diresepkan</p>
        <div class="h-72">
            <canvas id="chartObat"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-6">
        <!-- Stok Rendah -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-red-50 flex items-center justify-between">
                <h3 class="font-bold text-red-900 text-sm uppercase flex items-center gap-2">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                    Obat Stok Menipis
                </h3>
                <a href="<?= base_url('obat') ?>" class="text-xs font-semibold text-red-700 hover:underline">Kelola &rarr;</a>
            </div>
            <div class="overflow-x-auto max-h-48 overflow-y-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs text-gray-500 uppercase border-b sticky top-0 bg-white">
                        <tr>
                            <th class="py-2 px-4">Obat</th>
                            <th class="py-2 px-4 text-right">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($obat_stok_rendah)): ?>
                            <?php foreach ($obat_stok_rendah as $o): ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="py-2 px-4 font-medium"><?= htmlspecialchars($o->nama_obat) ?></td>
                                <td class="py-2 px-4 text-right">
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-bold"><?= (int)$o->stok ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="2" class="text-center py-6 text-gray-500">Semua stok aman.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800 text-sm uppercase">Aktivitas Terbaru</h3>
            </div>
            <ul class="divide-y divide-gray-100 max-h-52 overflow-y-auto">
                <?php if (!empty($aktivitas)): ?>
                    <?php foreach ($aktivitas as $act): ?>
                    <li class="px-4 py-3 flex items-center gap-3 hover:bg-gray-50/80 text-sm">
                        <?php if ($act->tipe === 'resep'): ?>
                            <span class="w-8 h-8 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0"><i data-lucide="pill" class="w-4 h-4"></i></span>
                        <?php else: ?>
                            <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center shrink-0"><i data-lucide="users" class="w-4 h-4"></i></span>
                        <?php endif; ?>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate"><?= htmlspecialchars($act->subjek) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($act->label) ?> &middot; <?= date('d/m/Y', strtotime($act->tanggal)) ?></p>
                        </div>
                        <?php if ($act->tipe === 'resep' && is_numeric($act->nilai)): ?>
                            <span class="text-xs font-bold text-green-600 shrink-0">Rp <?= number_format($act->nilai, 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="px-4 py-6 text-center text-gray-500 text-sm">Belum ada aktivitas.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
    <?php
    $quick_links = [
        ['url' => 'pasien', 'label' => 'Pasien', 'icon' => 'user-plus'],
        ['url' => 'dokter', 'label' => 'Dokter', 'icon' => 'stethoscope'],
        ['url' => 'antrean', 'label' => 'Antrean', 'icon' => 'list-ordered'],
        ['url' => 'rekam_medis/create', 'label' => 'SOAP', 'icon' => 'file-text'],
        ['url' => 'resep/create', 'label' => 'Resep', 'icon' => 'pill'],
        ['url' => 'billing', 'label' => 'Kasir', 'icon' => 'wallet'],
        ['url' => 'laporan', 'label' => 'Laporan', 'icon' => 'bar-chart-2'],
        ['url' => 'live_board', 'label' => 'Live Board', 'icon' => 'monitor'],
    ];
    foreach ($quick_links as $ql): ?>
    <a href="<?= base_url($ql['url']) ?>" class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:border-primary hover:shadow-md transition-all group">
        <i data-lucide="<?= $ql['icon'] ?>" class="w-6 h-6 mx-auto text-gray-400 group-hover:text-primary"></i>
        <span class="block text-xs font-semibold text-gray-700 mt-2 group-hover:text-primary"><?= $ql['label'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fmtRp = (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
    const primary = '#16A34A';
    const secondary = '#14B8A6';

    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = '#64748B';

    const trend = <?= json_encode($trend) ?>;
    new Chart(document.getElementById('chartTrend'), {
        type: 'line',
        data: {
            labels: trend.labels,
            datasets: [
                {
                    label: 'Pendapatan (Rp)',
                    data: trend.pendapatan,
                    borderColor: primary,
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    fill: true,
                    tension: 0.35,
                    yAxisID: 'y',
                },
                {
                    label: 'Jumlah Resep',
                    data: trend.resep,
                    borderColor: '#3B82F6',
                    backgroundColor: 'transparent',
                    tension: 0.35,
                    yAxisID: 'y1',
                },
                {
                    label: 'Antrean',
                    data: trend.antrean,
                    borderColor: '#F59E0B',
                    borderDash: [4, 4],
                    tension: 0.35,
                    yAxisID: 'y1',
                },
                {
                    label: 'Kunjungan RM',
                    data: trend.kunjungan_rm,
                    borderColor: secondary,
                    borderDash: [2, 2],
                    tension: 0.35,
                    yAxisID: 'y1',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 12, padding: 16 } },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            if (ctx.datasetIndex === 0) return ctx.dataset.label + ': ' + fmtRp(ctx.parsed.y);
                            return ctx.dataset.label + ': ' + ctx.parsed.y;
                        },
                    },
                },
            },
            scales: {
                y: {
                    position: 'left',
                    ticks: { callback: (v) => (v >= 1000 ? (v/1000) + 'k' : v) },
                },
                y1: {
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    beginAtZero: true,
                },
            },
        },
    });

    const antrean = <?= json_encode($chart_antrean) ?>;
    if (antrean.labels.length) {
        new Chart(document.getElementById('chartAntrean'), {
            type: 'doughnut',
            data: {
                labels: antrean.labels,
                datasets: [{ data: antrean.data, backgroundColor: antrean.colors, borderWidth: 2, borderColor: '#fff' }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
            },
        });
    } else {
        document.getElementById('chartAntrean').parentElement.innerHTML = '<p class="text-gray-400 text-sm">Belum ada antrean hari ini.</p>';
    }

    const layanan = <?= json_encode($chart_layanan) ?>;
    new Chart(document.getElementById('chartLayanan'), {
        type: 'bar',
        data: {
            labels: layanan.labels.length ? layanan.labels : ['Belum ada data'],
            datasets: [{
                label: 'Kunjungan',
                data: layanan.data.length ? layanan.data : [0],
                backgroundColor: 'rgba(22, 163, 74, 0.75)',
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

    const demografi = <?= json_encode($chart_demografi) ?>;
    new Chart(document.getElementById('chartDemografi'), {
        type: 'pie',
        data: {
            labels: demografi.labels.length ? demografi.labels : ['Belum ada data'],
            datasets: [{
                data: demografi.data.length ? demografi.data : [1],
                backgroundColor: ['#3B82F6', '#EC4899', '#94A3B8'],
                borderWidth: 2,
                borderColor: '#fff',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
        },
    });

    const billing = <?= json_encode($chart_billing) ?>;
    new Chart(document.getElementById('chartBilling'), {
        type: 'doughnut',
        data: {
            labels: billing.labels.length ? billing.labels : ['Belum ada data'],
            datasets: [{
                data: billing.data.length ? billing.data : [1],
                backgroundColor: billing.colors.length ? billing.colors : ['#E2E8F0'],
                borderWidth: 2,
                borderColor: '#fff',
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
        },
    });

    const obat = <?= json_encode($chart_obat) ?>;
    new Chart(document.getElementById('chartObat'), {
        type: 'bar',
        data: {
            labels: obat.labels.length ? obat.labels : ['Belum ada data'],
            datasets: [{
                label: 'Terjual (Pcs)',
                data: obat.data.length ? obat.data : [0],
                backgroundColor: 'rgba(245, 158, 11, 0.85)',
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
