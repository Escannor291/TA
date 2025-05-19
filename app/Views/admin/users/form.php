<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/users') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="<?= isset($user) ? base_url('admin/users/update/' . $user['id']) : base_url('admin/users/create') ?>" method="post">
        <?= csrf_field() ?>
        
        <?php if (isset($user)): ?>
        <input type="hidden" name="_method" value="PUT" />
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($user) ? $user['name'] : old('name') ?>" required>
                <?php if (session('validation') && session('validation')->hasError('name')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('name'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="username" id="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($user) ? $user['username'] : old('username') ?>" required>
                <?php if (session('validation') && session('validation')->hasError('username')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('username'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Password <?= isset($user) ? '(Biarkan kosong jika tidak ingin mengubah)' : '' ?>
                </label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" <?= isset($user) ? '' : 'required' ?>>
                <?php if (session('validation') && session('validation')->hasError('password')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('password'); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                    <option value="">Pilih Role</option>
                    <option value="anggota" <?= (isset($user) && $user['role'] == 'anggota') || old('role') == 'anggota' ? 'selected' : '' ?>>Anggota</option>
                    <option value="petugas" <?= (isset($user) && $user['role'] == 'petugas') || old('role') == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                </select>
                <?php if (session('validation') && session('validation')->hasError('role')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('role'); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                <?= isset($user) ? '<i class="fas fa-save mr-2"></i>Update Data' : '<i class="fas fa-plus mr-2"></i>Simpan Data' ?>
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
