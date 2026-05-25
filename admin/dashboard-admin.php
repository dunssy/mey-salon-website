<?php 
// Mengatur judul halaman
$page_title = "Dashboard";

// Memanggil file layout dan koneksi
include "../layout/header.php";
include "../config/app.php";


// Mengakses variabel koneksi yang ada pada koneksi.php dan di gunakan secara globa;
global $koneksi;

// Mengambil WAKRU DAN HARI HARI INI
$hari_ini = date('Y-m-d');

// Menghitung total pelanggan DARI tabel user dengan role 'Customer'
$query_customer = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM user WHERE role = 'Customer'"
);
$total_customer = mysqli_fetch_assoc($query_customer)['total'] ?? 0;

// Menghitung booking hari ini , dengan membandingkan tanggal_booking dengan hari ini
$query_booking_today = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM booking 
     WHERE DATE(tanggal_booking) = '$hari_ini'"
);
$total_booking_today = mysqli_fetch_assoc($query_booking_today)['total'] ?? 0;

// MENGHITUNG BOOKING YANG BUTUH RESPON , dengan status booking 'Waiting' atau 'Pending'
$query_pending = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM booking 
     WHERE status_booking IN ('Waiting', 'Pending')"
);
$total_pending = mysqli_fetch_assoc($query_pending)['total'] ?? 0;

// MENGHITUNG PENDAPATAN HARI INI DARI TABEL TRANSAKSI, DENGAN MEMBANDINGKAN TANGGAL_TRANSAKSI DENGAN
///HARI INI, DAN MENJUMLAHKAN TOTAL_BAYAR   

$query_pendapatan = mysqli_query(
    $koneksi,
    "SELECT COALESCE(SUM(total_bayar), 0) AS total 
     FROM transaksi 
     WHERE DATE(tanggal_transaksi) = '$hari_ini'"
);
$total_pendapatan = mysqli_fetch_assoc($query_pendapatan)['total'] ?? 0;

// JADI UNTUK b. itu adalah nama table 
// MENGAMBIL 10 BOOKING TERBARU DENGAN JOIN KE TABEL USER UNTUK MENDAPATKAN NAMA PELANGGAN, DAN JOIN KE TABEL BOOKING_DETAIL DAN LAYANAN UNTUK MENDAPATKAN NAMA LAYANAN
$query_booking = mysqli_query(
    $koneksi,
    "SELECT b.id_booking,b.tanggal_booking,b.jam_mulai,b.jam_selesai,
        b.status_booking,u.nama,GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan
     FROM booking b JOIN user u ON b.id_user = u.id_user LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan GROUP BY b.id_booking ORDER BY b.tanggal_booking DESC, b.jam_mulai DESC LIMIT 10"
);

// Mengambil stok barang yang hampir habis
$query_stok_menipis = mysqli_query(
    $koneksi,
    // MENGHITUNG STOK BARANG YANG JUMALAHNYA KURANG DARI ATAU SAMA DENGAN MINIMAL_STOK
    "SELECT COUNT(*) AS total FROM stok_barang WHERE jumlah_barang <= minimal_stok"
);
// MENGAMBIL HASIL QUERY STOK MENIPIS DAN MENGAMBIL NILAI TOTAL DARI HASIL
// QUERY, JIKA TIDAK ADA HASIL MAKA DEFAULTNYA 0
$total_stok_menipis = mysqli_fetch_assoc($query_stok_menipis)['total'] ?? 0;

//MENGAMBIL 5 TRANSAKSI TERBARU DENGAN JOIN KE TABEL BOOKING DAN USER UNTUK 
////MENDAPATKAN NAMA PELANGGAN, DAN MENAMPILKAN JENIS PELANGGAN (DARI BOOKING.JENIS_PELANGGAN)
$query_transaksi_terbaru = mysqli_query(
    $koneksi,
    "SELECT 
        t.id_transaksi,
        t.tanggal_transaksi,
        t.total_bayar,
        t.jenis_pelanggan,
        COALESCE(u.nama, 'Pelanggan Datang Langsung') AS nama_pelanggan
     FROM transaksi t
     LEFT JOIN booking b ON t.id_booking = b.id_booking
     LEFT JOIN user u ON b.id_user = u.id_user
     ORDER BY t.tanggal_transaksi DESC
     LIMIT 5"
);

//MENGATUR WARNA BADGE STATUS BOOKING 
//BERDSARKAN STATUS BOOKING, SEHINGGA MEMUDAHKAN ADMIN UNTUK MELIHAT STATUS BOOKING DENGAN CEPAT
function statusBadgeClass($status)
{
    if ($status == 'Waiting') { 
        return 'bg-yellow-100 text-yellow-700';
    }

    if ($status == 'Pending') {
        return 'bg-orange-100 text-orange-700';
    }

    if ($status == 'On-going') {
        return 'bg-blue-100 text-blue-700';
    }

    if ($status == 'Done') {
        return 'bg-green-100 text-green-700';
    }

    if ($status == 'Cancel') {
        return 'bg-red-100 text-red-700';
    }

    return 'bg-gray-100 text-gray-700';
}
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

                    <!-- Header dashboard -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                Dashboard
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Ringkasan data Mey Salon hari ini.
                            </p>
                        </div>

                        <a 
                            href="dashboard-admin.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-pink-600 bg-white border border-pink-100 rounded-xl hover:bg-pink-50 transition"
                        >
                            <i class="fa-solid fa-rotate"></i>
                            <span>Refresh</span>
                        </a>
                    </div>

                    <!-- Grid statistik dashboard -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 md:gap-6">

                        <!-- Card booking hari ini -->
                        <div class="glass-card p-5 md:p-6 rounded-2xl shadow-sm border border-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">
                                        Booking Hari Ini
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">
                                        <!-- Total Booking Hari Ini -->
                                        <?= (int) $total_booking_today; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-blue-600 font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">
                                Hari Ini
                            </div>
                        </div>

                        <!-- Card booking butuh respon -->
                        <div class="glass-card p-5 md:p-6 rounded-2xl shadow-sm border border-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">
                                        Butuh Respon
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-pink-600 mt-1">
                                        <!-- Total Booking Butuh Respon -->
                                        <?= (int) $total_pending; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-pink-50 text-pink-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-pink-600 font-bold bg-pink-50 inline-block px-2 py-0.5 rounded">
                                Waiting / Pending
                            </div>
                        </div>

                        <!-- Card pendapatan hari ini -->
                        <div class="glass-card p-5 md:p-6 rounded-2xl shadow-sm border border-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">
                                        Pendapatan Hari Ini
                                    </p>

                                    <h3 class="text-xl md:text-2xl font-bold text-gray-800 mt-1">
                                        <!-- Total Pendapatan Hari Ini -->
                                        Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-green-600 font-bold bg-green-50 inline-block px-2 py-0.5 rounded">
                                <!-- Total Pendapatan Hari Ini -->
                                Dari Transaksi
                            </div>
                        </div>

                        <!-- Card pelanggan -->
                        <div class="glass-card p-5 md:p-6 rounded-2xl shadow-sm border border-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">
                                        Pelanggan
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-800 mt-1">
                                        <!-- Total Pelanggan -->
                                        <?= (int) $total_customer; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-purple-600 font-bold bg-purple-50 inline-block px-2 py-0.5 rounded">
                                Customer
                            </div>
                        </div>

                        <!-- Card stok menipis -->
                        <div class="glass-card p-5 md:p-6 rounded-2xl shadow-sm border border-white">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-gray-500">
                                        Stok Menipis
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-red-600 mt-1">
                                        <!-- Total Stok Menipis -->
                                        <?= (int) $total_stok_menipis; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-red-600 font-bold bg-red-50 inline-block px-2 py-0.5 rounded">
                                Cek Stok
                            </div>
                        </div>
                    </div>

                    <!-- Grid tabel dashboard -->
                    <div class="grid grid-cols-1 xl:grid-cols-[1.6fr_1fr] gap-6">

                        <!-- Card histori booking -->
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">

                            <!-- Header tabel booking -->
                            <div class="px-6 py-4 border-b border-pink-50 flex justify-between items-center">
                                <div>
                                    <h4 class="font-bold text-gray-700">
                                        Histori Booking Terbaru
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Menampilkan 10 booking terbaru.
                                    </p>
                                </div>

                                <a href="data-booking.php" class="text-xs font-bold text-pink-600 hover:underline">
                                    Lihat Semua
                                </a>
                            </div>

                            <!-- Wrapper tabel booking -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left min-w-[780px]">

                                    <!-- Header kolom tabel -->
                                    <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                        <tr>
                                            <th class="px-6 py-4">Tanggal</th>
                                            <th class="px-6 py-4">Jam</th>
                                            <th class="px-6 py-4">Pelanggan</th>
                                            <th class="px-6 py-4">Layanan</th>
                                            <th class="px-6 py-4 text-center">Status</th>
                                            <th class="px-6 py-4 text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <!-- Isi tabel booking -->
                                    <tbody class="divide-y divide-pink-50">
                                        <!--CEK JIKA ADA DATA BOOKING MAKA TAMPILKAN DATA DARI TABLE BOOKING -->
                                        <?php if (mysqli_num_rows($query_booking) > 0) : ?> 

                                            <!-- Perulangan booking -->
                                            <?php while ($booking = mysqli_fetch_assoc($query_booking)) : ?>

                                                <tr class="hover:bg-pink-50/20 transition">

                                                    <!-- Tanggal booking -->
                                                    <td class="px-6 py-4 font-bold text-pink-600">
                                                        <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                                    </td>

                                                    <!-- Jam booking -->
                                                    <td class="px-6 py-4 text-gray-500">
                                                        <?= substr($booking['jam_mulai'], 0, 5); ?> -
                                                        <?= substr($booking['jam_selesai'], 0, 5); ?>
                                                    </td>

                                                    <!-- Nama pelanggan -->
                                                    <td class="px-6 py-4 font-semibold text-gray-700">
                                                        <?= htmlspecialchars($booking['nama']); ?>
                                                    </td>

                                                    <!-- Layanan booking -->
                                                    <td class="px-6 py-4 text-gray-500">
                                                        <?= htmlspecialchars($booking['nama_layanan'] ?: '-'); ?>
                                                    </td>

                                                    <!-- Status booking -->
                                                    <td class="px-6 py-4 text-center">
                                                        <span class="<?= statusBadgeClass($booking['status_booking']); ?> px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            <?= htmlspecialchars($booking['status_booking']); ?>
                                                        </span>
                                                    </td>

                                                    <!-- Tombol detail -->
                                                    <td class="px-6 py-4 text-center">
                                                        <a 
                                                            href="detail-booking.php?id_booking=<?= (int) $booking['id_booking']; ?>" 
                                                            class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:bg-pink-700 transition"
                                                        >
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>

                                            <?php endwhile; ?>

                                        <?php else : ?>

                                            <!-- Pesan data kosong -->
                                            <tr>
                                                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                                    Belum ada data booking.
                                                </td>
                                            </tr>

                                        <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Card transaksi terbaru -->
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">

                            <!-- Header transaksi -->
                            <div class="px-6 py-4 border-b border-pink-50">
                                <h4 class="font-bold text-gray-700">
                                    Transaksi Terbaru
                                </h4>

                                <p class="text-xs text-gray-400 mt-1">
                                    Pendapatan terbaru dari tabel transaksi.
                                </p>
                            </div>

                            <!-- List transaksi -->
                            <div class="p-4 space-y-3">

                                <?php if (mysqli_num_rows($query_transaksi_terbaru) > 0) : ?>

                                    <?php while ($transaksi = mysqli_fetch_assoc($query_transaksi_terbaru)) : ?>

                                        <!-- Item transaksi -->
                                        <div class="p-4 bg-pink-50/40 border border-pink-100 rounded-2xl">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="text-[11px] font-bold text-pink-600 uppercase">
                                                        #TRX-<?= str_pad($transaksi['id_transaksi'], 4, '0', STR_PAD_LEFT); ?>
                                                    </p>

                                                    <h5 class="text-sm font-bold text-gray-800 mt-1">
                                                        <?= htmlspecialchars($transaksi['nama_pelanggan']); ?>
                                                    </h5>

                                                    <p class="text-xs text-gray-400 mt-1">
                                                        <?= date('d M Y H:i', strtotime($transaksi['tanggal_transaksi'])); ?>
                                                    </p>
                                                </div>

                                                <p class="text-sm font-bold text-green-600 whitespace-nowrap">
                                                    Rp <?= number_format($transaksi['total_bayar'], 0, ',', '.'); ?>
                                                </p>
                                            </div>

                                            <span class="inline-block mt-3 text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded-lg">
                                                <?= htmlspecialchars($transaksi['jenis_pelanggan']); ?>
                                            </span>
                                        </div>

                                    <?php endwhile; ?>

                                <?php else : ?>

                                    <!-- Pesan transaksi kosong -->
                                    <div class="py-10 text-center text-gray-400">
                                        <i class="fa-solid fa-receipt text-3xl text-pink-100 mb-3"></i>

                                        <p class="text-sm">
                                            Belum ada transaksi.
                                        </p>
                                    </div>

                                <?php endif; ?>
                            </div>
                        </div>
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
