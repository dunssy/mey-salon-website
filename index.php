<?php
// Mencari file koneksi yang tersedia
$koneksiCandidates = [
    __DIR__ . '/koneksi.php',
    __DIR__ . '/config/koneksi.php',
    __DIR__ . '/koneksi/koneksi.php',
    __DIR__ . '/layout/koneksi.php',
];

foreach ($koneksiCandidates as $candidate) {
    if (file_exists($candidate)) {
        require_once $candidate;
        break;
    }
}

// Mengambil variabel koneksi mysqli
$dbConn = null;
foreach (['db', 'conn', 'koneksi', 'mysqli'] as $varName) {
    if (isset($$varName) && $$varName instanceof mysqli) {
        $dbConn = $$varName;
        break;
    }
}

// Mengambil data layanan dari database
$layananList = [];
if ($dbConn instanceof mysqli) {
    $query = mysqli_query($dbConn, "SELECT * FROM layanan ORDER BY id_layanan ASC");

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $layananList[] = $row;
        }
    }
}

// Format harga ke rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

// Format durasi layanan
function formatDurasi($menit)
{
    return (int) $menit . ' Menit';
}

// Menentukan gambar layanan berdasarkan nama layanan
function getServiceImage($namaLayanan)
{
    $nama = strtolower(trim($namaLayanan));

    $imageMap = [
        'potong pria' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&q=80&w=1200',
        'potong wanita' => 'https://images.unsplash.com/photo-1519699047748-de8e457a634e?auto=format&fit=crop&q=80&w=1200',
        'potong + cuci' => 'https://images.unsplash.com/photo-1605497788044-5a32c7078486?auto=format&fit=crop&q=80&w=1200',
        'smoothing' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&q=80&w=1200',
        'cat rambut' => 'https://images.unsplash.com/photo-1562322140-8baeececf3df?auto=format&fit=crop&q=80&w=1200',
        'highlight' => 'https://images.unsplash.com/photo-1600948836101-f9ffda59d250?auto=format&fit=crop&q=80&w=1200',
        'creambath' => 'https://images.unsplash.com/photo-1570172619245-d11f717d7c14?auto=format&fit=crop&q=80&w=1200',
        'hair mask' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&q=80&w=1200',
        'hair spa' => 'https://images.unsplash.com/photo-1512290923902-8a9f81dc236c?auto=format&fit=crop&q=80&w=1200',
        'eyelash extension' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&q=80&w=1200',
        'nail art' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&q=80&w=1200',
    ];

    return $imageMap[$nama] ?? 'https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80&w=1200';
}
?>
<!doctype html>
<html lang="id">
<head>
    <!-- Pengaturan dasar halaman -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mey Salon</title>

    <!-- Font dan Tailwind -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS utama project -->
    <link rel="stylesheet" href="layout/css/style.css" />

    <!-- CSS tambahan halaman -->
    <style>
        html { scroll-behavior: smooth; }

        .product-slider-wrapper { position: relative; }

        .product-slider {
            scroll-behavior: smooth;
            scrollbar-width: none;
        }

        .product-slider::-webkit-scrollbar { display: none; }

        .product-card {
            min-width: 300px;
            max-width: 300px;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 50px rgba(211, 107, 133, 0.22);
        }

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

        .slider-btn:hover {
            background: #d36b85;
            color: #ffffff;
            transform: scale(1.08);
        }

        .product-slide-in { animation: productSlideIn 0.65s ease both; }

        @keyframes productSlideIn {
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (min-width: 1024px) {
            .section-compact { padding-top: 5rem; padding-bottom: 5rem; }
            #home { min-height: 88vh; }
            .product-card { min-width: 320px; max-width: 320px; }
        }
    </style>
</head>

<body class="light">
    <!-- Tombol ganti tema -->
    <button id="theme-toggle-floating" aria-label="Ganti Tema">
        <svg id="sun-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <svg id="moon-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>

    <!-- Navbar utama -->
    <nav id="navbar" class="fixed top-0 left-0 w-full z-50 transition-all duration-300 py-3 px-4 md:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-[1fr_1.4fr_auto] items-center gap-6">
            <a href="#home" class="flex items-center space-x-3 group">
                <img src="layout/images/mey-salon.png" alt="Mey Salon Logo" class="w-12 md:w-14 group-hover:scale-110 transition-transform" />
                <span class="text-lg md:text-xl font-bold tracking-widest text-dark-brown">
                    MEY <span class="text-accent-pink">SALON</span>
                </span>
            </a>

            <div class="hidden md:flex justify-end items-center space-x-6 lg:space-x-8 text-sm font-semibold tracking-wide">
                <a href="#home" class="hover:text-accent-pink transition">HOME</a>
                <a href="#about" class="hover:text-accent-pink transition">ABOUT</a>
                <a href="#product" class="hover:text-accent-pink transition">PRODUCT</a>
                <a href="#contact" class="hover:text-accent-pink transition">CONTACT</a>
            </div>

            <div class="hidden md:flex justify-end items-center space-x-5">
                <a href="login.php" class="text-sm font-bold text-dark-brown hover:text-accent-pink transition uppercase tracking-widest">Login</a>
                <a href="registrasi.php" class="btn-pink px-7 py-3 rounded-full text-xs font-bold tracking-widest uppercase">Sign Up</a>
            </div>

            <div class="md:hidden flex justify-end items-center">
                <button id="mobile-menu-btn" class="text-dark-brown p-2" aria-label="Open Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Menu mobile -->
    <div id="mobile-menu" class="fixed inset-0 bg-white dark:bg-[#121212] z-[60] hidden flex-col p-6 space-y-6">
        <div class="flex justify-between items-center mb-4">
            <span class="text-xl font-bold tracking-widest text-dark-brown">MEY <span class="text-accent-pink">SALON</span></span>
            <button id="close-menu-btn" class="text-dark-brown p-2" aria-label="Close Menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <a href="#home" class="mobile-nav-link text-xl font-serif border-b dark:border-gray-800 pb-4 text-dark-brown">Home</a>
        <a href="#about" class="mobile-nav-link text-xl font-serif border-b dark:border-gray-800 pb-4 text-dark-brown">About</a>
        <a href="#product" class="mobile-nav-link text-xl font-serif border-b dark:border-gray-800 pb-4 text-dark-brown">Product</a>
        <a href="#contact" class="mobile-nav-link text-xl font-serif border-b dark:border-gray-800 pb-4 text-dark-brown">Contact</a>

        <div class="pt-8 flex flex-col space-y-4">
            <a href="registrasi.php" class="w-full bg-button-pink text-white py-4 rounded-xl font-bold text-lg text-center shadow-lg">Sign Up</a>
            <a href="login.php" class="w-full border-2 border-dark-brown/10 dark:border-white/10 text-dark-brown py-4 rounded-xl font-bold text-lg text-center">Login</a>
        </div>
    </div>

    <!-- Hero section -->
    <section id="home" class="relative pt-24 md:pt-28 pb-10 px-6 overflow-hidden flex items-center">
        <div class="max-w-7xl mx-auto w-full grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">
            <div class="space-y-5 md:space-y-7 animate-fade text-center lg:text-left order-2 lg:order-1">
                <p class="text-xs md:text-sm font-bold tracking-[5px] uppercase text-accent-pink">Selamat Datang di Mey Salon</p>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif text-dark-brown leading-tight">
                    MEY <br class="hidden lg:block" />
                    <span class="text-accent-pink italic">SALON</span>
                </h1>
                <p class="text-soft-brown text-base md:text-lg max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Temukan pengalaman menata rambut dan perawatan kecantikan terpercaya yang siap menonjolkan kecantikan alami dan rasa percaya diri Anda.
                </p>
                <div class="pt-2 flex justify-center lg:justify-start">
                    <a href="login.php" class="btn-pink inline-block px-9 py-4 rounded-full font-bold tracking-widest text-xs md:text-sm shadow-lg uppercase">Booking Sekarang</a>
                </div>
            </div>

          <div class="relative animate-fade order-1 lg:order-2" style="animation-delay: 0.2s">
    <div class="rounded-[26px] md:rounded-[36px] overflow-hidden shadow-2xl border-[8px] md:border-[10px] border-white dark:border-[#1E1E1E] max-w-sm md:max-w-md lg:max-w-md mx-auto">
        <video 
            class="w-full aspect-[4/5] object-cover"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
        >
            <source src="layout/images/vidio-mey.mp4" type="video/mp4">
            Browser Anda tidak mendukung video.
        </video>
    </div>
</div>
                <div class="absolute -bottom-8 -left-8 w-32 h-32 md:w-44 md:h-44 bg-pastel-pink rounded-full -z-10 opacity-40 blur-3xl"></div>
            </div>
        </div>
    </section>

    <!-- About section -->
    <section id="about" class="section-compact py-14 md:py-20 bg-[#FAF7F2] dark:bg-[#1A1A1A]">
    <div class="max-w-5xl mx-auto px-6">

        <!-- Judul section -->
       <h2 class="text-3xl md:text-4xl font-serif text-center text-white/90 mb-10 uppercase tracking-widest">
    Tentang Kami
</h2>

        <!-- Grid dua kolom -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 items-stretch">

            <!-- Card kiri video -->
           <div class="service-card p-4 rounded-[28px] group bg-white">
                <!-- Video -->
                <div class="rounded-[22px] overflow-hidden shadow-lg border-[5px] border-white dark:border-[#1E1E1E] w-full max-w-[300px] mx-auto mb-5">
                    <video 
                        class="w-full h-[340px] object-cover bg-black"
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                    >
                        <source src="layout/images/vidio-mey4.mp4" type="video/mp4">
                        Browser Anda tidak mendukung video.
                    </video>
                </div>

                <!-- Text -->
                <div class="px-3 md:px-4 pb-4 text-center md:text-left">
                    <h3 class="text-xl md:text-2xl font-serif text-dark-brown mb-3">
                        Gaya Rambut yang Bercerita Tentang Anda
                    </h3>

                    <p class="text-sm md:text-base text-soft-brown leading-relaxed">
                        Ekspresikan diri lewat gaya rambut yang dirancang khusus sesuai dengan karakter unik Anda.
                    </p>
                </div>
            </div>

            <!-- Card kanan gambar -->
            <div class="service-card p-4 rounded-[28px] group bg-white">
                 <!-- Video -->
                <div class="rounded-[22px] overflow-hidden shadow-lg border-[5px] border-white dark:border-[#1E1E1E] w-full max-w-[300px] mx-auto mb-5">
                    <video 
                        class="w-full h-[340px] object-cover bg-black"
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                    >
                        <source src="layout/images/vidio-mey2.mp4" type="video/mp4">
                        Browser Anda tidak mendukung video.
                    </video>
                </div>

                <!-- Text -->
                <div class="px-3 md:px-4 pb-4 text-center md:text-left">
                    <h3 class="text-xl md:text-2xl font-serif text-dark-brown mb-3">
                        Waktunya Me-Time
                    </h3>

                    <p class="text-sm md:text-base text-soft-brown leading-relaxed">
                        Tempat yang tepat untuk merawat diri sekaligus beristirahat sejenak dari rutinitas.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

    <!-- Section produk unggulan -->
    <section id="product" class="section-compact py-14 md:py-20 px-6 overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10 md:mb-12">
                <h2 class="text-3xl md:text-4xl font-serif text-dark-brown inline-block relative uppercase tracking-widest">
                    Produk Unggulan
                    <span class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-16 md:w-20 h-1 bg-accent-pink"></span>
                </h2>
                <p class="text-soft-brown text-sm md:text-base mt-8 max-w-2xl mx-auto">
                    Pilihan layanan terbaik Mey Salon yang tampil otomatis dari database.
                </p>
            </div>

            <?php if (!empty($layananList)): ?>
                <div class="product-slider-wrapper">
                    <button type="button" onclick="slideProduct(-1)" class="slider-btn absolute left-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex" aria-label="Geser ke kiri">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    <div id="product-slider" class="product-slider flex gap-6 overflow-x-auto pb-6 px-1 md:px-12 snap-x snap-mandatory">
                        <?php foreach ($layananList as $index => $layanan): ?>
                            <article class="product-card product-slide-in service-card rounded-[26px] overflow-hidden shadow-md border border-gray-100 dark:border-gray-800 flex-shrink-0 bg-white dark:bg-[#181818] snap-start" style="animation-delay: <?= $index * 0.08; ?>s">
                                <div class="relative overflow-hidden h-56">
                                    <img
                                        src="<?= htmlspecialchars(getServiceImage($layanan['nama_layanan'])); ?>"
                                        class="w-full h-full object-cover transition duration-700 hover:scale-110"
                                        alt="<?= htmlspecialchars($layanan['nama_layanan']); ?>"
                                    />
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[11px] font-bold text-accent-pink shadow-sm">
                                        <?= htmlspecialchars(formatDurasi($layanan['durasi_layanan'])); ?>
                                    </div>
                                </div>

                                <div class="p-6">
                                    <div class="mb-4">
                                        <h3 class="text-lg md:text-xl font-serif font-bold text-dark-brown leading-snug mb-2">
                                            <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                        </h3>
                                        <p class="text-accent-pink font-bold text-lg">
                                        <?php if (!empty($layanan['harga_max'])) : ?>
                                            Rp <?= number_format($layanan['harga_min'], 0, ',', '.'); ?> -
                                            Rp <?= number_format($layanan['harga_max'], 0, ',', '.'); ?>
                                        <?php else : ?>
                                            Rp <?= number_format($layanan['harga_min'], 0, ',', '.'); ?>
                                        <?php endif; ?>                                        </p>
                                    </div>

                                    <p class="text-xs md:text-sm text-soft-brown leading-relaxed mb-5">
                                        Layanan perawatan terbaik dari Mey Salon dengan hasil yang nyaman, rapi, dan profesional.
                                    </p>

                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800">
                                        <span class="text-[11px] text-soft-brown font-semibold">
                                            ID: <?= htmlspecialchars($layanan['id_layanan']); ?>
                                        </span>
                                        <a href="login.php" class="text-[11px] md:text-xs font-bold text-accent-pink tracking-widest uppercase hover:translate-x-1 transition inline-flex items-center gap-1">
                                            Booking <span>&rarr;</span>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" onclick="slideProduct(1)" class="slider-btn absolute right-0 top-1/2 -translate-y-1/2 z-20 hidden md:flex" aria-label="Geser ke kanan">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <p class="text-center text-xs text-soft-brown mt-2 md:hidden">
                    Geser ke samping untuk melihat layanan lainnya
                </p>
            <?php else: ?>
                <div class="service-card rounded-[24px] p-8 text-center shadow-md">
                    <p class="text-soft-brown">
                        Data layanan belum tampil. Cek kembali file koneksi database dan nama tabel <strong>layanan</strong>.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact section -->
    <section id="contact" class="section-compact py-14 md:py-20 bg-[#F3D9D9]/20 dark:bg-[#1E1212]/50 px-4 md:px-6">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl md:text-4xl font-serif text-center text-dark-brown mb-10 md:mb-14 uppercase tracking-widest">Kontak Kami</h2>
            <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-stretch">
                <div class="map-responsive shadow-2xl rounded-[30px] md:rounded-[36px] overflow-hidden border-4 md:border-8 border-white dark:border-[#1E1E1E] min-h-[320px]">
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

                <div class="service-card p-8 md:p-10 rounded-[30px] md:rounded-[36px] shadow-2xl flex flex-col justify-center">
                    <h3 class="text-2xl md:text-3xl font-serif text-dark-brown mb-4">Mey Salon</h3>
                    <p class="text-soft-brown leading-relaxed mb-6">
                        Siap membantu Anda tampil lebih percaya diri dengan berbagai layanan perawatan rambut dan kecantikan terbaik.
                    </p>
                    <div class="space-y-4 text-sm md:text-base text-soft-brown">
                        <div><strong class="text-dark-brown">Alamat:</strong> Jl. lokasi Mey Salon sesuai Google Maps</div>
                        <div><strong class="text-dark-brown">Jam Operasional:</strong> Setiap hari, 09.00 - 21.00</div>
                        <div><strong class="text-dark-brown">Reservasi:</strong> Silakan login untuk melakukan booking layanan</div>
                    </div>
                    <div class="pt-8">
                        <a href="login.php" class="btn-pink inline-block px-8 py-4 rounded-full font-bold tracking-widest text-xs md:text-sm shadow-lg uppercase">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer utama -->
    <footer class="bg-[#1A120B] text-white py-10 md:py-14 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8 md:gap-10">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 md:w-12 md:h-12 bg-button-pink rounded-xl flex items-center justify-center text-white shadow-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 md:h-7 md:w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-4.879-4.879L12 12m0 0L9.121 9.121m0 0L4 4m5.121 5.121L12 12m0 0l2.879 2.879M12 12L9.121 14.121m0 0L4 19m5.121-5.121L12 12m0 0l2.879-2.879M12 12l2.121-2.121M19 4l-4.879 4.879" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2m0 16v2m10-10h-2M4 12H2" />
                    </svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-serif font-bold tracking-widest uppercase">Mey <span class="text-accent-pink italic">Salon</span></h2>
            </div>

            <div class="text-center md:text-right space-y-4">
                <div class="text-gray-500 text-[8px] md:text-[10px] tracking-[3px] md:tracking-[5px] font-bold uppercase">
                    © 2026 MEY SALON. ALL RIGHTS RESERVED.
                </div>
            </div>
        </div>
    </footer>

    <!-- Script slider produk -->
    <script>
        function slideProduct(direction) {
            const slider = document.getElementById('product-slider');
            if (!slider) return;

            slider.scrollBy({
                left: direction * 340,
                behavior: 'smooth'
            });
        }

        function autoSlideProduct() {
            const slider = document.getElementById('product-slider');
            if (!slider) return;

            const maxScrollLeft = slider.scrollWidth - slider.clientWidth;

            if (slider.scrollLeft >= maxScrollLeft - 10) {
                slider.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                slider.scrollBy({ left: 340, behavior: 'smooth' });
            }
        }

        let productAutoSlide = null;

        document.addEventListener('DOMContentLoaded', function () {
            const slider = document.getElementById('product-slider');
            if (!slider) return;

            productAutoSlide = setInterval(autoSlideProduct, 4000);

            slider.addEventListener('mouseenter', function () {
                clearInterval(productAutoSlide);
            });

            slider.addEventListener('mouseleave', function () {
                productAutoSlide = setInterval(autoSlideProduct, 4000);
            });
        });
    </script>

    <!-- Script utama project -->
    <script src="layout/js/main.js"></script>
</body>
</html>
