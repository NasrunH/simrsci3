<header class="bg-card h-16 shadow-sm border-b border-bordercolor flex justify-between items-center px-8 shrink-0 z-10">
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggle Button (Mobile/Tablet) -->
        <button id="toggleSidebar" class="md:hidden text-title hover:text-primary transition-colors p-2 hover:bg-gray-100 rounded-lg">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
        <h2 class="text-xl font-bold text-title"><?= isset($title) ? $title : 'Dashboard' ?></h2>
    </div>
    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-title leading-tight"><?= $this->session->userdata('username') ?></p>
            <p class="text-xs text-textsec capitalize"><?= $this->session->userdata('role') ?></p>
        </div>
        <div class="h-9 w-9 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold border border-primary/30">
            <?= strtoupper(substr($this->session->userdata('username'), 0, 1)) ?>
        </div>
    </div>
</header>