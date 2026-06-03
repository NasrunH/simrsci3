<?php $this->load->view('layouts/header'); ?>
<?php $this->load->view('layouts/sidebar'); ?>

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

<?php $this->load->view('layouts/footer'); ?>