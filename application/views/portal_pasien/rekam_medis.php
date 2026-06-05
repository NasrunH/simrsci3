<p class="text-sm text-gray-500 mb-4">Riwayat pemeriksaan klinis (format SOAP).</p>

<?php if (!empty($rekam_medis)): ?>
<div class="space-y-3">
    <?php foreach ($rekam_medis as $i => $r): ?>
    <details class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group" <?= $i === 0 ? 'open' : '' ?>>
        <summary class="p-4 cursor-pointer list-none flex items-center justify-between gap-3 active:bg-gray-50">
            <div class="min-w-0">
                <p class="text-[10px] font-mono text-primary font-bold"><?= date('d M Y', strtotime($r->tanggal_periksa)) ?></p>
                <p class="font-bold text-gray-800 text-sm truncate mt-0.5"><?= htmlspecialchars($r->diagnosa ?? 'Pemeriksaan') ?></p>
                <p class="text-xs text-gray-500 truncate">Dr. <?= htmlspecialchars($r->nama_dokter) ?> · <?= htmlspecialchars($r->nama_layanan ?? 'Poli') ?></p>
            </div>
            <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400 shrink-0 group-open:rotate-180 transition-transform"></i>
        </summary>

        <div class="px-4 pb-4 space-y-3 border-t border-gray-50 pt-3">
            <?php if ($r->tekanan_darah || $r->suhu_tubuh || $r->berat_badan): ?>
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="bg-blue-50 rounded-lg py-2">
                    <p class="text-[9px] text-blue-600 font-bold">TD</p>
                    <p class="text-xs font-bold mt-0.5"><?= htmlspecialchars($r->tekanan_darah ?: '-') ?></p>
                </div>
                <div class="bg-blue-50 rounded-lg py-2">
                    <p class="text-[9px] text-blue-600 font-bold">Suhu</p>
                    <p class="text-xs font-bold mt-0.5"><?= $r->suhu_tubuh ? $r->suhu_tubuh.'°C' : '-' ?></p>
                </div>
                <div class="bg-blue-50 rounded-lg py-2">
                    <p class="text-[9px] text-blue-600 font-bold">BB</p>
                    <p class="text-xs font-bold mt-0.5"><?= $r->berat_badan ? $r->berat_badan.'kg' : '-' ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Subjective</p>
                <p class="text-sm bg-gray-50 rounded-xl p-3 border border-gray-100">"<?= htmlspecialchars($r->keluhan_utama ?? '-') ?>"</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Objective</p>
                <p class="text-sm bg-gray-50 rounded-xl p-3 border border-gray-100"><?= htmlspecialchars($r->pemeriksaan_fisik ?: '-') ?></p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-red-600 uppercase mb-1">Assessment</p>
                <p class="text-sm bg-red-50 rounded-xl p-3 border border-red-100 font-semibold text-red-900"><?= htmlspecialchars($r->diagnosa ?? '-') ?></p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-green-700 uppercase mb-1">Plan</p>
                <p class="text-sm bg-green-50 rounded-xl p-3 border border-green-100"><?= htmlspecialchars($r->tindakan_rencana ?: '-') ?></p>
            </div>
            <?php if (!empty($r->catatan_alergi)): ?>
            <div class="bg-red-100 border border-red-200 rounded-xl p-3 text-xs font-bold text-red-700 flex gap-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 shrink-0"></i>
                Alergi: <?= htmlspecialchars($r->catatan_alergi) ?>
            </div>
            <?php endif; ?>
        </div>
    </details>
    <?php endforeach; ?>
</div>
<?php else: ?>
<div class="bg-white rounded-2xl border border-dashed border-gray-200 py-14 text-center">
    <i data-lucide="file-x" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
    <p class="text-sm text-gray-500">Belum ada rekam medis.</p>
</div>
<?php endif; ?>
