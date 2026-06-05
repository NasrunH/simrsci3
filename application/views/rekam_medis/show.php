<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <div class="flex items-center gap-3">
            <a href="<?= base_url('rekam_medis') ?>" class="text-gray-500 hover:text-primary transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Berkas Rekam Medis</h1>
        </div>
        
        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Berkas
        </button>
    </div>

    <!-- KERTAS RM -->
    <div class="bg-white shadow-sm border border-gray-200 p-8 sm:p-10 mb-6 print:shadow-none print:border-none print:p-0">
        
        <!-- Header -->
        <div class="border-b-4 border-gray-800 pb-4 mb-6 flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">SIRS MEDIKA</h2>
                <p class="text-xs text-gray-600 mt-1">CATATAN PERKEMBANGAN PASIEN TERINTEGRASI</p>
            </div>
            <div class="text-right bg-gray-100 p-3 border border-gray-300">
                <p class="text-xl font-bold text-gray-800 font-mono"><?= $rm->no_rekam_medis ?></p>
                <p class="text-sm text-gray-600 font-bold uppercase"><?= $rm->nama_lengkap ?></p>
                <p class="text-xs text-gray-500"><?= $rm->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' ?> | Lahir: <?= date('d/m/Y', strtotime($rm->tanggal_lahir)) ?></p>
            </div>
        </div>

        <!-- Info Pemeriksaan -->
        <div class="flex justify-between items-center bg-blue-50 border border-blue-200 p-3 mb-6 text-sm">
            <p><strong>Tanggal:</strong> <?= date('d F Y', strtotime($rm->tanggal_periksa)) ?></p>
            <p><strong>Dokter:</strong> <?= htmlspecialchars($rm->nama_dokter) ?> (<?= htmlspecialchars($rm->spesialisasi) ?>)</p>
        </div>

        <!-- Vital Signs -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 border-b border-gray-300 pb-1 mb-3">Tanda-tanda Vital</h3>
            <div class="grid grid-cols-4 gap-4 text-sm">
                <div><span class="text-gray-500 block text-xs">TD (mmHg)</span><span class="font-bold"><?= $rm->tekanan_darah ?: '-' ?></span></div>
                <div><span class="text-gray-500 block text-xs">Suhu (°C)</span><span class="font-bold"><?= $rm->suhu_tubuh ?: '-' ?></span></div>
                <div><span class="text-gray-500 block text-xs">BB (Kg)</span><span class="font-bold"><?= $rm->berat_badan ?: '-' ?></span></div>
                <div><span class="text-red-500 font-bold block text-xs">Alergi</span><span class="font-bold text-red-600"><?= $rm->catatan_alergi ?: 'TIDAK ADA' ?></span></div>
            </div>
        </div>

        <!-- SOAP -->
        <div class="space-y-6">
            <div>
                <h3 class="font-bold text-gray-800 border-b border-gray-300 pb-1 mb-2">S - Subjective (Keluhan Utama)</h3>
                <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($rm->keluhan_utama) ?></p>
            </div>
            
            <div>
                <h3 class="font-bold text-gray-800 border-b border-gray-300 pb-1 mb-2">O - Objective (Pemeriksaan Fisik)</h3>
                <p class="text-gray-700 whitespace-pre-wrap"><?= htmlspecialchars($rm->pemeriksaan_fisik ?: '-') ?></p>
            </div>

            <div>
                <h3 class="font-bold text-red-800 border-b border-red-300 pb-1 mb-2">A - Assessment (Diagnosa)</h3>
                <p class="text-red-700 font-bold whitespace-pre-wrap text-lg"><?= htmlspecialchars($rm->diagnosa) ?></p>
            </div>

            <div>
                <h3 class="font-bold text-green-800 border-b border-green-300 pb-1 mb-2">P - Plan (Tindakan & Rencana)</h3>
                <p class="text-green-800 whitespace-pre-wrap"><?= htmlspecialchars($rm->tindakan_rencana ?: '-') ?></p>
            </div>
        </div>

        <!-- Signature -->
        <div class="mt-16 flex justify-end print:mt-24">
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-16">Dokter Pemeriksa,</p>
                <p class="font-bold text-gray-800 underline"><?= htmlspecialchars($rm->nama_dokter) ?></p>
                <p class="text-xs text-gray-500">SIRS Medika</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        aside, header, nav, .btn-logout, .btn-delete { display: none !important; }
        main { background: white !important; padding: 0 !important; margin: 0 !important; }
        body { background: white !important; }
    }
</style>