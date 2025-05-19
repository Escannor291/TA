<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
</div>

<div class="mb-4">
    <input type="text" id="searchInput" placeholder="Cari buku..." class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php if (empty($buku)): ?>
        <div class="col-span-full text-center py-8 bg-white rounded-lg shadow">
            <p class="text-gray-500">Tidak ada buku tersedia saat ini</p>
        </div>
    <?php else: ?>
        <?php foreach ($buku as $book): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300" data-title="<?= strtolower($book['judul']) ?>" data-author="<?= strtolower($book['penulis']) ?>">
                <div class="h-48 overflow-hidden">
                    <?php if (!empty($book['gambar']) && file_exists('.' . $book['gambar'])): ?>
                        <img class="w-full h-full object-cover" src="<?= base_url($book['gambar']) ?>" alt="<?= $book['judul'] ?>">
                    <?php else: ?>
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-book text-gray-400 text-4xl"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 truncate"><?= $book['judul'] ?></h3>
                    <p class="mt-1 text-sm text-gray-500"><?= $book['penulis'] ?></p>
                    <p class="mt-1 text-sm text-gray-500"><?= $book['penerbit'] ?> (<?= $book['tahun_terbit'] ?>)</p>
                    <div class="mt-2 flex justify-between items-center">
                        <span class="text-sm font-medium text-green-600">Stok: <?= $book['jumlah'] ?></span>
                        <a href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const books = document.querySelectorAll('[data-title]');
    
    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase();
        
        books.forEach(function(book) {
            const title = book.getAttribute('data-title');
            const author = book.getAttribute('data-author');
            
            if (title.includes(query) || author.includes(query)) {
                book.style.display = '';
            } else {
                book.style.display = 'none';
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
