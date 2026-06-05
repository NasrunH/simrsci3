<div class="max-w-6xl mx-auto space-y-6">
    
    <!-- HEADER PAPAN MONITOR -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white p-6 rounded-2xl border border-gray-200 shadow-sm gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                <span class="relative flex h-3.5 w-3.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-red-500"></span>
                </span>
                LIVE BOARD: Papan Antrean Medis
            </h1>
            <p class="text-sm text-gray-500 mt-1">Daftar pemanggilan aktif per poliklinik hari ini: <strong><?= date('d M Y') ?></strong></p>
        </div>
        <div class="text-xs bg-gray-100 text-gray-600 px-3 py-2 rounded-xl font-medium border border-gray-200 flex items-center gap-2">
            <svg class="w-4 h-4 text-primary animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 8H12v3"/>
            </svg>
            Auto-refresh setiap <span class="font-bold text-primary">10s</span>
        </div>
    </div>

    <!-- AREA CARD POLIKLINIK (CONTAINER DYNAMIC AJAX) -->
    <div id="boardAntreanContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loader Awal saat memproses data -->
        <div class="col-span-full py-20 flex flex-col justify-center items-center text-gray-400 gap-3">
            <svg class="w-12 h-12 text-primary animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="font-bold text-sm tracking-wide uppercase">Sinkronisasi Papan Layanan...</p>
        </div>
    </div>

</div>

<!-- JAVASCRIPT POLLING ENGINE -->
<script>
    $(document).ready(function() {
        
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Fungsi utama untuk memuat data live antrean dari API Controller
        function muatPapanAntrean() {
            $.ajax({
                url: '<?= base_url("portal_pasien/get_active_queues_ajax") ?>',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var html = '';

                    if (data.length > 0) {
                        $.each(data, function(key, val) {
                            // Cek jika ada pemanggilan aktif
                            var isServing = val.sedang_diperiksa !== '-';
                            var mainNumClass = isServing 
                                ? 'bg-gradient-to-br from-green-500 to-green-600 text-white shadow-green-200' 
                                : 'bg-gray-100 text-gray-400';

                            html += `
                                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col justify-between">
                                    <!-- Bagian Atas: Info Poli -->
                                    <div class="p-6 border-b border-gray-100 flex-1">
                                        <div class="flex justify-between items-start gap-2">
                                            <h3 class="font-extrabold text-gray-800 text-lg leading-tight">${val.nama_layanan}</h3>
                                            <span class="text-xs text-primary font-bold bg-green-50 px-2 py-0.5 rounded border border-green-200 whitespace-nowrap">Rp ${val.tarif}</span>
                                        </div>
                                        <p class="text-xs text-gray-400 font-bold uppercase mt-3 tracking-wide">Dokter Bertugas</p>
                                        <p class="text-sm font-semibold text-gray-700 mt-0.5">${val.dokter_bertugas}</p>
                                    </div>

                                    <!-- Bagian Tengah: DISPLAY NOMOR UTAMA -->
                                    <div class="p-6 bg-gray-50/50 flex flex-col items-center justify-center text-center">
                                        <span class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Sedang Diperiksa</span>
                                        <div class="w-24 h-24 rounded-2xl flex items-center justify-center font-mono font-black text-5xl shadow-lg ${mainNumClass}">
                                            ${val.sedang_diperiksa}
                                        </div>
                                    </div>

                                    <!-- Bagian Bawah: Statistik Tambahan -->
                                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 grid grid-cols-2 gap-4 text-center">
                                        <div class="border-r border-gray-200/60">
                                            <span class="text-[9px] font-bold text-gray-400 uppercase block tracking-wider">Antrean Terakhir</span>
                                            <span class="font-mono font-bold text-gray-800 text-base">${val.antrean_terakhir}</span>
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-amber-500 uppercase block tracking-wider">Sisa Antrean</span>
                                            <span class="font-mono font-black text-amber-600 text-base">${val.sisa_antrean} Orang</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        html = `
                            <div class="col-span-full py-16 text-center text-gray-400">
                                Belum ada unit pelayanan poliklinik yang dikonfigurasi di SIMRS.
                            </div>
                        `;
                    }

                    // Suntik html baru ke kontainer monitor
                    $('#boardAntreanContainer').html(html);
                },
                error: function() {
                    console.log('Sinkronisasi Monitor Antrean Gagal Terhubung.');
                }
            });
        }

        // Jalankan saat halaman pertama kali dibuka
        muatPapanAntrean();

        // Aktifkan loop interval 10 detik (10000ms) untuk auto-polling
        setInterval(function() {
            muatPapanAntrean();
        }, 10000);
    });
</script>