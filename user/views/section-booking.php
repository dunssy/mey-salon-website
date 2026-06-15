<?php
global $query_booking_user;
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

        <?php if (mysqli_num_rows($query_booking_user) > 0) : ?>

            <?php while ($booking_user = mysqli_fetch_assoc($query_booking_user)) : ?>

                <!-- Card booking -->
                <div class="bg-white rounded-3xl border border-pink-100 shadow-sm p-6">

                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">

                        <!-- Info booking -->
                        <div class="flex-1">
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

                            <?php if ($booking_user['status_booking'] == 'Pending' && !empty($booking_user['tanggal_saran']) && !empty($booking_user['jam_saran'])) : ?>

                                <!-- Box saran jadwal admin -->
                                <div class="mt-5 p-4 bg-orange-50 border border-orange-100 rounded-2xl">

                                    <p class="text-[11px] font-bold text-orange-700 uppercase tracking-widest mb-3">
                                        Saran Jadwal dari Admin
                                    </p>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                                Tanggal Saran
                                            </p>

                                            <p class="text-sm font-bold text-gray-800 mt-1">
                                                <?= date('d M Y', strtotime($booking_user['tanggal_saran'])); ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                                Jam Saran
                                            </p>

                                            <p class="text-sm font-bold text-gray-800 mt-1">
                                                <?= substr($booking_user['jam_saran'], 0, 5); ?>
                                            </p>
                                        </div>
                                    </div>

                                    <?php if (!empty($booking_user['catatan_admin'])) : ?>
                                        <div class="mt-3">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">
                                                Catatan Admin
                                            </p>

                                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                                <?= htmlspecialchars($booking_user['catatan_admin']); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Aksi saran jadwal -->
                                    <div class="flex flex-col sm:flex-row gap-2 mt-4">

                                        <!-- Tombol terima saran -->
                                        <form action="" method="POST">
                                            <input 
                                                type="hidden" 
                                                name="id_booking" 
                                                value="<?= (int) $booking_user['id_booking']; ?>"
                                            >

                                            <button 
                                                type="submit" 
                                                name="terima_saran_booking"
                                                onclick="return confirm('Terima saran jadwal dari admin?')"
                                                class="w-full sm:w-auto px-4 py-2 bg-green-500 text-white text-xs font-bold rounded-xl hover:bg-green-600 transition"
                                            >
                                                <i class="fa-solid fa-check mr-1"></i>
                                                Saya Bersedia
                                            </button>
                                        </form>

                                        <!-- Tombol batal booking -->
                                        <form action="" method="POST">
                                            <input 
                                                type="hidden" 
                                                name="id_booking" 
                                                value="<?= (int) $booking_user['id_booking']; ?>"
                                            >

                                            <button 
                                                type="submit" 
                                                name="batal_booking"
                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')"
                                                class="w-full sm:w-auto px-4 py-2 bg-red-50 text-red-600 text-xs font-bold rounded-xl hover:bg-red-100 transition"
                                            >
                                                <i class="fa-solid fa-xmark mr-1"></i>
                                                Tidak, Batalkan
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            <?php endif; ?>
                        </div>

                        <!-- Ringkasan booking -->
                        <div class="w-full md:w-56 text-left md:text-right">

                            <div class="p-4 bg-pink-50/60 border border-pink-100 rounded-2xl">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                    DP Dibayar
                                </p>

                                <p class="text-sm font-bold text-pink-600 mt-1">
                                    Rp <?= number_format($booking_user['total_dp'] ?? 0, 0, ',', '.'); ?>
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    Estimasi <?= (int) $booking_user['total_durasi']; ?> menit
                                </p>
                            </div>

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
                                        value="<?= (int) $booking_user['id_booking']; ?>"
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

                                <?php if (empty($booking_user['tanggal_saran']) || empty($booking_user['jam_saran'])) : ?>

                                    <!-- Tombol batal jika belum ada saran jadwal -->
                                    <form action="" method="POST" class="mt-3">
                                        <input 
                                            type="hidden" 
                                            name="id_booking" 
                                            value="<?= (int) $booking_user['id_booking']; ?>"
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

                                <?php endif; ?>

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

                            <?php if ($booking_user['status_booking'] == 'Done') : ?>
                                <div class="mt-3">
                                    <a
                                        href="invoice.php?id_booking=<?= (int) $booking_user['id_booking']; ?>"
                                        target="_blank"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-pink-600 text-white text-xs font-bold rounded-xl hover:bg-pink-700 transition"
                                    >
                                        <i class="fa-solid fa-file-invoice"></i>
                                        <span>Invoice</span>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <p class="text-[10px] text-gray-400 mt-2">
                                Pembayaran DP: QRIS
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