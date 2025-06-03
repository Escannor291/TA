<?= $this->extend('layout/user_layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-semibold page-title-decoration fade-in">Peminjaman Saya</h1>
    <p class="text-gray-600 mt-2 fade-in">Kelola dan pantau status peminjaman buku Anda</p>
</div>

<!-- Statistik Peminjaman -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow-md decorated-card fade-in">
        <div class="flex items-center">
            <div class="rounded-full bg-blue-100 p-3 mr-3">
                <i class="fas fa-books text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Peminjaman</p>
                <h3 class="text-xl font-bold text-gray-800 counter" data-count="<?= $total_peminjaman ?>"><?= $total_peminjaman ?></h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow-md decorated-card fade-in" style="animation-delay: 0.1s;">
        <div class="flex items-center">
            <div class="rounded-full bg-yellow-100 p-3 mr-3">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Sedang Dipinjam</p>
                <h3 class="text-xl font-bold text-gray-800 counter" data-count="<?= $peminjaman_aktif ?>"><?= $peminjaman_aktif ?></h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow-md decorated-card fade-in" style="animation-delay: 0.2s;">
        <div class="flex items-center">
            <div class="rounded-full bg-green-100 p-3 mr-3">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Sudah Kembali</p>
                <h3 class="text-xl font-bold text-gray-800 counter" data-count="<?= $peminjaman_selesai ?>"><?= $peminjaman_selesai ?></h3>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-4 rounded-lg shadow-md decorated-card fade-in" style="animation-delay: 0.3s;">
        <div class="flex items-center">
            <div class="rounded-full bg-red-100 p-3 mr-3">
                <i class="fas fa-money-bill text-red-600 text-xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Denda</p>
                <h3 class="text-xl font-bold text-gray-800">Rp <?= number_format($denda_total, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Filter dan Pencarian -->
<div class="bg-white p-4 rounded-lg shadow-md mb-6 fade-in">
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" id="searchPeminjaman" placeholder="Cari judul buku atau penulis..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div class="md:w-48">
            <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                <option value="">Semua Status</option>
                <option value="dipinjam">Sedang Dipinjam</option>
                <option value="dikembalikan">Sudah Dikembalikan</option>
            </select>
        </div>
    </div>
</div>

<!-- Daftar Peminjaman -->
<div class="bg-white rounded-lg shadow-md overflow-hidden decorated-card fade-in">
    <?php if (empty($peminjaman)): ?>
        <div class="text-center py-12">
            <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Peminjaman</h3>
            <p class="text-gray-500 mb-4">Anda belum meminjam buku apapun. Jelajahi katalog untuk meminjam buku.</p>
            <a href="<?= base_url('user/katalog') ?>" class="inline-block px-6 py-3 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors btn-ripple">
                <i class="fas fa-search mr-2"></i>Jelajahi Katalog
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full" id="peminjamanTable">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort" data-sort="buku">
                            Buku <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort" data-sort="tanggal_pinjam">
                            Tanggal Pinjam <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort" data-sort="tanggal_kembali">
                            Tenggat Kembali <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sort" data-sort="status">
                            Status <i class="fas fa-sort ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($peminjaman as $index => $item): ?>
                        <?php
                            // Hitung status keterlambatan
                            $today = new DateTime();
                            $tanggal_kembali = new DateTime($item['tanggal_kembali']);
                            $is_overdue = ($item['status'] == 'dipinjam' && $today > $tanggal_kembali);
                            $days_overdue = $is_overdue ? $today->diff($tanggal_kembali)->days : 0;
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors fade-in peminjaman-row" 
                            style="animation-delay: <?= ($index * 0.05) ?>s;"
                            data-title="<?= strtolower($item['judul']) ?>"
                            data-author="<?= strtolower($item['penulis']) ?>"
                            data-status="<?= $item['status'] ?>">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-8">
                                        <?php 
                                            $gambar_path = $item['gambar'] ?? '';
                                            if (!empty($gambar_path) && file_exists(FCPATH . $gambar_path)) {
                                                $gambar_url = base_url($gambar_path);
                                            } else {
                                                $gambar_url = base_url('assets/img/no-image.png');
                                            }
                                        ?>
                                        <img class="h-12 w-8 object-cover rounded" src="<?= $gambar_url ?>" alt="<?= $item['judul'] ?>">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"><?= $item['judul'] ?></div>
                                        <div class="text-sm text-gray-500"><?= $item['penulis'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= date('d/m/Y', strtotime($item['tanggal_pinjam'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="<?= $is_overdue ? 'text-red-600 font-medium' : 'text-gray-900' ?>">
                                    <?= date('d/m/Y', strtotime($item['tanggal_kembali'])) ?>
                                </span>
                                <?php if ($is_overdue): ?>
                                    <div class="text-xs text-red-500">Terlambat <?= $days_overdue ?> hari</div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($item['status'] == 'dipinjam'): ?>
                                    <?php if ($is_overdue): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Terlambat
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i> Dipinjam
                                        </span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Dikembalikan
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="<?= base_url('user/peminjaman/detail/' . $item['id']) ?>" 
                                   class="text-primary hover:text-primary-dark btn-ripple">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Empty State untuk Pencarian -->
<div id="emptySearchState" class="text-center py-12 hidden">
    <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
    <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak Ada Hasil</h3>
    <p class="text-gray-500">Tidak ada peminjaman yang sesuai dengan pencarian Anda</p>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations and counters
    if (typeof fadeInElements === 'function') fadeInElements();
    if (typeof animateCounters === 'function') animateCounters();
    
    // Search and filter functionality
    const searchInput = document.getElementById('searchPeminjaman');
    const statusFilter = document.getElementById('statusFilter');
    const table = document.getElementById('peminjamanTable');
    const emptyState = document.getElementById('emptySearchState');
    
    function filterPeminjaman() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();
        
        const rows = document.querySelectorAll('.peminjaman-row');
        let visibleCount = 0;
        
        rows.forEach(row => {
            const title = row.dataset.title;
            const author = row.dataset.author;
            const status = row.dataset.status;
            
            const matchSearch = !searchTerm || 
                title.includes(searchTerm) || 
                author.includes(searchTerm);
            
            const matchStatus = !statusTerm || status === statusTerm;
            
            if (matchSearch && matchStatus) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0 && (searchTerm || statusTerm)) {
            table.style.display = 'none';
            emptyState.classList.remove('hidden');
        } else {
            table.style.display = '';
            emptyState.classList.add('hidden');
        }
    }
    
    // Event listeners
    if (searchInput) searchInput.addEventListener('keyup', filterPeminjaman);
    if (statusFilter) statusFilter.addEventListener('change', filterPeminjaman);
    
    // Sorting functionality
    const sortButtons = document.querySelectorAll('.sort');
    let currentSort = null;
    let sortDirection = 1;
    
    sortButtons.forEach(button => {
        button.addEventListener('click', function() {
            const sortKey = this.getAttribute('data-sort');
            
            if (currentSort === sortKey) {
                sortDirection *= -1;
            } else {
                sortDirection = 1;
            }
            
            currentSort = sortKey;
            
            // Update sort icons
            sortButtons.forEach(btn => {
                const icon = btn.querySelector('i');
                icon.className = 'fas fa-sort ml-1';
            });
            
            const icon = this.querySelector('i');
            icon.className = sortDirection === 1 ? 'fas fa-sort-up ml-1' : 'fas fa-sort-down ml-1';
            
            // Sort table
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('.peminjaman-row'));
            
            rows.sort((a, b) => {
                let aValue, bValue;
                
                switch (sortKey) {
                    case 'buku':
                        aValue = a.dataset.title;
                        bValue = b.dataset.title;
                        break;
                    case 'tanggal_pinjam':
                        aValue = new Date(a.cells[1].textContent.split('/').reverse().join('/'));
                        bValue = new Date(b.cells[1].textContent.split('/').reverse().join('/'));
                        break;
                    case 'tanggal_kembali':
                        aValue = new Date(a.cells[2].textContent.split('/').reverse().join('/'));
                        bValue = new Date(b.cells[2].textContent.split('/').reverse().join('/'));
                        break;
                    case 'status':
                        aValue = a.dataset.status;
                        bValue = b.dataset.status;
                        break;
                }
                
                if (aValue < bValue) return -1 * sortDirection;
                if (aValue > bValue) return 1 * sortDirection;
                return 0;
            });
            
            // Reorder table
            rows.forEach(row => tbody.appendChild(row));
        });
    });
});
</script>
<?= $this->endSection() ?>
