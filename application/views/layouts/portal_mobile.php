<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$nav_active = $portal_nav ?? 'home';
$hide_bottom_nav = !empty($hide_bottom_nav);
$pasien_name = $pasien->nama_lengkap ?? $this->session->userdata('username');
$pasien_rm = $pasien->no_rekam_medis ?? '';
$tagihan_badge = (int)($tagihan_badge ?? 0);

$nav_items = [
    'home'    => ['url' => 'portal_pasien', 'label' => 'Beranda', 'icon' => 'home'],
    'live'    => ['url' => 'portal_pasien/antrean_saat_ini', 'label' => 'Antrean', 'icon' => 'radio'],
    'daftar'  => ['url' => 'portal_pasien/buat_antrean', 'label' => 'Daftar', 'icon' => 'plus-circle', 'fab' => true],
    'medis'   => ['url' => 'portal_pasien/rekam_medis', 'label' => 'Medis', 'icon' => 'file-text'],
    'tagihan' => ['url' => 'portal_pasien/billing', 'label' => 'Tagihan', 'icon' => 'wallet'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#14532D">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= htmlspecialchars($title ?? 'Portal Pasien') ?> — SIRS Medika</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/lucide/dist/umd/lucide.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#16A34A',
                        'primary-hover': '#15803D',
                        secondary: '#14B8A6',
                        portal: '#14532D',
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    spacing: { 'safe-b': 'env(safe-area-inset-bottom)' },
                }
            }
        }
    </script>
    <style>
        html { -webkit-tap-highlight-color: transparent; }
        body { overscroll-behavior-y: none; }
        .portal-scroll { -webkit-overflow-scrolling: touch; }
        .nav-fab {
            box-shadow: 0 8px 24px rgba(22, 163, 74, 0.45);
            margin-top: -1.25rem;
        }
        @media (min-width: 768px) {
            .portal-shell { max-width: 28rem; margin-left: auto; margin-right: auto; }
        }
    </style>
</head>
<body class="bg-[#F1F5F9] text-slate-800 font-sans antialiased min-h-[100dvh] flex flex-col">

<!-- App Bar -->
<header class="sticky top-0 z-40 bg-portal text-white shadow-lg portal-shell w-full">
    <div class="px-4 pt-[max(0.75rem,env(safe-area-inset-top))] pb-3 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3 min-w-0 flex-1">
            <?php if (!empty($show_back)): ?>
            <a href="<?= $back_url ?? base_url('portal_pasien') ?>" class="p-2 -ml-2 rounded-full hover:bg-white/10 shrink-0" aria-label="Kembali">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <?php else: ?>
            <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                <i data-lucide="heart-pulse" class="w-5 h-5 text-emerald-300"></i>
            </div>
            <?php endif; ?>
            <div class="min-w-0">
                <p class="text-[10px] uppercase tracking-widest text-emerald-200/80 font-semibold truncate">Portal Pasien</p>
                <h1 class="text-sm font-bold truncate leading-tight"><?= htmlspecialchars($title ?? 'SIRS Medika') ?></h1>
            </div>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <button type="button" id="btnProfile" class="p-2 rounded-full hover:bg-white/10" aria-label="Profil">
                <div class="w-8 h-8 rounded-full bg-emerald-400/30 border border-white/30 flex items-center justify-center text-xs font-bold">
                    <?= strtoupper(substr($pasien_name, 0, 1)) ?>
                </div>
            </button>
        </div>
    </div>
</header>

<!-- Profile Sheet -->
<div id="profileSheet" class="fixed inset-0 z-50 hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" data-close-sheet></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 pb-[calc(1.5rem+env(safe-area-inset-bottom))] shadow-2xl portal-shell mx-auto max-w-md animate-[slideUp_0.25s_ease-out]">
        <div class="w-10 h-1 bg-gray-200 rounded-full mx-auto mb-5"></div>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center text-xl font-black">
                <?= strtoupper(substr($pasien_name, 0, 1)) ?>
            </div>
            <div>
                <p class="font-bold text-gray-900"><?= htmlspecialchars($pasien_name) ?></p>
                <p class="text-xs font-mono text-primary mt-0.5"><?= htmlspecialchars($pasien_rm) ?></p>
            </div>
        </div>
        <a href="<?= base_url('auth/logout') ?>" class="btn-logout flex items-center justify-center gap-2 w-full bg-red-50 text-red-600 font-bold py-3.5 rounded-xl border border-red-100">
            <i data-lucide="log-out" class="w-4 h-4"></i> Keluar Akun
        </a>
    </div>
</div>

<!-- Main -->
<main class="flex-1 overflow-y-auto portal-scroll portal-shell w-full px-4 pt-4 <?= $hide_bottom_nav ? 'pb-6' : 'pb-[calc(5.5rem+env(safe-area-inset-bottom))]' ?>">
    <?php $this->load->view($view_name, $view_data ?? []); ?>
</main>

<!-- Bottom Navigation -->
<?php if (!$hide_bottom_nav): ?>
<nav class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.06)] portal-shell mx-auto" aria-label="Navigasi utama">
    <div class="flex items-end justify-around px-1 pt-1 pb-[max(0.5rem,env(safe-area-inset-bottom))]">
        <?php foreach ($nav_items as $key => $item):
            $is_active = ($nav_active === $key);
            $is_fab = !empty($item['fab']);
        ?>
        <?php if ($is_fab): ?>
        <a href="<?= base_url($item['url']) ?>"
           class="nav-fab flex flex-col items-center justify-center w-14 h-14 rounded-full bg-primary text-white -mt-5 border-4 border-[#F1F5F9] <?= $is_active ? 'ring-2 ring-primary/30' : '' ?>"
           aria-label="<?= $item['label'] ?>">
            <i data-lucide="<?= $item['icon'] ?>" class="w-6 h-6"></i>
        </a>
        <?php else: ?>
        <a href="<?= base_url($item['url']) ?>"
           class="flex flex-col items-center justify-center min-w-[4rem] py-2 px-1 rounded-xl transition-colors relative <?= $is_active ? 'text-primary' : 'text-gray-400' ?>"
           aria-current="<?= $is_active ? 'page' : 'false' ?>">
            <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5"></i>
            <span class="text-[10px] font-semibold mt-1"><?= $item['label'] ?></span>
            <?php if ($key === 'tagihan' && $tagihan_badge > 0): ?>
            <span class="absolute top-1 right-2 min-w-[1rem] h-4 px-1 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center"><?= $tagihan_badge ?></span>
            <?php endif; ?>
        </a>
        <?php endif; ?>
        <?php endforeach; ?>
    </div>
</nav>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const sheet = document.getElementById('profileSheet');
    const btnProfile = document.getElementById('btnProfile');
    if (btnProfile && sheet) {
        btnProfile.addEventListener('click', () => {
            sheet.classList.remove('hidden');
            sheet.setAttribute('aria-hidden', 'false');
        });
        sheet.querySelectorAll('[data-close-sheet]').forEach(el => {
            el.addEventListener('click', () => {
                sheet.classList.add('hidden');
                sheet.setAttribute('aria-hidden', 'true');
            });
        });
    }

    <?php if ($this->session->flashdata('success')): ?>
    Swal.fire({ icon: 'success', title: 'Berhasil', text: <?= json_encode($this->session->flashdata('success')) ?>, timer: 2800, showConfirmButton: false });
    <?php endif; ?>
    <?php if ($this->session->flashdata('error')): ?>
    Swal.fire({ icon: 'error', title: 'Gagal', text: <?= json_encode($this->session->flashdata('error')) ?>, confirmButtonColor: '#16A34A' });
    <?php endif; ?>

    document.querySelectorAll('.btn-logout').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Keluar?',
                text: 'Anda akan logout dari portal pasien.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16A34A',
                cancelButtonColor: '#94A3B8',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
            }).then(r => { if (r.isConfirmed) window.location.href = btn.href; });
        });
    });
});
</script>
<style>@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }</style>
</body>
</html>
