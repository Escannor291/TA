<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    
    <div class="flex space-x-2">
        <a href="<?= base_url('admin/pengembalian/report') ?>" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            <i class="fas fa-file-alt mr-2"></i>Laporan Pengembalian
        </a>
    </div>
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

<div class="mb-4 flex justify-between items-center">
    <div class="flex">
        <a href="<?= base_url('admin/pengembalian?status=dipinjam') ?>" class="px-3 py-2 mr-2 <?= $status == 'dipinjam' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded">
            Sedang Dipinjam
        </a>
        <a href="<?= base_url('admin/pengembalian?status=dikembalikan') ?>" class="px-3 py-2 mr-2 <?= $status == 'dikembalikan' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded">
            Sudah Dikembalikan
        </a>
        <a href="<?= base_url('admin/pengembalian?status=semua') ?>" class="px-3 py-2 <?= $status == 'semua' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' ?> rounded">
            Semua
        </a>
    </div>
    
    <div class="relative">
        <input type="text" id="searchInput" placeholder="Cari..." class="w-64 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
        <span class="absolute right-3 top-2 text-gray-400">
            <i class="fas fa-search"></i>
        </span>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table id="peminjamanTable" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Kembali</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($peminjaman)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data peminjaman</td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($peminjaman as $row): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row['nama_peminjam'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['judul_buku'] ?></td>
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
                                <?= !empty($row['denda']) && $row['denda'] > 0 ? '(Denda)' : '' ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="<?= base_url('admin/pengembalian/detail/' . $row['id']) ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        
                        <?php if ($row['status'] == 'dipinjam'): ?>
                        <a href="#" onclick="confirmReturn(<?= $row['id'] ?>)" class="text-green-600 hover:text-green-900">
                            <i class="fas fa-check-circle"></i> Proses
                        </a>
                        
                        <form id="return-form-<?= $row['id'] ?>" action="<?= base_url('admin/pengembalian/process/' . $row['id']) ?>" method="post" class="hidden">
                            <?= csrf_field() ?>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmReturn(id) {
    if (confirm('Apakah Anda yakin ingin memproses pengembalian buku ini?')) {
        document.getElementById('return-form-' + id).submit();
    }
}

// Fungsi pencarian
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('peminjamanTable');
    const rows = table.getElementsByTagName('tr');
    
    searchInput.addEventListener('keyup', function() {
        const search = searchInput.value.toLowerCase();
        
        for (let i = 1; i < rows.length; i++) {
            const rowData = rows[i].textContent.toLowerCase();
            if (rowData.includes(search)) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
