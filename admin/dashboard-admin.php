<?php 
$page_title = "Dashboard";
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
                <div id="section-dashboard" class="space-y-6 md:space-y-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Booking Today</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">24</h3>
                            <div class="mt-2 text-[10px] text-blue-600 font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">Hari Ini</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Pending</p>
                            <h3 class="text-xl md:text-3xl font-bold text-pink-600 mt-1">5</h3>
                            <div class="mt-2 text-[10px] text-pink-600 font-bold bg-pink-50 inline-block px-2 py-0.5 rounded">Butuh Respon</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Revenue</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">2.4jt</h3>
                            <div class="mt-2 text-[10px] text-green-600 font-bold bg-green-50 inline-block px-2 py-0.5 rounded">IDR</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Customers</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">156</h3>
                            <div class="mt-2 text-[10px] text-purple-600 font-bold bg-purple-50 inline-block px-2 py-0.5 rounded">Loyalty</div>
                        </div>
                    </div>

                    <!-- Table Area -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">
                        <div class="px-6 py-4 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-700 italic">Antrean Berjalan</h4>
                            <button class="p-2 text-pink-600 hover:bg-pink-50 rounded-lg"><i class="fa-solid fa-rotate"></i></button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[600px]">
                                <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4">Layanan</th>
                                        <th class="px-6 py-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-pink-50">
                                    <tr class="hover:bg-pink-50/20">
                                        <td class="px-6 py-4 font-bold text-pink-600">10:00</td>
                                        <td class="px-6 py-4 font-semibold text-gray-700">Siska Amelia</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Hair Spa</td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="bg-green-100 text-green-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">Selesai</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-pink-50/20">
                                        <td class="px-6 py-4 font-bold text-pink-600">11:30</td>
                                        <td class="px-6 py-4 font-semibold text-gray-700">Dewi Kusuma</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Coloring</td>
                                        <td class="px-6 py-4 text-center">
                                            <button onclick="showMessage('Booking dikonfirmasi')" class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:shadow-lg">Konfirmasi</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
           
                <!-- Pelanggan Section -->
                <div id="section-pelanggan" class="hidden">
                    <h3 class="text-lg font-bold mb-6 text-gray-700">Database Pelanggan</h3>
                    <div class="bg-white rounded-2xl border border-pink-100 p-12 text-center">
                        <div class="w-16 h-16 bg-pink-50 text-pink-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                        <p class="text-gray-400 italic text-sm">Menghubungkan ke database pelanggan...</p>
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