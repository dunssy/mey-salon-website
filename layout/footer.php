    <!-- Script utama dashboard -->
    <script>
        // Membuka dan menutup sidebar di mobile
        function toggleSidebarMobile() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && overlay) {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
        }

        // Mengecilkan dan membesarkan sidebar di desktop
        function toggleSidebarDesktop() {
            const sidebar = document.getElementById('sidebar');
            const brand = document.getElementById('sidebar-brand');
            const texts = document.querySelectorAll('.sidebar-text');
            const links = document.querySelectorAll('.sidebar-link');
            const navbarIcon = document.getElementById('navbar-sidebar-icon');

            if (!sidebar) return;

            sidebar.classList.toggle('md:w-64');
            sidebar.classList.toggle('md:w-20');

            if (brand) {
                brand.classList.toggle('md:hidden');
            }

            texts.forEach(text => {
                text.classList.toggle('md:hidden');
            });

            links.forEach(link => {
                link.classList.toggle('md:justify-center');
                link.classList.toggle('md:px-2');
            });

            if (navbarIcon) {
                navbarIcon.classList.toggle('fa-bars-staggered');
                navbarIcon.classList.toggle('fa-bars');
            }
        }

        // Membuka dan menutup dropdown navbar
        function toggleDropdown(id) {
            const dropdowns = ['notif-dropdown', 'profile-dropdown'];

            dropdowns.forEach(dropdownId => {
                const dropdown = document.getElementById(dropdownId);

                if (!dropdown) return;

                if (dropdownId === id) {
                    dropdown.classList.toggle('hidden');
                } else {
                    dropdown.classList.add('hidden');
                }
            });
        }

        // Menutup dropdown saat klik di luar area dropdown
        window.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                document.getElementById('notif-dropdown')?.classList.add('hidden');
                document.getElementById('profile-dropdown')?.classList.add('hidden');
            }
        });

        // Menampilkan tanggal hari ini
        function updateClock() {
            const currentTime = document.getElementById('current-time');
            const now = new Date();
            const options = {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            };

            if (currentTime) {
                currentTime.innerText = now.toLocaleDateString('id-ID', options);
            }
        }

        // Menjalankan tanggal otomatis
        setInterval(updateClock, 1000);
        updateClock();

        // Menampilkan pesan toast
        function showMessage(message) {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toast-message');

            if (!toast || !toastMessage) return;

            toastMessage.innerText = message;
            toast.classList.remove('translate-y-32', 'opacity-0');

            setTimeout(() => {
                toast.classList.add('translate-y-32', 'opacity-0');
            }, 3000);
        }
    </script>
</body>
</html>