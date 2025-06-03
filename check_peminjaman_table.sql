-- Jalankan query ini di phpMyAdmin

-- 1. Cek struktur tabel peminjaman
DESCRIBE peminjaman;

-- 2. Cek semua data peminjaman
SELECT * FROM peminjaman;

-- 3. Cek data dengan join
SELECT 
    p.*,
    u.name as user_name,
    u.username,
    b.judul
FROM peminjaman p
JOIN users u ON u.id = p.user_id
JOIN buku b ON b.id = p.buku_id
ORDER BY p.created_at DESC;

-- 4. Cek user dengan nama 'ariiiii'
SELECT * FROM users WHERE name LIKE '%ariiiii%' OR username LIKE '%ariiiii%';

-- 5. Cek data peminjaman untuk user tertentu (ganti 3 dengan ID user yang benar)
SELECT 
    p.*,
    b.judul,
    b.penulis
FROM peminjaman p
JOIN buku b ON b.id = p.buku_id
WHERE p.user_id = 3;
