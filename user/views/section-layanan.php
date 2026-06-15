<!-- Section layanan -->
<section id="section-layanan" class="content-section pb-32 md:pb-28">

    <!-- Style layanan responsif -->
    <style>
        /* Area layanan tidak membuat layout melebar */
        #section-layanan .services-area {
            min-width: 0;
        }

        /* Wrapper scroll layanan */
        #section-layanan .services-scroll-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            padding-bottom: 14px;
            -webkit-overflow-scrolling: touch;
        }

        /* Scrollbar layanan */
        #section-layanan .services-scroll-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        /* Track scrollbar */
        #section-layanan .services-scroll-wrapper::-webkit-scrollbar-track {
            background: #fdf2f8;
            border-radius: 999px;
        }

        /* Thumb scrollbar */
        #section-layanan .services-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #f472b6;
            border-radius: 999px;
        }

        /* Grid layanan 2 baris */
        #section-layanan .services-slider-grid {
            display: grid;
            grid-auto-flow: column;
            grid-template-rows: repeat(2, 1fr);
            grid-auto-columns: 245px;
            gap: 14px;
            width: max-content;
            min-width: max-content;
        }

        /* Ukuran tablet */
        @media (min-width: 640px) {
            #section-layanan .services-slider-grid {
                grid-auto-columns: 270px;
                gap: 16px;
            }
        }

        /* Ukuran desktop */
        @media (min-width: 1024px) {
            #section-layanan .services-slider-grid {
                grid-auto-columns: 300px;
                gap: 18px;
            }
        }

        /* Card layanan mobile */
        #section-layanan .service-card {
            min-height: 100%;
        }

        /* Gambar layanan mobile */
        #section-layanan .service-image {
            height: 120px;
        }

        /* Gambar layanan tablet */
        @media (min-width: 640px) {
            #section-layanan .service-image {
                height: 135px;
            }
        }

        /* Gambar layanan desktop */
        @media (min-width: 1024px) {
            #section-layanan .service-image {
                height: 145px;
            }
        }
        /* Grid tanggal kalender */
#calendar-days {
    gap: 6px;
}

/* Style dasar semua tanggal */
.calendar-day {
    width: 100%;
    height: 38px;
    min-height: 38px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    line-height: 1;
    transition: 0.25s;
}

/* Tanggal tersedia */
.calendar-day-available {
    background: #ffffff;
    color: #374151;
    border: 1px solid #fbcfe8;
}

/* Hover */
.calendar-day-available:hover {
    background: #fdf2f8;
}

/* Tanggal hari ini */
.calendar-day-today {
    border: 2px solid #ec4899 !important;
}

/* Tanggal sudah booking = HITAM */
.calendar-day-booked {
    background: #111827 !important;
    color: #ffffff !important;
    border-color: #111827 !important;
}

/* Tanggal dipilih = PINK */
.calendar-day-selected {
    background: #ec4899 !important;
    color: #ffffff !important;
    border-color: #ec4899 !important;
}

/* Tanggal tidak aktif */
.calendar-day-disabled {
    background: #f3f4f6;
    color: #d1d5db;
    cursor: not-allowed;
    border: 1px solid #f3f4f6;
}

/* Kotak kosong kalender */
.calendar-day-empty {
    height: 38px;
    min-height: 38px;
    pointer-events: none;
}

/* Ukuran kalender mobile */
@media (max-width: 639px) {
    .calendar-day,
    .calendar-day-empty {
        height: 34px;
        min-height: 34px;
        border-radius: 10px;
        font-size: 12px;
    }
}

/* Tombol jam booking */
#time-slots .time-slot-button {
    width: 100%;
    min-height: 38px;
    padding: 8px 6px;
    font-size: 12px;
    font-weight: 800;
    border-radius: 12px;
    border: 1px solid #fbcfe8;
    background: #ffffff;
    color: #374151;
    transition: 0.25s;
}

#time-slots .time-slot-button:hover {
    background: #fdf2f8;
}

#time-slots .time-slot-disabled {
    border-color: #f3f4f6 !important;
    background: #f3f4f6 !important;
    color: #d1d5db !important;
    cursor: not-allowed !important;
}

#time-slots .time-slot-booked {
    border-color: #111827 !important;
    background: #111827 !important;
    color: #ffffff !important;
    cursor: not-allowed !important;
}

#time-slots .time-selected {
    background: #ec4899 !important;
    color: #ffffff !important;
    border-color: #ec4899 !important;
}
    </style>

    <!-- Layout booking -->
    <div class="grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1fr)] gap-5 lg:gap-6 items-start">

        <!-- Kalender dan jam booking -->
        <aside class="bg-white rounded-3xl p-4 sm:p-5 lg:p-6 shadow-sm border border-pink-100 lg:sticky lg:top-24">

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
            <div class="grid grid-cols-7 gap-[6px] text-center text-[11px] font-bold text-gray-400 mb-2">
                <span>Min</span>
                <span>Sen</span>
                <span>Sel</span>
                <span>Rab</span>
                <span>Kam</span>
                <span>Jum</span>
                <span>Sab</span>
            </div>

            <!-- Isi tanggal kalender -->
            <div id="calendar-days" class="grid grid-cols-7 gap-[6px]"></div>

            <!-- Keterangan kalender -->
            <div class="mt-5 grid grid-cols-1 gap-2 text-xs text-gray-500">
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <span class="w-3 h-3 rounded-full bg-white border border-pink-200"></span>
                    <span>Tersedia</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-gray-900"></span>
                    <span>Sudah ada booking</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-pink-600"></span>
                    <span>Dipilih</span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-100 border border-red-200"></span>
                    <span>Libur setiap hari Rabu</span>
                </div>
            </div>

            <!-- Pilih jam booking -->
            <div class="mt-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                    Pilih Jam
                </p>

                <p class="text-[10px] text-gray-400 mb-3 leading-relaxed">
                    Jam otomatis nonaktif jika bentrok dengan booking lain sesuai estimasi waktu layanan.
                </p>

                <div id="time-slots" class="grid grid-cols-3 gap-2"></div>
            </div>
        </aside>

        <!-- Area kanan layanan -->
        <div class="services-area space-y-4">

            <!-- Header kecil layanan -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Pilih Layanan
                    </h3>

                    <p class="text-xs text-gray-400">
                        Centang layanan, lalu klik ikon keranjang bawah untuk checkout.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button 
                        type="button"
                        onclick="scrollServiceLanding(-1)"
                        aria-label="Geser layanan ke kiri"
                        class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 hover:bg-pink-100 transition"
                    >
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <button 
                        type="button"
                        onclick="scrollServiceLanding(1)"
                        aria-label="Geser layanan ke kanan"
                        class="w-10 h-10 rounded-xl bg-pink-600 text-white hover:bg-pink-700 transition"
                    >
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>


<script>
    // Menyimpan range harga layanan agar modal bisa membaca harga minimal dan maksimal
    window.servicePriceRangeMap = window.servicePriceRangeMap || {};

    <?php if (isset($query_layanan) && $query_layanan instanceof mysqli_result) : ?>
        <?php mysqli_data_seek($query_layanan, 0); ?>

        <?php while ($range_layanan = mysqli_fetch_assoc($query_layanan)) : ?>
            window.servicePriceRangeMap[<?= (int) $range_layanan['id_layanan']; ?>] = {
                min: <?= (int) ($range_layanan['harga_min'] ?? 0); ?>,
                max: <?= (int) (!empty($range_layanan['harga_max']) ? $range_layanan['harga_max'] : ($range_layanan['harga_min'] ?? 0)); ?>
            };
        <?php endwhile; ?>

        <?php mysqli_data_seek($query_layanan, 0); ?>
    <?php endif; ?>
</script>

<!-- Slider layanan 2 baris -->
            <div id="services-scroll-wrapper" class="services-scroll-wrapper">
                <div class="services-slider-grid">

                    <?php global $query_layanan; ?>

                    <?php if (mysqli_num_rows($query_layanan) > 0) : ?>

                        <?php while ($layanan = mysqli_fetch_assoc($query_layanan)) : ?>
                            <?php
                            // Mengambil gambar layanan dari DB final
                            $gambar_layanan = "../layout/images/mey-salon.png";

                            // Mengambil nama file gambar dari database
                            if (!empty($layanan['gambar_layanan'])) {
                                $gambar_layanan = "../layout/images/" . $layanan['gambar_layanan'];
                            } elseif (!empty($layanan['foto_layanan'])) {
                                $gambar_layanan = "../layout/images/" . $layanan['foto_layanan'];
                            }

                            // Jika gambar berasal dari upload admin, ambil dari ../uploads/layanan.
                            // Jika gambar berasal dari asset bawaan, ambil dari ../layout/images.
                            if (!empty($layanan['gambar_layanan'])) {
                                $gambar_upload = "../uploads/layanan/" . $layanan['gambar_layanan'];
                                $gambar_asset = "../layout/images/" . $layanan['gambar_layanan'];

                                if (file_exists($gambar_upload)) {
                                    $gambar_layanan = $gambar_upload;
                                } elseif (file_exists($gambar_asset)) {
                                    $gambar_layanan = $gambar_asset;
                                } else {
                                    $gambar_layanan = $gambar_asset;
                                }
                            }

                            // Harga min dan max sesuai DB final
                            $harga_min = isset($layanan['harga_min']) ? (int) $layanan['harga_min'] : 0;
                            $harga_max = isset($layanan['harga_max']) ? (int) $layanan['harga_max'] : 0;
                            ?>

                            <!-- Card layanan -->
                            <label 
                                id="service-card-<?= (int) $layanan['id_layanan']; ?>"
                                class="service-card cursor-pointer bg-white rounded-[1.5rem] sm:rounded-[1.8rem] p-3 sm:p-4 shadow-sm border border-pink-100 hover:border-pink-300 hover:shadow-md transition-all"
                            >

                                <!-- Foto layanan -->
                                <div class="relative">
                                    <img
                                        src="<?= htmlspecialchars($gambar_layanan); ?>"
                                        alt="<?= htmlspecialchars($layanan['nama_layanan']); ?>"
                                        class="service-image w-full object-cover rounded-2xl border border-pink-100 bg-pink-50"
                                    >

                                    <!-- Input checkbox yang langsung memakai addToCart/removeFromCart dari booking-script.js -->
                                    <input
                                        type="checkbox"
                                        class="service-checkbox peer sr-only"
                                        data-id="<?= (int) $layanan['id_layanan']; ?>"
                                        data-name="<?= htmlspecialchars($layanan['nama_layanan'], ENT_QUOTES); ?>"
                                        data-price="<?= $harga_min; ?>"
                                        data-price-min="<?= $harga_min; ?>"
                                        data-price-max="<?= $harga_max > 0 ? $harga_max : $harga_min; ?>"
                                        data-duration="<?= (int) $layanan['durasi_layanan']; ?>"
                                        onchange="toggleServiceToCartFromScript(this)"
                                    >

                                    <!-- Kotak checklist -->
                                    <div class="absolute top-2.5 right-2.5 w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white border-2 border-pink-200 flex items-center justify-center text-transparent shadow-sm peer-checked:bg-pink-600 peer-checked:border-pink-600 peer-checked:text-white transition-all">
                                        <i class="fa-solid fa-check text-sm"></i>
                                    </div>

                                    <!-- Label harga mulai -->
                                    <?php if (!empty($harga_max)) : ?>
                                        <div class="absolute left-2.5 top-2.5 px-2.5 py-1 rounded-full bg-white/90 backdrop-blur text-[10px] font-bold text-pink-600">
                                            Mulai Rp <?= number_format($harga_min, 0, ',', '.'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Isi card -->
                                <div class="mt-4">
                                    <h4 class="font-bold text-sm sm:text-base text-gray-800 leading-snug line-clamp-2 min-h-[40px] sm:min-h-[44px]">
                                        <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-2">
                                        Estimasi <?= (int) $layanan['durasi_layanan']; ?> menit
                                    </p>
                                </div>

                                <!-- Harga dan status -->
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 sm:gap-3">
                                    <div>
                                        <p class="text-pink-600 font-bold leading-tight">
                                            <?php if (!empty($harga_max)) : ?>
                                                Rp <?= number_format($harga_min, 0, ',', '.'); ?> - 
                                                Rp <?= number_format($harga_max, 0, ',', '.'); ?>
                                            <?php else : ?>
                                                Rp <?= number_format($harga_min, 0, ',', '.'); ?>
                                            <?php endif; ?>
                                        </p>

                                        <?php if (!empty($layanan['keterangan_harga'])) : ?>
                                            <p class="text-[10px] text-gray-400 mt-1 line-clamp-1">
                                                <?= htmlspecialchars($layanan['keterangan_harga']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <span class="service-status-label text-[11px] font-bold text-gray-400 whitespace-nowrap">
                                        Belum
                                    </span>
                                </div>
                            </label>

                        <?php endwhile; ?>

                    <?php else : ?>

                        <!-- Pesan layanan kosong -->
                        <div class="bg-white rounded-3xl p-10 text-center border border-dashed border-pink-200">
                            <i class="fa-solid fa-scissors text-4xl text-pink-100 mb-4"></i>
                            <p class="text-sm text-gray-400 font-medium">
                                Belum ada layanan tersedia.
                            </p>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script kecil untuk menghubungkan checkbox ke booking-script.js -->
<script>
    // Scroll layanan dengan tombol panah
    function scrollServiceLanding(direction) {
        const wrapper = document.getElementById('services-scroll-wrapper');

        if (!wrapper) return;

        wrapper.scrollBy({
            left: direction * (window.innerWidth < 640 ? 260 : 650),
            behavior: 'smooth'
        });
    }

    // Mengubah tampilan card layanan
    function updateServiceCardFromScript(checkbox) {
        const card = checkbox.closest('.service-card');
        const label = card ? card.querySelector('.service-status-label') : null;

        if (!card || !label) return;

        if (checkbox.checked) {
            card.classList.add('ring-2', 'ring-pink-300', 'bg-pink-50/40');
            label.textContent = 'Dipilih';
            label.classList.remove('text-gray-400');
            label.classList.add('text-pink-600');
        } else {
            card.classList.remove('ring-2', 'ring-pink-300', 'bg-pink-50/40');
            label.textContent = 'Belum';
            label.classList.add('text-gray-400');
            label.classList.remove('text-pink-600');
        }
    }

    // Fungsi utama: memakai addToCart/removeFromCart dari booking-script.js
    function toggleServiceToCartFromScript(checkbox) {
        const id = Number(checkbox.dataset.id);
        const name = checkbox.dataset.name;
        const price = Number(checkbox.dataset.price || 0);
        const priceMax = Number(checkbox.dataset.priceMax || checkbox.dataset.price || 0);
        const duration = Number(checkbox.dataset.duration || 0);

        if (checkbox.checked) {
            if (typeof addToCart === 'function') {
                addToCart(id, name, price, duration, priceMax);
            } else {
                alert('Fungsi addToCart belum ditemukan. Pastikan booking-script.js sudah dipanggil.');
                checkbox.checked = false;
            }
        } else {
            if (typeof removeFromCart === 'function') {
                removeFromCart(id);
            }
        }

        updateServiceCardFromScript(checkbox);
    }

    // Sinkron checkbox saat layanan dihapus dari keranjang lewat script lama
    const originalRemoveFromCartWatcher = setInterval(function () {
        if (typeof cart === 'undefined' || !Array.isArray(cart)) return;

        document.querySelectorAll('.service-checkbox').forEach(function (checkbox) {
            const id = Number(checkbox.dataset.id);
            const exists = cart.some(function (item) {
                return Number(item.id) === id;
            });

            checkbox.checked = exists;
            updateServiceCardFromScript(checkbox);
        });
    }, 300);

    // Scroll mouse vertical menjadi horizontal di area layanan
    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('services-scroll-wrapper');

        if (wrapper) {
            wrapper.addEventListener('wheel', function (event) {
                if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
                    event.preventDefault();
                    wrapper.scrollLeft += event.deltaY;
                }
            }, { passive: false });
        }
    });

    // Mengambil tanggal dari tombol kalender
    function getDateFromCalendarButtonWednesday(button) {
        const directDate =
            button.dataset.date ||
            button.dataset.fullDate ||
            button.dataset.tanggal ||
            button.dataset.value ||
            button.value ||
            '';

        if (directDate && /^\d{4}-\d{2}-\d{2}$/.test(directDate)) {
            return directDate;
        }

        const dayMatch = button.textContent.trim().match(/\d+/);
        const monthTitle = document.getElementById('calendar-month-title')?.textContent.trim() || '';

        if (!dayMatch || !monthTitle) return '';

        const monthMap = {
            'januari': '01',
            'februari': '02',
            'maret': '03',
            'april': '04',
            'mei': '05',
            'juni': '06',
            'juli': '07',
            'agustus': '08',
            'september': '09',
            'oktober': '10',
            'november': '11',
            'desember': '12'
        };

        const parts = monthTitle.split(/\s+/);
        const month = monthMap[String(parts[0] || '').toLowerCase()];
        const year = parts[1];
        const day = String(dayMatch[0]).padStart(2, '0');

        if (!month || !year) return '';

        return `${year}-${month}-${day}`;
    }

    // Mengecek apakah tanggal adalah hari Rabu
    function isWednesdayClosed(dateValue) {
        if (!dateValue) return false;

        const date = new Date(dateValue + 'T00:00:00');

        if (isNaN(date.getTime())) return false;

        return date.getDay() === 3;
    }

    // Menandai dan menonaktifkan hari Rabu di kalender
    function disableWednesdayCalendar() {
        const buttons = document.querySelectorAll('#calendar-days button');

        buttons.forEach(function (button) {
            const dateValue = getDateFromCalendarButtonWednesday(button);

            if (!dateValue) return;

            if (isWednesdayClosed(dateValue)) {
                button.disabled = true;
                button.dataset.closed = 'wednesday';
                button.title = 'Salon libur setiap hari Rabu';
                button.classList.add('calendar-day-disabled', 'line-through');
            }
        });
    }

    // Mengosongkan jam jika tanggal Rabu terlanjur dipilih
    function clearTimeIfWednesdaySelected() {
        if (typeof selectedBookingDate !== 'undefined' && isWednesdayClosed(selectedBookingDate)) {
            selectedBookingDate = '';
            selectedBookingTime = '';

            const timeSlots = document.getElementById('time-slots');

            if (timeSlots) {
                timeSlots.innerHTML = `
                    <div class="col-span-3 p-4 bg-red-50 text-red-500 text-xs font-bold rounded-2xl text-center border border-red-100">
                        Salon libur setiap hari Rabu.
                    </div>
                `;
            }

            alert('Salon libur setiap hari Rabu. Silakan pilih tanggal lain.');
        }
    }

    // Mencegah klik tanggal Rabu
    document.addEventListener('click', function (event) {
        const dateButton = event.target.closest('#calendar-days button');

        if (!dateButton) return;

        const dateValue = getDateFromCalendarButtonWednesday(dateButton);

        if (isWednesdayClosed(dateValue)) {
            event.preventDefault();
            event.stopImmediatePropagation();
            alert('Salon libur setiap hari Rabu. Silakan pilih tanggal lain.');
        }
    }, true);

    // Observer untuk kalender yang dibuat ulang oleh booking-script.js
    document.addEventListener('DOMContentLoaded', function () {
        const calendarDays = document.getElementById('calendar-days');

        if (!calendarDays) return;

        disableWednesdayCalendar();

        const observer = new MutationObserver(function () {
            disableWednesdayCalendar();
            clearTimeIfWednesdaySelected();
        });

        observer.observe(calendarDays, {
            childList: true,
            subtree: true
        });
    });

</script>
