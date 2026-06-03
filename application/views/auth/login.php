<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIRS Medika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-green-600">SIRS Medika</h1>
            <p class="text-sm text-gray-500 mt-1">Silakan login untuk melanjutkan</p>
        </div>

        <form action="<?= base_url('auth/login_process') ?>" method="POST">
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" required>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg transition-colors">
                Masuk
            </button>
        </form>
    </div>

    <!-- Tampilkan Error Flashdata -->
    <?php if($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'Gagal Login', text: '<?= $this->session->flashdata("error") ?>' });
    </script>
    <?php endif; ?>
    <?php if($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({ icon: 'success', title: 'Berhasil', text: '<?= $this->session->flashdata("success") ?>' });
    </script>
    <?php endif; ?>

</body>
</html>