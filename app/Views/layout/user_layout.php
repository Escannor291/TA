<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Perpustakaan Fachri' ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,600;0,7..72,700;1,7..72,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/decorations.css?v='.time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animations.css?v='.time()) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/dark-mode.css?v='.time()) ?>">
    <script>
        // Script Tailwind Config sama dengan layout admin
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#795548',
                            light: '#a98274',
                            dark: '#4b2c20',
                        },
                        accent: {
                            DEFAULT: '#FFCC80',
                            light: '#ffe0b2',
                            dark: '#ffb74d',
                        },
                        background: {
                            DEFAULT: '#FAF3E0',
                            light: '#fffdf9',
                            dark: '#f5e9d0',
                        },
                        dark: {
                            DEFAULT: '#3c3c3c',
                        }
                    },
                    fontFamily: {
                        'sans': ['Poppins', 'sans-serif'],
                        'serif': ['Literata', 'Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles sama dengan layout admin */
        .active-nav-link {
            background-color: #4b2c20;
            color: white;
        }
        
        body {
            background-color: #FAF3E0;
            font-family: 'Poppins', sans-serif;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Literata', Georgia, serif;
        }
        
        /* Fix SVG paths */
        .decor-books {
            background-image: url('<?= base_url('assets/img/decor-books.svg') ?>');
        }
        
        .decor-leaves {
            background-image: url('<?= base_url('assets/img/decor-leaves.svg') ?>');
        }
        
        .decor-dots {
            background-image: url('<?= base_url('assets/img/decor-dots.svg') ?>');
        }
        
        .decorated-card::before {
            background-image: url('<?= base_url('assets/img/decor-corner.svg') ?>');
        }
    </style>
</head>
<body class="bg-background" data-page="<?= isset($page) ? $page : 'Default' ?>">
    <!-- Elemen dekorasi statis -->
    <div class="decor-leaves"></div>
    <div class="decor-dots"></div>
    
    <!-- Notifikasi dinamis dari flashdata -->
    <?php if (session()->getFlashdata('message')): ?>
    <div class="notification success">
        <span><?= session()->getFlashdata('message') ?></span>
        <span class="notification-close">&times;</span>
    </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
    <div class="notification error">
        <span><?= session()->getFlashdata('error') ?></span>
        <span class="notification-close">&times;</span>
    </div>
    <?php endif; ?>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-primary border-r">
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <h1 class="text-xl font-semibold text-white font-literata">Perpustakaan Fachri</h1>
                    </div>
                    
                    <!-- Dekorasi buku di bawah judul -->
                    <div class="decor-books mt-2 mb-4 opacity-25"></div>
                    
                    <nav class="flex-1 px-2 mt-2 space-y-1 bg-primary">
                        <a href="<?= base_url('user/dashboard') ?>" class="<?= uri_string() == 'user/dashboard' ? 'active-nav-link' : 'text-white hover:bg-primary-dark' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                        <a href="<?= base_url('user/katalog') ?>" class="<?= uri_string() == 'user/katalog' ? 'active-nav-link' : 'text-white hover:bg-primary-dark' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-book mr-3"></i> Katalog Buku
                        </a>
                        <a href="<?= base_url('user/peminjaman') ?>" class="<?= uri_string() == 'user/peminjaman' ? 'active-nav-link' : 'text-white hover:bg-primary-dark' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-book-reader mr-3"></i> Peminjaman Saya
                        </a>
                        <a href="<?= base_url('user/profile') ?>" class="<?= uri_string() == 'user/profile' ? 'active-nav-link' : 'text-white hover:bg-primary-dark' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-user mr-3"></i> Profil Saya
                        </a>
                        <a href="<?= base_url('auth/logout') ?>" class="text-white hover:bg-primary-dark group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Top header -->
            <div class="relative z-10 flex flex-shrink-0 h-16 bg-white shadow">
                <button id="sidebar-toggle" class="px-4 text-gray-500 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex justify-between flex-1 px-4">
                    <div class="flex flex-1">
                        <h2 class="text-xl font-semibold text-gray-800 self-center font-literata"><?= $title ?? 'Dashboard Anggota - Perpustakaan Fachri' ?></h2>
                    </div>
                    <div class="flex items-center ml-4 md:ml-6">
                        <span class="text-gray-700 mr-4 font-poppins"><?= session()->get('name') ?? 'Anggota' ?></span>
                        
                        <!-- Dark mode toggle yang baru -->
                        <div id="darkModeToggle" class="dark-toggle">
                            <div class="toggle-circle"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="relative flex-1 overflow-y-auto focus:outline-none bg-background">
                <div class="py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
                        <?= $this->renderSection('content') ?>
                    </div>
                </div>
                <!-- Footer dengan dekorasi -->
                <div class="mt-8 decor-books"></div>
                <footer class="p-4 text-center text-sm text-gray-500">
                    <p>&copy; <?= date('Y') ?> Perpustakaan Fachri. All rights reserved.</p>
                </footer>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="<?= base_url('assets/js/main.js?v='.time()) ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script untuk mobile sidebar toggle
        const button = document.querySelector('button[aria-label="Open sidebar"]');
        if (button) {
            button.addEventListener('click', function() {
                const sidebar = document.querySelector('div.md\\:flex-shrink-0');
                sidebar.classList.toggle('hidden');
            });
        }

        // Dark mode functionality
        const darkModeToggle = document.getElementById('darkModeToggle');
        const body = document.body;
        
        // Check if dark mode is enabled in localStorage
        const isDarkMode = localStorage.getItem('darkMode') === 'true';
        
        // Set initial state
        if (isDarkMode) {
            body.classList.add('dark-mode');
        }
        
        darkModeToggle.addEventListener('click', function() {
            body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', body.classList.contains('dark-mode'));
        });
    });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
