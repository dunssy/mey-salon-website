<?php
// Memulai session user
session_start();
// Memanggil koneksi dan controller utama
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek user sudah login
if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

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
    <div id="success-alert" class="flex items-center justify-between bg-white- border border-emerald-400 text-emerald-700 px-5 py-4 rounded-xl shadow-md mb-4">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 text-emerald-600"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 12l2 2 4-4m6 2A9 9 0 1112 3a9 9 0 019 9z" />
            </svg>
            <div>
           
                <h4 class="font-semibold"></h4>
                <p class="text-sm">
                Selamat Datang Kembali <?php echo $_SESSION ['nama'] ?>
                </p>
           </div>
        </div>
        <!-- Tombol Close -->
        <button onclick="closeAlert()"
            class="text-emerald-700 hover:bg-emerald-200 p-2 rounded-lg transition duration-200">

            ✕
        </button>
    </div>

<script>
function closeAlert() {
    document.getElementById('success-alert').remove();
}
</script>

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