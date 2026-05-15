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
                <div id="section-layanan">
                    <h3 class="text-lg font-bold mb-6">Manajemen Layanan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm hover:border-pink-300 transition-colors">
                            <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center mb-4 text-xl">
                                <i class="fa-solid fa-scissors"></i>
                            </div>
                            <h4 class="font-bold text-gray-800">Hair Cut & Styling</h4>
                            <p class="text-sm text-pink-600 font-bold mt-1">Rp 85.000</p>
                            <button class="mt-4 w-full py-2 text-xs font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors">Edit Layanan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Informatif -->
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>