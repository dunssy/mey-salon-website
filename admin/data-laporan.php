<?php 
$page_title = "Laporan";
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
                <!-- Section Laporan -->
                <div id="section-laporan" class="space-y-6">
                    <!-- Header Content -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 tracking-tight"><?php echo $page_title; ?></h3>
                            <p class="text-xs text-gray-400">Pantau perkembangan data dan keuangan Mey Salon.</p>
                        </div>
                        <!-- Filter Rentang Waktu / Cetak -->
                        <div class="flex items-center gap-2">
                            <input type="date" class="px-3 py-2 text-sm bg-white border border-pink-100 rounded-xl focus:outline-none focus:border-pink-300 text-gray-600">
                            <button class="px-4 py-2 text-sm font-semibold text-white bg-pink-600 hover:bg-pink-700 rounded-xl shadow-sm shadow-pink-100 transition-colors flex items-center gap-2">
                                <i class="fa-solid fa-file-export"></i> Cetak
                            </button>
                        </div>
                    </div>

                    <!-- 1. Ringkasan Laporan (KPI Cards) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <!-- Card Total Pendapatan -->
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Pendapatan</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mt-1">Rp 12.450.000</h4>
                                </div>
                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>
                            <p class="text-xs text-green-600 font-medium mt-3 flex items-center gap-1">
                                <i class="fa-solid fa-arrow-trend-up"></i> +12% dibanding bulan lalu
                            </p>
                        </div>

                        <!-- Card Total Booking Selesai -->
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Booking Selesai</p>
                                    <h4 class="text-2xl font-bold text-gray-800 mt-1">142 Transaksi</h4>
                                </div>
                                <div class="w-10 h-10 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 font-medium mt-3">Rata-rata 5 reservasi / hari</p>
                        </div>

                        <!-- Card Layanan Terlaris -->
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm sm:col-span-2 lg:col-span-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Layanan Terfavorit</p>
                                    <h4 class="text-lg font-bold text-gray-800 mt-1">Hair Styling</h4>
                                </div>
                                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                            </div>
                            <p class="text-xs text-purple-600 font-medium mt-4">Dipilih sebanyak 68 kali</p>
                        </div>
                    </div>

                    <!-- 2. Tabel Detail Transaksi / Laporan -->
                    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden">
                        <div class="p-5 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-sm">Riwayat Pendapatan Terbaru</h4>
                            <span class="text-xs text-pink-600 bg-pink-50 px-2.5 py-1 rounded-full font-semibold">Live Data</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-pink-50/40 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-pink-50">
                                        <th class="py-3 px-6">ID Invoice</th>
                                        <th class="py-3 px-6">Pelanggan</th>
                                        <th class="py-3 px-6">Layanan</th>
                                        <th class="py-3 px-6">Tanggal</th>
                                        <th class="py-3 px-6">Total Bayar</th>
                                    </tr>
                                </thead>
                                <tbody class="text-xs text-gray-600 divide-y divide-pink-50">
                                    <!-- Contoh Data Statis (Nanti diganti dengan Loop While PHP dari Database) -->
                                    <tr class="hover:bg-pink-50/10 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-pink-600">#INV-0024</td>
                                        <td class="py-4 px-6 font-medium text-gray-800">Siti Rahma</td>
                                        <td class="py-4 px-6">Hair Cut & Styling</td>
                                        <td class="py-4 px-6 text-gray-400">15 Mei 2026</td>
                                        <td class="py-4 px-6 font-bold text-gray-800">Rp 85.000</td>
                                    </tr>
                                    <tr class="hover:bg-pink-50/10 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-pink-600">#INV-0023</td>
                                        <td class="py-4 px-6 font-medium text-gray-800">Rini Amalia</td>
                                        <td class="py-4 px-6">Creambath + Catok</td>
                                        <td class="py-4 px-6 text-gray-400">14 Mei 2026</td>
                                        <td class="py-4 px-6 font-bold text-gray-800">Rp 120.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>