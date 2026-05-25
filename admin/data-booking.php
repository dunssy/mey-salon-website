<?php 
// Mengatur judul halaman
$page_title = "Data Booking";

// Memanggil layout dan koneksi database
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil filter status dari URL
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';

// Mengambil filter tanggal dari URL
$tanggal_filter = isset($_GET['tanggal']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal']) : '';

// Mengecek mode tampil semua data
$tampil_semua = isset($_GET['tampil']) && $_GET['tampil'] == 'semua';

// Menyiapkan kondisi filter booking
$where = "WHERE 1=1";

// Menjalankan filter tanggal hanya jika tidak tampil semua dan tanggal tidak kosong
if (!$tampil_semua && !empty($tanggal_filter)) {
    $where .= " AND DATE(b.tanggal_booking) = '$tanggal_filter'";
}

// Menjalankan filter status jika dipilih
if (!empty($status_filter)) {
    $where .= " AND b.status_booking = '$status_filter'";
}

// Mengambil data booking dan detail layanan
$query_booking = mysqli_query(
    $koneksi,
    "SELECT 
        b.id_booking,
        b.tanggal_booking,
        b.jam_mulai,
        b.jam_selesai,
        b.status_booking,
        b.tanggal_saran,
        b.jam_saran,
        b.catatan_admin,
        u.nama,
        u.no_hp,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan,
        SUM(l.harga_min) AS total_harga,
        SUM(l.durasi_layanan) AS total_durasi
     FROM booking b
     JOIN user u ON b.id_user = u.id_user
     LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan
     $where
     GROUP BY b.id_booking
     ORDER BY b.tanggal_booking DESC, b.jam_mulai ASC"
);

// Menghitung total booking sesuai filter aktif
$query_total_booking = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM booking b
     $where"
);
$total_booking = mysqli_fetch_assoc($query_total_booking)['total'] ?? 0;

// Menghitung booking waiting sesuai filter tanggal jika ada
$where_ringkasan = "WHERE 1=1";

if (!$tampil_semua && !empty($tanggal_filter)) {
    $where_ringkasan .= " AND DATE(tanggal_booking) = '$tanggal_filter'";
}

$query_waiting = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM booking 
     $where_ringkasan 
     AND status_booking = 'Waiting'"
);
$total_waiting = mysqli_fetch_assoc($query_waiting)['total'] ?? 0;

// Menghitung booking pending sesuai filter tanggal jika ada
$query_pending = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM booking 
     $where_ringkasan 
     AND status_booking = 'Pending'"
);
$total_pending = mysqli_fetch_assoc($query_pending)['total'] ?? 0;

// Menghitung booking on-going sesuai filter tanggal jika ada
$query_ongoing = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total 
     FROM booking 
     $where_ringkasan 
     AND status_booking = 'On-going'"
);
$total_ongoing = mysqli_fetch_assoc($query_ongoing)['total'] ?? 0;

// Menentukan label periode data
$label_data = $tampil_semua || empty($tanggal_filter)
    ? 'Semua Data Booking'
    : date('d M Y', strtotime($tanggal_filter));

// Mengatur warna badge status booking
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

                    <!-- Header halaman -->
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                Data Booking
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Kelola semua booking pelanggan, konfirmasi jadwal, dan proses layanan salon.
                            </p>
                        </div>

                        <!-- Filter booking -->
                        <form action="" method="GET" class="bg-white p-4 rounded-2xl border border-pink-100 shadow-sm">
                            <div class="flex flex-col sm:flex-row sm:items-end gap-3">

                                <!-- Filter tampilan -->
                                <div>
                                    <label for="tampil" class="block text-[11px] font-bold text-gray-400 uppercase mb-1">
                                        Tampilan
                                    </label>

                                    <select 
                                        name="tampil" 
                                        id="tampil"
                                        onchange="toggleTanggalBooking()"
                                        class="px-3 py-2 text-sm bg-white border border-pink-100 rounded-xl focus:outline-none focus:border-pink-300 text-gray-600"
                                    >
                                        <option value="semua" <?= $tampil_semua || empty($tanggal_filter) ? 'selected' : ''; ?>>
                                            Semua Data
                                        </option>
                                        <option value="tanggal" <?= !$tampil_semua && !empty($tanggal_filter) ? 'selected' : ''; ?>>
                                            Per Tanggal
                                        </option>
                                    </select>
                                </div>

                                <!-- Filter tanggal -->
                                <div id="filter-tanggal-booking">
                                    <label for="tanggal" class="block text-[11px] font-bold text-gray-400 uppercase mb-1">
                                        Tanggal
                                    </label>

                                    <input 
                                        type="date" 
                                        name="tanggal" 
                                        id="tanggal"
                                        value="<?= htmlspecialchars($tanggal_filter); ?>"
                                        class="px-3 py-2 text-sm bg-white border border-pink-100 rounded-xl focus:outline-none focus:border-pink-300 text-gray-600"
                                    >
                                </div>

                                <!-- Filter status -->
                                <div>
                                    <label for="status" class="block text-[11px] font-bold text-gray-400 uppercase mb-1">
                                        Status
                                    </label>

                                    <select 
                                        name="status" 
                                        id="status"
                                        class="px-3 py-2 text-sm bg-white border border-pink-100 rounded-xl focus:outline-none focus:border-pink-300 text-gray-600"
                                    >
                                        <option value="">Semua Status</option>
                                        <option value="Waiting" <?= $status_filter == 'Waiting' ? 'selected' : ''; ?>>Waiting</option>
                                        <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="On-going" <?= $status_filter == 'On-going' ? 'selected' : ''; ?>>On-going</option>
                                        <option value="Done" <?= $status_filter == 'Done' ? 'selected' : ''; ?>>Done</option>
                                        <option value="Cancel" <?= $status_filter == 'Cancel' ? 'selected' : ''; ?>>Cancel</option>
                                    </select>
                                </div>

                                <!-- Tombol filter -->
                                <button 
                                    type="submit"
                                    class="px-4 py-2 text-sm font-semibold text-white bg-pink-600 hover:bg-pink-700 rounded-xl shadow-sm shadow-pink-100 transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="fa-solid fa-filter"></i>
                                    <span>Filter</span>
                                </button>

                                <!-- Tombol reset semua data -->
                                <a 
                                    href="data-booking.php?tampil=semua"
                                    class="px-4 py-2 text-sm font-semibold text-pink-600 bg-pink-50 hover:bg-pink-100 rounded-xl transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="fa-solid fa-list"></i>
                                    <span>Semua</span>
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Info data aktif -->
                    <div class="bg-white border border-pink-100 rounded-2xl p-4 shadow-sm">
                        <p class="text-sm text-gray-500">
                            Menampilkan:
                            <span class="font-bold text-pink-600">
                                <?= htmlspecialchars($label_data); ?>
                            </span>

                            <?php if (!empty($status_filter)) : ?>
                                <span class="ml-1">
                                    dengan status
                                    <b class="text-pink-600"><?= htmlspecialchars($status_filter); ?></b>
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Ringkasan booking -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                        <!-- Total booking -->
                        <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Total Booking
                            </p>

                            <h4 class="text-2xl font-bold text-gray-800 mt-1">
                                <?= (int) $total_booking; ?>
                            </h4>

                            <p class="text-xs text-gray-400 mt-2">
                                Berdasarkan filter aktif.
                            </p>
                        </div>

                        <!-- Booking waiting -->
                        <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Waiting
                            </p>

                            <h4 class="text-2xl font-bold text-yellow-600 mt-1">
                                <?= (int) $total_waiting; ?>
                            </h4>

                            <p class="text-xs text-gray-400 mt-2">
                                Menunggu konfirmasi admin.
                            </p>
                        </div>

                        <!-- Booking pending -->
                        <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Pending
                            </p>

                            <h4 class="text-2xl font-bold text-orange-600 mt-1">
                                <?= (int) $total_pending; ?>
                            </h4>

                            <p class="text-xs text-gray-400 mt-2">
                                Menunggu persetujuan jadwal.
                            </p>
                        </div>

                        <!-- Booking on-going -->
                        <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                On-going
                            </p>

                            <h4 class="text-2xl font-bold text-blue-600 mt-1">
                                <?= (int) $total_ongoing; ?>
                            </h4>

                            <p class="text-xs text-gray-400 mt-2">
                                Sedang diproses.
                            </p>
                        </div>
                    </div>

                    <!-- Card tabel booking -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">

                        <!-- Header tabel -->
                        <div class="px-6 py-4 border-b border-pink-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-gray-700">
                                    Antrean Booking
                                </h4>

                                <p class="text-xs text-gray-400 mt-1">
                                    Default halaman ini menampilkan semua data booking.
                                </p>
                            </div>

                            <a href="data-booking.php?tampil=semua" class="p-2 text-pink-600 hover:bg-pink-50 rounded-lg transition">
                                <i class="fa-solid fa-rotate"></i>
                            </a>
                        </div>

                        <!-- Tabel responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[1000px]">

                                <!-- Header kolom tabel -->
                                <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Jam</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4">Layanan</th>
                                        <th class="px-6 py-4">Total</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel booking -->
                                <tbody class="divide-y divide-pink-50">

                                    <?php if (mysqli_num_rows($query_booking) > 0) : ?>

                                        <!-- Perulangan data booking -->
                                        <?php while ($booking = mysqli_fetch_assoc($query_booking)) : ?>

                                            <tr class="hover:bg-pink-50/20 transition">

                                                <!-- Tanggal booking -->
                                                <td class="px-6 py-4 font-bold text-pink-600">
                                                    <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                                </td>

                                                <!-- Jam booking -->
                                                <td class="px-6 py-4 text-gray-600">
                                                    <span class="font-bold">
                                                        <?= substr($booking['jam_mulai'], 0, 5); ?>
                                                    </span>
                                                    -
                                                    <span>
                                                        <?= substr($booking['jam_selesai'], 0, 5); ?>
                                                    </span>

                                                    <?php if ($booking['status_booking'] == 'Pending' && !empty($booking['tanggal_saran']) && !empty($booking['jam_saran'])) : ?>
                                                        <span class="block text-[11px] text-orange-600 mt-1">
                                                            Saran:
                                                            <?= date('d M Y', strtotime($booking['tanggal_saran'])); ?>,
                                                            <?= substr($booking['jam_saran'], 0, 5); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Nama pelanggan -->
                                                <td class="px-6 py-4">
                                                    <p class="font-semibold text-gray-700">
                                                        <?= htmlspecialchars($booking['nama']); ?>
                                                    </p>

                                                    <p class="text-xs text-gray-400 mt-1">
                                                        <?= htmlspecialchars($booking['no_hp']); ?>
                                                    </p>
                                                </td>

                                                <!-- Layanan booking -->
                                                <td class="px-6 py-4 text-gray-600">
                                                    <?= htmlspecialchars($booking['nama_layanan'] ?: '-'); ?>

                                                    <?php if (!empty($booking['catatan'])) : ?>
                                                        <span class="block text-[11px] text-gray-400 mt-1 italic">
                                                            Catatan: <?= htmlspecialchars($booking['catatan']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Total harga -->
                                                <td class="px-6 py-4 font-bold text-gray-800">
                                                    Rp <?= number_format($booking['total_harga'] ?? 0, 0, ',', '.'); ?>

                                                    <span class="block text-[11px] font-normal text-gray-400 mt-1">
                                                        <?= (int) ($booking['total_durasi'] ?? 0); ?> menit
                                                    </span>
                                                </td>

                                                <!-- Status booking -->
                                                <td class="px-6 py-4 text-center">
                                                    <span class="<?= statusBadgeClass($booking['status_booking']); ?> px-3 py-1 text-[10px] font-bold rounded-lg uppercase">
                                                        <?= htmlspecialchars($booking['status_booking']); ?>
                                                    </span>
                                                </td>

                                                <!-- Tombol aksi -->
                                                <td class="px-6 py-4 text-center">
                                                    <a 
                                                        href="detail-booking.php?id_booking=<?= (int) $booking['id_booking']; ?>" 
                                                        class="inline-flex items-center justify-center gap-2 bg-pink-600 text-white px-3 py-2 text-[10px] font-bold rounded-lg hover:bg-pink-700 transition"
                                                    >
                                                        <i class="fa-solid fa-eye"></i>
                                                        <span>Detail</span>
                                                    </a>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <!-- Pesan jika data booking kosong -->
                                        <tr>
                                            <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">
                                                Belum ada data booking pada filter ini.
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

    <!-- Script filter booking -->
    <script>
        // Menampilkan atau menyembunyikan filter tanggal
        function toggleTanggalBooking() {
            const tampil = document.getElementById('tampil').value;
            const filterTanggal = document.getElementById('filter-tanggal-booking');
            const inputTanggal = document.getElementById('tanggal');

            if (tampil === 'semua') {
                filterTanggal.style.display = 'none';
                inputTanggal.value = '';
            } else {
                filterTanggal.style.display = 'block';

                if (inputTanggal.value === '') {
                    inputTanggal.value = '<?= date('Y-m-d'); ?>';
                }
            }
        }

        // Menjalankan filter saat halaman dibuka
        toggleTanggalBooking();
    </script>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>
