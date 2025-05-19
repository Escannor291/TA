<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/peminjaman') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="<?= base_url('admin/peminjaman/create') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Peminjam</label>
                <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Pilih Peminjam</option>
                    <?php foreach ($anggota as $member): ?>
                        <option value="<?= $member['id'] ?>" <?= old('user_id') == $member['id'] ? 'selected' : '' ?>>
                            <?= $member['name'] ?> (<?= $member['username'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (session('validation') && session('validation')->hasError('user_id')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('user_id'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="buku_id" class="block text-sm font-medium text-gray-700 mb-1">Buku</label>
                <select name="buku_id" id="buku_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Pilih Buku</option>
                    <?php foreach ($buku as $book): ?>
                        <option value="<?= $book['id'] ?>" <?= old('buku_id') == $book['id'] ? 'selected' : '' ?>>
                            <?= $book['judul'] ?> (Stok: <?= $book['jumlah'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (session('validation') && session('validation')->hasError('buku_id')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('buku_id'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Peminjaman</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= old('tanggal_pinjam') ?: date('Y-m-d') ?>" required>
                <?php if (session('validation') && session('validation')->hasError('tanggal_pinjam')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('tanggal_pinjam'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 mb-1">Batas Pengembalian</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= old('tanggal_kembali') ?: date('Y-m-d', strtotime('+7 days')) ?>" required>
                <?php if (session('validation') && session('validation')->hasError('tanggal_kembali')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('tanggal_kembali'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>Tambah Peminjaman
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set nilai minimum tanggal peminjaman ke hari ini
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_pinjam').setAttribute('min', today);
    
    // Update tanggal pengembalian saat tanggal peminjaman berubah
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        var pinjamDate = new Date(this.value);
        pinjamDate.setDate(pinjamDate.getDate() + 7);
        
        var kembaliDate = pinjamDate.toISOString().split('T')[0];
        document.getElementById('tanggal_kembali').value = kembaliDate;
        document.getElementById('tanggal_kembali').setAttribute('min', this.value);
    });
});
</script>
<?= $this->endSection() ?>
