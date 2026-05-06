<?php
require_once '../includes/auth_check.php';

$page_title = "Riwayat Booking";
include '../includes/header.php';
include '../includes/navbar.php';

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn, "
    SELECT * FROM booking
    WHERE id_user = '$id_user'
    ORDER BY tanggal_booking DESC, jam_mulai DESC
");
?>

<div class="container">
    <h1>Riwayat Booking</h1>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['tanggal_booking']; ?></td>
                    <td><?= $row['jam_mulai']; ?> - <?= $row['jam_selesai']; ?></td>
                    <td><?= $row['status_booking']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>