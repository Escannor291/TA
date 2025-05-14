<?php

// Path ke direktori public CodeIgniter
$public_path = 'public';

// Redirect ke controller Auth untuk halaman login
if (file_exists($public_path . DIRECTORY_SEPARATOR . 'index.php')) {
    require_once $public_path . DIRECTORY_SEPARATOR . 'index.php';
} else {
    echo 'Cannot find the CodeIgniter public/index.php file.';
}
