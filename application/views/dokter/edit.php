<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('dokter') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Profil Dokter</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
        
        <!-- Info Akun Statis -->
        <div class="mb-6 bg-yellow-50 p-4 rounded-lg border border-yellow-200 flex gap-3 items-start text-yellow-800">
            <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="text-sm">Untuk mengubah Username atau Password dokter, Anda harus melakukannya melalui menu <a href="<?= base_url('users') ?>" class="font-bold underline">Manajemen User</a>.</p>
        </div>

        <form action="<?= base_url('dokter/edit/'.$dokter->id_dokter) ?>" method="POST">
            <div class="grid grid-cols-1 gap-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Dokter (beserta gelar) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_dokter" value="<?= htmlspecialchars($dokter->nama_dokter) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                        <select name="spesialisasi" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                            <option value="Umum" <?= $dokter->spesialisasi == 'Umum' ? 'selected' : '' ?>>Dokter Umum</option>
                            <option value="Penyakit Dalam" <?= $dokter->spesialisasi == 'Penyakit Dalam' ? 'selected' : '' ?>>Penyakit Dalam (Sp.PD)</option>
                            <option value="Anak" <?= $dokter->spesialisasi == 'Anak' ? 'selected' : '' ?>>Anak (Sp.A)</option>
                            <option value="Kandungan" <?= $dokter->spesialisasi == 'Kandungan' ? 'selected' : '' ?>>Kandungan & Kebidanan (Sp.OG)</option>
                            <option value="Bedah" <?= $dokter->spesialisasi == 'Bedah' ? 'selected' : '' ?>>Bedah (Sp.B)</option>
                            <option value="Gigi" <?= $dokter->spesialisasi == 'Gigi' ? 'selected' : '' ?>>Gigi (drg.)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telp" value="<?= htmlspecialchars($dokter->no_telp) ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('dokter') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>