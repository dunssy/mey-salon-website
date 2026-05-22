<?php
global $booking_user;
?>
<!-- Section booking saya -->
<section id="section-booking" class="content-section hidden">

    <!-- Header halaman -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">
            Booking Saya
        </h2>

        <p class="text-sm text-gray-500">
            Lacak status reservasi Anda.
        </p>
    </div>

    <!-- List booking user -->
    <div class="space-y-4">
    <?php global $query_booking_user; ?>
        <?php if (mysqli_num_rows($query_booking_user) > 0) : ?>

            <?php while ($booking_user = mysqli_fetch_assoc($query_booking_user)) : ?>

                <!-- Card booking -->
                <div class="bg-white rounded-3xl border border-pink-100 shadow-sm p-6">

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Info booking -->
                        <div>
                            <p class="text-[11px] font-bold text-pink-600 uppercase tracking-widest">
                                Booking #<?= htmlspecialchars($booking_user['id_booking']); ?>
                            </p>

                            <h3 class="text-lg font-bold text-gray-800 mt-1">
                                <?= date('d M Y', strtotime($booking_user['tanggal_booking'])); ?>,
                                <?= substr($booking_user['jam_mulai'], 0, 5); ?> -
                                <?= substr($booking_user['jam_selesai'], 0, 5); ?>
                            </h3>

                            <p class="text-sm text-gray-500 mt-2">
                                <?= htmlspecialchars($booking_user['nama_layanan']); ?>
                            </p>

                            <?php if (!empty($booking_user['catatan'])) : ?>
                                <p class="text-xs text-gray-400 mt-2 italic">
                                    Catatan: <?= htmlspecialchars($booking_user['catatan']); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Ringkasan booking -->
                        <div class="text-left md:text-right">
                            <p class="text-sm font-bold text-gray-800">
                                Rp <?= number_format($booking_user['total_harga'], 0, ',', '.'); ?>
                            </p>

                            <p class="text-xs text-gray-400">
                                <?= (int) $booking_user['total_durasi']; ?> menit
                            </p>

                            <?php if ($booking_user['status_booking'] == 'Waiting') : ?>

                                <!-- Status waiting -->
                                <span class="inline-block mt-2 px-3 py-1 bg-yellow-50 text-yellow-700 text-[10px] font-bold rounded-full uppercase">
                                    Waiting
                                </span>

                                <!-- Tombol batal booking -->
                                <form action="" method="POST" class="mt-3">
                                    <input 
                                        type="hidden" 
                                        name="id_booking" 
                                        value="<?= htmlspecialchars($booking_user['id_booking']); ?>"
                                    >

                                    <button 
                                        type="submit" 
                                        name="batal_booking"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')"
                                        class="px-3 py-2 bg-red-50 text-red-600 text-[11px] font-bold rounded-xl hover:bg-red-100 transition"
                                    >
                                        Batalkan Booking
                                    </button>
                                </form>

                            <?php elseif ($booking_user['status_booking'] == 'Pending') : ?>

                                <!-- Status pending -->
                                <span class="inline-block mt-2 px-3 py-1 bg-orange-50 text-orange-700 text-[10px] font-bold rounded-full uppercase">
                                    Pending
                                </span>

                                <!-- Tombol batal booking -->
                                <form action="" method="POST" class="mt-3">
                                    <input 
                                        type="hidden" 
                                        name="id_booking" 
                                        value="<?= htmlspecialchars($booking_user['id_booking']); ?>"
                                    >

                                    <button 
                                        type="submit" 
                                        name="batal_booking"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')"
                                        class="px-3 py-2 bg-red-50 text-red-600 text-[11px] font-bold rounded-xl hover:bg-red-100 transition"
                                    >
                                        Batalkan Booking
                                    </button>
                                </form>

                            <?php elseif ($booking_user['status_booking'] == 'On-going') : ?>

                                <!-- Status on-going -->
                                <span class="inline-block mt-2 px-3 py-1 bg-blue-50 text-blue-700 text-[10px] font-bold rounded-full uppercase">
                                    On-going
                                </span>

                            <?php elseif ($booking_user['status_booking'] == 'Cancel') : ?>

                                <!-- Status cancel -->
                                <span class="inline-block mt-2 px-3 py-1 bg-red-50 text-red-700 text-[10px] font-bold rounded-full uppercase">
                                    Cancel
                                </span>

                            <?php elseif ($booking_user['status_booking'] == 'Done') : ?>

                                <!-- Status done -->
                                <span class="inline-block mt-2 px-3 py-1 bg-green-50 text-green-700 text-[10px] font-bold rounded-full uppercase">
                                    Done
                                </span>

                            <?php else : ?>

                                <!-- Status lainnya -->
                                <span class="inline-block mt-2 px-3 py-1 bg-gray-50 text-gray-700 text-[10px] font-bold rounded-full uppercase">
                                    <?= htmlspecialchars($booking_user['status_booking']); ?>
                                </span>

                            <?php endif; ?>

                            <p class="text-[10px] text-gray-400 mt-2">
                                Pembayaran: Cash
                            </p>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

        <?php else : ?>

            <!-- Pesan booking kosong -->
            <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-pink-200">
                <i class="fa-solid fa-calendar-xmark text-4xl text-pink-100 mb-4"></i>

                <p class="text-sm text-gray-400 font-medium">
                    Belum ada riwayat booking.
                </p>
            </div>

        <?php endif; ?>
    </div>
</section>