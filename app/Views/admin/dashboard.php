<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 gap-5 mt-6 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Card Total Buku -->
    <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
        <div class="flex items-start justify-between">
            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                <i class="fas fa-book text-2xl text-white"></i>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="text-gray-500">Total Buku</span>
                <span class="text-lg font-semibold"><?= $total_buku ?></span>
            </div>
        </div>
    </div>

    <!-- Card Total Peminjaman -->
    <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
        <div class="flex items-start justify-between">
            <div class="p-3 rounded-full bg-green-600 bg-opacity-75">
                <i class="fas fa-exchange-alt text-2xl text-white"></i>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="text-gray-500">Peminjaman Aktif</span>
                <span class="text-lg font-semibold"><?= $total_peminjaman ?></span>
            </div>
        </div>
    </div>

    <!-- Card Total Anggota -->
    <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
        <div class="flex items-start justify-between">
            <div class="p-3 rounded-full bg-blue-600 bg-opacity-75">
                <i class="fas fa-users text-2xl text-white"></i>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="text-gray-500">Total Anggota</span>
                <span class="text-lg font-semibold"><?= $total_anggota ?></span>
            </div>
        </div>
    </div>

    <!-- Card Lainnya -->
    <div class="p-4 transition-shadow bg-white rounded-lg shadow-sm hover:shadow-lg">
        <div class="flex items-start justify-between">
            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75">
                <i class="fas fa-calendar-check text-2xl text-white"></i>
            </div>
            <div class="flex flex-col space-y-2">
                <span class="text-gray-500">Tanggal Hari Ini</span>
                <span class="text-lg font-semibold"><?= date('d M Y') ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Peminjaman Terbaru -->
<div class="mt-8">
    <h2 class="text-lg font-medium text-gray-800">Peminjaman Terbaru</h2>
    
    <div class="mt-4 overflow-hidden bg-white rounded-md shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Peminjam
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Buku
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Tanggal Pinjam
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Batas Kembali
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($peminjaman_terbaru)) : ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Tidak ada data peminjaman
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($peminjaman_terbaru as $peminjaman) : ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= $peminjaman['nama_peminjam'] ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= $peminjaman['judul_buku'] ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= date('d M Y', strtotime($peminjaman['tanggal_pinjam'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= date('d M Y', strtotime($peminjaman['tanggal_kembali'])) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($peminjaman['status'] == 'dipinjam') : ?>
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                                        Dipinjam
                                    </span>
                                <?php else : ?>
                                    <span class="inline-flex px-2 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                                        Dikembalikan
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>