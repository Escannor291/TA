<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
</div>

<?php if (session()->getFlashdata('message')): ?>
    <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg p-6">
    <div class="mb-8 flex items-center">
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
        <div class="flex-shrink-0">
            <img id="preview-image" class="w-32 h-32 rounded-full object-cover border-2 border-gray-300" 
                src="<?= $avatar_url ?>" 
                alt="Profile Image">
        </div>
        <div class="ml-6">
            <h2 class="text-xl font-bold"><?= $user['name'] ?></h2>
            <p class="text-gray-600"><?= ucfirst($user['role']) ?></p>
            
            <div class="mt-2 text-sm">
                <p>
                    <span class="font-medium">Username:</span> 
                    <?= $user['username'] ?>
                </p>
                <?php if (!empty($profile_image)): ?>
                <p class="mt-1 text-gray-500">
                    Foto profil tersimpan di: <?= $profile_image ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <form action="<?= base_url('admin/profile/update') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $user['name'] ?>" required>
                <?php if (session('validation') && session('validation')->hasError('name')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('name'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= $user['username'] ?>" required>
                <?php if (session('validation') && session('validation')->hasError('username')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('username'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password (Biarkan kosong jika tidak ingin mengubah)</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <?php if (session('validation') && session('validation')->hasError('password')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('password'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                <input type="file" name="profile_image" id="profile_image" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" accept="image/png, image/jpeg, image/jpg">
                <p class="text-gray-500 text-xs mt-1">Format yang didukung: JPG, JPEG, PNG (Max: 1MB)</p>
                <?php if (session('validation') && session('validation')->hasError('profile_image')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('profile_image'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
// Preview image before upload
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('profile_image').addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('preview-image').src = event.target.result;
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
</script>
<?= $this->endSection() ?>
