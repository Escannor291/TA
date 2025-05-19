<?php

namespace App\Controllers\User;

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
            'title' => 'Profil Saya - Perpustakaan Fachri',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];
        
        return view('user/profile/index', $data);
    }
    
    public function update()
    {
        $userId = session()->get('id');
        
        // Validasi input
        $rules = [
            'name' => 'required|min_length[3]',
            'username' => 'required|min_length[3]|is_unique[users.username,id,' . $userId . ']'
        ];
        
        // Jika password diisi, validasi password juga
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['confirm_password'] = 'matches[password]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Update data profil
        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username')
        ];
        
        // Update password jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }
        
        $this->userModel->update($userId, $data);
        
        // Update session data
        session()->set([
            'name' => $data['name'],
            'username' => $data['username']
        ]);
        
        session()->setFlashdata('message', 'Profil berhasil diperbarui');
        return redirect()->to(base_url('user/profile'));
    }
}
