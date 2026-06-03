<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('dokter') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Registrasi Dokter Baru</h1>
    </div>

    <form action="<?= base_url('dokter/create') ?>" method="POST">
        
        <!-- INFORMASI AKUN -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Akun (Akses Login Dokter)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="<?= set_value('username') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: dr_budi" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Minimal 6 karakter" required>
                </div>
            </div>
        </div>

        <!-- PROFIL DOKTER -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Profil Dokter</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Dokter (beserta gelar) <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_dokter" value="<?= set_value('nama_dokter') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: dr. Budi Santoso, Sp.PD" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                    <select name="spesialisasi" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                        <option value="" disabled <?= set_value('spesialisasi') == '' ? 'selected' : '' ?>>Pilih Spesialisasi</option>
                        <option value="Umum" <?= set_value('spesialisasi') == 'Umum' ? 'selected' : '' ?>>Dokter Umum</option>
                        <option value="Penyakit Dalam" <?= set_value('spesialisasi') == 'Penyakit Dalam' ? 'selected' : '' ?>>Penyakit Dalam (Sp.PD)</option>
                        <option value="Anak" <?= set_value('spesialisasi') == 'Anak' ? 'selected' : '' ?>>Anak (Sp.A)</option>
                        <option value="Kandungan" <?= set_value('spesialisasi') == 'Kandungan' ? 'selected' : '' ?>>Kandungan & Kebidanan (Sp.OG)</option>
                        <option value="Bedah" <?= set_value('spesialisasi') == 'Bedah' ? 'selected' : '' ?>>Bedah (Sp.B)</option>
                        <option value="Gigi" <?= set_value('spesialisasi') == 'Gigi' ? 'selected' : '' ?>>Gigi (drg.)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / WhatsApp <span class="text-red-500">*</span></label>
                    <input type="text" name="no_telp" value="<?= set_value('no_telp') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: 081234567890" required>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('dokter') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm">Simpan Data & Akun</button>
            </div>
        </div>
    </form>
</div>