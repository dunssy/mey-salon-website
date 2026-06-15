<?php
// Mengatur judul halaman
$page_title = "Laporan";

// Memanggil header layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek kolom walk-in di tabel transaksi
$cek_nama_walkin = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'nama_pelanggan_walkin'");
$cek_layanan_manual = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'layanan_manual'");

$kolom_nama_walkin_ada = mysqli_num_rows($cek_nama_walkin) > 0;
$kolom_layanan_manual_ada = mysqli_num_rows($cek_layanan_manual) > 0;

// Menyiapkan query kolom walk-in agar tidak error jika kolom belum ada
$select_nama_walkin = $kolom_nama_walkin_ada ? "t.nama_pelanggan_walkin" : "NULL";
$select_layanan_manual = $kolom_layanan_manual_ada ? "t.layanan_manual" : "NULL";

// Mengambil filter laporan
$jenis_laporan = isset($_GET['jenis']) ? $_GET['jenis'] : 'harian';
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');

// Mengamankan nilai filter
$jenis_laporan = $jenis_laporan === 'bulanan' ? 'bulanan' : 'harian';
$tanggal = mysqli_real_escape_string($koneksi, $tanggal);
$bulan = mysqli_real_escape_string($koneksi, $bulan);

// Menentukan filter laporan
if ($jenis_laporan === 'bulanan') {
    $where_transaksi = "DATE_FORMAT(t.tanggal_transaksi, '%Y-%m') = '$bulan'";
    $where_booking = "DATE_FORMAT(b.tanggal_booking, '%Y-%m') = '$bulan'";
    $where_restok = "DATE_FORMAT(r.tanggal_restok, '%Y-%m') = '$bulan'";
    $where_pengeluaran = "DATE_FORMAT(p.tanggal_pengeluaran, '%Y-%m') = '$bulan'";
    $label_periode = date('F Y', strtotime($bulan . '-01'));
} else {
    $where_transaksi = "DATE(t.tanggal_transaksi) = '$tanggal'";
    $where_booking = "DATE(b.tanggal_booking) = '$tanggal'";
    $where_restok = "DATE(r.tanggal_restok) = '$tanggal'";
    $where_pengeluaran = "DATE(p.tanggal_pengeluaran) = '$tanggal'";
    $label_periode = date('d F Y', strtotime($tanggal));
}

// Mengambil pendapatan dari transaksi
$query_pendapatan = mysqli_query($koneksi, "
    SELECT 
        t.id_transaksi,
        t.id_booking,
        t.tanggal_transaksi,
        t.total_bayar,
        t.jenis_pelanggan,
        COALESCE(u.nama, $select_nama_walkin, 'Pelanggan Datang Langsung') AS nama_pelanggan,
        COALESCE(
            GROUP_CONCAT(DISTINCT l_transaksi.nama_layanan SEPARATOR ', '),
            GROUP_CONCAT(DISTINCT l_booking.nama_layanan SEPARATOR ', '),
            $select_layanan_manual,
            '-'
        ) AS nama_layanan
    FROM transaksi t
    LEFT JOIN booking b ON t.id_booking = b.id_booking
    LEFT JOIN user u ON b.id_user = u.id_user
    LEFT JOIN transaksi_detail td ON t.id_transaksi = td.id_transaksi
    LEFT JOIN layanan l_transaksi ON td.id_layanan = l_transaksi.id_layanan
    LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
    LEFT JOIN layanan l_booking ON bd.id_layanan = l_booking.id_layanan
    WHERE $where_transaksi
    GROUP BY t.id_transaksi
    ORDER BY t.tanggal_transaksi DESC
");

// Mengambil restok sebagai pengeluaran
$query_restok = mysqli_query($koneksi, "
    SELECT 
        r.id_restok,
        r.tanggal_restok,
        r.jumlah_tambah,
        r.total_harga_restok,
        s.nama_barang
    FROM restok r
    JOIN stok_barang s ON r.id_barang = s.id_barang
    WHERE $where_restok
    ORDER BY r.tanggal_restok DESC
");

// Mengambil pengeluaran manual
$query_pengeluaran = mysqli_query($koneksi, "
    SELECT 
        p.id_pengeluaran,
        p.jenis_pengeluaran,
        p.jumlah_pengeluaran,
        p.tanggal_pengeluaran,
        p.keterangan_pengeluaran,
        u.nama AS nama_user
    FROM pengeluaran p
    LEFT JOIN user u ON p.id_user = u.id_user
    WHERE $where_pengeluaran
    ORDER BY p.tanggal_pengeluaran DESC
");

// Menyiapkan data pendapatan
$data_pendapatan = [];
$total_pendapatan = 0;
$total_transaksi = 0;
$total_walkin = 0;
$total_booking_transaksi = 0;

if ($query_pendapatan) {
    while ($row = mysqli_fetch_assoc($query_pendapatan)) {
        $data_pendapatan[] = $row;
        $total_pendapatan += (int) $row['total_bayar'];
        $total_transaksi++;

        if ($row['jenis_pelanggan'] === 'walk-in') {
            $total_walkin++;
        } else {
            $total_booking_transaksi++;
        }
    }
}

// Menyiapkan data restok
$data_restok = [];
$total_restok = 0;
$total_data_restok = 0;

if ($query_restok) {
    while ($row = mysqli_fetch_assoc($query_restok)) {
        $data_restok[] = $row;
        $total_restok += (int) $row['total_harga_restok'];
        $total_data_restok++;
    }
}

// Menyiapkan data pengeluaran manual
$data_pengeluaran = [];
$total_pengeluaran_manual = 0;
$total_data_pengeluaran_manual = 0;

if ($query_pengeluaran) {
    while ($row = mysqli_fetch_assoc($query_pengeluaran)) {
        $data_pengeluaran[] = $row;
        $total_pengeluaran_manual += (int) $row['jumlah_pengeluaran'];
        $total_data_pengeluaran_manual++;
    }
}

// Menghitung total pengeluaran, total data pengeluaran, dan laba bersih
$total_pengeluaran = $total_restok + $total_pengeluaran_manual;
$total_data_pengeluaran = $total_data_restok + $total_data_pengeluaran_manual;
$laba_bersih = $total_pendapatan - $total_pengeluaran;

// Mengambil layanan terfavorit dari transaksi
$query_layanan_favorit = mysqli_query($koneksi, "
    SELECT 
        l.nama_layanan,
        COUNT(td.id_layanan) AS total_dipilih
    FROM transaksi t
    JOIN transaksi_detail td ON t.id_transaksi = td.id_transaksi
    JOIN layanan l ON td.id_layanan = l.id_layanan
    WHERE $where_transaksi
    GROUP BY td.id_layanan
    ORDER BY total_dipilih DESC
    LIMIT 1
");

// Mengambil layanan favorit dari booking jika transaksi detail kosong
$layanan_favorit = $query_layanan_favorit ? mysqli_fetch_assoc($query_layanan_favorit) : null;

if (!$layanan_favorit) {
    $query_layanan_favorit_booking = mysqli_query($koneksi, "
        SELECT 
            l.nama_layanan,
            COUNT(bd.id_layanan) AS total_dipilih
        FROM booking b
        JOIN booking_detail bd ON b.id_booking = bd.id_booking
        JOIN layanan l ON bd.id_layanan = l.id_layanan
        WHERE $where_booking
        AND b.status_booking = 'Done'
        GROUP BY bd.id_layanan
        ORDER BY total_dipilih DESC
        LIMIT 1
    ");

    $layanan_favorit = $query_layanan_favorit_booking ? mysqli_fetch_assoc($query_layanan_favorit_booking) : null;
}
?>

<body class="text-[#2B2424] overflow-x-hidden bg-[#FFF7FA]">

    <!-- Wrapper utama halaman admin -->
    <div class="flex h-screen overflow-hidden">

        <!-- Memanggil sidebar -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- Konten utama -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-[#FFF7FA]">

            <!-- Memanggil navbar -->
            <?php include "../layout/navbar.php"; ?>

            <!-- Isi halaman -->
            <div class="p-4 sm:p-5 md:p-8 flex-1">

                <!-- Section laporan -->
                <section id="section-laporan" class="space-y-6">

                    <!-- Header laporan minimalis -->
                        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">

                            <!-- Judul laporan -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424] tracking-tight">
                                    <?= htmlspecialchars($page_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Periode:
                                    <span class="font-bold text-[#C75C7A]">
                                        <?= htmlspecialchars($label_periode); ?>
                                    </span>
                                </p>
                            </div>

                            <!-- Filter laporan minimalis -->
                            <form action="" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-2">

                                <!-- Pilih jenis laporan -->
                                <select 
                                    name="jenis" 
                                    id="jenis_laporan"
                                    onchange="toggleFilterInput()"
                                    class="px-3 py-2 text-sm bg-[#FFF7FA] border border-[#F7D6E4] rounded-xl focus:outline-none focus:border-[#C75C7A] text-[#6F5E64]"
                                >
                                    <option value="harian" <?= $jenis_laporan === 'harian' ? 'selected' : ''; ?>>
                                        Harian
                                    </option>
                                    <option value="bulanan" <?= $jenis_laporan === 'bulanan' ? 'selected' : ''; ?>>
                                        Bulanan
                                    </option>
                                </select>

                                <!-- Input tanggal harian -->
                                <div id="filter_harian">
                                    <input 
                                        type="date" 
                                        name="tanggal" 
                                        id="tanggal"
                                        value="<?= htmlspecialchars($tanggal); ?>"
                                        class="w-full px-3 py-2 text-sm bg-[#FFF7FA] border border-[#F7D6E4] rounded-xl focus:outline-none focus:border-[#C75C7A] text-[#6F5E64]"
                                    >
                                </div>

                                <!-- Input bulan -->
                                <div id="filter_bulanan">
                                    <input 
                                        type="month" 
                                        name="bulan" 
                                        id="bulan"
                                        value="<?= htmlspecialchars($bulan); ?>"
                                        class="w-full px-3 py-2 text-sm bg-[#FFF7FA] border border-[#F7D6E4] rounded-xl focus:outline-none focus:border-[#C75C7A] text-[#6F5E64]"
                                    >
                                </div>

                                <!-- Tombol filter -->
                                <button 
                                    type="submit"
                                    class="px-4 py-2 text-sm font-bold text-white bg-[#C75C7A] hover:bg-[#B14F6C] rounded-xl transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="fa-solid fa-filter"></i>
                                    <span>Filter</span>
                                </button>

                                <!-- Tombol tambah walk-in -->
                                <a 
                                    href="pendapatan-walkin.php"
                                    class="px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] hover:bg-[#FAD7E5] rounded-xl transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="fa-solid fa-cash-register"></i>
                                    <span>Tambah Walk-in</span>
                                </a>

                                <!-- Tombol tambah pengeluaran -->
                                <a 
                                    href="pengeluaran.php" 
                                    class="px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] hover:bg-[#FAD7E5] rounded-xl transition-colors flex items-center justify-center gap-2"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Pengeluaran</span>
                                </a>

                                <!-- Tombol export PDF -->
                                <a 
                                    href="export-laporan.php?jenis=<?= urlencode($jenis_laporan); ?>&tanggal=<?= urlencode($tanggal); ?>&bulan=<?= urlencode($bulan); ?>&format=pdf"
                                    target="_blank"
                                    class="px-3 py-2 text-sm font-bold text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors flex items-center justify-center gap-2"
                                    title="Export PDF"
                                >
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span>PDF</span>
                                </a>

                                <!-- Tombol export Excel -->
                                <a 
                                    href="export-laporan.php?jenis=<?= urlencode($jenis_laporan); ?>&tanggal=<?= urlencode($tanggal); ?>&bulan=<?= urlencode($bulan); ?>&format=excel"
                                    class="px-3 py-2 text-sm font-bold text-white bg-green-500 hover:bg-green-600 rounded-xl transition-colors flex items-center justify-center gap-2"
                                    title="Export Excel"
                                >
                                    <i class="fa-solid fa-file-excel"></i>
                                    <span>Excel</span>
                                </a>
                            </form>
                        </div>


                    <!-- Peringatan kolom walk-in -->
                    <?php if (!$kolom_nama_walkin_ada || !$kolom_layanan_manual_ada) : ?>
                        <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-100 text-yellow-700 text-sm leading-relaxed">
                            <b>Perhatian:</b>
                            Kolom <b>nama_pelanggan_walkin</b> atau <b>layanan_manual</b> belum ada di tabel transaksi.
                            Data walk-in tetap bisa terbaca sebagai transaksi, tetapi nama pelanggan dan layanan manual belum bisa tampil lengkap.
                        </div>
                    <?php endif; ?>

                    <!-- Ringkasan laporan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3 md:gap-4">

                        <!-- Card pendapatan -->
                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#B77B8E] uppercase tracking-wider">
                                        Total Pendapatan
                                    </p>

                                    <h4 class="text-xl font-bold text-[#2B2424] mt-1">
                                        Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-base">
                                    <i class="fa-solid fa-wallet"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card pengeluaran -->
                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#B77B8E] uppercase tracking-wider">
                                        Total Pengeluaran
                                    </p>

                                    <h4 class="text-xl font-bold text-[#2B2424] mt-1">
                                        Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-base">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card laba bersih -->
                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#B77B8E] uppercase tracking-wider">
                                        Laba Bersih
                                    </p>

                                    <h4 class="text-xl font-bold <?= $laba_bersih >= 0 ? 'text-green-600' : 'text-red-600'; ?> mt-1">
                                        Rp <?= number_format($laba_bersih, 0, ',', '.'); ?>
                                    </h4>
                                </div>

                                <div class="w-9 h-9 bg-[#FDEAF1] text-[#C75C7A] rounded-xl flex items-center justify-center text-base">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card transaksi -->
                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#B77B8E] uppercase tracking-wider">
                                        Data Transaksi
                                    </p>

                                    <h4 class="text-xl font-bold text-[#2B2424] mt-1">
                                        <?= $total_transaksi; ?>
                                    </h4>

                                    <p class="text-[11px] text-[#B77B8E] mt-1">
                                        Booking: <?= $total_booking_transaksi; ?> | Walk-in: <?= $total_walkin; ?>
                                    </p>
                                </div>

                                <div class="w-9 h-9 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-base">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Card data pengeluaran -->
                        <div class="bg-white p-4 rounded-2xl border border-[#F7D6E4] shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-semibold text-[#B77B8E] uppercase tracking-wider">
                                        Data Pengeluaran
                                    </p>

                                    <h4 class="text-xl font-bold text-[#2B2424] mt-1">
                                        <?= $total_data_pengeluaran; ?>
                                    </h4>

                                    <p class="text-[11px] text-[#B77B8E] mt-1">
                                        Restok: <?= $total_data_restok; ?> | Manual: <?= $total_data_pengeluaran_manual; ?>
                                    </p>
                                </div>

                                <div class="w-9 h-9 bg-red-50 text-red-600 rounded-xl flex items-center justify-center text-base">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tabel pendapatan -->
                    <div class="bg-white rounded-2xl border border-[#F7D6E4] shadow-sm overflow-hidden">

                        <!-- Header tabel pendapatan -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-white">
                            <h4 class="font-bold text-[#2B2424] text-sm">
                                Riwayat Pendapatan Transaksi & Walk-in
                            </h4>

                            <span class="text-xs text-green-600 bg-green-50 px-2.5 py-1 rounded-full font-semibold">
                                Booking & Walk-in
                            </span>
                        </div>

                        <!-- Tabel pendapatan -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[850px]">

                                <!-- Header tabel -->
                                <thead>
                                    <tr class="bg-[#EFA9BF] text-[11px] font-bold text-white uppercase tracking-wider border-b border-[#EFA9BF]">
                                        <th class="py-3 px-5">Invoice</th>
                                        <th class="py-3 px-5">Pelanggan</th>
                                        <th class="py-3 px-5">Jenis</th>
                                        <th class="py-3 px-5">Layanan</th>
                                        <th class="py-3 px-5">Tanggal</th>
                                        <th class="py-3 px-5">Total Bayar</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel -->
                                <tbody class="text-xs text-[#6F5E64] divide-y divide-[#F7D6E4]">
                                    <?php if (!empty($data_pendapatan)) : ?>
                                        <?php foreach ($data_pendapatan as $pendapatan) : ?>
                                            <tr class="hover:bg-[#FDEAF1]/40 transition-colors">
                                                <td class="py-4 px-5 font-semibold text-[#C75C7A]">
                                                    #TRX-<?= str_pad($pendapatan['id_transaksi'], 4, '0', STR_PAD_LEFT); ?>
                                                </td>

                                                <td class="py-4 px-5 font-medium text-[#2B2424]">
                                                    <?= htmlspecialchars($pendapatan['nama_pelanggan']); ?>
                                                </td>

                                                <td class="py-4 px-5">
                                                    <?php if ($pendapatan['jenis_pelanggan'] === 'walk-in') : ?>
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-[#FDEAF1] text-[#C75C7A] text-[10px] font-bold uppercase">
                                                            <i class="fa-solid fa-cash-register"></i>
                                                            Walk-in
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold uppercase">
                                                            Booking
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td class="py-4 px-5">
                                                    <?= htmlspecialchars($pendapatan['nama_layanan']); ?>
                                                </td>

                                                <td class="py-4 px-5 text-[#B77B8E]">
                                                    <?= date('d M Y H:i', strtotime($pendapatan['tanggal_transaksi'])); ?>
                                                </td>

                                                <td class="py-4 px-5 font-bold text-[#2B2424]">
                                                    Rp <?= number_format($pendapatan['total_bayar'], 0, ',', '.'); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="6" class="py-6 px-5 text-center text-[#B77B8E]">
                                                Belum ada pendapatan pada periode ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tabel pengeluaran -->
                    <div class="bg-white rounded-2xl border border-[#F7D6E4] shadow-sm overflow-hidden">

                        <!-- Header tabel pengeluaran -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 bg-white">
                            <h4 class="font-bold text-[#2B2424] text-sm">
                                Riwayat Pengeluaran
                            </h4>

                            <span class="text-xs text-red-600 bg-red-50 px-2.5 py-1 rounded-full font-semibold">
                                Restok & Manual
                            </span>
                        </div>

                        <!-- Tabel pengeluaran -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[800px]">

                                <!-- Header tabel -->
                                <thead>
                                    <tr class="bg-[#EFA9BF] text-[11px] font-bold text-white uppercase tracking-wider border-b border-[#EFA9BF]">
                                        <th class="py-3 px-5">Jenis</th>
                                        <th class="py-3 px-5">Keterangan</th>
                                        <th class="py-3 px-5">Tanggal</th>
                                        <th class="py-3 px-5">Total</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel -->
                                <tbody class="text-xs text-[#6F5E64] divide-y divide-[#F7D6E4]">

                                    <?php foreach ($data_restok as $restok) : ?>
                                        <tr class="hover:bg-[#FDEAF1]/40 transition-colors">
                                            <td class="py-4 px-5 font-semibold text-red-600">
                                                Restok
                                            </td>

                                            <td class="py-4 px-5 font-medium text-[#2B2424]">
                                                <?= htmlspecialchars($restok['nama_barang']); ?>
                                                <span class="block text-[11px] text-[#B77B8E] mt-1">
                                                    Jumlah tambah: <?= (int) $restok['jumlah_tambah']; ?>
                                                </span>
                                            </td>

                                            <td class="py-4 px-5 text-[#B77B8E]">
                                                <?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?>
                                            </td>

                                            <td class="py-4 px-5 font-bold text-[#2B2424]">
                                                Rp <?= number_format($restok['total_harga_restok'], 0, ',', '.'); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php foreach ($data_pengeluaran as $pengeluaran) : ?>
                                        <tr class="hover:bg-[#FDEAF1]/40 transition-colors">
                                            <td class="py-4 px-5 font-semibold text-orange-600">
                                                Manual
                                            </td>

                                            <td class="py-4 px-5 font-medium text-[#2B2424]">
                                                <?= htmlspecialchars($pengeluaran['jenis_pengeluaran']); ?>

                                                <?php if (!empty($pengeluaran['keterangan_pengeluaran'])) : ?>
                                                    <span class="block text-[11px] text-[#B77B8E] mt-1">
                                                        <?= htmlspecialchars($pengeluaran['keterangan_pengeluaran']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <td class="py-4 px-5 text-[#B77B8E]">
                                                <?= date('d M Y H:i', strtotime($pengeluaran['tanggal_pengeluaran'])); ?>
                                            </td>

                                            <td class="py-4 px-5 font-bold text-[#2B2424]">
                                                Rp <?= number_format($pengeluaran['jumlah_pengeluaran'], 0, ',', '.'); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($data_restok) && empty($data_pengeluaran)) : ?>
                                        <tr>
                                            <td colspan="4" class="py-6 px-5 text-center text-[#B77B8E]">
                                                Belum ada pengeluaran pada periode ini.
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

    <!-- Script filter laporan -->
    <script>
        // Menampilkan input sesuai jenis laporan
        function toggleFilterInput() {
            const jenis = document.getElementById('jenis_laporan').value;
            const filterHarian = document.getElementById('filter_harian');
            const filterBulanan = document.getElementById('filter_bulanan');

            if (jenis === 'bulanan') {
                filterHarian.style.display = 'none';
                filterBulanan.style.display = 'block';
            } else {
                filterHarian.style.display = 'block';
                filterBulanan.style.display = 'none';
            }
        }

        // Menjalankan filter saat halaman dibuka
        toggleFilterInput();
    </script>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>
