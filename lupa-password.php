<?php
session_start();
include "config/app.php";
include "config/email.php";
global $koneksi;

if (isset($_POST['kirim'])) {
    $email = mysqli_real_escape_string($koneksi, strip_tags($_POST['email']));
    $query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");

    if (mysqli_num_rows($query_user) > 0) {
        $user = mysqli_fetch_assoc($query_user);
        $otp = random_int(100000, 999999);

        $_SESSION['reset_password'] = [
            'id_user' => $user['id_user'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'otp' => (string) $otp,
            'expired' => time() + 600
        ];

        if (kirimOtpEmail($user['email'], $user['nama'], $otp, 'Kode OTP Reset Password Mey Salon')) {
            echo "<script>alert('Kode OTP berhasil dikirim ke email Anda!'); window.location.href = 'reset-password.php';</script>";
            exit;
        } else {
            echo "<script>alert('Gagal mengirim OTP. Cek konfigurasi SMTP email.');</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Mey Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#fff1f2 0%,#ffe4e6 100%);min-height:100vh}.serif-font{font-family:'Playfair Display',serif}.glass-effect{background:rgba(255,255,255,.92);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.5)}</style>
</head>
<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">
    <div class="w-full max-w-md glass-effect rounded-[2rem] shadow-2xl overflow-hidden relative">
        <div class="w-full px-8 py-7 bg-white relative">
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100"><i class="far fa-envelope text-rose-600 text-2xl"></i></div>
                <h1 class="text-3xl font-bold text-gray-900 serif-font">Lupa Password?</h1>
                <p class="text-gray-500 mt-1 font-light text-sm">Masukkan email akun Anda untuk menerima kode OTP.</p>
            </div>
            <form action="" method="POST" class="space-y-4">
                <div class="space-y-1">
                    <label for="email" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Email</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400"><i class="far fa-envelope"></i></span>
                        <input type="email" name="email" id="email" required placeholder="Masukkan email Anda" class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                    </div>
                </div>
                <button type="submit" name="kirim" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]">Kirim Kode OTP</button>
            </form>
            <div class="mt-4 pt-4 border-t border-gray-100 text-center"><p class="text-gray-500 text-sm">Sudah ingat password?<a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">Masuk Sekarang</a></p></div>
            <div class="mt-5 text-left"><a href="login.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali</a></div>
        </div>
    </div>
</body>
</html>
