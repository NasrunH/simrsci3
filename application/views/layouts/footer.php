<!-- Global Script CI3 -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // 1. Flashdata SweetAlert CodeIgniter
            <?php if($this->session->flashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= $this->session->flashdata("success") ?>',
                    showConfirmButton: false,
                    timer: 2500
                });
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?= $this->session->flashdata("error") ?>',
                    confirmButtonColor: '#EF4444'
                });
            <?php endif; ?>

            // 2. Konfirmasi Logout
            $('.btn-logout').on('click', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Apakah Anda yakin ingin keluar dari sistem?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16A34A',
                    cancelButtonColor: '#9CA3AF',
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });

            // 3. Konfirmasi Delete Global
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                const href = $(this).attr('href');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#9CA3AF',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });
    </script>
</body>
</html>