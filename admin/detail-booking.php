<?php
// Mengatur judul halaman
$page_title = "Detail Booking";

// Mengatur sub judul halaman
$sub_title = "Detail Booking";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek tabel stok yang digunakan
$cek_tabel_stok_barang = mysqli_query($koneksi, "SHOW TABLES LIKE 'stok_barang'");
$tabel_stok = mysqli_num_rows($cek_tabel_stok_barang) > 0 ? 'stok_barang' : 'stok';

// Mengambil id booking dari URL
$id_booking = isset($_GET['id_booking']) ? (int) $_GET['id_booking'] : 0;

// Mengecek id booking valid
// Mengecek id booking valid
if ($id_booking <= 0) {
    echo "<script>
            alert('ID booking tidak valid!');
            window.location.href = 'data-booking.php';
          </script>";
    exit;
}

// Mengecek kolom saran sudah tersedia
$cek_tanggal_saran = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'tanggal_saran'");
$cek_jam_saran = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'jam_saran'");
$cek_catatan_admin = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'catatan_admin'");

$kolom_saran_ada = (
    mysqli_num_rows($cek_tanggal_saran) > 0 &&
    mysqli_num_rows($cek_jam_saran) > 0 &&
    mysqli_num_rows($cek_catatan_admin) > 0
);

// Memproses konfirmasi booking
if (isset($_POST['konfirmasi_booking'])) {
    mysqli_query(
        $koneksi,"UPDATE booking SET status_booking = 'On-going' WHERE id_booking = $id_booking"
    );
// Menampilkan pesan konfirmasi berhasil
        $_SESSION['success'] = [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Booking berhasil dikonfirmasi'
        ];

         header("Location: detail-booking.php?id_booking=$id_booking");
    exit;
}

// CEK JADWAL SUGGESTION DAN MASUKKAN KE PENDING JIKA ADA
if (isset($_POST['pending_booking'])) {
    // Mengambil input saran jadwal
    $tanggal_saran = mysqli_real_escape_string($koneksi, $_POST['tanggal_saran'] ?? '');
    $jam_saran = mysqli_real_escape_string($koneksi, $_POST['jam_saran'] ?? '');
    $catatan_admin = mysqli_real_escape_string($koneksi, strip_tags($_POST['catatan_admin'] ?? ''));

    // Mengecek input saran wajib diisi
    if (empty($tanggal_saran) || empty($jam_saran) || empty($catatan_admin)) {
       $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'informasi',
            'text' => 'Tanggal saran, jam saran, dan catatan admin wajib diisi!'
        ];

         header("Location: detail-booking.php?id_booking=$id_booking");    
        exit;
    }

    // Mengecek salon libur hari Rabu
    if (date('w', strtotime($tanggal_saran)) == 3) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'informasi',
            'text' => 'Salon libur setiap hari Rabu. Pilih tanggal saran lain!'
        ];

        header("Location: detail-booking.php?id_booking=$id_booking");
        exit;
    }

    // Mengecek jika kolom saran sudah tersedia
    if ($kolom_saran_ada) {
        mysqli_query(
            $koneksi,"UPDATE booking SET status_booking = 'Pending', 
                tanggal_saran = '$tanggal_saran',
                jam_saran = '$jam_saran',
                catatan_admin = '$catatan_admin'
             WHERE id_booking = $id_booking"
        );
//
    } else {
        mysqli_query(
            $koneksi,
            "UPDATE booking SET status_booking = 'Pending' WHERE id_booking = $id_booking"
        );
    }
// Menampilkan pesan pending berhasil
    $_SESSION['success'] = [
        'icon' => 'success',
        'title' => 'Berhasil',
        'text' => 'Booking dibuat pending dan saran jadwal berhasil disimpan!'
    ];

    header("Location: detail-booking.php?id_booking=$id_booking");
    exit;
}

if (isset($_POST['cancel_booking'])) {

mysqli_query(
    $koneksi,
    "UPDATE booking
     SET status_booking = 'Cancel'
     WHERE id_booking = $id_booking"
);

$_SESSION['success'] = [
    'icon' => 'success',
    'title' => 'Berhasil',
    'text' => 'Booking berhasil dibatalkan!'
];

header("Location: detail-booking.php?id_booking=$id_booking");
exit;

}

// Memproses booking selesai dan masuk ke transaksi
if (isset($_POST['done_booking'])) {
    // Total bayar final diinput admin dan masuk ke transaksi.total_bayar
    $total_bayar_admin = isset($_POST['total_bayar_admin']) ? (int) $_POST['total_bayar_admin'] : 0;
    $catatan_tambahan = isset($_POST['catatan_tambahan']) ? mysqli_real_escape_string($koneksi, $_POST['catatan_tambahan']) : '';

    $id_barang_tambahan = isset($_POST['id_barang_tambahan']) ? $_POST['id_barang_tambahan'] : [];
    $jumlah_tambahan = isset($_POST['jumlah_tambahan']) ? $_POST['jumlah_tambahan'] : [];

    // Mengecek transaksi booking agar tidak double
    $cek_transaksi = mysqli_query(
        $koneksi,
        "SELECT id_transaksi FROM transaksi WHERE id_booking = $id_booking"
    );
// Mengecek transaksi agar tidak double
    if (mysqli_num_rows($cek_transaksi) > 0) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'informasi',
            'text' => 'Booking ini sudah pernah masuk transaksi!'
        ];

        header("Location: detail-booking.php?id_booking=$id_booking");
        exit;
    }

    // Mengambil layanan booking berdasarkan id_booking di tabel booking_detail
    $query_layanan_booking = mysqli_query(
        $koneksi,
        "SELECT bd.id_layanan,l.harga_min AS harga_layanan FROM booking_detail bd JOIN layanan l ON bd.id_layanan = l.id_layanan WHERE bd.id_booking = $id_booking"
    );

    $layanan_booking = [];
    $total_layanan = 0;

    while ($layanan = mysqli_fetch_assoc($query_layanan_booking)) {
        $layanan_booking[] = $layanan;
        $total_layanan += (int) $layanan['harga_layanan'];
    }

    // Mengambil DP customer langsung dari tabel booking agar tidak bergantung pada variabel tampilan
    $query_booking_dp = mysqli_query(
        $koneksi,
        "SELECT total_dp FROM booking WHERE id_booking = $id_booking LIMIT 1"
    );
    $data_booking_dp = $query_booking_dp ? mysqli_fetch_assoc($query_booking_dp) : [];
    $dp = (int) ($data_booking_dp['total_dp'] ?? 0);

    // Validasi total bayar admin
    if ($total_bayar_admin <= 0) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'informasi',
            'text' => 'Total bayar final wajib diisi admin!'
        ];
        header("Location: detail-booking.php?id_booking=$id_booking");
        exit;
    }

    if ($total_bayar_admin < $dp) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'informasi',
            'text' => 'Total bayar final tidak boleh lebih kecil dari DP customer!'
        ];
        header("Location: detail-booking.php?id_booking=$id_booking");
        exit;
    }

    // Total transaksi final berasal dari input admin
    $total_bayar = $total_bayar_admin;
    // Memulai transaksi database
    mysqli_begin_transaction($koneksi);

    try {
        // Menyimpan transaksi utama 
        mysqli_query(
            $koneksi,
            "INSERT INTO transaksi 
                (id_booking, total_bayar, jenis_pelanggan, catatan_tambahan)
             VALUES 
                ($id_booking, $total_bayar, 'booking', '$catatan_tambahan')"
        );

        $id_transaksi = mysqli_insert_id($koneksi);

        // Menyimpan detail layanan transaksi
        foreach ($layanan_booking as $layanan) {
            $id_layanan = (int) $layanan['id_layanan'];
            $harga_satuan = (int) $layanan['harga_layanan'];

            mysqli_query(
                $koneksi,
                "INSERT INTO transaksi_detail 
                    (id_transaksi, id_layanan, harga_satuan, jumlah_layanan, subtotal)
                 VALUES 
                    ($id_transaksi, $id_layanan, $harga_satuan, 1, $harga_satuan)"
            );

            // Mengambil paket stok sesuai layanan
            $query_paket = mysqli_query(
                $koneksi,
                "SELECT 
                    id_barang,
                    jumlah_stok
                 FROM paket_stok
                 WHERE id_layanan = $id_layanan"
            );

            // Mengurangi stok otomatis dari paket layanan
            while ($paket = mysqli_fetch_assoc($query_paket)) {
                $id_barang = (int) $paket['id_barang'];
                $jumlah_pemakaian = (int) $paket['jumlah_stok'];

                mysqli_query(
                    $koneksi,
                    "INSERT INTO pemakaian_stok 
                        (id_barang, id_transaksi, jumlah_pemakaian)
                     VALUES 
                        ($id_barang, $id_transaksi, $jumlah_pemakaian)"
                );

                mysqli_query(
                    $koneksi,
                    "UPDATE $tabel_stok 
                     SET jumlah_barang = jumlah_barang - $jumlah_pemakaian
                     WHERE id_barang = $id_barang"
                );
            }
        }

        // Mengurangi stok tambahan bahan jika ada
        foreach ($id_barang_tambahan as $index => $id_barang) {
            $id_barang = (int) $id_barang;
            $jumlah = isset($jumlah_tambahan[$index]) ? (int) $jumlah_tambahan[$index] : 0;

            if ($id_barang > 0 && $jumlah > 0) {
                mysqli_query(
                    $koneksi,
                    "INSERT INTO pemakaian_stok 
                        (id_barang, id_transaksi, jumlah_pemakaian)
                     VALUES 
                        ($id_barang, $id_transaksi, $jumlah)"
                );

                mysqli_query(
                    $koneksi,
                    "UPDATE $tabel_stok 
                     SET jumlah_barang = jumlah_barang - $jumlah
                     WHERE id_barang = $id_barang"
                );
            }
        }

        // Mengubah status booking menjadi selesai tanpa kolom pembayaran lama
        mysqli_query(
            $koneksi,
            "UPDATE booking
             SET status_booking = 'Done'
             WHERE id_booking = $id_booking"
        );

        mysqli_commit($koneksi);

        $_SESSION['success'] = [
            'icon' => 'success',
            'title' => 'Berhasil',
            'text' => 'Booking selesai, transaksi berhasil dibuat, dan stok berhasil dikurangi!'
        ];

        header("Location: detail-booking.php?id_booking=$id_booking");
        exit;
    } catch (Exception $e) {
        mysqli_rollback($koneksi);

        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Gagal',
            'text' => 'Gagal menyelesaikan booking!'
        ];

        header("Location: detail-booking.php?id_booking=$id_booking");
        exit;
    }
}

// Mengambil data booking utama
$query_booking = mysqli_query(
    $koneksi,
    "SELECT 
        booking.*,
        user.nama,
        user.no_hp,
        user.email,
        user.alamat
     FROM booking
     JOIN user ON booking.id_user = user.id_user
     WHERE booking.id_booking = $id_booking"
);

// Mengambil data booking
$booking = mysqli_fetch_assoc($query_booking);

// Mengecek data booking ditemukan
if (!$booking) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Gagal',
        'text' => 'Data booking tidak ditemukan!'
    ];
    header("Location: data-booking.php");
    exit;
}

// Mengambil detail layanan booking
$query_layanan = mysqli_query(
    $koneksi,
    "SELECT 
        layanan.id_layanan,
        layanan.nama_layanan,
        layanan.harga_min AS harga_layanan,
        layanan.harga_max,
        layanan.durasi_layanan
     FROM booking_detail
     JOIN layanan ON booking_detail.id_layanan = layanan.id_layanan
     WHERE booking_detail.id_booking = $id_booking"
);

// Menyiapkan total harga dan durasi
$total_harga = 0;
$total_harga_max = 0;
$total_durasi = 0;
$layanan_booking = [];
$id_layanan_booking = [];

// Menghitung total layanan
while ($layanan = mysqli_fetch_assoc($query_layanan)) {
    $layanan_booking[] = $layanan;
    $id_layanan_booking[] = (int) $layanan['id_layanan'];
    $harga_min_layanan = (int) $layanan['harga_layanan'];
    $harga_max_layanan = !empty($layanan['harga_max']) ? (int) $layanan['harga_max'] : $harga_min_layanan;

    $total_harga += $harga_min_layanan;
    $total_harga_max += $harga_max_layanan;
    $total_durasi += (int) $layanan['durasi_layanan'];
}



// Mengatur warna status booking
function badge_status_booking($status)
{
    if ($status == 'Waiting') {
        return 'bg-yellow-50 text-yellow-700';
    }

    if ($status == 'Pending') {
        return 'bg-orange-50 text-orange-700';
    }

    if ($status == 'On-going') {
        return 'bg-blue-50 text-blue-700';
    }

    if ($status == 'Done') {
        return 'bg-green-50 text-green-700';
    }

    if ($status == 'Cancel') {
        return 'bg-red-50 text-red-700';
    }

    return 'bg-[#F8F4F2] text-[#3D3134]';
}

// Mengambil stok barang untuk tambahan bahan
$data_stok_barang = [];

// Menjalankan query stok barang langsung dari database
$query_data_stok_barang = mysqli_query(
    $koneksi,
    "SELECT 
        id_barang,
        nama_barang,
        jumlah_barang,
        satuan_barang
     FROM $tabel_stok
     ORDER BY nama_barang ASC"
);

// Menyimpan data stok barang ke array untuk JavaScript
if ($query_data_stok_barang) {
    while ($barang = mysqli_fetch_assoc($query_data_stok_barang)) {
        $data_stok_barang[] = $barang;
    }
}

// Mengambil kalender booking aktif untuk admin
$query_kalender_admin = mysqli_query(
    $koneksi,
    "SELECT 
        b.id_booking,
        b.tanggal_booking,
        b.jam_mulai,
        b.jam_selesai,
        b.status_booking,
        u.nama,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan
     FROM booking b
     JOIN user u ON b.id_user = u.id_user
     LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan
     WHERE b.status_booking IN ('Waiting', 'Pending', 'On-going')
     GROUP BY b.id_booking
     ORDER BY b.tanggal_booking ASC, b.jam_mulai ASC"
);

// Menyiapkan data kalender admin
$jadwal_admin = [];

// Mengisi data kalender admin
while ($jadwal = mysqli_fetch_assoc($query_kalender_admin)) {
    $tanggal = $jadwal['tanggal_booking'];

    if (!isset($jadwal_admin[$tanggal])) {
        $jadwal_admin[$tanggal] = [];
    }

    $jadwal_admin[$tanggal][] = [
        'id_booking' => $jadwal['id_booking'],
        'jam_mulai' => substr($jadwal['jam_mulai'], 0, 5),
        'jam_selesai' => substr($jadwal['jam_selesai'], 0, 5),
        'nama' => $jadwal['nama'],
        'layanan' => $jadwal['nama_layanan'] ?: 'Booking',
        'status' => $jadwal['status_booking'],
    ];
}

// Mengambil stok paket yang dipakai layanan booking
$stok_paket_booking = [];

// Menjalankan query stok paket jika layanan tersedia
if (!empty($id_layanan_booking)) {
    $id_layanan_string = implode(',', $id_layanan_booking);

    $query_stok_paket = mysqli_query(
        $koneksi,
        "SELECT 
            ps.id_layanan,
            ps.id_barang,
            ps.jumlah_stok,
            l.nama_layanan,
            s.nama_barang,
            s.jenis_barang,
            s.jumlah_barang,
            s.satuan_barang,
            s.minimal_stok
         FROM paket_stok ps
         JOIN layanan l ON ps.id_layanan = l.id_layanan
         JOIN $tabel_stok s ON ps.id_barang = s.id_barang
         WHERE ps.id_layanan IN ($id_layanan_string)
         ORDER BY l.nama_layanan ASC, s.nama_barang ASC"
    );

    if ($query_stok_paket) {
        while ($stok_paket = mysqli_fetch_assoc($query_stok_paket)) {
            $stok_paket_booking[] = $stok_paket;
        }
    }
}

// Mengubah data kalender ke JSON
$jadwal_admin_json = json_encode($jadwal_admin);

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

                <!-- Section detail booking -->
                <section class="space-y-6">

                    <!-- Header halaman minimalis -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- Judul halaman -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    <?= htmlspecialchars($sub_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Booking #<?= htmlspecialchars($booking['id_booking']); ?> ·
                                    <span class="font-bold text-[#C75C7A]"><?= htmlspecialchars($booking['status_booking']); ?></span>
                                </p>
                            </div>

                            <!-- Tombol kembali -->
                            <a 
                                href="data-booking.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors w-fit"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>

                    <!-- Grid detail booking -->
                    <div class="grid grid-cols-1 xl:grid-cols-[1fr_360px] gap-6 items-start">

                        <!-- Detail pelanggan dan booking -->
                        <div class="space-y-6">

                            <!-- Card informasi booking -->
                            <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                                <!-- Header card -->
                                <div class="p-5 border-b border-[#F7D6E4] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <h4 class="font-bold text-[#2B2424]">
                                            Informasi Booking
                                        </h4>

                                        <p class="text-xs text-[#B77B8E]">
                                            Booking #<?= htmlspecialchars($booking['id_booking']); ?>
                                        </p>
                                    </div>

                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full uppercase <?= badge_status_booking($booking['status_booking']); ?>">
                                        <?= htmlspecialchars($booking['status_booking']); ?>
                                    </span>
                                </div>

                                <!-- Isi informasi booking -->
                                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                                    <!-- Nama pelanggan -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Nama Pelanggan
                                        </p>

                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?= htmlspecialchars($booking['nama']); ?>
                                        </p>
                                    </div>

                                    <!-- Nomor hp -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            No HP
                                        </p>

                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?= htmlspecialchars($booking['no_hp']); ?>
                                        </p>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Email
                                        </p>

                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?= htmlspecialchars($booking['email']); ?>
                                        </p>
                                    </div>

                                    <!-- Tanggal booking -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Tanggal Booking
                                        </p>

                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                        </p>
                                    </div>

                                    <!-- Jam booking -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Jam Booking
                                        </p>

                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?= substr($booking['jam_mulai'], 0, 5); ?> - <?= substr($booking['jam_selesai'], 0, 5); ?>
                                        </p>
                                    </div>

                                    <!-- Alamat -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Alamat
                                        </p>

                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?= htmlspecialchars($booking['alamat']); ?>
                                        </p>
                                    </div>

                                    <!-- Bukti pembayarab -->
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Bukti Pembayaran
                                        </p>
                                        <!-- JIKA ADA BUKTI PEMBAYARAN -->
                                        <p class="text-sm font-bold text-[#2B2424] mt-1">
                                            <?php if ($booking['bukti_pembayaran']) : ?>
                                                <a 
                                                    
                                                    href="../uploads/bukti-pembayaran/<?= htmlspecialchars($booking['bukti_pembayaran']); ?>" 
                                                    target="_blank" 
                                                    class="inline-flex items-center gap-1 text-blue-600 hover:underline"
                                                >
                                                    <i class="fa-solid fa-file-image"></i>
                                                    Lihat Bukti
                                                </a>
                                            <?php else : ?>
                                                <p class="text-sm text-[#7A6F6F]">
                                                    Tidak ada bukti pembayaran
                                                </p>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card layanan booking -->
                            <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                                <!-- Header card layanan -->
                                <div class="p-5 border-b border-[#F7D6E4]">
                                    <h4 class="font-bold text-[#2B2424]">
                                        Layanan Dipilih
                                    </h4>
                                </div>

                                <!-- List layanan -->
                                <div class="p-5 space-y-3">
                                    <?php if (!empty($layanan_booking)) : ?>
                                        <?php foreach ($layanan_booking as $layanan) : ?>
                                            <div class="flex items-center justify-between gap-4 p-4 bg-[#FFF7FA] border border-[#F7D6E4] rounded-2xl">
                                                <div>
                                                    <h5 class="text-sm font-bold text-[#2B2424]">
                                                        <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                                    </h5>

                                                    <p class="text-xs text-[#B77B8E] mt-1">
                                                        <?= htmlspecialchars($layanan['durasi_layanan']); ?> menit
                                                    </p>
                                                </div>

                                                <div class="text-right">
                                                    <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                                        Range Harga
                                                    </p>

                                                    <p class="text-sm font-bold text-[#C75C7A] mt-1">
                                                        <?php if (!empty($layanan['harga_max']) && (int) $layanan['harga_max'] > (int) $layanan['harga_layanan']) : ?>
                                                            Rp <?= number_format($layanan['harga_layanan'], 0, ',', '.'); ?> -
                                                            Rp <?= number_format($layanan['harga_max'], 0, ',', '.'); ?>
                                                        <?php else : ?>
                                                            Rp <?= number_format($layanan['harga_layanan'], 0, ',', '.'); ?>
                                                        <?php endif; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-sm text-[#B77B8E] text-center py-4">
                                            Layanan tidak ditemukan.
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Total layanan -->
                                <div class="p-5 border-t border-[#F7D6E4] grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Estimasi Harga
                                        </p>

                                        <p class="text-xl font-bold text-[#C75C7A] mt-1">
                                            <?php if ($total_harga_max > $total_harga) : ?>
                                                Rp <?= number_format($total_harga, 0, ',', '.'); ?> -
                                                Rp <?= number_format($total_harga_max, 0, ',', '.'); ?>
                                            <?php else : ?>
                                                Rp <?= number_format($total_harga, 0, ',', '.'); ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                            Estimasi Waktu
                                        </p>

                                        <p class="text-xl font-bold text-[#2B2424] mt-1">
                                            <?= $total_durasi; ?> Menit
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <!-- Card stok paket layanan -->
                            <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                                <!-- Header stok paket -->
                                <div class="p-5 border-b border-[#F7D6E4]">
                                    <h4 class="font-bold text-[#2B2424]">
                                        Stok Barang yang Dipakai
                                    </h4>

                                    <p class="text-xs text-[#B77B8E] mt-1">
                                        Paket stok otomatis berdasarkan layanan yang dibooking customer.
                                    </p>
                                </div>

                                <!-- Isi stok paket -->
                                <div class="p-5 space-y-3">
                                    <?php if (!empty($stok_paket_booking)) : ?>
                                        <?php foreach ($stok_paket_booking as $stok_paket) : ?>
                                            <div class="p-4 bg-[#FFF7FA] border border-[#F7D6E4] rounded-2xl">
                                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                    <div>
                                                        <p class="text-xs font-bold text-[#C75C7A]">
                                                            <?= htmlspecialchars($stok_paket['nama_layanan']); ?>
                                                        </p>

                                                        <h5 class="text-sm font-bold text-[#2B2424] mt-1">
                                                            <?= htmlspecialchars($stok_paket['nama_barang']); ?>
                                                        </h5>

                                                        <p class="text-xs text-[#B77B8E] mt-1">
                                                            Jenis: <?= htmlspecialchars($stok_paket['jenis_barang']); ?>
                                                        </p>
                                                    </div>

                                                    <div class="text-left sm:text-right">
                                                        <p class="text-sm font-bold text-[#2B2424]">
                                                            Pakai: <?= (int) $stok_paket['jumlah_stok']; ?> <?= htmlspecialchars($stok_paket['satuan_barang']); ?>
                                                        </p>

                                                        <p class="text-xs text-[#B77B8E] mt-1">
                                                            Stok saat ini: <?= (int) $stok_paket['jumlah_barang']; ?> <?= htmlspecialchars($stok_paket['satuan_barang']); ?>
                                                        </p>

                                                        <?php if ((int) $stok_paket['jumlah_barang'] <= (int) $stok_paket['minimal_stok']) : ?>
                                                            <p class="text-xs font-bold text-red-500 mt-1">
                                                                Stok menipis
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-sm text-[#B77B8E] text-center py-4">
                                            Belum ada paket stok untuk layanan ini.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                        </div>

                        <!-- Panel aksi admin -->
                        <div class="space-y-6 xl:sticky xl:top-6">

                            <!-- Card aksi booking -->
                            <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                                <!-- Header aksi -->
                                <div class="p-5 border-b border-[#F7D6E4]">
                                    <h4 class="font-bold text-[#2B2424]">
                                        Aksi Admin
                                    </h4>

                                    <p class="text-xs text-[#B77B8E] mt-1">
                                        Atur status booking pelanggan.
                                    </p>
                                </div>

                                <!-- Isi aksi -->
                                <div class="p-5 space-y-3">

                                    <?php if ($booking['status_booking'] == 'Waiting' || $booking['status_booking'] == 'Pending') : ?>

                                        <!-- Tombol konfirmasi -->
                                        <form action="" method="POST">
                                            <button 
                                                type="submit" 
                                                name="konfirmasi_booking"
                                                onclick="konfirmasiBooking(event)"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#1883FF] text-white text-sm font-bold rounded-xl hover:bg-[#0028B3] transition-colors"
                                            >
                                                <i class="fa-solid fa-circle-check"></i>
                                                <span>Konfirmasi Booking</span>
                                            </button>
                                        </form>

                                        <!-- Tombol tampil MODAL PENDING-->
                                        <button 
                                            type="button"
                                            onclick="openPendingModal()"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition-colors"
                                        >
                                            <i class="fa-solid fa-clock"></i>
                                            <span>Pending & Beri Saran</span>
                                        </button>

                                        <!-- Tombol MODAL BATAL -->
                                        <form action="" method="POST">
                                            <button 
                                                type="submit" 
                                                name="cancel_booking"
                                                onclick="cancelBooking(event)"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-500 text-white text-sm font-bold rounded-xl hover:bg-red-600 transition-colors"
                                            >
                                                <i class="fa-solid fa-ban"></i>
                                                <span>Batalkan Booking</span>
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                    <?php if ($booking['status_booking'] == 'On-going') : ?>

                                        <!-- Form selesai booking -->
                                        <form action="" method="POST" class="space-y-4">
                                                                                <form action="" method="POST" class="space-y-4">

                                            <!-- Nominal DP customer -->
                                            <div>
                                                <label class="block text-sm font-bold text-[#3D3134] mb-2">
                                                    Nominal DP Customer
                                                </label>

                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-[#C75C7A]">
                                                        Rp
                                                    </span>

                                                    <input
                                                        type="number"
                                                        value="<?= (int) ($booking['total_dp'] ?? 0); ?>"
                                                        readonly
                                                        class="w-full pl-11 pr-4 py-3 border border-[#EAD8D0] bg-[#F8F4F2] rounded-xl text-sm font-bold text-[#7A6F6F] cursor-not-allowed"
                                                    >
                                                </div>

                                                <p class="text-[11px] text-[#B77B8E] mt-1">
                                                    DP ini berasal dari input customer dan tidak bisa diedit admin.
                                                </p>
                                            </div>

                                            <!-- Estimasi harga layanan -->
                                            <div>
                                                <label class="block text-sm font-bold text-[#3D3134] mb-2">
                                                    Estimasi Harga Layanan
                                                </label>

                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-[#B77B8E]">
                                                        Rp
                                                    </span>

                                                    <input
                                                        type="number"
                                                        value="<?= (int) $total_harga; ?>"
                                                        readonly
                                                        class="w-full pl-11 pr-4 py-3 border border-[#EAD8D0] bg-[#F8F4F2] rounded-xl text-sm font-bold text-[#7A6F6F] cursor-not-allowed"
                                                    >
                                                </div>

                                                <p class="text-[11px] text-[#B77B8E] mt-1">
                                                    Estimasi ini hanya referensi dari harga minimal layanan.
                                                </p>
                                            </div>

                                            <!-- Total bayar final admin -->
                                            <div>
                                                <label for="total_bayar_admin" class="block text-sm font-bold text-[#3D3134] mb-2">
                                                    Total Bayar Final
                                                </label>

                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-[#C75C7A]">
                                                        Rp
                                                    </span>

                                                    <input 
                                                        type="number"
                                                        name="total_bayar_admin"
                                                        id="total_bayar_admin"
                                                        min="<?= (int) ($booking['total_dp'] ?? 0); ?>"
                                                        value="<?= (int) $total_harga; ?>"
                                                        required
                                                        placeholder="Masukkan total bayar final"
                                                        class="w-full pl-11 pr-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                                    >
                                                </div>

                                                <p class="text-[11px] text-[#B77B8E] mt-1">
                                                    Nilai ini akan masuk ke tabel <b>transaksi</b> kolom <b>total_bayar</b>.
                                                </p>
                                            </div>

                                            <!-- Catatan tambahan opsional -->
                                            <div>
                                                <label for="catatan_tambahan" class="block text-sm font-bold text-[#3D3134] mb-2">
                                                    Catatan Admin Opsional
                                                </label>

                                                <textarea 
                                                    name="catatan_tambahan"
                                                    id="catatan_tambahan"
                                                    rows="3"
                                                    placeholder="Contoh: harga final sudah termasuk penyesuaian layanan"
                                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A] resize-none"
                                                ></textarea>
                                            </div>

                                            <!-- Tambahan bahan opsional -->
                                            <div>
                                                <div class="flex items-center justify-between mb-2">
                                                    <label class="block text-sm font-bold text-[#3D3134]">
                                                        Tambahan Bahan Opsional
                                                    </label>

                                                    <button 
                                                        type="button"
                                                        onclick="openBarangTambahanModal()"
                                                        class="px-3 py-1.5 bg-[#FDEAF1] text-[#C75C7A] text-xs font-bold rounded-lg hover:bg-[#FAD7E5] transition"
                                                    >
                                                        + Pilih Barang
                                                    </button>
                                                </div>

                                                <!-- Daftar bahan yang sudah dipilih -->
                                                <div id="tambahan-bahan-wrapper" class="space-y-2"></div>

                                                <p class="text-[11px] text-[#B77B8E] mt-2">
                                                    Klik tombol pilih barang untuk menambahkan bahan tambahan.
                                                    <?php if (empty($data_stok_barang)) : ?>
                                                        <br><span class="text-red-500 font-bold">Data barang belum terbaca dari database.</span>
                                                    <?php endif; ?>
                                                </p>
                                            </div>

                                            <!-- Tombol selesai -->
                                            <button 
                                                type="submit" 
                                                name="done_booking"
                                                onclick="return confirm('Selesaikan booking dan buat transaksi?')"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white text-sm font-bold rounded-xl hover:bg-green-600 transition-colors"
                                            >
                                                <i class="fa-solid fa-check-double"></i>
                                                <span>Selesaikan Booking</span>
                                            </button>
                                        </form>

                                    <?php endif; ?>

                                    <?php if ($booking['status_booking'] == 'Cancel' || $booking['status_booking'] == 'Done') : ?>
                                    <?php if ($booking['status_booking'] == 'Done') : ?>


                            <?php endif; ?>
                                        <!-- Info status final -->
                                        <div class="p-4 bg-[#F8F4F2] rounded-xl text-center">
                                            <p class="text-sm font-semibold text-[#7A6F6F]">
                                                Booking sudah berstatus <?= htmlspecialchars($booking['status_booking']); ?>.
                                            </p>
                                        </div>

                                    <?php endif; ?>
                                </div>
                            </div>


                            <!-- Card kalender admin compact -->
                            <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                                <!-- Header kalender admin -->
                                <div class="p-4 border-b border-[#F7D6E4] flex items-center justify-between gap-3">
                                    <div>
                                        <h4 class="text-sm font-bold text-[#2B2424]">
                                            Kalender Booking
                                        </h4>

                                        <p class="text-[11px] text-[#B77B8E] mt-1">
                                            Cek tanggal booking aktif.
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button 
                                            type="button"
                                            onclick="adminPrevMonth()"
                                            class="w-8 h-8 rounded-lg bg-[#FDEAF1] text-[#C75C7A] hover:bg-[#FAD7E5] transition"
                                        >
                                            <i class="fa-solid fa-chevron-left text-xs"></i>
                                        </button>

                                        <button 
                                            type="button"
                                            onclick="adminNextMonth()"
                                            class="w-8 h-8 rounded-lg bg-[#FDEAF1] text-[#C75C7A] hover:bg-[#FAD7E5] transition"
                                        >
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Isi kalender admin -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-3 mb-4">
                                        <h5 id="admin-calendar-title" class="text-xs font-bold text-[#2B2424]"></h5>

                                        <p class="text-[10px] text-[#B77B8E]">
                                            Pink: booking
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1.5 text-center text-[10px] font-bold text-[#B77B8E] mb-2">
                                        <div>Min</div>
                                        <div>Sen</div>
                                        <div>Sel</div>
                                        <div>Rab</div>
                                        <div>Kam</div>
                                        <div>Jum</div>
                                        <div>Sab</div>
                                    </div>

                                    <div id="admin-calendar-days" class="grid grid-cols-7 gap-1.5"></div>

                                    <div id="admin-calendar-detail" class="mt-4 space-y-2"></div>
                                </div>
                            </div>


                            <!-- Card saran jadwal tersimpan -->
                            <?php if ($kolom_saran_ada && !empty($booking['tanggal_saran']) && !empty($booking['jam_saran'])) : ?>

                                <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                                    <!-- Header saran tersimpan -->
                                    <div class="p-5 border-b border-[#F7D6E4]">
                                        <h4 class="font-bold text-[#2B2424]">
                                            Saran Jadwal Tersimpan
                                        </h4>
                                    </div>

                                    <!-- Isi saran -->
                                    <div class="p-5 space-y-4">
                                        <div>
                                            <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                                Tanggal Saran
                                            </p>

                                            <p class="text-sm font-bold text-[#2B2424] mt-1">
                                                <?= date('d M Y', strtotime($booking['tanggal_saran'])); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                                Jam Saran
                                            </p>

                                            <p class="text-sm font-bold text-[#2B2424] mt-1">
                                                <?= substr($booking['jam_saran'], 0, 5); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                                Catatan Admin
                                            </p>

                                            <p class="text-sm text-[#6F5E64] mt-1 leading-relaxed">
                                                <?= htmlspecialchars($booking['catatan_admin']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            <?php endif; ?>

                            <?php if (!$kolom_saran_ada) : ?>

                                <!-- Info kolom saran belum ada -->
                                <div class="bg-yellow-50 border border-yellow-100 text-yellow-700 rounded-2xl p-4 text-xs leading-relaxed">
                                    Kolom <b>tanggal_saran</b>, <b>jam_saran</b>, dan <b>catatan_admin</b> belum ada di tabel booking. Jalankan SQL yang saya berikan agar saran jadwal bisa tersimpan.
                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>


        <!-- Modal pending dan saran jadwal -->
        <div id="pending-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">

            <!-- Card modal pending -->
            <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden">

                <!-- Header modal -->
                <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/60 flex items-start justify-between gap-4">
                    <div>
                        <h4 class="text-lg font-bold text-[#2B2424]">
                            Pending & Saran Jadwal
                        </h4>

                        <p class="text-xs text-[#B77B8E] mt-1">
                            Isi tanggal, jam, dan catatan saran untuk customer.
                        </p>
                    </div>

                    <!-- Tombol tutup modal -->
                    <button 
                        type="button"
                        onclick="closePendingModal()"
                        class="w-9 h-9 rounded-xl bg-[#F8F4F2] text-[#B77B8E] hover:bg-red-50 hover:text-red-500 transition"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Form pending -->
                <form action="" method="POST" class="p-5 space-y-4">

                    <!-- Input tanggal saran -->
                    <div>
                        <label for="tanggal_saran" class="block text-sm font-bold text-[#3D3134] mb-2">
                            Tanggal Saran
                        </label>

                        <input 
                            type="date" 
                            name="tanggal_saran" 
                            id="tanggal_saran"
                            required
                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                        >

                        <p class="text-[11px] text-[#B77B8E] mt-1">
                            Jangan pilih hari Rabu karena salon libur.
                        </p>
                    </div>

                    <!-- Input jam saran -->
                    <div>
                        <label for="jam_saran" class="block text-sm font-bold text-[#3D3134] mb-2">
                            Jam Saran
                        </label>

                        <input 
                            type="time" 
                            name="jam_saran" 
                            id="jam_saran"
                            required
                            min="10:00"
                            max="21:00"
                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                        >
                    </div>

                    <!-- Input catatan admin -->
                    <div>
                        <label for="catatan_admin" class="block text-sm font-bold text-[#3D3134] mb-2">
                            Catatan Admin
                        </label>

                        <textarea 
                            name="catatan_admin" 
                            id="catatan_admin"
                            rows="4"
                            required
                            placeholder="Contoh: Mohon datang jam 15:00 karena jadwal sebelumnya penuh."
                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A] resize-none"
                        ></textarea>
                    </div>

                    <!-- Aksi modal -->
                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                        <button 
                            type="button"
                            onclick="closePendingModal()"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#F8F4F2] text-[#7A6F6F] text-sm font-bold rounded-xl hover:bg-[#EFE7E4] transition-colors"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            name="pending_booking"
                            onclick="pendingBooking(event)"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition-colors"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Simpan Saran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


<!-- Modal pilih barang tambahan -->
<div id="barang-tambahan-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <!-- Card modal pilih barang -->
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header modal -->
        <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/70 flex items-start justify-between gap-4">
            <div>
                <h4 class="text-lg font-bold text-[#2B2424]">
                    Pilih Barang Tambahan
                </h4>

                <p class="text-xs text-[#B77B8E] mt-1">
                    Pilih barang dari database, lalu isi jumlah pemakaian.
                </p>
            </div>

            <!-- Tombol tutup modal -->
            <button 
                type="button"
                onclick="closeBarangTambahanModal()"
                class="w-9 h-9 rounded-xl bg-white text-[#C75C7A] border border-[#F7D6E4] hover:bg-red-50 hover:text-red-500 transition"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Pencarian barang -->
        <div class="p-4 border-b border-[#F7D6E4] bg-white">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#B77B8E] text-sm"></i>

                <input 
                    type="text"
                    id="search-barang-tambahan"
                    oninput="filterBarangTambahan()"
                    placeholder="Cari nama barang..."
                    class="w-full pl-11 pr-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                >
            </div>
        </div>

        <!-- List barang -->
        <div class="p-4 sm:p-5 overflow-y-auto">
            <div id="list-barang-tambahan" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php if (!empty($data_stok_barang)) : ?>
                    <?php foreach ($data_stok_barang as $barang) : ?>
                        <div 
                            class="barang-tambahan-card p-4 rounded-2xl border border-[#F7D6E4] bg-white hover:bg-[#FDEAF1]/60 transition"
                            data-nama="<?= strtolower(htmlspecialchars($barang['nama_barang'])); ?>"
                        >
                            <div class="flex items-start justify-between gap-3">

                                <!-- Informasi barang -->
                                <div class="min-w-0">
                                    <h5 class="text-sm font-bold text-[#2B2424]">
                                        <?= htmlspecialchars($barang['nama_barang']); ?>
                                    </h5>

                                    <p class="text-xs text-[#7A6F6F] mt-1">
                                        Stok:
                                        <b><?= htmlspecialchars($barang['jumlah_barang']); ?> <?= htmlspecialchars($barang['satuan_barang'] ?? ''); ?></b>
                                    </p>

                                    <p class="text-[11px] text-[#B77B8E] mt-1">
                                        ID Barang: <?= (int) $barang['id_barang']; ?>
                                    </p>
                                </div>

                                <!-- Tombol pilih -->
                                <button
                                    type="button"
                                    onclick="pilihBarangTambahan(
                                        <?= (int) $barang['id_barang']; ?>,
                                        '<?= htmlspecialchars(addslashes($barang['nama_barang'])); ?>',
                                        '<?= htmlspecialchars(addslashes($barang['jumlah_barang'])); ?>',
                                        '<?= htmlspecialchars(addslashes($barang['satuan_barang'] ?? '')); ?>'
                                    )"
                                    class="shrink-0 px-3 py-2 rounded-xl text-xs font-bold bg-[#FDEAF1] text-[#C75C7A] hover:bg-[#C75C7A] hover:text-white transition"
                                >
                                    Pilih
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="md:col-span-2 p-8 text-center text-[#B77B8E] text-sm">
                        Data barang tidak tersedia.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pesan pencarian kosong -->
            <div id="barang-tambahan-empty" class="hidden p-8 text-center text-[#B77B8E] text-sm">
                Barang tidak ditemukan.
            </div>
        </div>
    </div>
</div>

<!-- Script popup pilih barang tambahan -->
<script>
    // Membuka modal pilih barang tambahan
    function openBarangTambahanModal() {
        const modal = document.getElementById('barang-tambahan-modal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        const search = document.getElementById('search-barang-tambahan');

        if (search) {
            search.value = '';
            filterBarangTambahan();
            setTimeout(function () {
                search.focus();
            }, 100);
        }
    }

    // Menutup modal pilih barang tambahan
    function closeBarangTambahanModal() {
        const modal = document.getElementById('barang-tambahan-modal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Mencari barang di modal
    function filterBarangTambahan() {
        const search = document.getElementById('search-barang-tambahan');
        const cards = document.querySelectorAll('.barang-tambahan-card');
        const empty = document.getElementById('barang-tambahan-empty');

        const keyword = search ? search.value.toLowerCase().trim() : '';
        let visibleCount = 0;

        cards.forEach(function (card) {
            const nama = card.dataset.nama || '';
            const isVisible = nama.includes(keyword);

            card.style.display = isVisible ? 'block' : 'none';

            if (isVisible) {
                visibleCount++;
            }
        });

        if (empty) {
            empty.classList.toggle('hidden', visibleCount !== 0);
        }
    }

    // Memilih barang tambahan lalu menambahkan ke form
    function pilihBarangTambahan(idBarang, namaBarang, jumlahBarang, satuanBarang) {
        const wrapper = document.getElementById('tambahan-bahan-wrapper');

        if (!wrapper) return;

        const existing = wrapper.querySelector('[data-id-barang="' + idBarang + '"]');

        if (existing) {
            alert('Barang ini sudah dipilih. Ubah jumlah pada daftar bahan tambahan.');
            closeBarangTambahanModal();
            return;
        }

        const item = document.createElement('div');
        item.className = 'p-3 bg-[#FFF7FA] border border-[#F7D6E4] rounded-2xl space-y-3';
        item.setAttribute('data-id-barang', idBarang);

        item.innerHTML = `
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-bold text-[#C75C7A]">
                        ${namaBarang}
                    </p>

                    <p class="text-[11px] text-[#B77B8E] mt-1">
                        Stok tersedia: ${jumlahBarang} ${satuanBarang}
                    </p>
                </div>

                <button 
                    type="button"
                    onclick="hapusBarangTambahan(this)"
                    class="w-8 h-8 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition"
                    title="Hapus"
                >
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-[1fr_120px] gap-2">
                <input 
                    type="hidden"
                    name="id_barang_tambahan[]"
                    value="${idBarang}"
                >

                <input 
                    type="text"
                    value="${namaBarang}"
                    readonly
                    class="w-full px-3 py-2 border border-[#EAD8D0] bg-[#F8F4F2] rounded-xl text-xs text-[#7A6F6F] cursor-not-allowed"
                >

                <input 
                    type="number" 
                    name="jumlah_tambahan[]" 
                    min="1" 
                    required
                    placeholder="Jumlah"
                    class="w-full px-3 py-2 border border-[#EAD8D0] bg-white rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                >
            </div>
        `;

        wrapper.appendChild(item);
        closeBarangTambahanModal();
    }

    // Menghapus barang tambahan dari form
    function hapusBarangTambahan(button) {
        const item = button.closest('[data-id-barang]');

        if (item) {
            item.remove();
        }
    }

    // Menutup modal saat klik area luar
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('barang-tambahan-modal');

        if (modal && !modal.classList.contains('hidden') && event.target === modal) {
            closeBarangTambahanModal();
        }
    });

    // Menutup modal saat tombol escape ditekan
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeBarangTambahanModal();
        }
    });
</script>

<!-- Data kalender dan stok dari PHP untuk JavaScript admin -->
<script>
    // Data kalender booking aktif agar tanggal yang sudah booking bisa ditandai
    window.jadwalAdmin = <?= json_encode($jadwal_admin, JSON_UNESCAPED_UNICODE); ?>;

    // Option stok barang untuk baris tambahan bahan
    window.stokBarangOptions = `
        <option value="">Pilih Bahan</option>
        <?php foreach ($data_stok_barang as $barang) : ?>
            <option value="<?= (int) $barang['id_barang']; ?>">
                <?= htmlspecialchars($barang['nama_barang'], ENT_QUOTES); ?> | Stok: <?= htmlspecialchars($barang['jumlah_barang'], ENT_QUOTES); ?> <?= htmlspecialchars($barang['satuan_barang'], ENT_QUOTES); ?>
            </option>
        <?php endforeach; ?>
    `;
</script>

<!-- MENJADIKAN FILE JS DI LAYOUT SEBAGAI MODULAR -->
<script src="../layout/js/booking-detail.js"></script>
<script src="../layout/js/sweetalert.js"></script>


<?php
// Memanggil footer utama
include "../layout/footer.php";
?>