<?php
require_once '../config/koneksi.php';
$page_title = "Login - Mey Salon";
include '../includes/header.php';
?>
<!-- MINIMAL KASIH KOMEN NGENTOT KALAU STAGING RUNGKAD BANGET DH LOGIKA SISTEMNYA  -->
<div class="auth-page">
    <div class="auth-card">
        <h1>Mey Salon</h1>
        <div class="auth-image">foto mey salon</div>

        <h2>Login</h2>

        <?php if (isset($_GET['error'])) : ?>
            <div class="alert-error"><?= htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST">
            <input type="text" name="username" placeholder="Username / Nomor HP" required>
            <input type="password" name="password" placeholder="Kata sandi" required>

            <button type="submit" class="btn-primary full">Masuk / Login</button>
        </form>

        <a href="#">Lupa kata sandi?</a>
        <p>Tidak punya akun? <a href="register.php">Daftar</a></p>
        <a href="../index.php">Back</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>