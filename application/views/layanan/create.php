<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('layanan') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Layanan Medis Baru</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form action="<?= base_url('layanan/create') ?>" method="POST">
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Poliklinik / Layanan <span class="text-red-500">*</span></label>
                <input type="text" name="nama_layanan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm" placeholder="misal: Poli Jantung, Poli Mata" required>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tarif Jasa Medis (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="tarif" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm font-bold" placeholder="misal: 100000" required>
                <p class="text-xs text-gray-500 mt-1">Tarif ini akan otomatis ditarik sebagai tagihan jasa dokter saat pasien berobat ke poli ini.</p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Layanan</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm" placeholder="Keterangan opsional tentang layanan poliklinik..."></textarea>
            </div>

            <div class="pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('layanan') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm text-sm">Simpan Layanan</button>
            </div>
        </form>
    </div>
</div>