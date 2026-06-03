<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="<?= base_url('roles') ?>" class="text-gray-500 hover:text-primary transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Hak Akses (Permissions)</h1>
    </div>

    <!-- Info Box -->
    <div class="mb-6 bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider mb-1">Menyunting Hak Akses Untuk Role:</p>
            <p class="text-2xl font-bold text-primary uppercase"><?= htmlspecialchars($role->name) ?></p>
        </div>
        <div class="hidden sm:block text-right">
            <button type="button" id="btn-check-all" class="text-sm font-semibold text-blue-600 hover:text-blue-800 underline block mb-1">Centang Semua</button>
            <button type="button" id="btn-uncheck-all" class="text-sm font-semibold text-red-600 hover:text-red-800 underline block">Hapus Semua Centang</button>
        </div>
    </div>

    <form action="<?= base_url('roles/permissions/'.$role->id) ?>" method="POST">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden p-6 sm:p-8">
            
            <p class="text-sm text-gray-600 mb-6 border-b border-gray-100 pb-4">
                Pilih modul dan fitur apa saja yang dapat diakses oleh *user* dengan peran <strong><?= ucfirst($role->name) ?></strong>.
            </p>

            <!-- Grid Checkboxes (Responsive) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <?php 
                // Mengelompokkan permission berdasarkan kata terakhirnya (misal: create_pasien -> pasien)
                $grouped_permissions = [];
                foreach($all_permissions as $p) {
                    $parts = explode('_', $p->name);
                    $module = end($parts); // ambil kata terakhir (pasien, dokter, resep, dll)
                    $grouped_permissions[$module][] = $p;
                }
                ?>

                <?php foreach($grouped_permissions as $module_name => $permissions): ?>
                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50/50 hover:bg-gray-50 transition-colors">
                        <h3 class="font-bold text-gray-800 uppercase tracking-wide text-xs mb-3 pb-2 border-b border-gray-200 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                            Modul <?= $module_name ?>
                        </h3>
                        
                        <div class="space-y-2">
                            <?php foreach($permissions as $perm): ?>
                                <?php 
                                    // Cek apakah permission ini sudah dimiliki oleh role tersebut
                                    $is_checked = in_array($perm->id, $role_permissions) ? 'checked' : ''; 
                                ?>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="permissions[]" value="<?= $perm->id ?>" <?= $is_checked ?> 
                                           class="perm-checkbox w-4 h-4 text-primary bg-gray-100 border-gray-300 rounded focus:ring-primary focus:ring-2">
                                    <span class="text-sm text-gray-700 group-hover:text-primary transition-colors">
                                        <?= htmlspecialchars($perm->name) ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

            <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                <a href="<?= base_url('roles') ?>" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white font-medium rounded-lg hover:bg-primary-hover transition-colors shadow-sm">
                    Simpan Hak Akses
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Script untuk Centang Semua -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btn-check-all').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.perm-checkbox');
            checkboxes.forEach(cb => cb.checked = true);
        });

        document.getElementById('btn-uncheck-all').addEventListener('click', function() {
            let checkboxes = document.querySelectorAll('.perm-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
        });
    });
</script>