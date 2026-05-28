<?php
// Memulai session untuk menyimpan data login
session_start();

// Memanggil koneksi database
include "config/app.php";
global $koneksi;

// Mengecek tombol login
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE email = '$email'");

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        if ($password == $user['password'] || password_verify($password, $user['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'Administrator') {
                $_SESSION ['success'] = 'Login berhasil sebagai Administrator!';
                header("Location : admin/dashboard-admin.php");
                exit();

                // echo "
                //         alert('Login berhasil sebagai Administrator!');
                //         window.location.href = 'admin/dashboard-admin.php';
                //       </script>";
            } else {
                $_SESSION ['success'] = 'Login berhasil!';
                header("Location: user/booking.php");
                exit();
                // echo "<script>
                //         alert('Login berhasil!');
                //         window.location.href = 'user/booking.php';
                //       </script>";
            }
        } else {
            $_SESSION['alert'] = 'Password salah!';
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['alert'] = 'Email tidak ditemukan!';
        header("Location: login.php");
        exit();
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
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
    </style>
</head>

<body class="min-h-screen overflow-hidden flex items-center justify-center p-4">

    <!-- Container utama halaman login -->
    <div class="w-full max-w-md glass-effect rounded-[2rem] shadow-2xl overflow-hidden relative">

        <!-- Bagian form login -->
        <div class="w-full px-8 py-7 bg-white relative">

            <!-- Logo dan judul login -->
            <div class="mb-6 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-rose-50 rounded-2xl mb-3 border border-rose-100">
                    <i class="fas fa-spa text-rose-600 text-2xl"></i>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 serif-font">
                    Mey Salon
                </h1>

                <p class="text-gray-500 mt-1 font-light text-sm">
                    Selamat datang kembali, silakan masuk ke akun Anda.
                </p>
            </div>
            <?php if (isset($_SESSION['alert'])): ?>
                <!-- alert -->
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">UPss!</strong>
                    <span class="block sm:inline"><?php echo $_SESSION['alert']; ?></span>
                </div>
                <?php unset($_SESSION['alert']); ?>
            <?php endif; ?>

            <!-- Form login user -->
            <form action="" method="POST" class="space-y-4">

                <!-- Input email -->
                <div class="space-y-1">
                    <label for="email" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">
                        Email
                    </label>

                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>

                        <input 
                            type="email" 
                            name="email"
                            id="email"
                            required 
                            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                            placeholder="Masukkan email"
                        >
                    </div>
                </div>

                <!-- Input password -->
                <div class="space-y-1">
                    <label for="password" class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">
                        Password
                    </label>

                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>

                        <input 
                            type="password" 
                            name="password"
                            id="password"
                            required 
                            class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" 
                            placeholder="Masukkan password"
                        >
                    </div>
                </div>

                <!-- Tombol submit login -->
                <button 
                    type="submit" 
                    name="login"
                    class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-3.5 px-4 rounded-2xl shadow-xl shadow-rose-200 transition active:scale-[0.98]"
                >
                    Masuk Sekarang
                </button>
            </form>

            <!-- Link lupa password -->
            <div class="mt-4 text-center">
                <a href="lupa-password.php" class="text-rose-600 font-bold hover:underline text-sm">
                    Lupa password?
                </a>
            </div>

            <!-- Link menuju registrasi -->
            <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">
                    Belum punya akun?
                    <a href="registrasi.php" class="text-rose-600 font-bold hover:underline ml-1">
                        Daftar di sini
                    </a>
                </p>
            </div>

            <!-- Tombol kembali ke halaman utama -->
            <div class="mt-5 text-left">
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