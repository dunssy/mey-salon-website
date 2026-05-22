<?php
// Memanggil koneksi database
include "config/app.php";
global $koneksi;

// Jika tombol registrasi ditekan
if (isset($_POST['registrasi'])) {
    // Mengamankan input dari form
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $password = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Mengecek apakah password dan konfirmasi sama
    if ($password != $konfirmasi_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
    } else {
            // Mengubah password menjadi hash agar lebih aman
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Menyimpan user baru dengan role Customer
            $query = "INSERT INTO user 
                      (nama, no_hp, alamat, email, role, password) 
                      VALUES 
                      ('$nama', '$no_hp', '$alamat', '$email', 'Customer', '$password_hash')";

            // Menjalankan query registrasi
            mysqli_query($koneksi, $query);

            // Mengecek apakah data berhasil disimpan
            if (mysqli_affected_rows($koneksi) > 0) {
                echo "<script>
                        alert('Registrasi berhasil, silakan login!');
                        window.location.href = 'login.php';
                      </script>";
            } else {
                echo "<script>alert('Registrasi gagal!');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Mengatur karakter dan tampilan responsif -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Mey Salon</title>

    <!-- Memanggil Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memanggil font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    <!-- Memanggil icon Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Mengatur style tambahan halaman -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            min-height: 100vh;
        }

        .serif-font {
            font-family: 'Playfair Display', serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>

<body class="flex items-center justify-center p-4 md:p-6 lg:p-12">

    <!-- Container utama halaman registrasi -->
    <div class="w-full max-w-6xl glass-effect rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row min-h-[650px]">

        <!-- Bagian kiri untuk gambar branding -->
        <div class="relative w-full md:w-1/2 lg:w-3/5 hidden md:block overflow-hidden">
            <img 
                src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&q=80&w=1200" 
                alt="Mey Salon Experience" 
                class="absolute inset-0 w-full h-full object-cover"
            >

            <!-- Overlay teks pada gambar -->
            <div class="absolute inset-0 bg-gradient-to-tr from-rose-950/80 via-rose-900/20 to-transparent flex flex-col justify-end p-12 text-white">
                <div class="space-y-4">
                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold tracking-widest uppercase border border-white/30">
                        Mey Salon
                    </span>
                    <h2 class="text-5xl font-bold serif-font leading-tight">Be Part of Us</h2>
                    <p class="text-rose-100/90 max-w-sm text-lg font-light leading-relaxed">
                        Daftar sebagai member untuk menikmati layanan kecantikan terbaik dari Mey Salon.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bagian kanan untuk form registrasi -->
        <div class="w-full md:w-1/2 lg:w-2/5 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white relative">

            <!-- Logo dan judul registrasi -->
            <div class="mb-8 text-center md:text-left">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-rose-50 rounded-2xl mb-4 border border-rose-100">
                    <i class="fas fa-spa text-rose-600 text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 serif-font">Daftar Member</h1>
                <p class="text-gray-500 mt-2 font-light">Buat akun baru untuk mulai booking layanan salon.</p>
            </div>

            <!-- Form registrasi user -->
            <form action="" method="POST" class="space-y-4">

                <!-- Input nama dan email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                        <input 
                            type="text" 
                            name="nama"
                            required 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Nama Anda"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Email</label>
                        <input 
                            type="email" 
                            name="email"
                            required 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Contoh@gmail.com"
                        >
                    </div>
                </div>

                <!-- Input nomor telepon -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">No. Telepon</label>
                    <input 
                        type="tel" 
                        name="no_hp"
                        required 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                        placeholder="08xx xxxx xxxx"
                    >
                </div>

                <!-- Input alamat -->
                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Alamat</label>
                    <input 
                        type="text" 
                        name="alamat"
                        required 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                        placeholder="Alamat lengkap"
                    >
                </div>

                <!-- Input password dan konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                        <input 
                            type="password" 
                            name="password"
                            required 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Password"
                        >
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Konfirmasi</label>
                        <input 
                            type="password" 
                            name="konfirmasi_password"
                            required 
                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Ulangi password"
                        >
                    </div>
                </div>

                <!-- Tombol submit registrasi -->
                <button 
                    type="submit" 
                    name="registrasi"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-4 rounded-2xl shadow-xl shadow-rose-200 mt-2 transition active:scale-[0.98]"
                >
                    Daftar Member Baru
                </button>
            </form>

            <!-- Link menuju login -->
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Sudah punya akun?
                    <a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">Masuk Sekarang</a>
                </p>
            </div>

            <!-- Tombol kembali ke halaman utama -->
            <div class="absolute bottom-6 left-8">
                <a href="index.html" class="text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali ke Beranda
                </a>
            </div>

            <!-- Dekorasi icon gunting -->
            <div class="absolute bottom-4 right-4 opacity-10 pointer-events-none">
                <i class="fas fa-scissors text-6xl text-rose-900 rotate-45"></i>
            </div>
        </div>
    </div>
</body>
</html>