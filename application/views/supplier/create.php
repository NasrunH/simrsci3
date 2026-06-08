<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('supplier') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Daftarkan Supplier</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <?php if(validation_errors()): ?>
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r-lg text-sm">
                <?= validation_errors() ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('supplier/create') ?>" method="POST">
            
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Perusahaan / Supplier <span class="text-red-500">*</span></label>
                <input type="text" name="nama_supplier" value="<?= set_value('nama_supplier') ?>" placeholder="Contoh: PT. Sumber Sehat Farmasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon Kantor / Sales</label>
                <input type="text" name="no_telp" value="<?= set_value('no_telp') ?>" placeholder="Contoh: 021-12345678" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap Perusahaan</label>
                <textarea name="alamat" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary" placeholder="Tulis nama jalan, blok, dan kota distributor..."><?= set_value('alamat') ?></textarea>
            </div>

            <div class="pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('supplier') ?>" class="px-5 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-primary-hover transition-colors shadow-sm text-sm">Daftarkan Mitra</button>
            </div>
        </form>
    </div>
</div>