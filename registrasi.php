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

<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">

    <!-- Container utama halaman registrasi -->
    <div class="w-full max-w-2xl glass-effect rounded-[2rem] shadow-2xl overflow-hidden relative">

        <!-- Bagian form registrasi -->
        <div class="w-full px-8 py-6 bg-white relative">

            <!-- Logo dan judul registrasi -->
            <div class="mb-5 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100">
                    <i class="fas fa-spa text-rose-600 text-2xl"></i>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 serif-font">
                    Daftar Member
                </h1>

                <p class="text-gray-500 mt-1 font-light text-sm">
                    Buat akun baru untuk mulai booking layanan salon.
                </p>
            </div>

            <!-- Form registrasi user -->
            <form action="" method="POST" class="space-y-3">

                <!-- Input nama dan email -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="nama" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                            Nama Lengkap
                        </label>

                        <input 
                            type="text" 
                            name="nama"
                            id="nama"
                            required 
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Nama Anda"
                        >
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                            Email
                        </label>

                        <input 
                            type="email" 
                            name="email"
                            id="email"
                            required 
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="contoh@gmail.com"
                        >
                    </div>
                </div>

                <!-- Input nomor telepon -->
                <div class="space-y-1">
                    <label for="no_hp" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                        No. Telepon
                    </label>

                    <input 
                        type="tel" 
                        name="no_hp"
                        id="no_hp"
                        required 
                        class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                        placeholder="08xx xxxx xxxx"
                    >
                </div>

                <!-- Input alamat -->
                <div class="space-y-1">
                    <label for="alamat" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                        Alamat
                    </label>

                    <textarea 
                        name="alamat"
                        id="alamat"
                        rows="2"
                        required 
                        class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm resize-none" 
                        placeholder="Alamat lengkap"
                    ></textarea>
                </div>

                <!-- Input password dan konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="password" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                            Password
                        </label>

                        <input 
                            type="password" 
                            name="password"
                            id="password"
                            required 
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Password"
                        >
                    </div>

                    <div class="space-y-1">
                        <label for="konfirmasi_password" class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">
                            Konfirmasi
                        </label>

                        <input 
                            type="password" 
                            name="konfirmasi_password"
                            id="konfirmasi_password"
                            required 
                            class="block w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" 
                            placeholder="Ulangi password"
                        >
                    </div>
                </div>

                <!-- Tombol submit registrasi -->
                <button 
                    type="submit" 
                    name="registrasi"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 mt-2 transition active:scale-[0.98]"
                >
                    Daftar Member Baru
                </button>
            </form>

            <!-- Link menuju login -->
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Sudah punya akun?
                    <a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">
                        Masuk Sekarang
                    </a>
                </p>
            </div>

            <!-- Tombol kembali ke halaman utama -->
            <div class="mt-4 text-left">
                <a href="index.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Dekorasi icon gunting -->
            <div class="absolute bottom-4 right-4 opacity-10 pointer-events-none">
                <i class="fas fa-scissors text-5xl text-rose-900 rotate-45"></i>
            </div>
        </div>
    </div>
</body>
</html>