<!-- Keranjang booking model checkout Shopee -->
<div 
    id="checkout-cart-bar" 
    class="fixed bottom-0 left-0 right-0 z-[90] bg-white/95 backdrop-blur-md border-t border-pink-100 shadow-[0_-8px_28px_rgba(219,39,119,0.14)]"
>
    <!-- Container keranjang -->
    <div class="max-w-6xl mx-auto px-2 sm:px-3 py-2">

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
                            Total
                        </span>

                        <span id="cart-total-price" class="text-xs sm:text-sm font-extrabold text-pink-600 whitespace-nowrap">
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

    // Menghitung total harga fallback
    function getShopeeTotalPriceFallback() {
        const items = getShopeeCartItems();

        return items.reduce(function (total, item) {
            return total + Number(item.price || item.harga || item.harga_min || item.harga_layanan || 0);
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

        const duration = typeof getTotalDuration === 'function'
            ? Number(getTotalDuration() || 0)
            : getShopeeTotalDurationFallback();

        if (totalPrice) {
            totalPrice.textContent = formatShopeeRupiah(price);
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
                const total = typeof getTotalPrice === 'function'
                    ? Number(getTotalPrice() || 0)
                    : getShopeeTotalPriceFallback();

                formTotalDp.value = total;
            }
        } catch (error) {
            console.warn('Sinkron hidden input keranjang gagal:', error);
        }
    }

    // Sinkron semua bagian keranjang
    function syncShopeeCartAll() {
        syncShopeeCartBadge();
        syncShopeeCartSchedule();
        syncShopeeCartTotal();
        syncShopeeCheckoutButton();
        syncShopeeCartHiddenInputs();
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

    // Sinkron ulang saat klik
    document.addEventListener('click', function () {
        setTimeout(syncShopeeCartAll, 80);
    });

    // Sinkron ulang saat perubahan input
    document.addEventListener('change', function () {
        setTimeout(syncShopeeCartAll, 80);
    });
</script>
