<?= $this->extend('layout/user_layout') ?>

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
    <form action="<?= base_url('user/profile/update') ?>" method="post">
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
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <?php if (session('validation') && session('validation')->hasError('password')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('password'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <?php if (session('validation') && session('validation')->hasError('confirm_password')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('confirm_password'); ?></p>
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
<?= $this->endSection() ?>
