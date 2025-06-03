<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold page-title-decoration fade-in">Katalog Buku</h1>
    <p class="text-gray-600 mt-2 fade-in">Jelajahi koleksi buku perpustakaan kami</p>
</div>

<!-- Menampilkan pesan flash -->
<?php if (session()->getFlashdata('message')): ?>
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 fade-in">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <?= session()->getFlashdata('message') ?>
    </div>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 fade-in">
    <div class="flex items-center">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
</div>
<?php endif; ?>

<!-- Grid Buku -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6" id="booksGrid">
    <?php foreach ($buku as $index => $book): ?>
    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 decorated-card fade-in book-card" 
         style="animation-delay: <?= ($index * 0.1) ?>s;">
        
        <div class="h-48 overflow-hidden bg-gray-100">
            <?php 
                $gambar_path = $book['gambar'] ?? '';
                if (!empty($gambar_path) && file_exists(FCPATH . $gambar_path)) {
                    $gambar_url = base_url($gambar_path);
                } else {
                    $gambar_url = base_url('assets/img/no-image.png');
                }
            ?>
            <img src="<?= $gambar_url ?>" alt="<?= $book['judul'] ?>" class="w-full h-full object-cover">
        </div>
        
        <div class="p-4">
            <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2"><?= $book['judul'] ?></h3>
            <p class="text-gray-600 text-sm mb-1">Penulis: <?= $book['penulis'] ?></p>
            <p class="text-gray-600 text-sm mb-2">Penerbit: <?= $book['penerbit'] ?></p>
            
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm text-gray-500">Stok: <?= $book['jumlah'] ?></span>
                <?php if ($book['jumlah'] > 0): ?>
                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Tersedia</span>
                <?php else: ?>
                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Habis</span>
                <?php endif; ?>
            </div>
            
            <div class="flex gap-2">
                <a href="<?= base_url('user/katalog/detail/' . $book['id']) ?>" 
                   class="flex-1 bg-primary text-white px-3 py-2 rounded text-center text-sm hover:bg-primary-dark transition-colors btn-ripple">
                    <i class="fas fa-eye mr-1"></i> Detail
                </a>
                
                <?php if ($book['jumlah'] > 0): ?>
                <form method="POST" action="<?= base_url('user/katalog/pinjam/' . $book['id']) ?>" class="flex-1" 
                      onsubmit="return confirmPinjam(this, '<?= addslashes($book['judul']) ?>')">
                    <?= csrf_field() ?>
                    <button type="submit" 
                            class="w-full bg-accent text-primary px-3 py-2 rounded text-sm hover:bg-accent-dark transition-colors btn-ripple">
                        <i class="fas fa-bookmark mr-1"></i> Pinjam
                    </button>
                </form>
                <?php else: ?>
                <button disabled class="flex-1 bg-gray-300 text-gray-500 px-3 py-2 rounded text-sm cursor-not-allowed">
                    <i class="fas fa-times mr-1"></i> Habis
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($buku)): ?>
<div class="text-center py-12">
    <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Buku</h3>
    <p class="text-gray-500">Belum ada buku yang tersedia di katalog</p>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function confirmPinjam(form, judulBuku) {
    const confirmed = confirm(`Yakin ingin meminjam buku "${judulBuku}"?\n\nMasa peminjaman: 7 hari`);
    
    if (confirmed) {
        // Disable tombol submit untuk mencegah double click
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...';
        
        // Re-enable tombol setelah 5 detik jika gagal
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-bookmark mr-1"></i> Pinjam';
        }, 5000);
        
        return true;
    }
    
    return false;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    if (typeof fadeInElements === 'function') {
        fadeInElements();
    }
    
    console.log('Katalog loaded');
    console.log('User ID:', '<?= session()->get('user_id') ?>');
    console.log('Total books:', <?= count($buku) ?>);
});
</script>
<?= $this->endSection() ?>
