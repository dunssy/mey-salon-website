<nav class="navbar">
    <div class="nav-logo">Mey Salon</div>

    <ul class="nav-menu">
        <li><a href="/index.php">Home</a></li>
        <li><a href="/index.php#about">About</a></li>
        <li><a href="/index.php#product">Product</a></li>
        <li><a href="/index.php#contact">Contact</a></li>

        <?php if (isset($_SESSION['id_user'])) : ?>
            <?php if ($_SESSION['role'] == 'Admin') : ?>
                <li><a href="/admin/dashboard.php">Dashboard</a></li>
            <?php else : ?>
                <li><a href="/user/booking.php">Booking</a></li>
            <?php endif; ?>
            <li><a href="/auth/logout.php">Logout</a></li>
        <?php else : ?>
            <li><a href="/auth/register.php">Sign Up</a></li>
            <li><a href="/auth/login.php">Log In</a></li>
        <?php endif; ?>
    </ul>
</nav>