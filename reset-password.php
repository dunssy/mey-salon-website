<?php
// Memulai session reset password
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek data reset password
if (!isset($_SESSION['reset_password'])) {
    echo "<script>
            alert('Silakan masukkan email terlebih dahulu!');
            window.location.href = 'lupa-password.php';
          </script>";
    exit;
}

// Mengambil data reset dari session
$reset_data = $_SESSION['reset_password'];

// Memproses reset password
if (isset($_POST['reset_password'])) {
    // Mengambil input OTP
    $otp_input = trim($_POST['otp']);

    // Mengambil input password baru
    $password = $_POST['password'];

    // Mengambil input konfirmasi password
    $konfirmasi_password = $_POST['konfirmasi_password'];

    // Mengecek OTP expired
    if (time() > $reset_data['expired']) {
        unset($_SESSION['reset_password']);

        echo "<script>
                alert('Kode OTP sudah expired. Silakan kirim ulang OTP!');
                window.location.href = 'lupa-password.php';
              </script>";
        exit;
    }

    // Mengecek password sama
    if ($password !== $konfirmasi_password) {
        echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
    } elseif ($otp_input !== $reset_data['otp']) {
        // Mengecek OTP benar
        echo "<script>alert('Kode OTP salah!');</script>";
    } else {
        // Mengambil id user
        $id_user = (int) $reset_data['id_user'];

        // Hash password baru
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Mengamankan password hash
        $password_hash = mysqli_real_escape_string($koneksi, $password_hash);

        // Update password user
        mysqli_query(
            $koneksi,
            "UPDATE user 
             SET password = '$password_hash' 
             WHERE id_user = $id_user"
        );

        // Menghapus session reset password
        unset($_SESSION['reset_password']);

        // Redirect ke login
        echo "<script>
                alert('Password berhasil diubah! Silakan login.');
                window.location.href = 'login.php';
              </script>";
        exit;
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
    <title>Reset Password - Mey Salon</title>

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
        .reset-bg {
            background:
                radial-gradient(circle at top left, rgba(244, 63, 94, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(251, 113, 133, 0.22), transparent 34%),
                linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        }
    </style>
</head>

<body class="reset-bg min-h-dvh overflow-y-auto">

    <!-- Wrapper halaman reset password -->
    <main class="min-h-dvh flex items-center justify-center px-4 py-6 sm:py-10">

        <!-- Card reset password -->
        <section class="w-full max-w-[420px] glass-effect rounded-[28px] sm:rounded-[32px] shadow-2xl overflow-hidden relative">

            <!-- Isi card -->
            <div class="w-full px-5 py-6 sm:px-8 sm:py-8 bg-white/95 relative">

                <!-- Header reset password -->
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
                        <i class="fa-solid fa-key text-rose-600 text-2xl hidden"></i>
                    </div>

                    <!-- Judul halaman -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 serif-font leading-tight">
                        Reset Password
                    </h1>

                    <!-- Deskripsi halaman -->
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        Masukkan kode OTP dari email dan password baru Anda.
                    </p>
                </div>

                <!-- Info email tujuan -->
                <div class="mb-5 p-3 bg-rose-50 border border-rose-100 rounded-2xl text-center">
                    <p class="text-xs text-gray-500">
                        OTP dikirim ke:
                    </p>

                    <p class="text-sm font-bold text-rose-600 break-all">
                        <?= htmlspecialchars($reset_data['email']); ?>
                    </p>
                </div>

                <!-- Form reset password -->
                <form action="" method="POST" class="space-y-4">

                    <!-- Input OTP -->
                    <div>
                        <label for="otp" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Kode OTP
                        </label>

                        <div class="relative">
                            <!-- Icon OTP -->
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </span>

                            <!-- Field OTP -->
                            <input 
                                type="text" 
                                name="otp" 
                                id="otp" 
                                maxlength="6" 
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                required 
                                placeholder="______" 
                                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-center tracking-[8px] font-bold text-lg focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all"
                            >
                        </div>

                        <!-- Info OTP -->
                        <p class="text-[11px] text-gray-400 mt-2">
                            Masukkan 6 digit kode OTP yang dikirim ke email Anda.
                        </p>
                    </div>

                    <!-- Input password baru -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                            Password Baru
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
                                placeholder="Masukkan password baru" 
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
                            Konfirmasi Password
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
                                placeholder="Ulangi password baru" 
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

                    <!-- Tombol ubah password -->
                    <button 
                        type="submit" 
                        name="reset_password" 
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                    >
                        Ubah Password
                    </button>
                </form>

                <!-- Link kirim ulang -->
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Tidak menerima OTP?
                        <a href="lupa-password.php" class="text-rose-600 font-bold hover:underline ml-1">
                            Kirim Ulang
                        </a>
                    </p>
                </div>

                <!-- Tombol kembali -->
                <div class="mt-5 text-center">
                    <a href="lupa-password.php" class="inline-flex items-center justify-center text-gray-400 hover:text-gray-600 transition text-sm">
                        <i class="fa-solid fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>

                <!-- Dekorasi bawah -->
                <div class="absolute bottom-4 right-4 opacity-10 pointer-events-none">
                    <i class="fa-solid fa-scissors text-5xl text-rose-900 rotate-45"></i>
                </div>
            </div>
        </section>
    </main>

    <!-- Script reset password -->
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
