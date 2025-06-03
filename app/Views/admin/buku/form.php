<?= $this->extend('layout/admin_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                <?= isset($buku) ? 'Edit Buku' : 'Tambah Buku Baru' ?>
            </h1>
        </div>

        <!-- Form -->
        <form action="<?= isset($buku) ? base_url('admin/buku/update/' . $buku['id']) : base_url('admin/buku/create') ?>" 
              method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <?= csrf_field() ?>
            <?php if (isset($buku)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Buku</label>
                <input type="text" name="judul" id="judul" 
                       value="<?= old('judul', isset($buku) ? $buku['judul'] : '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
                <?php if (isset($validation) && $validation->hasError('judul')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('judul') ?></div>
                <?php endif; ?>
            </div>

            <!-- Penulis -->
            <div>
                <label for="penulis" class="block text-sm font-medium text-gray-700 mb-2">Penulis</label>
                <input type="text" name="penulis" id="penulis" 
                       value="<?= old('penulis', isset($buku) ? $buku['penulis'] : '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
                <?php if (isset($validation) && $validation->hasError('penulis')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('penulis') ?></div>
                <?php endif; ?>
            </div>

            <!-- Penerbit -->
            <div>
                <label for="penerbit" class="block text-sm font-medium text-gray-700 mb-2">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" 
                       value="<?= old('penerbit', isset($buku) ? $buku['penerbit'] : '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
                <?php if (isset($validation) && $validation->hasError('penerbit')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('penerbit') ?></div>
                <?php endif; ?>
            </div>

            <!-- Tahun Terbit -->
            <div>
                <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 mb-2">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" 
                       value="<?= old('tahun_terbit', isset($buku) ? $buku['tahun_terbit'] : '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
                <?php if (isset($validation) && $validation->hasError('tahun_terbit')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('tahun_terbit') ?></div>
                <?php endif; ?>
            </div>

            <!-- ISBN -->
            <div>
                <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">ISBN</label>
                <input type="text" name="isbn" id="isbn" 
                       value="<?= old('isbn', isset($buku) ? $buku['isbn'] : '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required>
                <?php if (isset($validation) && $validation->hasError('isbn')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('isbn') ?></div>
                <?php endif; ?>
            </div>

            <!-- Jumlah -->
            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" 
                       value="<?= old('jumlah', isset($buku) ? $buku['jumlah'] : '') ?>" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                       required min="1">
                <?php if (isset($validation) && $validation->hasError('jumlah')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('jumlah') ?></div>
                <?php endif; ?>
            </div>

            <!-- Foto Buku -->
            <div>
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Buku</label>
                
                <?php if (isset($buku) && !empty($buku['foto'])): ?>
                    <div class="mb-3">
                        <img src="<?= base_url('uploads/buku/' . $buku['foto']) ?>" 
                             alt="Foto Buku" 
                             id="preview" 
                             class="w-32 h-40 object-cover rounded border">
                        <p class="text-sm text-gray-500 mt-1">Foto saat ini</p>
                    </div>
                <?php else: ?>
                    <div class="mb-3" id="preview-container" style="display: none;">
                        <img id="preview" class="w-32 h-40 object-cover rounded border">
                        <p class="text-sm text-gray-500 mt-1">Preview foto</p>
                    </div>
                <?php endif; ?>
                
                <input type="file" name="foto" id="foto" accept="image/*" 
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-sm text-gray-500 mt-1">Upload foto buku (JPG, PNG, max 2MB)</p>
                
                <?php if (isset($validation) && $validation->hasError('foto')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('foto') ?></div>
                <?php endif; ?>
            </div>

            <!-- Deskripsi -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"><?= old('deskripsi', (isset($buku) && isset($buku['deskripsi'])) ? $buku['deskripsi'] : '') ?></textarea>
                <?php if (isset($validation) && $validation->hasError('deskripsi')): ?>
                    <div class="text-red-500 text-sm mt-1"><?= $validation->getError('deskripsi') ?></div>
                <?php endif; ?>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <a href="<?= base_url('admin/buku') ?>" 
                   class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    <i class="fas fa-<?= isset($buku) ? 'save' : 'plus' ?> mr-2"></i>
                    <?= isset($buku) ? 'Update Data' : 'Simpan Data' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview image before upload
document.getElementById('foto').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('preview-container');
            
            preview.src = e.target.result;
            if (previewContainer) {
                previewContainer.style.display = 'block';
            }
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>

<?= $this->endSection() ?>
