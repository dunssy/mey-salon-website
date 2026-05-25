<?php
session_start();
include "config/app.php";
global $koneksi;

if (!isset($_SESSION['register_data'])) {
    echo "<script>alert('Silakan registrasi terlebih dahulu!'); window.location.href = 'registrasi.php';</script>";
    exit;
}

$register_data = $_SESSION['register_data'];

if (isset($_POST['verifikasi'])) {
    $otp_input = trim($_POST['otp']);

    if (time() > $register_data['expired']) {
        unset($_SESSION['register_data']);
        echo "<script>alert('Kode OTP sudah expired. Silakan registrasi ulang!'); window.location.href = 'registrasi.php';</script>";
        exit;
    }

    if ($otp_input === $register_data['otp']) {
        $nama = mysqli_real_escape_string($koneksi, $register_data['nama']);
        $email = mysqli_real_escape_string($koneksi, $register_data['email']);
        $no_hp = mysqli_real_escape_string($koneksi, $register_data['no_hp']);
        $alamat = mysqli_real_escape_string($koneksi, $register_data['alamat']);
        $password = mysqli_real_escape_string($koneksi, $register_data['password']);
        $role = 'Customer';

        mysqli_query($koneksi, "INSERT INTO user (nama, email, no_hp, alamat, password, role) VALUES ('$nama', '$email', '$no_hp', '$alamat', '$password', '$role')");

        if (mysqli_affected_rows($koneksi) > 0) {
            unset($_SESSION['register_data']);
            echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'login.php';</script>";
            exit;
        } else {
            echo "<script>alert('Registrasi gagal disimpan ke database!');</script>";
        }
    } else {
        echo "<script>alert('Kode OTP salah!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Mey Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#fff1f2 0%,#ffe4e6 100%);min-height:100vh}.serif-font{font-family:'Playfair Display',serif}</style>
</head>
<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl overflow-hidden relative">
        <div class="w-full px-8 py-8 bg-white relative text-center">
            <div class="mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100"><i class="fas fa-envelope-circle-check text-rose-600 text-2xl"></i></div>
                <h1 class="text-3xl font-bold text-gray-900 serif-font">Verifikasi Email</h1>
                <p class="text-gray-500 mt-1 font-light text-sm">Masukkan kode OTP yang dikirim ke email: <b><?= htmlspecialchars($register_data['email']); ?></b></p>
            </div>
            <form action="" method="POST" class="space-y-4">
                <input type="text" name="otp" maxlength="6" required placeholder="______" class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-center tracking-[8px] font-bold text-lg">
                <button type="submit" name="verifikasi" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]">Verifikasi & Daftar</button>
            </form>
            <div class="mt-5 text-left"><a href="registrasi.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </div>
    </div>
</body>
</html>
