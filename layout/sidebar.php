<!-- Sidebar utama admin -->
<aside 
    id="sidebar" 
    class="fixed inset-y-0 left-0 z-50 w-64 md:w-64 bg-[#FFF7FA] border-r border-[#F7D6E4] flex flex-col transform -translate-x-full md:relative md:translate-x-0 transition-all duration-300 ease-in-out shadow-xl md:shadow-none"
>

    <!-- Header sidebar -->
    <div class="p-6 flex items-center border-b border-[#F7D6E4]">

        <!-- Logo dan nama sistem -->
        <div id="sidebar-brand">
            <h1 class="text-2xl font-bold text-[#C75C7A] tracking-tight italic whitespace-nowrap">
                Mey Salon
            </h1>

            <p class="text-xs text-[#B77B8E] font-medium uppercase tracking-tighter whitespace-nowrap">
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
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-[#6F5E64] hover:bg-[#FDEAF1] hover:text-[#C75C7A] rounded-xl transition-all"
        >
            <i class="fa-solid fa-house-chimney w-6 text-lg text-center"></i>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <!-- Menu booking -->
        <a 
            href="data-booking.php" 
            id="nav-booking" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-[#6F5E64] hover:bg-[#FDEAF1] hover:text-[#C75C7A] rounded-xl transition-all"
        >
            <i class="fa-solid fa-calendar-check w-6 text-lg text-center"></i>
            <span class="sidebar-text">Booking</span>
        </a>

        <!-- Menu stok barang -->
        <a 
            href="data-stok.php" 
            id="nav-stok" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-[#6F5E64] hover:bg-[#FDEAF1] hover:text-[#C75C7A] rounded-xl transition-all"
        >
            <i class="fa-solid fa-boxes-stacked w-6 text-lg text-center"></i>
            <span class="sidebar-text">Stok Barang</span>
        </a>

        <!-- Menu layanan -->
        <a 
            href="data-layanan.php" 
            id="nav-layanan" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-[#6F5E64] hover:bg-[#FDEAF1] hover:text-[#C75C7A] rounded-xl transition-all"
        >
            <i class="fa-solid fa-scissors w-6 text-lg text-center"></i>
            <span class="sidebar-text">Layanan</span>
        </a>

        <!-- Menu pelanggan -->
        <a 
            href="data-user.php" 
            id="nav-pelanggan" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-[#6F5E64] hover:bg-[#FDEAF1] hover:text-[#C75C7A] rounded-xl transition-all"
        >
            <i class="fa-solid fa-users w-6 text-lg text-center"></i>
            <span class="sidebar-text">Pelanggan</span>
        </a>

        <!-- Menu laporan -->
        <a 
            href="data-laporan.php" 
            id="nav-laporan" 
            class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium text-[#6F5E64] hover:bg-[#FDEAF1] hover:text-[#C75C7A] rounded-xl transition-all"
        >
            <i class="fa-solid fa-chart-line w-6 text-lg text-center"></i>
            <span class="sidebar-text">Laporan</span>
        </a>
    </nav>

    <!-- Area logout sidebar -->
    <div class="p-4 border-t border-[#F7D6E4]">

        <!-- Tombol keluar sistem -->
        <a 
            href="../logout.php"
            onclick="confirmLogout(event)"
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
    class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 hidden md:hidden"
></div>

<script src="../layout/js/sweetalert.js" languange="javascript"></script>
<script>
    // Membuka dan menutup sidebar di mobile
    function toggleSidebarMobile() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (!sidebar || !overlay) return;

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

        if (!sidebar || !brand) return;

        sidebar.classList.toggle('md:w-64');
        sidebar.classList.toggle('md:w-20');

        brand.classList.toggle('md:hidden');

        texts.forEach(function (text) {
            text.classList.toggle('md:hidden');
        });

        links.forEach(function (link) {
            link.classList.toggle('md:justify-center');
            link.classList.toggle('md:px-2');
        });
        
        if (navbarIcon) {
            navbarIcon.classList.toggle('fa-bars-staggered');
            navbarIcon.classList.toggle('fa-bars');
        }
    }

    // Menandai menu aktif tanpa mengubah struktur menu
    document.addEventListener('DOMContentLoaded', function () {
        const currentPage = window.location.pathname.split('/').pop();

        const menuMap = {
            'dashboard-admin.php': 'nav-dashboard',
            'data-booking.php': 'nav-booking',
            'detail-booking.php': 'nav-booking',
            'data-stok.php': 'nav-stok',
            'tambah-stok.php': 'nav-stok',
            'edit-stok.php': 'nav-stok',
            'restok.php': 'nav-stok',
            'data-layanan.php': 'nav-layanan',
            'tambah-layanan.php': 'nav-layanan',
            'edit-layanan.php': 'nav-layanan',
            'detail-layanan.php': 'nav-layanan',
            'data-user.php': 'nav-pelanggan',
            'data-laporan.php': 'nav-laporan',
            'laporan.php': 'nav-laporan',
            'export-laporan.php': 'nav-laporan'
        };

        const activeId = menuMap[currentPage];
        const activeLink = activeId ? document.getElementById(activeId) : null;

        if (activeLink) {
            activeLink.classList.remove('text-[#6F5E64]');
            activeLink.classList.add('bg-[#EFA9BF]', 'text-white', 'shadow-md', 'shadow-pink-100');
        }
    });
</script>
