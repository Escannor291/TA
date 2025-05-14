<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Perpustakaan' ?></title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Custom styles */
        .active-nav-link {
            background-color: #4338ca;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-indigo-800 border-r">
                <div class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <h1 class="text-xl font-semibold text-white">Perpustakaan</h1>
                    </div>
                    <nav class="flex-1 px-2 mt-5 space-y-1 bg-indigo-800">
                        <a href="<?= base_url('admin/dashboard') ?>" class="<?= uri_string() == 'admin/dashboard' ? 'active-nav-link' : 'text-white hover:bg-indigo-700' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                        <a href="<?= base_url('admin/buku') ?>" class="<?= uri_string() == 'admin/buku' ? 'active-nav-link' : 'text-white hover:bg-indigo-700' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-book mr-3"></i> Kelola Buku
                        </a>
                        <a href="<?= base_url('admin/peminjaman') ?>" class="<?= uri_string() == 'admin/peminjaman' ? 'active-nav-link' : 'text-white hover:bg-indigo-700' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-exchange-alt mr-3"></i> Peminjaman
                        </a>
                        <a href="<?= base_url('admin/pengembalian') ?>" class="<?= uri_string() == 'admin/pengembalian' ? 'active-nav-link' : 'text-white hover:bg-indigo-700' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-undo-alt mr-3"></i> Pengembalian
                        </a>
                        <a href="<?= base_url('admin/users') ?>" class="<?= uri_string() == 'admin/users' ? 'active-nav-link' : 'text-white hover:bg-indigo-700' ?> group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-users mr-3"></i> Kelola Anggota
                        </a>
                        <a href="<?= base_url('auth/logout') ?>" class="text-white hover:bg-indigo-700 group flex items-center px-2 py-2 text-sm font-medium rounded-md">
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
                <button class="px-4 text-gray-500 border-r border-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 md:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <i class="fas fa-bars"></i>
                </button>
                <div class="flex justify-between flex-1 px-4">
                    <div class="flex flex-1">
                        <h2 class="text-xl font-semibold text-gray-800 self-center"><?= $title ?? 'Dashboard' ?></h2>
                    </div>
                    <div class="flex items-center ml-4 md:ml-6">
                        <div class="relative ml-3">
                            <span class="text-gray-700"><?= session()->get('name') ?? 'Administrator' ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content area -->
            <main class="relative flex-1 overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 md:px-8">
                        <?= $this->renderSection('content') ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Script untuk mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.querySelector('button[aria-label="Open sidebar"]');
            if (button) {
                button.addEventListener('click', function() {
                    const sidebar = document.querySelector('div.md\\:flex-shrink-0');
                    sidebar.classList.toggle('hidden');
                });
            }

            // Tambahkan konfirmasi untuk logout
            const logoutLink = document.querySelector('a[href*="auth/logout"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    if (!confirm('Apakah Anda yakin ingin keluar?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</body>
</html>