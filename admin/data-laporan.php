<?php 
// Mengatur judul halaman
$page_title = "Laporan";

// Memanggil header layout
include "../layout/header.php";
?>

<body class="text-gray-800 overflow-x-hidden">

    <!-- Wrapper utama halaman admin -->
    <div class="flex h-screen overflow-hidden">

        <!-- Memanggil sidebar -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- Konten utama -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">

            <!-- Memanggil navbar -->
            <?php include "../layout/navbar.php"; ?>

            <!-- Isi halaman -->
            <div class="p-4 md:p-8 flex-1">

                <!-- Section laporan -->
                <section id="section-laporan" class="space-y-6">

                    <!-- Header halaman laporan -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        <!-- Judul dan deskripsi -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 tracking-tight">
                                <?= $page_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Pantau perkembangan data dan keuangan Mey Salon.
                            </p>
                        </div>
                        <div>
                         <a 
                            href="pengeluaran.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-white bg-pink-600 rounded-lg hover:bg-pink-700 transition-colors"
                        >
                            <i class="fa-solid fa-plus"></i>
                            <span>Tambah Pengeluaran</span>
                        </a>
                        </div>
                        <!-- Filter dan tombol cetak -->
                        <div class="flex items-center gap-2">

                            <!-- Input tanggal laporan -->
                            <input 
                                type="date" 
                                class="px-3 py-2 text-sm bg-white border border-pink-100 rounded-xl focus:outline-none focus:border-pink-300 text-gray-600"
                            >

                            <!-- Tombol cetak laporan -->
                            <button 
                                type="button"
                                class="px-4 py-2 text-sm font-semibold text-white bg-pink-600 hover:bg-pink-700 rounded-xl shadow-sm shadow-pink-100 transition-colors flex items-center gap-2"
                            >
                                <i class="fa-solid fa-file-export"></i>
                                <span>Cetak</span>
                            </button>
                        </div>
                    </div>

                    <!-- Grid ringkasan laporan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">

                        <!-- Card total pendapatan -->
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm">
                            <div class="flex justify-between items-start">

                                <!-- Data pendapatan -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                        Total Pendapatan
                                    </p>

                                    <h4 class="text-2xl font-bold text-gray-800 mt-1">
                                        Rp 12.450.000
                                    </h4>
                                </div>

                                <!-- Icon pendapatan -->
                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>

                            <!-- Informasi kenaikan -->
                            <p class="text-xs text-green-600 font-medium mt-3 flex items-center gap-1">
                                <i class="fa-solid fa-arrow-trend-up"></i>
                                <span>+12% dibanding bulan lalu</span>
                            </p>
                        </div>

                        <!-- Card booking selesai -->
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm">
                            <div class="flex justify-between items-start">

                                <!-- Data booking selesai -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                        Booking Selesai
                                    </p>

                                    <h4 class="text-2xl font-bold text-gray-800 mt-1">
                                        142 Transaksi
                                    </h4>
                                </div>

                                <!-- Icon booking -->
                                <div class="w-10 h-10 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-calendar-check"></i>
                                </div>
                            </div>

                            <!-- Informasi rata-rata -->
                            <p class="text-xs text-gray-400 font-medium mt-3">
                                Rata-rata 5 reservasi / hari
                            </p>
                        </div>

                        <!-- Card layanan terfavorit -->
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm sm:col-span-2 lg:col-span-1">
                            <div class="flex justify-between items-start">

                                <!-- Data layanan favorit -->
                                <div>
                                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                        Layanan Terfavorit
                                    </p>

                                    <h4 class="text-lg font-bold text-gray-800 mt-1">
                                        Hair Styling
                                    </h4>
                                </div>

                                <!-- Icon layanan favorit -->
                                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-lg">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                            </div>

                            <!-- Informasi jumlah layanan -->
                            <p class="text-xs text-purple-600 font-medium mt-4">
                                Dipilih sebanyak 68 kali
                            </p>
                        </div>
                    </div>

                    <!-- Card tabel riwayat pendapatan -->
                    <div class="bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden">

                        <!-- Header tabel -->
                        <div class="p-5 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-800 text-sm">
                                Riwayat Pendapatan Terbaru
                            </h4>

                            <span class="text-xs text-pink-600 bg-pink-50 px-2.5 py-1 rounded-full font-semibold">
                                Live Data
                            </span>
                        </div>

                        <!-- Tabel responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[700px]">

                                <!-- Header kolom tabel -->
                                <thead>
                                    <tr class="bg-pink-50/40 text-[11px] font-bold text-gray-400 uppercase tracking-wider border-b border-pink-50">
                                        <th class="py-3 px-6">ID Invoice</th>
                                        <th class="py-3 px-6">Pelanggan</th>
                                        <th class="py-3 px-6">Layanan</th>
                                        <th class="py-3 px-6">Tanggal</th>
                                        <th class="py-3 px-6">Total Bayar</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel laporan -->
                                <tbody class="text-xs text-gray-600 divide-y divide-pink-50">

                                    <!-- Contoh data laporan pertama -->
                                    <tr class="hover:bg-pink-50/10 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-pink-600">
                                            #INV-0024
                                        </td>

                                        <td class="py-4 px-6 font-medium text-gray-800">
                                            Siti Rahma
                                        </td>

                                        <td class="py-4 px-6">
                                            Hair Cut & Styling
                                        </td>

                                        <td class="py-4 px-6 text-gray-400">
                                            15 Mei 2026
                                        </td>

                                        <td class="py-4 px-6 font-bold text-gray-800">
                                            Rp 85.000
                                        </td>
                                    </tr>

                                    <!-- Contoh data laporan kedua -->
                                    <tr class="hover:bg-pink-50/10 transition-colors">
                                        <td class="py-4 px-6 font-semibold text-pink-600">
                                            #INV-0023
                                        </td>

                                        <td class="py-4 px-6 font-medium text-gray-800">
                                            Rini Amalia
                                        </td>

                                        <td class="py-4 px-6">
                                            Creambath + Catok
                                        </td>

                                        <td class="py-4 px-6 text-gray-400">
                                            14 Mei 2026
                                        </td>

                                        <td class="py-4 px-6 font-bold text-gray-800">
                                            Rp 120.000
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>