<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mey Salon - User Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        .cart-sheet { 
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1); 
            border-top-left-radius: 2.5rem; 
            border-top-right-radius: 2.5rem;
        }
        .sheet-hidden { transform: translateY(100%); }
        .sheet-visible { transform: translateY(0); }
        
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-pink-50/20 text-gray-800 overflow-x-hidden">

    <nav class="fixed w-full z-50 glass-nav border-b border-pink-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2 cursor-pointer" onclick="showSection('layanan')">
                    <span class="text-xl font-bold text-pink-600 italic tracking-tighter">Mey Salon</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <button onclick="showSection('layanan')" class="text-sm font-semibold hover:text-pink-600 transition-colors">Layanan</button>
                    <button onclick="showSection('booking')" class="text-sm font-semibold hover:text-pink-600 transition-colors">Booking Saya</button>
                    
                    <button onclick="toggleCart()" class="relative p-2 text-gray-600 hover:text-pink-600 transition-all">
                        <i class="fa-solid fa-basket-shopping text-xl"></i>
                        <span id="cart-count-nav" class="absolute top-0 right-0 bg-pink-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
                    </button>

                    <button onclick="showSection('profil')" class="flex items-center gap-2 text-sm font-semibold bg-pink-50 text-pink-600 px-4 py-2 rounded-full hover:bg-pink-100 transition-all">
                        <i class="fa-solid fa-circle-user text-lg"></i>
                        <span>Profil</span>
                    </button>
                </div>

                <!-- Mobile Action -->
                <div class="md:hidden flex items-center gap-4">
                    <button onclick="toggleCart()" class="relative p-2 text-pink-600">
                        <i class="fa-solid fa-basket-shopping text-xl"></i>
                        <span id="cart-count-mobile" class="absolute top-0 right-0 bg-pink-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full hidden">0</span>
                    </button>
                    <button onclick="toggleMobileMenu()" class="text-gray-600">
                        <i id="menu-icon" class="fa-solid fa-bars-staggered text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 pt-24 pb-32">
        
        <section id="section-layanan" class="content-section">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Layanan Kecantikan</h2>
                <p class="text-sm text-gray-500">Pilih layanan yang Anda inginkan hari ini.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Service 1 -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">
                    <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                        <i class="fa-solid fa-scissors"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800">Hair Cut & Styling</h3>
                    <p class="text-xs text-gray-400 mt-2 leading-relaxed">Potong rambut modern dan styling profesional sesuai bentuk wajah.</p>
                    <div class="flex justify-between items-center mt-6">
                        <span class="text-pink-600 font-bold">Rp 85.000</span>
                        <button onclick="addToCart(1, 'Hair Cut & Styling', 85000)" class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all">
                            Pilih
                        </button>
                    </div>
                </div>

                <!-- Service 2 -->
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">
                    <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                        <i class="fa-solid fa-sparkles"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800">Facial Glowing</h3>
                    <p class="text-xs text-gray-400 mt-2 leading-relaxed">Perawatan wajah deep cleaning untuk kulit cerah dan merona.</p>
                    <div class="flex justify-between items-center mt-6">
                        <span class="text-pink-600 font-bold">Rp 150.000</span>
                        <button onclick="addToCart(2, 'Facial Glowing', 150000)" class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all">
                            Pilih
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section id="section-booking" class="content-section hidden">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Booking Saya</h2>
                <p class="text-sm text-gray-500">Lacak status reservasi aktif Anda.</p>
            </div>
            <div id="booking-list" class="space-y-4">
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-pink-200">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-pink-100 mb-4"></i>
                    <p class="text-sm text-gray-400 font-medium">Belum ada riwayat booking.</p>
                </div>
            </div>
        </section>

        <section id="section-profil" class="content-section hidden">
            <div class="max-w-xl mx-auto">
                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-100 shadow-sm space-y-6">
                    <div class="text-center">
                        <img src="https://placehold.co/100x100/fbcfe8/db2777?text=M" class="w-24 h-24 rounded-3xl mx-auto mb-4 border-4 border-pink-50 shadow-sm">
                        <h3 class="font-bold text-xl">Profil Saya</h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Nama</label>
                            <input type="text" value="Meylani Putri" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">WhatsApp</label>
                            <input type="tel" value="08123456789" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400">
                        </div>
                    </div>
                    <button onclick="showToast('Profil Diperbarui')" class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </section>
    </main>

    <div id="cart-overlay" onclick="toggleCart()" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[90] hidden animate-fade-in"></div>

    <div id="cart-drawer" class="fixed bottom-0 left-0 right-0 z-[100] bg-white cart-sheet sheet-hidden shadow-[0_-20px_50px_-12px_rgba(0,0,0,0.15)] flex flex-col max-h-[85vh]">
        <!-- Handle Bar for Design -->
        <div class="w-12 h-1.5 bg-gray-200 rounded-full mx-auto mt-4 mb-2"></div>
        
        <div class="p-6 border-b border-pink-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Keranjang Layanan</h3>
                <p id="cart-item-count" class="text-xs text-pink-600 font-bold">0 Layanan dipilih</p>
            </div>
            <button onclick="toggleCart()" class="w-10 h-10 flex items-center justify-center rounded-full bg-pink-50 text-pink-600">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
            <!-- Empty state inside cart -->
            <div id="empty-cart-msg" class="text-center py-10 opacity-30">
                <i class="fa-solid fa-basket-shopping text-5xl mb-4"></i>
                <p class="text-sm font-medium">Keranjang kosong</p>
            </div>
        </div>

        <div class="p-6 border-t border-pink-50 bg-white">
            <div class="flex justify-between items-center mb-6">
                <span class="text-sm text-gray-400 font-bold uppercase tracking-widest">Total Bayar</span>
                <span id="cart-total-price" class="text-2xl font-bold text-pink-600 tracking-tighter">Rp 0</span>
            </div>
            <button id="btn-checkout" onclick="processCheckout()" disabled class="w-full py-5 bg-pink-600 text-white font-bold rounded-[1.5rem] shadow-xl shadow-pink-100 hover:bg-pink-700 active:scale-95 transition-all disabled:opacity-40 disabled:cursor-not-allowed">
                Konfirmasi Reservasi
            </button>
        </div>
    </div>
