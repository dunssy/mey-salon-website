<?php
// Memastikan koneksi database tersedia
if (!isset($koneksi)) {
    include_once "../config/app.php";
}

// Menggunakan koneksi database
global $koneksi;

// Mengambil data admin login
$admin_nama = $_SESSION['nama'] ?? 'Admin Mey';
$admin_role = $_SESSION['role'] ?? 'Owner';
$admin_inisial = strtoupper(substr($admin_nama, 0, 1));

// Mengambil data admin dari database jika session tersedia
if (isset($_SESSION['id_user']) && isset($koneksi)) {
    $id_user_login = (int) $_SESSION['id_user'];

    $query_admin = mysqli_query(
        $koneksi,
        "SELECT nama, role FROM user WHERE id_user = $id_user_login LIMIT 1"
    );

    if ($query_admin && mysqli_num_rows($query_admin) > 0) {
        $admin = mysqli_fetch_assoc($query_admin);
        $admin_nama = $admin['nama'] ?? $admin_nama;
        $admin_role = $admin['role'] ?? $admin_role;
        $admin_inisial = strtoupper(substr($admin_nama, 0, 1));
    }
}

// Menghitung booking yang butuh respon
$query_notif_booking_count = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM booking WHERE status_booking IN ('Waiting', 'Pending')"
);

$total_notif_booking = $query_notif_booking_count
    ? (int) (mysqli_fetch_assoc($query_notif_booking_count)['total'] ?? 0)
    : 0;

// Menghitung stok barang yang menipis
$query_notif_stok_count = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) AS total FROM stok_barang WHERE jumlah_barang <= minimal_stok"
);

$total_notif_stok = $query_notif_stok_count
    ? (int) (mysqli_fetch_assoc($query_notif_stok_count)['total'] ?? 0)
    : 0;

// Menghitung total notifikasi
$total_notifikasi = $total_notif_booking + $total_notif_stok;

// Mengambil booking terbaru yang butuh respon
$query_notif_booking = mysqli_query(
    $koneksi,
    "SELECT 
        b.id_booking,
        b.tanggal_booking,
        b.jam_mulai,
        b.status_booking,
        u.nama,
        GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan
     FROM booking b
     JOIN user u ON b.id_user = u.id_user
     LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
     LEFT JOIN layanan l ON bd.id_layanan = l.id_layanan
     WHERE b.status_booking IN ('Waiting', 'Pending')
     GROUP BY b.id_booking
     ORDER BY b.tanggal_booking ASC, b.jam_mulai ASC
     LIMIT 5"
);

// Mengambil stok menipis terbaru
$query_notif_stok = mysqli_query(
    $koneksi,
    "SELECT id_barang, nama_barang, jumlah_barang, minimal_stok, satuan_barang
     FROM stok_barang
     WHERE jumlah_barang <= minimal_stok
     ORDER BY jumlah_barang ASC
     LIMIT 5"
);
?>

<!-- Header utama admin -->
<header class="bg-white/80 backdrop-blur-md border-b border-pink-100 px-4 md:px-8 py-3 flex justify-between items-center sticky top-0 z-30">

    <!-- Bagian kiri navbar -->
    <div class="flex items-center gap-4">

        <!-- Tombol buka sidebar mobile -->
        <button onclick="toggleSidebarMobile()" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl text-gray-500 hover:bg-pink-50 hover:text-pink-600 transition" type="button">
            <i class="fa-solid fa-bars-staggered text-xl"></i>
        </button>

        <!-- Tombol kecilkan sidebar desktop -->
        <button onclick="toggleSidebarDesktop()" class="hidden md:flex w-10 h-10 items-center justify-center rounded-xl text-gray-500 hover:bg-pink-50 hover:text-pink-600 transition" type="button">
            <i id="navbar-sidebar-icon" class="fa-solid fa-bars-staggered text-xl"></i>
        </button>

        <!-- Judul halaman -->
        <div>
            <h2 id="page-title" class="text-lg md:text-xl font-bold text-gray-800">
                <?= htmlspecialchars($page_title ?? 'Admin'); ?>
            </h2>

            <p id="current-time" class="hidden sm:block text-[10px] uppercase tracking-wider text-pink-500 font-bold"></p>
        </div>
    </div>

    <!-- Bagian kanan navbar -->
    <div class="flex items-center space-x-2 md:space-x-4">

        <!-- Dropdown notifikasi -->
        <div class="relative navbar-dropdown">

            <!-- Tombol notifikasi -->
            <button onclick="toggleDropdown('notif-dropdown')" class="p-2 text-gray-400 hover:text-pink-600 transition-colors relative" type="button">
                <i class="fa-solid fa-bell text-xl"></i>

                <?php if ($total_notifikasi > 0) : ?>
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full border-2 border-white flex items-center justify-center">
                        <?= $total_notifikasi > 9 ? '9+' : $total_notifikasi; ?>
                    </span>
                <?php endif; ?>
            </button>

            <!-- Isi dropdown notifikasi -->
            <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-80 md:w-96 bg-white rounded-2xl shadow-xl border border-pink-50 overflow-hidden z-50">

                <!-- Header notifikasi -->
                <div class="p-4 border-b border-pink-50 flex justify-between items-center">
                    <div>
                        <h5 class="font-bold text-gray-800">Notifikasi</h5>
                        <p class="text-[11px] text-gray-400 mt-0.5">Booking dan stok barang yang butuh perhatian.</p>
                    </div>

                    <span class="text-[10px] bg-pink-100 text-pink-600 px-2 py-0.5 rounded-full font-bold">
                        <?= (int) $total_notifikasi; ?> Baru
                    </span>
                </div>

                <!-- List notifikasi -->
                <div class="max-h-96 overflow-y-auto">
                    <?php if ($total_notifikasi > 0) : ?>

                        <!-- Notifikasi booking -->
                        <?php if ($query_notif_booking && mysqli_num_rows($query_notif_booking) > 0) : ?>
                            <?php while ($booking_notif = mysqli_fetch_assoc($query_notif_booking)) : ?>
                                <a href="detail-booking.php?id_booking=<?= (int) $booking_notif['id_booking']; ?>" class="block p-4 hover:bg-pink-50/50 border-b border-pink-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 bg-yellow-50 text-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-calendar-check"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="text-sm font-bold text-gray-700">Booking <?= htmlspecialchars($booking_notif['status_booking']); ?></p>

                                                <span class="text-[9px] px-2 py-0.5 rounded-full font-bold <?= $booking_notif['status_booking'] == 'Waiting' ? 'bg-yellow-100 text-yellow-700' : 'bg-orange-100 text-orange-700'; ?>">
                                                    <?= htmlspecialchars($booking_notif['status_booking']); ?>
                                                </span>
                                            </div>

                                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                                <?= htmlspecialchars($booking_notif['nama']); ?> booking <?= htmlspecialchars($booking_notif['nama_layanan'] ?: 'layanan salon'); ?> pukul <?= substr($booking_notif['jam_mulai'], 0, 5); ?>.
                                            </p>

                                            <p class="text-[10px] text-pink-400 mt-1">
                                                <?= date('d M Y', strtotime($booking_notif['tanggal_booking'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        <?php endif; ?>

                        <!-- Notifikasi stok -->
                        <?php if ($query_notif_stok && mysqli_num_rows($query_notif_stok) > 0) : ?>
                            <?php while ($stok_notif = mysqli_fetch_assoc($query_notif_stok)) : ?>
                                <a href="restok.php?id_barang=<?= (int) $stok_notif['id_barang']; ?>" class="block p-4 hover:bg-pink-50/50 border-b border-pink-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="w-9 h-9 bg-red-50 text-red-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <i class="fa-solid fa-box-open"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-700">Stok Menipis</p>

                                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                                <?= htmlspecialchars($stok_notif['nama_barang']); ?> tersisa <b><?= htmlspecialchars($stok_notif['jumlah_barang']); ?> <?= htmlspecialchars($stok_notif['satuan_barang']); ?></b>.
                                            </p>

                                            <p class="text-[10px] text-red-400 mt-1">
                                                Minimal stok: <?= htmlspecialchars($stok_notif['minimal_stok']); ?> <?= htmlspecialchars($stok_notif['satuan_barang']); ?>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        <?php endif; ?>

                    <?php else : ?>

                        <!-- Pesan tidak ada notifikasi -->
                        <div class="p-8 text-center">
                            <div class="w-14 h-14 bg-pink-50 text-pink-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <i class="fa-solid fa-bell-slash text-2xl"></i>
                            </div>

                            <p class="text-sm font-bold text-gray-600">Tidak ada notifikasi</p>
                            <p class="text-xs text-gray-400 mt-1">Semua booking dan stok aman.</p>
                        </div>

                    <?php endif; ?>
                </div>

                <!-- Tombol semua notifikasi -->
                <div class="grid grid-cols-2 border-t border-pink-50">
                    <a href="data-booking.php?tampil=semua&status=Waiting" class="py-3 bg-pink-50 text-pink-600 text-xs font-bold hover:bg-pink-100 text-center">Cek Booking</a>
                    <a href="data-stok.php" class="py-3 bg-red-50 text-red-600 text-xs font-bold hover:bg-red-100 text-center">Cek Stok</a>
                </div>
            </div>
        </div>

        <!-- Garis pemisah -->
        <div class="h-8 w-[1px] bg-gray-200"></div>

        <!-- Dropdown profile -->
        <div class="relative navbar-dropdown">

            <!-- Tombol profile -->
            <button onclick="toggleDropdown('profile-dropdown')" class="flex items-center space-x-2 md:space-x-3 cursor-pointer group hover:bg-pink-50 p-1 rounded-xl transition-all" type="button">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-gray-700 group-hover:text-pink-600 transition-colors"><?= htmlspecialchars($admin_nama); ?></p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase"><?= htmlspecialchars($admin_role); ?></p>
                </div>

                <div class="w-10 h-10 rounded-full bg-pink-600 border-2 border-pink-200 flex items-center justify-center text-white font-bold shadow-md">
                    <?= htmlspecialchars($admin_inisial); ?>
                </div>

                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-pink-600"></i>
            </button>

            <!-- Isi dropdown profile -->
            <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-pink-50 overflow-hidden z-50">

                <!-- Info profile mobile -->
                <div class="p-4 bg-pink-50/50 border-b border-pink-50 sm:hidden">
                    <p class="text-sm font-bold text-gray-700"><?= htmlspecialchars($admin_nama); ?></p>
                    <p class="text-[10px] text-gray-400 uppercase"><?= htmlspecialchars($admin_role); ?></p>
                </div>

                <!-- Menu profile -->
                <div class="py-2">
                    <a href="pengaturan-profil.php" class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors">
                        <i class="fa-solid fa-user-gear w-5"></i>
                        <span>Pengaturan Profil</span>
                    </a>

                    <div class="border-t border-pink-50 mt-2">
                        <a href="../logout.php" class="flex items-center space-x-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-colors font-semibold">
                            <i class="fa-solid fa-right-from-bracket w-5"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Toast notifikasi -->
<div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-8 transform translate-y-32 opacity-0 transition-all duration-500 z-[100]">
    <div class="bg-gray-900/90 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-4 border border-gray-700 min-w-[280px]">
        <div class="p-2 bg-pink-600 rounded-lg">
            <i class="fa-solid fa-circle-check"></i>
        </div>

        <p class="text-xs font-semibold" id="toast-message"></p>
    </div>
</div>

<!-- Script navbar -->
<script>
    // Membuka dan menutup dropdown navbar
    function toggleDropdown(id) {
        const dropdowns = ['notif-dropdown', 'profile-dropdown'];

        dropdowns.forEach(dropdownId => {
            const dropdown = document.getElementById(dropdownId);

            if (!dropdown) return;

            if (dropdownId === id) {
                dropdown.classList.toggle('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        });
    }

    // Menutup dropdown saat klik di luar area navbar
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.navbar-dropdown')) {
            document.getElementById('notif-dropdown')?.classList.add('hidden');
            document.getElementById('profile-dropdown')?.classList.add('hidden');
        }
    });

    // Menampilkan tanggal hari ini
    function updateClock() {
        const currentTime = document.getElementById('current-time');
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };

        if (currentTime) {
            currentTime.innerText = now.toLocaleDateString('id-ID', options);
        }
    }

    // Menjalankan tanggal real-time
    setInterval(updateClock, 1000);
    updateClock();

    // Menampilkan toast pesan
    function showMessage(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        if (!toast || !toastMessage) return;

        toastMessage.innerText = message;
        toast.classList.remove('translate-y-32', 'opacity-0');

        setTimeout(() => {
            toast.classList.add('translate-y-32', 'opacity-0');
        }, 3000);
    }
</script>
