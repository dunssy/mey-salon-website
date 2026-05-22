<!-- Section layanan -->
<section id="section-layanan" class="content-section">

    <!-- Header halaman -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
            Booking Layanan
        </h2>

        <p class="text-sm text-gray-500">
            Pilih tanggal, jam, dan layanan yang ingin Anda booking.
        </p>
    </div>

    <!-- Layout booking -->
    <div class="grid grid-cols-1 lg:grid-cols-[340px_1fr] gap-6 items-start">

        <!-- Kalender dan jam booking -->
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

            <!-- Nama hari -->
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
            <div class="mt-5 grid grid-cols-1 gap-2 text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-white border border-pink-200"></span>
                    <span>Tersedia</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-pink-600"></span>
                    <span>Sudah ada booking</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-800"></span>
                    <span>Dipilih</span>
                </div>
            </div>

            <!-- Detail tanggal dipilih -->
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

            <!-- Pilih jam booking -->
            <div class="mt-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">
                    Pilih Jam
                </p>

                <div id="time-slots" class="grid grid-cols-3 gap-2"></div>
            </div>
        </aside>

        <!-- Area kanan layanan dan keranjang -->
        <div class="space-y-6">

            <!-- Daftar layanan -->
            <div>
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">
                        Pilih Layanan
                    </h3>

                    <p class="text-xs text-gray-400">
                        Layanan yang dipilih akan muncul di keranjang bawah.
                    </p>
                </div>

                <!-- Grid layanan dari database -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php global $query_layanan; ?>
                    <?php if (mysqli_num_rows($query_layanan) > 0) : ?>

                        <?php while ($layanan = mysqli_fetch_assoc($query_layanan)) : ?>

                            <!-- Card layanan -->
                            <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100 group">

                                <!-- Icon layanan -->
                                <div class="w-14 h-14 bg-pink-50 text-pink-500 rounded-2xl flex items-center justify-center text-2xl mb-4 group-hover:bg-pink-600 group-hover:text-white transition-all">
                                    <i class="fa-solid fa-scissors"></i>
                                </div>

                                <!-- Nama layanan -->
                                <h3 class="font-bold text-lg text-gray-800">
                                    <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                </h3>

                                <!-- Durasi layanan -->
                                <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                    Durasi layanan sekitar <?= htmlspecialchars($layanan['durasi_layanan']); ?> menit.
                                </p>

                                <!-- Harga dan tombol pilih -->
                                <div class="flex justify-between items-center mt-6">
                                    <div>
                                        <span class="block text-pink-600 font-bold">
                                            Rp <?= number_format($layanan['harga_layanan'], 0, ',', '.'); ?>
                                        </span>

                                        <span class="text-[11px] text-gray-400">
                                            Estimasi <?= htmlspecialchars($layanan['durasi_layanan']); ?> menit
                                        </span>
                                    </div>

                                    <button 
                                        type="button"
                                        onclick="addToCart(
                                            <?= (int) $layanan['id_layanan']; ?>,
                                            '<?= htmlspecialchars($layanan['nama_layanan'], ENT_QUOTES); ?>',
                                            <?= (int) $layanan['harga_layanan']; ?>,
                                            <?= (int) $layanan['durasi_layanan']; ?>
                                        )"
                                        class="px-5 py-2.5 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 active:scale-95 transition-all"
                                    >
                                        Pilih
                                    </button>
                                </div>
                            </div>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <!-- Pesan layanan kosong -->
                        <div class="md:col-span-2 bg-white rounded-3xl p-10 text-center border border-dashed border-pink-200">
                            <i class="fa-solid fa-scissors text-4xl text-pink-100 mb-4"></i>
                            <p class="text-sm text-gray-400 font-medium">
                                Belum ada layanan tersedia.
                            </p>
                        </div>

                    <?php endif; ?>
                </div>
            </div>

            <!-- Keranjang booking -->
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-pink-100">

                <!-- Header keranjang -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Keranjang Booking
                        </h3>

                        <p id="cart-item-count" class="text-xs text-pink-600 font-bold">
                            0 layanan dipilih
                        </p>
                    </div>

                    <i class="fa-solid fa-basket-shopping text-2xl text-pink-500"></i>
                </div>

                <!-- Isi keranjang -->
                <div id="cart-items-container" class="space-y-3">
                    <div id="empty-cart-msg" class="text-center py-8 border border-dashed border-pink-100 rounded-2xl text-gray-400">
                        <i class="fa-solid fa-basket-shopping text-3xl mb-3 text-pink-100"></i>
                        <p class="text-sm font-medium">
                            Belum ada layanan dipilih.
                        </p>
                    </div>
                </div>

                <!-- Ringkasan tanggal, jam, pembayaran -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6 pt-6 border-t border-pink-50">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Tanggal
                        </p>

                        <p id="summary-date" class="text-sm font-bold text-gray-800">
                            -
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Jam
                        </p>

                        <p id="summary-time" class="text-sm font-bold text-gray-800">
                            -
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Pembayaran
                        </p>

                        <p class="text-sm font-bold text-gray-800">
                            Cash
                        </p>
                    </div>
                </div>

                <!-- Ringkasan harga dan durasi -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Total Harga
                        </p>

                        <p id="cart-total-price" class="text-2xl font-bold text-pink-600">
                            Rp 0
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            Estimasi Waktu
                        </p>

                        <p id="cart-total-duration" class="text-2xl font-bold text-gray-800">
                            0 Menit
                        </p>
                    </div>

                    <button 
                        id="btn-open-confirm"
                        type="button"
                        onclick="openBookingModal()"
                        disabled
                        class="px-6 py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        Lihat Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>