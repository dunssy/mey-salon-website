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
// MENGAMBIL 5 BOOKING TERBARU DENGAN JOIN KE TABEL USER UNTUK MENDAPATKAN NAMA PELANGGAN, DAN JOIN KE TABEL BOOKING_DETAIL DAN LAYANAN UNTUK MENDAPATKAN NAMA LAYANAN
$query_booking = mysqli_query(
    $koneksi,
    "SELECT b.id_booking,b.tanggal_booking,b.jam_mulai,b.jam_selesai,
        b.status_booking,u.nama,GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan
     FROM booking b JOIN user u ON b.id_user = u.id_user LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan GROUP BY b.id_booking ORDER BY b.tanggal_booking DESC, b.jam_mulai DESC LIMIT 5"
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

    return 'bg-gray-100 text-[#3D3134]';
}
?>

<body class="text-[#2B2424] overflow-x-hidden bg-[#FFF7FA]">

    <!-- Wrapper utama halaman admin -->
    <div class="flex h-screen overflow-hidden">

        <!-- Memanggil sidebar admin -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- Konten utama dashboard -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-[#FDEAF1]/40">

            <!-- Memanggil navbar admin -->
            <?php include "../layout/navbar.php"; ?>
        <!-- ALERT SUCCESS LOGIN -->
        <?php if (isset($_SESSION['success'])) : ?>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Selamat Datang 👋',
                        html: '<b><?= htmlspecialchars($_SESSION["nama"]); ?>.',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                });
            </script>

            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
            <!-- Isi halaman dashboard -->
            <div class="p-4 sm:p-5 md:p-8 flex-1">

                <!-- Section dashboard -->
                <section id="section-dashboard" class="space-y-6 md:space-y-8">

                    <!-- Header dashboard -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                Dashboard
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Ringkasan data Mey Salon.
                            </p>
                        </div>

                        <a 
                            href="dashboard-admin.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-white border border-[#F7D6E4] rounded-xl hover:bg-[#FDEAF1] shadow-sm transition"
                        >
                            <i class="fa-solid fa-rotate"></i>
                            <span>Refresh</span>
                        </a>
                    </div>

                    <!-- Grid statistik dashboard -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-5">

                        <!-- Card booking hari ini -->
                        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-[#F7D6E4] hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#7A6F6F]">
                                        Booking Hari Ini
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-[#2B2424] mt-1">
                                        <!-- Total Booking Hari Ini -->
                                        <?= (int) $total_booking_today; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-[#EEF6FF] text-[#3B82F6] rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-calendar-day"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-[#3B82F6] font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">
                                Hari Ini
                            </div>
                        </div>

                        <!-- Card booking butuh respon -->
                        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-[#F7D6E4] hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#7A6F6F]">
                                        Butuh Respon
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-[#C75C7A] mt-1">
                                        <!-- Total Booking Butuh Respon -->
                                        <?= (int) $total_pending; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-[#FDEAF1] text-[#C75C7A] rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-[#C75C7A] font-bold bg-[#FDEAF1] inline-block px-2 py-0.5 rounded">
                                Waiting / Pending
                            </div>
                        </div>

                        <!-- Card pendapatan hari ini -->
                        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-[#F7D6E4] hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#7A6F6F]">
                                        Pendapatan Hari Ini
                                    </p>

                                    <h3 class="text-xl md:text-2xl font-bold text-[#2B2424] mt-1">
                                        <!-- Total Pendapatan Hari Ini -->
                                        Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-[#ECFDF3] text-[#16A34A] rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-[#16A34A] font-bold bg-green-50 inline-block px-2 py-0.5 rounded">
                                <!-- Total Pendapatan Hari Ini -->
                                Dari Transaksi
                            </div>
                        </div>

                        <!-- Card stok menipis -->
                        <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border border-[#F7D6E4] hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#7A6F6F]">
                                        Stok Menipis
                                    </p>

                                    <h3 class="text-2xl md:text-3xl font-bold text-[#E11D48] mt-1">
                                        <!-- Total Stok Menipis -->
                                        <?= (int) $total_stok_menipis; ?>
                                    </h3>
                                </div>

                                <div class="w-10 h-10 bg-[#FFF1F2] text-[#E11D48] rounded-xl flex items-center justify-center">
                                    <i class="fa-solid fa-box-open"></i>
                                </div>
                            </div>

                            <div class="mt-3 text-[10px] text-[#E11D48] font-bold bg-red-50 inline-block px-2 py-0.5 rounded">
                                Cek Stok
                            </div>
                        </div>
                    </div>

                    <!-- Grid tabel dashboard -->
                    <div class="grid grid-cols-1 gap-6">

                        <!-- Card histori booking -->
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#F7D6E4]">

                            <!-- Header tabel booking -->
                            <div class="px-4 sm:px-6 md:px-8 py-5 md:py-6 border-b border-[#F7D6E4] flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div>
                                    <h4 class="font-bold text-[#3D3134]">
                                        Histori Booking Terbaru
                                    </h4>

                                    <p class="text-xs text-[#B77B8E] mt-1">
                                        Menampilkan 5 booking terbaru.
                                    </p>
                                </div>

                                <a href="data-booking.php" class="text-xs font-bold text-[#C75C7A] hover:underline">
                                    Lihat Semua
                                </a>
                            </div>

                            <!-- Wrapper tabel booking -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left min-w-[780px]">

                                    <!-- Header kolom tabel -->
                                    <thead class="bg-[#EFA9BF] text-white text-[10px] uppercase font-bold tracking-widest">                                        <tr>
                                            <th class="px-5 md:px-8 py-4 md:py-5">Tanggal</th>
                                            <th class="px-5 md:px-8 py-4 md:py-5">Jam</th>
                                            <th class="px-5 md:px-8 py-4 md:py-5">Pelanggan</th>
                                            <th class="px-5 md:px-8 py-4 md:py-5">Layanan</th>
                                            <th class="px-5 md:px-8 py-4 md:py-5 text-center">Status</th>
                                            <th class="px-5 md:px-8 py-4 md:py-5 text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <!-- Isi tabel booking -->
                                    <tbody class="divide-y divide-[#F7D6E4]">
                                        <!--CEK JIKA ADA DATA BOOKING MAKA TAMPILKAN DATA DARI TABLE BOOKING -->
                                        <?php if (mysqli_num_rows($query_booking) > 0) : ?> 

                                            <!-- Perulangan booking -->
                                            <?php while ($booking = mysqli_fetch_assoc($query_booking)) : ?>

                                                <tr class="hover:bg-[#FDEAF1]/50 transition">

                                                    <!-- Tanggal booking -->
                                                    <td class="px-5 md:px-8 py-4 md:py-5 font-bold text-[#C75C7A]">
                                                        <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                                    </td>

                                                    <!-- Jam booking -->
                                                    <td class="px-5 md:px-8 py-4 md:py-5 text-[#7A6F6F]">
                                                        <?= substr($booking['jam_mulai'], 0, 5); ?> -
                                                        <?= substr($booking['jam_selesai'], 0, 5); ?>
                                                    </td>

                                                    <!-- Nama pelanggan -->
                                                    <td class="px-5 md:px-8 py-4 md:py-5 font-semibold text-[#3D3134]">
                                                        <?= htmlspecialchars($booking['nama']); ?>
                                                    </td>

                                                    <!-- Layanan booking -->
                                                    <td class="px-5 md:px-8 py-4 md:py-5 text-[#7A6F6F]">
                                                        <?= htmlspecialchars($booking['nama_layanan'] ?: '-'); ?>
                                                    </td>

                                                    <!-- Status booking -->
                                                    <td class="px-5 md:px-8 py-4 md:py-5 text-center">
                                                        <span class="<?= statusBadgeClass($booking['status_booking']); ?> px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                            <?= htmlspecialchars($booking['status_booking']); ?>
                                                        </span>
                                                    </td>

                                                    <!-- Tombol detail -->
                                                    <td class="px-5 md:px-8 py-4 md:py-5 text-center">
                                                        <a 
                                                        href="detail-booking.php?id_booking=<?= (int) $booking['id_booking']; ?>" 
                                                        class="inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-50 text-green-500 rounded-lg hover:bg-green-100 transition text-[10px] font-bold whitespace-nowrap"
                                                        title="Detail"
                                                    >
                                                        <i class="fa-solid fa-eye text-xs"></i>
                                                        <span>Detail</span>
                                                    </a>
                                                    </td>
                                                </tr>

                                            <?php endwhile; ?>

                                        <?php else : ?>

                                            <!-- Pesan data kosong -->
                                            <tr>
                                                <td colspan="6" class="px-6 py-10 text-center text-[#B77B8E] italic">
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

            <!-- Memanggil footer dashboard -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>
<?php
// Memanggil footer utama
include "../layout/footer.php";
?>
