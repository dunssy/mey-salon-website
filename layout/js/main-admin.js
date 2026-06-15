// ======================================================
// ADMIN UI - MEY SALON
// Script dipisah sesuai halaman agar mudah dirawat.
// ======================================================


        // Sidebar Mobile Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        // Dropdown Toggle Logic
        function toggleDropdown(id) {
            const dropdowns = ['notif-dropdown', 'profile-dropdown'];
            dropdowns.forEach(d => {
                const el = document.getElementById(d);
                if (d === id) {
                    el.classList.toggle('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }

        // Close dropdowns on outside click
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.getElementById('notif-dropdown').classList.add('hidden');
                document.getElementById('profile-dropdown').classList.add('hidden');
            }
        });

        // Update Waktu Real-time
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('current-time').innerText = now.toLocaleDateString('id-ID', options);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Switch Tabs Logic
        function switchTab(tabName) {
            const sections = ['dashboard', 'layanan', 'pelanggan'];
            sections.forEach(s => {
                const el = document.getElementById(`section-${s}`);
                if (el) el.classList.add('hidden');
                
                const nav = document.getElementById(`nav-${s}`);
                if (nav) nav.classList.remove('sidebar-active');
                if (nav) nav.classList.add('text-gray-600');
            });

            const target = document.getElementById(`section-${tabName}`);
            if (target) target.classList.remove('hidden');

            const navTarget = document.getElementById(`nav-${tabName}`);
            if (navTarget) {
                navTarget.classList.add('sidebar-active');
                navTarget.classList.remove('text-gray-600');
            }

            const titleMap = {
                'dashboard': 'Dashboard',
                'layanan': 'Services',
                'pelanggan': 'Customers',
                'booking': 'Reservations',
                'laporan': 'Reports'
            };
            document.getElementById('page-title').innerText = titleMap[tabName] || 'Admin';
            
            // Close sidebar on mobile after selection
            if (window.innerWidth < 768) toggleSidebar();
        }

        // Notification Function
        function showMessage(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = msg;
            
            toast.classList.remove('translate-y-32', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-32', 'opacity-0');
            }, 3000);
        }
    