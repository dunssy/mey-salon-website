<?php 
$page_title = "Dashboard";
include "../layout/header.php";
?>
<body class="text-gray-800 overflow-x-hidden">

    <div class="flex h-screen overflow-hidden">
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
                <a href="#" onclick="switchTab('dashboard')" id="nav-dashboard" class="sidebar-active flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fa-solid fa-house-chimney w-6 text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" onclick="switchTab('booking')" id="nav-booking" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-calendar-check w-6 text-lg"></i>
                    <span>Booking</span>
                </a>
                <a href="#" onclick="switchTab('layanan')" id="nav-layanan" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-scissors w-6 text-lg"></i>
                    <span>Layanan</span>
                </a>
                <a href="#" onclick="switchTab('pelanggan')" id="nav-pelanggan" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
                    <i class="fa-solid fa-users w-6 text-lg"></i>
                    <span>Pelanggan</span>
                </a>
                <a href="#" onclick="switchTab('laporan')" id="nav-laporan" class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 hover:bg-pink-50 hover:text-pink-600 rounded-xl transition-all">
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

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">
            <!-- Top Header -->
            <header class="bg-white/80 backdrop-blur-md border-b border-pink-100 px-4 md:px-8 py-3 flex justify-between items-center sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-500 hover:text-pink-600">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <div>
                        <h2 id="page-title" class="text-lg md:text-xl font-bold text-gray-800">Dashboard</h2>
                        <p id="current-time" class="hidden sm:block text-[10px] uppercase tracking-wider text-pink-500 font-bold"></p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 md:space-x-4">
                    <!-- Notifications Dropdown -->
                    <div class="relative">
                        <button onclick="toggleDropdown('notif-dropdown')" class="p-2 text-gray-400 hover:text-pink-600 transition-colors relative">
                            <i class="fa-solid fa-bell text-xl"></i>
                            <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                        
                        <!-- Dropdown Content -->
                        <div id="notif-dropdown" class="hidden absolute right-0 mt-3 w-72 md:w-80 bg-white rounded-2xl shadow-xl border border-pink-50 overflow-hidden z-50">
                            <div class="p-4 border-b border-pink-50 flex justify-between items-center">
                                <h5 class="font-bold text-gray-800">Notifikasi</h5>
                                <span class="text-[10px] bg-pink-100 text-pink-600 px-2 py-0.5 rounded-full font-bold">3 Baru</span>
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                <a href="#" class="block p-4 hover:bg-pink-50/50 border-b border-pink-50 transition-colors">
                                    <p class="text-sm font-bold text-gray-700">Booking Baru!</p>
                                    <p class="text-xs text-gray-500">Siska Amelia melakukan booking Hair Spa pukul 14:00.</p>
                                    <p class="text-[10px] text-pink-400 mt-1">2 menit yang lalu</p>
                                </a>
                                <a href="#" class="block p-4 hover:bg-pink-50/50 border-b border-pink-50 transition-colors">
                                    <p class="text-sm font-bold text-gray-700">Stok Menipis</p>
                                    <p class="text-xs text-gray-500">Persediaan Vitamin Rambut tersisa 2 botol.</p>
                                    <p class="text-[10px] text-pink-400 mt-1">1 jam yang lalu</p>
                                </a>
                                <a href="#" class="block p-4 hover:bg-pink-50/50 transition-colors">
                                    <p class="text-sm font-bold text-gray-700">Laporan Selesai</p>
                                    <p class="text-xs text-gray-500">Laporan harian kemarin sudah siap diunduh.</p>
                                    <p class="text-[10px] text-pink-400 mt-1">3 jam yang lalu</p>
                                </a>
                            </div>
                            <button class="w-full py-3 bg-pink-50 text-pink-600 text-xs font-bold hover:bg-pink-100">Lihat Semua Notifikasi</button>
                        </div>
                    </div>
                    
                    <div class="h-8 w-[1px] bg-gray-200"></div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <div onclick="toggleDropdown('profile-dropdown')" class="flex items-center space-x-2 md:space-x-3 cursor-pointer group hover:bg-pink-50 p-1 rounded-xl transition-all">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-bold text-gray-700 group-hover:text-pink-600 transition-colors">Admin Mey</p>
                                <p class="text-[10px] text-gray-400 font-medium uppercase">Owner</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-pink-600 border-2 border-pink-200 flex items-center justify-center text-white font-bold shadow-md">
                                M
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-pink-600"></i>
                        </div>

                        <!-- Dropdown Content -->
                        <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-pink-50 overflow-hidden z-50">
                            <div class="p-4 bg-pink-50/50 border-b border-pink-50 sm:hidden">
                                <p class="text-sm font-bold text-gray-700">Admin Mey</p>
                                <p class="text-[10px] text-gray-400 uppercase">Owner</p>
                            </div>
                            <div class="py-2">
                                <a href="#" onclick="showMessage('Membuka Pengaturan Profil')" class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors">
                                    <i class="fa-solid fa-user-gear w-5"></i>
                                    <span>Pengaturan Profil</span>
                                </a>
                                <a href="#" onclick="showMessage('Membuka Pengaturan Salon')" class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors">
                                    <i class="fa-solid fa-gears w-5"></i>
                                    <span>Pengaturan Salon</span>
                                </a>
                                <a href="#" onclick="showMessage('Membuka Bantuan')" class="flex items-center space-x-3 px-4 py-3 text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors">
                                    <i class="fa-solid fa-circle-question w-5"></i>
                                    <span>Pusat Bantuan</span>
                                </a>
                                <div class="border-t border-pink-50 mt-2">
                                    <a href="#" class="flex items-center space-x-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-colors font-semibold">
                                        <i class="fa-solid fa-right-from-bracket w-5"></i>
                                        <span>Keluar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 md:p-8 flex-1">
                <div id="section-dashboard" class="space-y-6 md:space-y-8">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Booking Today</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">24</h3>
                            <div class="mt-2 text-[10px] text-blue-600 font-bold bg-blue-50 inline-block px-2 py-0.5 rounded">Hari Ini</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Pending</p>
                            <h3 class="text-xl md:text-3xl font-bold text-pink-600 mt-1">5</h3>
                            <div class="mt-2 text-[10px] text-pink-600 font-bold bg-pink-50 inline-block px-2 py-0.5 rounded">Butuh Respon</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Revenue</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">2.4jt</h3>
                            <div class="mt-2 text-[10px] text-green-600 font-bold bg-green-50 inline-block px-2 py-0.5 rounded">IDR</div>
                        </div>
                        <div class="glass-card p-4 md:p-6 rounded-2xl shadow-sm border border-white">
                            <p class="text-[10px] md:text-sm font-medium text-gray-500">Customers</p>
                            <h3 class="text-xl md:text-3xl font-bold text-gray-800 mt-1">156</h3>
                            <div class="mt-2 text-[10px] text-purple-600 font-bold bg-purple-50 inline-block px-2 py-0.5 rounded">Loyalty</div>
                        </div>
                    </div>

                    <!-- Table Area -->
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-pink-100">
                        <div class="px-6 py-4 border-b border-pink-50 flex justify-between items-center">
                            <h4 class="font-bold text-gray-700 italic">Antrean Berjalan</h4>
                            <button class="p-2 text-pink-600 hover:bg-pink-50 rounded-lg"><i class="fa-solid fa-rotate"></i></button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[600px]">
                                <thead class="bg-pink-50/30 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Pelanggan</th>
                                        <th class="px-6 py-4">Layanan</th>
                                        <th class="px-6 py-4 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-pink-50">
                                    <tr class="hover:bg-pink-50/20">
                                        <td class="px-6 py-4 font-bold text-pink-600">10:00</td>
                                        <td class="px-6 py-4 font-semibold text-gray-700">Siska Amelia</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Hair Spa</td>
                                        <td class="px-6 py-4 text-center">
                                            <button class="bg-green-100 text-green-600 px-3 py-1 text-[10px] font-bold rounded-lg uppercase">Selesai</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-pink-50/20">
                                        <td class="px-6 py-4 font-bold text-pink-600">11:30</td>
                                        <td class="px-6 py-4 font-semibold text-gray-700">Dewi Kusuma</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">Coloring</td>
                                        <td class="px-6 py-4 text-center">
                                            <button onclick="showMessage('Booking dikonfirmasi')" class="bg-pink-600 text-white px-3 py-1 text-[10px] font-bold rounded-lg hover:shadow-lg">Konfirmasi</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Section Service -->
                <div id="section-layanan" class="hidden">
                    <h3 class="text-lg font-bold mb-6">Manajemen Layanan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-pink-100 shadow-sm hover:border-pink-300 transition-colors">
                            <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center mb-4 text-xl">
                                <i class="fa-solid fa-scissors"></i>
                            </div>
                            <h4 class="font-bold text-gray-800">Hair Cut & Styling</h4>
                            <p class="text-sm text-pink-600 font-bold mt-1">Rp 85.000</p>
                            <button class="mt-4 w-full py-2 text-xs font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors">Edit Layanan</button>
                        </div>
                    </div>
                </div>

                <!-- Pelanggan Section -->
                <div id="section-pelanggan" class="hidden">
                    <h3 class="text-lg font-bold mb-6 text-gray-700">Database Pelanggan</h3>
                    <div class="bg-white rounded-2xl border border-pink-100 p-12 text-center">
                        <div class="w-16 h-16 bg-pink-50 text-pink-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-users text-2xl"></i>
                        </div>
                        <p class="text-gray-400 italic text-sm">Menghubungkan ke database pelanggan...</p>
                    </div>
                </div>
            </div>

            <!-- Footer Informatif -->
            <footer class="bg-white border-t border-pink-100 px-4 md:px-8 py-8 mt-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center md:text-left">
                        <h5 class="text-pink-600 font-bold italic text-lg">Mey Salon System</h5>
                        <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">
                            Aplikasi manajemen salon tercanggih untuk memudahkan reservasi, 
                            pelaporan, dan peningkatan layanan kecantikan Anda.
                        </p>
                    </div>
                    
                    <div class="flex justify-center md:justify-start space-x-12">
                        <div>
                            <h6 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Links</h6>
                            <ul class="text-[11px] space-y-2 text-gray-600 font-medium">
                                <li><a href="#" class="hover:text-pink-600">Panduan</a></li>
                                <li><a href="#" class="hover:text-pink-600">Dukungan</a></li>
                                <li><a href="#" class="hover:text-pink-600">API Docs</a></li>
                            </ul>
                        </div>
                        <div>
                            <h6 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Kontak</h6>
                            <ul class="text-[11px] space-y-2 text-gray-600 font-medium">
                                <li><i class="fa-solid fa-envelope mr-1 opacity-50"></i> help@meysalon.com</li>
                                <li><i class="fa-solid fa-phone mr-1 opacity-50"></i> +62 812-XXXX</li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center md:text-right border-t md:border-t-0 pt-6 md:pt-0 border-pink-50">
                        <p class="text-[11px] text-gray-400">&copy; 2024 Mey Salon Dashboard.</p>
                        <div class="flex justify-center md:justify-end gap-3 mt-3 opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                            <i class="fa-brands fa-cc-visa text-xl"></i>
                            <i class="fa-brands fa-cc-mastercard text-xl"></i>
                            <i class="fa-solid fa-shield-halved text-xl"></i>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-8 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:right-8 transform translate-y-32 opacity-0 transition-all duration-500 z-[100]">
        <div class="bg-gray-900/90 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-4 border border-gray-700 min-w-[280px]">
            <div class="p-2 bg-pink-600 rounded-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <p class="text-xs font-semibold" id="toast-message"></p>
        </div>
    </div>
<script>
        // Sidebar Mobile Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Dropdown Toggle Logic
        function toggleDropdown(id) {
            const dropdowns = ['notif-dropdown', 'profile-dropdown'];
            dropdowns.forEach(d => {
                const el = document.getElementById(d);
                if (d === id) {
                    el.classList.toggle('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }

        // Close dropdowns on outside click
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.getElementById('notif-dropdown').classList.add('hidden');
                document.getElementById('profile-dropdown').classList.add('hidden');
            }
        });

        // Update Waktu Real-time
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('current-time').innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Switch Tabs Logic
        function switchTab(tabName) {
            const sections = ['dashboard', 'layanan', 'pelanggan'];
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

            const titleMap = {
                'dashboard': 'Dashboard',
                'layanan': 'Services',
                'pelanggan': 'Customers',
                'booking': 'Reservations',
                'laporan': 'Reports'
            };
            document.getElementById('page-title').innerText = titleMap[tabName] || 'Admin';
            
            // Close sidebar on mobile after selection
            if (window.innerWidth < 768) toggleSidebar();
        }

        // Notification Function
        function showMessage(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = msg;
            
            toast.classList.remove('translate-y-32', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-32', 'opacity-0');
            }, 3000);
        }
    </script>
<?php
include "layout/footer.php";
?>