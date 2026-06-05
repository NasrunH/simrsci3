<?php
$permissions = $this->session->userdata('permissions') ?? [];
$can_create  = in_array('create_antrean', $permissions);
$can_edit    = in_array('edit_rm', $permissions);
$can_call    = !empty($can_call);
$role        = $role ?? strtolower($this->session->userdata('role'));

$status_tabs = [
    'menunggu'  => ['label' => 'Menunggu',   'key' => 'Menunggu',  'color' => 'amber'],
    'dipanggil' => ['label' => 'Dipanggil',  'key' => 'Diperiksa', 'color' => 'blue'],
    'selesai'   => ['label' => 'Selesai',    'key' => 'Selesai',   'color' => 'green'],
    'batal'     => ['label' => 'Batal',      'key' => 'Batal',     'color' => 'red'],
];

// Siapkan daftar poli: semua layanan + yang punya antrean hari ini
$poli_map = [];
foreach ($layanan_all as $l) {
    $poli_map[$l->id_layanan] = $l->nama_layanan;
}
foreach ($grouped as $lid => $g) {
    if (!isset($poli_map[$lid]) && $lid) {
        $poli_map[$lid] = $g['nama_layanan'];
    }
}
asort($poli_map);

$total_all = count($antrean);
?>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Antrean Poliklinik</h1>
        <p class="text-gray-500 text-sm mt-1">
            Hari ini: <strong><?= date('d M Y', strtotime($tanggal)) ?></strong>
            &middot; <span class="text-primary font-semibold"><?= $total_all ?> pasien</span>
        </p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="<?= base_url('live_board') ?>" target="_blank" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
            <i data-lucide="monitor" class="w-4 h-4"></i> Live Board
        </a>
        <?php if ($can_create): ?>
        <a href="<?= base_url('antrean/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Daftar Kunjungan
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Tab Poliklinik -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-4 overflow-hidden">
    <div class="flex overflow-x-auto border-b border-gray-100 scrollbar-thin" id="poliTabs" role="tablist">
        <button type="button" data-poli="all"
            class="poli-tab shrink-0 px-5 py-3.5 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap <?= $active_poli === 'all' ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-gray-500 hover:text-gray-800' ?>">
            Semua Poli
            <span class="ml-1.5 bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full"><?= $total_all ?></span>
        </button>
        <?php foreach ($poli_map as $pid => $pnama):
            $cnt = 0;
            if (isset($grouped[$pid])) {
                foreach ($status_tabs as $st) {
                    $cnt += count($grouped[$pid][$st['key']] ?? []);
                }
            }
        ?>
        <button type="button" data-poli="<?= (int)$pid ?>"
            class="poli-tab shrink-0 px-5 py-3.5 text-sm font-semibold border-b-2 transition-colors whitespace-nowrap <?= (string)$active_poli === (string)$pid ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-gray-500 hover:text-gray-800' ?>">
            <?= htmlspecialchars($pnama) ?>
            <span class="ml-1.5 bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full poli-count" data-poli-count="<?= (int)$pid ?>"><?= $cnt ?></span>
        </button>
        <?php endforeach; ?>
    </div>
</div>

<?php
// Render panel per poli + panel "all"
$panels = ['all' => ['nama' => 'Semua Poliklinik', 'id' => 'all']];
foreach ($poli_map as $pid => $pnama) {
    $panels[$pid] = ['nama' => $pnama, 'id' => $pid];
}

foreach ($panels as $panel_id => $panel_info):
    $is_all = ($panel_id === 'all');
    $poli_key = $is_all ? null : $panel_id;
    $panel_visible = ($active_poli === 'all' && $is_all) || ((string)$active_poli === (string)$panel_id);
?>

<div class="poli-panel <?= $panel_visible ? '' : 'hidden' ?>" data-poli-panel="<?= $is_all ? 'all' : (int)$panel_id ?>">

    <?php if ($is_all): ?>
    <!-- Ringkasan semua poli -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
        <?php foreach ($poli_map as $pid => $pnama):
            $g = $grouped[$pid] ?? null;
            $wait = count($g['Menunggu'] ?? []);
            $call = count($g['Diperiksa'] ?? []);
            $done = count($g['Selesai'] ?? []);
            $active_call = !empty($g['Diperiksa']) ? end($g['Diperiksa']) : null;
        ?>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm hover:border-primary/40 transition-colors">
            <div class="flex justify-between items-start mb-3">
                <h3 class="font-bold text-gray-800"><?= htmlspecialchars($pnama) ?></h3>
                <button type="button" class="text-xs font-bold text-primary hover:underline goto-poli-btn" data-goto-poli="<?= (int)$pid ?>">Kelola &rarr;</button>
            </div>
            <div class="grid grid-cols-3 gap-2 text-center text-xs mb-4">
                <div class="bg-amber-50 rounded-lg py-2"><span class="block font-black text-lg text-amber-700"><?= $wait ?></span>Menunggu</div>
                <div class="bg-blue-50 rounded-lg py-2"><span class="block font-black text-lg text-blue-700"><?= $call ?></span>Dipanggil</div>
                <div class="bg-green-50 rounded-lg py-2"><span class="block font-black text-lg text-green-700"><?= $done ?></span>Selesai</div>
            </div>
            <?php if ($active_call): ?>
            <p class="text-sm text-blue-800 bg-blue-50 rounded-lg px-3 py-2">
                Sedang dipanggil: <strong class="font-mono">#<?= (int)$active_call->no_antrean ?></strong>
                <?= htmlspecialchars($active_call->nama_pasien) ?>
            </p>
            <?php elseif ($wait > 0): ?>
            <p class="text-sm text-gray-500"><?= $wait ?> pasien menunggu panggilan.</p>
            <?php else: ?>
            <p class="text-sm text-gray-400">Belum ada antrean hari ini.</p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php if (empty($poli_map)): ?>
        <div class="col-span-full text-center py-12 text-gray-500 bg-white rounded-xl border">Belum ada data poliklinik.</div>
        <?php endif; ?>
    </div>
    <?php else: ?>

    <?php
        $g = $grouped[$poli_key] ?? [
            'nama_layanan' => $panel_info['nama'],
            'Menunggu' => [], 'Diperiksa' => [], 'Selesai' => [], 'Batal' => [],
        ];
    ?>

    <!-- Tombol panggil selanjutnya -->
    <?php if ($can_call): ?>
    <div class="bg-gradient-to-r from-primary to-secondary rounded-xl p-5 mb-4 text-white shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-white/90 text-sm font-medium"><?= htmlspecialchars($panel_info['nama']) ?></p>
            <p class="text-lg font-bold mt-0.5">
                <?php $next_preview = !empty($g['Menunggu']) ? $g['Menunggu'][0] : null; ?>
                <?php if ($next_preview): ?>
                    Berikutnya: <span class="font-mono text-2xl">#<?= (int)$next_preview->no_antrean ?></span>
                    — <?= htmlspecialchars($next_preview->nama_pasien) ?>
                <?php else: ?>
                    Tidak ada antrean menunggu
                <?php endif; ?>
            </p>
        </div>
        <button type="button"
            class="btn-panggil-selanjutnya bg-white text-primary hover:bg-gray-50 px-6 py-3 rounded-xl font-bold text-sm shadow-lg flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            data-layanan-id="<?= (int)$poli_key ?>"
            <?= empty($g['Menunggu']) ? 'disabled' : '' ?>>
            <i data-lucide="volume-2" class="w-5 h-5"></i>
            Panggil Antrean Selanjutnya
        </button>
    </div>
    <?php endif; ?>

    <!-- Sub-tab status -->
    <div class="flex overflow-x-auto gap-1 mb-4 bg-white p-1.5 rounded-xl border border-gray-200 shadow-sm" role="tablist">
        <?php foreach ($status_tabs as $tab_key => $tab_info):
            $count = count($g[$tab_info['key']] ?? []);
            $active = ($active_subtab === $tab_key && $panel_visible) || (!$panel_visible && false);
            // subtab active only when this poli panel is active
            $sub_active = ((string)$active_poli === (string)$panel_id) && ($active_subtab === $tab_key);
        ?>
        <button type="button"
            class="status-tab flex-1 min-w-[7rem] px-4 py-2.5 rounded-lg text-sm font-semibold transition-colors whitespace-nowrap <?= $sub_active ? 'bg-'.$tab_info['color'].'-100 text-'.$tab_info['color'].'-800 shadow-sm' : 'text-gray-500 hover:bg-gray-50' ?>"
            data-status-tab="<?= $tab_key ?>"
            data-poli="<?= (int)$poli_key ?>">
            <?= $tab_info['label'] ?>
            <span class="ml-1 text-xs font-bold opacity-75">(<?= $count ?>)</span>
        </button>
        <?php endforeach; ?>
    </div>

    <!-- Konten per sub-tab -->
    <?php foreach ($status_tabs as $tab_key => $tab_info):
        $items = $g[$tab_info['key']] ?? [];
        $sub_active = ((string)$active_poli === (string)$panel_id) && ($active_subtab === $tab_key);
    ?>
    <div class="status-panel space-y-3 mb-6 <?= $sub_active ? '' : 'hidden' ?>"
         data-status-panel="<?= $tab_key ?>"
         data-poli="<?= (int)$poli_key ?>">

        <?php if (empty($items)): ?>
        <div class="bg-white rounded-xl border border-dashed border-gray-200 py-12 text-center text-gray-400 text-sm">
            Tidak ada antrean dengan status <strong><?= $tab_info['label'] ?></strong>.
        </div>
        <?php else: ?>
            <?php foreach ($items as $a):
                $statusVal = $a->status ?? 'Menunggu';
            ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4 hover:border-primary/30 transition-colors antrean-card"
                 data-id="<?= (int)$a->id_antrean ?>"
                 data-no="<?= (int)$a->no_antrean ?>"
                 data-pasien="<?= htmlspecialchars($a->nama_pasien ?? '', ENT_QUOTES) ?>"
                 data-poli="<?= htmlspecialchars($a->nama_layanan ?? '', ENT_QUOTES) ?>"
                 data-dokter="<?= htmlspecialchars($a->nama_dokter ?? '', ENT_QUOTES) ?>">

                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <div class="w-14 h-14 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-mono font-black text-xl shrink-0">
                        <?= (int)($a->no_antrean ?? 0) ?>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-bold text-gray-800 truncate"><?= htmlspecialchars($a->nama_pasien ?? '') ?></p>
                        <p class="text-xs text-primary font-mono"><?= htmlspecialchars($a->no_rekam_medis ?? '') ?></p>
                        <p class="text-sm text-gray-500 mt-1">
                            <?= htmlspecialchars($a->nama_dokter ?? '') ?>
                            <span class="text-gray-300">|</span>
                            <?= htmlspecialchars($a->spesialisasi ?? '') ?>
                        </p>
                        <?php if (!empty($a->keluhan_awal)): ?>
                        <p class="text-xs text-gray-400 italic mt-1 truncate" title="<?= htmlspecialchars($a->keluhan_awal) ?>">"<?= htmlspecialchars($a->keluhan_awal) ?>"</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($can_call): ?>
                <div class="flex flex-wrap gap-2 sm:justify-end shrink-0">
                    <?php if ($statusVal === 'Menunggu'): ?>
                    <button type="button" class="btn-panggil-satu bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-1.5"
                        data-id="<?= (int)$a->id_antrean ?>"
                        data-action="panggil">
                        <i data-lucide="volume-2" class="w-3.5 h-3.5"></i> Panggil
                    </button>
                    <a href="<?= base_url('antrean/update_status/'.$a->id_antrean.'/Batal') ?>" class="bg-red-50 text-red-700 hover:bg-red-100 px-3 py-2 rounded-lg text-xs font-bold">Batal</a>

                    <?php elseif ($statusVal === 'Diperiksa'): ?>
                    <button type="button" class="btn-panggil-ulang bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-1.5"
                        data-id="<?= (int)$a->id_antrean ?>">
                        <i data-lucide="repeat" class="w-3.5 h-3.5"></i> Panggil Ulang
                    </button>
                    <?php if ($can_edit): ?>
                    <a href="<?= base_url('rekam_medis/create?pasien='.$a->id_pasien.'&dokter='.$a->id_dokter) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold">Isi SOAP</a>
                    <?php endif; ?>
                    <a href="<?= base_url('antrean/update_status/'.$a->id_antrean.'/Selesai') ?>" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-xs font-bold">Selesai</a>

                    <?php else: ?>
                    <span class="text-xs text-gray-400 font-medium px-2"><?= $statusVal ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<!-- Toast panggilan -->
<div id="callToast" class="fixed bottom-6 right-6 z-50 hidden max-w-sm bg-slate-900 text-white rounded-xl shadow-2xl p-4 border border-slate-700">
    <p class="text-xs text-slate-400 uppercase tracking-wider">Sedang memanggil</p>
    <p id="callToastText" class="font-bold text-lg mt-1"></p>
</div>

<script>
(function () {
    const baseUrl = <?= json_encode(base_url()) ?>;
    const canCall = <?= $can_call ? 'true' : 'false' ?>;

    let activePoli = <?= json_encode((string)$active_poli) ?>;
    let activeTab  = <?= json_encode($active_subtab) ?>;

    function updateUrl() {
        const params = new URLSearchParams();
        if (activePoli !== 'all') params.set('poli', activePoli);
        if (activeTab !== 'menunggu') params.set('tab', activeTab);
        const qs = params.toString();
        history.replaceState(null, '', baseUrl + 'antrean' + (qs ? '?' + qs : ''));
    }

    function setPoliTab(poli) {
        activePoli = String(poli);
        document.querySelectorAll('.poli-tab').forEach(btn => {
            const isActive = btn.dataset.poli === activePoli;
            btn.classList.toggle('border-primary', isActive);
            btn.classList.toggle('text-primary', isActive);
            btn.classList.toggle('bg-primary/5', isActive);
            btn.classList.toggle('border-transparent', !isActive);
            btn.classList.toggle('text-gray-500', !isActive);
        });
        document.querySelectorAll('.poli-panel').forEach(p => {
            p.classList.toggle('hidden', p.dataset.poliPanel !== activePoli);
        });
        if (activePoli !== 'all') {
            syncStatusTabs();
        }
        updateUrl();
    }

    function setStatusTab(tab, poliId) {
        activeTab = tab;
        const poli = poliId || activePoli;
        const activeMap = {
            menunggu:  ['bg-amber-100', 'text-amber-800', 'shadow-sm'],
            dipanggil: ['bg-blue-100', 'text-blue-800', 'shadow-sm'],
            selesai:   ['bg-green-100', 'text-green-800', 'shadow-sm'],
            batal:     ['bg-red-100', 'text-red-800', 'shadow-sm'],
        };
        const allActive = Object.values(activeMap).flat();

        document.querySelectorAll(`.status-tab[data-poli="${poli}"]`).forEach(btn => {
            btn.classList.remove(...allActive, 'text-gray-500');
            if (btn.dataset.statusTab === tab) {
                btn.classList.add(...(activeMap[tab] || []));
            } else {
                btn.classList.add('text-gray-500');
            }
        });
        document.querySelectorAll(`.status-panel[data-poli="${poli}"]`).forEach(p => {
            p.classList.toggle('hidden', p.dataset.statusPanel !== tab);
        });
        updateUrl();
    }

    function syncStatusTabs() {
        const panel = document.querySelector(`.poli-panel[data-poli-panel="${activePoli}"]`);
        if (!panel) return;
        setStatusTab(activeTab, activePoli);
    }

    document.querySelectorAll('.poli-tab').forEach(btn => {
        btn.addEventListener('click', () => setPoliTab(btn.dataset.poli));
    });
    document.querySelectorAll('.goto-poli-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            activeTab = 'menunggu';
            setPoliTab(btn.dataset.gotoPoli);
            setStatusTab('menunggu', btn.dataset.gotoPoli);
        });
    });
    document.querySelectorAll('.status-tab').forEach(btn => {
        btn.addEventListener('click', () => setStatusTab(btn.dataset.statusTab, btn.dataset.poli));
    });

    function showToast(text) {
        const t = document.getElementById('callToast');
        document.getElementById('callToastText').textContent = text;
        t.classList.remove('hidden');
        setTimeout(() => t.classList.add('hidden'), 4000);
    }

    function speakAntrean(data) {
        if (!data) return;
        const nomor = data.no_antrean;
        const nama  = data.nama_pasien || 'pasien';
        const poli  = data.nama_layanan || 'poliklinik';
        const text  = `Nomor antrean ${nomor}, atas nama ${nama}, silakan menuju ${poli}. Terima kasih.`;

        showToast(`#${nomor} — ${nama}`);

        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const u = new SpeechSynthesisUtterance(text);
            u.lang = 'id-ID';
            u.rate = 0.92;
            u.pitch = 1;
            const voices = speechSynthesis.getVoices();
            const idVoice = voices.find(v => v.lang && v.lang.startsWith('id'));
            if (idVoice) u.voice = idVoice;
            speechSynthesis.speak(u);
        }
    }

    if (speechSynthesis.onvoiceschanged !== undefined) {
        speechSynthesis.onvoiceschanged = () => speechSynthesis.getVoices();
    }

    async function panggilSelanjutnya(layananId) {
        const btn = document.querySelector(`.btn-panggil-selanjutnya[data-layanan-id="${layananId}"]`);
        if (btn) btn.disabled = true;

        try {
            const res = await fetch(baseUrl + 'antrean/panggil_selanjutnya', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
                body: 'id_layanan=' + encodeURIComponent(layananId),
            });
            const json = await res.json();
            if (!json.success) {
                Swal.fire({ icon: 'info', title: 'Informasi', text: json.message, confirmButtonColor: '#16A34A' });
                if (btn) btn.disabled = false;
                return;
            }
            speakAntrean(json.antrean);
            setTimeout(() => {
                activeTab = 'dipanggil';
                window.location.href = json.redirect || (baseUrl + 'antrean?poli=' + layananId + '&tab=dipanggil');
            }, 3500);
        } catch (e) {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memanggil antrean.', confirmButtonColor: '#EF4444' });
            if (btn) btn.disabled = false;
        }
    }

    async function panggilUlang(id) {
        try {
            const res = await fetch(baseUrl + 'antrean/panggil_ulang/' + id, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const json = await res.json();
            if (!json.success) {
                Swal.fire({ icon: 'warning', title: 'Gagal', text: json.message, confirmButtonColor: '#16A34A' });
                return;
            }
            speakAntrean(json.antrean);
        } catch (e) {
            const card = document.querySelector(`.antrean-card[data-id="${id}"]`);
            if (card) {
                speakAntrean({
                    no_antrean: card.dataset.no,
                    nama_pasien: card.dataset.pasien,
                    nama_layanan: card.dataset.poli,
                });
            }
        }
    }

    document.querySelectorAll('.btn-panggil-selanjutnya').forEach(btn => {
        btn.addEventListener('click', () => panggilSelanjutnya(btn.dataset.layananId));
    });
    document.querySelectorAll('.btn-panggil-ulang').forEach(btn => {
        btn.addEventListener('click', () => panggilUlang(btn.dataset.id));
    });

    document.querySelectorAll('.btn-panggil-satu').forEach(btn => {
        btn.addEventListener('click', () => {
            const card = btn.closest('.antrean-card');
            speakAntrean({
                no_antrean: card.dataset.no,
                nama_pasien: card.dataset.pasien,
                nama_layanan: card.dataset.poli,
                nama_dokter: card.dataset.dokter,
            });
            setTimeout(() => {
                window.location.href = baseUrl + 'antrean/update_status/' + btn.dataset.id + '/Diperiksa';
            }, 2800);
        });
    });

    if (typeof lucide !== 'undefined') lucide.createIcons();
})();
</script>
