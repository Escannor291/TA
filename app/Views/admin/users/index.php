<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/users/new') ?>" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i>Tambah Anggota
    </a>
</div>

<?php if (session()->getFlashdata('message')): ?>
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="mb-4">
    <input type="text" id="searchInput" placeholder="Cari anggota..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="usersTable">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Registrasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data anggota</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($users as $user): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $user['name'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $user['username'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?= $user['role'] == 'anggota' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' ?>">
                            <?= ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= date('d M Y', strtotime($user['created_at'])) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="#" onclick="confirmDelete(<?= $user['id'] ?>)" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                        
                        <form id="delete-form-<?= $user['id'] ?>" action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="post" class="hidden">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE" />
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus anggota ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('usersTable');
    const rows = table.getElementsByTagName('tr');
    
    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase();
        
        for (let i = 1; i < rows.length; i++) {
            const rowText = rows[i].textContent.toLowerCase();
            if (rowText.includes(query)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
