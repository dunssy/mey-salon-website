<?php
// Memulai session login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek status login user
$is_login = isset($_SESSION['id_user']) || isset($_SESSION['user_id']) || !empty($_SESSION['login']);

// Mengambil id user dari session
$id_user_login = isset($_SESSION['id_user']) ? (int) $_SESSION['id_user'] : (int) ($_SESSION['user_id'] ?? 0);

// Menyiapkan data user default
$nama_user = $_SESSION['nama'] ?? $_SESSION['nama_user'] ?? 'Customer';
$role_user = $_SESSION['role'] ?? 'Customer';
$inisial_user = strtoupper(substr(trim($nama_user), 0, 1));

// Mengambil data user terbaru dari database
if ($is_login && $id_user_login > 0) {
    $query_user_login = mysqli_query(
        $koneksi,
        "SELECT nama, email, role FROM user WHERE id_user = $id_user_login LIMIT 1"
    );

    if ($query_user_login && mysqli_num_rows($query_user_login) > 0) {
        $data_user_login = mysqli_fetch_assoc($query_user_login);

        $nama_user = $data_user_login['nama'] ?? $nama_user;
        $role_user = $data_user_login['role'] ?? $role_user;
        $inisial_user = strtoupper(substr(trim($nama_user), 0, 1));

        $_SESSION['nama'] = $nama_user;
        $_SESSION['role'] = $role_user;
    }
}

// Menyiapkan array layanan
$layananList = [];

// Mengambil data layanan dari database
$query_layanan = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan ASC");

if ($query_layanan) {
    while ($layanan = mysqli_fetch_assoc($query_layanan)) {
        $layananList[] = $layanan;
    }
}

// Format angka menjadi rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

// Format menit menjadi teks durasi
function formatDurasi($menit)
{
    return (int) $menit . ' Menit';
}

// Mengambil gambar layanan dari database
function getServiceImage($layanan)
{
    if (!empty($layanan['gambar_layanan'])) {
        $path = 'layout/images/' . $layanan['gambar_layanan'];

        if (file_exists($path)) {
            return $path;
        }
    }

    if (!empty($layanan['foto_layanan'])) {
        $path = 'layout/images/' . $layanan['foto_layanan'];

        if (file_exists($path)) {
            return $path;
        }
    }

    return 'layout/images/mey-salon.png';
}
?>
<!doctype html>
<html lang="id">
<head>
    <!-- Metadata dasar -->
    <meta charset="UTF-8" />

    <!-- Tampilan responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Judul halaman -->
    <title>Mey Salon</title>

    <!-- fav ico -->
    <link rel="icon" href="layout/images/favicon_io/favicon.ico" type="image/x-icon" />
    
    <!-- Font Google -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- CSS utama project -->
    <link rel="stylesheet" href="layout/css/style.css" />
    <!-- CSS tambahan landing page -->
    <style>
        /* Scroll halaman halus */
        html {
            scroll-behavior: smooth;
        }

        /* Warna body mode terang */
        body.light {
            background-color: #ffffff !important;
            color: #111827;
        }

        /* Warna body mode gelap */
        body.dark {
            background-color: #0f0f0f !important;
            color: #ffffff;
        }

        /* Jarak anchor section */
        .section-compact {
            scroll-margin-top: 90px;
        }

        /* Section mode terang */
        body.light .theme-section,
        body.light .theme-section-soft {
            background-color: #ffffff !important;
        }

        /* Section mode gelap */
        body.dark .theme-section {
            background-color: #0f0f0f !important;
        }

        /* Section soft mode gelap */
        body.dark .theme-section-soft {
            background-color: #121212 !important;
        }

        /* Judul mode terang */
        body.light .theme-title {
            color: #111827 !important;
        }

        /* Judul mode gelap */
        body.dark .theme-title {
            color: #ffffff !important;
        }

        /* Teks mode terang */
        body.light .theme-text {
            color: #4b5563 !important;
        }

        /* Teks mode gelap */
        body.dark .theme-text {
            color: #d1d5db !important;
        }

        /* Card mode terang */
        body.light .theme-card {
            background-color: #ffffff !important;
            border-color: #111827 !important;
            color: #111827 !important;
        }

        /* Card mode gelap */
        body.dark .theme-card {
            background-color: #181818 !important;
            border-color: #2a2a2a !important;
            color: #ffffff !important;
        }

        /* Border video mode terang */
        body.light .theme-video-border {
            border-color: #111827 !important;
        }

        /* Border video mode gelap */
        body.dark .theme-video-border {
            border-color: #ffffff !important;
        }

        /* Navbar mode terang */
        body.light #navbar {
            background-color: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(18px);
        }

        /* Navbar mode gelap */
        body.dark #navbar {
            background-color: rgba(15, 15, 15, 0.88);
            backdrop-filter: blur(18px);
        }

        /* Menu mobile mode terang */
        body.light #mobile-menu {
            background-color: #ffffff !important;
        }

        /* Menu mobile mode gelap */
        body.dark #mobile-menu {
            background-color: #121212 !important;
        }

        /* Border menu mobile mode gelap */
        body.dark .mobile-nav-link {
            border-color: #2a2a2a !important;
        }

        /* Tombol profile mode terang */
        body.light .profile-button {
            background-color: #fdf2f8 !important;
            color: #db2777 !important;
        }

        /* Tombol profile mode gelap */
        body.dark .profile-button {
            background-color: #2a2a2a !important;
            color: #f9a8d4 !important;
        }

        /* Dropdown profile mode terang */
        body.light .profile-menu {
            background-color: #ffffff !important;
            border-color: #fbcfe8 !important;
        }

        /* Dropdown profile mode gelap */
        body.dark .profile-menu {
            background-color: #181818 !important;
            border-color: #2a2a2a !important;
        }

        /* Header dropdown mode terang */
        body.light .profile-menu-header {
            background-color: #fdf2f8 !important;
            border-color: #fbcfe8 !important;
        }

        /* Header dropdown mode gelap */
        body.dark .profile-menu-header {
            background-color: #202020 !important;
            border-color: #2a2a2a !important;
        }

        /* Item dropdown mode terang */
        body.light .profile-menu-item {
            color: #4b5563 !important;
        }

        /* Item dropdown mode gelap */
        body.dark .profile-menu-item {
            color: #d1d5db !important;
        }

        /* Hover item dropdown */
        .profile-menu-item:hover {
            background-color: #fdf2f8 !important;
            color: #db2777 !important;
        }

        /* Wrapper slider produk */
        .product-slider-wrapper {
            position: relative;
        }

        /* Slider produk */
        .product-slider {
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        /* Sembunyikan scrollbar slider */
        .product-slider::-webkit-scrollbar {
            display: none;
        }

        /* Ukuran card produk */
        .product-card {
            min-width: 280px;
            max-width: 280px;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        /* Hover card produk */
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 50px rgba(211, 107, 133, 0.22);
        }

        /* Tombol slider */
        .slider-btn {
            width: 44px;
            height: 44px;
            border-radius: 9999px;
            background: #ffffff;
            color: #d36b85;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
        }

        /* Hover tombol slider */
        .slider-btn:hover {
            background: #d36b85;
            color: #ffffff;
            transform: scale(1.08);
        }

        /* Tombol slider mode gelap */
        body.dark .slider-btn {
            background: #1f1f1f;
            color: #ffffff;
        }

        /* Animasi produk */
        .product-slide-in {
            animation: productSlideIn 0.65s ease both;
        }

        /* Gerak animasi produk */
        @keyframes productSlideIn {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Ukuran desktop */
        @media (min-width: 1024px) {
            .section-compact {
                padding-top: 5rem;
                padding-bottom: 5rem;
            }

            #home {
                min-height: 88vh;
            }

            .product-card {
                min-width: 300px;
                max-width: 300px;
            }
        }

        /* Ukuran mobile */
        @media (max-width: 767px) {
            .product-card {
                min-width: 260px;
                max-width: 260px;
            }
        }
    </style>

    <!-- MEY SALON PAGE LOADER (Landing) -->
    <style>
        #mey-page-loader {
            position: fixed; inset: 0; z-index: 99999;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #fff5f8 0%, #fce7f3 60%, #fdf2f8 100%);
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #mey-page-loader.mey-loader-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
        .mey-loader-brand { font-family: 'Playfair Display', 'Georgia', serif; text-align: center; margin-bottom: 28px; }
        .mey-loader-brand-title { font-size: 2.2rem; font-weight: 700; color: #be185d; letter-spacing: 2px; line-height: 1; }
        .mey-loader-brand-sub { font-family: 'Montserrat', sans-serif; font-size: 0.65rem; color: #f472b6; letter-spacing: 5px; text-transform: uppercase; margin-top: 5px; }
        .mey-spinner-wrap { position: relative; width: 72px; height: 72px; margin-bottom: 22px; }
        .mey-spinner-svg { position: absolute; inset: 0; animation: mey-spin 1.4s linear infinite; }
        .mey-spinner-icon { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 1.7rem; animation: mey-pulse 1.4s ease-in-out infinite; }
        .mey-dots { display: flex; gap: 8px; align-items: center; }
        .mey-dot { width: 9px; height: 9px; border-radius: 50%; background: #ec4899; animation: mey-bounce 1.2s ease-in-out infinite; }
        .mey-dot:nth-child(2) { background: #f472b6; animation-delay: .2s; }
        .mey-dot:nth-child(3) { background: #fbcfe8; animation-delay: .4s; }
        .mey-loader-text { margin-top: 20px; font-family: 'Montserrat', sans-serif; font-size: 0.78rem; color: #db2777; letter-spacing: 1px; opacity: 0.8; }
        @keyframes mey-spin   { to { transform: rotate(360deg); } }
        @keyframes mey-bounce { 0%,100%{transform:translateY(0);opacity:.5} 50%{transform:translateY(-8px);opacity:1} }
        @keyframes mey-pulse  { 0%,100%{transform:scale(1);opacity:.9} 50%{transform:scale(1.15);opacity:1} }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.createElement('div');
            el.id = 'mey-page-loader';
            el.innerHTML = '<div class="mey-loader-brand"><div class="mey-loader-brand-title">Mey Salon</div><div class="mey-loader-brand-sub">Beauty &amp; Care</div></div>'
                + '<div class="mey-spinner-wrap">'
                + '<svg class="mey-spinner-svg" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">'
                + '<circle cx="36" cy="36" r="32" stroke="#fce7f3" stroke-width="4"/>'
                + '<circle cx="36" cy="36" r="32" stroke="url(#meyGL)" stroke-width="4" stroke-linecap="round" stroke-dasharray="50 150"/>'
                + '<defs><linearGradient id="meyGL" x1="0" y1="0" x2="72" y2="72" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#ec4899"/><stop offset="100%" stop-color="#f9a8d4"/></linearGradient></defs></svg>'
                + '<div class="mey-spinner-icon">🌸</div></div>'
                + '<div class="mey-dots"><span class="mey-dot"></span><span class="mey-dot"></span><span class="mey-dot"></span></div>'
                + '<p class="mey-loader-text">Selamat datang di Mey Salon...</p>';
            if (document.body) document.body.insertBefore(el, document.body.firstChild);
        }, { once: true });
        (function () {
            function hide() {
                var el = document.getElementById('mey-page-loader');
                if (el) { el.classList.add('mey-loader-hidden'); setTimeout(function () { if (el && el.parentNode) el.parentNode.removeChild(el); }, 600); }
            }
            if (document.readyState === 'complete') { setTimeout(hide, 400); }
            else { window.addEventListener('load', function () { setTimeout(hide, 400); }); }
            setTimeout(hide, 7000);
        })();
    </script>
</head>

<body class="light">
    <!-- Tombol ganti tema -->
    <button id="theme-toggle-floating" aria-label="Ganti Tema">
        <!-- Icon matahari -->
        <svg id="sun-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Path icon matahari -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>

        <!-- Icon bulan -->
        <svg id="moon-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <!-- Path icon bulan -->
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>

    <!-- Navbar utama -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 py-3 px-4 md:px-8">
        <!-- Container navbar -->
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-[1fr_1.4fr_auto] items-center gap-6">

            <!-- Logo navbar -->
            <a href="#home" class="flex items-center space-x-3 group">
                <!-- Gambar logo -->
                <img src="layout/images/mey-salon.png" alt="Mey Salon Logo" class="w-12 md:w-14 group-hover:scale-110 transition-transform" />

                <!-- Nama brand -->
                <span class="text-lg md:text-xl font-bold tracking-widest theme-title">
                    MEY <span class="text-accent-pink">SALON</span>
                </span>
            </a>

            <!-- Menu desktop -->
            <div class="hidden md:flex justify-end items-center space-x-6 lg:space-x-8 text-sm font-semibold tracking-wide theme-title">
                <!-- Link home -->
                <a href="#home" class="hover:text-accent-pink transition">HOME</a>

                <!-- Link about -->
                <a href="#about" class="hover:text-accent-pink transition">ABOUT</a>

                <!-- Link produk -->
                <a href="#product" class="hover:text-accent-pink transition">PRODUCT</a>

                <!-- Link kontak -->
                <a href="#contact" class="hover:text-accent-pink transition">CONTACT</a>
            </div>

            <!-- Aksi navbar desktop -->
            <div class="hidden md:flex items-center justify-end">
                <?php if ($is_login) : ?>

                    <!-- Dropdown profile -->
                    <div class="relative user-profile-dropdown">

                        <!-- Tombol profile -->
                        <button
                            type="button"
                            onclick="toggleUserProfileDropdown()"
                            class="profile-button flex items-center gap-3 px-4 py-2 rounded-full transition-all"
                        >
                            <!-- Inisial user -->
                            <div class="w-8 h-8 rounded-full bg-pink-600 text-white flex items-center justify-center text-sm font-bold">
                                <?= htmlspecialchars($inisial_user); ?>
                            </div>

                            <!-- Nama dan role user -->
                            <div class="text-left leading-tight">
                                <!-- Nama user -->
                                <p class="text-sm font-bold">
                                    <?= htmlspecialchars($nama_user); ?>
                                </p>

                                <!-- Role user -->
                                <p class="text-[10px] uppercase text-pink-400 font-bold">
                                    <?= htmlspecialchars($role_user); ?>
                                </p>
                            </div>

                            <!-- Icon dropdown -->
                            <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>

                        <!-- Menu profile -->
                        <div id="user-profile-menu" class="profile-menu hidden absolute right-0 mt-3 w-56 rounded-2xl shadow-xl border overflow-hidden z-50">

                            <!-- Header menu profile -->
                            <div class="profile-menu-header p-4 border-b">
                                <!-- Nama user dropdown -->
                                <p class="text-sm font-bold theme-title">
                                    <?= htmlspecialchars($nama_user); ?>
                                </p>

                                <!-- Role user dropdown -->
                                <p class="text-[10px] text-gray-400 uppercase font-bold">
                                    <?= htmlspecialchars($role_user); ?>
                                </p>
                            </div>
                        <?php if($role_user === 'Customer'){ ?>
                            <!-- Menu booking -->
                            <a href="user/booking.php?section=booking#booking" class="profile-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm transition text-left">
                                <i class="fa-solid fa-calendar-check w-5"></i>
                                <span>Booking Saya</span>
                            </a>
                            <!-- Menu pengaturan profil -->
                            <a href="user/booking.php?section=profil#profil" class="profile-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm transition text-left">
                                <i class="fa-solid fa-user-gear w-5"></i>
                                <span>Pengaturan Profil</span>
                            </a>
                        <?php }else{ ?>
                          <!-- Menu Admin -->
                            <a href="admin/dashboard-admin.php" class="profile-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm transition text-left">
                                <i class="fa-solid fa-calendar-check w-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <!-- Menu pengaturan profil admin -->
                            <a href="admin/pengaturan-profil.php" class="profile-menu-item w-full flex items-center gap-3 px-4 py-3 text-sm transition text-left">
                                <i class="fa-solid fa-user-gear w-5"></i>
                                <span>Pengaturan Profil</span>
                            </a>
                        <?php } ?>
                            <!-- Menu logout -->
                            <div class="border-t border-pink-50">
                                <!-- Link logout -->
                                <a
                                    href="logout.php"
                                    onclick="return confirm('Apakah Anda yakin ingin logout?')"
                                    class="flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition font-semibold"
                                >
                                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>

                <?php else : ?>

                    <!-- Link login -->
                    <a href="login.php" class="text-sm font-bold theme-title hover:text-accent-pink transition uppercase tracking-widest">
                        Login
                    </a>

                    <!-- Link daftar -->
                    <a href="registrasi.php" class="btn-pink px-7 py-3 rounded-full text-xs font-bold tracking-widest uppercase ml-5">
                        Sign Up
                    </a>

                <?php endif; ?>
            </div>

            <!-- Tombol menu mobile -->
            <div class="md:hidden flex justify-end items-center">
                <!-- Button buka menu -->
                <button id="mobile-menu-btn" class="theme-title p-2" aria-label="Open Menu">
                    <!-- Icon menu -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <!-- Path icon menu -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Menu mobile -->
    <div id="mobile-menu" class="fixed inset-0 z-[60] hidden flex-col p-6 space-y-6">

        <!-- Header menu mobile -->
        <div class="flex justify-between items-center mb-4">
            <!-- Brand mobile -->
            <span class="text-xl font-bold tracking-widest theme-title">
                MEY <span class="text-accent-pink">SALON</span>
            </span>

            <!-- Tombol tutup menu -->
            <button id="close-menu-btn" class="theme-title p-2" aria-label="Close Menu">
                <!-- Icon tutup -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <!-- Path icon tutup -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Link home mobile -->
        <a href="#home" class="mobile-nav-link text-xl font-serif border-b border-gray-100 pb-4 theme-title">Home</a>

        <!-- Link about mobile -->
        <a href="#about" class="mobile-nav-link text-xl font-serif border-b border-gray-100 pb-4 theme-title">About</a>

        <!-- Link produk mobile -->
        <a href="#product" class="mobile-nav-link text-xl font-serif border-b border-gray-100 pb-4 theme-title">Product</a>

        <!-- Link kontak mobile -->
        <a href="#contact" class="mobile-nav-link text-xl font-serif border-b border-gray-100 pb-4 theme-title">Contact</a>

        <!-- Aksi mobile -->
        <div class="pt-8 flex flex-col space-y-4">
            <?php if ($is_login) : ?>

                <!-- Info user mobile -->
                <div class="w-full bg-pink-50 text-accent-pink py-4 rounded-xl font-bold text-lg text-center">
                    Halo, <?= htmlspecialchars($nama_user); ?>
                </div>

                <!-- Link booking mobile -->
                <a href="user/booking.php?section=booking#booking" class="w-full bg-button-pink text-white py-4 rounded-xl font-bold text-lg text-center shadow-lg">
                    Booking Saya
                </a>

                <!-- Link profil mobile -->
                <a href="user/booking.php?section=profil#profil" class="w-full border-2 border-pink-100 text-accent-pink py-4 rounded-xl font-bold text-lg text-center">
                    Pengaturan Profil
                </a>

                <!-- Link logout mobile -->
                <a href="logout.php" class="w-full border-2 border-red-100 text-red-500 py-4 rounded-xl font-bold text-lg text-center" onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            <?php else : ?>

                <!-- Link daftar mobile -->
                <a href="registrasi.php" class="w-full bg-button-pink text-white py-4 rounded-xl font-bold text-lg text-center shadow-lg">
                    Sign Up
                </a>

                <!-- Link login mobile -->
                <a href="login.php" class="w-full border-2 border-dark-brown/10 theme-title py-4 rounded-xl font-bold text-lg text-center">
                    Login
                </a>

            <?php endif; ?>
        </div>
    </div>

    <!-- Section home -->
    <section id="home" class="relative pt-24 md:pt-28 pb-10 px-6 overflow-hidden flex items-center theme-section">
        <!-- Container home -->
        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">

            <!-- Teks home -->
            <div class="space-y-5 md:space-y-7 animate-fade text-center lg:text-left order-2 lg:order-1">
                <!-- Label home -->
                <p class="text-xs md:text-sm font-bold tracking-[5px] uppercase text-accent-pink">
                    Selamat Datang di Mey Salon
                </p>

                <!-- Judul home -->
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif theme-title leading-tight">
                    MEY <br class="hidden lg:block" />
                    <span class="text-accent-pink italic">SALON</span>
                </h1>

                <!-- Deskripsi home -->
                <p class="theme-text text-base md:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Temukan pengalaman menata rambut dan perawatan kecantikan terpercaya yang siap menonjolkan kecantikan alami dan rasa percaya diri Anda.
                </p>

                <!-- Tombol booking home -->
                <div class="pt-2 flex justify-center lg:justify-start">
                    <!-- Link booking dinamis -->
                    <a href="<?= $is_login ? 'user/booking.php?section=layanan#layanan' : 'login.php'; ?>" class="btn-pink inline-block px-9 py-4 rounded-full font-bold tracking-widest text-xs md:text-sm shadow-lg uppercase">
                        Booking Sekarang
                    </a>
                </div>
            </div>

            <!-- Video home -->
            <div class="relative animate-fade order-1 lg:order-2" style="animation-delay: 0.2s">
                <!-- Wrapper video home -->
                <div class="rounded-[22px] md:rounded-[28px] overflow-hidden shadow-xl border-[5px] theme-video-border w-full max-w-[250px] sm:max-w-[290px] md:max-w-[330px] lg:max-w-[350px] mx-auto">
                    <!-- Video autoplay -->
                    <video class="w-full h-[330px] sm:h-[380px] md:h-[430px] lg:h-[460px] object-cover bg-black" autoplay muted loop playsinline preload="metadata">
                        <!-- Source video -->
                        <source src="layout/images/vidio-mey.mp4" type="video/mp4">

                        <!-- Fallback video -->
                        Browser Anda tidak mendukung video.
                    </video>
                </div>

                <!-- Dekorasi blur -->
                <div class="absolute -bottom-8 -left-8 w-32 h-32 md:w-44 md:h-44 bg-pastel-pink rounded-full -z-10 opacity-40 blur-3xl"></div>
            </div>
        </div>
    </section>

    <!-- Section tentang kami -->
    <section id="about" class="section-compact py-14 md:py-20 theme-section">
        <!-- Container about -->
        <div class="max-w-5xl mx-auto px-6">
            <!-- Judul about -->
            <h2 class="text-3xl md:text-4xl font-serif text-center theme-title mb-10 uppercase tracking-widest">
                Tentang Kami
            </h2>

            <!-- Grid about -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-stretch">

                <!-- Card about kiri -->
                <div class="theme-card p-4 rounded-[28px] group border shadow-sm">
                    <!-- Video about kiri -->
                    <div class="rounded-[22px] overflow-hidden shadow-lg border-[5px] theme-video-border w-full max-w-[300px] mx-auto mb-5">
                        <!-- Video autoplay -->
                        <video class="w-full h-[320px] object-cover bg-black" autoplay muted loop playsinline preload="metadata">
                            <!-- Source video -->
                            <source src="layout/images/vidio-mey4.mp4" type="video/mp4">

                            <!-- Fallback video -->
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>

                    <!-- Konten about kiri -->
                    <div class="px-3 md:px-4 pb-4 text-center md:text-left">
                        <!-- Judul card -->
                        <h3 class="text-xl md:text-2xl font-serif theme-title mb-3">
                            Gaya Rambut yang Bercerita
                        </h3>

                        <!-- Deskripsi card -->
                        <p class="text-sm md:text-base theme-text leading-relaxed">
                            Ekspresikan diri lewat gaya rambut yang dirancang khusus sesuai dengan karakter unik.
                        </p>
                    </div>
                </div>

                <!-- Card about kanan -->
                <div class="theme-card p-4 rounded-[28px] group border shadow-sm">
                    <!-- Video about kanan -->
                    <div class="rounded-[22px] overflow-hidden shadow-lg border-[5px] theme-video-border w-full max-w-[300px] mx-auto mb-5">
                        <!-- Video autoplay -->
                        <video class="w-full h-[320px] object-cover bg-black" autoplay muted loop playsinline preload="metadata">
                            <!-- Source video -->
                            <source src="layout/images/vidio-mey2.mp4" type="video/mp4">

                            <!-- Fallback video -->
                            Browser Anda tidak mendukung video.
                        </video>
                    </div>

                    <!-- Konten about kanan -->
                    <div class="px-3 md:px-4 pb-4 text-center md:text-left">
                        <!-- Judul card -->
                        <h3 class="text-xl md:text-2xl font-serif theme-title mb-3">
                            Waktunya Me-Time
                        </h3>

                        <!-- Deskripsi card -->
                        <p class="text-sm md:text-base theme-text leading-relaxed">
                            Tempat yang tepat untuk merawat diri sekaligus beristirahat sejenak dari rutinitas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section produk -->
    <section id="product" class="section-compact py-14 md:py-20 px-6 overflow-hidden theme-section">
        <!-- Container produk -->
        <div class="max-w-7xl mx-auto">

            <!-- Header produk -->
            <div class="text-center mb-10 md:mb-12">
                <!-- Judul produk -->
                <h2 class="text-3xl md:text-4xl font-serif theme-title inline-block relative uppercase tracking-widest">
                    Produk Unggulan
                    <span class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-16 md:w-20 h-1 bg-accent-pink"></span>
                </h2>
            </div>

            <?php if (!empty($layananList)): ?>

                <!-- Wrapper slider produk -->
                <div class="product-slider-wrapper">

                    <!-- Tombol slider kiri -->
                    <button type="button" onclick="slideProduct(-1)" class="slider-btn absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex" aria-label="Geser ke kiri">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <!-- Slider produk -->
                    <div id="product-slider" class="product-slider flex gap-5 overflow-x-auto pb-6 px-1 md:px-12 snap-x snap-mandatory">
                        <?php foreach ($layananList as $index => $layanan): ?>

                            <!-- Card produk -->
                            <article class="product-card product-slide-in theme-card rounded-[24px] overflow-hidden shadow-md border flex-shrink-0 snap-start" style="animation-delay: <?= $index * 0.08; ?>s">

                                <!-- Foto produk -->
                                <div class="relative overflow-hidden h-48 md:h-52 bg-pink-50">
                                    <!-- Gambar layanan -->
                                    <img src="<?= htmlspecialchars(getServiceImage($layanan)); ?>" class="w-full h-full object-cover transition duration-700 hover:scale-110" alt="<?= htmlspecialchars($layanan['nama_layanan']); ?>">

                                    <!-- Badge durasi -->
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[11px] font-bold text-accent-pink shadow-sm">
                                        <?= htmlspecialchars(formatDurasi($layanan['durasi_layanan'])); ?>
                                    </div>
                                </div>

                                <!-- Isi produk -->
                                <div class="p-5">

                                    <!-- Nama dan harga -->
                                    <div class="mb-4">
                                        <!-- Nama layanan -->
                                        <h3 class="text-lg md:text-xl font-serif font-bold theme-title leading-snug mb-2 line-clamp-2 min-h-[56px]">
                                            <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                        </h3>

                                        <!-- Harga layanan -->
                                        <p class="text-accent-pink font-bold text-base md:text-lg">
                                            <?php if (!empty($layanan['harga_max'])) : ?>
                                                Rp <?= number_format($layanan['harga_min'], 0, ',', '.'); ?> - Rp <?= number_format($layanan['harga_max'], 0, ',', '.'); ?>
                                            <?php else : ?>
                                                Rp <?= number_format($layanan['harga_min'], 0, ',', '.'); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>

                                    <!-- Footer produk -->
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <!-- ID layanan -->
                                        <span class="text-[11px] theme-text font-semibold">
                                            ID: <?= htmlspecialchars($layanan['id_layanan']); ?>
                                        </span>

                                        <!-- Link booking -->
                                        <a href="<?= $is_login ? 'user/booking.php?section=layanan#layanan' : 'login.php'; ?>" class="text-[11px] md:text-xs font-bold text-accent-pink tracking-widest uppercase hover:translate-x-1 transition inline-flex items-center gap-1">
                                            Booking <span>&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </article>

                        <?php endforeach; ?>
                    </div>

                    <!-- Tombol slider kanan -->
                    <button type="button" onclick="slideProduct(1)" class="slider-btn absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex" aria-label="Geser ke kanan">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Info slider mobile -->
                <p class="text-center text-xs theme-text mt-2 md:hidden">
                    Geser ke samping untuk melihat layanan lainnya
                </p>

            <?php else: ?>

                <!-- Data layanan kosong -->
                <div class="theme-card rounded-[24px] p-8 text-center shadow-md border">
                    <!-- Pesan kosong -->
                    <p class="theme-text">
                        Data layanan belum tampil. Cek kembali koneksi database dan tabel <strong>layanan</strong>.
                    </p>
                </div>

            <?php endif; ?>
        </div>
    </section>

    <!-- Section kontak -->
    <section id="contact" class="section-compact py-14 md:py-20 px-4 md:px-6 theme-section-soft">
        <!-- Container kontak -->
        <div class="max-w-7xl mx-auto">
            <!-- Judul kontak -->
            <h2 class="text-3xl md:text-4xl font-serif text-center theme-title mb-10 md:mb-14 uppercase tracking-widest">
                Kontak Kami
            </h2>

            <!-- Grid kontak -->
            <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-stretch">

                <!-- Google map -->
                <div class="map-responsive shadow-2xl rounded-[30px] md:rounded-[36px] overflow-hidden border-4 md:border-8 theme-video-border min-h-[320px]">
                    <!-- Iframe Google Maps -->
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.6546680698507!2d107.759136477936!3d-6.565198595110477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e693c9ced4b58e5%3A0x7f8a07a95c8b627f!2sMey%20Salon!5e0!3m2!1sid!2sid!4v1779330835533!5m2!1sid!2sid"
                        width="600"
                        height="450"
                        style="border: 0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="w-full h-full min-h-[320px]"
                    ></iframe>
                </div>

                <!-- Card kontak -->
                <div class="theme-card p-8 md:p-10 rounded-[30px] md:rounded-[36px] shadow-2xl flex flex-col justify-center border">
                    <!-- Nama salon -->
                    <h3 class="text-2xl md:text-3xl font-serif theme-title mb-4">
                        Mey Salon
                    </h3>

                    <!-- Deskripsi kontak -->
                    <p class="theme-text leading-relaxed mb-6">
                        Siap membantu Anda tampil lebih percaya diri dengan berbagai layanan perawatan rambut dan kecantikan terbaik.
                    </p>

                    <!-- Detail kontak -->
                    <div class="space-y-4 text-sm md:text-base theme-text">
                        <!-- Alamat salon -->
                        <div><strong class="theme-title">Alamat:</strong> Jl. D. Kartawigenda Gg. Palabuan No.27, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211</div>

                        <!-- Jam operasional -->
                        <div><strong class="theme-title">Jam Operasional:</strong> Setiap hari (Rabu Libur), 09.00 - 21.00</div>

                        <!-- Info reservasi -->
                        <div><strong class="theme-title">Reservasi:</strong> Silakan login untuk melakukan booking layanan</div>
                    </div>

                    <!-- Tombol kontak -->
                    <div class="pt-8">
                        <!-- Link booking kontak -->
                        <a href="<?= $is_login ? 'user/booking.php?section=layanan#layanan' : 'login.php'; ?>" class="btn-pink inline-block px-8 py-4 rounded-full font-bold tracking-widest text-xs md:text-sm shadow-lg uppercase">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer utama -->
    <footer class="bg-[#1A120B] text-white py-10 md:py-14 px-6">
        <!-- Container footer -->
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8 md:gap-10">

            <!-- Brand footer -->
            <div class="flex items-center space-x-4">
                <!-- Icon footer -->
                <div class="w-10 h-10 md:w-12 md:h-12 bg-button-pink rounded-xl flex items-center justify-center text-white shadow-xl">
                    <i class="fa-solid fa-scissors"></i>
                </div>

                <!-- Nama brand footer -->
                <h2 class="text-2xl md:text-3xl font-serif font-bold tracking-widest uppercase">
                    Mey <span class="text-accent-pink italic">Salon</span>
                </h2>
            </div>

            <!-- Copyright -->
            <div class="text-center md:text-right space-y-4">
                <!-- Teks copyright -->
                <div class="text-gray-500 text-[8px] md:text-[10px] tracking-[3px] md:tracking-[5px] font-bold uppercase">
                    © <?= date('Y'); ?> MEY SALON. ALL RIGHTS RESERVED.
                </div>
            </div>
        </div>
    </footer>

    <!-- Script halaman -->
    <script>
        // Fungsi geser slider produk
        function slideProduct(direction) {
            const slider = document.getElementById('product-slider');

            if (!slider) return;

            slider.scrollBy({
                left: direction * 320,
                behavior: 'smooth'
            });
        }

        // Fungsi auto slide produk
        function autoSlideProduct() {
            const slider = document.getElementById('product-slider');

            if (!slider) return;

            const maxScrollLeft = slider.scrollWidth - slider.clientWidth;

            if (slider.scrollLeft >= maxScrollLeft - 10) {
                slider.scrollTo({
                    left: 0,
                    behavior: 'smooth'
                });
            } else {
                slider.scrollBy({
                    left: 320,
                    behavior: 'smooth'
                });
            }
        }

        // Fungsi buka tutup dropdown profile
        function toggleUserProfileDropdown() {
            const menu = document.getElementById('user-profile-menu');

            if (!menu) return;

            menu.classList.toggle('hidden');
        }

        // Fungsi tutup dropdown profile
        function closeUserProfileDropdown() {
            const menu = document.getElementById('user-profile-menu');

            if (!menu) return;

            menu.classList.add('hidden');
        }

        // Menjalankan event setelah halaman siap
        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('product-slider');

            if (slider) {
                let productAutoSlide = setInterval(autoSlideProduct, 4000);

                slider.addEventListener('mouseenter', function () {
                    clearInterval(productAutoSlide);
                });

                slider.addEventListener('mouseleave', function () {
                    productAutoSlide = setInterval(autoSlideProduct, 4000);
                });
            }
        });

        // Menutup dropdown profile saat klik luar
        document.addEventListener('click', function (event) {
            const dropdown = document.querySelector('.user-profile-dropdown');

            if (!dropdown) return;

            if (!dropdown.contains(event.target)) {
                closeUserProfileDropdown();
            }
        });
    </script>

    <!-- Script utama project -->
    <script src="layout/js/main.js"></script>
</body>
</html>
