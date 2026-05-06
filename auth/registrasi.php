<?php
require_once '../config/koneksi.php';
$page_title = "Registrasi - Mey Salon";
include '../includes/header.php';
?>

<div class="auth-page">
    <div class="auth-card">
        <h1>Mey Salon</h1>
        <h2>Registrasi</h2>

        <?php if (isset($_GET['error'])) : ?>
            <div class="alert-error"><?= htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <form action="proses_register.php" method="POST">
            <input type="text" name="nama" placeholder="Nama" required>
            <input type="text" name="no_hp" placeholder="Nomor HP" required>
            <textarea name="alamat" placeholder="Alamat" required></textarea>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="konfirmasi_password" placeholder="Konfirmasi Password" required>

            <button type="submit" class="btn-primary full">Sign Up</button>
        </form>

        <p>Sudah memiliki akun? <a href="login.php">Login</a></p>
        <a href="../index.php">Back</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>