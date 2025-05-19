<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
    }
    
    public function index()
    {
        $userId = session()->get('id');
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'Profil Admin - Perpustakaan Fachri',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];
        
        return view('admin/profile/index', $data);
    }
    
    public function update()
    {
        $userId = session()->get('id');
        
        // Validasi
        $rules = [
            'name' => 'required',
            'username' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username,id,' . $userId . ']',
            'profile_image' => 'max_size[profile_image,1024]|is_image[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Ambil data user yang sedang login
        $user = $this->userModel->find($userId);
        
        // Upload image if provided
        $profileImage = $this->request->getFile('profile_image');
        
        // Siapkan data untuk update
        $updateData = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username')
        ];
        
        // Proses upload gambar jika ada
        if ($profileImage && $profileImage->isValid() && !$profileImage->hasMoved()) {
            // Pastikan direktori upload ada
            $uploadPath = './uploads/profile';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Delete old image if exists
            if (!empty($user['profile_image'])) {
                $oldImagePath = FCPATH . ltrim($user['profile_image'], '/');
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Generate random name
            $newName = $profileImage->getRandomName();
            
            // Store in uploads/profile
            $profileImage->move($uploadPath, $newName);
            
            // Save the path to database (using forward slashes for URLs)
            $updateData['profile_image'] = '/uploads/profile/' . $newName;
            
            // Log success message for debugging
            log_message('debug', 'Profile image uploaded to: ' . $updateData['profile_image']);
            
            // Update session
            session()->set('profile_image', $updateData['profile_image']);
        }
        
        // Update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        // Update database
        try {
            $this->userModel->update($userId, $updateData);
            
            // Update session
            session()->set('name', $updateData['name']);
            session()->set('username', $updateData['username']);
            
            session()->setFlashdata('message', 'Profil berhasil diperbarui' . 
                (isset($updateData['profile_image']) ? ' dengan foto baru' : ''));
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        
        return redirect()->to(base_url('admin/profile'));
    }
}
