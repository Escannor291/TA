<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Fachri</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:ital,opsz,wght@0,7..72,400;0,7..72,600;0,7..72,700;1,7..72,400&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Dekorasi CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/decorations.css') ?>">
    <!-- Animations CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/animations.css') ?>">
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
            background-image: url('<?= base_url("assets/img/decor-dots.svg") ?>');
            background-repeat: no-repeat;
            background-position: left bottom;
            background-size: 300px;
        }
        
        h1, h2, h3, h4, h5 {
            font-family: 'Literata', Georgia, serif;
        }
        
        .login-container {
            position: relative;
            z-index: 10;
        }
        
        .login-decor {
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background-image: url('<?= base_url("assets/img/decor-leaves.svg") ?>');
            background-repeat: no-repeat;
            background-size: contain;
            opacity: 0.1;
            z-index: 0;
            animation: float 6s ease-in-out infinite;
        }
        
        .books-decor {
            margin: 20px 0;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 5s ease-in-out infinite;
        }
        
        .form-input {
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            transform: scale(1.02);
        }
        
        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }
        
        .typing-text {
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid #795548;
            width: 0;
            animation: typing 3.5s steps(40, end) forwards;
        }
        
        .leaf {
            position: absolute;
            width: 30px;
            height: 30px;
            background-image: url('<?= base_url("assets/img/decor-leaves.svg") ?>');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.2;
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body class="bg-background">
    <div class="flex items-center justify-center min-h-screen px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md space-y-8 login-container fade-in">
            <div class="login-decor floating"></div>
            
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-center text-primary">
                    <i class="fas fa-book-reader"></i> Perpustakaan Fachri
                </h1>
                <div class="decor-books books-decor"></div>
                <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900 typing-text">
                    Login
                </h2>
            </div>
            
            <?php if (session()->getFlashdata('error')): ?>
            <div class="notification error">
                <span><?= session()->getFlashdata('error') ?></span>
                <span class="notification-close">&times;</span>
            </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('message')): ?>
            <div class="notification success">
                <span><?= session()->getFlashdata('message') ?></span>
                <span class="notification-close">&times;</span>
            </div>
            <?php endif; ?>
            
            <form id="loginForm" class="mt-8 space-y-6 bg-white p-6 rounded-lg shadow-md decorated-card" action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="space-y-3 rounded-md shadow-sm">
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-user text-gray-400"></i>
                            </span>
                            <input id="username" name="username" type="text" required class="form-input relative block w-full pl-10 pr-3 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Masukkan username">
                        </div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fas fa-lock text-gray-400"></i>
                            </span>
                            <input id="password" name="password" type="password" required class="form-input relative block w-full pl-10 pr-10 py-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md appearance-none focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm" placeholder="Masukkan password">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-ripple relative flex justify-center w-full px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md group bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                        Login
                    </button>
                </div>

                <div class="text-center mt-4">
                    <p class="text-sm text-gray-600">
                        Belum punya akun? <a href="<?= base_url('register') ?>" class="font-medium text-primary hover:text-primary-dark">Daftar disini</a>
                    </p>
                </div>
            </form>
            
            <div class="decor-books books-decor"></div>
            <p class="text-center text-sm text-gray-500">&copy; <?= date('Y') ?> Perpustakaan Fachri</p>
        </div>
    </div>

    <!-- JavaScript untuk efek visual -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle the eye icon
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }
            
            // Form validation
            const loginForm = document.getElementById('loginForm');
            
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    // Animasi loading
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengautentikasi...';
                    submitBtn.disabled = true;
                });
            }
            
            // Animated leaves
            function createLeaves() {
                const leafCount = 10;
                const container = document.body;
                
                for (let i = 0; i < leafCount; i++) {
                    const leaf = document.createElement('div');
                    leaf.className = 'leaf';
                    
                    // Random position
                    leaf.style.left = Math.random() * window.innerWidth + 'px';
                    leaf.style.top = Math.random() * window.innerHeight + 'px';
                    
                    // Random size
                    const size = Math.random() * 20 + 10;
                    leaf.style.width = size + 'px';
                    leaf.style.height = size + 'px';
                    
                    // Random rotation
                    leaf.style.transform = `rotate(${Math.random() * 360}deg)`;
                    
                    // Animation
                    leaf.style.animation = `float ${Math.random() * 6 + 4}s ease-in-out infinite`;
                    leaf.style.animationDelay = Math.random() * 5 + 's';
                    
                    container.appendChild(leaf);
                }
            }
            
            createLeaves();
            
            // Initialize notifications
            initNotifications();
        });
    </script>
</body>
</html>