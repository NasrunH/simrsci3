<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('portal_pasien') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Ambil Nomor Antrean</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form action="<?= base_url('portal_pasien/buat_antrean') ?>" method="POST">
            
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Rencana Berobat <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_antrean" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm font-bold" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Poliklinik <span class="text-red-500">*</span></label>
                <select name="id_layanan" id="layananSelector" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm select2" required>
                    <option value="" disabled selected>-- Pilih Poli --</option>
                    <?php foreach($layanan as $l): ?>
                        <option value="<?= $l->id_layanan ?>"><?= htmlspecialchars($l->nama_layanan) ?> (Biaya: Rp <?= number_format($l->tarif, 0, ',', '.') ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Dokter Spesialis <span class="text-red-500">*</span></label>
                <select name="id_dokter" id="dokterSelector" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm bg-gray-50 select2" disabled required>
                    <option value="" disabled selected>-- Silakan Pilih Poliklinik Dahulu --</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keluhan yang Dirasakan <span class="text-red-500">*</span></label>
                <textarea name="keluhan_awal" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-primary focus:border-primary text-sm" placeholder="Tulis keluhan secara ringkas, misal: Demam tinggi 3 hari dan mual." required></textarea>
            </div>

            <div class="pt-5 border-t border-gray-100">
                <button type="submit" class="w-full bg-primary hover:bg-green-700 text-white font-bold py-3 rounded-xl transition-all shadow-md shadow-primary/20 flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Selesaikan & Ambil Tiket
                </button>
            </div>

        </form>
    </div>
</div>

<style>
    .select2-container { width: 100% !important; display: block !important; }
    .select2-container--default .select2-selection--single {
        border-color: #D1D5DB !important; border-radius: 0.75rem !important; height: 46px !important; display: flex !important; align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { padding-left: 1rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 44px !important; right: 10px !important; }
</style>

<script>
    $(document).ready(function() {
        $('#layananSelector').select2({ width: '100%' });
        $('#dokterSelector').select2({ width: '100%', placeholder: '-- Silakan Pilih Poliklinik Dahulu --' });

        $('#layananSelector').on('change', function() {
            var id_layanan = $(this).val();
            var dokterSelector = $('#dokterSelector');

            if (id_layanan) {
                dokterSelector.prop('disabled', false).empty().append('<option value="" disabled selected>Memuat dokter...</option>').trigger('change');

                $.ajax({
                    url: '<?= base_url("portal_pasien/get_dokter/") ?>' + id_layanan,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        dokterSelector.empty();
                        if (data.length > 0) {
                            dokterSelector.append('<option value="" disabled selected>-- Pilih Dokter --</option>');
                            $.each(data, function(key, value) {
                                var option = new Option(value.nama_dokter + ' (' + value.spesialisasi + ')', value.id_dokter, false, false);
                                dokterSelector.append(option);
                            });
                        } else {
                            dokterSelector.append('<option value="" disabled selected>Tidak ada dokter yang bertugas di poli ini</option>');
                        }
                        dokterSelector.trigger('change');
                    }
                });
            } else {
                dokterSelector.prop('disabled', true).empty().append('<option value="" disabled selected>-- Silakan Pilih Poliklinik Dahulu --</option>').trigger('change');
            }
        });
    });
</script>