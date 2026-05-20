<?php 
// Mengatur judul halaman
$page_title = "Dashboard";

// Memanggil file layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil semua data booking dan nama pelanggan
$query_booking = "
    SELECT b.*, u.nama 
    FROM booking b 
    JOIN user u ON b.id_user = u.id_user 
    ORDER BY b.tanggal_booking DESC
";
$data_booking = mysqli_query($koneksi, $query_booking);

// Menghitung jumlah pelanggan
$query_customer = "SELECT * FROM user WHERE role = 'Customer'";
$total_customer = mysqli_num_rows(mysqli_query($koneksi, $query_customer));

// Mengambil tanggal hari ini
$hari_ini = date('Y-m-d');

// Menghitung booking hari ini
$query_booking_today = "SELECT * FROM booking WHERE tanggal_booking = '$hari_ini'";
$total_booking_today = mysqli_num_rows(mysqli_query($koneksi, $query_booking_today));

// Menghitung booking pending
$query_pending = "SELECT * FROM booking WHERE status_booking = 'Pending'";
$total_pending = mysqli_num_rows(mysqli_query($koneksi, $query_pending));

// Mengatur pendapatan sementara
$total_pendapatan = "2.4jt";
?>

<body class="text-gray-800 overflow-x-hidden">

    <!-- Wrapper utama halaman admin -->
    <div class="flex h-screen overflow-hidden">

        <!-- Memanggil sidebar admin -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- Konten utama dashboard -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">

            <!-- Memanggil navbar admin -->
            <?php include "../layout/navbar.php"; ?>

            <!-- Isi halaman dashboard -->
            <div class="p-4 md:p-8 flex-1">

                <!-- Section dashboard -->
                <section id="section-dashboard" class="space-y-6 md:space-y-8">

                    <!-- Grid statistik dashboard -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">

                        <!-- Card booking hari ini -->
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">
                                Booking Hari Ini
                            </p>

                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">
                                <?= $total_booking_today; ?>
                            </h3>

                            <div class="mt-2 text-[10px] text-blue-600 font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">
                                Hari Ini
                            </div>
                        </div>

                        <!-- Card booking pending -->
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">
                                Pending
                            </p>

                            <h3 class="text-xl md:text-3xl font-bold text-pink-600 mt-1">
                                <?= $total_pending; ?>
                            </h3>

                            <div class="mt-2 text-[10px] text-pink-600 font-bold bg-pink-50 inline-block px-2 py-0.5 rounded">
                                Butuh Respon
                            </div>
                        </div>

                        <!-- Card pendapatan -->
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">
                                Pendapatan
                            </p>

                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">
                                <?= $total_pendapatan; ?>
                            </h3>

                            <div class="mt-2 text-[10px] text-green-600 font-bold bg-green-50 inline-block px-2 py-0.5 rounded">
                                IDR
                            </div>
                        </div>

                        <!-- Card pelanggan -->
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">
                                Pelanggan
                            </p>

                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">
                                <?= $total_customer; ?>
                            </h3>

                            <div class="mt-2 text-[10px] text-purple-600 font-bold bg-purple-50 inline-block px-2 py-0.5 rounded">
                                Loyalty
                            </div>
                        </div>
                    </div>

                    <!-- Card tabel histori booking -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">

                        <!-- Header tabel booking -->
                        <div class="px-6 py-4 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-700 italic">
                                Histori Booking
                            </h4>

                            <a href="dashboard-admin.php" class="p-2 text-pink-600 hover:bg-pink-50 rounded-lg">
                                <i class="fa-solid fa-rotate"></i>
                            </a>
                        </div>

                        <!-- Wrapper tabel responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[700px]">

                                <!-- Header kolom tabel -->
                                <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Tanggal Booking</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4 text-center">Layanan</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                    </tr>
                                </thead>

                                <!-- Isi data booking -->
                                <tbody class="divide-y divide-pink-50">

                                    <?php if (mysqli_num_rows($data_booking) > 0) : ?>

                                        <!-- Perulangan data booking -->
                                        <?php while ($booking = mysqli_fetch_assoc($data_booking)) : ?>

                                            <tr class="hover:bg-pink-50/20 transition">

                                                <!-- Tanggal booking -->
                                                <td class="px-6 py-4 font-bold text-pink-600">
                                                    <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                                </td>

                                                <!-- Nama pelanggan -->
                                                <td class="px-6 py-4 font-semibold text-gray-700">
                                                    <?= htmlspecialchars($booking['nama']); ?>
                                                </td>

                                                <!-- Tombol detail layanan -->
                                                <td class="px-6 py-4 text-center">
                                                    <a 
                                                        href="data-booking.php" 
                                                        class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:shadow-lg transition"
                                                    >
                                                        Detail Layanan
                                                    </a>
                                                </td>

                                                <!-- Status booking -->
                                                <td class="px-6 py-4 text-center">
                                                    <?php if ($booking['status_booking'] == 'Pending') : ?>
                                                        <span class="bg-pink-100 text-pink-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            Pending
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="bg-green-100 text-green-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            <?= htmlspecialchars($booking['status_booking']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <!-- Pesan jika data booking kosong -->
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">
                                                Belum ada data booking.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <!-- Section pelanggan sementara -->
                <section id="section-pelanggan" class="hidden">

                    <!-- Judul section pelanggan -->
                    <h3 class="text-lg font-bold mb-6 text-gray-700">
                        Database Pelanggan
                    </h3>

                    <!-- Card informasi pelanggan -->
                    <div class="bg-white rounded-2xl border border-pink-100 p-12 text-center">
                        <div class="w-16 h-16 bg-pink-50 text-pink-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>

                        <p class="text-gray-400 italic text-sm">
                            Menghubungkan ke database pelanggan...
                        </p>
                    </div>
                </section>
            </div>

            <!-- Memanggil footer dashboard -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>