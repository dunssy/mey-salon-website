<?php
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: /auth/login.php");
    exit;
}

if ($_SESSION['role'] != 'Admin') {
    header("Location: /index.php");
    exit;
}
?>