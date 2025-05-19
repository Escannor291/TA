<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <div class="flex space-x-2">
        <a href="<?= base_url('admin/pengembalian') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
        <button onclick="printReport()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <i class="fas fa-print mr-2"></i>Cetak
        </button>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg p-6" id="reportContent">
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold">Laporan Pengembalian Buku</h2>
        <h3 class="text-lg">Perpustakaan Fachri</h3>
        <p>Periode: <?= date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)) ?></p>
    </div>
    
    <div class="mb-4">
        <form method="get" action="<?= base_url('admin/pengembalian/report') ?>" class="flex space-x-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>" <?= $bulan == $i ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--): ?>
                        <option value="<?= $i ?>" <?= $tahun == $i ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <table class="min-w-full divide-y divide-gray-200 mt-4">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Pinjam</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Kembali</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl Dikembalikan</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($pengembalian)): ?>
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data pengembalian pada periode ini</td>
            </tr>
            <?php else: ?>
            <?php $no = 1; foreach ($pengembalian as $row): ?>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $no++ ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $row['nama_peminjam'] ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $row['judul_buku'] ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('d/m/Y', strtotime($row['tanggal_dikembalikan'])) ?></td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    Rp <?= number_format($row['denda'] ?? 0, 0, ',', '.') ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="bg-gray-50">
                <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">Total Denda:</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                    Rp <?= number_format($total_denda, 0, ',', '.') ?>
                </td>
            </tr>
        </tfoot>
    </table>
    
    <div class="mt-8 text-right hidden print:block">
        <p>Tanggal Cetak: <?= date('d M Y') ?></p>
        <div class="mt-12">
            <p>(________________________)</p>
            <p class="mt-2">Petugas Perpustakaan</p>
        </div>
    </div>
</div>

<script>
function printReport() {
    const printContents = document.getElementById('reportContent').innerHTML;
    const originalContents = document.body.innerHTML;
    
    document.body.innerHTML = `
    <html>
        <head>
            <title>Laporan Pengembalian - Perpustakaan Fachri</title>
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .print-header { text-align: center; margin-bottom: 20px; }
                @media print {
                    button { display: none !important; }
                    .print-only { display: block !important; }
                }
            </style>
        </head>
        <body>
            ${printContents}
        </body>
    </html>`;
    
    window.print();
    document.body.innerHTML = originalContents;
}
</script>
<?= $this->endSection() ?>
