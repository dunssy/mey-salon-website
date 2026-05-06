<?php
require_once '../config/koneksi.php';

$nama     = mysqli_real_escape_string($conn, $_POST['nama']);
$no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
$alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi_password'];

if ($password != $konfirmasi) {
    header("Location: register.php?error=Konfirmasi password tidak sama");
    exit;
}

$cek = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' OR no_hp = '$no_hp'");
if (mysqli_num_rows($cek) > 0) {
    header("Location: register.php?error=Username atau nomor HP sudah digunakan");
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$query = mysqli_query($conn, "
    INSERT INTO user (nama, no_hp, alamat, username, role, password)
    VALUES ('$nama', '$no_hp', '$alamat', '$username', 'User', '$hash')
");

if ($query) {
    header("Location: login.php");
    exit;
}

header("Location: register.php?error=Registrasi gagal");
exit;
?>