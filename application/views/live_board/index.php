<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0B132B; /* Dark deep blue */
        }
        .font-mono-clock {
            font-family: 'Share Tech Mono', monospace;
        }
        /* Custom keyframe animation for running text ticker */
        @keyframes ticker {
            0% { transform: translate3d(100%, 0, 0); }
            100% { transform: translate3d(-100%, 0, 0); }
        }
        .ticker-wrap {
            overflow: hidden;
            white-space: nowrap;
        }
        .ticker-item {
            display: inline-block;
            animation: ticker 25s linear infinite;
        }
    </style>
</head>
<body class="h-screen overflow-hidden flex flex-col justify-between text-white select-none">

    <!-- ==================== HEADER MONITOR ==================== -->
    <header class="bg-slate-900/80 border-b-2 border-emerald-500/30 px-8 py-4 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-4">
            <!-- Icon Hospital -->
            <div class="bg-emerald-500 text-slate-900 p-2.5 rounded-2xl shadow-lg shadow-emerald-500/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white tracking-wider leading-none">SIRS MEDIKA</h1>
                <p class="text-[11px] text-emerald-400 font-bold uppercase tracking-widest mt-1">Sistem Informasi Rawat Jalan Utama</p>
            </div>
        </div>

        <!-- Live Clock & Date -->
        <div class="flex items-center gap-6">
            <div class="text-right">
                <p id="liveDate" class="text-sm font-bold text-gray-400 uppercase tracking-wider">Memuat Hari & Tanggal...</p>
                <p id="liveClock" class="text-3xl font-black text-emerald-400 font-mono-clock leading-none mt-1">00:00:00</p>
            </div>
            <!-- Status Monitor -->
            <div class="bg-emerald-950/50 border border-emerald-500/30 px-3.5 py-1.5 rounded-full flex items-center gap-2">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
                <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider">Monitor Live</span>
            </div>
        </div>
    </header>

    <!-- ==================== MAIN CONTENT AREA ==================== -->
    <main class="flex-1 grid grid-cols-1 lg:grid-cols-12 p-8 gap-8 items-stretch overflow-hidden">
        
        <!-- LEFT SIDE: GRID DAFTAR ANTRIAN POLI (Col 8) -->
        <div class="lg:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-6" id="displayPoliContainer">
            <!-- Data poliklinik diisi lewat AJAX -->
        </div>

        <!-- RIGHT SIDE: HIGHLIGHT PANGGILAN TERBARU (Col 4) -->
        <div class="lg:col-span-4 bg-slate-900 border-2 border-emerald-500 rounded-3xl p-8 flex flex-col justify-between items-center text-center shadow-2xl relative overflow-hidden">
            <!-- Accent glow -->
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 blur-3xl rounded-full"></div>
            
            <div class="w-full">
                <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest block mx-auto w-fit">Panggilan Utama</span>
                <h2 class="text-xl font-bold text-gray-300 mt-6 leading-tight" id="highlightPoli">POLIKLINIK UTAMA</h2>
                <p class="text-sm text-gray-500 font-medium mt-1" id="highlightDokter">Nama Dokter Bertugas</p>
            </div>

            <div class="my-6 w-full">
                <span class="text-[10px] text-emerald-400 font-bold uppercase tracking-wider block mb-2">Nomor Antrean</span>
                <div class="text-8xl font-black font-mono-clock text-white bg-slate-950 py-6 rounded-3xl border border-slate-800 tracking-tight shadow-inner inline-block w-full max-w-[280px]" id="highlightNo">
                    -
                </div>
                <!-- BOX NAMA PASIEN YANG DIHIGHLIGHT -->
                <div class="mt-4 px-4 py-2 bg-slate-950/40 rounded-xl border border-slate-800/60 max-w-[320px] mx-auto truncate">
                    <span class="text-xs text-slate-400 block uppercase tracking-wider font-semibold">Atas Nama Pasien</span>
                    <span class="text-xl font-extrabold text-emerald-400" id="highlightNamaPasien">-</span>
                </div>
            </div>

            <div class="w-full bg-slate-950/80 border border-slate-800 rounded-2xl p-4 flex items-center justify-center gap-3">
                <svg class="w-5 h-5 text-emerald-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
                <span class="text-xs font-semibold text-gray-400">Silakan Pasien menuju ruang periksa poliklinik</span>
            </div>
        </div>

    </main>

    <!-- ==================== RUNNING TEXT FOOTER ==================== -->
    <footer class="bg-slate-950 border-t border-slate-800 py-3.5 flex items-center overflow-hidden">
        <div class="bg-emerald-500 text-slate-900 px-6 py-1.5 font-black text-sm uppercase tracking-widest shrink-0 rounded-r-full z-10 shadow-lg">
            Informasi
        </div>
        <div class="ticker-wrap flex-1 flex items-center h-6">
            <div class="ticker-item text-sm font-bold text-gray-300">
                Selamat Datang di SIRS Medika • Utamakan Keselamatan dan Kenyamanan Pasien • Mohon mengantre dengan tertib di ruang tunggu yang telah disediakan • Cuci tangan Anda menggunakan Hand Sanitizer sebelum memasuki area klinik • Semoga lekas sembuh.
            </div>
        </div>
    </footer>

    <!-- ==================== AUDIO & POLING JS ENGINE ==================== -->
    <script>
        $(document).ready(function() {
            var audioCtx = null;
            var lastCalledNumbers = {}; 

            function initAudio() {
                if (!audioCtx) {
                    audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                }
            }

            // ==========================================
            // BEL DING-DONG SYNTHESIZER (WEB AUDIO API)
            // ==========================================
            function playBelChime() {
                initAudio();
                if (!audioCtx) return;

                var now = audioCtx.currentTime;
                
                // Nada Ding
                var osc1 = audioCtx.createOscillator();
                var gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(523.25, now); // C5
                gain1.gain.setValueAtTime(0.2, now);
                gain1.gain.exponentialRampToValueAtTime(0.01, now + 0.6);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start(now);
                osc1.stop(now + 0.6);

                // Nada Dong (0.5 detik kemudian)
                setTimeout(function() {
                    var nowDong = audioCtx.currentTime;
                    var osc2 = audioCtx.createOscillator();
                    var gain2 = audioCtx.createGain();
                    osc2.type = 'sine';
                    osc2.frequency.setValueAtTime(392.00, nowDong); // G4
                    gain2.gain.setValueAtTime(0.2, nowDong);
                    gain2.gain.exponentialRampToValueAtTime(0.01, nowDong + 0.8);
                    osc2.connect(gain2);
                    gain2.connect(audioCtx.destination);
                    osc2.start(nowDong);
                    osc2.stop(nowDong + 0.8);
                }, 400);
            }

            // ==========================================
            // TEXT-TO-SPEECH BAHASA INDONESIA (DENGAN NAMA PASIEN)
            // ==========================================
            function panggilSuara(nomor, nama, poli) {
                if ('speechSynthesis' in window) {
                    // Penyelarasan jeda jeda dengan tanda koma agar pemanggilan terdengar sangat natural
                    var pesan = "Nomor antrean " + nomor + ", atas nama " + nama + ", silakan menuju " + poli;
                    var utterance = new SpeechSynthesisUtterance(pesan);
                    utterance.lang = 'id-ID'; 
                    utterance.rate = 0.85;     
                    utterance.pitch = 1.0;
                    
                    setTimeout(function() {
                        window.speechSynthesis.speak(utterance);
                    }, 1200);
                }
            }

            // ==========================================
            // JAM DIGITAL REAL-TIME
            // ==========================================
            function updateClock() {
                var d = new Date();
                var hours = String(d.getHours()).padStart(2, '0');
                var minutes = String(d.getMinutes()).padStart(2, '0');
                var seconds = String(d.getSeconds()).padStart(2, '0');
                $('#liveClock').text(hours + ":" + minutes + ":" + seconds);

                var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                var fullDate = days[d.getDay()] + ", " + d.getDate() + " " + months[d.getMonth()] + " " + d.getFullYear();
                $('#liveDate').text(fullDate);
            }
            setInterval(updateClock, 1000);

            // ==========================================
            // AUTO POLL DATA ANTREAN DARI SERVER
            // ==========================================
            function syncMonitorData() {
                $.ajax({
                    url: '<?= base_url("live_board/get_active_queues_ajax") ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        var gridHtml = '';
                        var playSoundQueue = false;
                        var playSoundPoli = '';
                        var playSoundNo = '';
                        var playSoundNama = '';

                        // Render Kartu Poliklinik di Grid Kiri
                        if (response.layanan.length > 0) {
                            $.each(response.layanan, function(k, val) {
                                var serving = val.sedang_diperiksa;
                                var isServing = serving !== '-';
                                var activeClass = isServing 
                                    ? 'border-emerald-500 bg-slate-900/60 shadow-emerald-500/10' 
                                    : 'border-slate-800 bg-slate-900/20';

                                gridHtml += `
                                    <div class="border-2 rounded-3xl p-6 flex flex-col justify-between shadow-lg transition-all ${activeClass}">
                                        <div class="flex justify-between items-start gap-2">
                                            <div class="truncate flex-1">
                                                <h3 class="text-xl font-extrabold text-white leading-tight truncate">${val.nama_layanan}</h3>
                                                <p class="text-xs text-slate-400 font-semibold mt-1 truncate">${val.dokter_bertugas}</p>
                                            </div>
                                            <span class="text-[9px] bg-slate-950 text-emerald-400 font-bold px-2 py-0.5 rounded border border-slate-800 uppercase shrink-0">Sisa: ${val.sisa_antrean}</span>
                                        </div>

                                        <div class="flex justify-between items-end mt-4 pt-4 border-t border-slate-800/60">
                                            <div class="truncate flex-1">
                                                <span class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider mb-1 block">Sedang Diperiksa:</span>
                                                ${isServing ? `<span class="text-sm text-emerald-400 font-extrabold block truncate max-w-[180px]">${val.nama_pasien}</span>` : `<span class="text-xs text-slate-600 font-bold block">-</span>`}
                                            </div>
                                            <div class="font-mono-clock font-black text-5xl ${isServing ? 'text-emerald-400' : 'text-slate-600'} shrink-0">
                                                ${serving}
                                            </div>
                                        </div>
                                    </div>
                                `;

                                // DETEKSI PERUBAHAN: Bandingkan jika ada pemanggilan antrean baru
                                if (isServing) {
                                    if (lastCalledNumbers[val.id_layanan] !== undefined && lastCalledNumbers[val.id_layanan] !== serving) {
                                        playSoundQueue = true;
                                        playSoundPoli = val.nama_layanan;
                                        playSoundNo = serving;
                                        playSoundNama = val.nama_pasien; // Ambil nama pasien untuk dipanggil suara
                                    }
                                    lastCalledNumbers[val.id_layanan] = serving;
                                } else {
                                    lastCalledNumbers[val.id_layanan] = '-';
                                }
                            });
                        } else {
                            gridHtml = `<div class="col-span-full text-center py-20 text-slate-500">Belum ada data unit poliklinik aktif hari ini.</div>`;
                        }

                        $('#displayPoliContainer').html(gridHtml);

                        // Render Panggilan Utama di Box Kanan
                        if (response.panggilan_baru) {
                            $('#highlightPoli').text(response.panggilan_baru.nama_layanan.toUpperCase());
                            $('#highlightDokter').text(response.panggilan_baru.nama_dokter);
                            $('#highlightNo').text(response.panggilan_baru.no_antrean);
                            $('#highlightNamaPasien').text(response.panggilan_baru.nama_pasien);
                        } else {
                            $('#highlightPoli').text('POLIKLINIK UTAMA');
                            $('#highlightDokter').text('Menunggu Pemeriksaan');
                            $('#highlightNo').text('-');
                            $('#highlightNamaPasien').text('-');
                        }

                        // Eksekusi Panggilan Suara & Bel jika terdeteksi panggilan pasien baru
                        if (playSoundQueue) {
                            playBelChime();
                            panggilSuara(playSoundNo, playSoundNama, playSoundPoli);
                        }
                    },
                    error: function() {
                        console.log('Gagal menyinkronkan papan monitor.');
                    }
                });
            }

            // Jalankan polling pertama & set interval 5 detik
            syncMonitorData();
            setInterval(syncMonitorData, 5000);

            // Aktifkan Audio Context saat monitor pertama kali di klik (aturan browser modern)
            $('body').one('click', function() {
                initAudio();
                playBelChime();
            });
        });
    </script>
</body>
</html>