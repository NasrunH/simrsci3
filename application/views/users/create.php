<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('users') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Registrasi User & Profil</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
        <form action="<?= base_url('users/create') ?>" method="POST" id="formUser">
            
            <!-- 1. INFORMASI AKUN (SELALU TAMPIL) -->
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Akun (Login)</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Peran (Role) <span class="text-red-500">*</span></label>
                    <select name="role_id" id="roleSelector" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary bg-gray-50 font-semibold" required>
                        <option value="" disabled selected>-- Pilih Role --</option>
                        <?php foreach($roles as $r): ?>
                            <!-- Perhatikan penambahan atribut data-role -->
                            <option value="<?= $r->id ?>" data-role="<?= strtolower($r->name) ?>">
                                <?= ucfirst($r->name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Tanpa spasi" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" placeholder="Minimal 6 karakter" required>
                </div>
            </div>

            <!-- 2. FORM DINAMIS (AWALNYA DISEMBUNYIKAN) -->
            
            <!-- A. Form Profil Admin -->
            <div id="form-admin" class="dynamic-form hidden bg-red-50/50 p-6 rounded-lg border border-red-100 mb-6">
                <h2 class="text-md font-bold text-red-800 mb-4">Lengkapi Data Profil Admin</h2>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap Admin <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_admin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                </div>
            </div>

            <!-- B. Form Profil Dokter -->
            <div id="form-dokter" class="dynamic-form hidden bg-blue-50/50 p-6 rounded-lg border border-blue-100 mb-6">
                <h2 class="text-md font-bold text-blue-800 mb-4">Lengkapi Data Profil Dokter</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap (beserta gelar) <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_dokter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" placeholder="Contoh: dr. Budi Santoso">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Spesialisasi <span class="text-red-500">*</span></label>
                        <select name="spesialisasi" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="" disabled selected>Pilih Spesialisasi</option>
                            <option value="Umum">Dokter Umum</option>
                            <option value="Penyakit Dalam">Penyakit Dalam (Sp.PD)</option>
                            <option value="Anak">Anak (Sp.A)</option>
                            <option value="Kandungan">Kandungan & Kebidanan (Sp.OG)</option>
                            <option value="Bedah">Bedah (Sp.B)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon / WA <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telp" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- C. Form Profil Pasien -->
            <div id="form-pasien" class="dynamic-form hidden bg-teal-50/50 p-6 rounded-lg border border-teal-100 mb-6">
                <h2 class="text-md font-bold text-teal-800 mb-2">Lengkapi Data Profil Pasien</h2>
                <p class="text-xs text-teal-600 mb-4">* Nomor Rekam Medis akan dibuat otomatis oleh sistem.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap (Opsional)</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></textarea>
                    </div>
                </div>
            </div>

            <!-- TOMBOL SUBMIT -->
            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('users') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" id="btnSubmit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    Pilih Role Terlebih Dahulu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT PENGATUR FORM DINAMIS -->
<script>
    $(document).ready(function() {
        $('#roleSelector').on('change', function() {
            // Ambil atribut 'data-role' (misal: 'admin', 'dokter', atau 'pasien')
            var roleName = $(this).find(':selected').data('role');
            var btn = $('#btnSubmit');

            // 1. Sembunyikan semua form profil & hilangkan properti required
            $('.dynamic-form').hide();
            $('.dynamic-form').find('input, select, textarea').prop('required', false);

            // 2. Munculkan form yang sesuai & tambahkan properti required (kecuali alamat)
            if (roleName) {
                $('#form-' + roleName).fadeIn(); // Animasi muncul
                
                // Tambahkan validasi required di form yang aktif saja
                $('#form-' + roleName).find('input, select').not('[name="alamat"]').prop('required', true);
                
                // Aktifkan tombol submit
                btn.prop('disabled', false).text('Simpan Akun & Data ' + roleName.charAt(0).toUpperCase() + roleName.slice(1));
            } else {
                btn.prop('disabled', true).text('Pilih Role Terlebih Dahulu');
            }
        });
    });
</script>