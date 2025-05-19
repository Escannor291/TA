<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Perpustakaan Fachri</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,600;0,7..72,700;1,7..72,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#795548', // Coklat tanah
                            light: '#a98274',
                            dark: '#4b2c20',
                        },
                        accent: {
                            DEFAULT: '#FFCC80', // Oranye lembut
                            light: '#ffe0b2',
                            dark: '#ffb74d',
                        },
                        background: {
                            DEFAULT: '#FAF3E0', // Gading
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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FAF3E0;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Literata', Georgia, serif;
        }
    </style>
</head>
<body class="bg-background">
    <div class="flex items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h1 class="text-4xl font-extrabold text-center text-primary">
                    <i class="fas fa-book-reader"></i> Perpustakaan Fachri
                </h1>
                <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">
                    Daftar Akun Baru
                </h2>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="p-4 mt-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('message')): ?>
                <div class="p-4 mt-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>
            
            <form class="mt-8 space-y-6" action="<?= base_url('register/create') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="space-y-3 rounded-md shadow-sm">
                    <div>
                        <label for="name" class="sr-only">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="<?= old('name') ?>" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Nama Lengkap">
                        <?php if (session('validation') && session('validation')->hasError('name')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('name') ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" value="<?= old('username') ?>" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
                        <?php if (session('validation') && session('validation')->hasError('username')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('username') ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                        <?php if (session('validation') && session('validation')->hasError('password')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('password') ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="confirm_password" class="sr-only">Konfirmasi Password</label>
                        <input id="confirm_password" name="confirm_password" type="password" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-b-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Konfirmasi Password">
                        <?php if (session('validation') && session('validation')->hasError('confirm_password')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('confirm_password') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <button type="submit" class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md group hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-user-plus"></i>
                        </span>
                        Daftar
                    </button>
                </div>
                
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? <a href="<?= base_url('/') ?>" class="font-medium text-indigo-600 hover:text-indigo-500">Login disini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
