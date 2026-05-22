<?php
// Memulai session
session_start();

// Memanggil koneksi database
include "config/app.php";
global $koneksi;

// Mengecek email reset tersedia
// if (!isset($_SESSION['reset_email'])) {
//     echo "<script>
//             alert('Silakan masukkan email terlebih dahulu!');
//             window.location.href = 'lupa-password.php';
//           </script>";
//     exit;
// }

// // Mengambil email dari session
// $email = mysqli_real_escape_string($koneksi, $_SESSION['reset_email']);

// // Memproses reset password
// if (isset($_POST['reset_password'])) {
//     $otp = mysqli_real_escape_string($koneksi, $_POST['otp']);
//     $password = $_POST['password'];
//     $konfirmasi_password = $_POST['konfirmasi_password'];

//     // Mengecek password sama
//     if ($password != $konfirmasi_password) {
//         echo "<script>alert('Password dan konfirmasi password tidak sama!');</script>";
//     } else {
//         // Mengecek OTP valid
//         $query_otp = mysqli_query(
//             $koneksi,
//             "SELECT * FROM password_reset_otp 
//              WHERE email = '$email' 
//              AND otp = '$otp' 
//              AND is_used = 0 
//              AND expires_at >= NOW()
//              ORDER BY id_otp DESC 
//              LIMIT 1"
//         );

//         if (mysqli_num_rows($query_otp) > 0) {
//             // Hash password baru
//             $password_hash = password_hash($password, PASSWORD_DEFAULT);

//             // Update password user
//             mysqli_query($koneksi, "UPDATE user SET password = '$password_hash' WHERE email = '$email'");

//             // Menandai OTP sudah digunakan
//             mysqli_query($koneksi, "UPDATE password_reset_otp SET is_used = 1 WHERE email = '$email' AND otp = '$otp'");

//             // Menghapus session reset
//             unset($_SESSION['reset_email']);

//             echo "<script>
//                     alert('Password berhasil diubah! Silakan login.');
//                     window.location.href = 'login.php';
//                   </script>";
//             exit;
//         } else {
//             echo "<script>alert('Kode OTP salah atau sudah expired!');</script>";
//         }
//     }
// }
// ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Mengatur karakter dan tampilan responsive -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Mey Salon</title>

    <!-- Memanggil Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memanggil font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">

    <!-- Memanggil icon Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Mengatur style tambahan -->
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">

    <!-- Container utama -->
    <div class="w-full max-w-md glass-effect rounded-[2rem] shadow-2xl overflow-hidden relative">

        <!-- Form reset password -->
        <div class="w-full px-8 py-7 bg-white relative">

            <!-- Logo dan judul -->
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100">
                    <i class="fas fa-key text-rose-600 text-2xl"></i>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 serif-font">
                    Reset Password
                </h1>

                <p class="text-gray-500 mt-1 font-light text-sm">
                    Masukkan kode OTP dan password baru Anda.
                </p>
            </div>

            <!-- Form reset -->
            <form action="" method="POST" class="space-y-4">

                <!-- Input OTP -->
                <div class="space-y-1">
                    <label for="otp" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">
                        Kode OTP
                    </label>

                    <input 
                        type="text" 
                        name="otp"
                        id="otp"
                        maxlength="6"
                        required 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-center tracking-[8px] font-bold text-lg" 
                        placeholder="______"
                    >
                </div>

                <!-- Input password baru -->
                <div class="space-y-1">
                    <label for="password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">
                        Password Baru
                    </label>

                    <input 
                        type="password" 
                        name="password"
                        id="password"
                        required 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                        placeholder="Masukkan password baru"
                    >
                </div>

                <!-- Input konfirmasi password -->
                <div class="space-y-1">
                    <label for="konfirmasi_password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">
                        Konfirmasi Password
                    </label>

                    <input 
                        type="password" 
                        name="konfirmasi_password"
                        id="konfirmasi_password"
                        required 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                        placeholder="Ulangi password baru"
                    >
                </div>

                <!-- Tombol reset -->
                <button 
                    type="submit" 
                    name="reset_password"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                >
                    Ubah Password
                </button>
            </form>

            <!-- Tombol kembali -->
            <div class="mt-5 text-left">
                <a href="lupa-password.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
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