<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('resep') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Form Peresepan Obat Baru</h1>
    </div>

    <form action="<?= base_url('resep/create') ?>" method="POST" id="formResep">
        
        <!-- HEADER RESEP (PASIEN & DOKTER) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Informasi Resep
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Dropdown Pasien (Untuk Semua) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pasien Penerima Resep <span class="text-red-500">*</span></label>
                    <select name="id_pasien" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <!-- PHP 8.1 Fix: Kosongkan 'selected' default jika ada parameter $selected_pasien dari URL -->
                        <option value="" disabled <?= empty($selected_pasien) ? 'selected' : '' ?>>Ketik Nama atau No RM Pasien...</option>
                        <?php foreach($pasien as $p): ?>
                            <!-- Pre-select Pasien jika id cocok dengan selected_pasien dari URL -->
                            <option value="<?= $p->id_pasien ?>" <?= (isset($selected_pasien) && $selected_pasien == $p->id_pasien) ? 'selected' : '' ?>>
                                <?= $p->no_rekam_medis ?> - <?= htmlspecialchars($p->nama_lengkap) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dropdown Dokter (Khusus Admin) -->
                <?php if(strtolower($this->session->userdata('role')) == 'admin'): ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Pemeriksa <span class="text-red-500">*</span></label>
                    <select name="id_dokter" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <option value="" disabled <?= empty($selected_dokter) ? 'selected' : '' ?>>Ketik Nama Dokter...</option>
                        <?php if(!empty($dokters)): ?>
                            <?php foreach($dokters as $d): ?>
                                <!-- Pre-select Dokter jika id cocok dengan selected_dokter dari URL -->
                                <option value="<?= $d->id_dokter ?>" <?= (isset($selected_dokter) && $selected_dokter == $d->id_dokter) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($d->nama_dokter) ?> (<?= htmlspecialchars($d->spesialisasi) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Belum ada data dokter di sistem.</option>
                        <?php endif; ?>
                    </select>
                </div>
                <?php else: ?>
                    <!-- Jika Dokter yang login, tampilkan info saja (Readonly) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Dokter Pemeriksa</label>
                        <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-gray-600 font-medium text-sm">
                            <span class="text-green-600 font-bold">✓</span> Akun Dokter Anda (Terhubung Otomatis)
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- DETAIL OBAT (DINAMIS) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8 mb-6">
            <div class="flex justify-between items-end mb-4 border-b border-gray-100 pb-2">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Daftar Obat
                </h2>
                <button type="button" id="btnTambahObat" class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-md text-sm font-semibold transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Baris
                </button>
            </div>

            <!-- Area Table Dinamis -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="tableObat">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="py-2 px-2 font-semibold w-[300px] min-w-[300px]">Pilih Obat</th>
                            <th class="py-2 px-2 font-semibold w-24">Stok</th>
                            <th class="py-2 px-2 font-semibold w-32">Harga (Rp)</th>
                            <th class="py-2 px-2 font-semibold w-24">Qty</th>
                            <th class="py-2 px-2 font-semibold min-w-[150px]">Aturan Pakai</th>
                            <th class="py-2 px-2 font-semibold w-32 text-right">Subtotal</th>
                            <th class="py-2 px-2 font-semibold w-12 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyObat">
                        <!-- Baris obat pertama ditambahkan otomatis via JS -->
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="5" class="py-4 px-2 text-right font-bold text-gray-800 text-lg">TOTAL KESELURUHAN:</td>
                            <td class="py-4 px-2 text-right font-bold text-primary text-xl" id="displayTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('resep') ?>" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batalkan</a>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-md">Simpan & Proses Resep</button>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- STYLE FIX UNTUK SELECT2 DI DALAM GRID/TABLE TAILWIND -->
<!-- ============================================== -->
<style>
    /* Paksa container Select2 untuk mengambil lebar penuh kontainer */
    .select2-container {
        width: 100% !important;
        display: block !important;
    }
    
    /* Percantik tampilan kotak input Select2 agar serasi dengan Tailwind */
    .select2-container--default .select2-selection--single {
        border-color: #D1D5DB !important; /* border-gray-300 */
        border-radius: 0.5rem !important; /* rounded-lg */
        height: 42px !important;
        display: flex !important;
        align-items: center !important;
        background-color: #FFF !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1F2937 !important; /* text-gray-800 */
        font-size: 0.875rem !important; /* text-sm */
        padding-left: 1rem !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
</style>

<!-- DATA OBAT DARI PHP KE JAVASCRIPT -->
<script>
    const daftarObat = <?= json_encode($obat) ?>;
    
    $(document).ready(function() {
        
        // Aktifkan Select2
        $('.select2').select2({ width: '100%' });

        // Fungsi Menambahkan Baris Baru
        function tambahBaris() {
            let options = '<option value="" disabled selected>-- Pilih Obat --</option>';
            daftarObat.forEach(function(o) {
                let stokInfo = o.stok <= 0 ? ' (STOK HABIS)' : ' (Sisa: '+o.stok+')';
                let dis = o.stok <= 0 ? 'disabled' : '';
                options += `<option value="${o.id_obat}" data-harga="${o.harga}" data-stok="${o.stok}" ${dis}>${o.kode_obat} - ${o.nama_obat} ${stokInfo}</option>`;
            });

            let tr = `
                <tr class="border-b border-gray-100 baris-obat">
                    <td class="py-2 px-2 w-[300px]">
                        <select name="id_obat[]" class="w-full select-obat select2-dynamic" required>
                            ${options}
                        </select>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-lg text-center py-2 input-stok" readonly tabindex="-1">
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-lg text-right py-2 input-harga" readonly tabindex="-1">
                    </td>
                    <td class="py-2 px-2">
                        <input type="number" name="jumlah[]" min="1" value="1" class="w-full text-sm border border-gray-300 rounded-lg text-center py-2 input-qty" required>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" name="aturan_pakai[]" class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2" placeholder="misal: 3x1 Sesudah Makan" required>
                    </td>
                    <td class="py-2 px-2">
                        <input type="text" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-lg text-right py-2 font-bold text-gray-700 input-subtotal" value="0" readonly tabindex="-1">
                    </td>
                    <td class="py-2 px-2 text-center">
                        <button type="button" class="text-red-500 hover:text-red-700 btn-hapus" tabindex="-1">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
            $('#tbodyObat').append(tr);
            
            // Inisialisasi Select2 pada elemen yang baru ditambahkan
            $('.select2-dynamic').last().select2({ width: '100%' });
        }

        tambahBaris();

        $('#btnTambahObat').click(function() {
            tambahBaris();
        });

        $('#tbodyObat').on('click', '.btn-hapus', function() {
            if ($('.baris-obat').length > 1) {
                // Destroy Select2 before removing to prevent memory leaks
                $(this).closest('tr').find('.select2-dynamic').select2('destroy');
                $(this).closest('tr').remove();
                hitungTotal();
            } else {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Resep minimal harus memiliki 1 obat.' });
            }
        });

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        $('#tbodyObat').on('change', '.select-obat', function() {
            let option = $(this).find('option:selected');
            let harga = parseFloat(option.data('harga')) || 0;
            let stok = parseInt(option.data('stok')) || 0;
            
            let tr = $(this).closest('tr');
            tr.find('.input-harga').val(formatRupiah(harga));
            tr.find('.input-stok').val(stok);
            tr.find('.input-qty').val(1).attr('max', stok);
            
            hitungSubtotal(tr);
        });

        $('#tbodyObat').on('input', '.input-qty', function() {
            let tr = $(this).closest('tr');
            let max = parseInt($(this).attr('max'));
            let val = parseInt($(this).val());
            
            if (val > max) {
                Swal.fire({ icon: 'error', title: 'Stok Tidak Cukup', text: 'Stok obat ini hanya tersisa ' + max });
                $(this).val(max);
            }
            hitungSubtotal(tr);
        });

        function hitungSubtotal(tr) {
            let option = tr.find('.select-obat option:selected');
            let harga = parseFloat(option.data('harga')) || 0;
            let qty = parseInt(tr.find('.input-qty').val()) || 0;
            let subtotal = harga * qty;
            
            tr.find('.input-subtotal').val(formatRupiah(subtotal));
            tr.find('.input-subtotal').data('nilai', subtotal);
            hitungTotal();
        }

        function hitungTotal() {
            let total = 0;
            $('.input-subtotal').each(function() {
                let sub = parseFloat($(this).data('nilai')) || 0;
                total += sub;
            });
            $('#displayTotal').text('Rp ' + formatRupiah(total));
        }
    });
</script>