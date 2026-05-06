<?php
require_once '../../includes/admin_check.php';

$page_title = "Data Booking";
include '../../includes/header.php';

$query = mysqli_query($koneksi, "
    SELECT booking.*, user.nama, user.no_hp
    FROM booking
    JOIN user ON booking.id_user = user.id_user
    ORDER BY booking.tanggal_booking DESC
");
?>

<div class="admin-wrapper">
    <?php include '../../includes/sidebar.php'; ?>

    <main class="admin-content">
        <h1>Data Booking</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>No HP</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['no_hp']); ?></td>
                        <td><?= $row['tanggal_booking']; ?></td>
                        <td><?= $row['jam_mulai']; ?> - <?= $row['jam_selesai']; ?></td>
                        <td><?= $row['status_booking']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>