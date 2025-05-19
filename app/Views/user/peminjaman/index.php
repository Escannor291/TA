<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
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
    <input type="text" id="searchInput" placeholder="Cari peminjaman..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="peminjamanTable">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Kembali</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($peminjaman)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data peminjaman</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($peminjaman as $row): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <?php if (!empty($row['gambar']) && file_exists('.' . $row['gambar'])): ?>
                                <img class="h-10 w-8 object-cover mr-3" src="<?= base_url($row['gambar']) ?>" alt="<?= $row['judul'] ?>">
                            <?php else: ?>
                                <div class="h-10 w-8 bg-gray-200 flex items-center justify-center mr-3">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                            <div>
                                <div class="text-sm font-medium text-gray-900"><?= $row['judul'] ?></div>
                                <div class="text-sm text-gray-500"><?= $row['penulis'] ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($row['status'] == 'dipinjam'): ?>
                            <?php 
                            $today = new DateTime();
                            $batas_kembali = new DateTime($row['tanggal_kembali']);
                            $is_late = $today > $batas_kembali;
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $is_late ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                <?= $is_late ? 'Terlambat' : 'Dipinjam' ?>
                            </span>
                        <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Dikembalikan
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?= base_url('user/peminjaman/detail/' . $row['id']) ?>" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('peminjamanTable');
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
