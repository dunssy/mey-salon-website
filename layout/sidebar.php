        <!-- Sidebar (Responsive: hidden on mobile by default) -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-pink-100 flex flex-col transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="p-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-pink-600 tracking-tight italic">Mey Salon</h1>
                    <p class="text-xs text-pink-400 font-medium uppercase tracking-tighter">Management System</p>
                </div>
                <button onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-pink-600">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>
            
            <nav class="flex-1 px-4 space-y-1 mt-4 overflow-y-auto">
                <a href="dashboard-admin.php" onclick="switchTab('dashboard')" id="nav-dashboard" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fa-solid fa-house-chimney w-6 text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="data-booking.php" onclick="switchTab('booking.php')" id="nav-booking" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-calendar-check w-6 text-lg"></i>
                    <span>Booking</span>
                </a>
                <!-- data stok barang -->
                <a href="data-stok.php" onclick="switchTab('stok')" id="nav-stok" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-boxes-stacked w-6 text-lg"></i>
                    <span>Stok Barang</span>
                </a>
                
                <a href="data-layanan.php" onclick="switchTab('layanan.php')" id="nav-layanan" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-scissors w-6 text-lg"></i>
                    <span>Layanan</span>
                </a>
                <a href="data-user.php" onclick="switchTab('pelanggan')" id="nav-pelanggan" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-users w-6 text-lg"></i>
                    <span>Pelanggan</span>
                </a>
                <a href="data-laporan.php" onclick="switchTab('laporan')" id="nav-laporan" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-chart-line w-6 text-lg"></i>
                    <span>Laporan</span>
                </a>
            </nav>

            <div class="p-4 border-t border-pink-100">
                <button onclick="showMessage('Anda telah keluar sistem')" class="w-full flex items-center space-x-3 p-3 text-sm text-red-500 font-semibold hover:bg-red-50 rounded-xl transition-all">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                    <span>Keluar Sistem</span>
                </button>
            </div>
        </aside>

        <!-- Overlay for mobile sidebar -->
        <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 hidden md:hidden"></div>
    
    <!-- JAVA SCRIPT UNTUK ANIMASI SIDEBAR -->
    <script>
    // Sidebar Mobile Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Tab Switching Logic
        
        // Switch Tabs Logic
        function switchTab(tabName) {
            const sections = ['dashboard', 'layanan'];
            sections.forEach(s => {
                const el = document.getElementById(`section-${s}`);
                if (el) el.classList.add('hidden');
                
                const nav = document.getElementById(`nav-${s}`);
                if (nav) nav.classList.remove('sidebar-active');
                if (nav) nav.classList.add('text-gray-600');
            });

            const target = document.getElementById(`section-${tabName}`);
            if (target) target.classList.remove('hidden');

            const navTarget = document.getElementById(`nav-${tabName}`);
            if (navTarget) {
                navTarget.classList.add('sidebar-active');
                navTarget.classList.remove('text-gray-600');
            }

            // 
            // Close sidebar on mobile after selection
            if (window.innerWidth < 768) toggleSidebar();
        }
    </script>