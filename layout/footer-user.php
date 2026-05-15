
    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-gray-800 text-white text-xs font-bold rounded-full shadow-2xl opacity-0 transition-all z-[200] pointer-events-none"></div>

    <script>
        let cart = [];
        let bookings = [];

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        }

        function showSection(id) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.add('hidden'));
            document.getElementById(`section-${id}`).classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function toggleCart() {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('cart-overlay');
            drawer.classList.toggle('sheet-hidden');
            drawer.classList.toggle('sheet-visible');
            overlay.classList.toggle('hidden');
        }

        function addToCart(id, name, price) {
            if(cart.find(i => i.id === id)) {
                showToast("Sudah ada di keranjang");
                return;
            }
            cart.push({id, name, price});
            updateUI();
            showToast("Ditambahkan ke keranjang");
        }

        function removeFromCart(id) {
            cart = cart.filter(i => i.id !== id);
            updateUI();
        }

        function updateUI() {
            const container = document.getElementById('cart-items-container');
            const countNav = document.getElementById('cart-count-nav');
            const countMob = document.getElementById('cart-count-mobile');
            const totalEl = document.getElementById('cart-total-price');
            const btn = document.getElementById('btn-checkout');
            const subTitle = document.getElementById('cart-item-count');
            const empty = document.getElementById('empty-cart-msg');

            container.innerHTML = '';
            container.appendChild(empty);

            let total = 0;
            cart.forEach(item => {
                total += item.price;
                const div = document.createElement('div');
                div.className = "flex items-center justify-between bg-pink-50/40 p-4 rounded-2xl border border-pink-50 animate-fade-in";
                div.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-pink-600 shadow-sm"><i class="fa-solid fa-sparkles text-xs"></i></div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">${item.name}</p>
                            <p class="text-[11px] text-pink-600 font-bold">Rp ${item.price.toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-gray-300 hover:text-red-500 transition-colors p-2"><i class="fa-solid fa-trash-can"></i></button>
                `;
                container.appendChild(div);
            });

            if(cart.length > 0) {
                empty.classList.add('hidden');
                countNav.classList.remove('hidden');
                countMob.classList.remove('hidden');
                countNav.innerText = cart.length;
                countMob.innerText = cart.length;
                btn.disabled = false;
            } else {
                empty.classList.remove('hidden');
                countNav.classList.add('hidden');
                countMob.classList.add('hidden');
                btn.disabled = true;
            }

            subTitle.innerText = `${cart.length} Layanan dipilih`;
            totalEl.innerText = `Rp ${total.toLocaleString('id-ID')}`;
        }

        function processCheckout() {
            const date = new Date().toLocaleDateString('id-ID', { day:'numeric', month:'short' });
            cart.forEach(i => bookings.unshift({...i, date, status: 'Menunggu'}));
            cart = [];
            updateUI();
            updateBookingList();
            toggleCart();
            showSection('booking');
            showToast("Reservasi Berhasil!");
        }

        function updateBookingList() {
            const list = document.getElementById('booking-list');
            list.innerHTML = '';
            bookings.forEach(b => {
                const div = document.createElement('div');
                div.className = "bg-white p-5 rounded-3xl border border-pink-100 flex items-center justify-between shadow-sm animate-fade-in";
                div.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-pink-50 rounded-xl flex items-center justify-center text-pink-600"><i class="fa-solid fa-calendar-check"></i></div>
                        <div>
                            <p class="text-sm font-bold">${b.name}</p>
                            <p class="text-[10px] text-gray-400">${b.date} • <span class="text-yellow-600 font-bold uppercase">${b.status}</span></p>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-pink-600">Rp ${b.price.toLocaleString('id-ID')}</p>
                `;
                list.appendChild(div);
            });
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            t.innerText = msg;
            t.classList.remove('opacity-0');
            t.classList.add('opacity-100');
            setTimeout(() => { t.classList.add('opacity-0'); t.classList.remove('opacity-100'); }, 2000);
        }

        window.onload = () => showSection('layanan');
    </script>
</body>
</html>