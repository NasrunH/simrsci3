<?php
    // Pemrograman Defensif: Seleraskan pemanggilan variabel b dan billing
    $b = $b ?? $billing ?? null;
    if (!$b) {
        show_error("Data transaksi billing tidak ditemukan atau gagal dimuat.", 500, "Kesalahan Sistem");
    }
?>

<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('billing') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Proses Pembayaran Invoice</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- SISI KIRI (RINCIAN TAGIHAN) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Identitas Pasien -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-start border-b border-gray-100 pb-3 mb-4">
                    <div>
                        <span class="text-xs text-gray-500 font-bold uppercase tracking-wider">No. Invoice</span>
                        <h2 class="text-lg font-mono font-bold text-gray-800"><?= htmlspecialchars($b->no_invoice ?? '') ?></h2>
                    </div>
                    <span class="bg-amber-100 text-amber-700 border border-amber-200 px-3 py-1 rounded-full text-xs font-bold">Belum Lunas</span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
                    <div>
                        <span class="text-gray-400 block text-xs uppercase font-semibold">Nama Pasien</span>
                        <span class="font-bold text-gray-800 text-base"><?= htmlspecialchars($b->nama_lengkap ?? '') ?></span>
                        <span class="text-xs text-primary font-mono block">RM: <?= htmlspecialchars($b->no_rekam_medis ?? '') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs uppercase font-semibold">Dokter Pemeriksa</span>
                        <span class="font-bold text-gray-800"><?= htmlspecialchars($b->nama_dokter ?? '-') ?></span>
                        <span class="text-xs text-gray-500 block"><?= htmlspecialchars($b->nama_layanan ?? 'Poliklinik') ?></span>
                    </div>
                </div>
            </div>

            <!-- Detail Biaya Itemizer -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Rincian Item & Jasa Medis
                </h3>

                <table class="w-full text-left text-sm border-collapse mb-6">
                    <thead>
                        <tr class="text-gray-400 text-xs font-bold uppercase border-b border-gray-200">
                            <th class="py-2">Item/Jasa</th>
                            <th class="py-2 text-center w-16">Qty</th>
                            <th class="py-2 text-right w-32">Harga (Rp)</th>
                            <th class="py-2 text-right w-32">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 1. Jasa Medis Dokter -->
                        <tr class="border-b border-gray-100">
                            <td class="py-3">
                                <span class="font-bold block text-gray-800">Jasa Konsultasi & Periksa <?= htmlspecialchars($b->nama_layanan ?? 'Poliklinik') ?></span>
                                <span class="text-xs text-gray-400">Tarif standar pelayanan medis</span>
                            </td>
                            <td class="py-3 text-center">1</td>
                            <td class="py-3 text-right"><?= number_format($b->biaya_jasa_dokter ?? 0, 0, ',', '.') ?></td>
                            <td class="py-3 text-right font-medium"><?= number_format($b->biaya_jasa_dokter ?? 0, 0, ',', '.') ?></td>
                        </tr>
                        <!-- 2. Detail Obat -->
                        <?php if(!empty($detail_resep)): ?>
                            <?php foreach($detail_resep as $d): ?>
                            <tr class="border-b border-gray-100">
                                <td class="py-3">
                                    <span class="font-bold block text-gray-800"><?= htmlspecialchars($d->nama_obat ?? '') ?></span>
                                    <span class="text-xs text-gray-400">Aturan pakai: <?= htmlspecialchars($d->aturan_pakai ?? '') ?></span>
                                </td>
                                <td class="py-3 text-center"><?= $d->jumlah ?></td>
                                <td class="py-3 text-right"><?= number_format($d->harga_satuan, 0, ',', '.') ?></td>
                                <td class="py-3 text-right font-medium"><?= number_format($d->subtotal, 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

        <!-- SISI KANAN (FORM PEMBAYARAN KASIR) -->
        <div class="lg:col-span-1">
            <div class="bg-gray-900 text-white rounded-xl shadow-lg p-6 sticky top-6">
                
                <h3 class="text-xs font-bold text-accent uppercase tracking-wider mb-2">Total Tagihan</h3>
                <div class="text-4xl font-black mb-6" id="total_tagihan_display">
                    Rp <?= number_format($b->total_tagihan ?? 0, 0, ',', '.') ?>
                </div>

                <form action="<?= base_url('billing/pay/'.($b->id_billing ?? '')) ?>" method="POST" id="formBayar">
                    
                    <!-- Simpan Total Tagihan di Atribut Data -->
                    <input type="hidden" id="nilai_tagihan" value="<?= $b->total_tagihan ?? 0 ?>">

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="metode" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary focus:border-primary text-white" required>
                            <option value="Tunai" selected>Cash / Tunai</option>
                            <option value="Debit">Kartu Debit</option>
                            <option value="QRIS">QRIS / E-Wallet</option>
                            <option value="Transfer">Bank Transfer</option>
                        </select>
                    </div>

                    <!-- Input Nominal Uang Diterima -->
                    <div class="mb-5" id="group-uang-diterima">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Uang Diterima (Rp)</label>
                        <input type="number" name="uang_diterima" id="uang_diterima" min="<?= $b->total_tagihan ?? 0 ?>" value="<?= $b->total_tagihan ?? 0 ?>" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-xl font-bold focus:ring-2 focus:ring-primary focus:border-primary text-white text-right" required>
                    </div>

                    <!-- Tampilan Kembalian -->
                    <div class="mb-6 p-4 bg-gray-800/50 rounded-lg border border-gray-800 flex justify-between items-center" id="group-kembalian">
                        <div>
                            <span class="text-xs text-gray-400 block uppercase font-bold tracking-wide">Uang Kembalian</span>
                            <span class="text-xl font-black text-green-400" id="kembalian_display">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-primary hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-lg shadow-primary/30 flex justify-center items-center gap-2 text-md">
                        Selesaikan Pembayaran
                    </button>
                    
                    <a href="<?= base_url('billing') ?>" class="block text-center text-xs text-gray-400 hover:text-white mt-4 underline">Kembali ke Daftar</a>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        const totalTagihan = parseFloat($('#nilai_tagihan').val()) || 0;

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Hitung Kembalian Secara Real-time
        $('#uang_diterima').on('input', function() {
            let uangDiterima = parseFloat($(this).val()) || 0;
            let kembalian = uangDiterima - totalTagihan;

            if (kembalian < 0) {
                $('#kembalian_display').text('Sisa: Rp ' + formatRupiah(Math.abs(kembalian))).addClass('text-red-400').removeClass('text-green-400');
            } else {
                $('#kembalian_display').text('Rp ' + formatRupiah(kembalian)).addClass('text-green-400').removeClass('text-red-400');
            }
        });

        // Toggle Tampilan Form Berdasarkan Metode
        $('#metode').on('change', function() {
            if ($(this).val() !== 'Tunai') {
                $('#group-uang-diterima').hide();
                $('#group-kembalian').hide();
                $('#uang_diterima').val(totalTagihan).prop('required', false);
            } else {
                $('#group-uang-diterima').fadeIn();
                $('#group-kembalian').fadeIn();
                $('#uang_diterima').val(totalTagihan).prop('required', true).trigger('input');
            }
        });
    });
</script>