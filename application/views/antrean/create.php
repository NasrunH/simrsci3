<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('antrean') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Pendaftaran Kunjungan Poliklinik</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form action="<?= base_url('antrean/create') ?>" method="POST" id="formDaftarAntrean">
            
            <!-- Dropdown Pasien (Hanya tampil untuk selain Pasien Mandiri) -->
            <?php if(strtolower($this->session->userdata('role')) != 'pasien'): ?>
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Pasien <span class="text-red-500">*</span></label>
                <!-- Tambahkan ID pasienSelector -->
                <select name="id_pasien" id="pasienSelector" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                    <option value="" disabled selected>Ketik Nama atau No RM Pasien...</option>
                    <?php foreach($pasien as $p): ?>
                        <option value="<?= $p->id_pasien ?>"><?= $p->no_rekam_medis ?> - <?= htmlspecialchars($p->nama_lengkap) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Berobat <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_antrean" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Poliklinik <span class="text-red-500">*</span></label>
                    <select name="id_layanan" id="layananSelector" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm select2" required>
                        <option value="" disabled selected>-- Pilih Poli --</option>
                        <?php foreach($layanan as $l): ?>
                            <option value="<?= $l->id_layanan ?>"><?= htmlspecialchars($l->nama_layanan) ?> (Rp <?= number_format($l->tarif, 0, ',', '.') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Dokter Saringan Dinamis (Daftar ini akan diisi lewat AJAX jQuery dan Select2) -->
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Dokter Spesialis <span class="text-red-500">*</span></label>
                <select name="id_dokter" id="dokterSelector" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm select2 bg-gray-50" disabled required>
                    <option value="" disabled selected>-- Silakan Pilih Poliklinik Terlebih Dahulu --</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan Utama (Singkat)</label>
                <textarea name="keluhan_awal" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Ketik keluhan awal medis pasien..." required></textarea>
            </div>

            <div class="pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('antrean') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm text-sm">Daftar Antrean</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================== -->
<!-- STYLE FIX UNTUK SELECT2 DI DALAM GRID/FLEX TAILWIND -->
<!-- ============================================== -->
<style>
    /* Paksa container Select2 untuk mengambil lebar penuh 100% kontainer */
    .select2-container {
        width: 100% !important;
        display: block !important;
    }
    
    /* Percantik tampilan kotak input Select2 agar serasi dengan Tailwind */
    .select2-container--default .select2-selection--single {
        border-color: #D1D5DB !important; /* border-gray-300 */
        border-radius: 0.5rem !important; /* rounded-lg */
        height: 42px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #FFF !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1F2937 !important; /* text-gray-800 */
        font-size: 0.875rem !important; /* text-sm */
        padding-left: 1rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
</style>

<!-- JAVASCRIPT AJAX & SELECT2 INTEGRATION -->
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 pada seluruh dropdown yang ada
        $('#pasienSelector').select2({ width: '100%' });
        $('#layananSelector').select2({ width: '100%' });
        
        // Inisialisasi awal untuk Dokter Selector
        $('#dokterSelector').select2({ 
            width: '100%',
            placeholder: '-- Silakan Pilih Poliklinik Terlebih Dahulu --'
        });

        // Trigger Event saat Poliklinik dipilih
        $('#layananSelector').on('change', function() {
            var id_layanan = $(this).val();
            var dokterSelector = $('#dokterSelector');

            if (id_layanan) {
                // Aktifkan dropdown dokter
                dokterSelector.prop('disabled', false);

                // Bersihkan opsi lama dan beri status loading pada Select2
                dokterSelector.empty().append('<option value="" disabled selected>Memuat daftar dokter...</option>').trigger('change');

                // Request data dokter via AJAX
                $.ajax({
                    url: '<?= base_url("antrean/get_dokter_by_layanan/") ?>' + id_layanan,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Kosongkan kembali dropdown dokter
                        dokterSelector.empty();

                        if (data.length > 0) {
                            dokterSelector.append('<option value="" disabled selected>-- Pilih Dokter --</option>');
                            $.each(data, function(key, value) {
                                // Gunakan objek Option resmi jQuery agar kompatibel dengan Select2 rendering
                                var option = new Option(value.nama_dokter + ' (' + value.spesialisasi + ')', value.id_dokter, false, false);
                                dokterSelector.append(option);
                            });
                        } else {
                            dokterSelector.append('<option value="" disabled selected>Tidak ada dokter aktif di poli ini</option>');
                        }

                        // PENTING: Memicu render ulang visual dropdown Select2 setelah data HTML diubah
                        dokterSelector.trigger('change');
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal memuat daftar dokter dari database.', 'error');
                        dokterSelector.empty().append('<option value="" disabled selected>Gagal memuat data dokter</option>').trigger('change');
                    }
                });
            } else {
                // Jika poliklinik dikosongkan kembali
                dokterSelector.prop('disabled', true).empty().append('<option value="" disabled selected>-- Silakan Pilih Poliklinik Terlebih Dahulu --</option>').trigger('change');
            }
        });
    });
</script>