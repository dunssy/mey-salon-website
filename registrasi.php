<?php
// Memulai session registrasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "config/app.php";

// Memanggil fungsi email OTP
include "config/email.php";

// Menggunakan koneksi database
global $koneksi;

// Memproses registrasi
if (isset($_POST['registrasi'])) {
    // Mengambil input nama
    $nama = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['nama'])));

    // Mengambil input email
    $email = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['email'])));

    // Mengambil input nomor hp
    $no_hp = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['no_hp'])));

    // Mengambil input alamat
    $alamat = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['alamat'])));

    // Mengambil input password
    $password = $_POST['password'];

    // Mengambil input konfirmasi password
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Mengecek password sama
    if ($password !== $konfirmasi_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
    } else {
        // Mengecek email sudah terdaftar
        $cek_email = mysqli_query($koneksi, "SELECT email FROM user WHERE email = '$email' LIMIT 1");

        // Menolak email yang sudah ada
        if ($cek_email && mysqli_num_rows($cek_email) > 0) {
            echo "<script>alert('Email sudah terdaftar!');</script>";
        } else {
            // Membuat kode OTP 6 digit
            $otp = random_int(100000, 999999);

            // Menyimpan data registrasi sementara
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
            }

            // Pesan gagal kirim OTP
            echo "<script>alert('Gagal mengirim OTP. Cek konfigurasi SMTP email.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Metadata dasar -->
    <meta charset="UTF-8">

    <!-- Tampilan responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Judul halaman -->
    <title>Registrasi - Mey Salon</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Style tambahan -->
    <style>
        /* Font utama halaman */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Font judul */
        .serif-font {
            font-family: 'Playfair Display', serif;
        }

        /* Efek kartu kaca */
        .glass-effect {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.65);
        }

        /* Background halaman */
        .register-bg {
            background:
                radial-gradient(circle at top left, rgba(244, 63, 94, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(251, 113, 133, 0.22), transparent 34%),
                linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        }
    </style>
</head>

<body class="register-bg min-h-dvh overflow-y-auto">

    <!-- Wrapper halaman registrasi -->
    <main class="min-h-dvh flex items-center justify-center px-4 py-6 sm:py-10">

        <!-- Card registrasi -->
        <section class="w-full max-w-[680px] glass-effect rounded-[28px] sm:rounded-[32px] shadow-2xl overflow-hidden relative">

            <!-- Isi card registrasi -->
            <div class="w-full px-5 py-6 sm:px-8 sm:py-8 bg-white/95 relative">

                <!-- Header registrasi -->
                <div class="mb-6 text-center">

                    <!-- Logo -->
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shadow-sm mb-4">
                        <img 
                            src="layout/images/mey-salon.png" 
                            alt="Mey Salon Logo"
                            class="w-12 h-12 rounded-xl object-cover"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                        >

                        <!-- Icon cadangan -->
                        <i class="fa-solid fa-spa text-rose-600 text-2xl hidden"></i>
                    </div>

                    <!-- Judul registrasi -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 serif-font leading-tight">
                        Daftar Member
                    </h1>

                    <!-- Deskripsi registrasi -->
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        Buat akun dan verifikasi email dengan kode OTP.
                    </p>
                </div>

                <!-- Form registrasi -->
                <form action="" method="POST" class="space-y-4">

                    <!-- Grid nama dan email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Input nama -->
                        <div>
                            <label for="nama" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                Nama Lengkap
                            </label>

                            <div class="relative">
                                <!-- Icon user -->
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <i class="fa-solid fa-user text-sm"></i>
                                </span>

                                <!-- Field nama -->
                                <input 
                                    type="text" 
                                    name="nama" 
                                    id="nama" 
                                    required 
                                    autocomplete="name"
                                    placeholder="Nama Anda" 
                                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                                >
                            </div>
                        </div>

                        <!-- Input email -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                Email
                            </label>

                            <div class="relative">
                                <!-- Icon email -->
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <i class="fa-solid fa-envelope text-sm"></i>
                                </span>

                                <!-- Field email -->
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    required 
                                    autocomplete="email"
                                    placeholder="contoh@gmail.com" 
                                    class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Input nomor hp -->
                    <div>
                        <label for="no_hp" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            No. Telepon
                        </label>

                        <div class="relative">
                            <!-- Icon telepon -->
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-phone text-sm"></i>
                            </span>

                            <!-- Field nomor hp -->
                            <input 
                                type="tel" 
                                name="no_hp" 
                                id="no_hp" 
                                required 
                                autocomplete="tel"
                                placeholder="08xx xxxx xxxx" 
                                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                            >
                        </div>
                    </div>

                    <!-- Input alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Alamat
                        </label>

                        <div class="relative">
                            <!-- Icon alamat -->
                            <span class="absolute top-3.5 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-location-dot text-sm"></i>
                            </span>

                            <!-- Field alamat -->
                            <textarea 
                                name="alamat" 
                                id="alamat" 
                                rows="3" 
                                required 
                                autocomplete="street-address"
                                placeholder="Alamat lengkap" 
                                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all resize-none"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Grid password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Input password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                Password
                            </label>

                            <div class="relative">
                                <!-- Icon password -->
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>

                                <!-- Field password -->
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Password" 
                                    class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                                >

                                <!-- Tombol lihat password -->
                                <button 
                                    type="button"
                                    onclick="togglePassword('password', 'password-icon')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-rose-600 transition"
                                >
                                    <i id="password-icon" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Input konfirmasi password -->
                        <div>
                            <label for="konfirmasi_password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                Konfirmasi
                            </label>

                            <div class="relative">
                                <!-- Icon konfirmasi -->
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>

                                <!-- Field konfirmasi -->
                                <input 
                                    type="password" 
                                    name="konfirmasi_password" 
                                    id="konfirmasi_password" 
                                    required 
                                    autocomplete="new-password"
                                    placeholder="Ulangi password" 
                                    class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                                >

                                <!-- Tombol lihat konfirmasi -->
                                <button 
                                    type="button"
                                    onclick="togglePassword('konfirmasi_password', 'konfirmasi-icon')"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-rose-600 transition"
                                >
                                    <i id="konfirmasi-icon" class="fa-solid fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol registrasi -->
                    <button 
                        type="submit" 
                        name="registrasi" 
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 mt-2 transition active:scale-[0.98]"
                    >
                        Kirim OTP Registrasi
                    </button>
                </form>

                <!-- Link login -->
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Sudah punya akun?
                        <a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">
                            Masuk Sekarang
                        </a>
                    </p>
                </div>

                <!-- Tombol kembali -->
                <div class="mt-5 text-center">
                    <a href="index.php" class="inline-flex items-center justify-center text-gray-400 hover:text-gray-600 transition text-sm">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali ke Beranda
                    </a>
                </div>

                <!-- Dekorasi bawah -->
                <div class="absolute bottom-4 right-4 opacity-10 pointer-events-none">
                    <i class="fa-solid fa-scissors text-5xl text-rose-900 rotate-45"></i>
                </div>
            </div>
        </section>
    </main>

    <!-- Script registrasi -->
    <script>
        // Menampilkan atau menyembunyikan password
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);

            if (!passwordInput || !passwordIcon) return;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
