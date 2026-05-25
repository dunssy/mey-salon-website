<?php
// Memulai session untuk menyimpan data registrasi sementara
session_start();

// Memanggil koneksi dan fungsi email
include "config/app.php";
include "config/email.php";

// Menggunakan koneksi database
global $koneksi;

// Memproses form registrasi
if (isset($_POST['registrasi'])) {
    $nama = mysqli_real_escape_string($koneksi, strip_tags($_POST['nama']));
    $email = mysqli_real_escape_string($koneksi, strip_tags($_POST['email']));
    $no_hp = mysqli_real_escape_string($koneksi, strip_tags($_POST['no_hp']));
    $alamat = mysqli_real_escape_string($koneksi, strip_tags($_POST['alamat']));
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

//// Mengecek password dan konfirmasi password
    if ($password !== $konfirmasi_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
    } else {
        // Mengecek email sudah terdaftar
        $cek_email = mysqli_query($koneksi, "SELECT email FROM user WHERE email = '$email'");

        if (mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar!');</script>";
        } else {
            // Membuat OTP 6 digit
            $otp = random_int(100000, 999999);

            // Menyimpan data registrasi sementara ke session
            $_SESSION['register_data'] = [
                'nama' => $nama,
                'email' => $email,
                'no_hp' => $no_hp,
                'alamat' => $alamat,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'otp' => (string) $otp,
                'expired' => time() + 600
            ];

            // Mengirim OTP ke email
            if (kirimOtpEmail($email, $nama, $otp, 'Kode OTP Registrasi Mey Salon')) {
                echo "<script>
                        alert('Kode OTP berhasil dikirim ke email Anda!');
                        window.location.href = 'verifikasi-registrasi.php';
                      </script>";
                exit;
            } else {
                echo "<script>alert('Gagal mengirim OTP. Cek konfigurasi SMTP email.');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Mey Salon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); min-height: 100vh; }
        .serif-font { font-family: 'Playfair Display', serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.5); }
    </style>
</head>
<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">
    <div class="w-full max-w-2xl glass-effect rounded-[2rem] shadow-2xl overflow-hidden relative">
        <div class="w-full px-8 py-6 bg-white relative">
            <div class="mb-5 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100"><i class="fas fa-spa text-rose-600 text-2xl"></i></div>
                <h1 class="text-3xl font-bold text-gray-900 serif-font">Daftar Member</h1>
                <p class="text-gray-500 mt-1 font-light text-sm">Buat akun dan verifikasi email dengan kode OTP.</p>
            </div>
            <form action="" method="POST" class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="nama" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" required placeholder="Nama Anda" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm">
                    </div>
                    <div class="space-y-1">
                        <label for="email" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Email</label>
                        <input type="email" name="email" id="email" required placeholder="contoh@gmail.com" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm">
                    </div>
                </div>
                <div class="space-y-1">
                    <label for="no_hp" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">No. Telepon</label>
                    <input type="tel" name="no_hp" id="no_hp" required placeholder="08xx xxxx xxxx" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm">
                </div>
                <div class="space-y-1">
                    <label for="alamat" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2" required placeholder="Alamat lengkap" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm resize-none"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="password" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                        <input type="password" name="password" id="password" required placeholder="Password" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm">
                    </div>
                    <div class="space-y-1">
                        <label for="konfirmasi_password" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Konfirmasi</label>
                        <input type="password" name="konfirmasi_password" id="konfirmasi_password" required placeholder="Ulangi password" class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm">
                    </div>
                </div>
                <button type="submit" name="registrasi" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 mt-2 transition active:scale-[0.98]">Kirim OTP Registrasi</button>
            </form>
            <div class="mt-4 pt-4 border-t border-gray-100 text-center"><p class="text-gray-500 text-sm">Sudah punya akun?<a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">Masuk Sekarang</a></p></div>
            <div class="mt-4 text-left"><a href="index.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda</a></div>
        </div>
    </div>
</body>
</html>
