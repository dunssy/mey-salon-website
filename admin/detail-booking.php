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
// SWEET ALERT ID BOOKING TIDAK VALID
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
// SWEET ALERT KONFIRMASI BERHASIL
    echo "<script>
            alert('Booking berhasil dikonfirmasi!');
            window.location.href = 'detail-booking.php?id_booking=$id_booking';
          </script>";
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
        echo "<script>
                alert('Tanggal saran, jam saran, dan catatan admin wajib diisi!');
                window.location.href = 'detail-booking.php?id_booking=$id_booking';
              </script>";
        exit;
    }

    // Mengecek salon libur hari Rabu
    if (date('w', strtotime($tanggal_saran)) == 3) {
        echo "<script>
                alert('Salon libur setiap hari Rabu. Pilih tanggal saran lain!');
                window.location.href = 'detail-booking.php?id_booking=$id_booking';
              </script>";
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
// SWEET ALERT PENDING DAN SARAN JADWAL BERHASIL
    echo "<script>
            alert('Booking dibuat pending dan saran jadwal berhasil disimpan!');
            window.location.href = 'detail-booking.php?id_booking=$id_booking';
          </script>";
    exit;
}

// Memproses pembatalan booking
if (isset($_POST['cancel_booking'])) {
    mysqli_query(
        $koneksi,
        "UPDATE booking 
         SET status_booking = 'Cancel'
         WHERE id_booking = $id_booking"
    );
// SWEET ALERT PEMBATALAN BERHASIL
    echo "<script>
            alert('Booking berhasil dibatalkan!');
            window.location.href = 'detail-booking.php?id_booking=$id_booking';
          </script>";
    exit;
}

// Memproses booking selesai dan masuk ke transaksi
if (isset($_POST['done_booking'])) {
    $tambahan_harga = isset($_POST['tambahan_harga']) ? (int) $_POST['tambahan_harga'] : 0;
    $catatan_tambahan = isset($_POST['catatan_tambahan']) ? mysqli_real_escape_string($koneksi, $_POST['catatan_tambahan']) : '';

    $id_barang_tambahan = isset($_POST['id_barang_tambahan']) ? $_POST['id_barang_tambahan'] : [];
    $jumlah_tambahan = isset($_POST['jumlah_tambahan']) ? $_POST['jumlah_tambahan'] : [];

    // Mengecek transaksi booking agar tidak double
    $cek_transaksi = mysqli_query(
        $koneksi,
        "SELECT id_transaksi FROM transaksi WHERE id_booking = $id_booking"
    );
// SWEET ALERT JIKA TRANSAKSI SUDAH ADA
    if (mysqli_num_rows($cek_transaksi) > 0) {
        echo "<script>
                alert('Booking ini sudah pernah masuk transaksi!');
                window.location.href = 'detail-booking.php?id_booking=$id_booking';
              </script>";
        exit;
    }

    // Mengambil layanan booking BERDASARAKAN FOREGIN KEY id_booking di table booking_detail
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

    // Menghitung total transaksi 
    $total_bayar = $total_layanan + $tambahan_harga;

    // Memulai transaksi database
    mysqli_begin_transaction($koneksi);

    try {
        // Menyimpan transaksi utama 
        mysqli_query(
            $koneksi,
            "INSERT INTO transaksi 
                (id_booking, total_bayar, jenis_pelanggan, tambahan_harga, catatan_tambahan)
             VALUES 
                ($id_booking, $total_bayar, 'booking', $tambahan_harga, '$catatan_tambahan')"
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

        // Mengubah status booking menjadi selesai
        mysqli_query(
            $koneksi,
            "UPDATE booking 
             SET status_booking = 'Done'
             WHERE id_booking = $id_booking"
        );

        mysqli_commit($koneksi);

        echo "<script>
                alert('Booking selesai, transaksi berhasil dibuat, dan stok berhasil dikurangi!');
                window.location.href = 'detail-booking.php?id_booking=$id_booking';
              </script>";
        exit;
    } catch (Exception $e) {
        mysqli_rollback($koneksi);

        echo "<script>
                alert('Gagal menyelesaikan booking!');
                window.location.href = 'detail-booking.php?id_booking=$id_booking';
              </script>";
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
    echo "<script>
            alert('Data booking tidak ditemukan!');
            window.location.href = 'data-booking.php';
          </script>";
    exit;
}

// Mengambil detail layanan booking
$query_layanan = mysqli_query(
    $koneksi,
    "SELECT 
        layanan.id_layanan,
        layanan.nama_layanan,
        layanan.harga_min AS harga_layanan,
        layanan.durasi_layanan
     FROM booking_detail
     JOIN layanan ON booking_detail.id_layanan = layanan.id_layanan
     WHERE booking_detail.id_booking = $id_booking"
);

// Menyiapkan total harga dan durasi
$total_harga = 0;
$total_durasi = 0;
$layanan_booking = [];
$id_layanan_booking = [];

// Menghitung total layanan
while ($layanan = mysqli_fetch_assoc($query_layanan)) {
    $layanan_booking[] = $layanan;
    $id_layanan_booking[] = (int) $layanan['id_layanan'];
    $total_harga += (int) $layanan['harga_layanan'];
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

    return 'bg-gray-50 text-gray-700';
}

// Mengambil stok barang untuk tambahan bahan
$data_stok_barang = select("\n    SELECT * FROM $tabel_stok\n    ORDER BY nama_barang ASC\n");

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

                <!-- Section detail booking -->
                <section class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Konfirmasi booking pelanggan atau beri saran jadwal lain.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-booking.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-white border border-pink-100 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Grid detail booking -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                        <!-- Detail pelanggan dan booking -->
                        <div class="lg:col-span-2 space-y-6">

                            <!-- Card informasi booking -->
                            <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                                <!-- Header card -->
                                <div class="p-6 border-b border-pink-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <h4 class="font-bold text-gray-800">
                                            Informasi Booking
                                        </h4>

                                        <p class="text-xs text-gray-400">
                                            Booking #<?= htmlspecialchars($booking['id_booking']); ?>
                                        </p>
                                    </div>

                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full uppercase <?= badge_status_booking($booking['status_booking']); ?>">
                                        <?= htmlspecialchars($booking['status_booking']); ?>
                                    </span>
                                </div>

                                <!-- Isi informasi booking -->
                                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">

                                    <!-- Nama pelanggan -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Nama Pelanggan
                                        </p>

                                        <p class="text-sm font-bold text-gray-800 mt-1">
                                            <?= htmlspecialchars($booking['nama']); ?>
                                        </p>
                                    </div>

                                    <!-- Nomor hp -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            No HP
                                        </p>

                                        <p class="text-sm font-bold text-gray-800 mt-1">
                                            <?= htmlspecialchars($booking['no_hp']); ?>
                                        </p>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Email
                                        </p>

                                        <p class="text-sm font-bold text-gray-800 mt-1">
                                            <?= htmlspecialchars($booking['email']); ?>
                                        </p>
                                    </div>

                                    <!-- Tanggal booking -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Tanggal Booking
                                        </p>

                                        <p class="text-sm font-bold text-gray-800 mt-1">
                                            <?= date('d M Y', strtotime($booking['tanggal_booking'])); ?>
                                        </p>
                                    </div>

                                    <!-- Jam booking -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Jam Booking
                                        </p>

                                        <p class="text-sm font-bold text-gray-800 mt-1">
                                            <?= substr($booking['jam_mulai'], 0, 5); ?> - <?= substr($booking['jam_selesai'], 0, 5); ?>
                                        </p>
                                    </div>

                                    <!-- Alamat -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Alamat
                                        </p>

                                        <p class="text-sm font-bold text-gray-800 mt-1">
                                            <?= htmlspecialchars($booking['alamat']); ?>
                                        </p>
                                    </div>

                                    <!-- Bukti pembayarab -->
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Bukti Pembayaran
                                        </p>
                                        <!-- JIKA ADA BUKTI PEMBAYARAN -->
                                        <p class="text-sm font-bold text-gray-800 mt-1">
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
                                                <p class="text-sm text-gray-500">
                                                    Tidak ada bukti pembayaran
                                                </p>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Card layanan booking -->
                            <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                                <!-- Header card layanan -->
                                <div class="p-6 border-b border-pink-100">
                                    <h4 class="font-bold text-gray-800">
                                        Layanan Dipilih
                                    </h4>
                                </div>

                                <!-- List layanan -->
                                <div class="p-6 space-y-3">
                                    <?php if (!empty($layanan_booking)) : ?>
                                        <?php foreach ($layanan_booking as $layanan) : ?>
                                            <div class="flex items-center justify-between gap-4 p-4 bg-pink-50/40 border border-pink-100 rounded-2xl">
                                                <div>
                                                    <h5 class="text-sm font-bold text-gray-800">
                                                        <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                                    </h5>

                                                    <p class="text-xs text-gray-400 mt-1">
                                                        <?= htmlspecialchars($layanan['durasi_layanan']); ?> menit
                                                    </p>
                                                </div>

                                                <p class="text-sm font-bold text-pink-600">
                                                    Rp <?= number_format($layanan['harga_layanan'], 0, ',', '.'); ?>
                                                </p>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p class="text-sm text-gray-400 text-center py-4">
                                            Layanan tidak ditemukan.
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <!-- Total layanan -->
                                <div class="p-6 border-t border-pink-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Total Harga
                                        </p>

                                        <p class="text-xl font-bold text-pink-600 mt-1">
                                            Rp <?= number_format($total_harga, 0, ',', '.'); ?>
                                        </p>
                                    </div>

                                    <div>
                                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                            Estimasi Waktu
                                        </p>

                                        <p class="text-xl font-bold text-gray-800 mt-1">
                                            <?= $total_durasi; ?> Menit
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <!-- Card stok paket layanan -->
                            <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                                <!-- Header stok paket -->
                                <div class="p-6 border-b border-pink-100">
                                    <h4 class="font-bold text-gray-800">
                                        Stok Barang yang Dipakai
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Paket stok otomatis berdasarkan layanan yang dibooking customer.
                                    </p>
                                </div>

                                <!-- Isi stok paket -->
                                <div class="p-6 space-y-3">
                                    <?php if (!empty($stok_paket_booking)) : ?>
                                        <?php foreach ($stok_paket_booking as $stok_paket) : ?>
                                            <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl">
                                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                                                    <div>
                                                        <p class="text-xs font-bold text-pink-600">
                                                            <?= htmlspecialchars($stok_paket['nama_layanan']); ?>
                                                        </p>

                                                        <h5 class="text-sm font-bold text-gray-800 mt-1">
                                                            <?= htmlspecialchars($stok_paket['nama_barang']); ?>
                                                        </h5>

                                                        <p class="text-xs text-gray-400 mt-1">
                                                            Jenis: <?= htmlspecialchars($stok_paket['jenis_barang']); ?>
                                                        </p>
                                                    </div>

                                                    <div class="text-left sm:text-right">
                                                        <p class="text-sm font-bold text-gray-800">
                                                            Pakai: <?= (int) $stok_paket['jumlah_stok']; ?> <?= htmlspecialchars($stok_paket['satuan_barang']); ?>
                                                        </p>

                                                        <p class="text-xs text-gray-400 mt-1">
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
                                        <p class="text-sm text-gray-400 text-center py-4">
                                            Belum ada paket stok untuk layanan ini.
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                        </div>

                        <!-- Panel aksi admin -->
                        <div class="space-y-6">

                            <!-- Card aksi booking -->
                            <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                                <!-- Header aksi -->
                                <div class="p-6 border-b border-pink-100">
                                    <h4 class="font-bold text-gray-800">
                                        Aksi Admin
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Atur status booking pelanggan.
                                    </p>
                                </div>

                                <!-- Isi aksi -->
                                <div class="p-6 space-y-3">

                                    <?php if ($booking['status_booking'] == 'Waiting' || $booking['status_booking'] == 'Pending') : ?>

                                        <!-- Tombol konfirmasi -->
                                        <form action="" method="POST">
                                            <button 
                                                type="submit" 
                                                name="konfirmasi_booking"
                                                onclick="return confirm('Konfirmasi booking pelanggan ini?')"
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-500 text-white text-sm font-bold rounded-xl hover:bg-blue-600 transition-colors"
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
                                                onclick="return confirm('Batalkan booking pelanggan ini?')"
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

                                            <!-- Tambahan harga opsional -->
                                            <div>
                                                <label for="tambahan_harga" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Tambahan Harga Opsional
                                                </label>

                                                <input 
                                                    type="number"
                                                    name="tambahan_harga"
                                                    id="tambahan_harga"
                                                    min="0"
                                                    value="0"
                                                    placeholder="Contoh: 50000"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                                >

                                                <p class="text-[11px] text-gray-400 mt-1">
                                                    Isi jika ada tambahan biaya, misalnya rambut panjang.
                                                </p>
                                            </div>

                                            <!-- Catatan tambahan opsional -->
                                            <div>
                                                <label for="catatan_tambahan" class="block text-sm font-medium text-gray-700 mb-1">
                                                    Catatan Tambahan Opsional
                                                </label>

                                                <textarea 
                                                    name="catatan_tambahan"
                                                    id="catatan_tambahan"
                                                    rows="3"
                                                    placeholder="Contoh: tambahan cat rambut karena rambut panjang"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200 resize-none"
                                                ></textarea>
                                            </div>

                                            <!-- Tambahan bahan opsional -->
                                            <div>
                                                <div class="flex items-center justify-between mb-2">
                                                    <label class="block text-sm font-medium text-gray-700">
                                                        Tambahan Bahan Opsional
                                                    </label>

                                                    <button 
                                                        type="button"
                                                        onclick="tambahBahan()"
                                                        class="px-3 py-1.5 bg-pink-50 text-pink-600 text-xs font-bold rounded-lg hover:bg-pink-100 transition"
                                                    >
                                                        + Tambah Bahan
                                                    </button>
                                                </div>

                                                <div id="tambahan-bahan-wrapper" class="space-y-2"></div>

                                                <p class="text-[11px] text-gray-400 mt-2">
                                                    Kosongkan jika tidak ada tambahan bahan.
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

                                        <!-- Info status final -->
                                        <div class="p-4 bg-gray-50 rounded-xl text-center">
                                            <p class="text-sm font-semibold text-gray-500">
                                                Booking sudah berstatus <?= htmlspecialchars($booking['status_booking']); ?>.
                                            </p>
                                        </div>

                                    <?php endif; ?>
                                </div>
                            </div>


                            <!-- Card kalender admin compact -->
                            <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                                <!-- Header kalender admin -->
                                <div class="p-4 border-b border-pink-100 flex items-center justify-between gap-3">
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">
                                            Kalender Booking
                                        </h4>

                                        <p class="text-[11px] text-gray-400 mt-1">
                                            Cek tanggal booking aktif.
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button 
                                            type="button"
                                            onclick="adminPrevMonth()"
                                            class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 hover:bg-pink-100 transition"
                                        >
                                            <i class="fa-solid fa-chevron-left text-xs"></i>
                                        </button>

                                        <button 
                                            type="button"
                                            onclick="adminNextMonth()"
                                            class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 hover:bg-pink-100 transition"
                                        >
                                            <i class="fa-solid fa-chevron-right text-xs"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Isi kalender admin -->
                                <div class="p-4">
                                    <div class="flex items-center justify-between gap-3 mb-4">
                                        <h5 id="admin-calendar-title" class="text-xs font-bold text-gray-800"></h5>

                                        <p class="text-[10px] text-gray-400">
                                            Pink: booking
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-7 gap-1.5 text-center text-[10px] font-bold text-gray-400 mb-2">
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

                                <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                                    <!-- Header saran tersimpan -->
                                    <div class="p-6 border-b border-pink-100">
                                        <h4 class="font-bold text-gray-800">
                                            Saran Jadwal Tersimpan
                                        </h4>
                                    </div>

                                    <!-- Isi saran -->
                                    <div class="p-6 space-y-4">
                                        <div>
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                                Tanggal Saran
                                            </p>

                                            <p class="text-sm font-bold text-gray-800 mt-1">
                                                <?= date('d M Y', strtotime($booking['tanggal_saran'])); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                                Jam Saran
                                            </p>

                                            <p class="text-sm font-bold text-gray-800 mt-1">
                                                <?= substr($booking['jam_saran'], 0, 5); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                                Catatan Admin
                                            </p>

                                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
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
            <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-orange-100 overflow-hidden">

                <!-- Header modal -->
                <div class="p-5 border-b border-orange-100 flex items-start justify-between gap-4">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">
                            Pending & Saran Jadwal
                        </h4>

                        <p class="text-xs text-gray-400 mt-1">
                            Isi tanggal, jam, dan catatan saran untuk customer.
                        </p>
                    </div>

                    <!-- Tombol tutup modal -->
                    <button 
                        type="button"
                        onclick="closePendingModal()"
                        class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition"
                    >
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Form pending -->
                <form action="" method="POST" class="p-5 space-y-4">

                    <!-- Input tanggal saran -->
                    <div>
                        <label for="tanggal_saran" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Saran
                        </label>

                        <input 
                            type="date" 
                            name="tanggal_saran" 
                            id="tanggal_saran"
                            required
                            class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-200"
                        >

                        <p class="text-[11px] text-gray-400 mt-1">
                            Jangan pilih hari Rabu karena salon libur.
                        </p>
                    </div>

                    <!-- Input jam saran -->
                    <div>
                        <label for="jam_saran" class="block text-sm font-medium text-gray-700 mb-1">
                            Jam Saran
                        </label>

                        <input 
                            type="time" 
                            name="jam_saran" 
                            id="jam_saran"
                            required
                            min="10:00"
                            max="21:00"
                            class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-200"
                        >
                    </div>

                    <!-- Input catatan admin -->
                    <div>
                        <label for="catatan_admin" class="block text-sm font-medium text-gray-700 mb-1">
                            Catatan Admin
                        </label>

                        <textarea 
                            name="catatan_admin" 
                            id="catatan_admin"
                            rows="4"
                            required
                            placeholder="Contoh: Mohon datang jam 15:00 karena jadwal sebelumnya penuh."
                            class="w-full px-3 py-2 border border-orange-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-200 resize-none"
                        ></textarea>
                    </div>

                    <!-- Aksi modal -->
                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                        <button 
                            type="button"
                            onclick="closePendingModal()"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-gray-50 text-gray-500 text-sm font-bold rounded-xl hover:bg-gray-100 transition-colors"
                        >
                            Batal
                        </button>

                        <button 
                            type="submit" 
                            name="pending_booking"
                            onclick="return confirm('Jadikan booking ini pending dan kirim saran jadwal?')"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-orange-500 text-white text-sm font-bold rounded-xl hover:bg-orange-600 transition-colors"
                        >
                            <i class="fa-solid fa-paper-plane"></i>
                            <span>Simpan Saran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
<!-- MENJADIKAN FILE JS DI LAYOUT SEBAGAI MODULAR -->
<script src="../layout/js/booking-detail.js">
</script>



<?php
// Memanggil footer utama
include "../layout/footer.php";
?>