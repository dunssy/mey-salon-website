<?php

// Memulai session user
global $koneksi;

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

    // Mengecek email digunakan user lain
    $cek_email = mysqli_query(
        $koneksi,
        "SELECT id_user FROM user 
         WHERE email = '$email' 
         AND id_user != $id_user"
    );

    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>
                alert('Email sudah digunakan oleh akun lain!');
                window.location.href = 'booking.php';
              </script>";
        exit;
    }

    // Mengupdate profil user
    $query_update_profil = "UPDATE user SET 
                                nama = '$nama',
                                no_hp = '$no_hp',
                                email = '$email',
                                alamat = '$alamat'
                            WHERE id_user = $id_user";

    if (mysqli_query($koneksi, $query_update_profil)) {
        $_SESSION['nama'] = $nama;

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

        // Mengecek saran tanggal dan jam tersedia
        if (empty($tanggal_saran) || empty($jam_saran)) {
            echo "<script>
                    alert('Admin belum memberikan saran tanggal atau jam.');
                    window.location.href = 'booking.php';
                  </script>";
            exit;
        }

        // Menghitung ulang jam selesai berdasarkan durasi layanan
        $query_durasi = mysqli_query(
            $koneksi,
            "SELECT COALESCE(SUM(l.durasi_layanan), 0) AS total_durasi
             FROM booking_detail bd
             JOIN layanan l ON bd.id_layanan = l.id_layanan
             WHERE bd.id_booking = $id_booking"
        );

        $data_durasi = mysqli_fetch_assoc($query_durasi);
        $total_durasi = (int) ($data_durasi['total_durasi'] ?? 0);

        if ($total_durasi <= 0) {
            echo "<script>
                    alert('Durasi layanan tidak valid.');
                    window.location.href = 'booking.php';
                  </script>";
            exit;
        }

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

// Mengambil booking user login sesuai DB final
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
    $tanggal_booking = mysqli_real_escape_string($koneksi, $_POST['tanggal_booking'] ?? '');
    $jam_mulai = mysqli_real_escape_string($koneksi, $_POST['jam_mulai'] ?? '');
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($koneksi, strip_tags($_POST['catatan'])) : '';

    // Mengambil layanan terpilih dari JSON
    $layanan_terpilih_raw = $_POST['layanan_terpilih'] ?? '';
    $layanan_terpilih_decode = json_decode($layanan_terpilih_raw, true);

    // Menyiapkan list id layanan agar cocok dengan format lama dan format cart baru
    $id_layanan_list = [];

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

    if (empty($tanggal_booking) || empty($jam_mulai) || empty($id_layanan_list)) {
        echo "<script>alert('Tanggal, jam, dan layanan wajib dipilih!');</script>";
    } else {
        $id_layanan_string = implode(',', $id_layanan_list);

        // Mengambil total durasi dan total harga minimum dari layanan
        $query_total = mysqli_query(
            $koneksi,
            "SELECT 
                COALESCE(SUM(durasi_layanan), 0) AS total_durasi,
                COALESCE(SUM(harga_min), 0) AS total_harga
             FROM layanan 
             WHERE id_layanan IN ($id_layanan_string)"
        );

        $data_total = mysqli_fetch_assoc($query_total);
        $total_durasi = (int) ($data_total['total_durasi'] ?? 0);
        $total_dp = (int) ($data_total['total_harga'] ?? 0);

        if ($total_durasi <= 0 || $total_dp <= 0) {
            echo "<script>alert('Durasi atau total harga layanan tidak valid!');</script>";
        } else {
            // Upload bukti pembayaran sesuai kolom DB: bukti_pembayaran
            $bukti_pembayaran = '';

            if (!isset($_FILES['bukti_pembayaran']) || $_FILES['bukti_pembayaran']['error'] !== UPLOAD_ERR_OK) {
                echo "<script>alert('Bukti pembayaran wajib diupload!');</script>";
                exit;
            }

            $file_tmp = $_FILES['bukti_pembayaran']['tmp_name'];
            $file_name = $_FILES['bukti_pembayaran']['name'];
            $file_size = $_FILES['bukti_pembayaran']['size'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

            if (!in_array($file_ext, $allowed_ext)) {
                echo "<script>alert('Format bukti pembayaran harus JPG, JPEG, PNG, WEBP, atau PDF!');</script>";
                exit;
            }

            if ($file_size > 2 * 1024 * 1024) {
                echo "<script>alert('Ukuran bukti pembayaran maksimal 2MB!');</script>";
                exit;
            }

            $upload_dir = "../uploads/bukti-pembayaran/";

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $bukti_pembayaran = "bukti_" . date('YmdHis') . "_" . rand(1000, 9999) . "." . $file_ext;
            $upload_path = $upload_dir . $bukti_pembayaran;

            if (!move_uploaded_file($file_tmp, $upload_path)) {
                echo "<script>alert('Upload bukti pembayaran gagal!');</script>";
                exit;
            }

            $waktu_mulai = new DateTime($tanggal_booking . ' ' . $jam_mulai);
            $waktu_selesai = clone $waktu_mulai;
            $waktu_selesai->modify("+$total_durasi minutes");

            // Jam tutup salon 21:00
            $batas_tutup = new DateTime($tanggal_booking . ' 21:00:00');

            if ($waktu_selesai > $batas_tutup) {
                if (!empty($upload_path) && file_exists($upload_path)) {
                    unlink($upload_path);
                }

                echo "<script>alert('Estimasi waktu melewati jam tutup salon!');</script>";
            } else {
                $jam_selesai = $waktu_selesai->format('H:i:s');

                // Mengecek jadwal bentrok dengan booking aktif
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

                if (mysqli_num_rows($cek_jadwal) > 0) {
                    if (!empty($upload_path) && file_exists($upload_path)) {
                        unlink($upload_path);
                    }

                    echo "<script>alert('Jadwal pada jam tersebut sudah terisi!');</script>";
                } else {
                    mysqli_begin_transaction($koneksi);

                    try {
                        // Menyimpan booking sesuai DB final:
                        // total_dp dan bukti_pembayaran sudah ada, metode_pembayaran tidak dipakai karena tidak ada di DB
                        $query_insert = "INSERT INTO booking 
                                            (id_user, tanggal_booking, jam_mulai, jam_selesai, status_booking, total_dp, bukti_pembayaran, catatan_costumer)
                                         VALUES 
                                            ($id_user, '$tanggal_booking', '$jam_mulai', '$jam_selesai', 'Waiting', $total_dp, '$bukti_pembayaran', '$catatan')";

                        mysqli_query($koneksi, $query_insert);
                        $id_booking = mysqli_insert_id($koneksi);

                        // Menyimpan detail layanan booking
                        foreach ($id_layanan_list as $id_layanan) {
                            mysqli_query(
                                $koneksi,
                                "INSERT INTO booking_detail (id_booking, id_layanan)
                                 VALUES ($id_booking, $id_layanan)"
                            );
                        }

                        mysqli_commit($koneksi);

                        echo "<script>
                                alert('Booking berhasil dibuat! Silakan tunggu admin mengecek bukti pembayaran QRIS.');
                                window.location.href = 'booking.php';
                              </script>";
                        exit;
                    } catch (Exception $e) {
                        mysqli_rollback($koneksi);

                        if (!empty($upload_path) && file_exists($upload_path)) {
                            unlink($upload_path);
                        }

                        echo "<script>
                                alert('Booking gagal dibuat!');
                                window.location.href = 'booking.php';
                              </script>";
                        exit;
                    }
                }
            }
        }
    }
}
?>
