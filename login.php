<?php
// Memulai session login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Memproses login
if (isset($_POST['login'])) {
    // Mengambil input email
    $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));

    // Mengambil input password
    $password = $_POST['password'];

    // Mengambil user berdasarkan email
    $query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email' LIMIT 1");

    // Mengecek email ditemukan
    if ($query_user && mysqli_num_rows($query_user) > 0) {
        // Mengubah data user menjadi array
        $user = mysqli_fetch_assoc($query_user);

        // Mengecek password biasa atau password hash
        $password_valid = ($password == $user['password']) || password_verify($password, $user['password']);

        // Jika password benar
        if ($password_valid) {
            // Menyimpan session login
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Mengarahkan admin ke dashboard
            if ($user['role'] == 'Administrator' || $user['role'] == 'Admin') {
                $_SESSION['success'] = "Selamat Datang, " . $user['nama'] . "!";
                header('location: admin/dashboard-admin.php');
            exit;
            }
            // Mengarahkan customer ke landing page dengan status login
            $_SESSION['success'] = "Selamat Datang, " . $user['nama'] . "!";
            header('location: user/booking.php');
            exit;
        }

        // Pesan password salah
        $_SESSION['error'] = "Password salah!";
    } else {
        // Pesan email tidak ditemukan
        $_SESSION['error'] = "Email tidak ditemukan!";
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
    <title>Login - Mey Salon</title>
    <!-- fav icon -->
    <link rel="icon" href="layout/images/favicon_io/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" sizes="180x180" href="layout/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="layout/images/favicon_io/favicon-32x32.png">
    <!-- Font Google -->
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

        /* Background dekorasi */
        .login-bg {
            background:
                radial-gradient(circle at top left, rgba(244, 63, 94, 0.20), transparent 32%),
                radial-gradient(circle at bottom right, rgba(251, 113, 133, 0.22), transparent 34%),
                linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
        }
    </style>
</head>

<body class="login-bg min-h-dvh overflow-y-auto">

    <!-- Wrapper halaman login -->
    <main class="min-h-dvh flex items-center justify-center px-4 py-6 sm:py-10">

        <!-- Card login -->
        <section class="w-full max-w-[420px] glass-effect rounded-[28px] sm:rounded-[32px] shadow-2xl overflow-hidden relative">

            <!-- Isi card login -->
            <div class="w-full px-5 py-6 sm:px-8 sm:py-8 bg-white/95 relative">

                <!-- Header login -->
                <div class="mb-6 text-center">

                    <!-- Logo -->
                    <div class="mx-auto w-16 h-16 sm:w-18 sm:h-18 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center shadow-sm mb-4">
                        <img 
                            src="layout/images/mey-salon.png" 
                            alt="Mey Salon Logo"
                            class="w-12 h-12 rounded-xl object-cover"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                        >

                        <!-- Icon cadangan -->
                        <i class="fa-solid fa-spa text-rose-600 text-2xl hidden"></i>
                    </div>

                    <!-- Nama salon -->
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 serif-font leading-tight">
                        Mey Salon
                    </h1>

                    <!-- Deskripsi login -->
                    <p class="text-gray-500 mt-2 text-sm leading-relaxed">
                        Selamat datang kembali, silakan masuk ke akun Anda.
                    </p>
                </div>
                <!-- ALERT ERROR -->
                 <?php if(isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error! </strong>
                        <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- Form login -->
                <form action="" method="POST" class="space-y-4">

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
                                class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                                placeholder="Masukkan email"
                            >
                        </div>
                    </div>

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
                                autocomplete="current-password"
                                class="block w-full pl-11 pr-12 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                                placeholder="Masukkan password"
                            >

                            <!-- Tombol lihat password -->
                            <button 
                                type="button"
                                onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-rose-600 transition"
                            >
                                <i id="password-icon" class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Link lupa password -->
                    <div class="flex justify-end">
                        <a href="lupa-password.php" class="text-rose-600 font-bold hover:underline text-xs sm:text-sm">
                            Lupa password?
                        </a>
                    </div>

                    <!-- Tombol login -->
                    <button 
                        type="submit" 
                        name="login"
                        class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                    >
                        Masuk Sekarang
                    </button>
                </form>

                <!-- Link registrasi -->
                <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                    <p class="text-gray-500 text-sm">
                        Belum punya akun?
                        <a href="registrasi.php" class="text-rose-600 font-bold hover:underline ml-1">
                            Daftar di sini
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

    <!-- Script halaman login -->
    <script>
        // Menampilkan atau menyembunyikan password
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

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
