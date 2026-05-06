<?php
require_once '../../includes/admin_check.php';

$page_title = "Stok Barang";
include '../../includes/header.php';

$query = mysqli_query($conn, "SELECT * FROM stok_barang ORDER BY nama_barang ASC");
?>

<div class="admin-wrapper">
    <?php include '../../includes/sidebar.php'; ?>

    <main class="admin-content">
        <h1>Stok Barang</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                    <th>Minimal Stok</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                        <td><?= htmlspecialchars($row['jenis_barang']); ?></td>
                        <td><?= $row['jumlah_barang']; ?></td>
                        <td><?= htmlspecialchars($row['satuan']); ?></td>
                        <td><?= $row['minimal_stok']; ?></td>
                        <td>
                            <?php if ($row['jumlah_barang'] <= $row['minimal_stok']) : ?>
                                <span class="badge danger">Stok Menipis</span>
                            <?php else : ?>
                                <span class="badge success">Aman</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>