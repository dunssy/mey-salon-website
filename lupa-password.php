<?php
// Memulai session reset password
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "config/app.php";

// Memanggil fungsi email OTP
include "config/email.php";

// Menggunakan koneksi database
global $koneksi;

// Memproses kirim OTP
if (isset($_POST['kirim'])) {
    // Mengambil input email
    $email = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['email'])));

    // Mengambil data user berdasarkan email
    $query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email' LIMIT 1");

    // Mengecek email terdaftar
    if ($query_user && mysqli_num_rows($query_user) > 0) {
        // Mengubah data user menjadi array
        $user = mysqli_fetch_assoc($query_user);

        // Membuat kode OTP 6 digit
        $otp = random_int(100000, 999999);

        // Menyimpan data reset password sementara
        $_SESSION['reset_password'] = [
            'id_user' => $user['id_user'],
            'nama' => $user['nama'],
            'email' => $user['email'],
            'otp' => (string) $otp,
            'expired' => time() + 600
        ];

        // Mengirim OTP ke email user
        if (kirimOtpEmail($user['email'], $user['nama'], $otp, 'Kode OTP Reset Password Mey Salon')) {
            echo "<script>
                    alert('Kode OTP berhasil dikirim ke email Anda!');
                    window.location.href = 'reset-password.php';
                  </script>";
            exit;
        }

        // Pesan gagal kirim OTP
        echo "<script>alert('Gagal mengirim OTP. Cek konfigurasi SMTP email.');</script>";
    } else {
        // Pesan email tidak terdaftar
        echo "<script>alert('Email tidak terdaftar!');</script>";
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
    <title>Lupa Password - Mey Salon</title>

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
        .forgot-bg {
            background:
                radial-gradient(circle at top left, rgba(244, 63, 94, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(251, 113, 133, 0.22), transparent 34%),
                linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        }
    </style>
</head>

<body class="forgot-bg min-h-dvh overflow-y-auto">

    <!-- Wrapper halaman lupa password -->
    <main class="min-h-dvh flex items-center justify-center px-4 py-6 sm:py-10">

        <!-- Card lupa password -->
        <section class="w-full max-w-[420px] glass-effect rounded-[28px] sm:rounded-[32px] shadow-2xl overflow-hidden relative">

            <!-- Isi card -->
            <div class="w-full px-5 py-6 sm:px-8 sm:py-8 bg-white/95 relative">

                <!-- Header lupa password -->
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
                        <i class="fa-regular fa-envelope text-rose-600 text-2xl hidden"></i>
                    </div>

                    <!-- Judul halaman -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 serif-font leading-tight">
                        Lupa Password?
                    </h1>

                    <!-- Deskripsi halaman -->
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        Masukkan email akun Anda untuk menerima kode OTP reset password.
                    </p>
                </div>

                <!-- Form kirim OTP -->
                <form action="" method="POST" class="space-y-4">

                    <!-- Input email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Email
                        </label>

                        <div class="relative">
                            <!-- Icon email -->
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </span>

                            <!-- Field email -->
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                required 
                                autocomplete="email"
                                placeholder="Masukkan email Anda" 
                                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                            >
                        </div>

                        <!-- Info OTP -->
                        <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">
                            Kode OTP berlaku selama 10 menit setelah dikirim.
                        </p>
                    </div>

                    <!-- Tombol kirim OTP -->
                    <button 
                        type="submit" 
                        name="kirim" 
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                    >
                        Kirim Kode OTP
                    </button>
                </form>

                <!-- Link login -->
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Sudah ingat password?
                        <a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">
                            Masuk Sekarang
                        </a>
                    </p>
                </div>

                <!-- Tombol kembali -->
                <div class="mt-5 text-center">
                    <a href="login.php" class="inline-flex items-center justify-center text-gray-400 hover:text-gray-600 transition text-sm">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali ke Login
                    </a>
                </div>

                <!-- Dekorasi bawah -->
                <div class="absolute bottom-4 right-4 opacity-10 pointer-events-none">
                    <i class="fa-solid fa-scissors text-5xl text-rose-900 rotate-45"></i>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
