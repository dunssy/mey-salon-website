<?php
require_once '../includes/auth_check.php';

$page_title = "Booking - Mey Salon";
include '../includes/header.php';
include '../includes/navbar.php';

$layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY nama_layanan ASC");
?>

<div class="booking-page">
    <h1>Booking Layanan</h1>

    <form action="proses_booking.php" method="POST" class="booking-form">
        <div class="booking-layout">
            <div class="booking-left">
                <h2>Pilih Layanan</h2>

                <?php while ($row = mysqli_fetch_assoc($layanan)) : ?>
                    <label class="checkbox-card">
                        <input
                            type="checkbox"
                            name="layanan[]"
                            value="<?= $row['id_layanan']; ?>"
                            data-harga="<?= $row['harga_layanan']; ?>"
                            data-durasi="<?= $row['durasi_layanan']; ?>"
                        >
                        <span>
                            <?= htmlspecialchars($row['nama_layanan']); ?>
                            <small>
                                Rp<?= number_format($row['harga_layanan'], 0, ',', '.'); ?>
                                - <?= $row['durasi_layanan']; ?> menit
                            </small>
                        </span>
                    </label>
                <?php endwhile; ?>

                <div class="summary-box">
                    <p>Total Harga: <strong id="totalHarga">Rp0</strong></p>
                    <p>Estimasi Waktu: <strong id="totalDurasi">0 menit</strong></p>
                </div>
            </div>

            <div class="booking-right">
                <h2>Tanggal Booking</h2>

                <label>Tanggal</label>
                <input type="date" name="tanggal_booking" required>

                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" required>

                <button type="submit" class="btn-primary full">KONFIRMASI</button>
            </div>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>