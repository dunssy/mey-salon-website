<!-- Sidebar utama admin -->
<aside 
    id="sidebar" 
    class="fixed inset-y-0 left-0 z-50 w-64 md:w-64 bg-white border-r border-pink-100 flex flex-col transform -translate-x-full md:relative md:translate-x-0 transition-all duration-300 ease-in-out"
>

    <!-- Header sidebar -->
    <div class="p-6 flex items-center border-b border-pink-50">

        <!-- Logo dan nama sistem -->
        <div id="sidebar-brand">
            <h1 class="text-2xl font-bold text-pink-600 tracking-tight italic whitespace-nowrap">
                Mey Salon
            </h1>
            <p class="text-xs text-pink-400 font-medium uppercase tracking-tighter whitespace-nowrap">
                Management System
            </p>
        </div>
    </div>

    <!-- Menu navigasi sidebar -->
    <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto">

        <!-- Menu dashboard -->
        <a 
            href="dashboard-admin.php" 
            id="nav-dashboard" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all"
        >
            <i class="fa-solid fa-house-chimney w-6 text-lg text-center"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- Menu booking -->
        <a 
            href="data-booking.php" 
            id="nav-booking" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all"
        >
            <i class="fa-solid fa-calendar-check w-6 text-lg text-center"></i>
            <span class="sidebar-text">Booking</span>
        </a>

        <!-- Menu stok barang -->
        <a 
            href="data-stok.php" 
            id="nav-stok" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all"
        >
            <i class="fa-solid fa-boxes-stacked w-6 text-lg text-center"></i>
            <span class="sidebar-text">Stok Barang</span>
        </a>

        <!-- Menu layanan -->
        <a 
            href="data-layanan.php" 
            id="nav-layanan" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all"
        >
            <i class="fa-solid fa-scissors w-6 text-lg text-center"></i>
            <span class="sidebar-text">Layanan</span>
        </a>

        <!-- Menu pelanggan -->
        <a 
            href="data-user.php" 
            id="nav-pelanggan" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all"
        >
            <i class="fa-solid fa-users w-6 text-lg text-center"></i>
            <span class="sidebar-text">Pelanggan</span>
        </a>
   
        <!-- Menu laporan -->
        <a 
            href="data-laporan.php" 
            id="nav-laporan" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all"
        >
            <i class="fa-solid fa-chart-line w-6 text-lg text-center"></i>
            <span class="sidebar-text">Laporan</span>
        </a>
    </nav>

    <!-- Area logout sidebar -->
    <div class="p-4 border-t border-pink-100">

        <!-- Tombol keluar sistem -->
        <a 
            href="../logout.php" 
            class="sidebar-link w-full flex items-center gap-3 p-3 text-sm text-red-500 font-semibold hover:bg-red-50 rounded-xl transition-all"
        >
            <i class="fa-solid fa-right-from-bracket w-6 text-lg text-center"></i>
            <span class="sidebar-text">Keluar Sistem</span>
        </a>
    </div>
</aside>

<!-- Overlay sidebar mobile -->
<div 
    id="sidebar-overlay" 
    onclick="toggleSidebarMobile()" 
    class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 hidden md:hidden"
></div>

<script>
    // Membuka dan menutup sidebar di mobile
    function toggleSidebarMobile() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Mengecilkan dan membesarkan sidebar di desktop
    function toggleSidebarDesktop() {
        const sidebar = document.getElementById('sidebar');
        const brand = document.getElementById('sidebar-brand');
        const texts = document.querySelectorAll('.sidebar-text');
        const links = document.querySelectorAll('.sidebar-link');
        const navbarIcon = document.getElementById('navbar-sidebar-icon');

        sidebar.classList.toggle('md:w-64');
        sidebar.classList.toggle('md:w-20');

        brand.classList.toggle('md:hidden');

        texts.forEach(text => {
            text.classList.toggle('md:hidden');
        });

        links.forEach(link => {
            link.classList.toggle('md:justify-center');
            link.classList.toggle('md:px-2');
        });

        if (navbarIcon) {
            navbarIcon.classList.toggle('fa-bars-staggered');
            navbarIcon.classList.toggle('fa-bars');
        }
    }
</script>