<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('pasien') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Pendaftaran Pasien Baru</h1>
    </div>

    <form action="<?= base_url('pasien/create') ?>" method="POST">
        
        <!-- BAGIAN 1: INFORMASI AKUN (LOGIN) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Akun (Untuk Login Pasien)</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="<?= set_value('username') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Buat username (Tanpa Spasi)" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Minimal 6 karakter" required>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: DATA REKAM MEDIS -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Data Profil Pasien</h2>
            
            <div class="bg-blue-50 text-blue-700 p-4 rounded-lg text-sm mb-6 flex gap-3 items-start border border-blue-100">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p>Nomor Rekam Medis (RM) akan dibuat secara otomatis oleh sistem saat data disimpan.</p>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="<?= set_value('nama_lengkap') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Masukkan nama pasien" required>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="<?= set_value('tanggal_lahir') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" required>
                            <option value="" disabled <?= set_value('jenis_kelamin') == '' ? 'selected' : '' ?>>Pilih Jenis Kelamin</option>
                            <option value="L" <?= set_value('jenis_kelamin') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= set_value('jenis_kelamin') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                    <textarea name="alamat" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Contoh: Jl. Mawar No. 12, Jakarta"><?= set_value('alamat') ?></textarea>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('pasien') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm">Simpan Data & Akun</button>
            </div>
        </div>
    </form>
</div>