<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('rekam_medis') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Pemeriksaan Pasien (SOAP)</h1>
    </div>

    <!-- Tambahkan ID formSOAP untuk intercept submit di JS -->
    <form action="<?= base_url('rekam_medis/create') ?>" method="POST" id="formSOAP">
        
        <!-- Informasi Umum -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Data Kunjungan</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien <span class="text-red-500">*</span></label>
                    <select name="id_pasien" id="id_pasien" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <option value="" disabled <?= empty($selected_pasien) ? 'selected' : '' ?>>Ketik Nama / No RM...</option>
                        <?php foreach($pasien as $p): ?>
                            <option value="<?= $p->id_pasien ?>" <?= ($selected_pasien == $p->id_pasien) ? 'selected' : '' ?>>
                                <?= $p->no_rekam_medis ?> - <?= htmlspecialchars($p->nama_lengkap) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Periksa <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_periksa" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm" required>
                </div>
                <?php if(strtolower($this->session->userdata('role')) == 'admin'): ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter <span class="text-red-500">*</span></label>
                    <select name="id_dokter" id="id_dokter" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <option value="" disabled <?= empty($selected_dokter) ? 'selected' : '' ?>>Pilih Dokter...</option>
                        <?php foreach($dokters as $d): ?>
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
                    <input type="text" name="tekanan_darah" id="tekanan_darah" placeholder="misal: 120/80" class="w-full px-4 py-2 border border-blue-200 rounded-lg text-sm bg-white">
                    <p class="text-[10px] text-blue-600 mt-1">Format wajib: Sistol/Diastol</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-blue-800 mb-2 uppercase">Suhu (Celcius)</label>
                    <input type="number" step="0.1" name="suhu_tubuh" id="suhu_tubuh" placeholder="misal: 36.5" class="w-full px-4 py-2 border border-blue-200 rounded-lg text-sm bg-white">
                    <p class="text-[10px] text-blue-600 mt-1">Normal: 35.0 - 42.0 °C</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-blue-800 mb-2 uppercase">Berat Badan (Kg)</label>
                    <input type="number" step="0.1" name="berat_badan" id="berat_badan" placeholder="misal: 60" class="w-full px-4 py-2 border border-blue-200 rounded-lg text-sm bg-white">
                    <p class="text-[10px] text-blue-600 mt-1">Harus angka positif</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-red-600 mb-2 uppercase">Catatan Alergi</label>
                    <input type="text" name="catatan_alergi" placeholder="Obat / Makanan..." class="w-full px-4 py-2 border border-red-200 bg-white rounded-lg focus:ring-red-500 text-sm">
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
                    <textarea name="keluhan_utama" id="keluhan_utama" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Objective (O)</label>
                    <p class="text-xs text-gray-500 mb-2">Hasil pemeriksaan fisik & lab oleh dokter.</p>
                    <textarea name="pemeriksaan_fisik" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-red-700 mb-2">Assessment (A) <span class="text-red-500">*</span></label>
                    <p class="text-xs text-red-400 mb-2">Diagnosa kerja / masalah klinis.</p>
                    <textarea name="diagnosa" id="diagnosa" rows="3" class="w-full px-4 py-2 border border-red-300 bg-red-50 rounded-lg focus:ring-red-500 focus:border-red-500 text-sm" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-green-700 mb-2">Plan (P)</label>
                    <p class="text-xs text-green-500 mb-2">Rencana tindakan, edukasi, atau terapi.</p>
                    <textarea name="tindakan_rencana" rows="3" class="w-full px-4 py-2 border border-green-300 bg-green-50 rounded-lg focus:ring-green-500 focus:border-green-500 text-sm"></textarea>
                </div>

            </div>
        </div>

        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl border border-gray-200">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="lanjut_resep" value="yes" checked class="w-5 h-5 text-primary rounded focus:ring-primary">
                <span class="font-semibold text-gray-700 text-sm">Lanjutkan ke form Peresepan Obat setelah simpan</span>
            </label>

            <div class="flex gap-3">
                <a href="<?= base_url('rekam_medis') ?>" class="px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-100 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-primary text-white font-bold rounded-lg hover:bg-primary-hover transition-colors shadow-md text-sm">Simpan EMR</button>
            </div>
        </div>
    </form>
</div>

<style>
    /* Sinkronisasi Select2 di dalam form */
    .select2-container { width: 100% !important; display: block !important; }
    .select2-container--default .select2-selection--single {
        border-color: #D1D5DB !important; border-radius: 0.5rem !important; height: 42px !important; display: flex !important; align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 1rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 8px !important; }
</style>

<!-- JAVASCRIPT VALIDASI DENGAN SWEETALERT2 -->
<script>
    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        $('#formSOAP').on('submit', function(e) {
            var isValid = true;

            // 1. Ambil nilai-nilai dari form
            var tekananDarah = $('#tekanan_darah').val().trim();
            var suhuTubuh = $('#suhu_tubuh').val().trim();
            var beratBadan = $('#berat_badan').val().trim();

            // 2. Validasi Format Tekanan Darah (Harus angka/angka, misal 120/80)
            if (tekananDarah !== '') {
                var bpRegex = /^\d{2,3}\/\d{2,3}$/;
                if (!bpRegex.test(tekananDarah)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tekanan Darah Salah',
                        text: 'Tekanan darah harus menggunakan format Sistol/Diastol (contoh: 120/80).',
                        confirmButtonColor: '#10B981'
                    });
                    isValid = false;
                    e.preventDefault();
                    return false;
                }
            }

            // 3. Validasi Batas Logis Suhu Tubuh (Misal: 35.0 sampai 42.0)
            if (suhuTubuh !== '') {
                var temp = parseFloat(suhuTubuh);
                if (temp < 30.0 || temp > 45.0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Nilai Suhu Tubuh Tidak Logis',
                        text: 'Suhu tubuh manusia normal berkisar antara 30.0°C hingga 45.0°C.',
                        confirmButtonColor: '#10B981'
                    });
                    isValid = false;
                    e.preventDefault();
                    return false;
                }
            }

            // 4. Validasi Berat Badan (Harus positif dan masuk akal)
            if (beratBadan !== '') {
                var weight = parseFloat(beratBadan);
                if (weight <= 0 || weight > 400) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Berat Badan Tidak Logis',
                        text: 'Berat badan harus bernilai positif dan di bawah 400 Kg.',
                        confirmButtonColor: '#10B981'
                    });
                    isValid = false;
                    e.preventDefault();
                    return false;
                }
            }

            // ====================================================================
            // SOLUSI KRITIS POSTGRESQL:
            // Jika kolom numerik (suhu/berat) dibiarkan kosong, hilangkan atribut 'name'
            // agar tidak terkirim sebagai "" (string kosong) yang merusak kueri PostgreSQL.
            // ====================================================================
            if (isValid) {
                if (suhuTubuh === '') {
                    $('#suhu_tubuh').removeAttr('name');
                }
                if (beratBadan === '') {
                    $('#berat_badan').removeAttr('name');
                }
            }
        });
    });
</script>