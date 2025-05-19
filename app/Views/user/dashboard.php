<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<h1 class="text-2xl font-semibold mb-6">Selamat Datang, <?= session()->get('name') ?></h1>

<!-- Cards section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <!-- Total Buku -->
    <a href="<?= base_url('user/katalog') ?>" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center cursor-pointer">
        <div class="rounded-full bg-indigo-100 p-3 mr-4">
            <i class="fas fa-book text-indigo-600 text-2xl"></i>
        </div>
        <div>
            <h2 class="text-gray-500">Total Buku</h2>
            <p class="text-3xl font-bold"><?= $total_buku ?></p>
        </div>
    </a>
    
    <!-- Total Peminjaman -->
    <a href="<?= base_url('user/peminjaman') ?>" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center cursor-pointer">
        <div class="rounded-full bg-blue-100 p-3 mr-4">
            <i class="fas fa-book-reader text-blue-600 text-2xl"></i>
        </div>
        <div>
            <h2 class="text-gray-500">Total Peminjaman</h2>
            <p class="text-3xl font-bold"><?= $total_pinjaman ?></p>
        </div>
    </a>
    
    <!-- Peminjaman Aktif -->
    <a href="<?= base_url('user/peminjaman') ?>" class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 flex items-center cursor-pointer">
        <div class="rounded-full bg-green-100 p-3 mr-4">
            <i class="fas fa-exchange-alt text-green-600 text-2xl"></i>
        </div>
        <div>
            <h2 class="text-gray-500">Peminjaman Aktif</h2>
            <p class="text-3xl font-bold"><?= $pinjaman_aktif ?></p>
        </div>
    </a>
</div>

<!-- Riwayat Peminjaman Section -->
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold">Riwayat Peminjaman Terakhir</h2>
        <a href="<?= base_url('user/peminjaman') ?>" class="text-indigo-600 hover:text-indigo-900 text-sm flex items-center">
            Lihat Semua
            <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <?php if (empty($riwayat_peminjaman)): ?>
        <p class="text-gray-500 text-center py-4">Belum ada riwayat peminjaman</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Buku</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tenggat Kembali</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($riwayat_peminjaman as $peminjaman): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if (!empty($peminjaman['gambar']) && file_exists('.' . $peminjaman['gambar'])): ?>
                                        <img class="h-10 w-8 object-cover mr-3" src="<?= base_url($peminjaman['gambar']) ?>" alt="<?= $peminjaman['judul'] ?>">
                                    <?php else: ?>
                                        <div class="h-10 w-8 bg-gray-200 flex items-center justify-center mr-3">
                                            <i class="fas fa-book text-gray-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900"><?= $peminjaman['judul'] ?></div>
                                        <div class="text-sm text-gray-500"><?= $peminjaman['penulis'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($peminjaman['tanggal_pinjam'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($peminjaman['tanggal_kembali'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                                    <?php 
                                    $today = new DateTime();
                                    $batas_kembali = new DateTime($peminjaman['tanggal_kembali']);
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600">
                                <a href="<?= base_url('user/peminjaman/detail/' . $peminjaman['id']) ?>" class="hover:text-indigo-900">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Katalog Buku Populer Section, dapat ditambahkan nanti -->

<?= $this->endSection() ?>
