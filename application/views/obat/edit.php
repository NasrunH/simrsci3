<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('obat') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Obat</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
        
        <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 flex justify-between items-center">
            <div>
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Kode Obat</p>
                <p class="text-xl font-mono font-bold text-gray-700"><?= htmlspecialchars($obat->kode_obat) ?></p>
            </div>
            <span class="text-xs text-gray-500 bg-gray-200 px-2 py-1 rounded">Tidak dapat diubah</span>
        </div>

        <form action="<?= base_url('obat/edit/'.$obat->id_obat) ?>" method="POST">
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat <span class="text-red-500">*</span></label>
                <input type="text" name="nama_obat" value="<?= htmlspecialchars($obat->nama_obat) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori Obat <span class="text-red-500">*</span></label>
                <select name="kategori" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                    <option value="Tablet" <?= $obat->kategori == 'Tablet' ? 'selected' : '' ?>>Tablet</option>
                    <option value="Kapsul" <?= $obat->kategori == 'Kapsul' ? 'selected' : '' ?>>Kapsul</option>
                    <option value="Sirup" <?= $obat->kategori == 'Sirup' ? 'selected' : '' ?>>Sirup</option>
                    <option value="Salep" <?= $obat->kategori == 'Salep' ? 'selected' : '' ?>>Salep</option>
                    <option value="Injeksi" <?= $obat->kategori == 'Injeksi' ? 'selected' : '' ?>>Injeksi / Suntik</option>
                    <option value="Lainnya" <?= $obat->kategori == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Tersedia <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="stok" value="<?= $obat->stok ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="harga" value="<?= floatval($obat->harga) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('obat') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>