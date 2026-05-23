<?php
// Memulai session user
session_start();

// Memanggil koneksi dan controller utama
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek user sudah login
if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// Mengambil id user dari session
$id_user = isset($_SESSION['id_user']) ? (int) $_SESSION['id_user'] : (int) $_SESSION['user_id'];

// Mengambil data user login
$query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id_user");
$user = mysqli_fetch_assoc($query_user);

// Mengecek data user ditemukan
if (!$user) {
    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Memproses update profil user
if (isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($koneksi, strip_tags($_POST['nama']));
    $no_hp = mysqli_real_escape_string($koneksi, strip_tags($_POST['no_hp']));
    $email = mysqli_real_escape_string($koneksi, strip_tags($_POST['email']));
    $alamat = mysqli_real_escape_string($koneksi, strip_tags($_POST['alamat']));

    $query_update_profil = "UPDATE user SET 
                                nama = '$nama',
                                no_hp = '$no_hp',
                                email = '$email',
                                alamat = '$alamat'
                            WHERE id_user = $id_user";

    if (mysqli_query($koneksi, $query_update_profil)) {
        echo "<script>
                alert('Profil berhasil diperbarui!');
                window.location.href = 'booking.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Profil gagal diperbarui!');
              </script>";
    }
}

// Memproses batal booking
if (isset($_POST['batal_booking'])) {
    $id_booking = (int) $_POST['id_booking'];

    // Mengecek booking milik user dan status masih bisa dibatalkan
    $cek_booking = mysqli_query(
        $koneksi,
        "SELECT * FROM booking 
         WHERE id_booking = $id_booking 
         AND id_user = $id_user 
         AND status_booking IN ('Waiting', 'Pending')"
    );

    // Jika booking ditemukan dan status masih Waiting atau Pending
    if (mysqli_num_rows($cek_booking) > 0) {
        mysqli_query(
            $koneksi,
            "UPDATE booking 
             SET status_booking = 'Cancel' 
             WHERE id_booking = $id_booking 
             AND id_user = $id_user"
        );

        echo "<script>
                alert('Booking berhasil dibatalkan!');
                window.location.href = 'booking.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Booking tidak bisa dibatalkan karena sudah diproses admin!');
                window.location.href = 'booking.php';
              </script>";
        exit;
    }
}

// Memproses user menerima saran jadwal admin
if (isset($_POST['terima_saran_booking'])) {
    $id_booking = (int) $_POST['id_booking'];

    // Mengambil data booking yang masih pending dan milik user login
    $query_saran = mysqli_query(
        $koneksi,
        "SELECT * FROM booking 
         WHERE id_booking = $id_booking 
         AND id_user = $id_user 
         AND status_booking = 'Pending'"
    );

    // Mengecek booking ditemukan
    if (mysqli_num_rows($query_saran) > 0) {
        $booking_saran = mysqli_fetch_assoc($query_saran);

        $tanggal_saran = $booking_saran['tanggal_saran'];
        $jam_saran = $booking_saran['jam_saran'];

        // Menghitung ulang jam selesai berdasarkan durasi layanan
        $query_durasi = mysqli_query(
            $koneksi,
            "SELECT SUM(layanan.durasi_layanan) AS total_durasi
             FROM booking_detail
             JOIN layanan ON booking_detail.id_layanan = layanan.id_layanan
             WHERE booking_detail.id_booking = $id_booking"
        );

        $data_durasi = mysqli_fetch_assoc($query_durasi);
        $total_durasi = (int) $data_durasi['total_durasi'];

        $waktu_mulai = new DateTime($tanggal_saran . ' ' . $jam_saran);
        $waktu_selesai = clone $waktu_mulai;
        $waktu_selesai->modify("+$total_durasi minutes");

        $jam_selesai = $waktu_selesai->format('H:i:s');

        // Mengubah booking sesuai jadwal saran admin
        mysqli_query(
            $koneksi,
            "UPDATE booking SET
                tanggal_booking = '$tanggal_saran',
                jam_mulai = '$jam_saran',
                jam_selesai = '$jam_selesai',
                status_booking = 'Waiting'
             WHERE id_booking = $id_booking
             AND id_user = $id_user"
        );

        echo "<script>
                alert('Saran jadwal berhasil dikonfirmasi. Silakan tunggu konfirmasi admin.');
                window.location.href = 'booking.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Saran jadwal tidak ditemukan atau booking tidak valid.');
                window.location.href = 'booking.php';
              </script>";
        exit;
    }
}

// Mengambil data layanan
$query_layanan = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY nama_layanan ASC");

// Mengambil data booking untuk kalender
$query_booking = mysqli_query(
    $koneksi,
    "SELECT 
        b.id_booking,
        b.tanggal_booking,
        b.jam_mulai,
        b.jam_selesai,
        b.status_booking,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan
     FROM booking b
     LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan
     WHERE b.status_booking IN ('Waiting', 'Pending', 'On-going')
     GROUP BY b.id_booking
     ORDER BY b.tanggal_booking ASC, b.jam_mulai ASC"
);

// Menyiapkan data kalender untuk JavaScript
$jadwal_booking = [];

while ($booking = mysqli_fetch_assoc($query_booking)) {
    $tanggal = $booking['tanggal_booking'];
    $jam = substr($booking['jam_mulai'], 0, 5);
    $layanan = $booking['nama_layanan'] ?: 'Booking';

    if (!isset($jadwal_booking[$tanggal])) {
        $jadwal_booking[$tanggal] = [];
    }

    $jadwal_booking[$tanggal][] = $jam . " - " . $layanan;
}

// Mengambil booking user login
$query_booking_user = mysqli_query(
    $koneksi,
    "SELECT 
        b.*,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan,
        SUM(l.harga_layanan) AS total_harga,
        SUM(l.durasi_layanan) AS total_durasi
     FROM booking b
     LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan
     WHERE b.id_user = $id_user
     GROUP BY b.id_booking
     ORDER BY b.tanggal_booking DESC, b.jam_mulai DESC"
);

// Mengecek kolom catatan
$cek_kolom_catatan = mysqli_query($koneksi, "SHOW COLUMNS FROM booking LIKE 'catatan'");
$kolom_catatan_ada = mysqli_num_rows($cek_kolom_catatan) > 0;

// Memproses booking
if (isset($_POST['konfirmasi_booking'])) {
    $tanggal_booking = mysqli_real_escape_string($koneksi, $_POST['tanggal_booking']);
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai']);
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, $_POST['catatan']) : '';
    $layanan_terpilih = json_decode($_POST['layanan_terpilih'], true);

    if (empty($tanggal_booking) || empty($jam_mulai) || empty($layanan_terpilih)) {
        echo "<script>alert('Tanggal, jam, dan layanan wajib dipilih!');</script>";
    } else {
        $id_layanan_list = array_map('intval', $layanan_terpilih);
        $id_layanan_string = implode(',', $id_layanan_list);

        $query_total = mysqli_query(
            $koneksi,
            "SELECT SUM(durasi_layanan) AS total_durasi 
             FROM layanan 
             WHERE id_layanan IN ($id_layanan_string)"
        );

        $data_total = mysqli_fetch_assoc($query_total);
        $total_durasi = (int) $data_total['total_durasi'];

        $waktu_mulai = new DateTime($tanggal_booking . ' ' . $jam_mulai);
        $waktu_selesai = clone $waktu_mulai;
        $waktu_selesai->modify("+$total_durasi minutes");

        $batas_tutup = new DateTime($tanggal_booking . ' 20:00:00');

        if ($waktu_selesai > $batas_tutup) {
            echo "<script>alert('Estimasi waktu melewati jam tutup salon!');</script>";
        } else {
            $jam_selesai = $waktu_selesai->format('H:i:s');

            $cek_jadwal = mysqli_query(
                $koneksi,
                "SELECT * FROM booking 
                 WHERE tanggal_booking = '$tanggal_booking'
                 AND status_booking = 'Waiting'
                 AND (
                    ('$jam_mulai' >= jam_mulai AND '$jam_mulai' < jam_selesai)
                    OR ('$jam_selesai' > jam_mulai AND '$jam_selesai' <= jam_selesai)
                    OR ('$jam_mulai' <= jam_mulai AND '$jam_selesai' >= jam_selesai)
                 )"
            );

            if (mysqli_num_rows($cek_jadwal) > 0) {
                echo "<script>alert('Jadwal pada jam tersebut sudah terisi!');</script>";
            } else {
                if ($kolom_catatan_ada) {
                    $query_insert = "INSERT INTO booking 
                                        (id_user, tanggal_booking, jam_mulai, jam_selesai, status_booking, catatan)
                                     VALUES 
                                        ($id_user, '$tanggal_booking', '$jam_mulai', '$jam_selesai', 'Waiting', '$catatan')";
                } else {
                    $query_insert = "INSERT INTO booking 
                                        (id_user, tanggal_booking, jam_mulai, jam_selesai, status_booking)
                                     VALUES 
                                        ($id_user, '$tanggal_booking', '$jam_mulai', '$jam_selesai', 'Waiting')";
                }

                mysqli_query($koneksi, $query_insert);
                $id_booking = mysqli_insert_id($koneksi);

                foreach ($id_layanan_list as $id_layanan) {
                    mysqli_query(
                        $koneksi,
                        "INSERT INTO booking_detail (id_booking, id_layanan)
                         VALUES ($id_booking, $id_layanan)"
                    );
                }

                echo "<script>
                        alert('Booking berhasil dibuat! Silakan tunggu konfirmasi admin. Pembayaran dilakukan cash di salon.');
                        window.location.href = 'booking.php';
                      </script>";
                exit;
            }
        }
    }
}
?>