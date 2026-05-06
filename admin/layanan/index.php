<?php
require_once '../../includes/admin_check.php';

$page_title = "Data Layanan";
include '../../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id_layanan DESC");
?>

<div class="admin-wrapper">
    <?php include '../../includes/sidebar.php'; ?>

    <main class="admin-content">
        <h1>Data Layanan</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <th>Durasi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_layanan']); ?></td>
                        <td>Rp<?= number_format($row['harga_layanan'], 0, ',', '.'); ?></td>
                        <td><?= $row['durasi_layanan']; ?> menit</td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>