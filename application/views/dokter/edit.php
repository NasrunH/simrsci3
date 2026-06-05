<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('dokter') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Profil Dokter</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form action="<?= base_url('dokter/edit/'.$dokter->id_dokter) ?>" method="POST">
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Dokter <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_dokter" value="<?= htmlspecialchars($dokter->nama_dokter) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Layanan / Poliklinik <span class="text-red-500">*</span></label>
                        <select name="id_layanan" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                            <?php foreach($layanan as $l): ?>
                                <option value="<?= $l->id_layanan ?>" <?= $dokter->id_layanan == $l->id_layanan ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($l->nama_layanan) ?> (Rp <?= number_format($l->tarif, 0, ',', '.') ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                        <input type="text" name="spesialisasi" value="<?= htmlspecialchars($dokter->spesialisasi) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telp" value="<?= htmlspecialchars($dokter->no_telp) ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('dokter') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>