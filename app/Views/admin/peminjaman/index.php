<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/peminjaman/new') ?>" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        <i class="fas fa-plus mr-2"></i>Tambah Peminjaman
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

<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Kembali</th>
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
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <?php if ($row['status'] == 'dipinjam'): ?>
                        <a href="#" onclick="confirmReturn(<?= $row['id'] ?>)" class="text-green-600 hover:text-green-900 mr-3">
                            <i class="fas fa-check-circle"></i> Kembali
                        </a>
                        <?php endif; ?>
                        
                        <a href="#" onclick="confirmDelete(<?= $row['id'] ?>)" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                        
                        <form id="return-form-<?= $row['id'] ?>" action="<?= base_url('admin/peminjaman/return/' . $row['id']) ?>" method="post" class="hidden">
                            <?= csrf_field() ?>
                        </form>
                        
                        <form id="delete-form-<?= $row['id'] ?>" action="<?= base_url('admin/peminjaman/delete/' . $row['id']) ?>" method="post" class="hidden">
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
function confirmReturn(id) {
    if (confirm('Apakah Anda yakin ingin mengonfirmasi pengembalian buku ini?')) {
        document.getElementById('return-form-' + id).submit();
    }
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data peminjaman ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
<?= $this->endSection() ?>
