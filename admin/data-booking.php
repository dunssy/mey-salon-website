<?php 
$page_title = "Data Booking";
include "../layout/header.php";
include "../config/koneksi.php";

global $koneksi;
// MENGAMBIL DATA DARI DATABASE UNTUK DITAMPILKAN DI DATA BOOKING
$query = "SELECT * FROM booking";
$data = mysqli_query($koneksi, $query);
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
                                        <th class="px-6 py-4">Jam Mulai</th>
                                        <th class="px-6 py-4">Jam Selesai</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4">Layanan</th>
                                        <th class="px-6 py-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-pink-50">
                                <?php while ($booking = mysqli_fetch_assoc($data)) { ?>
                                    <tr class="hover:bg-pink-50/20">
                                        <td class="px-6 py-4 font-bold text-pink-600"><?php echo $booking['jam_mulai']; ?></td>
                                        <td class="px-6 py-4 font-bold text-pink-600"><?php echo $booking['jam_selesai']; ?></td>
                                        <td class="px-6 py-4 font-semibold text-gray-700"><?php echo $booking['id_user']; ?></td>
                                        <td class="px-6 py-4">
                                            <button class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:shadow-lg">Booking Detail</button>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="bg-yellow-100 text-black-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase"><?php echo $booking['status_booking']; ?></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                                   
                                </tbody>
                            </table>
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