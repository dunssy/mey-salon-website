<?php global $user; ?>
<!-- Modal konfirmasi booking -->
<div id="booking-modal" class="fixed inset-0 z-[100] hidden">

    <!-- Overlay modal -->
    <div onclick="closeBookingModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Box modal -->
    <div class="relative max-w-2xl mx-auto mt-10 mb-10 bg-white rounded-[2rem] shadow-2xl border border-pink-100 overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header modal -->
        <div class="p-6 border-b border-pink-50 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-gray-800">
                    Konfirmasi Booking
                </h3>

                <p class="text-xs text-gray-400">
                    Pembayaran dilakukan cash setelah admin mengonfirmasi booking.
                </p>
            </div>

            <button 
                type="button"
                onclick="closeBookingModal()" 
                class="w-10 h-10 bg-pink-50 text-pink-600 rounded-full"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form konfirmasi booking -->
        <form action="" method="POST" class="overflow-y-auto p-6 space-y-5">

            <!-- Input tersembunyi -->
            <input type="hidden" name="tanggal_booking" id="form-tanggal-booking">
            <input type="hidden" name="jam_mulai" id="form-jam-mulai">
            <input type="hidden" name="layanan_terpilih" id="form-layanan-terpilih">

            <!-- Data pelanggan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Nama pelanggan -->
                <div class="p-4 bg-pink-50/50 rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Nama
                    </p>

                    <p class="text-sm font-bold text-gray-800">
                        <?= htmlspecialchars($user['nama']); ?>
                    </p>
                </div>

                <!-- No HP pelanggan -->
                <div class="p-4 bg-pink-50/50 rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        No HP
                    </p>

                    <p class="text-sm font-bold text-gray-800">
                        <?= htmlspecialchars($user['no_hp']); ?>
                    </p>
                </div>
            </div>

            <!-- Data jadwal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Tanggal booking -->
                <div class="p-4 bg-white rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Tanggal Booking
                    </p>

                    <p id="modal-date" class="text-sm font-bold text-gray-800">
                        -
                    </p>
                </div>

                <!-- Jam booking -->
                <div class="p-4 bg-white rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Jam Booking
                    </p>

                    <p id="modal-time" class="text-sm font-bold text-gray-800">
                        -
                    </p>
                </div>
            </div>

            <!-- Layanan dipilih -->
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                    Layanan Dipilih
                </p>

                <div id="modal-service-list" class="space-y-3"></div>
            </div>

            <!-- Ringkasan pembayaran -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <!-- Total harga -->
                <div class="p-4 bg-pink-50/60 rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Total Harga
                    </p>

                    <p id="modal-total-price" class="text-lg font-bold text-pink-600">
                        Rp 0
                    </p>
                </div>

                <!-- Total durasi -->
                <div class="p-4 bg-pink-50/60 rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Estimasi
                    </p>

                    <p id="modal-total-duration" class="text-lg font-bold text-gray-800">
                        0 Menit
                    </p>
                </div>

                <!-- Metode pembayaran -->
                <div class="p-4 bg-pink-50/60 rounded-2xl border border-pink-100">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        Pembayaran
                    </p>

                    <p class="text-lg font-bold text-gray-800">
                        Cash
                    </p>
                </div>
            </div>

            <!-- Catatan opsional -->
            <div>
                <label for="catatan" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Catatan Opsional
                </label>

                <textarea 
                    name="catatan" 
                    id="catatan" 
                    rows="3" 
                    placeholder="Contoh: ingin potong rambut model layer..."
                    class="mt-2 w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400 resize-none"
                ></textarea>
            </div>

            <!-- Info pembayaran -->
            <div class="p-4 bg-yellow-50 border border-yellow-100 text-yellow-700 rounded-2xl text-xs leading-relaxed">
                <b>Info:</b> Setelah booking dikirim, silakan tunggu konfirmasi admin. Pembayaran dilakukan secara cash di salon.
            </div>

            <!-- Tombol konfirmasi -->
            <button 
                type="submit" 
                name="konfirmasi_booking"
                class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100"
            >
                Konfirmasi Booking
            </button>
        </form>
    </div>
</div>