<p class="text-sm text-gray-500 mb-4">Isi formulir untuk mengambil nomor antrean poliklinik hari ini.</p>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-5">
    <form action="<?= base_url('portal_pasien/buat_antrean') ?>" method="POST" class="space-y-5">

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tanggal Berobat</label>
            <input type="date" name="tanggal_antrean" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>"
                class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-base focus:ring-2 focus:ring-primary/30 focus:border-primary bg-gray-50" required>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Poliklinik</label>
            <select name="id_layanan" id="layananSelector"
                class="w-full portal-select" required>
                <option value="" disabled selected>Pilih poliklinik</option>
                <?php foreach ($layanan as $l): ?>
                <option value="<?= $l->id_layanan ?>">
                    <?= htmlspecialchars($l->nama_layanan) ?> — Rp <?= number_format($l->tarif, 0, ',', '.') ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Dokter</label>
            <select name="id_dokter" id="dokterSelector" class="w-full portal-select bg-gray-100" disabled required>
                <option value="" disabled selected>Pilih poli terlebih dahulu</option>
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Keluhan</label>
            <textarea name="keluhan_awal" rows="4"
                class="w-full px-4 py-3.5 border border-gray-200 rounded-xl text-base focus:ring-2 focus:ring-primary/30 focus:border-primary resize-none"
                placeholder="Contoh: Demam 3 hari, batuk berdahak"
                required></textarea>
        </div>

        <button type="submit" class="w-full bg-primary active:bg-primary-hover text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/25 flex items-center justify-center gap-2 text-base min-h-[52px]">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            Ambil Nomor Antrean
        </button>
    </form>
</div>

<div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3">
    <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
    <p class="text-xs text-blue-800 leading-relaxed">Hadir <strong>15 menit</strong> sebelum giliran. Bawa KTP dan kartu BPJS jika ada.</p>
</div>

<style>
.portal-select { padding: 0.875rem 1rem; border: 1px solid #E5E7EB; border-radius: 0.75rem; font-size: 1rem; width: 100%; }
.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single {
    border: 1px solid #E5E7EB !important; border-radius: 0.75rem !important;
    min-height: 52px !important; display: flex !important; align-items: center !important;
    padding: 0 0.5rem !important; font-size: 1rem !important;
}
.select2-container--default .select2-selection--single .select2-selection__arrow { height: 100% !important; right: 12px !important; }
</style>

<script>
$(function () {
    $('#layananSelector, #dokterSelector').select2({ width: '100%' });

    $('#layananSelector').on('change', function () {
        var id = $(this).val();
        var $doc = $('#dokterSelector');
        if (!id) {
            $doc.prop('disabled', true).empty().append('<option disabled selected>Pilih poli terlebih dahulu</option>').trigger('change');
            return;
        }
        $doc.prop('disabled', false).empty().append('<option disabled selected>Memuat...</option>').trigger('change');
        $.getJSON('<?= base_url('portal_pasien/get_dokter/') ?>' + id, function (data) {
            $doc.empty();
            if (data.length) {
                $doc.append('<option disabled selected>Pilih dokter</option>');
                data.forEach(function (v) {
                    $doc.append(new Option(v.nama_dokter + ' (' + v.spesialisasi + ')', v.id_dokter));
                });
            } else {
                $doc.append('<option disabled selected>Tidak ada dokter</option>');
            }
            $doc.trigger('change');
        });
    });
});
</script>
