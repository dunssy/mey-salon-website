<?php
require_once '../includes/auth_check.php';

$id_user = $_SESSION['id_user'];
$tanggal = $_POST['tanggal_booking'];
$jam_mulai = $_POST['jam_mulai'];
$layanan = $_POST['layanan'] ?? [];

if (empty($layanan)) {
    echo "<script>alert('Pilih minimal satu layanan'); window.location='booking.php';</script>";
    exit;
}

$ids = implode(',', array_map('intval', $layanan));

$qDurasi = mysqli_query($conn, "SELECT SUM(durasi_layanan) AS total_durasi FROM layanan WHERE id_layanan IN ($ids)");
$dDurasi = mysqli_fetch_assoc($qDurasi);
$total_durasi = (int)$dDurasi['total_durasi'];

$jam_selesai = date("H:i:s", strtotime($jam_mulai . " +$total_durasi minutes"));

$cekBentrok = mysqli_query($conn, "
    SELECT * FROM booking
    WHERE tanggal_booking = '$tanggal'
    AND status_booking != 'Batal'
    AND (
        ('$jam_mulai' BETWEEN jam_mulai AND jam_selesai)
        OR ('$jam_selesai' BETWEEN jam_mulai AND jam_selesai)
        OR (jam_mulai BETWEEN '$jam_mulai' AND '$jam_selesai')
    )
");

if (mysqli_num_rows($cekBentrok) > 0) {
    echo "<script>alert('Jadwal sudah terisi. Silakan pilih jam lain.'); window.location='booking.php';</script>";
    exit;
}

$insertBooking = mysqli_query($conn, "
    INSERT INTO booking (id_user, tanggal_booking, jam_mulai, jam_selesai, status_booking)
    VALUES ('$id_user', '$tanggal', '$jam_mulai', '$jam_selesai', 'Pending')
");

if ($insertBooking) {
    $id_booking = mysqli_insert_id($conn);

    foreach ($layanan as $id_layanan) {
        $id_layanan = intval($id_layanan);
        mysqli_query($conn, "
            INSERT INTO booking_detail (id_booking, id_layanan)
            VALUES ('$id_booking', '$id_layanan')
        ");
    }

    echo "<script>alert('Booking berhasil dibuat'); window.location='riwayat_booking.php';</script>";
    exit;
}

echo "<script>alert('Booking gagal'); window.location='booking.php';</script>";
exit;
?>