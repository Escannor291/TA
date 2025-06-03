<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold page-title-decoration fade-in"><?= $title ?></h1>
</div>

<!-- Welcome Card -->
<div class="bg-yellow-100 border-l-4 border-yellow-500 p-4 mb-6 fade-in">
    <div class="flex">
        <div class="ml-3">
            <p class="text-sm text-yellow-700">
                <strong>Selamat datang, <?= session()->get('name') ?>!</strong><br>
                Role: <?= ucfirst(session()->get('role')) ?><br>
                Total Peminjaman: <?= isset($totalPeminjaman) ? $totalPeminjaman : 0 ?><br>
                Sedang Dipinjam: <?= isset($sedangDipinjam) ? $sedangDipinjam : 0 ?>
            </p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow-md fade-in">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-book text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Total Peminjaman</h3>
                <p class="text-2xl font-semibold"><?= isset($totalPeminjaman) ? $totalPeminjaman : 0 ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md fade-in">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <i class="fas fa-clock text-yellow-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Sedang Dipinjam</h3>
                <p class="text-2xl font-semibold"><?= isset($sedangDipinjam) ? $sedangDipinjam : 0 ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md fade-in">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <i class="fas fa-check text-green-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-gray-500 text-sm">Sudah Dikembalikan</h3>
                <p class="text-2xl font-semibold"><?= isset($totalPeminjaman) && isset($sedangDipinjam) ? ($totalPeminjaman - $sedangDipinjam) : 0 ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Riwayat Peminjaman -->
<div class="bg-white shadow-md rounded-lg p-6 fade-in">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Riwayat Peminjaman Terbaru</h2>
        <a href="<?= base_url('user/peminjaman') ?>" class="text-blue-600 hover:text-blue-800">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>

    <?php if (!empty($peminjaman) && isset($peminjaman)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Kembali</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach (array_slice($peminjaman, 0, 5) as $index => $item): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $index + 1 ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= isset($item['judul']) ? $item['judul'] : 'Judul tidak tersedia' ?></div>
                                <div class="text-sm text-gray-500"><?= isset($item['penulis']) ? $item['penulis'] : 'Penulis tidak tersedia' ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d/m/Y', strtotime($item['tanggal_pinjam'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d/m/Y', strtotime($item['tanggal_kembali'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if (strtolower($item['status']) == 'dipinjam'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Dipinjam
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Dikembalikan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-8">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-book-open"></i>
            </div>
            <p class="text-gray-500 mb-4">Belum ada riwayat peminjaman</p>
            <a href="<?= base_url('user/katalog') ?>" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">
                Mulai Meminjam Buku
            </a>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
