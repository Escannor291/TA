<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('user/katalog') ?>" class="text-primary hover:text-primary-dark mb-4 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Katalog
    </a>
    <h1 class="text-2xl font-semibold page-title-decoration fade-in"><?= $buku['judul'] ?></h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Cover Buku -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-hidden decorated-card fade-in">
            <div class="h-96 overflow-hidden bg-gray-100">
                <?php 
                    $gambar_path = $buku['gambar'] ?? '';
                    if (!empty($gambar_path) && file_exists(FCPATH . $gambar_path)) {
                        $gambar_url = base_url($gambar_path);
                    } else {
                        $gambar_url = base_url('assets/img/no-image.png');
                    }
                ?>
                <img src="<?= $gambar_url ?>" alt="<?= $buku['judul'] ?>" 
                     class="w-full h-full object-cover">
            </div>
            
            <div class="p-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Stok: <?= $buku['jumlah'] ?></span>
                    <?php if ($buku['jumlah'] > 0): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Tersedia</span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Habis</span>
                    <?php endif; ?>
                </div>
                
                <?php if ($peminjaman_aktif): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Anda sedang meminjam buku ini
                    </div>
                    <a href="<?= base_url('user/peminjaman') ?>" 
                       class="w-full bg-blue-500 text-white px-4 py-2 rounded text-center hover:bg-blue-600 transition-colors btn-ripple block">
                        <i class="fas fa-list mr-2"></i>Lihat Detail Peminjaman
                    </a>
                <?php elseif ($dapat_dipinjam): ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        Buku tersedia untuk dipinjam (masa peminjaman: 7 hari)
                    </div>
                    <button onclick="confirmPinjamDetail(<?= $buku['id'] ?>, '<?= addslashes($buku['judul']) ?>')" 
                            class="w-full bg-accent text-primary px-4 py-2 rounded hover:bg-accent-dark transition-colors btn-ripple mb-3">
                        <i class="fas fa-bookmark mr-2"></i>Pinjam Buku Ini
                    </button>
                    <a href="<?= base_url('user/katalog') ?>" 
                       class="w-full bg-gray-500 text-white px-4 py-2 rounded text-center hover:bg-gray-600 transition-colors block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
                    </a>
                <?php else: ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Buku sedang tidak tersedia
                    </div>
                    <a href="<?= base_url('user/katalog') ?>" 
                       class="w-full bg-gray-500 text-white px-4 py-2 rounded text-center hover:bg-gray-600 transition-colors block">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Detail Buku -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-6 decorated-card fade-in">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 page-title-decoration">Informasi Buku</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                        <p class="text-gray-900"><?= $buku['judul'] ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                        <p class="text-gray-900"><?= $buku['penulis'] ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                        <p class="text-gray-900"><?= $buku['penerbit'] ?></p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                        <p class="text-gray-900"><?= $buku['tahun_terbit'] ?></p>
                    </div>
                    
                    <?php if (!empty($buku['isbn'])): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <p class="text-gray-900"><?= $buku['isbn'] ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($buku['kategori'])): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <p class="text-gray-900"><?= $buku['kategori'] ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($buku['deskripsi'])): ?>
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3 page-title-decoration">Deskripsi</h3>
                <p class="text-gray-700 leading-relaxed"><?= nl2br($buku['deskripsi']) ?></p>
            </div>
            <?php endif; ?>
            
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold mb-3 page-title-decoration">Statistik</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="text-2xl font-bold text-primary counter" data-count="<?= $total_peminjaman ?>"><?= $total_peminjaman ?></div>
                        <div class="text-sm text-gray-600">Total Dipinjam</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded">
                        <div class="text-2xl font-bold text-primary counter" data-count="<?= $buku['jumlah'] ?>"><?= $buku['jumlah'] ?></div>
                        <div class="text-sm text-gray-600">Stok Tersedia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Peminjaman untuk Detail -->
<div id="pinjamDetailModal" class="dialog hidden">
    <div class="dialog-content">
        <div class="dialog-header">
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Peminjaman</h3>
            <button class="dialog-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="dialog-body">
            <p>Apakah Anda yakin ingin meminjam buku <strong id="bukuJudulDetail"></strong>?</p>
            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Masa peminjaman: 7 hari dari hari ini
                </p>
                <p class="text-sm text-blue-700 mt-1">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Tanggal kembali: <span id="tanggalKembaliDetail"></span>
                </p>
                <p class="text-sm text-red-600 mt-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Pastikan Anda dapat mengembalikan buku tepat waktu untuk menghindari denda
                </p>
            </div>
        </div>
        <div class="dialog-footer">
            <button class="dialog-close px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                Batal
            </button>
            <form id="pinjamDetailForm" method="post" style="display: inline;">
                <?= csrf_field() ?>
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark btn-ripple">
                    <i class="fas fa-bookmark mr-2"></i>Pinjam Sekarang
                </button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Function untuk konfirmasi peminjaman dari detail
function confirmPinjamDetail(bukuId, judulBuku) {
    const modal = document.getElementById('pinjamDetailModal');
    const bukuJudulElement = document.getElementById('bukuJudulDetail');
    const tanggalKembaliElement = document.getElementById('tanggalKembaliDetail');
    const form = document.getElementById('pinjamDetailForm');
    
    // Set data buku
    bukuJudulElement.textContent = judulBuku;
    
    // Hitung tanggal kembali (7 hari dari sekarang)
    const tanggalKembali = new Date();
    tanggalKembali.setDate(tanggalKembali.getDate() + 7);
    tanggalKembaliElement.textContent = tanggalKembali.toLocaleDateString('id-ID');
    
    // Set action form
    form.action = `<?= base_url('user/katalog/pinjam/') ?>${bukuId}`;
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('dialog-open');
}

// Close modal handlers
document.querySelectorAll('.dialog-close').forEach(button => {
    button.addEventListener('click', function() {
        const modal = document.getElementById('pinjamDetailModal');
        modal.classList.remove('dialog-open');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    });
});

// Close modal on outside click
document.getElementById('pinjamDetailModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.remove('dialog-open');
        setTimeout(() => {
            this.classList.add('hidden');
        }, 300);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    if (typeof fadeInElements === 'function') {
        fadeInElements();
    }
    
    if (typeof animateCounters === 'function') {
        animateCounters();
    }
});
</script>
<?= $this->endSection() ?>
