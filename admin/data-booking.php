<?php 
// Mengatur judul halaman
$page_title = "Data Booking";

// Memanggil layout dan koneksi database
include "../layout/header.php";
include "../config/koneksi.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil data booking dan nama pelanggan
$query = "
    SELECT booking.*, user.nama 
    FROM booking 
    JOIN user ON booking.id_user = user.id_user
    ORDER BY booking.tanggal_booking DESC, booking.jam_mulai ASC
";

$data = mysqli_query($koneksi, $query);
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

                <!-- Section data booking -->
                <section id="section-booking" class="space-y-6 md:space-y-8">

                    <!-- Card tabel booking -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">

                        <!-- Header tabel -->
                        <div class="px-6 py-4 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-700 italic">
                                Antrean Berjalan
                            </h4>

                            <a href="data-booking.php" class="p-2 text-pink-600 hover:bg-pink-50 rounded-lg transition">
                                <i class="fa-solid fa-rotate"></i>
                            </a>
                        </div>

                        <!-- Tabel responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[700px]">

                                <!-- Header kolom tabel -->
                                <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Jam Mulai</th>
                                        <th class="px-6 py-4">Jam Selesai</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4 text-center">Layanan</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel booking -->
                                <tbody class="divide-y divide-pink-50">

                                    <?php if (mysqli_num_rows($data) > 0) : ?>

                                        <!-- Perulangan data booking -->
                                        <?php while ($booking = mysqli_fetch_assoc($data)) : ?>

                                            <tr class="hover:bg-pink-50/20 transition">

                                                <!-- Jam mulai booking -->
                                                <td class="px-6 py-4 font-bold text-pink-600">
                                                    <?= htmlspecialchars($booking['jam_mulai']); ?>
                                                </td>

                                                <!-- Jam selesai booking -->
                                                <td class="px-6 py-4 font-bold text-pink-600">
                                                    <?= htmlspecialchars($booking['jam_selesai']); ?>
                                                </td>

                                                <!-- Nama pelanggan -->
                                                <td class="px-6 py-4 font-semibold text-gray-700">
                                                    <?= htmlspecialchars($booking['nama']); ?>
                                                </td>

                                                <!-- Tombol detail booking -->
                                                <td class="px-6 py-4 text-center">
                                                    <a 
                                                        href="detail-booking.php?id_booking=<?= $booking['id_booking']; ?>" 
                                                        class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:shadow-lg transition"
                                                    >
                                                        Booking Detail
                                                    </a>
                                                </td>

                                                <!-- Status booking -->
                                                <td class="px-6 py-4 text-center">
                                                    <?php if ($booking['status_booking'] == 'Pending') : ?>
                                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            <?= htmlspecialchars($booking['status_booking']); ?>
                                                        </span>
                                                    <?php elseif ($booking['status_booking'] == 'Selesai') : ?>
                                                        <span class="bg-green-100 text-green-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            <?= htmlspecialchars($booking['status_booking']); ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="bg-gray-100 text-gray-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            <?= htmlspecialchars($booking['status_booking']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <!-- Pesan jika data booking kosong -->
                                        <tr>
                                            <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">
                                                Belum ada data booking.
                                            </td>
                                        </tr>

                                    <?php endif; ?>

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