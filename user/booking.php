<?php
// Mengatur judul halaman
$page_title = "Booking Layanan - Mey Salon";

// Memanggil logic booking
include "booking-controller.php";

// Memanggil header user
include "../layout/header-user.php";
?>

<body class="bg-pink-50/20 text-gray-800 overflow-x-hidden">

    <!-- Memanggil navbar user -->
    <?php include "../layout/navbar-user.php"; ?>

    <!-- Konten utama -->
    <main class="max-w-6xl mx-auto px-4 pt-24 pb-10">

        <!-- Section layanan -->
        <?php include "views/section-layanan.php"; ?>

        <!-- Section booking saya -->
        <?php include "views/section-booking.php"; ?>

        <!-- Section profil -->
        <?php include "views/section-profil.php"; ?>
        
        <!-- booking cart -->
        <?php include "booking-cart.php"; ?>
        
        <!-- Modal konfirmasi booking -->
        <?php include "views/modal-booking.php"; ?>
        
    </main>

    <!-- Footer user -->
    <?php include "../layout/footer-component-user.php"; ?>

<?php
// Memanggil footer utama user
include "../layout/footer-user.php";
?>