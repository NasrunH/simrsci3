<style>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<div class="flex items-center justify-between mb-3">
    <p class="text-sm text-gray-500"><?= date('d M Y') ?></p>
    <span class="inline-flex items-center gap-1.5 text-[10px] font-bold text-primary bg-primary/10 px-2.5 py-1 rounded-full">
        <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
        LIVE · 10 detik
    </span>
</div>

<!-- Tabs Layanan / Poliklinik -->
<div class="mb-4 overflow-x-auto no-scrollbar -mx-4 px-4 pb-2">
    <div id="tabsContainer" class="flex gap-2 whitespace-nowrap">
        <div class="h-8 w-24 bg-gray-200 rounded-full animate-pulse"></div>
        <div class="h-8 w-24 bg-gray-200 rounded-full animate-pulse"></div>
        <div class="h-8 w-24 bg-gray-200 rounded-full animate-pulse"></div>
    </div>
</div>

<div id="boardAntreanContainer" class="space-y-3">
    <div class="py-16 flex flex-col items-center text-gray-400 gap-2">
        <div class="w-10 h-10 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
        <p class="text-xs font-semibold">Memuat data antrean...</p>
    </div>
</div>

<script>
$(function () {
    var currentData = [];
    var selectedLayananId = 'all';
    var tabsGenerated = false;

    function renderBoard(data) {
        if (!data.length) {
            return '<div class="bg-white rounded-2xl p-10 text-center text-gray-400 text-sm">Belum ada antrean aktif pada kategori ini.</div>';
        }
        var html = '';
        $.each(data, function (_, val) {
            var serving = val.sedang_diperiksa !== '-';
            var numClass = serving ? 'bg-primary text-white shadow-lg' : 'bg-gray-100 text-gray-400';
            html += `
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-50">
                    <div class="flex justify-between items-start gap-2">
                        <h3 class="font-bold text-gray-800">${val.nama_layanan}</h3>
                        <span class="text-[10px] font-bold text-primary bg-green-50 px-2 py-0.5 rounded-full shrink-0">Rp ${val.tarif}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 truncate">${val.dokter_bertugas}</p>
                </div>
                <div class="py-6 flex flex-col items-center bg-gray-50/80">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Sedang Dipanggil</p>
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-mono font-black text-4xl ${numClass}">${val.sedang_diperiksa}</div>
                </div>
                <div class="grid grid-cols-2 divide-x divide-gray-100 text-center py-3 bg-white">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Terakhir</p>
                        <p class="font-mono font-bold text-lg">${val.antrean_terakhir}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-amber-500 font-bold uppercase">Menunggu</p>
                        <p class="font-mono font-black text-lg text-amber-600">${val.sisa_antrean}</p>
                    </div>
                </div>
            </div>`;
        });
        return html;
    }

    function renderTabs(data) {
        if (tabsGenerated) return;
        
        var html = `<button type="button" data-id="all" class="tab-btn px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 bg-primary text-white shadow-sm border border-primary">Semua Poli</button>`;
        
        $.each(data, function (_, val) {
            html += `<button type="button" data-id="${val.id_layanan}" class="tab-btn px-4 py-2 rounded-full text-xs font-bold transition-all duration-200 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50">${val.nama_layanan}</button>`;
        });
        
        $('#tabsContainer').html(html);
        tabsGenerated = true;
        
        $('.tab-btn').on('click', function() {
            $('.tab-btn').removeClass('bg-primary text-white shadow-sm border-primary')
                         .addClass('bg-white text-gray-600 border-gray-200');
            $(this).removeClass('bg-white text-gray-600 border-gray-200')
                   .addClass('bg-primary text-white shadow-sm border-primary');
            
            selectedLayananId = $(this).attr('data-id');
            applyFilter();
        });
    }

    function applyFilter() {
        var filtered = currentData;
        if (selectedLayananId !== 'all') {
            filtered = $.grep(currentData, function(val) {
                return val.id_layanan == selectedLayananId;
            });
        }
        $('#boardAntreanContainer').html(renderBoard(filtered));
    }

    function load() {
        $.getJSON('<?= base_url('portal_pasien/get_active_queues_ajax') ?>', function (data) {
            currentData = data;
            renderTabs(data);
            applyFilter();
        });
    }

    load();
    setInterval(load, 10000);
});
</script>
