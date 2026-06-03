<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien - SIRS Medika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#16A34A', secondary: '#14B8A6' } } }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen py-8">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl flex overflow-hidden mx-4">
        
        <!-- Sisi Kiri (Ilustrasi) -->
        <div class="hidden lg:flex lg:w-2/5 bg-gradient-to-br from-primary to-secondary p-12 flex-col justify-between relative overflow-hidden">
            <svg class="absolute top-0 right-0 transform translate-x-1/4 -translate-y-1/4 opacity-10 w-96 h-96 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-4H6v-2h4V7h2v4h4v2h-4v4z"/></svg>

            <div class="relative z-10 text-white">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-white/20 p-2.5 rounded-lg backdrop-blur-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h1 class="text-3xl font-bold tracking-wider">SIRS Medika</h1>
                </div>
                <h2 class="text-4xl font-bold leading-tight mb-4">Mulai Rekam Medis Anda</h2>
                <p class="text-teal-50 text-lg opacity-90 leading-relaxed">Daftarkan diri Anda untuk mendapatkan pelayanan medis terpadu dan melihat riwayat resep obat Anda.</p>
            </div>
            
            <div class="relative z-10 bg-white/10 p-5 rounded-xl border border-white/20 mt-10">
                <p class="text-white text-sm font-medium">✅ Data Anda dijamin kerahasiaannya.</p>
                <p class="text-white text-sm font-medium mt-2">✅ Nomor Rekam Medis (RM) akan dibuat otomatis.</p>
            </div>
        </div>

        <!-- Sisi Kanan (Form Registrasi) -->
        <div class="w-full lg:w-3/5 p-8 sm:p-12 overflow-y-auto max-h-[90vh]">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Daftar Pasien Baru</h2>
                <p class="text-gray-500">Lengkapi formulir di bawah ini untuk membuat akun Anda.</p>
            </div>

            <form action="<?= base_url('auth/register_process') ?>" method="POST">
                
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2">Informasi Akun (Untuk Login)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700" placeholder="Tanpa spasi" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password_register" class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700" placeholder="Minimal 6 karakter" required>
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" onclick="togglePasswordVisibility('password_register', 'icon_register_pass')">
                                <svg id="icon_register_pass" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ulangi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirm" id="password_confirm" class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700" placeholder="Ulangi password yang sama" required onchange="checkPasswordMatch()">
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors" onclick="togglePasswordVisibility('password_confirm', 'icon_register_confirm')">
                                <svg id="icon_register_confirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        <div id="password_match_message" class="text-xs mt-1"></div>
                    </div>
                </div>

                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b border-gray-100 pb-2 mt-8">Profil Pasien</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap Sesuai KTP <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700" placeholder="Nama Lengkap Anda" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700 bg-white" required>
                            <option value="" disabled selected>Pilih...</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap Tempat Tinggal</label>
                        <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary text-gray-700" placeholder="Tuliskan nama jalan, RT/RW, dan Kota..."></textarea>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-primary hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg shadow-primary/30 text-lg">
                        Daftar Sebagai Pasien
                    </button>
                </div>

            </form>

            <div class="mt-8 text-center text-sm text-gray-600">
                Sudah memiliki akun? 
                <a href="<?= base_url('login') ?>" class="text-primary font-bold hover:underline ml-1">Kembali ke halaman Login</a>
            </div>

        </div>
    </div>

    <?php if($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'Registrasi Gagal', text: '<?= $this->session->flashdata("error") ?>', confirmButtonColor: '#16A34A' });
    </script>
    <?php endif; ?>

    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password_register').value;
            const confirmPassword = document.getElementById('password_confirm').value;
            const message = document.getElementById('password_match_message');

            if (confirmPassword === '') {
                message.innerHTML = '';
                return;
            }

            if (password === confirmPassword) {
                message.innerHTML = '<span class="text-green-600 font-medium">✓ Password cocok</span>';
            } else {
                message.innerHTML = '<span class="text-red-600 font-medium">✗ Password tidak cocok</span>';
            }
        }

        // Check on input as well for real-time feedback
        document.getElementById('password_register').addEventListener('input', checkPasswordMatch);
        document.getElementById('password_confirm').addEventListener('input', checkPasswordMatch);
    </script>

</body>
</html>