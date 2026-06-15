<!-- Keranjang booking model checkout Shopee -->
<div 
    id="checkout-cart-bar" 
    class="fixed left-1/2 bottom-4 z-[90] w-[94%] max-w-4xl -translate-x-1/2 translate-y-8 opacity-0 pointer-events-none transition-all duration-300"
>
    <!-- Container keranjang popup -->
    <div class="bg-white/95 backdrop-blur-md border border-pink-100 rounded-3xl shadow-[0_14px_40px_rgba(219,39,119,0.18)] px-3 sm:px-4 py-3">

        <!-- Label popup keranjang -->
        <div class="flex items-center justify-between gap-3 mb-2">
            <div class="flex items-center gap-2">
                <span class="w-8 h-8 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center">
                    <i class="fa-solid fa-basket-shopping text-sm"></i>
                </span>

                <div>
                    <p class="text-xs font-extrabold text-gray-800">
                        Keranjang Booking
                    </p>

                    <p class="text-[10px] text-gray-400">
                        Muncul setelah customer memilih layanan, tanggal, atau jam.
                    </p>
                </div>
            </div>

            <button
                type="button"
                onclick="hideCheckoutCartPopup()"
                class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 hover:bg-pink-50 hover:text-pink-600 transition"
                aria-label="Tutup keranjang booking"
            >
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>

        <!-- Bar checkout responsive -->
        <div class="grid grid-cols-[1fr_auto] sm:grid-cols-[1fr_auto_auto] items-center gap-2 sm:gap-3">

            <!-- Kiri: tanggal dan jam -->
            <div class="min-w-0">
                <div class="grid grid-cols-1 gap-1">

                    <!-- Ringkasan tanggal -->
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-12 sm:w-14 text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">
                            Tanggal
                        </span>

                        <span id="summary-date" class="text-[11px] sm:text-xs font-bold text-gray-800 truncate">
                            -
                        </span>
                    </div>

                    <!-- Ringkasan jam -->
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-12 sm:w-14 text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider shrink-0">
                            Jam
                        </span>

                        <span id="summary-time" class="text-[11px] sm:text-xs font-bold text-gray-800 truncate">
                            -
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tengah: total dan estimasi -->
            <div class="col-start-1 row-start-2 sm:col-start-auto sm:row-start-auto w-full sm:w-[230px]">
                <div class="grid grid-cols-2 sm:grid-cols-1 gap-2 sm:gap-1">

                    <!-- Total pembayaran -->
                    <div class="flex items-center justify-between gap-2 bg-pink-50/50 sm:bg-transparent rounded-xl px-2 py-1 sm:p-0">
                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Range
                        </span>

                        <span id="cart-total-price" class="text-[11px] sm:text-xs font-extrabold text-pink-600 whitespace-nowrap">
                            Rp 0
                        </span>
                    </div>

                    <!-- Total estimasi -->
                    <div class="flex items-center justify-between gap-2 bg-gray-50 sm:bg-transparent rounded-xl px-2 py-1 sm:p-0">
                        <span class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Estimasi
                        </span>

                        <span id="cart-total-duration" class="text-xs sm:text-sm font-bold text-gray-800 whitespace-nowrap">
                            0 Menit
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kanan: tombol keranjang -->
            <button
                id="btn-open-confirm"
                type="button"
                onclick="openBookingModal()"
                disabled
                class="relative col-start-2 row-span-2 sm:col-start-auto sm:row-span-1 w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-pink-600 text-white flex items-center justify-center hover:bg-pink-700 active:scale-95 transition-all shadow-lg shadow-pink-100 disabled:opacity-40 disabled:cursor-not-allowed"
                aria-label="Buka keranjang booking"
            >
                <!-- Icon keranjang -->
                <i class="fa-solid fa-basket-shopping text-base sm:text-lg"></i>

                <!-- Badge jumlah layanan -->
                <span id="cart-badge-count" class="absolute -top-2 -right-2 min-w-6 h-6 px-1 rounded-full bg-red-500 border-2 border-white text-white text-[10px] font-extrabold flex items-center justify-center">
                    0
                </span>
            </button>
        </div>

        <!-- Element tersembunyi untuk script lama -->
        <div class="hidden">
            <div id="cart-items-container"></div>
            <div id="empty-cart-msg"></div>
            <span id="cart-item-count">0 layanan dipilih</span>
        </div>
    </div>
</div>

<!-- Script penghubung keranjang Shopee dengan booking-script.js -->
<script>
    // Popup keranjang akan tetap terbuka sampai customer menekan tombol tutup
    // Menampilkan popup keranjang
    function showCheckoutCartPopup() {
        const cartBar = document.getElementById('checkout-cart-bar');

        if (!cartBar) return;

        cartBar.classList.remove('translate-y-8', 'opacity-0', 'pointer-events-none');
        cartBar.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
    }

    // Menyembunyikan popup keranjang
    function hideCheckoutCartPopup() {
        const cartBar = document.getElementById('checkout-cart-bar');

        if (!cartBar) return;

        cartBar.classList.add('translate-y-8', 'opacity-0', 'pointer-events-none');
        cartBar.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
    }

    // Menampilkan popup saat customer memilih tanggal, jam, atau layanan
    function triggerCheckoutCartPopup() {
        syncShopeeCartAll();
        showCheckoutCartPopup();
    }

    // Mengambil angka layanan dari teks lama
    function getCartCountFromText(text) {
        const match = String(text || '').match(/\d+/);

        return match ? Number(match[0]) : 0;
    }

    // Mengambil data layanan yang dipilih
    function getShopeeCartItems() {
        if (typeof cart !== 'undefined' && Array.isArray(cart)) {
            return cart;
        }

        if (Array.isArray(window.cart)) {
            return window.cart;
        }

        return [];
    }

    // Mengambil harga minimum item layanan
    function getShopeeItemMinPrice(item) {
        return Number(
            item.price ||
            item.harga ||
            item.harga_min ||
            item.harga_layanan ||
            0
        );
    }

    // Mengambil harga maksimum item layanan
    function getShopeeItemMaxPrice(item) {
        const directMax = Number(
            item.priceMax ||
            item.price_max ||
            item.harga_max ||
            item.max_price ||
            item.harga_maksimal ||
            0
        );

        if (directMax > 0) {
            return directMax;
        }

        const itemId = Number(item.id || item.id_layanan || item.service_id || 0);

        if (itemId > 0 && window.servicePriceRangeMap && window.servicePriceRangeMap[itemId]) {
            const mappedMax = Number(window.servicePriceRangeMap[itemId].max || 0);

            if (mappedMax > 0) {
                return mappedMax;
            }
        }

        return getShopeeItemMinPrice(item);
    }

    // Format range rupiah
    function formatShopeeRangeRupiah(minValue, maxValue) {
        const minPrice = Number(minValue || 0);
        const maxPrice = Number(maxValue || minPrice || 0);

        if (maxPrice > minPrice) {
            return formatShopeeRupiah(minPrice) + ' - ' + formatShopeeRupiah(maxPrice);
        }

        return formatShopeeRupiah(minPrice);
    }

    // Menghitung total harga minimum fallback
    function getShopeeTotalPriceFallback() {
        const items = getShopeeCartItems();

        return items.reduce(function (total, item) {
            return total + getShopeeItemMinPrice(item);
        }, 0);
    }

    // Menghitung total harga maksimum fallback
    function getShopeeTotalMaxPriceFallback() {
        const items = getShopeeCartItems();

        return items.reduce(function (total, item) {
            return total + getShopeeItemMaxPrice(item);
        }, 0);
    }

    // Menghitung total durasi fallback
    function getShopeeTotalDurationFallback() {
        const items = getShopeeCartItems();

        return items.reduce(function (total, item) {
            return total + Number(item.duration || item.durasi || item.durasi_layanan || 0);
        }, 0);
    }

    // Format rupiah untuk keranjang
    function formatShopeeRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(number || 0));
    }

    // Sinkron badge angka keranjang
    function syncShopeeCartBadge() {
        const cartItemCount = document.getElementById('cart-item-count');
        const badge = document.getElementById('cart-badge-count');

        if (!badge) return;

        if (cartItemCount) {
            badge.textContent = getCartCountFromText(cartItemCount.textContent);
            return;
        }

        badge.textContent = getShopeeCartItems().length;
    }

    // Sinkron ringkasan tanggal dan jam
    function syncShopeeCartSchedule() {
        const summaryDate = document.getElementById('summary-date');
        const summaryTime = document.getElementById('summary-time');

        if (typeof selectedBookingDate !== 'undefined' && summaryDate) {
            summaryDate.textContent = selectedBookingDate || '-';
        }

        if (typeof selectedBookingTime !== 'undefined' && summaryTime) {
            summaryTime.textContent = selectedBookingTime || '-';
        }
    }

    // Sinkron total dan estimasi
    function syncShopeeCartTotal() {
        const totalPrice = document.getElementById('cart-total-price');
        const totalDuration = document.getElementById('cart-total-duration');

        const price = typeof getTotalPrice === 'function'
            ? Number(getTotalPrice() || 0)
            : getShopeeTotalPriceFallback();

        const maxPrice = typeof getTotalMaxPrice === 'function'
            ? Number(getTotalMaxPrice() || 0)
            : getShopeeTotalMaxPriceFallback();

        const duration = typeof getTotalDuration === 'function'
            ? Number(getTotalDuration() || 0)
            : getShopeeTotalDurationFallback();

        if (totalPrice) {
            totalPrice.textContent = formatShopeeRangeRupiah(price, maxPrice);
        }

        if (totalDuration) {
            totalDuration.textContent = duration + ' Menit';
        }
    }

    // Sinkron tombol checkout
    function syncShopeeCheckoutButton() {
        const button = document.getElementById('btn-open-confirm');
        const items = getShopeeCartItems();

        const hasService = items.length > 0;
        const hasDate = typeof selectedBookingDate !== 'undefined' && selectedBookingDate;
        const hasTime = typeof selectedBookingTime !== 'undefined' && selectedBookingTime;

        if (button) {
            button.disabled = !(hasService && hasDate && hasTime);
        }
    }

    // Sinkron hidden input modal booking
    function syncShopeeCartHiddenInputs() {
        try {
            const formTanggalBooking = document.getElementById('form-tanggal-booking');
            const formJamMulai = document.getElementById('form-jam-mulai');
            const formLayananTerpilih = document.getElementById('form-layanan-terpilih');
            const formTotalDp = document.getElementById('form-total-dp');

            const items = getShopeeCartItems();

            if (typeof selectedBookingDate !== 'undefined' && formTanggalBooking) {
                formTanggalBooking.value = selectedBookingDate || '';
            }

            if (typeof selectedBookingTime !== 'undefined' && formJamMulai) {
                formJamMulai.value = selectedBookingTime ? selectedBookingTime + ':00' : '';
            }

            if (formLayananTerpilih) {
                formLayananTerpilih.value = JSON.stringify(items.map(function (item) {
                    return Number(item.id || item.id_layanan || item.service_id);
                }).filter(Boolean));
            }

            if (formTotalDp) {
                const inputDp = document.getElementById('input-total-dp-qris');

                // DP tidak diambil dari total harga, tetapi dari input nominal DP customer di modal.
                formTotalDp.value = inputDp ? Number(inputDp.value || 0) : formTotalDp.value;
            }
        } catch (error) {
            console.warn('Sinkron hidden input keranjang gagal:', error);
        }
    }

    // Mengecek apakah customer sudah mulai memilih booking
    function hasCustomerStartedBooking() {
        const items = getShopeeCartItems();

        const hasService = items.length > 0;
        const hasDate = typeof selectedBookingDate !== 'undefined' && selectedBookingDate;
        const hasTime = typeof selectedBookingTime !== 'undefined' && selectedBookingTime;

        return hasService || hasDate || hasTime;
    }

    // Sinkron semua bagian keranjang
    function syncShopeeCartAll() {
        syncShopeeCartBadge();
        syncShopeeCartSchedule();
        syncShopeeCartTotal();
        syncShopeeCheckoutButton();
        syncShopeeCartHiddenInputs();

        if (hasCustomerStartedBooking()) {
            showCheckoutCartPopup();
        }
    }

    // Membungkus fungsi updateCartUI lama
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof updateCartUI === 'function') {
            const originalUpdateCartUI = updateCartUI;

            updateCartUI = function () {
                originalUpdateCartUI();
                syncShopeeCartAll();
            };
        }

        syncShopeeCartAll();

        setInterval(syncShopeeCartAll, 500);
    });

    // Munculkan popup saat customer memilih layanan, tanggal, atau jam
    document.addEventListener('click', function (event) {
        const target = event.target;

        const isServiceChoice = target.closest('.service-card, .service-checkbox');
        const isCalendarChoice = target.closest('#calendar-days button');
        const isTimeChoice = target.closest('#time-slots button');

        setTimeout(function () {
            syncShopeeCartAll();

            if (isServiceChoice || isCalendarChoice || isTimeChoice) {
                showCheckoutCartPopup();
            }
        }, 80);
    });

    // Munculkan popup saat layanan berubah dari checkbox
    document.addEventListener('change', function (event) {
        const isServiceCheckbox = event.target.closest('.service-checkbox');

        setTimeout(function () {
            syncShopeeCartAll();

            if (isServiceCheckbox) {
                showCheckoutCartPopup();
            }
        }, 80);
    });
</script>
