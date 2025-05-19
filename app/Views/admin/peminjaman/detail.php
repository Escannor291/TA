<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <div class="flex space-x-2">
        <a href="<?= base_url('admin/peminjaman') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <?php if ($peminjaman['status'] == 'dipinjam'): ?>
        <button onclick="confirmReturn(<?= $peminjaman['id'] ?>, <?= $denda ?>)" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            <i class="fas fa-check-circle mr-2"></i>Proses Pengembalian
            <?php if ($denda > 0): ?>
            <span class="text-xs">(Denda: Rp <?= number_format($denda, 0, ',', '.') ?>)</span>
            <?php endif; ?>
        </button>
        <?php endif; ?>
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

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">Informasi Peminjam</h2>
            <table class="min-w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-40">Nama</td>
                    <td class="py-2 font-medium"><?= $peminjaman['nama_peminjam'] ?></td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Username</td>
                    <td class="py-2"><?= $peminjaman['username'] ?></td>
                </tr>
            </table>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">Informasi Buku</h2>
            <table class="min-w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-40">Judul</td>
                    <td class="py-2 font-medium"><?= $peminjaman['judul_buku'] ?></td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Penulis</td>
                    <td class="py-2"><?= $peminjaman['penulis'] ?></td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Penerbit</td>
                    <td class="py-2"><?= $peminjaman['penerbit'] ?></td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Tahun Terbit</td>
                    <td class="py-2"><?= $peminjaman['tahun_terbit'] ?></td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">ISBN</td>
                    <td class="py-2"><?= $peminjaman['isbn'] ?></td>
                </tr>
            </table>
        </div>
    </div>
    
    <hr class="my-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">Detail Peminjaman</h2>
            <table class="min-w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-40">Tanggal Pinjam</td>
                    <td class="py-2 font-medium"><?= date('d M Y', strtotime($peminjaman['tanggal_pinjam'])) ?></td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Batas Pengembalian</td>
                    <td class="py-2"><?= date('d M Y', strtotime($peminjaman['tanggal_kembali'])) ?></td>
                </tr>
                <?php if ($peminjaman['status'] == 'dikembalikan' && isset($peminjaman['tanggal_dikembalikan'])): ?>
                <tr>
                    <td class="py-2 text-gray-600">Tanggal Pengembalian</td>
                    <td class="py-2"><?= date('d M Y', strtotime($peminjaman['tanggal_dikembalikan'])) ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">Status Peminjaman</h2>
            <table class="min-w-full">
                <tr>
                    <td class="py-2 text-gray-600 w-40">Status</td>
                    <td class="py-2">
                        <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">Sedang Dipinjam</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Sudah Dikembalikan</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-600">Keterlambatan</td>
                    <td class="py-2 <?= $terlambat > 0 ? 'text-red-600 font-medium' : 'text-green-600' ?>">
                        <?= $status_keterlambatan ?>
                    </td>
                </tr>
                <?php if ($terlambat > 0): ?>
                <tr>
                    <td class="py-2 text-gray-600">Denda</td>
                    <td class="py-2 text-red-600 font-medium">
                        Rp <?= number_format($denda, 0, ',', '.') ?>
                        <span class="text-sm text-gray-500 font-normal">(Rp 1.000 per hari)</span>
                    </td>
                </tr>
                <?php endif; ?>
                <?php if ($peminjaman['status'] == 'dikembalikan' && isset($peminjaman['denda']) && $peminjaman['denda'] > 0): ?>
                <tr>
                    <td class="py-2 text-gray-600">Denda Dibayar</td>
                    <td class="py-2 text-red-600 font-medium">
                        Rp <?= number_format($peminjaman['denda'], 0, ',', '.') ?>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="py-2 text-gray-600">Durasi Peminjaman</td>
                    <td class="py-2">
                        <?php
                        $tgl_pinjam = new \DateTime($peminjaman['tanggal_pinjam']);
                        $tgl_kembali = new \DateTime($peminjaman['tanggal_kembali']);
                        $durasi = $tgl_pinjam->diff($tgl_kembali)->days;
                        echo $durasi . ' hari';
                        ?>
                    </td>
                </tr>
            </table>
            
            <?php if ($peminjaman['status'] == 'dipinjam'): ?>
            <div class="mt-6">
                <form id="return-form-<?= $peminjaman['id'] ?>" action="<?= base_url('admin/peminjaman/return/' . $peminjaman['id']) ?>" method="post" class="hidden">
                    <?= csrf_field() ?>
                </form>
                <button onclick="confirmReturn(<?= $peminjaman['id'] ?>, <?= $denda ?>)" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 w-full">
                    <i class="fas fa-check-circle mr-2"></i>Proses Pengembalian
                    <?php if ($denda > 0): ?>
                    <span class="text-xs">(Denda: Rp <?= number_format($denda, 0, ',', '.') ?>)</span>
                    <?php endif; ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmReturn(id, denda) {
    let message = 'Apakah Anda yakin ingin mengonfirmasi pengembalian buku ini?';
    
    if (denda > 0) {
        message += '\n\nPerhatian: Ada denda keterlambatan sebesar Rp ' + denda.toLocaleString('id-ID') + ' yang harus dibayarkan.';
    }
    
    if (confirm(message)) {
        document.getElementById('return-form-' + id).submit();
    }
}
</script>
<?= $this->endSection() ?>
