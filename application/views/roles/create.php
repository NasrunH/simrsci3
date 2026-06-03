<div class="max-w-2xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('roles') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Role Baru</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
        <form action="<?= base_url('roles/create') ?>" method="POST">
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Role <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="<?= set_value('name') ?>" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary font-mono text-sm lowercase" placeholder="contoh: apoteker, perawat_inap, kasir" required>
                <p class="text-xs text-gray-500 mt-2 font-medium">Gunakan hanya huruf kecil, angka, dan underscore (_). Dilarang menggunakan spasi. Sistem akan merubahnya secara otomatis ke format huruf kecil <em>(lowercase)</em>.</p>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('roles') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm">Simpan Role</button>
            </div>
        </form>
    </div>
</div>