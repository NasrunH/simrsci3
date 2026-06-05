<div class="max-w-4xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Rekam Medis</h1>
        <p class="text-gray-500 text-sm mt-1">Dokumentasi hasil pemeriksaan klinis (SOAP) dan riwayat pengobatan Anda.</p>
    </div>

    <?php if(!empty($rekam_medis)): ?>
        <?php foreach($rekam_medis as $r): ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            
            <!-- Header Kartu -->
            <div class="bg-gray-50 border-b border-gray-100 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Tanggal Periksa</span>
                    <h3 class="text-lg font-black text-gray-800 mt-0.5"><?= date('d F Y', strtotime($r->tanggal_periksa)) ?></h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-primary/10 text-primary border border-primary/20 px-3 py-1 rounded-full text-xs font-bold uppercase">
                        <?= htmlspecialchars($r->nama_layanan ?? 'Poliklinik') ?>
                    </span>
                </div>
            </div>

            <!-- Vital Signs -->
            <?php if(!empty($r->tekanan_darah) || !empty($r->suhu_tubuh) || !empty($r->berat_badan)): ?>
            <div class="px-6 py-4 bg-blue-50/50 border-b border-blue-50 grid grid-cols-3 gap-4 text-xs">
                <div>
                    <span class="text-blue-500 font-bold block uppercase tracking-wide">Tekanan Darah</span>
                    <span class="font-mono font-bold text-gray-800 text-sm"><?= htmlspecialchars($r->tekanan_darah ?: '-') ?></span>
                </div>
                <div>
                    <span class="text-blue-500 font-bold block uppercase tracking-wide">Suhu Tubuh</span>
                    <span class="font-mono font-bold text-gray-800 text-sm"><?= $r->suhu_tubuh ? $r->suhu_tubuh . ' °C' : '-' ?></span>
                </div>
                <div>
                    <span class="text-blue-500 font-bold block uppercase tracking-wide">Berat Badan</span>
                    <span class="font-mono font-bold text-gray-800 text-sm"><?= $r->berat_badan ? $r->berat_badan . ' Kg' : '-' ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Isi SOAP klinis -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <span class="font-bold text-gray-400 uppercase text-xs block mb-1">Subjective (Keluhan Utama)</span>
                    <p class="text-gray-800 bg-gray-50 rounded-lg p-3 border border-gray-100">"<?= htmlspecialchars($r->keluhan_utama ?? '') ?>"</p>
                </div>
                <div>
                    <span class="font-bold text-gray-400 uppercase text-xs block mb-1">Objective (Pemeriksaan Fisik)</span>
                    <p class="text-gray-800 bg-gray-50 rounded-lg p-3 border border-gray-100"><?= htmlspecialchars($r->pemeriksaan_fisik ?: '-') ?></p>
                </div>
                <div>
                    <span class="font-bold text-red-500 uppercase text-xs block mb-1">Assessment (Diagnosa Medis)</span>
                    <p class="text-red-950 bg-red-50 rounded-lg p-3 border border-red-100 font-bold"><?= htmlspecialchars($r->diagnosa ?? '') ?></p>
                </div>
                <div>
                    <span class="font-bold text-green-600 uppercase text-xs block mb-1">Plan (Terapi & Rencana Tindakan)</span>
                    <p class="text-green-950 bg-green-50 rounded-lg p-3 border border-green-100"><?= htmlspecialchars($r->tindakan_rencana ?: '-') ?></p>
                </div>
            </div>

            <!-- Info Dokter -->
            <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-xs text-gray-500 flex justify-between items-center">
                <span>Diperiksa Oleh: <strong><?= htmlspecialchars($r->nama_dokter) ?></strong></span>
                <?php if(!empty($r->catatan_alergi)): ?>
                    <span class="bg-red-100 text-red-700 font-bold px-2.5 py-1 rounded">Alergi: <?= htmlspecialchars($r->catatan_alergi) ?></span>
                <?php endif; ?>
            </div>

        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-12 text-center text-gray-400">
            Belum ditemukan adanya catatan riwayat medis medis di sistem kami.
        </div>
    <?php endif; ?>
</div>