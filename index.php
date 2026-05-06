<?php
require_once 'config/koneksi.php';
$page_title = "Mey Salon";
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="hero">
    <div class="hero-content">
        <h1>MEY SALON</h1>
        <p>Salon pria dan wanita dengan layanan perawatan rambut, kecantikan, dan booking online.</p>
        <a href="user/booking.php" class="btn-primary">BOOKING NOW</a>
    </div>
</section>

<section id="about" class="section">
    <h2>About Us</h2>
    <p>
        Mey Salon adalah layanan salon yang menyediakan potong rambut, smoothing, creambath,
        hair mask, facial, nail art, dan layanan kecantikan lainnya.
    </p>
</section>

<section id="product" class="section product-grid">
    <h2>Product & Layanan</h2>

    <?php
    $layanan = mysqli_query($koneksi, "SELECT * FROM layanan ORDER BY id_layanan DESC LIMIT 6");
    while ($row = mysqli_fetch_assoc($layanan)) :
    ?>
        <div class="product-card">
            <div class="product-img">Foto</div>
            <h3><?= htmlspecialchars($row['nama_layanan']); ?></h3>
            <p>Rp<?= number_format($row['harga_layanan'], 0, ',', '.'); ?></p>
            <p><?= $row['durasi_layanan']; ?> menit</p>
        </div>
    <?php endwhile; ?>
</section>

<section id="contact" class="section">
    <h2>Contact</h2>
    <p>Jl. Kertawigwanda, Gg. Palabuan, Subang</p>
    <p>WhatsApp: 08xxxxxxxxxx</p>
</section>

<?php include 'includes/footer.php'; ?>