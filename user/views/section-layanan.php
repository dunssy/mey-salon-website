<!-- Section layanan -->
<section id="section-layanan" class="content-section pb-24">

    <!-- Style layanan 2 baris horizontal -->
    <style>
        #section-layanan .services-area {
            min-width: 0;
        }

        #section-layanan .services-scroll-wrapper {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            padding-bottom: 14px;
        }

        #section-layanan .services-scroll-wrapper::-webkit-scrollbar {
            height: 9px;
        }

        #section-layanan .services-scroll-wrapper::-webkit-scrollbar-track {
            background: #fdf2f8;
            border-radius: 999px;
        }

        #section-layanan .services-scroll-wrapper::-webkit-scrollbar-thumb {
            background: #f472b6;
            border-radius: 999px;
        }

        #section-layanan .services-slider-grid {
            display: grid;
            grid-auto-flow: column;
            grid-template-rows: repeat(2, 1fr);
            grid-auto-columns: 280px;
            gap: 18px;
            width: max-content;
            min-width: max-content;
        }

        @media (min-width: 768px) {
            #section-layanan .services-slider-grid {
                grid-auto-columns: 310px;
            }
        }
    </style>

    <!-- Layout booking -->
    <div class="grid grid-cols-1 lg:grid-cols-[340px_minmax(0,1fr)] gap-6 items-start">

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

            <!-- Pilih jam booking -->
            <div class="mt-6">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">
                    Pilih Jam
                </p>

                <div id="time-slots" class="grid grid-cols-3 gap-2"></div>
            </div>
        </aside>

        <!-- Area kanan layanan -->
        <div class="services-area space-y-4">

            <!-- Header kecil layanan -->
            <div class="flex items-center justify-between gap-3">
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
                        class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 hover:bg-pink-100 transition"
                    >
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>

                    <button 
                        type="button"
                        onclick="scrollServiceLanding(1)"
                        class="w-10 h-10 rounded-xl bg-pink-600 text-white hover:bg-pink-700 transition"
                    >
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <!-- Slider layanan 2 baris -->
            <div id="services-scroll-wrapper" class="services-scroll-wrapper">
                <div class="services-slider-grid">

                    <?php global $query_layanan; ?>

                    <?php if (mysqli_num_rows($query_layanan) > 0) : ?>

                        <?php while ($layanan = mysqli_fetch_assoc($query_layanan)) : ?>
                            <?php
                                // Mengambil gambar layanan dari DB final
                                $gambar_layanan = "../layout/images/mey-salon.png";

                                if (!empty($layanan['gambar_layanan'])) {
                                    $gambar_layanan = "../uploads/layanan/" . $layanan['gambar_layanan'];
                                } elseif (!empty($layanan['foto_layanan'])) {
                                    $gambar_layanan = "../uploads/layanan/" . $layanan['foto_layanan'];
                                }

                                // Harga min dan max sesuai DB final
                                $harga_min = isset($layanan['harga_min']) ? (int) $layanan['harga_min'] : 0;
                                $harga_max = isset($layanan['harga_max']) ? (int) $layanan['harga_max'] : 0;
                            ?>

                            <!-- Card layanan -->
                            <label 
                                id="service-card-<?= (int) $layanan['id_layanan']; ?>"
                                class="service-card cursor-pointer bg-white rounded-[1.8rem] p-4 shadow-sm border border-pink-100 hover:border-pink-300 hover:shadow-md transition-all"
                            >

                                <!-- Foto layanan -->
                                <div class="relative">
                                    <img
                                        src="<?= htmlspecialchars($gambar_layanan); ?>"
                                        alt="<?= htmlspecialchars($layanan['nama_layanan']); ?>"
                                        class="w-full h-36 object-cover rounded-2xl border border-pink-100 bg-pink-50"
                                    >

                                    <!-- Input checkbox yang langsung memakai addToCart/removeFromCart dari booking-script.js -->
                                    <input
                                        type="checkbox"
                                        class="service-checkbox peer sr-only"
                                        data-id="<?= (int) $layanan['id_layanan']; ?>"
                                        data-name="<?= htmlspecialchars($layanan['nama_layanan'], ENT_QUOTES); ?>"
                                        data-price="<?= $harga_min; ?>"
                                        data-duration="<?= (int) $layanan['durasi_layanan']; ?>"
                                        onchange="toggleServiceToCartFromScript(this)"
                                    >

                                    <!-- Kotak checklist -->
                                    <div class="absolute top-3 right-3 w-10 h-10 rounded-xl bg-white border-2 border-pink-200 flex items-center justify-center text-transparent shadow-sm peer-checked:bg-pink-600 peer-checked:border-pink-600 peer-checked:text-white transition-all">
                                        <i class="fa-solid fa-check text-sm"></i>
                                    </div>

                                    <!-- Label range harga -->
                                    <?php if (!empty($harga_max)) : ?>
                                        <div class="absolute left-3 top-3 px-3 py-1 rounded-full bg-white/90 backdrop-blur text-[10px] font-bold text-pink-600">
                                            Mulai Rp <?= number_format($harga_min, 0, ',', '.'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Isi card -->
                                <div class="mt-4">
                                    <h4 class="font-bold text-base text-gray-800 leading-snug line-clamp-2 min-h-[44px]">
                                        <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-2">
                                        Estimasi <?= (int) $layanan['durasi_layanan']; ?> menit
                                    </p>
                                </div>

                                <!-- Harga dan status -->
                                <div class="mt-4 flex items-end justify-between gap-3">
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
            left: direction * 650,
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
        const duration = Number(checkbox.dataset.duration || 0);

        if (checkbox.checked) {
            if (typeof addToCart === 'function') {
                addToCart(id, name, price, duration);
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
</script>
