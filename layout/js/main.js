// ======================================================
// LANDING / USER UI - MEY SALON
// Script dipisah sesuai halaman agar mudah dirawat.
// ======================================================


        const body = document.body;
        const navbar = document.getElementById('navbar');
        const themeToggleFloating = document.getElementById('theme-toggle-floating');
        const sunIcon = document.getElementById('sun-icon');
        const moonIcon = document.getElementById('moon-icon');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeMenuBtn = document.getElementById('close-menu-btn');

        // Theme Toggle Logic
        function toggleTheme() {
            body.classList.toggle('dark');
            const isDark = body.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            updateThemeIcons(isDark);
        }

        function updateThemeIcons(isDark) {
            if (isDark) {
                sunIcon.classList.remove('hidden');
                moonIcon.classList.add('hidden');
            } else {
                sunIcon.classList.add('hidden');
                moonIcon.classList.remove('hidden');
            }
        }

        // Initialize Theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.add('dark');
            updateThemeIcons(true);
        }

        themeToggleFloating.addEventListener('click', toggleTheme);

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 30) {
                navbar.classList.add('glass-nav');
                navbar.classList.remove('py-4');
                navbar.classList.add('py-2');
            } else {
                navbar.classList.remove('glass-nav');
                navbar.classList.remove('py-2');
                navbar.classList.add('py-4');
            }
        });

        // Smooth Scroll Logic with offset for Mobile
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    const offset = window.innerWidth < 768 ? 60 : 80;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - offset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    mobileMenu.classList.add('hidden');
                    body.style.overflow = 'auto';
                }
            });
        });

        // Mobile Menu Logic
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            mobileMenu.classList.add('flex');
            body.style.overflow = 'hidden';
        });

        closeMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            body.style.overflow = 'auto';
        });

        // Handle viewport resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                mobileMenu.classList.add('hidden');
                body.style.overflow = 'auto';
            }
        });