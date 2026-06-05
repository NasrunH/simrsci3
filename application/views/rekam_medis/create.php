<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('rekam_medis') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Pemeriksaan Pasien (SOAP)</h1>
    </div>

    <form action="<?= base_url('rekam_medis/create') ?>" method="POST">
        
        <!-- Informasi Umum -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Data Kunjungan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien <span class="text-red-500">*</span></label>
                    <select name="id_pasien" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <option value="" disabled <?= empty($selected_pasien) ? 'selected' : '' ?>>Ketik Nama / No RM...</option>
                        <?php foreach($pasien as $p): ?>
                            <!-- CEK AUTO-SELECT DARI URL -->
                            <option value="<?= $p->id_pasien ?>" <?= ($selected_pasien == $p->id_pasien) ? 'selected' : '' ?>>
                                <?= $p->no_rekam_medis ?> - <?= htmlspecialchars($p->nama_lengkap) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Periksa <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_periksa" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                </div>
                <?php if(strtolower($this->session->userdata('role')) == 'admin'): ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter <span class="text-red-500">*</span></label>
                    <select name="id_dokter" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <option value="" disabled <?= empty($selected_dokter) ? 'selected' : '' ?>>Pilih Dokter...</option>
                        <?php foreach($dokters as $d): ?>
                            <!-- CEK AUTO-SELECT DARI URL -->
                            <option value="<?= $d->id_dokter ?>" <?= ($selected_dokter == $d->id_dokter) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d->nama_dokter) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Vital Signs -->
        <div class="bg-blue-50 rounded-xl shadow-sm border border-blue-100 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-blue-900 mb-4 border-b border-blue-200 pb-2">Vital Signs (Opsional)</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="block text-xs font-bold text-blue-800 mb-2 uppercase">Tekanan Darah</label>
                    <input type="text" name="tekanan_darah" placeholder="misal: 120/80" class="w-full px-4 py-2 border border-blue-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-blue-800 mb-2 uppercase">Suhu (Celcius)</label>
                    <input type="number" step="0.1" name="suhu_tubuh" placeholder="misal: 36.5" class="w-full px-4 py-2 border border-blue-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-blue-800 mb-2 uppercase">Berat Badan (Kg)</label>
                    <input type="number" step="0.1" name="berat_badan" placeholder="misal: 60" class="w-full px-4 py-2 border border-blue-200 rounded-lg">
                </div>
                <div>
                    <label class="block text-xs font-bold text-red-600 mb-2 uppercase">Catatan Alergi</label>
                    <input type="text" name="catatan_alergi" placeholder="Obat / Makanan..." class="w-full px-4 py-2 border border-red-200 bg-white rounded-lg focus:ring-red-500">
                </div>
            </div>
        </div>

        <!-- Metode SOAP -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Pemeriksaan Klinis (SOAP)</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subjective (S) <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mb-2">Keluhan utama dari pasien/keluarga.</p>
                    <textarea name="keluhan_utama" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Objective (O)</label>
                    <p class="text-xs text-gray-500 mb-2">Hasil pemeriksaan fisik & lab oleh dokter.</p>
                    <textarea name="pemeriksaan_fisik" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-red-700 mb-2">Assessment (A) <span class="text-red-500">*</span></label>
                    <p class="text-xs text-red-400 mb-2">Diagnosa kerja / masalah klinis.</p>
                    <textarea name="diagnosa" rows="3" class="w-full px-4 py-2 border border-red-300 bg-red-50 rounded-lg focus:ring-red-500 focus:border-red-500" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-green-700 mb-2">Plan (P)</label>
                    <p class="text-xs text-green-500 mb-2">Rencana tindakan, edukasi, atau terapi.</p>
                    <textarea name="tindakan_rencana" rows="3" class="w-full px-4 py-2 border border-green-300 bg-green-50 rounded-lg focus:ring-green-500 focus:border-green-500"></textarea>
                </div>

            </div>
        </div>

        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="lanjut_resep" value="yes" checked class="w-5 h-5 text-primary rounded focus:ring-primary">
                <span class="font-semibold text-gray-700">Lanjutkan ke form Peresepan Obat setelah simpan</span>
            </label>

            <div class="flex gap-3">
                <a href="<?= base_url('rekam_medis') ?>" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-100 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-primary-hover transition-colors shadow-md">Simpan EMR</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });
    });
</script>