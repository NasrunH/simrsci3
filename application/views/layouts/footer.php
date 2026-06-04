<!-- Global Script CI3 -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Initialize Lucide Icons safely
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
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

            // 2. Sidebar Toggle
            const toggleBtn = document.getElementById('toggleSidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (toggleBtn && sidebar) {
                toggleBtn.addEventListener('click', () => {
                    if (window.innerWidth >= 768) {
                        // Desktop collapse
                        sidebar.classList.toggle('w-64');
                        sidebar.classList.toggle('w-20');
                        sidebar.classList.toggle('sidebar-collapsed');
                        
                        const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                        localStorage.setItem('sidebar-collapsed', isCollapsed ? 'true' : 'false');
                    } else {
                        // Mobile slide-over
                        sidebar.classList.toggle('-translate-x-full');
                        sidebar.classList.toggle('translate-x-0');
                        if (overlay) overlay.classList.toggle('hidden');
                    }
                });
            }

            // Close sidebar when clicking overlay on mobile
            if (overlay && sidebar) {
                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    sidebar.classList.remove('translate-x-0');
                    overlay.classList.add('hidden');
                });
            }

            // Close sidebar when clicking a link on mobile
            if (sidebar) {
                const sidebarLinks = sidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 768) {
                            sidebar.classList.add('-translate-x-full');
                            sidebar.classList.remove('translate-x-0');
                            if (overlay) overlay.classList.add('hidden');
                        }
                    });
                });
            }

            // 3. Konfirmasi Logout
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

            // 4. Konfirmasi Delete Global
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