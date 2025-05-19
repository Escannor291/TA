<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('user/peminjaman') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">Informasi Buku</h2>
            <div class="flex items-start">
                <?php if (!empty($peminjaman['gambar']) && file_exists('.' . $peminjaman['gambar'])): ?>
                    <img class="h-40 w-32 object-cover mr-4 rounded shadow" src="<?= base_url($peminjaman['gambar']) ?>" alt="<?= $peminjaman['judul'] ?>">
                <?php else: ?>
                    <div class="h-40 w-32 bg-gray-200 flex items-center justify-center mr-4 rounded shadow">
                        <i class="fas fa-book text-gray-400 text-4xl"></i>
                    </div>
                <?php endif; ?>
                
                <div>
                    <h3 class="text-xl font-semibold"><?= $peminjaman['judul'] ?></h3>
                    <p class="text-gray-600 mt-1">Penulis: <?= $peminjaman['penulis'] ?></p>
                    <p class="text-gray-600 mt-1">Penerbit: <?= $peminjaman['penerbit'] ?></p>
                    <p class="text-gray-600 mt-1">Tahun Terbit: <?= $peminjaman['tahun_terbit'] ?></p>
                    <p class="text-gray-600 mt-1">ISBN: <?= $peminjaman['isbn'] ?></p>
                </div>
            </div>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-4 text-primary border-b pb-2">Detail Peminjaman</h2>
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
                <tr>
                    <td class="py-2 text-gray-600">Status</td>
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
            </table>
            
            <?php if ($peminjaman['status'] == 'dipinjam'): ?>
            <div class="mt-6 p-4 bg-blue-50 text-blue-700 rounded-lg">
                <p><i class="fas fa-info-circle mr-2"></i> Silahkan datang ke perpustakaan untuk mengembalikan buku ini sebelum tanggal jatuh tempo.</p>
                <?php if ($terlambat > 0): ?>
                <p class="mt-2 text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i> Buku ini sudah terlambat dikembalikan. Denda yang harus dibayar: Rp <?= number_format($denda, 0, ',', '.') ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
