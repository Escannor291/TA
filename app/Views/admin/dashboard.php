<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<!-- Tambahkan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-semibold text-dark">Dashboard Admin</h1>
        <p class="text-gray-600 transition-opacity duration-500" id="welcomeMessage">Selamat datang kembali, <?= session()->get('name') ?? 'Administrator' ?></p>
    </div>
    
    <!-- Pencarian Global -->
    <div class="relative w-64">
        <input type="text" id="globalSearch" placeholder="Cari buku, anggota..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-search text-gray-400"></i>
        </div>
        <div id="searchResults" class="absolute z-10 w-full mt-1 bg-white shadow-lg rounded-md border border-gray-200 hidden max-h-64 overflow-y-auto">
            <!-- Search results will be populated here via JavaScript -->
        </div>
    </div>
</div>

<!-- Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Buku -->
    <a href="<?= base_url('admin/buku') ?>" class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden group">
        <div class="flex items-center p-6">
            <div class="rounded-full bg-primary-light bg-opacity-30 p-4 mr-4 group-hover:bg-primary-light group-hover:bg-opacity-40 transition duration-300">
                <i class="fas fa-book text-primary text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Buku</p>
                <h3 class="text-3xl font-bold text-gray-800 group-hover:text-primary transition duration-300" id="totalBuku" data-count="<?= $total_buku ?>">0</h3>
            </div>
        </div>
        <div class="bg-background-light px-6 py-2 group-hover:bg-background transition duration-300">
            <span class="flex justify-between items-center text-xs text-primary font-medium">
                <span>Kelola Buku</span>
                <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </a>
    
    <!-- Total Peminjaman Aktif -->
    <a href="<?= base_url('admin/peminjaman') ?>" class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden group">
        <div class="flex items-center p-6">
            <div class="rounded-full bg-accent-light p-4 mr-4 group-hover:bg-accent transition duration-300">
                <i class="fas fa-exchange-alt text-primary text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Peminjaman Aktif</p>
                <h3 class="text-3xl font-bold text-gray-800 group-hover:text-primary transition duration-300" id="totalPeminjaman" data-count="<?= $total_peminjaman ?>">0</h3>
            </div>
        </div>
        <div class="bg-background-light px-6 py-2 group-hover:bg-background transition duration-300">
            <span class="flex justify-between items-center text-xs text-primary font-medium">
                <span>Lihat Peminjaman</span>
                <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </a>
    
    <!-- Total Anggota -->
    <a href="<?= base_url('admin/users') ?>" class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden group">
        <div class="flex items-center p-6">
            <div class="rounded-full bg-accent-light p-4 mr-4 group-hover:bg-accent transition duration-300">
                <i class="fas fa-users text-primary text-3xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Anggota</p>
                <h3 class="text-3xl font-bold text-gray-800 group-hover:text-primary transition duration-300" id="totalAnggota" data-count="<?= $total_anggota ?>">0</h3>
            </div>
        </div>
        <div class="bg-background-light px-6 py-2 group-hover:bg-background transition duration-300">
            <span class="flex justify-between items-center text-xs text-primary font-medium">
                <span>Kelola Anggota</span>
                <i class="fas fa-arrow-right"></i>
            </span>
        </div>
    </a>
</div>

<!-- Notifikasi Peminjaman Hampir Jatuh Tempo -->
<?php if (!empty($peminjaman_hampir_jatuh_tempo)): ?>
<div class="mb-8">
    <div class="bg-accent-light border-l-4 border-accent p-4 rounded-md shadow-md">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-primary"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm leading-5 font-medium text-dark">
                    Ada <?= count($peminjaman_hampir_jatuh_tempo) ?> peminjaman yang hampir jatuh tempo
                </h3>
            </div>
            <div class="ml-auto">
                <button id="toggleNotificationBtn" class="text-primary hover:text-primary-dark transition-colors">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
        </div>
        
        <div id="notificationDetails" class="hidden mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach ($peminjaman_hampir_jatuh_tempo as $peminjaman): ?>
                    <tr class="hover:bg-yellow-50 transition-colors">
                        <td class="px-3 py-2 whitespace-nowrap text-sm"><?= $peminjaman['nama_peminjam'] ?></td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm"><?= $peminjaman['judul_buku'] ?></td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                            <?php
                                $jatuhTempo = new DateTime($peminjaman['tanggal_kembali']);
                                $today = new DateTime();
                                $selisih = $jatuhTempo->diff($today)->days;
                                $badgeClass = $selisih == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800';
                                $text = $selisih == 0 ? 'Hari ini' : ($selisih . ' hari lagi');
                            ?>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $badgeClass ?>">
                                <?= $text ?>
                            </span>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                            <a href="<?= base_url('admin/peminjaman/detail/' . $peminjaman['id']) ?>" class="text-indigo-600 hover:text-indigo-900 hover:underline">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Grafik dan Statistik -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Grafik Peminjaman -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-background-light px-6 py-4 border-b border-background">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-dark">Statistik Peminjaman Mingguan</h2>
            </div>
        </div>
        <div class="p-4">
            <canvas id="peminjamanChart" height="300"></canvas>
        </div>
    </div>
    
    <!-- Buku Terpopuler -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-background-light px-6 py-4 border-b border-background">
            <h2 class="text-lg font-semibold text-dark">Buku Terpopuler</h2>
        </div>
        <div class="p-4">
            <?php if (empty($buku_terpopuler)): ?>
                <div class="text-center py-8">
                    <p class="text-gray-500">Belum ada data peminjaman buku.</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($buku_terpopuler as $buku): ?>
                    <li class="py-3 flex items-start">
                        <div class="flex-shrink-0 h-16 w-12">
                            <?php if (!empty($buku['gambar']) && file_exists('.' . $buku['gambar'])): ?>
                                <img class="h-16 w-12 object-cover rounded" src="<?= base_url($buku['gambar']) ?>" alt="<?= $buku['judul'] ?>">
                            <?php else: ?>
                                <div class="h-16 w-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium text-gray-900"><?= $buku['judul'] ?></p>
                            <p class="text-xs text-gray-500"><?= $buku['penulis'] ?></p>
                            <div class="mt-1 flex items-center">
                                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                    <?= $buku['total_dipinjam'] ?> kali dipinjam
                                </span>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Informasi & Statistik Lanjutan -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Peminjaman Terbaru -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-background-light px-6 py-4 border-b border-background">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-dark">Peminjaman Terbaru</h2>
                    <a href="<?= base_url('admin/peminjaman') ?>" class="text-primary hover:text-primary-dark text-sm flex items-center transition duration-200">
                        Lihat Semua 
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>
            
            <div class="p-4">
                <?php if (empty($peminjaman_terbaru)): ?>
                    <div class="text-center py-8 text-gray-500" id="emptyPeminjamanMessage">
                        <i class="fas fa-inbox text-4xl mb-4 opacity-30"></i>
                        <p>Belum ada data peminjaman.</p>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full" id="peminjamanTable">
                            <thead>
                                <tr class="text-left text-xs text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-2">Peminjam</th>
                                    <th class="px-4 py-2">Buku</th>
                                    <th class="px-4 py-2 hidden sm:table-cell">Tgl Pinjam</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($peminjaman_terbaru as $peminjaman): ?>
                                <tr class="hover:bg-gray-50 transition-all duration-150">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900"><?= $peminjaman['nama_peminjam'] ?></div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-gray-700 truncate max-w-[150px]" title="<?= $peminjaman['judul_buku'] ?>">
                                            <?= $peminjaman['judul_buku'] ?>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 hidden sm:table-cell">
                                        <?= date('d M Y', strtotime($peminjaman['tanggal_pinjam'])) ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if ($peminjaman['status'] == 'dipinjam'): ?>
                                            <?php 
                                            $today = new DateTime();
                                            $batas_kembali = new DateTime($peminjaman['tanggal_kembali']);
                                            $is_late = $today > $batas_kembali;
                                            ?>
                                            <span class="px-2 py-1 text-xs rounded-full <?= $is_late ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' ?>">
                                                <?= $is_late ? 'Terlambat' : 'Dipinjam' ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                Kembali
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="<?= base_url('admin/peminjaman/detail/' . $peminjaman['id']) ?>" 
                                           class="text-indigo-600 hover:text-indigo-900 text-sm hover:underline transition-all duration-150">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Ringkasan Sistem -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <div class="bg-background-light px-6 py-4 border-b border-background">
                <h2 class="text-lg font-semibold text-dark">Ringkasan Sistem</h2>
            </div>
            <div class="p-5">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-700">Tanggal Hari Ini</span>
                    <span class="text-dark font-medium" id="currentDate"><?= date('d F Y') ?></span>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-700">Buku Tersedia</span>
                    <div class="flex items-center text-primary font-medium">
                        <span id="totalBukuTersedia" data-count="<?= $total_buku_tersedia ?>">0</span>
                        <span class="ml-1">buku</span>
                    </div>
                </div>
                <div class="flex justify-between items-center mb-4">
                    <span class="text-gray-700">Peminjaman Hari Ini</span>
                    <div class="flex items-center text-primary font-medium">
                        <span id="peminjamanHariIni" data-count="<?= $peminjaman_hari_ini ?>">0</span>
                        <span class="ml-1">peminjaman</span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-700">Total Denda Terkumpul</span>
                    <div class="flex items-center text-primary font-medium">
                        <span>Rp</span>
                        <span id="totalDenda" data-count="<?= $total_denda ?>" class="ml-1"><?= number_format($total_denda, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan tag script untuk JavaScript dinamis -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animasi counter untuk angka-angka statistik
    function animateCounter(elementId, targetValue) {
        const counterElement = document.getElementById(elementId);
        const target = parseInt(targetValue);
        const duration = 1000; // durasi animasi dalam ms
        const frameRate = 60; // fps
        const totalFrames = duration / 1000 * frameRate;
        const increment = target / totalFrames;
        
        let currentCount = 0;
        let currentFrame = 0;
        
        const counter = setInterval(() => {
            currentCount += increment;
            currentFrame++;
            
            if (currentFrame === totalFrames) {
                clearInterval(counter);
                counterElement.textContent = target;
            } else {
                counterElement.textContent = Math.floor(currentCount);
            }
        }, 1000 / frameRate);
    }
    
    // Pesan welcome yang berubah
    const welcomeMessage = document.getElementById('welcomeMessage');
    const greetings = [
        'Selamat datang kembali, <?= session()->get('name') ?? 'Administrator' ?>',
        'Apa yang ingin Anda kerjakan hari ini?',
        'Dashboard siap digunakan!',
        'Semoga hari Anda menyenangkan!'
    ];
    
    let greetingIndex = 0;
    setInterval(() => {
        welcomeMessage.style.opacity = 0;
        setTimeout(() => {
            greetingIndex = (greetingIndex + 1) % greetings.length;
            welcomeMessage.textContent = greetings[greetingIndex];
            welcomeMessage.style.opacity = 1;
        }, 500); // setengah dari durasi transisi
    }, 5000); // ganti tiap 5 detik
    
    // Tanggal dan waktu real-time
    const currentDateElement = document.getElementById('currentDate');
    function updateDateTime() {
        const now = new Date();
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        currentDateElement.textContent = now.toLocaleDateString('id-ID', options);
    }
    
    // Jalankan update tanggal tiap menit
    updateDateTime();
    setInterval(updateDateTime, 60000);
    
    // Highlight baris tabel saat dihover
    const tableRows = document.querySelectorAll('#peminjamanTable tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseover', () => {
            row.classList.add('bg-gray-50');
        });
        row.addEventListener('mouseout', () => {
            row.classList.remove('bg-gray-50');
        });
    });
    
    // Jalankan animasi counter untuk semua elemen statistik
    animateCounter('totalBuku', document.getElementById('totalBuku').getAttribute('data-count'));
    animateCounter('totalPeminjaman', document.getElementById('totalPeminjaman').getAttribute('data-count'));
    animateCounter('totalAnggota', document.getElementById('totalAnggota').getAttribute('data-count'));
    animateCounter('totalBukuTersedia', document.getElementById('totalBukuTersedia').getAttribute('data-count'));
    animateCounter('peminjamanHariIni', document.getElementById('peminjamanHariIni').getAttribute('data-count'));
    
    // Tambahkan highlight untuk baris dengan status terlambat
    document.querySelectorAll('#peminjamanTable tbody tr').forEach(row => {
        const statusCell = row.querySelector('td:nth-child(4)');
        if (statusCell && statusCell.textContent.trim().includes('Terlambat')) {
            statusCell.classList.add('animate-pulse');
        }
    });
    
    // Toggle notifikasi peminjaman hampir jatuh tempo
    const toggleNotificationBtn = document.getElementById('toggleNotificationBtn');
    const notificationDetails = document.getElementById('notificationDetails');
    
    if (toggleNotificationBtn) {
        toggleNotificationBtn.addEventListener('click', function() {
            notificationDetails.classList.toggle('hidden');
            toggleNotificationBtn.querySelector('i').classList.toggle('fa-chevron-down');
            toggleNotificationBtn.querySelector('i').classList.toggle('fa-chevron-up');
        });
    }
    
    // Chart peminjaman mingguan
    const ctx = document.getElementById('peminjamanChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_data['labels']) ?>,
                datasets: [{
                    label: 'Peminjaman',
                    data: <?= json_encode($chart_data['peminjaman']) ?>,
                    backgroundColor: 'rgba(121, 85, 72, 0.2)', // primary color with opacity
                    borderColor: '#795548', // primary color
                    tension: 0.3,
                    borderWidth: 2
                }, {
                    label: 'Pengembalian',
                    data: <?= json_encode($chart_data['pengembalian']) ?>,
                    backgroundColor: 'rgba(255, 204, 128, 0.2)', // accent color with opacity
                    borderColor: '#FFCC80', // accent color
                    tension: 0.3,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
    
    // Pencarian global
    const globalSearch = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');
    
    if (globalSearch) {
        globalSearch.addEventListener('focus', function() {
            if (globalSearch.value.length > 0) {
                searchResults.classList.remove('hidden');
            }
        });
        
        globalSearch.addEventListener('blur', function(e) {
            // Delay hiding to allow clicking on results
            setTimeout(() => {
                searchResults.classList.add('hidden');
            }, 200);
        });
        
        globalSearch.addEventListener('input', function() {
            if (this.value.length > 2) { // Search after 3 characters
                // Simulate search results
                searchResults.classList.remove('hidden');
                fetch(`<?= base_url('admin/search') ?>?q=${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        
                        // Show results or "no results" message
                        if (data.length === 0) {
                            html = '<div class="p-3 text-sm text-gray-500">Tidak ada hasil ditemukan</div>';
                        } else {
                            data.forEach(item => {
                                let icon = '';
                                let link = '#';
                                
                                if (item.type === 'buku') {
                                    icon = '<i class="fas fa-book text-blue-600 mr-2"></i>';
                                    link = `<?= base_url('admin/buku/edit') ?>/${item.id}`;
                                } else if (item.type === 'anggota') {
                                    icon = '<i class="fas fa-user text-green-600 mr-2"></i>';
                                    link = `<?= base_url('admin/users/edit') ?>/${item.id}`;
                                } else if (item.type === 'peminjaman') {
                                    icon = '<i class="fas fa-bookmark text-indigo-600 mr-2"></i>';
                                    link = `<?= base_url('admin/peminjaman/detail') ?>/${item.id}`;
                                }
                                
                                html += `
                                <a href="${link}" class="block p-2 hover:bg-gray-50 text-sm">
                                    <div class="flex items-center">
                                        ${icon}
                                        <div>
                                            <p class="font-medium">${item.title}</p>
                                            <p class="text-xs text-gray-500">${item.subtitle}</p>
                                        </div>
                                    </div>
                                </a>`;
                            });
                        }
                        
                        searchResults.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error during search:', error);
                        searchResults.innerHTML = '<div class="p-3 text-sm text-red-500">Error during search</div>';
                    });
            } else {
                searchResults.classList.add('hidden');
            }
        });
    }
});
</script>
<?= $this->endSection() ?>