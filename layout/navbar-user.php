<?php
// Mengambil data user jika tersedia
global $user;

// Menyiapkan nama user
$nama_user = $user['nama'] ?? ($_SESSION['nama'] ?? 'User');

// Menyiapkan role user
$role_user = $user['role'] ?? ($_SESSION['role'] ?? 'Customer');

// Membuat inisial user
$inisial_user = strtoupper(substr(trim($nama_user), 0, 1));
?>

<!-- Navbar user -->
<nav class="fixed w-full z-50 glass-nav border-b border-pink-100">

    <!-- Container navbar -->
    <div class="max-w-6xl mx-auto px-3 sm:px-4">

        <!-- Isi navbar -->
        <div class="flex justify-between items-center h-16">

            <!-- Kiri navbar -->
            <div class="flex items-center gap-4 md:gap-8 min-w-0">

                <!-- Logo dan brand -->
                <button 
                    type="button"
                    onclick="showSectionAndCloseMenu('layanan')" 
                    class="flex items-center gap-3 min-w-0"
                >
                    <img
                        src="../layout/images/mey-salon.png"
                        alt="Mey Salon Logo"
                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl object-cover shrink-0"
                    >

                    <span class="text-lg sm:text-xl font-bold text-pink-600 italic tracking-tighter truncate">
                        Mey Salon
                    </span>
                </button>

                <!-- Menu desktop -->
                <div class="hidden md:flex items-center gap-6">
                    <a 
                        href="../index.php" 
                        class="text-sm font-semibold hover:text-pink-600 transition-colors"
                    >
                        Home
                    </a>

                    <button 
                        type="button"
                        onclick="showSectionAndCloseMenu('layanan')" 
                        class="text-sm font-semibold hover:text-pink-600 transition-colors"
                    >
                        Layanan
                    </button>

                    <button 
                        type="button"
                        onclick="showSectionAndCloseMenu('booking')" 
                        class="text-sm font-semibold hover:text-pink-600 transition-colors"
                    >
                        Booking Saya
                    </button>
                </div>
            </div>

            <!-- Kanan navbar desktop -->
            <div class="hidden md:flex items-center">

                <!-- Dropdown profile desktop -->
                <div class="relative user-profile-dropdown">

                    <!-- Tombol profile -->
                    <button 
                        type="button"
                        onclick="toggleUserProfileDropdown()"
                        class="flex items-center gap-3 bg-pink-50 text-pink-600 px-4 py-2 rounded-full hover:bg-pink-100 transition-all"
                    >
                        <div class="w-8 h-8 rounded-full bg-pink-600 text-white flex items-center justify-center text-sm font-bold">
                            <?= htmlspecialchars($inisial_user); ?>
                        </div>

                        <div class="text-left leading-tight">
                            <p class="text-sm font-bold max-w-[130px] truncate">
                                <?= htmlspecialchars($nama_user); ?>
                            </p>

                            <p class="text-[10px] uppercase text-pink-400 font-bold">
                                <?= htmlspecialchars($role_user); ?>
                            </p>
                        </div>

                        <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </button>

                    <!-- Isi dropdown profile -->
                    <div 
                        id="user-profile-menu" 
                        class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-pink-100 overflow-hidden z-50"
                    >
                        <!-- Info profile -->
                        <div class="p-4 bg-pink-50/50 border-b border-pink-100">
                            <p class="text-sm font-bold text-gray-700 truncate">
                                <?= htmlspecialchars($nama_user); ?>
                            </p>

                            <p class="text-[10px] text-gray-400 uppercase font-bold">
                                <?= htmlspecialchars($role_user); ?>
                            </p>
                        </div>

                        <!-- Menu pengaturan profile -->
                        <button
                            type="button"
                            onclick="showSectionAndCloseMenu('profil'); closeUserProfileDropdown();"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition text-left"
                        >
                            <i class="fa-solid fa-user-gear w-5"></i>
                            <span>Pengaturan Profil</span>
                        </button>

                        <!-- Menu logout -->
                        <div class="border-t border-pink-50">
                            <a href="../logout.php" onclick="confirmLogout(event)" class="flex items-center space-x-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition-colors font-semibold">
                                <i class="fa-solid fa-right-from-bracket w-5"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kanan navbar mobile -->
            <div class="md:hidden flex items-center gap-3">

                <!-- Tombol profile mobile -->
                <button 
                    type="button"
                    onclick="toggleUserProfileDropdownMobile()" 
                    class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center"
                    aria-label="Buka profil"
                >
                    <i class="fa-solid fa-circle-user text-xl"></i>
                </button>

                <!-- Tombol menu mobile -->
                <button 
                    type="button"
                    onclick="toggleMobileMenu()" 
                    class="w-10 h-10 rounded-xl bg-gray-50 text-gray-600 flex items-center justify-center"
                    aria-label="Buka menu"
                >
                    <i id="menu-icon" class="fa-solid fa-bars-staggered text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Dropdown profile mobile -->
        <div 
            id="user-profile-menu-mobile" 
            class="hidden md:hidden mt-2 mb-3 bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden"
        >
            <!-- Info profile mobile -->
            <div class="p-4 bg-pink-50/50 border-b border-pink-100">
                <p class="text-sm font-bold text-gray-700 truncate">
                    <?= htmlspecialchars($nama_user); ?>
                </p>

                <p class="text-[10px] text-gray-400 uppercase font-bold">
                    <?= htmlspecialchars($role_user); ?>
                </p>
            </div>

            <!-- Menu pengaturan profile mobile -->
            <button
                type="button"
                onclick="showSectionAndCloseMenu('profil'); closeUserProfileDropdownMobile();"
                class="w-full flex items-center gap-3 px-4 py-3 text-sm text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition text-left"
            >
                <i class="fa-solid fa-user-gear w-5"></i>
                <span>Pengaturan Profil</span>
            </button>

            <!-- Menu logout mobile -->
            <div class="border-t border-pink-50">
                <a 
                    href="../logout.php"
                    onclick="confirmLogout(event)"
                    class="flex items-center gap-3 px-4 py-3 text-sm text-red-500 hover:bg-red-50 transition font-semibold"
                >
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Menu mobile -->
        <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
            <a 
                href="../index.php"
                class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50"
            >
                Home
            </a>

            <button 
                type="button"
                onclick="showSectionAndCloseMenu('layanan')" 
                class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50"
            >
                Layanan
            </button>

            <button 
                type="button"
                onclick="showSectionAndCloseMenu('booking')" 
                class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50"
            >
                Booking Saya
            </button>
        </div>
    </div>
</nav>
<!-- SWEET ALERT LOGOUT -->
<script src="../layout/js/sweetalert.js" languange="javascript"></script>
<!-- Script navbar user -->
<script>
    // Membuka section dan menutup menu mobile
    function showSectionAndCloseMenu(sectionName) {
        if (typeof showSection === 'function') {
            showSection(sectionName);
        }

        closeMobileMenu();
        closeUserProfileDropdownMobile();
    }

    // Membuka dan menutup dropdown profile desktop
    function toggleUserProfileDropdown() {
        const menu = document.getElementById('user-profile-menu');

        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // Menutup dropdown profile desktop
    function closeUserProfileDropdown() {
        const menu = document.getElementById('user-profile-menu');

        if (menu) {
            menu.classList.add('hidden');
        }
    }

    // Membuka dan menutup dropdown profile mobile
    function toggleUserProfileDropdownMobile() {
        const menu = document.getElementById('user-profile-menu-mobile');

        closeMobileMenu();

        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // Menutup dropdown profile mobile
    function closeUserProfileDropdownMobile() {
        const menu = document.getElementById('user-profile-menu-mobile');

        if (menu) {
            menu.classList.add('hidden');
        }
    }

    // Membuka dan menutup menu mobile
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('menu-icon');

        closeUserProfileDropdownMobile();

        if (!menu) return;

        menu.classList.toggle('hidden');

        if (icon) {
            icon.classList.toggle('fa-bars-staggered');
            icon.classList.toggle('fa-xmark');
        }
    }

    // Menutup menu mobile
    function closeMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const icon = document.getElementById('menu-icon');

        if (menu) {
            menu.classList.add('hidden');
        }

        if (icon) {
            icon.classList.add('fa-bars-staggered');
            icon.classList.remove('fa-xmark');
        }
    }

    // Membuka section dari parameter URL
    document.addEventListener('DOMContentLoaded', function () {
        const params = new URLSearchParams(window.location.search);
        const section = params.get('section') || window.location.hash.replace('#', '');

        if (section && typeof showSection === 'function') {
            showSection(section);
        }
    });

    // Menutup dropdown desktop saat klik luar
    window.addEventListener('click', function (event) {
        const desktopDropdown = document.querySelector('.user-profile-dropdown');
        const desktopMenu = document.getElementById('user-profile-menu');

        if (
            desktopDropdown &&
            desktopMenu &&
            !desktopDropdown.contains(event.target)
        ) {
            desktopMenu.classList.add('hidden');
        }
    });
</script>
