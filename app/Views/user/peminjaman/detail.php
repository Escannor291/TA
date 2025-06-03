<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('user/peminjaman') ?>" class="text-primary hover:text-primary-dark mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Peminjaman
    </a>
    <h1 class="text-2xl font-semibold page-title-decoration fade-in">Detail Peminjaman</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cover Buku -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-hidden decorated-card fade-in">
            <div class="h-96 overflow-hidden bg-gray-100">
                <?php 
                    $gambar_path = $peminjaman['gambar'] ?? '';
                    if (!empty($gambar_path) && file_exists(FCPATH . $gambar_path)) {
                        $gambar_url = base_url($gambar_path);
                    } else {
                        $gambar_url = base_url('assets/img/no-image.png');
                    }
                ?>
                <img src="<?= $gambar_url ?>" alt="<?= $peminjaman['judul'] ?>" 
                     class="w-full h-full object-cover">
            </div>
            
            <div class="p-4">
                <h2 class="text-lg font-semibold mb-2"><?= $peminjaman['judul'] ?></h2>
                <p class="text-gray-600 mb-1">Penulis: <?= $peminjaman['penulis'] ?></p>
                <p class="text-gray-600 mb-4">Penerbit: <?= $peminjaman['penerbit'] ?></p>
                
                <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                    <?php if ($terlambat > 0): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Buku terlambat dikembalikan!
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                            <i class="fas fa-clock mr-2"></i>
                            Buku sedang Anda pinjam
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        Buku sudah dikembalikan
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Detail Peminjaman -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 decorated-card fade-in">
            <h2 class="text-xl font-semibold mb-6 page-title-decoration">Informasi Peminjaman</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam</label>
                    <p class="text-gray-900"><?= $peminjaman['nama_peminjam'] ?></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Peminjaman</label>
                    <p class="text-gray-900">#<?= str_pad($peminjaman['id'], 6, '0', STR_PAD_LEFT) ?></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pinjam</label>
                    <p class="text-gray-900"><?= date('d F Y', strtotime($peminjaman['tanggal_pinjam'])) ?></p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tenggat Kembali</label>
                    <p class="text-gray-900 <?= $terlambat > 0 ? 'text-red-600 font-medium' : '' ?>">
                        <?= date('d F Y', strtotime($peminjaman['tanggal_kembali'])) ?>
                    </p>
                </div>
                
                <?php if ($peminjaman['status'] == 'dikembalikan' && !empty($peminjaman['tanggal_dikembalikan'])): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Dikembalikan</label>
                    <p class="text-gray-900"><?= date('d F Y', strtotime($peminjaman['tanggal_dikembalikan'])) ?></p>
                </div>
                <?php endif; ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                        <?php if ($terlambat > 0): ?>
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Terlambat
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                <i class="fas fa-clock mr-1"></i> Sedang Dipinjam
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            <i class="fas fa-check mr-1"></i> Dikembalikan
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($terlambat > 0 || $denda > 0): ?>
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4 page-title-decoration text-red-600">Informasi Keterlambatan</h3>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-red-700 mb-1">Status Keterlambatan</label>
                            <p class="text-red-900 font-medium"><?= $status_keterlambatan ?></p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-red-700 mb-1">Denda</label>
                            <p class="text-red-900 font-medium">
                                Rp <?= number_format($denda, 0, ',', '.') ?>
                                <span class="text-sm font-normal">(Rp 1.000 per hari)</span>
                            </p>
                        </div>
                    </div>
                    
                    <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                    <div class="mt-4 p-3 bg-yellow-100 border border-yellow-300 rounded">
                        <p class="text-yellow-800 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            Segera kembalikan buku ke perpustakaan untuk menghindari denda tambahan.
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="border-t pt-6 mt-6">
                <h3 class="text-lg font-semibold mb-4 page-title-decoration">Informasi Buku</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <p class="text-gray-900"><?= $peminjaman['judul'] ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                        <p class="text-gray-900"><?= $peminjaman['penulis'] ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                        <p class="text-gray-900"><?= $peminjaman['penerbit'] ?></p>
                    </div>
                    
                    <?php if (!empty($peminjaman['isbn'])): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <p class="text-gray-900"><?= $peminjaman['isbn'] ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    if (typeof fadeInElements === 'function') {
        fadeInElements();
    }
});
</script>
<?= $this->endSection() ?>
