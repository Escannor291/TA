<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/pengembalian') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700">Informasi Peminjam</h2>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Nama Peminjam</p>
                <p class="font-medium"><?= $peminjaman['nama_peminjam'] ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Username</p>
                <p class="font-medium"><?= $peminjaman['username'] ?></p>
            </div>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700">Informasi Buku</h2>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Judul Buku</p>
                <p class="font-medium"><?= $peminjaman['judul_buku'] ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Penulis</p>
                <p class="font-medium"><?= $peminjaman['penulis'] ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm text-gray-600">ISBN</p>
                <p class="font-medium"><?= $peminjaman['isbn'] ?></p>
            </div>
        </div>
    </div>
    
    <hr class="my-6">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700">Detail Peminjaman</h2>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Tanggal Peminjaman</p>
                <p class="font-medium"><?= date('d M Y', strtotime($peminjaman['tanggal_pinjam'])) ?></p>
            </div>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Batas Pengembalian</p>
                <p class="font-medium"><?= date('d M Y', strtotime($peminjaman['tanggal_kembali'])) ?></p>
            </div>
            <?php if ($peminjaman['status'] == 'dikembalikan'): ?>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Tanggal Pengembalian</p>
                <p class="font-medium"><?= date('d M Y', strtotime($peminjaman['tanggal_dikembalikan'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <div>
            <h2 class="text-lg font-semibold mb-4 text-indigo-700">Status Pengembalian</h2>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Status</p>
                <p class="font-medium">
                    <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Sedang Dipinjam</span>
                    <?php else: ?>
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Sudah Dikembalikan</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="mb-3">
                <p class="text-sm text-gray-600">Keterlambatan</p>
                <p class="font-medium">
                    <span class="<?= $terlambat > 0 ? 'text-red-600' : 'text-green-600' ?>">
                        <?= $status_keterlambatan ?>
                    </span>
                </p>
            </div>
            
            <?php if ($peminjaman['status'] == 'dikembalikan' && isset($peminjaman['denda']) && $peminjaman['denda'] > 0): ?>
            <div class="mb-3">
                <p class="text-sm text-gray-600">Denda</p>
                <p class="font-medium text-red-600">Rp <?= number_format($peminjaman['denda'], 0, ',', '.') ?></p>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($peminjaman['status'] == 'dipinjam'): ?>
    <div class="mt-8 flex justify-end">
        <a href="#" onclick="confirmReturn(<?= $peminjaman['id'] ?>)" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
            <i class="fas fa-check-circle mr-2"></i>Proses Pengembalian
        </a>
        
        <form id="return-form-<?= $peminjaman['id'] ?>" action="<?= base_url('admin/pengembalian/process/' . $peminjaman['id']) ?>" method="post" class="hidden">
            <?= csrf_field() ?>
        </form>
    </div>
    <?php endif; ?>
</div>

<script>
function confirmReturn(id) {
    if (confirm('Apakah Anda yakin ingin memproses pengembalian buku ini?')) {
        document.getElementById('return-form-' + id).submit();
    }
}
</script>
<?= $this->endSection() ?>
