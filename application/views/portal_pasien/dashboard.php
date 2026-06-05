<?php
$first = strtoupper(substr($pasien->nama_lengkap, 0, 1));
$status_map = [
    'Menunggu'  => ['bg' => 'bg-amber-100 text-amber-800', 'label' => 'Menunggu Panggilan'],
    'Diperiksa' => ['bg' => 'bg-blue-100 text-blue-800', 'label' => 'Sedang Dipanggil'],
    'Selesai'   => ['bg' => 'bg-green-100 text-green-800', 'label' => 'Selesai'],
    'Batal'     => ['bg' => 'bg-red-100 text-red-800', 'label' => 'Dibatalkan'],
];
?>

<!-- Kartu identitas -->
<div class="bg-gradient-to-br from-primary via-green-600 to-teal-600 rounded-2xl p-5 text-white shadow-lg mb-4 relative overflow-hidden">
    <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full"></div>
    <p class="text-emerald-100 text-xs font-medium">Selamat datang</p>
    <h2 class="text-xl font-black mt-1 leading-tight"><?= htmlspecialchars($pasien->nama_lengkap) ?></h2>
    <p class="text-emerald-100/90 text-xs font-mono mt-1"><?= htmlspecialchars($pasien->no_rekam_medis) ?></p>
</div>

<!-- Antrean hari ini (live) -->
<div id="queueWidget" class="mb-4">
    <?php if (!empty($antrean_hari_ini)):
        $st = $status_map[$antrean_hari_ini->status] ?? $status_map['Menunggu'];
    ?>
    <div class="bg-white rounded-2xl border-2 border-primary/20 shadow-sm overflow-hidden">
        <div class="bg-primary/5 px-4 py-3 flex items-center justify-between border-b border-primary/10">
            <div class="flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-primary"></span>
                </span>
                <span class="text-xs font-bold text-primary uppercase tracking-wide">Antrean Hari Ini</span>
            </div>
            <span id="queueStatusBadge" class="<?= $st['bg'] ?> text-[10px] font-bold px-2.5 py-1 rounded-full"><?= $st['label'] ?></span>
        </div>
        <div class="p-4 flex items-center gap-4">
            <div id="queueNumber" class="w-20 h-20 rounded-2xl bg-primary text-white flex items-center justify-center font-mono font-black text-3xl shadow-lg shrink-0">
                <?= (int)$antrean_hari_ini->no_antrean ?>
            </div>
            <div class="min-w-0 flex-1">
                <p class="font-bold text-gray-800 truncate"><?= htmlspecialchars($antrean_hari_ini->nama_layanan ?? 'Poliklinik') ?></p>
                <p class="text-sm text-gray-500 truncate">Dr. <?= htmlspecialchars($antrean_hari_ini->nama_dokter) ?></p>
                <p id="queueHint" class="text-xs text-amber-700 font-semibold mt-2">
                    <?php if ($antrean_hari_ini->status === 'Menunggu'): ?>
                        <?= (int)$antrean_di_depan ?> orang di depan Anda
                    <?php elseif ($antrean_hari_ini->status === 'Diperiksa'): ?>
                        Silakan menuju ruang pemeriksaan
                    <?php else: ?>
                        Status: <?= htmlspecialchars($antrean_hari_ini->status) ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="px-4 pb-4 grid grid-cols-2 gap-2">
            <a href="<?= base_url('portal_pasien/antrean_saat_ini') ?>" class="bg-gray-100 text-gray-700 text-center py-2.5 rounded-xl text-xs font-bold">Monitor Live</a>
            <button type="button" id="btnRefreshQueue" class="bg-primary/10 text-primary py-2.5 rounded-xl text-xs font-bold">Perbarui</button>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-6 text-center">
        <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i data-lucide="ticket" class="w-7 h-7 text-gray-400"></i>
        </div>
        <p class="text-sm font-semibold text-gray-700">Belum ada antrean hari ini</p>
        <p class="text-xs text-gray-400 mt-1 mb-4">Daftar kunjungan untuk mengambil nomor antrean</p>
        <a href="<?= base_url('portal_pasien/buat_antrean') ?>" class="inline-flex items-center gap-2 bg-primary text-white font-bold px-5 py-3 rounded-xl text-sm shadow-md">
            <i data-lucide="plus" class="w-4 h-4"></i> Daftar Sekarang
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Quick actions -->
<div class="grid grid-cols-2 gap-3 mb-4">
    <a href="<?= base_url('portal_pasien/buat_antrean') ?>" class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col items-center text-center active:scale-[0.98] transition-transform">
        <div class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center mb-2">
            <i data-lucide="calendar-plus" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-bold text-gray-800">Daftar Poli</span>
    </a>
    <a href="<?= base_url('portal_pasien/antrean_saat_ini') ?>" class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col items-center text-center active:scale-[0.98] transition-transform">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
            <i data-lucide="radio" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-bold text-gray-800">Monitor Antrean</span>
    </a>
    <a href="<?= base_url('portal_pasien/rekam_medis') ?>" class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col items-center text-center active:scale-[0.98] transition-transform">
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2">
            <i data-lucide="file-text" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-bold text-gray-800">Rekam Medis</span>
    </a>
    <a href="<?= base_url('portal_pasien/billing') ?>" class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm flex flex-col items-center text-center relative active:scale-[0.98] transition-transform">
        <?php if ($tagihan_aktif > 0): ?>
        <span class="absolute top-2 right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"><?= (int)$tagihan_aktif ?></span>
        <?php endif; ?>
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2">
            <i data-lucide="wallet" class="w-5 h-5"></i>
        </div>
        <span class="text-xs font-bold text-gray-800">Tagihan</span>
    </a>
</div>

<!-- Stat ringkas -->
<div class="grid grid-cols-2 gap-3 mb-4">
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <p class="text-[10px] text-gray-400 font-bold uppercase">Kunjungan</p>
        <p class="text-2xl font-black text-gray-800 mt-1"><?= (int)$total_kunjungan ?></p>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <p class="text-[10px] text-gray-400 font-bold uppercase">Tagihan Aktif</p>
        <p class="text-2xl font-black <?= $tagihan_aktif ? 'text-amber-600' : 'text-gray-800' ?> mt-1"><?= (int)$tagihan_aktif ?></p>
    </div>
</div>

<!-- Kunjungan terakhir -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-2">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-bold text-gray-800 text-sm">Kunjungan Terakhir</h3>
        <a href="<?= base_url('portal_pasien/rekam_medis') ?>" class="text-xs font-bold text-primary">Semua</a>
    </div>
    <?php if (!empty($kunjungan_terakhir)): ?>
    <div class="space-y-2">
        <?php foreach ($kunjungan_terakhir as $k): ?>
        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
            <p class="text-[10px] text-primary font-mono font-semibold"><?= date('d M Y', strtotime($k->tanggal_periksa)) ?></p>
            <p class="font-bold text-gray-800 text-sm mt-0.5 line-clamp-1"><?= htmlspecialchars($k->diagnosa ?? 'Pemeriksaan') ?></p>
            <p class="text-xs text-gray-500">Dr. <?= htmlspecialchars($k->nama_dokter) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p class="text-center text-gray-400 text-xs py-6">Belum ada riwayat medis.</p>
    <?php endif; ?>
</div>

<?php if (!empty($antrean_hari_ini)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusLabels = {
        'Menunggu': ['bg-amber-100 text-amber-800', 'Menunggu Panggilan'],
        'Diperiksa': ['bg-blue-100 text-blue-800', 'Sedang Dipanggil'],
        'Selesai': ['bg-green-100 text-green-800', 'Selesai'],
        'Batal': ['bg-red-100 text-red-800', 'Dibatalkan'],
    };

    async function refreshQueue() {
        try {
            const res = await fetch('<?= base_url('portal_pasien/status_antrean_ajax') ?>');
            const d = await res.json();
            if (!d.has_queue) return;

            const badge = document.getElementById('queueStatusBadge');
            const hint = document.getElementById('queueHint');
            const num = document.getElementById('queueNumber');
            if (num) num.textContent = d.no_antrean;

            const st = statusLabels[d.status] || statusLabels['Menunggu'];
            if (badge) {
                badge.className = st[0] + ' text-[10px] font-bold px-2.5 py-1 rounded-full';
                badge.textContent = st[1];
            }
            if (hint) {
                if (d.status === 'Menunggu') {
                    hint.textContent = d.di_depan + ' orang di depan Anda';
                    hint.className = 'text-xs text-amber-700 font-semibold mt-2';
                } else if (d.status === 'Diperiksa') {
                    hint.textContent = 'Silakan menuju ruang pemeriksaan';
                    hint.className = 'text-xs text-blue-700 font-semibold mt-2';
                } else {
                    hint.textContent = 'Status: ' + d.status;
                }
            }
        } catch (e) { /* silent */ }
    }

    document.getElementById('btnRefreshQueue')?.addEventListener('click', refreshQueue);
    setInterval(refreshQueue, 15000);
});
</script>
<?php endif; ?>
