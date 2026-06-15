<?php 
$page_title = "Data Booking";

include "../layout/header.php";
include "../config/app.php";

global $koneksi;

$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($koneksi, $_GET['status']) : '';
$tanggal_filter = isset($_GET['tanggal']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal']) : '';
$tampil_semua = isset($_GET['tampil']) && $_GET['tampil'] == 'semua';

$where = "WHERE 1=1";

if (!$tampil_semua && !empty($tanggal_filter)) {
    $where .= " AND DATE(b.tanggal_booking) = '$tanggal_filter'";
}

if (!empty($status_filter)) {
    $where .= " AND b.status_booking = '$status_filter'";
}

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
        b.bukti_pembayaran,
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

$query_total_booking = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM booking b $where"
);
$total_booking = mysqli_fetch_assoc($query_total_booking)['total'] ?? 0;

$where_ringkasan = "WHERE 1=1";

if (!$tampil_semua && !empty($tanggal_filter)) {
    $where_ringkasan .= " AND DATE(tanggal_booking) = '$tanggal_filter'";
}

$query_waiting = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM booking $where_ringkasan AND status_booking = 'Waiting'"
);
$total_waiting = mysqli_fetch_assoc($query_waiting)['total'] ?? 0;

$query_pending = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM booking $where_ringkasan AND status_booking = 'Pending'"
);
$total_pending = mysqli_fetch_assoc($query_pending)['total'] ?? 0;

$query_ongoing = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM booking $where_ringkasan AND status_booking = 'On-going'"
);
$total_ongoing = mysqli_fetch_assoc($query_ongoing)['total'] ?? 0;

$label_data = $tampil_semua || empty($tanggal_filter)
    ? 'Semua Data Booking'
    : date('d M Y', strtotime($tanggal_filter));

function statusBadgeClass($status)
{
    if ($status == 'Waiting') return 'bg-yellow-100 text-yellow-700';
    if ($status == 'Pending') return 'bg-orange-100 text-orange-700';
    if ($status == 'On-going') return 'bg-blue-100 text-blue-700';
    if ($status == 'Done') return 'bg-green-100 text-green-700';
    if ($status == 'Cancel') return 'bg-red-100 text-red-700';
    return 'bg-gray-100 text-[#3D3134]';
}
?>

<body class="text-[#2B2424] overflow-x-hidden bg-[#FFF7FA]">

    <div class="flex h-screen overflow-hidden">

        <?php include "../layout/sidebar.php"; ?>

        <main class="flex-1 flex flex-col overflow-y-auto bg-[#FFF7FA]">

            <?php include "../layout/navbar.php"; ?>

            <div class="px-4 py-5 md:px-6 lg:px-8 flex-1">

                <section id="section-booking" class="space-y-5">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    Data Booking
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Kelola semua booking pelanggan.
                                </p>
                            </div>
                        </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                        Total Booking
                                    </p>

                                    <h4 class="text-2xl font-extrabold text-[#2B2424] mt-1">
                                        <?= (int) $total_booking; ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-[#FDEAF1] text-[#C75C7A] rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-calendar-check text-sm"></i>
                                </div>
                            </div>

                            <p class="text-[11px] text-[#B77B8E] mt-2">
                                <?= htmlspecialchars($label_data); ?>
                            </p>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                        Waiting
                                    </p>

                                    <h4 class="text-2xl font-extrabold text-yellow-600 mt-1">
                                        <?= (int) $total_waiting; ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-clock text-sm"></i>
                                </div>
                            </div>

                            <p class="text-[11px] text-[#B77B8E] mt-2">
                                Menunggu konfirmasi.
                            </p>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                        Pending
                                    </p>

                                    <h4 class="text-2xl font-extrabold text-orange-600 mt-1">
                                        <?= (int) $total_pending; ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-calendar-plus text-sm"></i>
                                </div>
                            </div>

                            <p class="text-[11px] text-[#B77B8E] mt-2">
                                Menunggu persetujuan.
                            </p>
                        </div>

                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm hover:shadow-md hover:shadow-[#FAD7E5]/60 transition">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                        On-going
                                    </p>

                                    <h4 class="text-2xl font-extrabold text-blue-600 mt-1">
                                        <?= (int) $total_ongoing; ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-scissors text-sm"></i>
                                </div>
                            </div>

                            <p class="text-[11px] text-[#B77B8E] mt-2">
                                Sedang diproses.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#F7D6E4]">

                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-[#3D3134]">
                                    Antrean Booking
                                </h4>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[11px] font-bold text-[#C75C7A] bg-[#FDEAF1] px-3 py-1.5 rounded-lg">
                                    <?= (int) $total_booking; ?> Data
                                </span>

                                <a 
                                    href="data-booking.php?tampil=semua"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-[11px] font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-lg hover:bg-[#FAD7E5] transition"
                                >
                                    <i class="fa-solid fa-list"></i>
                                    <span>Semua</span>
                                </a>

                                <a 
                                    href="data-booking.php?tanggal=<?= date('Y-m-d'); ?>"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 text-[11px] font-bold text-[#6F5E64] bg-white border border-[#F7D6E4] rounded-lg hover:bg-[#FDEAF1] transition"
                                >
                                    <i class="fa-solid fa-calendar-day"></i>
                                    <span>Hari Ini</span>
                                </a>

                                <a href="data-booking.php?tampil=semua" class="w-9 h-9 inline-flex items-center justify-center text-[#C75C7A] hover:bg-[#FDEAF1] rounded-lg transition">
                                    <i class="fa-solid fa-rotate"></i>
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[1080px] text-sm">

                                <thead class="bg-[#EFA9BF] text-white text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-5 py-3">Tanggal</th>
                                        <th class="px-5 py-3">Jam</th>
                                        <th class="px-5 py-3">Pelanggan</th>
                                        <th class="px-5 py-3">Layanan</th>
                                        <th class="px-5 py-3">Estimasi</th>
                                        <th class="px-5 py-3 text-center">Bukti</th>
                                        <th class="px-5 py-3 text-center">Status</th>
                                        <th class="px-5 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-[#F7D6E4]">

                                    <?php if (mysqli_num_rows($query_booking) > 0) : ?>

                                        <?php while ($booking = mysqli_fetch_assoc($query_booking)) : ?>

                                            <tr class="hover:bg-[#FDEAF1]/50 transition">

                                                <td class="px-5 py-3 font-bold text-[#C75C7A] whitespace-nowrap">
                                                    <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                                </td>

                                                <td class="px-5 py-3 text-[#6F5E64] whitespace-nowrap">
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

                                                <td class="px-5 py-3">
                                                    <p class="font-semibold text-[#3D3134] whitespace-nowrap">
                                                        <?= htmlspecialchars($booking['nama']); ?>
                                                    </p>

                                                    <p class="text-xs text-[#B77B8E] mt-1 whitespace-nowrap">
                                                        <?= htmlspecialchars($booking['no_hp']); ?>
                                                    </p>
                                                </td>

                                                <td class="px-5 py-3 text-[#6F5E64] max-w-[280px]">
                                                    <p class="line-clamp-2">
                                                        <?= htmlspecialchars($booking['nama_layanan'] ?: '-'); ?>
                                                    </p>

                                                    <?php if (!empty($booking['catatan_admin'])) : ?>
                                                        <span class="block text-[11px] text-[#B77B8E] mt-1 italic line-clamp-1">
                                                            Admin: <?= htmlspecialchars($booking['catatan_admin']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="px-5 py-3 font-bold text-[#2B2424] whitespace-nowrap">
                                                    Rp <?= number_format($booking['total_harga'] ?? 0, 0, ',', '.'); ?>

                                                    <span class="block text-[11px] font-normal text-[#B77B8E] mt-1">
                                                        <?= (int) ($booking['total_durasi'] ?? 0); ?> menit
                                                    </span>
                                                </td>

                                                <td class="px-5 py-3 text-center">
                                                    <?php if (!empty($booking['bukti_pembayaran'])) : ?>
                                                        <a 
                                                            href="../uploads/bukti-pembayaran/<?= htmlspecialchars($booking['bukti_pembayaran']); ?>" 
                                                            target="_blank"
                                                            class="inline-flex items-center justify-center w-9 h-9 bg-green-50 text-green-600 rounded-xl hover:bg-green-100 transition"
                                                            title="Lihat bukti pembayaran"
                                                        >
                                                            <i class="fa-solid fa-receipt"></i>
                                                        </a>
                                                    <?php else : ?>
                                                        <span class="inline-flex items-center justify-center w-9 h-9 bg-gray-50 text-gray-300 rounded-xl">
                                                            <i class="fa-solid fa-minus"></i>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="px-5 py-3 text-center">
                                                    <span class="<?= statusBadgeClass($booking['status_booking']); ?> px-3 py-1 text-[10px] font-bold rounded-lg uppercase whitespace-nowrap">
                                                        <?= htmlspecialchars($booking['status_booking']); ?>
                                                    </span>
                                                </td>

                                                <td class="px-5 py-3 text-center">
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

                                        <tr>
                                            <td colspan="8" class="px-5 py-10 text-center text-[#B77B8E] italic">
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

            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>
