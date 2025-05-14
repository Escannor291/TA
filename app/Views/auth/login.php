<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Perpustakaan</title>
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8">
            <div>
                <h1 class="text-4xl font-extrabold text-center text-indigo-600">
                    <i class="fas fa-book-reader"></i> Perpustakaan
                </h1>
                <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">
                    Login ke akun Anda
                </h2>
            </div>
            
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="p-4 mt-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('message')) : ?>
                <div class="p-4 mt-4 text-sm text-green-700 bg-green-100 rounded-lg">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>
            
            <form class="mt-8 space-y-6" action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="-space-y-px rounded-md shadow-sm">
                    <div>
                        <label for="username" class="sr-only">Username</label>
                        <input id="username" name="username" type="text" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-t-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Username">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" required class="relative block w-full px-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-b-md appearance-none focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <div>
                    <button type="submit" class="relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md group hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>