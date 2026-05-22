<?php
// Memulai session untuk menyimpan email reset
session_start();

// Memanggil koneksi database
include "config/app.php";
global $koneksi;

// Memanggil PHPMailer
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

// require "vendor/autoload.php";

// // Mengirim OTP ke email
// function kirimOtpEmail($email, $otp)
// {
//     $mail = new PHPMailer(true);

//     try {
//         // Konfigurasi SMTP Gmail
//         $mail->isSMTP();
//         $mail->Host       = "smtp.gmail.com";
//         $mail->SMTPAuth   = true;
//         $mail->Username   = "EMAIL_KAMU@gmail.com";
//         $mail->Password   = "APP_PASSWORD_GMAIL_KAMU";
//         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
//         $mail->Port       = 587;

//         // Pengirim dan penerima
//         $mail->setFrom("EMAIL_KAMU@gmail.com", "Mey Salon");
//         $mail->addAddress($email);

//         // Isi email
//         $mail->isHTML(true);
//         $mail->Subject = "Kode OTP Reset Password Mey Salon";
//         $mail->Body = "
//             <div style='font-family: Arial, sans-serif; padding: 20px;'>
//                 <h2 style='color: #e11d48;'>Mey Salon</h2>
//                 <p>Kode OTP untuk reset password Anda adalah:</p>
//                 <h1 style='letter-spacing: 6px; color: #111827;'>$otp</h1>
//                 <p>Kode ini berlaku selama <b>10 menit</b>.</p>
//                 <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
//             </div>
//         ";

//         return $mail->send();
//     } catch (Exception $e) {
//         return false;
//     }
// }

// Mengecek tombol kirim OTP
// if (isset($_POST['kirim'])) {
//     $email = mysqli_real_escape_string($koneksi, $_POST['email']);

//     // Mengecek email user
//     $query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");

//     if (mysqli_num_rows($query_user) > 0) {
//         // Membuat OTP 6 digit
//         $otp = random_int(100000, 999999);

//         // Mengatur masa aktif OTP 10 menit
//         $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

//         // Menonaktifkan OTP lama
//         mysqli_query($koneksi, "UPDATE password_reset_otp SET is_used = 1 WHERE email = '$email'");

//         // Menyimpan OTP baru
//         $query_otp = "INSERT INTO password_reset_otp 
//                         (email, otp, expires_at, is_used) 
//                       VALUES 
//                         ('$email', '$otp', '$expires_at', 0)";

//         mysqli_query($koneksi, $query_otp);

//         // Mengirim OTP ke email
//         if (kirimOtpEmail($email, $otp)) {
//             $_SESSION['reset_email'] = $email;

//             echo "<script>
//                     alert('Kode OTP berhasil dikirim ke email Anda!');
//                     window.location.href = 'reset-password.php';
//                   </script>";
//         } else {
//             echo "<script>alert('OTP gagal dikirim. Periksa konfigurasi email SMTP!');</script>";
//         }
//     } else {
//         echo "<script>alert('Email tidak terdaftar!');</script>";
//     }
// }
// ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Mengatur karakter dan tampilan responsive -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Mey Salon</title>

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

        <!-- Form lupa password -->
        <div class="w-full px-8 py-7 bg-white relative">

            <!-- Logo dan judul -->
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100">
                    <i class="fas fa-spa text-rose-600 text-2xl"></i>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 serif-font">
                    Lupa Password?
                </h1>

                <p class="text-gray-500 mt-1 font-light text-sm">
                    Masukkan email akun Anda untuk menerima kode OTP.
                </p>
            </div>

            <!-- Form kirim OTP -->
            <form action="" method="POST" class="space-y-4">

                <!-- Input email -->
                <div class="space-y-1">
                    <label for="email" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">
                        Email
                    </label>

                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="far fa-envelope"></i>
                        </span>

                        <input 
                            type="email" 
                            name="email"
                            id="email"
                            required 
                            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                            placeholder="Masukkan email Anda"
                        >
                    </div>
                </div>

                <!-- Tombol kirim -->
                <button 
                    type="submit" 
                    name="kirim"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                >
                    Kirim Kode OTP
                </button>
            </form>

            <!-- Link kembali login -->
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Sudah ingat password?
                    <a href="login.php" class="text-rose-600 font-bold hover:underline ml-1">
                        Masuk Sekarang
                    </a>
                </p>
            </div>

            <!-- Tombol kembali -->
            <div class="mt-5 text-left">
                <a href="login.php" class="inline-flex items-center text-gray-400 hover:text-gray-600 transition text-sm">
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