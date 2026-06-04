<!-- Footer informatif dashboard -->
<footer class="mt-12 bg-white border-t border-pink-100 px-4 md:px-8 py-8">
    <!-- Kontainer footer -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Informasi aplikasi -->
        <div class="text-center md:text-left">
            <h5 class="text-pink-600 font-bold italic text-lg">
                Mey Salons
            </h5>

            <p class="mt-2 text-[11px] text-gray-400 leading-relaxed">
                Mey Salon adalah platform pemesanan layanan salon yang memudahkan pelanggan untuk menemukan dan memesan layanan kecantikan.
            </p>
        </div>
        <!-- Link dan kontak -->
        <div class="flex justify-center md:justify-start gap-12">

            <!-- Link bantuan -->
            <div>
                <h6 class="mb-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Links
                </h6>

                <ul class="space-y-2 text-[11px] text-gray-600 font-medium">
                    <li>
                        <a href="#" class="hover:text-pink-600 transition-colors">
                            Panduan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-pink-600 transition-colors">
                            Dukungan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-pink-600 transition-colors">
                            API Docs
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Informasi kontak -->
            <div>
                <h6 class="mb-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Kontak
                </h6>

                <ul class="space-y-2 text-[11px] text-gray-600 font-medium">
                    <li class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fa-solid fa-envelope opacity-50"></i>
                        <span>help@meysalon.com</span>
                    </li>

                    <li class="flex items-center justify-center md:justify-start gap-2">
                        <i class="fa-solid fa-phone opacity-50"></i>
                        <span>+62 812-XXXX</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright dan ikon keamanan -->
        <div class="text-center md:text-right border-t md:border-t-0 border-pink-50 pt-6 md:pt-0">

            <!-- Copyright -->
            <p class="text-[11px] text-gray-400">
                &copy; <?php echo date("Y"); ?> Mey Salon Dashboard.
            </p>

            <!-- Ikon pembayaran dan keamanan -->
            <div class="mt-3 flex justify-center md:justify-end gap-3 text-gray-400 opacity-40 grayscale hover:grayscale-0 hover:opacity-100 transition-all">
                <i class="fa-brands fa-cc-visa text-xl"></i>
                <i class="fa-brands fa-cc-mastercard text-xl"></i>
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>
        </div>
    </div>
</footer>