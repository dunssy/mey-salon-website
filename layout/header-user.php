<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Mengatur karakter dan responsive halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mey Salon - User Portal</title>

    <!-- Memanggil Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Memanggil font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Memanggil Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Style tambahan halaman -->
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            scroll-behavior: smooth; 
        }

        .glass-nav { 
            background: rgba(255, 255, 255, 0.85); 
            backdrop-filter: blur(12px); 
        }

        .cart-sheet { 
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
            border-top-left-radius: 2.5rem; 
            border-top-right-radius: 2.5rem;
        }

        .sheet-hidden { 
            transform: translateY(100%); 
        }

        .sheet-visible { 
            transform: translateY(0); 
        }

        .animate-fade-in { 
            animation: fadeIn 0.3s ease-out; 
        }

        @keyframes fadeIn {
            from { 
                opacity: 0; 
                transform: translateY(10px); 
            }

            to { 
                opacity: 1; 
                transform: translateY(0); 
            }
        }

        .calendar-day {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 0.9rem;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .calendar-day-empty {
            opacity: 0;
            pointer-events: none;
        }

        .calendar-day-available {
            background: white;
            border: 1px solid #fbcfe8;
            color: #374151;
        }

        .calendar-day-available:hover {
            background: #fdf2f8;
            color: #db2777;
        }

        .calendar-day-booked {
            background: #db2777;
            color: white;
            box-shadow: 0 8px 20px rgba(219, 39, 119, 0.25);
        }

        .calendar-day-selected {
            background: #111827 !important;
            color: white !important;
        }
    </style>
</head>

<body class="bg-pink-50/20 text-gray-800 overflow-x-hidden">

    <!-- Navbar user -->
    <nav class="fixed w-full z-50 glass-nav border-b border-pink-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">

                <!-- Logo website -->
                <div class="flex items-center gap-2 cursor-pointer" onclick="showSection('layanan')">
                    <span class="text-xl font-bold text-pink-600 italic tracking-tighter">
                        Mey Salon
                    </span>
                </div>

                <!-- Menu desktop -->
                <div class="hidden md:flex items-center space-x-6">
                    <button onclick="showSection('layanan')" class="text-sm font-semibold hover:text-pink-600 transition-colors">
                        Layanan
                    </button>

                    <button onclick="showSection('booking')" class="text-sm font-semibold hover:text-pink-600 transition-colors">
                        Booking Saya
                    </button>

                    <button onclick="toggleCart()" class="relative p-2 text-gray-600 hover:text-pink-600 transition-all">
                        <i class="fa-solid fa-basket-shopping text-xl"></i>
                        <span id="cart-count-nav" class="absolute top-0 right-0 bg-pink-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">
                            0
                        </span>
                    </button>

                    <button onclick="showSection('profil')" class="flex items-center gap-2 text-sm font-semibold bg-pink-50 text-pink-600 px-4 py-2 rounded-full hover:bg-pink-100 transition-all">
                        <i class="fa-solid fa-circle-user text-lg"></i>
                        <span>Profil</span>
                    </button>
                </div>

                <!-- Menu mobile -->
                <div class="md:hidden flex items-center gap-4">
                    <button onclick="toggleCart()" class="relative p-2 text-pink-600">
                        <i class="fa-solid fa-basket-shopping text-xl"></i>
                        <span id="cart-count-mobile" class="absolute top-0 right-0 bg-pink-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">
                            0
                        </span>
                    </button>

                    <button onclick="toggleMobileMenu()" class="text-gray-600">
                        <i id="menu-icon" class="fa-solid fa-bars-staggered text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Dropdown menu mobile -->
            <div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
                <button onclick="showSection('layanan')" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50">
                    Layanan
                </button>

                <button onclick="showSection('booking')" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50">
                    Booking Saya
                </button>

                <button onclick="showSection('profil')" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold hover:bg-pink-50">
                    Profil
                </button>
            </div>
        </div>
    </nav>

    <!-- Konten utama -->
    <main class="max-w-6xl mx-auto px-4 pt-24 pb-32">

        <!-- Section layanan dan kalender booking -->
        <section id="section-layanan" class="content-section">

            <!-- Header section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
                    Layanan Kecantikan
                </h2>

                <p class="text-sm text-gray-500">
                    Pilih tanggal booking di kalender, lalu pilih layanan yang Anda inginkan.
                </p>
            </div>

            <!-- Layout kalender kiri dan layanan kanan -->
            <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6 items-start">

                <!-- Kalender booking -->
                <aside class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 lg:sticky lg:top-24">

                    <!-- Header kalender -->
                    <div class="flex items-center justify-between mb-5">
                        <button 
                            type="button" 
                            onclick="changeMonth(-1)" 
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-pink-50 text-pink-600 hover:bg-pink-100 transition"
                        >
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>

                        <div class="text-center">
                            <h3 id="calendar-month-title" class="font-bold text-gray-800"></h3>
                            <p class="text-[11px] text-gray-400">Jadwal Booking</p>
                        </div>

                        <button 
                            type="button" 
                            onclick="changeMonth(1)" 
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-pink-50 text-pink-600 hover:bg-pink-100 transition"
                        >
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    </div>

                    <!-- Nama hari kalender -->
                    <div class="grid grid-cols-7 gap-2 text-center text-[11px] font-bold text-gray-400 mb-2">
                        <span>Min</span>
                        <span>Sen</span>
                        <span>Sel</span>
                        <span>Rab</span>
                        <span>Kam</span>
                        <span>Jum</span>
                        <span>Sab</span>
                    </div>

                    <!-- Isi tanggal kalender -->
                    <div id="calendar-days" class="grid grid-cols-7 gap-2"></div>

                    <!-- Keterangan kalender -->
                    <div class="mt-6 space-y-3 text-xs text-gray-500">

                        <!-- Keterangan tersedia -->
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-white border border-pink-200"></span>
                            <span>Tanggal tersedia</span>
                        </div>

                        <!-- Keterangan terisi -->
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-pink-600"></span>
                            <span>Sudah ada booking</span>
                        </div>

                        <!-- Keterangan dipilih -->
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-gray-800"></span>
                            <span>Tanggal dipilih</span>
                        </div>
                    </div>

                    <!-- Detail tanggal terpilih -->
                    <div class="mt-6 p-4 bg-pink-50/60 rounded-2xl border border-pink-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                            Tanggal Dipilih
                        </p>

                        <h4 id="selected-date-text" class="text-sm font-bold text-gray-800 mt-1">
                            Belum memilih tanggal
                        </h4>

                        <p id="selected-date-status" class="text-xs text-pink-600 mt-1">
                            Pilih tanggal pada kalender.
                        </p>
                    </div>
                </aside>

                <!-- Daftar layanan -->
                <div class="space-y-5">

                    <!-- Header layanan -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">
                                Pilih Layanan
                            </h3>

                            <p class="text-xs text-gray-400">
                                Layanan akan masuk ke keranjang booking.
                            </p>
                        </div>
                    </div>

                    <!-- Grid layanan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Layanan pertama -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">
                            <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-scissors"></i>
                            </div>

                            <h3 class="font-bold text-lg text-gray-800">
                                Hair Cut & Styling
                            </h3>

                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                Potong rambut modern dan styling profesional sesuai bentuk wajah.
                            </p>

                            <div class="flex justify-between items-center mt-6">
                                <div>
                                    <span class="block text-pink-600 font-bold">
                                        Rp 85.000
                                    </span>
                                    <span class="text-[11px] text-gray-400">
                                        Estimasi 45 menit
                                    </span>
                                </div>

                                <button 
                                    type="button"
                                    onclick="addToCart(1, 'Hair Cut & Styling', 85000, 45)" 
                                    class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all"
                                >
                                    Pilih
                                </button>
                            </div>
                        </div>

                        <!-- Layanan kedua -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">
                            <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-sparkles"></i>
                            </div>

                            <h3 class="font-bold text-lg text-gray-800">
                                Facial Glowing
                            </h3>

                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                Perawatan wajah deep cleaning untuk kulit cerah dan merona.
                            </p>

                            <div class="flex justify-between items-center mt-6">
                                <div>
                                    <span class="block text-pink-600 font-bold">
                                        Rp 150.000
                                    </span>
                                    <span class="text-[11px] text-gray-400">
                                        Estimasi 60 menit
                                    </span>
                                </div>

                                <button 
                                    type="button"
                                    onclick="addToCart(2, 'Facial Glowing', 150000, 60)" 
                                    class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all"
                                >
                                    Pilih
                                </button>
                            </div>
                        </div>

                        <!-- Layanan ketiga -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">
                            <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-hand-sparkles"></i>
                            </div>

                            <h3 class="font-bold text-lg text-gray-800">
                                Creambath
                            </h3>

                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                Perawatan rambut dan pijat kepala untuk relaksasi maksimal.
                            </p>

                            <div class="flex justify-between items-center mt-6">
                                <div>
                                    <span class="block text-pink-600 font-bold">
                                        Rp 120.000
                                    </span>
                                    <span class="text-[11px] text-gray-400">
                                        Estimasi 50 menit
                                    </span>
                                </div>

                                <button 
                                    type="button"
                                    onclick="addToCart(3, 'Creambath', 120000, 50)" 
                                    class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all"
                                >
                                    Pilih
                                </button>
                            </div>
                        </div>

                        <!-- Layanan keempat -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">
                            <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                                <i class="fa-solid fa-droplet"></i>
                            </div>

                            <h3 class="font-bold text-lg text-gray-800">
                                Hair Coloring
                            </h3>

                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                Pewarnaan rambut dengan warna pilihan dan hasil tahan lama.
                            </p>

                            <div class="flex justify-between items-center mt-6">
                                <div>
                                    <span class="block text-pink-600 font-bold">
                                        Rp 250.000
                                    </span>
                                    <span class="text-[11px] text-gray-400">
                                        Estimasi 90 menit
                                    </span>
                                </div>

                                <button 
                                    type="button"
                                    onclick="addToCart(4, 'Hair Coloring', 250000, 90)" 
                                    class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all"
                                >
                                    Pilih
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section booking saya -->
        <section id="section-booking" class="content-section hidden">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">
                    Booking Saya
                </h2>

                <p class="text-sm text-gray-500">
                    Lacak status reservasi aktif Anda.
                </p>
            </div>

            <div id="booking-list" class="space-y-4">
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-pink-200">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-pink-100 mb-4"></i>
                    <p class="text-sm text-gray-400 font-medium">
                        Belum ada riwayat booking.
                    </p>
                </div>
            </div>
        </section>

        <!-- Section profil -->
        <section id="section-profil" class="content-section hidden">
            <div class="max-w-xl mx-auto">
                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-100 shadow-sm space-y-6">
                    <div class="text-center">
                        <img src="https://placehold.co/100x100/fbcfe8/db2777?text=M" class="w-24 h-24 rounded-3xl mx-auto mb-4 border-4 border-pink-50 shadow-sm" alt="Foto Profil">
                        <h3 class="font-bold text-xl">Profil Saya</h3>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                Nama
                            </label>
                            <input type="text" value="Meylani Putri" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400">
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                WhatsApp
                            </label>
                            <input type="tel" value="08123456789" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400">
                        </div>
                    </div>

                    <button onclick="showToast('Profil diperbarui')" class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </section>
    </main>

    <!-- Overlay cart -->
    <div id="cart-overlay" onclick="toggleCart()" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[90] hidden animate-fade-in"></div>

    <!-- Drawer cart -->
    <div id="cart-drawer" class="fixed bottom-0 left-0 right-0 z-[100] bg-white cart-sheet sheet-hidden shadow-[0_-20px_50px_-12px_rgba(0,0,0,0.15)] flex flex-col max-h-[85vh]">

        <!-- Handle drawer -->
        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mt-4 mb-2"></div>

        <!-- Header drawer -->
        <div class="p-6 border-b border-pink-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-800">
                    Keranjang Layanan
                </h3>

                <p id="cart-item-count" class="text-xs text-pink-600 font-bold">
                    0 Layanan dipilih
                </p>
            </div>

            <button onclick="toggleCart()" class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-50 text-pink-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Isi cart -->
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">

            <!-- Pesan cart kosong -->
            <div id="empty-cart-msg" class="text-center py-10 opacity-30">
                <i class="fa-solid fa-basket-shopping text-5xl mb-4"></i>
                <p class="text-sm font-medium">Keranjang kosong</p>
            </div>
        </div>

        <!-- Footer cart -->
        <div class="p-6 border-t border-pink-50 bg-white">

            <!-- Ringkasan cart -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Total Bayar
                    </span>
                    <p id="cart-total-price" class="text-xl font-bold text-pink-600 tracking-tighter">
                        Rp 0
                    </p>
                </div>

                <div>
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        Estimasi Waktu
                    </span>
                    <p id="cart-total-duration" class="text-xl font-bold text-gray-800 tracking-tighter">
                        0 Menit
                    </p>
                </div>
            </div>

            <!-- Tanggal booking checkout -->
            <div class="mb-5">
                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    Tanggal Booking
                </label>
                <input 
                    type="text" 
                    id="checkout-date" 
                    readonly 
                    placeholder="Pilih tanggal dari kalender"
                    class="mt-2 w-full px-4 py-3 bg-pink-50/40 border border-pink-100 rounded-2xl text-sm outline-none"
                >
            </div>

            <!-- Tombol checkout -->
            <button id="btn-checkout" onclick="processCheckout()" disabled class="w-full py-5 bg-pink-600 text-white font-bold rounded-[1.5rem] shadow-xl shadow-pink-100 hover:bg-pink-700 active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                Konfirmasi Reservasi
            </button>
        </div>
    </div>

    <!-- Toast pesan -->
    <div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-xl hidden z-[120]">
        Pesan
    </div>

    <!-- Script utama -->
    <script>
        // Menyimpan data cart
        let cart = [];

        // Menyimpan bulan kalender aktif
        let calendarDate = new Date(2026, 3, 1);

        // Menyimpan tanggal booking dipilih
        let selectedBookingDate = '';

        // Data contoh booking yang sudah terisi
        const bookedSchedules = {
            '2026-04-03': ['Hair Cut & Styling - 10:00'],
            '2026-04-08': ['Facial Glowing - 13:00', 'Creambath - 15:00'],
            '2026-04-15': ['Hair Coloring - 11:00'],
            '2026-04-22': ['Creambath - 14:00'],
            '2026-04-29': ['Hair Cut & Styling - 09:00']
        };

        // Menampilkan section tertentu
        function showSection(sectionName) {
            const sections = document.querySelectorAll('.content-section');

            sections.forEach(section => {
                section.classList.add('hidden');
            });

            document.getElementById(`section-${sectionName}`).classList.remove('hidden');

            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');

            if (mobileMenu) {
                mobileMenu.classList.add('hidden');
            }

            if (menuIcon) {
                menuIcon.className = 'fa-solid fa-bars-staggered text-2xl';
            }
        }

        // Membuka dan menutup menu mobile
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');

            mobileMenu.classList.toggle('hidden');

            if (mobileMenu.classList.contains('hidden')) {
                menuIcon.className = 'fa-solid fa-bars-staggered text-2xl';
            } else {
                menuIcon.className = 'fa-solid fa-xmark text-2xl';
            }
        }

        // Format tanggal menjadi YYYY-MM-DD
        function formatDateKey(year, month, day) {
            const monthText = String(month + 1).padStart(2, '0');
            const dayText = String(day).padStart(2, '0');

            return `${year}-${monthText}-${dayText}`;
        }

        // Menampilkan kalender booking
        function renderCalendar() {
            const calendarDays = document.getElementById('calendar-days');
            const calendarTitle = document.getElementById('calendar-month-title');

            const year = calendarDate.getFullYear();
            const month = calendarDate.getMonth();

            const firstDay = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();

            const monthName = calendarDate.toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric'
            });

            calendarTitle.textContent = monthName;
            calendarDays.innerHTML = '';

            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.className = 'calendar-day calendar-day-empty';
                calendarDays.appendChild(emptyDay);
            }

            for (let day = 1; day <= totalDays; day++) {
                const dateKey = formatDateKey(year, month, day);
                const dayButton = document.createElement('button');

                dayButton.type = 'button';
                dayButton.textContent = day;
                dayButton.className = 'calendar-day calendar-day-available';

                if (bookedSchedules[dateKey]) {
                    dayButton.classList.add('calendar-day-booked');
                }

                if (selectedBookingDate === dateKey) {
                    dayButton.classList.add('calendar-day-selected');
                }

                dayButton.onclick = function () {
                    selectBookingDate(dateKey);
                };

                calendarDays.appendChild(dayButton);
            }
        }

        // Memilih tanggal booking
        function selectBookingDate(dateKey) {
            selectedBookingDate = dateKey;

            const selectedDateText = document.getElementById('selected-date-text');
            const selectedDateStatus = document.getElementById('selected-date-status');
            const checkoutDate = document.getElementById('checkout-date');

            const dateObject = new Date(dateKey);
            const formattedDate = dateObject.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            selectedDateText.textContent = formattedDate;
            checkoutDate.value = formattedDate;

            if (bookedSchedules[dateKey]) {
                selectedDateStatus.innerHTML = `
                    Sudah ada booking:<br>
                    ${bookedSchedules[dateKey].map(item => `• ${item}`).join('<br>')}
                `;
            } else {
                selectedDateStatus.textContent = 'Tanggal ini masih tersedia untuk booking.';
            }

            renderCalendar();
            updateCartUI();
        }

        // Mengganti bulan kalender
        function changeMonth(step) {
            calendarDate.setMonth(calendarDate.getMonth() + step);
            renderCalendar();
        }

        // Membuka dan menutup cart
        function toggleCart() {
            const overlay = document.getElementById('cart-overlay');
            const drawer = document.getElementById('cart-drawer');

            overlay.classList.toggle('hidden');
            drawer.classList.toggle('sheet-hidden');
            drawer.classList.toggle('sheet-visible');
        }

        // Menambahkan layanan ke cart
        function addToCart(id, name, price, duration) {
            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                showToast('Layanan sudah ada di keranjang');
                return;
            }

            cart.push({
                id: id,
                name: name,
                price: price,
                duration: duration
            });

            updateCartUI();
            showToast('Layanan ditambahkan ke keranjang');
        }

        // Menghapus layanan dari cart
        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);

            updateCartUI();
            showToast('Layanan dihapus dari keranjang');
        }

        // Format rupiah
        function formatRupiah(number) {
            return 'Rp ' + number.toLocaleString('id-ID');
        }

        // Memperbarui tampilan cart
        function updateCartUI() {
            const cartItemsContainer = document.getElementById('cart-items-container');
            const emptyCartMsg = document.getElementById('empty-cart-msg');
            const cartItemCount = document.getElementById('cart-item-count');
            const cartTotalPrice = document.getElementById('cart-total-price');
            const cartTotalDuration = document.getElementById('cart-total-duration');
            const cartCountNav = document.getElementById('cart-count-nav');
            const cartCountMobile = document.getElementById('cart-count-mobile');
            const checkoutButton = document.getElementById('btn-checkout');

            const totalPrice = cart.reduce((total, item) => total + item.price, 0);
            const totalDuration = cart.reduce((total, item) => total + item.duration, 0);

            cartItemCount.textContent = `${cart.length} Layanan dipilih`;
            cartTotalPrice.textContent = formatRupiah(totalPrice);
            cartTotalDuration.textContent = `${totalDuration} Menit`;

            cartCountNav.textContent = cart.length;
            cartCountMobile.textContent = cart.length;

            if (cart.length > 0) {
                cartCountNav.classList.remove('hidden');
                cartCountMobile.classList.remove('hidden');
                emptyCartMsg.classList.add('hidden');
            } else {
                cartCountNav.classList.add('hidden');
                cartCountMobile.classList.add('hidden');
                emptyCartMsg.classList.remove('hidden');
            }

            cartItemsContainer.querySelectorAll('.cart-item').forEach(item => item.remove());

            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'cart-item bg-pink-50/40 border border-pink-100 rounded-2xl p-4 flex justify-between items-center';

                itemElement.innerHTML = `
                    <div>
                        <h4 class="font-bold text-sm text-gray-800">${item.name}</h4>
                        <p class="text-xs text-gray-400 mt-1">${item.duration} menit</p>
                        <p class="text-sm font-bold text-pink-600 mt-1">${formatRupiah(item.price)}</p>
                    </div>

                    <button onclick="removeFromCart(${item.id})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-red-500 hover:bg-red-50 transition">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                `;

                cartItemsContainer.appendChild(itemElement);
            });

            checkoutButton.disabled = cart.length === 0 || selectedBookingDate === '';
        }

        // Memproses checkout booking
        function processCheckout() {
            if (selectedBookingDate === '') {
                showToast('Pilih tanggal booking terlebih dahulu');
                return;
            }

            if (cart.length === 0) {
                showToast('Pilih layanan terlebih dahulu');
                return;
            }

            const bookingList = document.getElementById('booking-list');
            const totalPrice = cart.reduce((total, item) => total + item.price, 0);
            const totalDuration = cart.reduce((total, item) => total + item.duration, 0);

            const dateObject = new Date(selectedBookingDate);
            const formattedDate = dateObject.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            bookingList.innerHTML = `
                <div class="bg-white rounded-3xl border border-pink-100 shadow-sm p-6 animate-fade-in">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-[11px] font-bold text-pink-600 uppercase tracking-widest">Booking Baru</p>
                            <h3 class="text-lg font-bold text-gray-800 mt-1">${formattedDate}</h3>
                            <p class="text-sm text-gray-500 mt-2">${cart.map(item => item.name).join(', ')}</p>
                        </div>

                        <div class="text-left md:text-right">
                            <p class="text-sm font-bold text-gray-800">${formatRupiah(totalPrice)}</p>
                            <p class="text-xs text-gray-400">${totalDuration} menit</p>
                            <span class="inline-block mt-2 px-3 py-1 bg-yellow-50 text-yellow-700 text-[10px] font-bold rounded-full uppercase">
                                Pending
                            </span>
                        </div>
                    </div>
                </div>
            `;

            showSection('booking');
            toggleCart();

            cart = [];
            selectedBookingDate = '';
            document.getElementById('checkout-date').value = '';
            document.getElementById('selected-date-text').textContent = 'Belum memilih tanggal';
            document.getElementById('selected-date-status').textContent = 'Pilih tanggal pada kalender.';

            updateCartUI();
            renderCalendar();
            showToast('Reservasi berhasil dibuat');
        }

        // Menampilkan toast
        function showToast(message) {
            const toast = document.getElementById('toast');

            toast.textContent = message;
            toast.classList.remove('hidden');

            setTimeout(() => {
                toast.classList.add('hidden');
            }, 2500);
        }

        // Menjalankan kalender saat halaman dibuka
        renderCalendar();

        // Memperbarui cart saat halaman dibuka
        updateCartUI();
    </script>
</body>
</html>