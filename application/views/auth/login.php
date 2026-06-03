<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIRS Medika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: { colors: { primary: '#16A34A', secondary: '#14B8A6' } }
            }
        }
    </script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-5xl bg-white rounded-2xl shadow-2xl flex overflow-hidden mx-4 h-[600px]">
        
        <!-- Sisi Kiri (Ilustrasi/Branding) -->
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-secondary to-primary p-12 flex-col justify-between relative overflow-hidden">
            <!-- Ornamen Dekoratif SVG -->
            <svg class="absolute top-0 left-0 transform -translate-x-1/4 -translate-y-1/4 opacity-10 w-96 h-96 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
            <svg class="absolute bottom-0 right-0 transform translate-x-1/3 translate-y-1/4 opacity-10 w-80 h-80 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-4H6v-2h4V7h2v4h4v2h-4v4z"/></svg>

            <div class="relative z-10 text-white">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-white/20 p-2.5 rounded-lg backdrop-blur-sm">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h1 class="text-3xl font-bold tracking-wider">SIRS Medika</h1>
                </div>
                <h2 class="text-4xl font-bold leading-tight mb-4">Sistem Informasi<br>Rumah Sakit Terpadu</h2>
                <p class="text-teal-50 text-lg opacity-90 leading-relaxed">Kelola data pasien, inventaris obat, dan riwayat peresepan dokter dengan mudah, cepat, dan aman.</p>
            </div>
            
            <div class="relative z-10 text-white/70 text-sm">
                &copy; <?= date('Y') ?> SIRS Medika. All rights reserved.
            </div>
        </div>

        <!-- Sisi Kanan (Form Login) -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
            
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang 👋</h2>
                <p class="text-gray-500">Silakan masukkan username dan password Anda untuk masuk ke dashboard.</p>
            </div>

            <form action="<?= base_url('auth/login_process') ?>" method="POST" class="space-y-5">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input type="text" name="username" class="w-full pl-11 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all text-gray-700" placeholder="Ketik username Anda..." required autofocus>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input type="password" name="password" id="password_login" class="w-full pl-11 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all text-gray-700" placeholder="••••••••" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors" onclick="togglePasswordVisibility('password_login', 'icon_login')">
                            <svg id="icon_login" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-primary hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg shadow-primary/30 flex justify-center items-center gap-2">
                        Masuk Ke Sistem
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>

            </form>

            <div class="mt-10 text-center text-sm text-gray-600">
                Belum terdaftar sebagai pasien? <br>
                <a href="<?= base_url('register') ?>" class="text-primary font-bold hover:underline transition-all inline-block mt-1">Daftar Pasien Baru Sekarang &rarr;</a>
            </div>

        </div>
    </div>

    <!-- Tampilkan Error Flashdata -->
    <?php if($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal Login', text: '<?= $this->session->flashdata("error") ?>', confirmButtonColor: '#16A34A' });
    </script>
    <?php endif; ?>
    <?php if($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= $this->session->flashdata("success") ?>', confirmButtonColor: '#16A34A' });
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
    </script>

</body>
</html>