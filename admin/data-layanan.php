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
            <footer class="bg-white border-t border-pink-100 px-4 md:px-8 py-8 mt-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center md:text-left">
                        <h5 class="text-pink-600 font-bold italic text-lg">Mey Salon System</h5>
                        <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">
                            Aplikasi manajemen salon tercanggih untuk memudahkan reservasi, 
                            pelaporan, dan peningkatan layanan kecantikan Anda.
                        </p>
                    </div>
                    
                    <div class="flex justify-center md:justify-start space-x-12">
                        <div>
                            <h6 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Links</h6>
                            <ul class="text-[11px] space-y-2 text-gray-600 font-medium">
                                <li><a href="#" class="hover:text-pink-600">Panduan</a></li>
                                <li><a href="#" class="hover:text-pink-600">Dukungan</a></li>
                                <li><a href="#" class="hover:text-pink-600">API Docs</a></li>
                            </ul>
                        </div>
                        <div>
                            <h6 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Kontak</h6>
                            <ul class="text-[11px] space-y-2 text-gray-600 font-medium">
                                <li><i class="fa-solid fa-envelope mr-1 opacity-50"></i> help@meysalon.com</li>
                                <li><i class="fa-solid fa-phone mr-1 opacity-50"></i> +62 812-XXXX</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center md:text-right border-t md:border-t-0 pt-6 md:pt-0 border-pink-50">
                        <p class="text-[11px] text-gray-400">&copy; 2024 Mey Salon Dashboard.</p>
                        <div class="flex justify-center md:justify-end gap-3 mt-3 opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                            <i class="fa-brands fa-cc-visa text-xl"></i>
                            <i class="fa-brands fa-cc-mastercard text-xl"></i>
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>