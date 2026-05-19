<?php 
$page_title = "Dashboard";
include "../layout/header.php";
include "../config/app.php";

global $koneksi;
// MENGAMBIL DATA DARI DATABASE DENGAN JOIN UNTUK MENDAPATKAN NAMA PELANGGAN
$query = "SELECT b.*, u.nama FROM booking b JOIN user u ON b.id_user = u.id_user";
$data = mysqli_query($koneksi, $query);
// MENGAMBIL DATA PELANGGAN BERDASARKAN ROLE UNTUK DITAMPILKAN DI DASHBOARD 
$user_query = "SELECT * FROM user WHERE role='Customer'";
// MENJUMLAH DATA USER BERDASARKAN ROLE USER SAJA UNTUK DITAMPILKAN DI DASHBOARD
$user_data = mysqli_num_rows(mysqli_query($koneksi, $user_query));
// MENGAMBIL DATA BOOKING BERDASARKAN TANGGAL BOOKING HARI INI UNTUK DITAMPILKAN DI DASHBOARD
$hari_ini = date('Y-m-d');
$booking_today_query = "SELECT * FROM booking WHERE tanggal_booking = '$hari_ini'";
$booking_today_data = mysqli_num_rows(mysqli_query($koneksi, $booking_today_query));
// MENGAMBIL DATA BOOKING BERDASARKAN STATUS BOOKING PENDING UNTUK DITAMPILKAN DI DASHBOARD
$pending_query = "SELECT * FROM booking WHERE status_booking = 'Pending'";
$pending_data = mysqli_num_rows(mysqli_query($koneksi, $pending_query));

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
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Booking Hari Ini</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo $booking_today_data; ?></h3>
                            <div class="mt-2 text-[10px] text-blue-600 font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">Hari Ini</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Pending</p>
                            <h3 class="text-xl md:text-3xl font-bold text-pink-600 mt-1"><?php echo $pending_data; ?></h3>
                            <div class="mt-2 text-[10px] text-pink-600 font-bold bg-pink-50 inline-block px-2 py-0.5 rounded">Butuh Respon</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Pendapatan</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">2.4jt</h3>
                            <div class="mt-2 text-[10px] text-green-600 font-bold bg-green-50 inline-block px-2 py-0.5 rounded">IDR</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Pelanggan</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1"><?php echo $user_data; ?></h3>
                            <div class="mt-2 text-[10px] text-purple-600 font-bold bg-purple-50 inline-block px-2 py-0.5 rounded">Loyalty</div>
                        </div>
                    </div>

                    <!-- Table Area -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">
                        <div class="px-6 py-4 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-700 italic">Histori Booking</h4>
                            <button class="p-2 text-pink-600 hover:bg-pink-50 rounded-lg"><i class="fa-solid fa-rotate"></i></button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[600px]">
                                <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Tanggal Booking</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4 text-center">Layanan</th>
                                        <th class="px-6 py-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-pink-50">
                                <!-- DATA BOOKING  -->
                                <?php while ($booking = mysqli_fetch_assoc($data)) { ?>
                                    <tr class="hover:bg-pink-50/20">
                                        <td class="px-6 py-4 font-bold text-pink-600"><?php echo $booking['tanggal_booking']; ?></td>
                                        <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $booking['nama']; ?></td>
                                        <td class="px-6 py-4 text-center">
                                            <button onclick="showMessage('Booking dikonfirmasi')" class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:shadow-lg">detail layanan</button>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="bg-green-100 text-green-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">Selesai</button>
                                        </td>
                                    </tr>
                                <?php } ?>
    
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
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>