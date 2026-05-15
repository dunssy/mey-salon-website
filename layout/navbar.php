 <!-- Top Header -->
            <header class="bg-white/80 backdrop-blur-md border-b border-pink-100 px-4 md:px-8 py-3 flex justify-between items-center sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden p-2 text-gray-500 hover:text-pink-600">
                        <i class="fa-solid fa-bars-staggered text-xl"></i>
                    </button>
                    <div>
                        <h2 id="page-title" class="text-lg md:text-xl font-bold text-gray-800"><?php echo $page_title; ?></h2>
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