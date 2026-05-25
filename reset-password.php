<?php
session_start();
include "config/app.php";
global $koneksi;

if (!isset($_SESSION['reset_password'])) {
    echo "<script>alert('Silakan masukkan email terlebih dahulu!'); window.location.href = 'lupa-password.php';</script>";
    exit;
}

$reset_data = $_SESSION['reset_password'];

if (isset($_POST['reset_password'])) {
    $otp_input = trim($_POST['otp']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    if (time() > $reset_data['expired']) {
        unset($_SESSION['reset_password']);
        echo "<script>alert('Kode OTP sudah expired. Silakan kirim ulang OTP!'); window.location.href = 'lupa-password.php';</script>";
        exit;
    }

    if ($password !== $konfirmasi_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
    } elseif ($otp_input !== $reset_data['otp']) {
        echo "<script>alert('Kode OTP salah!');</script>";
    } else {
        $id_user = (int) $reset_data['id_user'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE user SET password = '$password_hash' WHERE id_user = $id_user");
        unset($_SESSION['reset_password']);
        echo "<script>alert('Password berhasil diubah! Silakan login.'); window.location.href = 'login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Mey Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#fff1f2 0%,#ffe4e6 100%);min-height:100vh}.serif-font{font-family:'Playfair Display',serif}</style>
</head>
<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-[2rem] shadow-2xl overflow-hidden relative">
        <div class="w-full px-8 py-7 bg-white relative">
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100"><i class="fas fa-key text-rose-600 text-2xl"></i></div>
                <h1 class="text-3xl font-bold text-gray-900 serif-font">Reset Password</h1>
                <p class="text-gray-500 mt-1 font-light text-sm">Masukkan kode OTP dari email dan password baru.</p>
            </div>
            <form action="" method="POST" class="space-y-4">
                <div><label for="otp" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Kode OTP</label><input type="text" name="otp" id="otp" maxlength="6" required placeholder="______" class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-center tracking-[8px] font-bold text-lg"></div>
                <div><label for="password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Password Baru</label><input type="password" name="password" id="password" required placeholder="Masukkan password baru" class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"></div>
                <div><label for="konfirmasi_password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Konfirmasi Password</label><input type="password" name="konfirmasi_password" id="konfirmasi_password" required placeholder="Ulangi password baru" class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"></div>
                <button type="submit" name="reset_password" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]">Ubah Password</button>
            </form>
            <div class="mt-5 text-left"><a href="lupa-password.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </div>
    </div>
</body>
</html>
