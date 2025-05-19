<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold"><?= $title ?></h1>
    <a href="<?= base_url('admin/buku') ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg p-6">
    <form action="<?= isset($buku) ? base_url('admin/buku/update/' . $buku['id']) : base_url('admin/buku/create') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <?php if (isset($buku)): ?>
        <input type="hidden" name="_method" value="PUT" />
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4 md:col-span-2">
                <label for="gambar" class="block text-sm font-medium text-gray-700 mb-1">Gambar Buku</label>
                <div class="flex items-start">
                    <div class="mr-4 flex flex-col items-center">
                        <?php 
                        $gambarSrc = '';
                        if (isset($buku) && !empty($buku['gambar']) && file_exists('.' . $buku['gambar'])) {
                            $gambarSrc = base_url($buku['gambar']) . '?v=' . time();
                        } else {
                            $gambarSrc = base_url('assets/img/no-image.png');
                        }
                        ?>
                        <img id="preview-image" src="<?= $gambarSrc ?>" alt="Preview" class="w-32 h-40 object-cover border rounded">
                        <p class="text-xs text-gray-500 mt-2">Preview</p>
                    </div>
                    
                    <div class="flex-1">
                        <input type="file" name="gambar" id="gambar" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" accept="image/*">
                        <p class="text-gray-500 text-xs mt-1">Format yang didukung: JPG, JPEG, PNG (Max: 2MB)</p>
                        
                        <?php if (session('validation') && session('validation')->hasError('gambar')): ?>
                            <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('gambar'); ?></p>
                        <?php endif; ?>
                        
                        <?php if (isset($buku) && !empty($buku['gambar'])): ?>
                            <p class="text-blue-500 text-xs mt-2">
                                <i class="fas fa-info-circle mr-1"></i>
                                File gambar saat ini: <?= basename($buku['gambar']) ?>
                            </p>
                            <p class="text-gray-500 text-xs mt-1">Upload gambar baru jika ingin mengganti, atau biarkan kosong jika tidak ingin mengubah gambar</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Buku</label>
                    <input type="text" name="judul" id="judul" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($buku) ? $buku['judul'] : old('judul') ?>" required>
                    <?php if (session('validation') && session('validation')->hasError('judul')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('judul'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="penulis" class="block text-sm font-medium text-gray-700 mb-1">Penulis</label>
                    <input type="text" name="penulis" id="penulis" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($buku) ? $buku['penulis'] : old('penulis') ?>" required>
                    <?php if (session('validation') && session('validation')->hasError('penulis')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('penulis'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-1">Penerbit</label>
                    <input type="text" name="penerbit" id="penerbit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($buku) ? $buku['penerbit'] : old('penerbit') ?>" required>
                    <?php if (session('validation') && session('validation')->hasError('penerbit')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('penerbit'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-1">Tahun Terbit</label>
                    <input type="number" name="tahun_terbit" id="tahun_terbit" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($buku) ? $buku['tahun_terbit'] : old('tahun_terbit') ?>" min="1900" max="<?= date('Y') ?>" required>
                    <?php if (session('validation') && session('validation')->hasError('tahun_terbit')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('tahun_terbit'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                    <input type="text" name="isbn" id="isbn" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($buku) ? $buku['isbn'] : old('isbn') ?>" required>
                    <?php if (session('validation') && session('validation')->hasError('isbn')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('isbn'); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="mb-4">
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <input type="number" name="jumlah" id="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" value="<?= isset($buku) ? $buku['jumlah'] : old('jumlah') ?>" min="1" required>
                    <?php if (session('validation') && session('validation')->hasError('jumlah')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('validation')->getError('jumlah'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                <?= isset($buku) ? '<i class="fas fa-save mr-2"></i>Update Data' : '<i class="fas fa-plus mr-2"></i>Simpan Data' ?>
            </button>
        </div>
    </form>
</div>

<script>
// Preview image before upload
document.getElementById('gambar').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
<?= $this->endSection() ?>
