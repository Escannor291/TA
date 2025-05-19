<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/buku/new') ?>" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i>Tambah Buku Baru
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
    <input type="text" id="searchInput" placeholder="Cari buku..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="bukuTable">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerbit</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($buku)): ?>
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data buku</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($buku as $row): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php if (!empty($row['gambar']) && file_exists('.' . $row['gambar'])): ?>
                            <img src="<?= base_url($row['gambar']) ?>" alt="<?= $row['judul'] ?>" class="w-16 h-20 object-cover rounded">
                        <?php else: ?>
                            <div class="w-16 h-20 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-xl"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row['judul'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['penulis'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['penerbit'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['tahun_terbit'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['isbn'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['jumlah'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="<?= base_url('admin/buku/edit/' . $row['id']) ?>" class="px-2 py-1 bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <a href="#" onclick="confirmDelete(<?= $row['id'] ?>)" class="px-2 py-1 bg-red-50 text-red-600 rounded hover:bg-red-100">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </a>
                        </div>
                        
                        <form id="delete-form-<?= $row['id'] ?>" action="<?= base_url('admin/buku/delete/' . $row['id']) ?>" method="post" class="hidden">
                            <input type="hidden" name="_method" value="DELETE" />
                            <?= csrf_field() ?>
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
    if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('bukuTable');
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
