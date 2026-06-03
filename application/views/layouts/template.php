<?php $this->load->view('layouts/header'); ?>
<div class="flex h-screen overflow-hidden bg-mainbg">
    <?php $this->load->view('layouts/sidebar'); ?>
    
    <!-- Sidebar Overlay (Mobile) -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 md:hidden hidden z-10" onclick="document.getElementById('sidebar').classList.add('-translate-x-full'); document.getElementById('sidebar').classList.remove('translate-x-0'); document.getElementById('sidebarOverlay').classList.add('hidden');"></div>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-mainbg">
        <?php $this->load->view('layouts/navbar'); ?>
        
        <div class="flex-1 overflow-y-auto p-8">
            <div class="max-w-7xl mx-auto">
                <!-- KONTEN DINAMIS DIMUAT DI SINI -->
                <?php $this->load->view($view_name, $view_data ?? []); ?>
            </div>
        </div>
    </main>
</div>

<?php $this->load->view('layouts/footer'); ?>