<?php
require_once '../../includes/admin_check.php';

$page_title = "Laporan Keuangan";
include '../../includes/header.php';

$dari = isset($_GET['dari']) ? $_GET['dari'] : date('Y-m-01');
$sampai = isset($_GET['sampai']) ? $_GET['sampai'] : date('Y-m-d');

$query = mysqli_query($koneksi, "
    SELECT
        pemasukan.total_pemasukan,
        pengeluaran.total_pengeluaran,
        restok.total_restok,
        (
            pemasukan.total_pemasukan
            - pengeluaran.total_pengeluaran
            - restok.total_restok
        ) AS laba_bersih
    FROM
        (
            SELECT COALESCE(SUM(total_bayar), 0) AS total_pemasukan
            FROM transaksi
            WHERE status_bayar = 'Selesai'
            AND DATE(tanggal_transaksi) BETWEEN '$dari' AND '$sampai'
        ) pemasukan,
        (
            SELECT COALESCE(SUM(jumlah_pengeluaran), 0) AS total_pengeluaran
            FROM pengeluaran
            WHERE DATE(tanggal_pengeluaran) BETWEEN '$dari' AND '$sampai'
        ) pengeluaran,
        (
            SELECT COALESCE(SUM(total_harga_restock), 0) AS total_restok
            FROM restok
            WHERE DATE(tanggal_restock) BETWEEN '$dari' AND '$sampai'
        ) restok
");

$data = mysqli_fetch_assoc($query);
?>

<div class="admin-wrapper">
    <?php include '../../includes/sidebar.php'; ?>

    <main class="admin-content">
        <h1>Laporan Keuangan</h1>

        <form method="GET" class="filter-form">
            <label>Dari</label>
            <input type="date" name="dari" value="<?= $dari; ?>">

            <label>Sampai</label>
            <input type="date" name="sampai" value="<?= $sampai; ?>">

            <button type="submit" class="btn-primary">Tampilkan</button>
            <button type="button" onclick="window.print()" class="btn-secondary">Cetak</button>
        </form>

        <div class="dashboard-cards">
            <div class="dashboard-card">
                <h3>Total Pemasukan</h3>
                <p>Rp<?= number_format($data['total_pemasukan'], 0, ',', '.'); ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Pengeluaran</h3>
                <p>Rp<?= number_format($data['total_pengeluaran'], 0, ',', '.'); ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Restok</h3>
                <p>Rp<?= number_format($data['total_restok'], 0, ',', '.'); ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Laba Bersih</h3>
                <p>Rp<?= number_format($data['laba_bersih'], 0, ',', '.'); ?></p>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>