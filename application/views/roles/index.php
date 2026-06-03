<!-- ... (Bagian header, form search, dan div peringatan sama persis seperti sebelumnya) ... -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Role</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola jenis peran yang tersedia di dalam sistem.</p>
    </div>
    <a href="<?= base_url('roles/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Tambah Role Baru
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-16 text-center">ID</th>
                    <th class="py-3 px-4 font-semibold">Nama Role (Alias)</th>
                    <th class="py-3 px-4 font-semibold text-center w-64">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($roles)): ?>
                    <?php foreach($roles as $r): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center font-mono text-gray-500 font-bold"><?= $r->id ?></td>
                        <td class="py-3 px-4">
                            <span class="bg-gray-100 border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-sm font-bold uppercase tracking-wider">
                                <?= htmlspecialchars($r->name) ?>
                            </span>
                        </td>
                        
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center gap-2">
                                
                                <!-- Tombol Kelola Akses (Tidak untuk Admin ID 1) -->
                                <?php if($r->id == 1): ?>
                                    <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-md text-xs font-bold border border-green-200" title="Akses Penuh">Superadmin</span>
                                <?php else: ?>
                                    <a href="<?= base_url('roles/permissions/'.$r->id) ?>" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Kelola Akses
                                    </a>
                                <?php endif; ?>

                                <!-- Tombol Edit & Hapus (Sistem Role 1,2,3 dilindungi) -->
                                <?php if(in_array($r->id, [1, 2, 3])): ?>
                                    <span class="bg-gray-100 text-gray-400 px-3 py-1.5 rounded-md text-xs font-semibold cursor-not-allowed border border-gray-200" title="Role Sistem dilindungi">Sistem</span>
                                <?php else: ?>
                                    <a href="<?= base_url('roles/edit/'.$r->id) ?>" class="bg-amber-100 text-amber-700 hover:bg-amber-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Edit</a>
                                    <a href="<?= base_url('roles/delete/'.$r->id) ?>" class="btn-delete bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-md transition-colors text-xs font-semibold">Hapus</a>
                                <?php endif; ?>
                                
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center py-8 text-gray-500">Tidak ada data role ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- ... (Pagination div sama seperti sebelumnya) ... -->