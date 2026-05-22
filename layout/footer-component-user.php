<!-- Footer user -->
<footer class="bg-white border-t border-pink-100 mt-20">

    <!-- Kontainer footer -->
    <div class="max-w-6xl mx-auto px-4 py-10">

        <!-- Grid footer -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Brand footer -->
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <img
                        src="../layout/images/mey-salon.png"
                        alt="Mey Salon Logo"
                        class="w-10 h-10 rounded-xl object-cover"
                    />

                    <h3 class="text-xl font-bold text-pink-600 italic">
                        Mey Salon
                    </h3>
                </div>

                <p class="text-sm text-gray-500 leading-relaxed">
                    Platform booking layanan salon yang memudahkan pelanggan memilih jadwal, layanan, dan melakukan reservasi dengan nyaman.
                </p>
            </div>

            <!-- Link footer -->
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                    Menu
                </h4>

                <ul class="space-y-3 text-sm text-gray-600 font-medium">
                    <li>
                        <button onclick="showSection('layanan')" class="hover:text-pink-600 transition">
                            Layanan
                        </button>
                    </li>

                    <li>
                        <button onclick="showSection('booking')" class="hover:text-pink-600 transition">
                            Booking Saya
                        </button>
                    </li>

                    <li>
                        <button onclick="showSection('profil')" class="hover:text-pink-600 transition">
                            Profil
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Kontak footer -->
            <div>
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">
                    Kontak
                </h4>

                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-envelope text-pink-500"></i>
                        <span>help@meysalon.com</span>
                    </li>

                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-phone text-pink-500"></i>
                        <span>+62 812-XXXX-XXXX</span>
                    </li>

                    <li class="flex items-center gap-2">
                        <i class="fa-solid fa-location-dot text-pink-500"></i>
                        <span>Indonesia</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-pink-50 mt-8 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-400">
                &copy; <?= date('Y'); ?> Mey Salon. All rights reserved.
            </p>

            <p class="text-xs text-gray-400">
                Booking mudah, pembayaran cash di salon.
            </p>
        </div>
    </div>
</footer>