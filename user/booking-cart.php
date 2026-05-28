<!-- Keranjang booking model checkout Shopee -->
<div id="checkout-cart-bar" class="fixed bottom-0 left-0 right-0 z-[90] bg-white/95 backdrop-blur-md border-t border-pink-100 shadow-[0_-8px_28px_rgba(219,39,119,0.14)]">
    <div class="max-w-6xl mx-auto px-3 py-2">
        <!-- alert succes dan X -->
     
        <!-- Bar checkout kecil -->
        <div class="flex items-center gap-3">

            <!-- Kiri: tanggal dan jam 2 baris -->
            <div class="flex-1 min-w-0">
                <div class="grid grid-cols-1 gap-1">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-14 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Tanggal
                        </span>

                        <span id="summary-date" class="text-xs font-bold text-gray-800 truncate">
                            -
                        </span>
                    </div>

                    <div class="flex items-center gap-2 min-w-0">
                        <span class="w-14 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Jam
                        </span>

                        <span id="summary-time" class="text-xs font-bold text-gray-800 truncate">
                            -
                        </span>
                    </div>
                </div>
            </div>

            <!-- Kanan: total dan estimasi 2 baris -->
            <div class="w-[190px] sm:w-[230px]">
                <div class="grid grid-cols-1 gap-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Total DP
                        </span>

                        <span id="cart-total-price" class="text-sm font-extrabold text-pink-600 whitespace-nowrap">
                            Rp 0
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Estimasi
                        </span>

                        <span id="cart-total-duration" class="text-sm font-bold text-gray-800 whitespace-nowrap">
                            0 Menit
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tombol keranjang -->
            <button
                id="btn-open-confirm"
                type="button"
                onclick="openBookingModal()"
                disabled
                class="relative w-12 h-12 rounded-2xl bg-pink-600 text-white flex items-center justify-center hover:bg-pink-700 active:scale-95 transition-all shadow-lg shadow-pink-100 disabled:opacity-40 disabled:cursor-not-allowed"
                aria-label="Buka keranjang booking"
            >
                <i class="fa-solid fa-basket-shopping text-lg"></i>

                <span id="cart-badge-count" class="absolute -top-2 -right-2 min-w-6 h-6 px-1 rounded-full bg-red-500 border-2 border-white text-white text-[10px] font-extrabold flex items-center justify-center">
                    0
                </span>
            </button>
        </div>

        <!-- Element ini sengaja disembunyikan agar script booking lama tetap bisa jalan -->
        <div class="hidden">
            <div id="cart-items-container"></div>
            <div id="empty-cart-msg"></div>
            <span id="cart-item-count">0 layanan dipilih</span>
        </div>
    </div>
</div>

<!-- Script penghubung keranjang Shopee dengan booking-script.js -->
<script>
    // Mengambil angka jumlah layanan dari text "2 layanan dipilih"
    function getCartCountFromText(text) {
        const match = String(text || '').match(/\d+/);
        return match ? Number(match[0]) : 0;
    }

    // Sinkron badge angka dari cart script lama
    function syncShopeeCartBadge() {
        const cartItemCount = document.getElementById('cart-item-count');
        const badge = document.getElementById('cart-badge-count');

        if (!cartItemCount || !badge) return;

        badge.textContent = getCartCountFromText(cartItemCount.textContent);
    }

    // Sinkron hidden input modal dari variable script booking lama
    function syncShopeeCartHiddenInputs() {
        try {
            const formTanggalBooking = document.getElementById('form-tanggal-booking');
            const formJamMulai = document.getElementById('form-jam-mulai');
            const formLayananTerpilih = document.getElementById('form-layanan-terpilih');
            const formTotalDp = document.getElementById('form-total-dp');

            // Variable cart, selectedBookingDate, selectedBookingTime berasal dari booking-script.js
            if (typeof selectedBookingDate !== 'undefined' && formTanggalBooking) {
                formTanggalBooking.value = selectedBookingDate || '';
            }

            if (typeof selectedBookingTime !== 'undefined' && formJamMulai) {
                formJamMulai.value = selectedBookingTime ? selectedBookingTime + ':00' : '';
            }

            if (typeof cart !== 'undefined' && Array.isArray(cart) && formLayananTerpilih) {
                formLayananTerpilih.value = JSON.stringify(cart.map(item => item.id));
            }

            if (typeof getTotalPrice === 'function' && formTotalDp) {
                formTotalDp.value = getTotalPrice();
            }
        } catch (error) {
            console.warn('Sinkron hidden input keranjang gagal:', error);
        }
    }

    // Membungkus fungsi updateCartUI lama supaya badge ikut update
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof updateCartUI === 'function') {
            const originalUpdateCartUI = updateCartUI;

            updateCartUI = function () {
                originalUpdateCartUI();
                syncShopeeCartBadge();
                syncShopeeCartHiddenInputs();
            };
        }

        // Memaksa sinkron saat awal halaman
        if (typeof updateCartUI === 'function') {
            updateCartUI();
        }

        syncShopeeCartBadge();
        syncShopeeCartHiddenInputs();
    });

    // Sinkron ulang saat user klik tanggal, jam, atau layanan
    document.addEventListener('click', function () {
        setTimeout(function () {
            syncShopeeCartBadge();
            syncShopeeCartHiddenInputs();
        }, 80);
    });

    document.addEventListener('change', function () {
        setTimeout(function () {
            syncShopeeCartBadge();
            syncShopeeCartHiddenInputs();
        }, 80);
    });
</script>
