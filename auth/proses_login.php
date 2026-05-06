<?php
require_once '../config/koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username' OR no_hp = '$username' LIMIT 1");

if (mysqli_num_rows($query) == 1) {
    $user = mysqli_fetch_assoc($query);

    if (password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];

        if ($user['role'] == 'Admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit;
    }
}

header("Location: login.php?error=Username atau password salah");
exit;
?>