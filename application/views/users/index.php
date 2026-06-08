<?php 
    // Ambil permission dari session
    $permissions = $this->session->userdata('permissions') ?? [];
    $can_create  = in_array('create_users', $permissions);
    $can_edit    = in_array('edit_users', $permissions);
    $can_delete  = in_array('delete_users', $permissions);
?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Akses User</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola akun, password, dan role pengguna sistem.</p>
    </div>
    
    <?php if($can_create): ?>
    <a href="<?= base_url('users/create') ?>" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm flex items-center gap-2 shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
        Registrasi User Baru
    </a>
    <?php endif; ?>
</div>

<!-- ============================================== -->
<!-- FORM PENCARIAN & FILTER                        -->
<!-- ============================================== -->
<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6">
    <form action="<?= base_url('users') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="w-full md:w-1/3">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Cari Username</label>
            <input type="text" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Ketik username..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
        </div>
        
        <div class="w-full md:w-1/4">
            <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wider">Filter Role</label>
            <select name="role_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary text-sm">
                <option value="">-- Semua Role --</option>
                <?php foreach($roles as $r): ?>
                    <option value="<?= $r->id ?>" <?= ($role_id ?? '') == $r->id ? 'selected' : '' ?>>
                        <?= ucfirst($r->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                Terapkan
            </button>
            <?php if(!empty($keyword) || !empty($role_id)): ?>
                <a href="<?= base_url('users') ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors border border-gray-300">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- ============================================== -->
<!-- TABEL DATA USER                                -->
<!-- ============================================== -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-sm border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                    <th class="py-3 px-4 font-semibold">Username</th>
                    <th class="py-3 px-4 font-semibold">Peran (Role)</th>
                    <th class="py-3 px-4 font-semibold">Tgl Terdaftar</th>
                    
                    <?php if($can_edit || $can_delete): ?>
                    <th class="py-3 px-4 font-semibold text-center w-32">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?php if(!empty($users)): ?>
                    <?php $no = $start + 1; foreach($users as $u): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-center text-gray-500"><?= $no++ ?></td>
                        <td class="py-3 px-4 font-bold text-gray-800">
                            <?= htmlspecialchars($u->username) ?>
                            <?php if($u->id_user == $this->session->userdata('id_user')): ?>
                                <span class="ml-2 text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full border border-green-200">Anda (Aktif)</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-4">
                            <?php 
                                $bgClass = 'bg-gray-100 text-gray-700';
                                if(strtolower($u->role_name) == 'admin') $bgClass = 'bg-red-100 text-red-700';
                                elseif(strtolower($u->role_name) == 'dokter') $bgClass = 'bg-blue-100 text-blue-700';
                                elseif(strtolower($u->role_name) == 'pasien') $bgClass = 'bg-teal-100 text-teal-700';
                            ?>
                            <span class="<?= $bgClass ?> px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider">
                                <?= htmlspecialchars($u->role_name) ?>
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-500">
                            <?= date('d M Y, H:i', strtotime($u->created_at)) ?>
                        </td>
                        
                        <?php if($can_edit || $can_delete): ?>
                        <td class="py-3 px-4 text-center">
                            <div class="flex justify-center items-center gap-1.5">
                                <?php if($can_edit): ?>
                                    <a href="<?= base_url('users/edit/'.$u->id_user) ?>" class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 hover:bg-amber-100 px-2.5 py-1.5 rounded-lg border border-amber-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Edit User">
                                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                        <span>Edit</span>
                                    </a>
                                <?php endif; ?>

                                <?php if($can_delete): ?>
                                    <?php if($u->id_user != $this->session->userdata('id_user')): ?>
                                    <a href="<?= base_url('users/delete/'.$u->id_user) ?>" class="btn-delete inline-flex items-center gap-1 bg-red-50 text-red-700 hover:bg-red-100 px-2.5 py-1.5 rounded-lg border border-red-200/60 transition-all duration-200 text-xs font-bold hover:shadow-sm hover:-translate-y-0.5" title="Hapus User">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </a>
                                    <?php else: ?>
                                    <button disabled class="inline-flex items-center gap-1 bg-gray-50 text-gray-400 px-2.5 py-1.5 rounded-lg border border-gray-200 transition-all duration-200 text-xs font-bold cursor-not-allowed" title="Anda tidak bisa menghapus diri sendiri">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <?php $colspan = ($can_edit || $can_delete) ? 5 : 4; ?>
                        <td colspan="<?= $colspan ?>" class="text-center py-8 text-gray-500">
                            Tidak ada user yang cocok dengan pencarian Anda.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================== -->
<!-- PAGINATION & INFO                              -->
<!-- ============================================== -->
<div class="flex flex-col md:flex-row justify-between items-center gap-4">
    <div class="text-sm text-gray-500">
        Menampilkan <span class="font-bold text-gray-800"><?= !empty($users) ? $start + 1 : 0 ?></span> 
        sampai <span class="font-bold text-gray-800"><?= $start + count($users) ?></span> 
        dari total <span class="font-bold text-gray-800"><?= $total_rows ?></span> user
    </div>
    
    <div>
        <?= $pagination ?>
    </div>
</div>