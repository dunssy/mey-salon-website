<!-- Toast notifikasi -->
<div 
    id="toast" 
    class="fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-gray-800 text-white text-xs font-bold rounded-full shadow-2xl opacity-0 transition-all z-[200] pointer-events-none"
></div>

<!-- Script utama user portal -->
<script>
    // Menyimpan data keranjang
    let cart = [];

    // Menyimpan data booking sementara
    let bookings = [];

    // Membuka dan menutup menu mobile
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        if (!menu) return;

        menu.classList.toggle('hidden');

        if (menuIcon) {
            menuIcon.className = menu.classList.contains('hidden')
                ? 'fa-solid fa-bars-staggered text-2xl'
                : 'fa-solid fa-xmark text-2xl';
        }
    }

    // Menampilkan section yang dipilih
    function showSection(sectionName) {
        const sections = document.querySelectorAll('.content-section');
        const targetSection = document.getElementById(`section-${sectionName}`);
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');

        sections.forEach(section => {
            section.classList.add('hidden');
        });

        if (targetSection) {
            targetSection.classList.remove('hidden');
        }

        if (mobileMenu) {
            mobileMenu.classList.add('hidden');
        }

        if (menuIcon) {
            menuIcon.className = 'fa-solid fa-bars-staggered text-2xl';
        }

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Membuka dan menutup keranjang
    function toggleCart() {
        const drawer = document.getElementById('cart-drawer');
        const overlay = document.getElementById('cart-overlay');

        if (!drawer || !overlay) return;

        drawer.classList.toggle('sheet-hidden');
        drawer.classList.toggle('sheet-visible');
        overlay.classList.toggle('hidden');
    }

    // Menambahkan layanan ke keranjang
    function addToCart(id, name, price) {
        const itemExists = cart.find(item => item.id === id);

        if (itemExists) {
            showToast('Layanan sudah ada di keranjang');
            return;
        }

        cart.push({
            id: id,
            name: name,
            price: price
        });

        updateCartUI();
        showToast('Layanan ditambahkan ke keranjang');
    }

    // Menghapus layanan dari keranjang
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);

        updateCartUI();
        showToast('Layanan dihapus dari keranjang');
    }

    // Format angka menjadi rupiah
    function formatRupiah(value) {
        return `Rp ${value.toLocaleString('id-ID')}`;
    }

    // Memperbarui tampilan keranjang
    function updateCartUI() {
        const container = document.getElementById('cart-items-container');
        const countNav = document.getElementById('cart-count-nav');
        const countMobile = document.getElementById('cart-count-mobile');
        const totalElement = document.getElementById('cart-total-price');
        const checkoutButton = document.getElementById('btn-checkout');
        const cartSubtitle = document.getElementById('cart-item-count');
        const emptyMessage = document.getElementById('empty-cart-msg');

        if (!container || !emptyMessage) return;

        // Menghapus item lama dari keranjang
        container.querySelectorAll('.cart-item').forEach(item => item.remove());

        // Menghitung total harga
        const totalPrice = cart.reduce((total, item) => total + item.price, 0);

        // Menampilkan item keranjang
        cart.forEach(item => {
            const itemElement = document.createElement('div');

            itemElement.className = 'cart-item flex items-center justify-between bg-pink-50/40 p-4 rounded-2xl border border-pink-50 animate-fade-in';

            itemElement.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-pink-600 shadow-sm">
                        <i class="fa-solid fa-sparkles text-xs"></i>
                    </div>

                    <div>
                        <p class="text-sm font-bold text-gray-800">${item.name}</p>
                        <p class="text-[11px] text-pink-600 font-bold">${formatRupiah(item.price)}</p>
                    </div>
                </div>

                <button 
                    type="button"
                    onclick="removeFromCart(${item.id})" 
                    class="text-gray-300 hover:text-red-500 transition-colors p-2"
                >
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            `;

            container.appendChild(itemElement);
        });

        // Mengatur tampilan jika keranjang ada isi
        if (cart.length > 0) {
            emptyMessage.classList.add('hidden');

            countNav?.classList.remove('hidden');
            countMobile?.classList.remove('hidden');

            if (countNav) countNav.innerText = cart.length;
            if (countMobile) countMobile.innerText = cart.length;
            if (checkoutButton) checkoutButton.disabled = false;
        } else {
            emptyMessage.classList.remove('hidden');

            countNav?.classList.add('hidden');
            countMobile?.classList.add('hidden');

            if (checkoutButton) checkoutButton.disabled = true;
        }

        // Mengubah teks jumlah dan total harga
        if (cartSubtitle) {
            cartSubtitle.innerText = `${cart.length} Layanan dipilih`;
        }

        if (totalElement) {
            totalElement.innerText = formatRupiah(totalPrice);
        }
    }

    // Memproses checkout booking
    function processCheckout() {
        if (cart.length === 0) {
            showToast('Keranjang masih kosong');
            return;
        }

        const bookingDate = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short'
        });

        cart.forEach(item => {
            bookings.unshift({
                ...item,
                date: bookingDate,
                status: 'Menunggu'
            });
        });

        cart = [];

        updateCartUI();
        updateBookingList();
        toggleCart();
        showSection('booking');
        showToast('Reservasi berhasil dibuat');
    }

    // Memperbarui daftar booking saya
    function updateBookingList() {
        const list = document.getElementById('booking-list');

        if (!list) return;

        list.innerHTML = '';

        if (bookings.length === 0) {
            list.innerHTML = `
                <div class="text-center py-20 bg-white rounded-3xl border border-dashed border-pink-200">
                    <i class="fa-solid fa-calendar-xmark text-4xl text-pink-100 mb-4"></i>
                    <p class="text-sm text-gray-400 font-medium">Belum ada riwayat booking.</p>
                </div>
            `;
            return;
        }

        bookings.forEach(booking => {
            const bookingElement = document.createElement('div');

            bookingElement.className = 'bg-white p-5 rounded-3xl border border-pink-100 flex items-center justify-between shadow-sm animate-fade-in';

            bookingElement.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-pink-600">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>

                    <div>
                        <p class="text-sm font-bold">${booking.name}</p>
                        <p class="text-[10px] text-gray-400">
                            ${booking.date} • 
                            <span class="text-yellow-600 font-bold uppercase">${booking.status}</span>
                        </p>
                    </div>
                </div>

                <p class="text-sm font-bold text-pink-600">
                    ${formatRupiah(booking.price)}
                </p>
            `;

            list.appendChild(bookingElement);
        });
    }

    // Menampilkan toast notifikasi
    function showToast(message) {
        const toast = document.getElementById('toast');

        if (!toast) return;

        toast.innerText = message;
        toast.classList.remove('opacity-0');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            toast.classList.add('opacity-0');
            toast.classList.remove('opacity-100');
        }, 2000);
    }

    // Menjalankan halaman awal
    window.addEventListener('load', function () {
        showSection('layanan');
        updateCartUI();
        updateBookingList();
    });
</script>
</body>
</html>