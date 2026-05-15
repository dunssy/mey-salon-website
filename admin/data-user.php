  <?php 
$page_title = "Layanan";
include "../layout/header.php";
?>
<body class="text-gray-800 overflow-x-hidden">

    <div class="flex h-screen overflow-hidden">
        <!-- PEMANGGILAN SIDEBAR -->
         <?php include "../layout/sidebar.php"; ?>
        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">
           <!-- PEMANGGILAN NAVBAR -->
            <?php include "../layout/navbar.php"; ?>
            <!-- Page Content -->
            <div class="p-4 md:p-8 flex-1">
                <!-- Section Service -->
                <!-- Pelanggan Section -->         
                <div id="section-pelanggan">
                    <h3 class="text-lg font-bold mb-6 text-gray-700">Database Pelanggan</h3>
                    <div class="bg-white rounded-2xl border border-pink-100 p-12 text-center">
                        <div class="w-16 h-16 bg-pink-50 text-pink-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                        <p class="text-gray-400 italic text-sm">Menghubungkan ke database pelanggan...</p>
                    </div>
                </div>

            <!-- Footer Informatif -->
             <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>
  