<!-- Navbar user -->
<nav class="fixed w-full z-50 glass-nav border-b border-pink-100">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">

            <!-- Kiri navbar -->
            <div class="flex items-center gap-8">

                <!-- Logo dan brand -->
                <button onclick="showSection('layanan')" class="flex items-center gap-3">
                    <img
                        src="../layout/images/mey-salon.png"
                        alt="Mey Salon Logo"
                        class="w-10 h-10 rounded-xl object-cover"
                    />

                    <span class="text-xl font-bold text-pink-600 italic tracking-tighter">
                        Mey Salon
                    </span>
                </button>

                <!-- Menu desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="../index.php" class="text-sm font-semibold hover:text-pink-600 transition-colors">
                        Home
                    </a>

                    <button onclick="showSection('layanan')" class="text-sm font-semibold hover:text-pink-600 transition-colors">
                        Layanan
                    </button>

                    <button onclick="showSection('booking')" class="text-sm font-semibold hover:text-pink-600 transition-colors">
                        Booking Saya
                    </button>
                </div>
            </div>

            <!-- Kanan navbar -->
            <div class="hidden md:flex items-center">
                <button onclick="showSection('profil')" class="flex items-center gap-2 text-sm font-semibold bg-pink-50 text-pink-600 px-4 py-2 rounded-full hover:bg-pink-100 transition-all">
                    <i class="fa-solid fa-circle-user text-lg"></i>
                    <span>Profil</span>
                </button>
            </div>

            <!-- Mobile navbar -->
            <div class="md:hidden flex items-center gap-3">
                <button onclick="showSection('profil')" class="text-pink-600">
                    <i class="fa-solid fa-circle-user text-2xl"></i>
                </button>

                <button onclick="toggleMobileMenu()" class="text-gray-600">
                    <i id="menu-icon" class="fa-solid fa-bars-staggered text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
            <button onclick="showSection('layanan')" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50">
                Layanan
            </button>

            <button onclick="showSection('booking')" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50">
                Booking Saya
            </button>
        </div>
    </div>
</nav>