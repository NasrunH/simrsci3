<div class="max-w-5xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('penerimaan') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Pencatatan Stok Masuk Supplier</h1>
    </div>

    <form action="<?= base_url('penerimaan/create') ?>" method="POST" id="formPenerimaan">
        
        <!-- HEADER PENGIRIMAN -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Informasi Faktur Pengiriman
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. Surat Jalan / Faktur <span class="text-red-500">*</span></label>
                    <input type="text" name="no_faktur" placeholder="Contoh: FK-2026-0001" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Supplier <span class="text-red-500">*</span></label>
                    <select name="id_supplier" id="supplierSelector" class="w-full px-4 py-2 border border-gray-300 rounded-lg select2" required>
                        <option value="" disabled selected>-- Pilih Perusahaan Supplier --</option>
                        <?php foreach($suppliers as $s): ?>
                            <option value="<?= $s->id_supplier ?>"><?= htmlspecialchars($s->nama_supplier) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Terima <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_penerimaan" value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
            </div>
        </div>

        <!-- DAFTAR ITEM OBAT YANG MASUK -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-6">
            <div class="flex justify-between items-end mb-4 border-b border-gray-100 pb-2">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Daftar Obat Masuk Faktur
                </h2>
                <button type="button" id="btnTambahRow" class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1.5 rounded-md text-sm font-semibold transition-colors flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Obat
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm" id="tablePenerimaan">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                            <th class="py-2 px-2 font-semibold min-w-[320px]">Pilih Obat</th>
                            <th class="py-2 px-2 font-semibold w-24">Stok Saat Ini</th>
                            <th class="py-2 px-2 font-semibold w-36">Harga Beli Satuan (Rp)</th>
                            <th class="py-2 px-2 font-semibold w-24">Jumlah (Qty)</th>
                            <th class="py-2 px-2 font-semibold w-36 text-right">Subtotal (Rp)</th>
                            <th class="py-2 px-2 font-semibold w-12 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPenerimaan">
                        <!-- Baris pertama otomatis via JS -->
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-gray-200">
                            <td colspan="4" class="py-4 px-2 text-right font-bold text-gray-800 text-lg">TOTAL FAKTUR:</td>
                            <td class="py-4 px-2 text-right font-black text-primary text-xl" id="displayTotal">Rp 0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Tulis rincian kondisi logistik obat atau kesepakatan tempo pembayaran..."></textarea>
            </div>
        </div>

        <!-- FITUR BARU: OPSI UPDATE SUPPLIER UTAMA SECARA LIVE -->
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 sm:p-5 mb-6 flex items-start gap-3">
            <input type="checkbox" name="update_main_supplier" id="chkUpdateSupplier" value="yes" class="w-5 h-5 text-primary rounded border-gray-300 focus:ring-primary mt-0.5">
            <div>
                <label for="chkUpdateSupplier" class="font-bold text-blue-900 text-sm cursor-pointer block">Update Supplier Utama Obat Secara Otomatis</label>
                <p class="text-xs text-blue-700 mt-1">Jika dicentang, seluruh obat dalam daftar faktur di atas akan otomatis diatur memiliki supplier ini sebagai Supplier Utama mereka di master obat.</p>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="<?= base_url('penerimaan') ?>" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors text-sm">Kembali</a>
            <button type="submit" class="px-8 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary-hover transition-colors shadow-md text-sm">Simpan Faktur Masuk</button>
        </div>
    </form>
</div>

<!-- SELECT2 STYLE OVERRIDE -->
<style>
    .select2-container { width: 100% !important; display: block !important; }
    .select2-container--default .select2-selection--single {
        border-color: #D1D5DB !important; border-radius: 0.5rem !important; height: 42px !important; display: flex !important; align-items: center !important; background-color: #FFF !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered { color: #1F2937 !important; font-size: 0.875rem !important; padding-left: 1rem !important; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; right: 8px !important; }
</style>

<script>
    const listObat = <?= json_encode($obat) ?>;

    $(document).ready(function() {
        $('.select2').select2({ width: '100%' });

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        function cleanNumber(str) {
            return str.replace(/[^0-9]/g, '');
        }

        // Fungsi baris obat dinamis
        function insertBarisObat() {
            let options = '<option value="" disabled selected>-- Ketik Nama / Kode Obat --</option>';
            listObat.forEach(function(o) {
                // Simpan atribut id_supplier pada masing-masing opsi
                options += `<option value="${o.id_obat}" data-harga="${o.harga}" data-stok="${o.stok}" data-supplier="${o.id_supplier || ''}">${o.kode_obat} - ${o.nama_obat}</option>`;
            });

            let tr = `
                <tr class="border-b border-gray-100 baris-penerimaan">
                    <td class="py-3 px-2">
                        <select name="id_obat[]" class="w-full select-obat select2-dynamic" required>
                            ${options}
                        </select>
                        <!-- Badge pembantu visual kecocokan supplier -->
                        <div class="badge-supplier-container mt-1"></div>
                    </td>
                    <td class="py-3 px-2">
                        <input type="text" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-lg text-center py-2 input-stok-asal" readonly tabindex="-1" value="0">
                    </td>
                    <td class="py-3 px-2">
                        <input type="text" name="harga_beli[]" value="0" class="w-full text-sm border border-gray-300 rounded-lg text-right py-2 input-harga-beli-mask font-mono font-semibold" required>
                    </td>
                    <td class="py-3 px-2">
                        <input type="number" step="any" name="jumlah[]" min="0.01" value="1" class="w-full text-sm border border-gray-300 rounded-lg text-center py-2 input-jumlah font-mono" required>
                    </td>
                    <td class="py-3 px-2">
                        <input type="text" class="w-full text-sm bg-gray-50 border border-gray-300 rounded-lg text-right py-2 font-bold text-gray-700 input-subtotal font-mono" value="0" readonly tabindex="-1">
                    </td>
                    <td class="py-3 px-2 text-center">
                        <button type="button" class="text-red-500 hover:text-red-700 btn-hapus-row" tabindex="-1">
                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
            $('#tbodyPenerimaan').append(tr);
            $('.select2-dynamic').last().select2({ width: '100%' });
        }

        insertBarisObat();

        $('#btnTambahRow').click(function() {
            insertBarisObat();
        });

        // Event hapus baris
        $('#tbodyPenerimaan').on('click', '.btn-hapus-row', function() {
            if ($('.baris-penerimaan').length > 1) {
                $(this).closest('tr').find('.select2-dynamic').select2('destroy');
                $(this).closest('tr').remove();
                hitungTotalFaktur();
            } else {
                Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Transaksi harus memiliki minimal 1 item obat.' });
            }
        });

        // Deteksi ganti obat
        $('#tbodyPenerimaan').on('change', '.select-obat', function() {
            let option = $(this).find('option:selected');
            let stok = parseFloat(option.data('stok')) || 0;
            let hargaJual = parseFloat(option.data('harga')) || 0;
            let defaultHargaBeli = Math.round(hargaJual * 0.8);

            let tr = $(this).closest('tr');
            tr.find('.input-stok-asal').val(stok);
            tr.find('.input-harga-beli-mask').val(formatRupiah(defaultHargaBeli));
            
            hitungSubtotal(tr);
            cekKecocokanSupplier(tr);
        });

        // Fungsi mencocokkan supplier transaksi dengan supplier obat
        function cekKecocokanSupplier(tr) {
            let optionObat = tr.find('.select-obat option:selected');
            let idSupplierObat = optionObat.data('supplier') ? parseInt(optionObat.data('supplier')) : null;
            let idSupplierTransaksi = $('#supplierSelector').val() ? parseInt($('#supplierSelector').val()) : null;
            let badgeContainer = tr.find('.badge-supplier-container');

            badgeContainer.empty();

            if (!idSupplierTransaksi) {
                return; // Supplier transaksi di header belum dipilih
            }

            if (!idSupplierObat) {
                // Obat belum memiliki supplier utama sama sekali
                badgeContainer.html(`<span class="inline-block bg-amber-50 text-amber-700 border border-amber-200 text-[10px] px-2 py-0.5 rounded font-bold">⚠️ Belum Ada Supplier Utama</span>`);
            } else if (idSupplierObat === idSupplierTransaksi) {
                // Cocok
                badgeContainer.html(`<span class="inline-block bg-green-50 text-green-700 border border-green-200 text-[10px] px-2 py-0.5 rounded font-bold">✓ Cocok dengan Supplier Utama</span>`);
            } else {
                // Berbeda
                badgeContainer.html(`<span class="inline-block bg-red-50 text-red-700 border border-red-200 text-[10px] px-2 py-0.5 rounded font-bold">⚠️ Berbeda dari Supplier Utama</span>`);
            }
        }

        // Jika supplier di header diubah, evaluasi ulang semua baris obat
        $('#supplierSelector').on('change', function() {
            $('.baris-penerimaan').each(function() {
                cekKecocokanSupplier($(this));
            });
        });

        // Event Masking Live Typing Rupiah
        $('#tbodyPenerimaan').on('input', '.input-harga-beli-mask', function() {
            let numericValue = cleanNumber($(this).val());
            $(this).val(formatRupiah(numericValue));
            
            let tr = $(this).closest('tr');
            hitungSubtotal(tr);
        });

        // Deteksi perubahan Qty
        $('#tbodyPenerimaan').on('input', '.input-jumlah', function() {
            let tr = $(this).closest('tr');
            hitungSubtotal(tr);
        });

        function hitungSubtotal(tr) {
            let rawHarga = cleanNumber(tr.find('.input-harga-beli-mask').val());
            let hargaBeli = parseFloat(rawHarga) || 0;
            let qty = parseFloat(tr.find('.input-jumlah').val()) || 0;
            let subtotal = hargaBeli * qty;

            tr.find('.input-subtotal').val(formatRupiah(subtotal));
            tr.find('.input-subtotal').data('nilai', subtotal);
            hitungTotalFaktur();
        }

        function hitungTotalFaktur() {
            let total = 0;
            $('.input-subtotal').each(function() {
                let sub = parseFloat($(this).data('nilai')) || 0;
                total += sub;
            });
            $('#displayTotal').text('Rp ' + formatRupiah(total));
        }
    });
</script>