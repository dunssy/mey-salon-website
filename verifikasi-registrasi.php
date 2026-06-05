<?php
// Memulai session verifikasi registrasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek data registrasi sementara
if (!isset($_SESSION['register_data'])) {
    echo "<script>
            alert('Silakan registrasi terlebih dahulu!');
            window.location.href = 'registrasi.php';
          </script>";
    exit;
}

// Mengambil data registrasi dari session
$register_data = $_SESSION['register_data'];

// Memproses verifikasi OTP
if (isset($_POST['verifikasi'])) {
    // Mengambil input OTP
    $otp_input = trim($_POST['otp']);

    // Mengecek OTP expired
    if (time() > $register_data['expired']) {
        unset($_SESSION['register_data']);

        echo "<script>
                alert('Kode OTP sudah expired. Silakan registrasi ulang!');
                window.location.href = 'registrasi.php';
              </script>";
        exit;
    }

    // Mengecek OTP benar
    if ($otp_input === $register_data['otp']) {
        // Mengamankan data nama
        $nama = mysqli_real_escape_string($koneksi, $register_data['nama']);

        // Mengamankan data email
        $email = mysqli_real_escape_string($koneksi, $register_data['email']);

        // Mengamankan data nomor hp
        $no_hp = mysqli_real_escape_string($koneksi, $register_data['no_hp']);

        // Mengamankan data alamat
        $alamat = mysqli_real_escape_string($koneksi, $register_data['alamat']);

        // Mengamankan data password hash
        $password = mysqli_real_escape_string($koneksi, $register_data['password']);

        // Mengatur role customer
        $role = 'Customer';

        // Mengecek ulang email agar tidak double
        $cek_email = mysqli_query($koneksi, "SELECT email FROM user WHERE email = '$email' LIMIT 1");

        // Menolak jika email sudah digunakan
        if ($cek_email && mysqli_num_rows($cek_email) > 0) {
            unset($_SESSION['register_data']);

            echo "<script>
                    alert('Email sudah terdaftar. Silakan login!');
                    window.location.href = 'login.php';
                  </script>";
            exit;
        }

        // Menyimpan user baru
        mysqli_query(
            $koneksi,
            "INSERT INTO user 
                (nama, email, no_hp, alamat, password, role) 
             VALUES 
                ('$nama', '$email', '$no_hp', '$alamat', '$password', '$role')"
        );

        // Mengecek registrasi berhasil
        if (mysqli_affected_rows($koneksi) > 0) {
            unset($_SESSION['register_data']);

            echo "<script>
                    alert('Registrasi berhasil! Silakan login.');
                    window.location.href = 'login.php';
                  </script>";
            exit;
        }

        // Pesan gagal simpan
        echo "<script>alert('Registrasi gagal disimpan ke database!');</script>";
    } else {
        // Pesan OTP salah
        echo "<script>alert('Kode OTP salah!');</script>";
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
    <title>Verifikasi OTP - Mey Salon</title>

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
        .verify-bg {
            background:
                radial-gradient(circle at top left, rgba(244, 63, 94, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(251, 113, 133, 0.22), transparent 34%),
                linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        }
    </style>
</head>

<body class="verify-bg min-h-dvh overflow-y-auto">

    <!-- Wrapper halaman verifikasi -->
    <main class="min-h-dvh flex items-center justify-center px-4 py-6 sm:py-10">

        <!-- Card verifikasi -->
        <section class="w-full max-w-[420px] glass-effect rounded-[28px] sm:rounded-[32px] shadow-2xl overflow-hidden relative">

            <!-- Isi card -->
            <div class="w-full px-5 py-6 sm:px-8 sm:py-8 bg-white/95 relative text-center">

                <!-- Header verifikasi -->
                <div class="mb-6">

                    <!-- Logo -->
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shadow-sm mb-4">
                        <img 
                            src="layout/images/mey-salon.png" 
                            alt="Mey Salon Logo"
                            class="w-12 h-12 rounded-xl object-cover"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                        >

                        <!-- Icon cadangan -->
                        <i class="fa-solid fa-envelope-circle-check text-rose-600 text-2xl hidden"></i>
                    </div>

                    <!-- Judul halaman -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 serif-font leading-tight">
                        Verifikasi Email
                    </h1>

                    <!-- Deskripsi halaman -->
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        Masukkan kode OTP yang dikirim ke email Anda.
                    </p>
                </div>

                <!-- Info email -->
                <div class="mb-5 p-3 bg-rose-50 border border-rose-100 rounded-2xl">
                    <p class="text-xs text-gray-500">
                        Email tujuan:
                    </p>

                    <p class="text-sm font-bold text-rose-600 break-all">
                        <?= htmlspecialchars($register_data['email']); ?>
                    </p>
                </div>

                <!-- Form verifikasi -->
                <form action="" method="POST" class="space-y-4">

                    <!-- Input OTP -->
                    <div>
                        <label for="otp" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 text-left">
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
                        <p class="text-[11px] text-gray-400 mt-2 text-left">
                            Kode OTP berlaku selama 10 menit.
                        </p>
                    </div>

                    <!-- Tombol verifikasi -->
                    <button 
                        type="submit" 
                        name="verifikasi" 
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                    >
                        Verifikasi & Daftar
                    </button>
                </form>

                <!-- Link kirim ulang -->
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        OTP expired atau salah email?
                        <a href="registrasi.php" class="text-rose-600 font-bold hover:underline ml-1">
                            Registrasi Ulang
                        </a>
                    </p>
                </div>

                <!-- Tombol kembali -->
                <div class="mt-5 text-center">
                    <a href="registrasi.php" class="inline-flex items-center justify-center text-gray-400 hover:text-gray-600 transition text-sm">
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
</body>
</html>
