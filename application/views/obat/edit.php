<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('obat') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Obat</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <?php if(validation_errors()): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg text-sm">
                <?= validation_errors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('obat/edit/'.$obat->id_obat) ?>" method="POST">
            
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Item / SKU</label>
                    <input type="text" value="<?= $obat->kode_obat ?>" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold text-gray-500 font-mono uppercase" readonly tabindex="-1">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary" required>
                        <option value="Obat Bebas" <?= set_select('kategori', 'Obat Bebas', ($obat->kategori == 'Obat Bebas')) ?>>Obat Bebas</option>
                        <option value="Obat Keras" <?= set_select('kategori', 'Obat Keras', ($obat->kategori == 'Obat Keras')) ?>>Obat Keras</option>
                        <option value="Obat Narkotika" <?= set_select('kategori', 'Obat Narkotika', ($obat->kategori == 'Obat Narkotika')) ?>>Obat Narkotika</option>
                        <option value="Alat Kesehatan" <?= set_select('kategori', 'Alat Kesehatan', ($obat->kategori == 'Alat Kesehatan')) ?>>Alat Kesehatan</option>
                    </select>
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat / Alkes <span class="text-red-500">*</span></label>
                <input type="text" name="nama_obat" value="<?= set_value('nama_obat', $obat->nama_obat) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary font-semibold" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <!-- FITUR BARU: Edit Satuan dengan Datalist Rekomendasi Pintar (DISTINCT) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan Kemasan <span class="text-red-500">*</span></label>
                    <input type="text" name="satuan" id="satuanInput" list="satuan_list" value="<?= set_value('satuan', $obat->satuan) ?>" placeholder="Pilih / ketik satuan..." class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" autocomplete="off" required>
                    <datalist id="satuan_list">
                        <?php if(!empty($distinct_satuan)): ?>
                            <?php foreach($distinct_satuan as $ds): ?>
                                <option value="<?= htmlspecialchars($ds->satuan) ?>"></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>
                </div>

                <!-- FITUR BARU: Dropdown Pilih Supplier Terintegrasi (Edit Mode) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Supplier Utama</label>
                    <select name="id_supplier" class="w-full select2-supplier rounded-lg text-sm" data-placeholder="-- Pilih Supplier (Opsional) --">
                        <option value=""></option>
                        <?php foreach($suppliers as $s): ?>
                            <option value="<?= $s->id_supplier ?>" <?= set_select('id_supplier', $s->id_supplier, ($obat->id_supplier == $s->id_supplier)) ?>><?= htmlspecialchars($s->nama_supplier) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sisa Stok Fisik <span class="text-red-500">*</span></label>
                    <input type="number" step="any" min="0" name="stok" value="<?= set_value('stok', (float)$obat->stok) ?>" placeholder="Mendukung angka pecahan desimal" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm font-mono" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" value="<?= set_value('harga', $obat->harga) ?>" placeholder="Tarif nominal rupiah" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm font-mono" required>
                </div>
            </div>

            <div class="pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('obat') ?>" class="px-5 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-primary-hover transition-colors shadow-sm text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- STYLE OVERRIDE FOR SELECT2 -->
<style>
    .select2-container { width: 100% !important; display: block !important; }
    .select2-container--default .select2-selection--single {
        border-color: #D1D5DB !important; border-radius: 0.5rem !important; height: 42px !important; display: flex !important; align-items: center !important; background-color: #FFF !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: #1F2937 !important; font-size: 0.875rem !important; padding-left: 1rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 8px !important; }
</style>

<script>
    $(document).ready(function() {
        $('.select2-supplier').select2({
            allowClear: true,
            width: '100%'
        });
    });
</script>