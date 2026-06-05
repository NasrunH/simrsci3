<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- BANNER SELAMAT DATANG -->
    <div class="bg-gradient-to-r from-primary to-green-600 rounded-2xl shadow-md p-6 sm:p-8 text-white">
        <span class="text-xs font-bold uppercase tracking-widest bg-white/20 px-3 py-1 rounded-full">Portal Pasien SIMRS</span>
        <h1 class="text-2xl sm:text-3xl font-black mt-3">Halo, <?= htmlspecialchars($pasien->nama_lengkap) ?>!</h1>
        <p class="text-white/80 text-sm mt-1">Gunakan layanan mandiri SIMRS Medika untuk berobat secara praktis tanpa antre manual.</p>
        <div class="flex flex-wrap gap-3 mt-6">
            <a href="<?= base_url('portal_pasien/buat_antrean') ?>" class="bg-white hover:bg-gray-100 text-primary px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Daftar Berobat Sekarang
            </a>
            <a href="<?= base_url('portal_pasien/rekam_medis') ?>" class="bg-primary-hover hover:bg-primary border border-white/20 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all">
                Riwayat Rekam Medis
            </a>
        </div>
    </div>

    <!-- ANTREAN HARI INI (REAL-TIME STATUS) -->
    <?php if(!empty($antrean_hari_ini)): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 shadow-sm">
        <h2 class="text-sm font-bold text-amber-800 uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="relative flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-amber-500"></span>
            </span>
            Antrean Anda Hari Ini (<?= date('d M Y') ?>)
        </h2>
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="bg-amber-600 text-white font-mono font-black text-4xl w-20 h-20 rounded-xl flex items-center justify-center shadow-md">
                    <?= $antrean_hari_ini->no_antrean ?>
                </div>
                <div>
                    <span class="bg-amber-200 text-amber-800 px-2.5 py-0.5 rounded text-xs font-bold uppercase"><?= $antrean_hari_ini->status ?></span>
                    <h3 class="text-xl font-bold text-gray-800 mt-1"><?= htmlspecialchars($antrean_hari_ini->nama_layanan ?? 'Poliklinik') ?></h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($antrean_hari_ini->nama_dokter) ?></p>
                </div>
            </div>
            
            <div class="text-left md:text-right bg-white p-4 rounded-xl border border-amber-100 w-full md:w-auto">
                <p class="text-xs text-gray-400 font-semibold uppercase">Estimasi Kedatangan</p>
                <p class="text-lg font-bold text-gray-800">Pukul 09:00 - 11:30</p>
                <p class="text-xs text-amber-600 mt-1">*Mohon hadir 15 menit sebelum estimasi.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- STATISTIK RINGKAS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="p-4 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <span class="text-gray-400 text-xs font-semibold uppercase">Total Kunjungan Medis</span>
                <p class="text-2xl font-bold text-gray-800"><?= $total_kunjungan ?> Kali</p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="p-4 bg-red-50 text-red-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span class="text-gray-400 text-xs font-semibold uppercase">Tagihan Belum Dibayar</span>
                <p class="text-2xl font-bold text-gray-800"><?= $tagihan_aktif ?> Invoice</p>
            </div>
        </div>
    </div>

    <!-- TIGA KUNJUNGAN TERAKHIR -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">3 Kunjungan Medis Terakhir Anda</h2>
        <div class="space-y-4">
            <?php if(!empty($kunjungan_terakhir)): ?>
                <?php foreach($kunjungan_terakhir as $k): ?>
                <div class="flex justify-between items-start p-4 rounded-lg bg-gray-50 border border-gray-100">
                    <div>
                        <span class="text-xs text-primary font-mono font-semibold"><?= date('d M Y', strtotime($k->tanggal_periksa)) ?></span>
                        <h3 class="font-bold text-gray-800 mt-1"><?= htmlspecialchars($k->diagnosa ?? 'Pemeriksaan Kesehatan') ?></h3>
                        <p class="text-xs text-gray-500 mt-0.5">Dokter: <?= htmlspecialchars($k->nama_dokter) ?></p>
                        <p class="text-xs text-gray-600 mt-2 bg-white px-2 py-1 rounded inline-block border border-gray-100">"<?= htmlspecialchars($k->keluhan_utama ?? '') ?>"</p>
                    </div>
                    <a href="<?= base_url('portal_pasien/rekam_medis') ?>" class="text-xs text-primary hover:underline font-bold">Lihat Detail SOAP →</a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center py-6 text-gray-400 text-sm">Belum ada riwayat rekam medis terdokumentasi.</p>
            <?php endif; ?>
        </div>
    </div>

</div>