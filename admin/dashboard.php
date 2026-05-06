<?php
require_once '../includes/admin_check.php';

$page_title = "Dashboard Admin";
include '../includes/header.php';

$total_user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM user"))['total'];
$total_booking = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM booking"))['total'];
$total_layanan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM layanan"))['total'];
$total_stok = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM stok_barang"))['total'];
?>

<div class="admin-wrapper">
    <?php include '../includes/sidebar.php'; ?>

    <main class="admin-content">
        <h1>Dashboard</h1>
        <p>Selamat datang, <?= htmlspecialchars($_SESSION['nama']); ?></p>

        <div class="dashboard-cards">
            <div class="dashboard-card">
                <h3>Data User</h3>
                <p><?= $total_user; ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Booking</h3>
                <p><?= $total_booking; ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Layanan</h3>
                <p><?= $total_layanan; ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Stok Barang</h3>
                <p><?= $total_stok; ?></p>
            </div>
        </div>
    </main>
</div>

<?php include '../includes/footer.php'; ?>