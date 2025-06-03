<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold page-title-decoration fade-in"><?= $title ?></h1>
</div>

<div class="bg-white shadow-md rounded-lg p-6 decorated-card fade-in">
    <div class="mb-8 flex flex-col md:flex-row items-center">
        <?php 
            $profile_image = isset($user['profile_image']) ? $user['profile_image'] : null;
            $avatarPath = $profile_image ? FCPATH . ltrim($profile_image, '/') : '';
            $default_avatar = 'assets/img/default-avatar.png';
            
            if ($profile_image && file_exists($avatarPath)) {
                $avatar_url = base_url($profile_image) . '?v=' . time(); // Add cache-busting query parameter
            } else {
                $avatar_url = base_url($default_avatar);
            }
        ?>
        <div class="flex-shrink-0 relative profile-image-container">
            <img id="preview-image" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300" 
                src="<?= $avatar_url ?>" 
                alt="Profile Image">
            
            <!-- Tombol upload gambar lebih interaktif -->
            <div class="absolute bottom-0 right-0 bg-primary text-white rounded-full p-2 cursor-pointer hover:bg-primary-dark transition-colors upload-trigger" data-tooltip="Ubah Foto Profil">
                <i class="fas fa-camera"></i>
            </div>
        </div>
        
        <div class="md:ml-6 mt-4 md:mt-0 text-center md:text-left">
            <h2 class="text-xl font-bold fade-in"><?= $user['name'] ?></h2>
            <p class="text-gray-600 fade-in"><?= ucfirst($user['role']) ?></p>
            
            <div class="mt-2 text-sm">
                <p class="fade-in">
                    <span class="font-medium">Username:</span> 
                    <?= $user['username'] ?>
                </p>
                <?php if (!empty($profile_image)): ?>
                <p class="mt-1 text-gray-500 fade-in">
                    Foto profil tersimpan di: <?= $profile_image ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <form id="profileForm" action="<?= base_url('admin/profile/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4 fade-in">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $user['name'] ?>" required>
                <?php if (session('validation') && session('validation')->hasError('name')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('name'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4 fade-in">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $user['username'] ?>" required>
                <?php if (session('validation') && session('validation')->hasError('username')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('username'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4 fade-in">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-eye text-gray-400"></i>
                    </button>
                </div>
                <?php if (session('validation') && session('validation')->hasError('password')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('password'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4 fade-in">
                <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                <input type="file" name="profile_image" id="profile_image" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" accept="image/png, image/jpeg, image/jpg">
                <div class="mt-2 progress-container hidden">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-primary h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 progress-text">0%</p>
                </div>
                <p class="text-gray-500 text-xs mt-1">Format yang didukung: JPG, JPEG, PNG (Max: 1MB)</p>
                <?php if (session('validation') && session('validation')->hasError('profile_image')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('profile_image'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary-dark btn-ripple fade-in">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview image before upload
    const profileInput = document.getElementById('profile_image');
    const previewImage = document.getElementById('preview-image');
    const uploadTrigger = document.querySelector('.upload-trigger');
    
    if (uploadTrigger) {
        uploadTrigger.addEventListener('click', function() {
            profileInput.click();
        });
    }
    
    if (profileInput) {
        profileInput.addEventListener('change', function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                const progressContainer = document.querySelector('.progress-container');
                const progressBar = document.querySelector('.progress-bar');
                const progressText = document.querySelector('.progress-text');
                
                progressContainer.classList.remove('hidden');
                
                reader.onprogress = function(e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percent + '%';
                        progressText.textContent = percent + '%';
                    }
                };
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    progressBar.style.width = '100%';
                    progressText.textContent = '100%';
                    
                    setTimeout(() => {
                        progressContainer.classList.add('hidden');
                    }, 1000);
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
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
    const profileForm = document.getElementById('profileForm');
    
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            let isValid = true;
            const nameInput = document.getElementById('name');
            const usernameInput = document.getElementById('username');
            
            if (nameInput.value.trim().length < 3) {
                createErrorMessage(nameInput, 'Nama harus minimal 3 karakter');
                isValid = false;
            } else {
                removeErrorMessage(nameInput);
            }
            
            if (usernameInput.value.trim().length < 3) {
                createErrorMessage(usernameInput, 'Username harus minimal 3 karakter');
                isValid = false;
            } else {
                removeErrorMessage(usernameInput);
            }
            
            if (!isValid) {
                e.preventDefault();
            } else {
                // Animate button on valid submit
                const submitBtn = profileForm.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
                submitBtn.disabled = true;
            }
        });
    }
    
    function createErrorMessage(input, message) {
        removeErrorMessage(input);
        const error = document.createElement('p');
        error.className = 'text-red-500 text-xs mt-1 error-message';
        error.textContent = message;
        input.parentNode.appendChild(error);
        input.classList.add('border-red-500');
    }
    
    function removeErrorMessage(input) {
        const error = input.parentNode.querySelector('.error-message');
        if (error) {
            error.remove();
            input.classList.remove('border-red-500');
        }
    }
    
    // Animasi avatar
    previewImage.classList.add('hover:scale-105', 'transition-transform', 'duration-300');
});
</script>
<?= $this->endSection() ?>
