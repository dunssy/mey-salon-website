<?php
// Memulai session untuk menyimpan data login
session_start();

// Memanggil koneksi database
include "config/app.php";
global $koneksi;

// Jika tombol login ditekan
if (isset($_POST['login'])) {
    // Mengamankan input username
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);

    // Mengambil input password
    $password = $_POST['password'];

    // Mengecek username di database
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");

    // Jika username ditemukan
    if (mysqli_num_rows($query) > 0) {
        // Mengambil data user
        $user = mysqli_fetch_assoc($query);

        // Mengecek password biasa atau password hash
        if ($password == $user['password'] || password_verify($password, $user['password'])) {
            // Menyimpan data user ke session
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Mengarahkan user berdasarkan role
            if ($user['role'] == 'Administrator') {
                echo "<script>
                        alert('Login berhasil sebagai Administrator!');
                        window.location.href = 'admin/dashboard-admin.php';
                      </script>";
            } else {
                echo "<script>
                        alert('Login berhasil!');
                        window.location.href = 'user/booking.php';
                      </script>";
            }
        } else {
            // Menampilkan pesan jika password salah
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        // Menampilkan pesan jika username tidak ditemukan
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Mengatur karakter dan tampilan responsif -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mey Salon</title>

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

    <!-- Container utama halaman login -->
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
                        Premium Studio
                    </span>
                    <h2 class="text-5xl font-bold serif-font leading-tight">Elevate Your<br>Natural Beauty</h2>
                    <p class="text-rose-100/90 max-w-sm text-lg font-light leading-relaxed">
                        Nikmati pengalaman kecantikan kelas dunia dengan sentuhan personal dari para ahli kami.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bagian kanan untuk form login -->
        <div class="w-full md:w-1/2 lg:w-2/5 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white relative">

            <!-- Logo dan judul login -->
            <div class="mb-10 text-center md:text-left">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-rose-50 rounded-2xl mb-4 border border-rose-100">
                    <i class="fas fa-spa text-rose-600 text-3xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 serif-font">Mey Salon</h1>
                <p class="text-gray-500 mt-2 font-light">Selamat datang kembali, silakan masuk ke akun Anda.</p>
            </div>

            <!-- Form login user -->
            <form action="" method="POST" class="space-y-5">

                <!-- Input username -->
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Username</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="far fa-user"></i>
                        </span>
                        <input 
                            type="text" 
                            name="username"
                            required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                            placeholder="Masukkan username"
                        >
                    </div>
                </div>

                <!-- Input password -->
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input 
                            type="password" 
                            name="password"
                            required 
                            class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                            placeholder="Masukkan password"
                        >
                    </div>
                </div>

                <!-- Tombol submit login -->
                <button 
                    type="submit" 
                    name="login"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                >
                    Masuk Sekarang
                </button>
            </form>

            <!-- Link lupa password -->
            <div class="mt-4 text-center">
                <a href="lupa-password.php" class="text-rose-600 font-bold hover:underline ml-1">Lupa password?</a>
            </div>

            <!-- Link menuju registrasi -->
            <div class="mt-2 pt-2 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Belum punya akun?
                    <a href="registrasi.php" class="text-rose-600 font-bold hover:underline ml-1">Daftar di sini</a>
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