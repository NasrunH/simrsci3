<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('dokter') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Registrasi Dokter Baru</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        <form action="<?= base_url('dokter/create') ?>" method="POST">
            
            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Informasi Akun</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Ketik username login" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Profil & Poliklinik</h2>
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Dokter (beserta gelar) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_dokter" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="contoh: dr. Ahmad Yani, Sp.A" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Layanan / Poliklinik <span class="text-red-500">*</span></label>
                        <select name="id_layanan" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                            <option value="" disabled selected>-- Pilih Poli --</option>
                            <?php foreach($layanan as $l): ?>
                                <option value="<?= $l->id_layanan ?>"><?= htmlspecialchars($l->nama_layanan) ?> (Rp <?= number_format($l->tarif, 0, ',', '.') ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                        <input type="text" name="spesialisasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="contoh: Spesialis Anak, Umum" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / HP <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telp" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('dokter') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm text-sm">Simpan Dokter</button>
            </div>
        </form>
    </div>
</div>