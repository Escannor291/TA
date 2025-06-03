<?php

namespace App\Models;

use CodeIgniter\Model;

class PeminjamanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 
        'buku_id', 
        'tanggal_pinjam', 
        'tanggal_kembali', 
        'tanggal_dikembalikan', 
        'status', 
        'denda'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|is_natural_no_zero',
        'buku_id' => 'required|is_natural_no_zero',
        'tanggal_pinjam' => 'required|valid_date',
        'tanggal_kembali' => 'required|valid_date',
        'status' => 'required|in_list[dipinjam,dikembalikan]'
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID harus diisi',
            'is_natural_no_zero' => 'User ID harus berupa angka positif'
        ],
        'buku_id' => [
            'required' => 'Buku ID harus diisi',
            'is_natural_no_zero' => 'Buku ID harus berupa angka positif'
        ],
        'tanggal_pinjam' => [
            'required' => 'Tanggal pinjam harus diisi',
            'valid_date' => 'Format tanggal pinjam tidak valid'
        ],
        'tanggal_kembali' => [
            'required' => 'Tanggal kembali harus diisi',
            'valid_date' => 'Format tanggal kembali tidak valid'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status harus dipinjam atau dikembalikan'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['validateData'];
    protected $afterInsert    = ['logInsert'];

    protected function validateData(array $data)
    {
        log_message('info', 'PeminjamanModel beforeInsert: ' . json_encode($data));
        return $data;
    }

    protected function logInsert(array $data)
    {
        if (isset($data['id'])) {
            log_message('info', 'PeminjamanModel afterInsert: ID ' . $data['id']);
        }
        return $data;
    }
}
