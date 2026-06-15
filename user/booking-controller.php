<?php
// Memulai session user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil koneksi database
include "../config/app.php";

// Menggunakan koneksi global
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

// Mengubah data user menjadi array
$user = mysqli_fetch_assoc($query_user);

// Mengecek data user ditemukan
if (!$user) {
    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Fungsi redirect dengan alert
function redirectAlert($pesan, $tujuan = 'booking.php')
{
    
    // Menampilkan pesan dan pindah halaman
    $_SESSION['alert'] = [
      'icon' => 'success',
       'title' => 'Berhasil',
       'text' => $pesan,
    ];

    header("Location: $tujuan");
    exit;
}

// Fungsi hapus file jika ada
function hapusFileJikaAda($path)
{
    // Mengecek file lalu hapus
    if (!empty($path) && file_exists($path)) {
        unlink($path);
    }
}

// Fungsi validasi hari Rabu
function validasiSalonTidakLiburRabu($tanggal)
{
    // Mengambil angka hari dari tanggal
    $hari = date('w', strtotime($tanggal));

    // Menolak booking hari Rabu
    if ($hari == 3) {
        redirectAlert('Salon libur setiap hari Rabu. Silakan pilih tanggal lain!');
    }
}

// Fungsi upload bukti pembayaran
function uploadBuktiPembayaran()
{
    // Mengecek file wajib ada
    if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
        redirectAlert('Bukti pembayaran wajib diupload!');
    }

    // Mengambil data file
    $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
    $file_name = $_FILES['bukti_pembayaran']['name'];
    $file_size = $_FILES['bukti_pembayaran']['size'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Menentukan ekstensi yang diizinkan
    $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

    // Mengecek format file
    if (!in_array($file_ext, $allowed_ext)) {
        redirectAlert('Format bukti pembayaran harus JPG, JPEG, PNG, WEBP, atau PDF!');
    }

    // Mengecek ukuran maksimal 2MB
    if ($file_size > 2 * 1024 * 1024) {
        redirectAlert('Ukuran bukti pembayaran maksimal 2MB!');
    }

    // Menentukan folder upload
    $upload_dir = "../uploads/bukti-pembayaran/";

    // Membuat folder jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Membuat nama file unik
    $bukti_pembayaran = "bukti_" . date('YmdHis') . "_" . rand(1000, 9999) . "." . $file_ext;

    // Menentukan path upload
    $upload_path = $upload_dir . $bukti_pembayaran;

    // Memindahkan file upload
    if (!move_uploaded_file($file_tmp, $upload_path)) {
        redirectAlert('Upload bukti pembayaran gagal!');
    }

    // Mengembalikan nama dan path file
    return [
        'nama_file' => $bukti_pembayaran,
        'path_file' => $upload_path
    ];
}

// Memproses update profil user
if (isset($_POST['update_profil'])) {
    // Mengambil input profil
    $nama = mysqli_real_escape_string($koneksi, strip_tags($_POST['nama']));
    $no_hp = mysqli_real_escape_string($koneksi, strip_tags($_POST['no_hp']));
    $email = mysqli_real_escape_string($koneksi, strip_tags($_POST['email']));
    $alamat = mysqli_real_escape_string($koneksi, strip_tags($_POST['alamat']));

    // Mengecek email digunakan user lain
    $cek_email = mysqli_query(
        $koneksi,
        "SELECT id_user FROM user 
         WHERE email = '$email' 
         AND id_user != $id_user"
    );

    // Menolak jika email sudah digunakan
    if (mysqli_num_rows($cek_email) > 0) {
        redirectAlert('Email sudah digunakan oleh akun lain!');
    }

    // Query update profil
    $query_update_profil = "UPDATE user SET 
                                nama = '$nama',
                                no_hp = '$no_hp',
                                email = '$email',
                                alamat = '$alamat'
                            WHERE id_user = $id_user";

    // Menjalankan update profil
    if (mysqli_query($koneksi, $query_update_profil)) {
        $_SESSION['nama'] = $nama;
        redirectAlert('Profil berhasil diperbarui!');
    } else {
        echo "<script>alert('Profil gagal diperbarui!');</script>";
    }
}

// Memproses batal booking
if (isset($_POST['batal_booking'])) {
    // Mengambil id booking
    $id_booking = (int) $_POST['id_booking'];

    // Mengecek booking milik user dan status bisa batal
    $cek_booking = mysqli_query(
        $koneksi,
        "SELECT * FROM booking 
         WHERE id_booking = $id_booking 
         AND id_user = $id_user 
         AND status_booking IN ('Waiting', 'Pending')"
    );

    // Membatalkan booking jika valid
    if (mysqli_num_rows($cek_booking) > 0) {
        mysqli_query(
            $koneksi,
            "UPDATE booking 
             SET status_booking = 'Cancel' 
             WHERE id_booking = $id_booking 
             AND id_user = $id_user"
        );

        redirectAlert('Booking berhasil dibatalkan!');
    }

    // Menolak batal jika status tidak valid
    redirectAlert('Booking tidak bisa dibatalkan karena sudah diproses admin!');
}

// Memproses user menerima saran jadwal admin
if (isset($_POST['terima_saran_booking'])) {
    // Mengambil id booking
    $id_booking = (int) $_POST['id_booking'];

    // Mengambil booking pending milik user
    $query_saran = mysqli_query(
        $koneksi,
        "SELECT * FROM booking 
         WHERE id_booking = $id_booking 
         AND id_user = $id_user 
         AND status_booking = 'Pending'"
    );

    // Mengecek data saran
    if (mysqli_num_rows($query_saran) <= 0) {
        redirectAlert('Saran jadwal tidak ditemukan atau booking tidak valid.');
    }

    // Mengambil data booking saran
    $booking_saran = mysqli_fetch_assoc($query_saran);

    // Mengambil tanggal dan jam saran
    $tanggal_saran = $booking_saran['tanggal_saran'];
    $jam_saran = $booking_saran['jam_saran'];

    // Mengecek saran lengkap
    if (empty($tanggal_saran) || empty($jam_saran)) {
        redirectAlert('Admin belum memberikan saran tanggal atau jam.');
    }

    // Validasi salon libur Rabu
    validasiSalonTidakLiburRabu($tanggal_saran);

    // Menghitung total durasi layanan
    $query_durasi = mysqli_query(
        $koneksi,
        "SELECT COALESCE(SUM(l.durasi_layanan), 0) AS total_durasi
         FROM booking_detail bd
         JOIN layanan l ON bd.id_layanan = l.id_layanan
         WHERE bd.id_booking = $id_booking"
    );

    // Mengambil data durasi
    $data_durasi = mysqli_fetch_assoc($query_durasi);

    // Mengubah durasi ke integer
    $total_durasi = (int) ($data_durasi['total_durasi'] ?? 0);

    // Mengecek durasi valid
    if ($total_durasi <= 0) {
        redirectAlert('Durasi layanan tidak valid.');
    }

    // Membuat waktu mulai
    $waktu_mulai = new DateTime($tanggal_saran . ' ' . $jam_saran);

    // Membuat waktu selesai
    $waktu_selesai = clone $waktu_mulai;

    // Menambahkan durasi
    $waktu_selesai->modify("+$total_durasi minutes");

    // Mengambil jam selesai
    $jam_selesai = $waktu_selesai->format('H:i:s');

    // Mengubah booking ke jadwal saran
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

    // Redirect berhasil
    redirectAlert('Saran jadwal berhasil dikonfirmasi. Silakan tunggu konfirmasi admin.');
}

// Mengambil data layanan sesuai DB final
$query_layanan = mysqli_query(
    $koneksi,
    "SELECT 
        id_layanan,
        nama_layanan,
        harga_min,
        harga_max,
        harga_min AS harga_layanan,
        durasi_layanan,
        keterangan_harga,
        gambar_layanan
     FROM layanan
     ORDER BY nama_layanan ASC"
);

// Mengambil data booking aktif untuk kalender
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

// Menyiapkan data kalender
$jadwal_booking = [];

// Mengisi data kalender
while ($booking = mysqli_fetch_assoc($query_booking)) {
    // Mengambil tanggal booking
    $tanggal = substr($booking['tanggal_booking'], 0, 10);

    // Mengambil nama layanan
    $layanan = $booking['nama_layanan'] ?: 'Booking';

    // Membuat array tanggal jika belum ada
    if (!isset($jadwal_booking[$tanggal])) {
        $jadwal_booking[$tanggal] = [];
    }

    // Menambahkan jadwal booking dalam bentuk object agar JS bisa membaca jam_mulai dan jam_selesai
    $jadwal_booking[$tanggal][] = [
        'id_booking' => (int) $booking['id_booking'],
        'tanggal_booking' => $tanggal,
        'jam_mulai' => substr($booking['jam_mulai'], 0, 5),
        'jam_selesai' => substr($booking['jam_selesai'], 0, 5),
        'layanan' => $layanan,
        'status' => $booking['status_booking']
    ];
}

// Mengambil booking user login
$query_booking_user = mysqli_query(
    $koneksi,
    "SELECT 
        b.*,
        b.catatan_costumer AS catatan,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan,
        COALESCE(SUM(l.harga_min), 0) AS total_harga,
        COALESCE(SUM(l.durasi_layanan), 0) AS total_durasi
     FROM booking b
     LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan
     WHERE b.id_user = $id_user
     GROUP BY b.id_booking
     ORDER BY b.tanggal_booking DESC, b.jam_mulai DESC"
);

// Memproses booking baru
if (isset($_POST['konfirmasi_booking'])) {
    // Mengambil tanggal booking
    $tanggal_booking = mysqli_real_escape_string($koneksi, $_POST['tanggal_booking'] ?? '');

    // Mengambil jam mulai
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai'] ?? '');

    // Mengambil catatan customer
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, strip_tags($_POST['catatan'])) : '';

    // Mengambil layanan terpilih JSON
    $layanan_terpilih_raw = $_POST['layanan_terpilih'] ?? '';

    // Decode layanan terpilih
    $layanan_terpilih_decode = json_decode($layanan_terpilih_raw, true);

    // Menyiapkan id layanan
    $id_layanan_list = [];

    // Membaca id layanan dari JSON
    if (is_array($layanan_terpilih_decode)) {
        foreach ($layanan_terpilih_decode as $item) {
            if (is_array($item)) {
                $id_layanan_list[] = (int) ($item['id'] ?? $item['id_layanan'] ?? $item['service_id'] ?? 0);
            } else {
                $id_layanan_list[] = (int) $item;
            }
        }
    }

    // Membersihkan id layanan kosong dan duplikat
    $id_layanan_list = array_values(array_unique(array_filter($id_layanan_list)));

    // Mengecek input wajib
    if (empty($tanggal_booking) || empty($jam_mulai) || empty($id_layanan_list)) {
        echo "<script>alert('Tanggal, jam, dan layanan wajib dipilih!');</script>";
    } else {
        // Validasi salon libur Rabu
        validasiSalonTidakLiburRabu($tanggal_booking);

        // Menggabungkan id layanan untuk query
        $id_layanan_string = implode(',', $id_layanan_list);

        // Mengambil total durasi dan total harga minimum
        $query_total = mysqli_query(
            $koneksi,
            "SELECT 
                COALESCE(SUM(durasi_layanan), 0) AS total_durasi,
                COALESCE(SUM(harga_min), 0) AS total_harga
             FROM layanan 
             WHERE id_layanan IN ($id_layanan_string)"
        );

        // Mengambil hasil total
        $data_total = mysqli_fetch_assoc($query_total);

        // Mengambil total durasi dari layanan terpilih
        $total_durasi = (int) ($data_total['total_durasi'] ?? 0);

        // DP diambil dari input customer, bukan dari harga_min layanan
        $total_dp = isset($_POST['total_dp']) ? (int) preg_replace('/[^0-9]/', '', $_POST['total_dp']) : 0;

        // Mengecek total durasi layanan
        if ($total_durasi <= 0) {
            echo "<script>alert('Durasi layanan tidak valid!');</script>";
        } elseif ($total_dp < 50000) {
            echo "<script>alert('Minimal DP adalah Rp 50.000!');</script>";
        } else {
            // Upload bukti pembayaran
            $upload_bukti = uploadBuktiPembayaran();

            // Mengambil nama file bukti
            $bukti_pembayaran = $upload_bukti['nama_file'];

            // Mengambil path file bukti
            $upload_path = $upload_bukti['path_file'];

            // Membuat waktu mulai
            $waktu_mulai = new DateTime($tanggal_booking . ' ' . $jam_mulai);

            // Membuat waktu selesai
            $waktu_selesai = clone $waktu_mulai;

            // Menambahkan durasi ke waktu selesai
            $waktu_selesai->modify("+$total_durasi minutes");

            // Menentukan batas jam tutup salon
            $batas_tutup = new DateTime($tanggal_booking . ' 21:00:00');

            // Mengecek melewati jam tutup
            if ($waktu_selesai > $batas_tutup) {
                hapusFileJikaAda($upload_path);
                echo "<script>alert('Estimasi waktu melewati jam tutup salon!');</script>";
            } else {
                // Mengambil jam selesai
                $jam_selesai = $waktu_selesai->format('H:i:s');

                // Mengecek jadwal bentrok
                $cek_jadwal = mysqli_query(
                    $koneksi,
                    "SELECT * FROM booking 
                     WHERE tanggal_booking = '$tanggal_booking'
                     AND status_booking IN ('Waiting', 'Pending', 'On-going')
                     AND (
                        ('$jam_mulai' >= jam_mulai AND '$jam_mulai' < jam_selesai)
                        OR ('$jam_selesai' > jam_mulai AND '$jam_selesai' <= jam_selesai)
                        OR ('$jam_mulai' <= jam_mulai AND '$jam_selesai' >= jam_selesai)
                     )"
                );

                // Menolak jika jadwal bentrok
                if (mysqli_num_rows($cek_jadwal) > 0) {
                    hapusFileJikaAda($upload_path);
                    echo "<script>alert('Jadwal pada jam tersebut sudah terisi!');</script>";
                } else {
                    // Memulai transaksi database
                    mysqli_begin_transaction($koneksi);

                    try {
                        // Query insert booking
                        $query_insert = "INSERT INTO booking 
                                            (id_user, tanggal_booking, jam_mulai, jam_selesai, status_booking, total_dp, bukti_pembayaran, catatan_costumer)
                                         VALUES 
                                            ($id_user, '$tanggal_booking', '$jam_mulai', '$jam_selesai', 'Waiting', $total_dp, '$bukti_pembayaran', '$catatan')";

                        // Menyimpan booking
                        mysqli_query($koneksi, $query_insert);

                        // Mengambil id booking baru
                        $id_booking = mysqli_insert_id($koneksi);

                        // Menyimpan detail layanan booking
                        foreach ($id_layanan_list as $id_layanan) {
                            mysqli_query(
                                $koneksi,
                                "INSERT INTO booking_detail (id_booking, id_layanan)
                                 VALUES ($id_booking, $id_layanan)"
                            );
                        }

                        // Commit transaksi
                        mysqli_commit($koneksi);

                        // Redirect berhasil tampilkan sweetalert
            
                        redirectAlert('Booking berhasil dibuat! Silakan tunggu konfirmasi admin.');

                    } catch (Exception $e) {
                        // Rollback transaksi
                        mysqli_rollback($koneksi);

                        // Hapus file bukti
                        hapusFileJikaAda($upload_path);

                        // Redirect gagal
                        redirectAlert('Booking gagal dibuat!');
                    }
                }
            }
        }
    }
}
?>
