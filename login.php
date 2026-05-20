<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mey Salon - Keanggunan & Kecantikan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-rose: #be123c;
            --soft-rose: #fff1f2;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
            min-height: 100vh;
        }

        .serif-font {
            font-family: 'Playfair Display', serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .input-focus-ring:focus {
            ring: 2px;
            ring-color: var(--primary-rose);
            border-color: var(--primary-rose);
        }

        .form-container {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Hide Scrollbar for cleaner look */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #fda4af;
            border-radius: 10px;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 md:p-6 lg:p-12">

    <div class="w-full max-w-6xl glass-effect rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row h-auto min-h-[650px] transform transition hover:shadow-rose-200/50">
        
        <!-- Sisi Kiri: Visual Branding (Hidden on Mobile, Visible on MD+) -->
        <div class="relative w-full md:w-1/2 lg:w-3/5 hidden md:block overflow-hidden">
            <img src="https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&q=80&w=1200" 
                 alt="Mey Salon Experience" 
                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 hover:scale-105">
            
            <!-- Overlay Gradien yang Mewah -->
            <div class="absolute inset-0 bg-gradient-to-tr from-rose-950/80 via-rose-900/20 to-transparent flex flex-col justify-end p-12 text-white">
                <div class="space-y-4 fade-in">
                    <span class="px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold tracking-widest uppercase border border-white/30">Premium Studio</span>
                    <h2 class="text-5xl font-bold serif-font leading-tight">Elevate Your<br>Natural Beauty</h2>
                    <p class="text-rose-100/90 max-w-sm text-lg font-light leading-relaxed">
                        Nikmati pengalaman kecantikan kelas dunia dengan sentuhan personal dari para ahli kami.
                    </p>
                    <div class="pt-6 flex gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-rose-300"></i>
                            <span>Certified Stylists</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-rose-300"></i>
                            <span>Luxury Products</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sisi Kanan: Form Dinamis -->
        <div class="w-full md:w-1/2 lg:w-2/5 p-8 sm:p-12 lg:p-16 flex flex-col justify-center bg-white relative">
            
            <!-- Logo Header -->
            <div class="mb-10 text-center md:text-left">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-rose-50 rounded-2xl mb-4 border border-rose-100">
                    <i class="fas fa-spa text-rose-600 text-3xl"></i>
                </div>
                <h1 id="brand-title" class="text-3xl font-bold text-gray-900 serif-font">Mey Salon</h1>
                <p id="brand-subtitle" class="text-gray-500 mt-2 font-light">Selamat datang kembali, silakan masuk ke akun Anda.</p>
            </div>

            <form id="login-form" class="space-y-5 fade-in">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Username</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 transition-colors group-focus-within:text-rose-600">
                            <i class="far fa-user"></i>
                        </span>
                        <input type="text" required class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="ID Member / Username">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 group-focus-within:text-rose-600">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" required class="block w-full pl-11 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-gray-500 cursor-pointer">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-rose-600 focus:ring-rose-500 mr-2">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="text-rose-600 font-medium hover:text-rose-700 transition">Lupa password?</a>
                </div>

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-4 rounded-2xl shadow-xl shadow-rose-200 transform transition active:scale-[0.98] hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>
            </form>

            <form id="register-form" class="hidden space-y-4 fade-in">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Nama Lengkap</label>
                        <input type="text" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" placeholder="Nama Anda">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Username</label>
                        <input type="text" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" placeholder="Unique ID">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">No. Telepon</label>
                    <input type="tel" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" placeholder="08xx xxxx xxxx">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Password</label>
                        <input type="password" id="reg-pass" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" placeholder="••••">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider ml-1">Konfirmasi</label>
                        <input type="password" id="reg-conf" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all text-sm" placeholder="••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-4 rounded-2xl shadow-xl shadow-rose-200 mt-2 transform transition active:scale-[0.98]">
                    Daftar Member Baru
                </button>
            </form>

            <!-- Toggle Buttons -->
            <div class="mt-10 pt-6 border-t border-gray-100 text-center">
                <p id="toggle-info" class="text-gray-500 text-sm">
                    Belum punya akun? 
                    <button onclick="toggleForm('register')" class="text-rose-600 font-bold hover:underline ml-1">Daftar di sini</button>
                </p>
                <p id="toggle-info-back" class="hidden text-gray-500 text-sm">
                    Sudah menjadi member? 
                    <button onclick="toggleForm('login')" class="text-rose-600 font-bold hover:underline ml-1">Masuk Sekarang</button>
                </p>
            </div>

            <!-- Decorative Element -->
            <div class="absolute bottom-4 right-4 opacity-10 pointer-events-none">
                <i class="fas fa-scissors text-6xl text-rose-900 rotate-45"></i>
            </div>
        </div>
    </div>

    <div id="notif" class="fixed top-8 right-8 max-w-sm w-full bg-white rounded-2xl shadow-2xl p-4 border-l-4 border-rose-600 transform translate-x-[120%] transition-transform duration-500 z-50">
        <div class="flex items-center">
            <div class="bg-rose-100 p-2 rounded-full mr-3 text-rose-600">
                <i class="fas fa-info-circle"></i>
            </div>
            <div>
                <p id="notif-text" class="text-sm font-medium text-gray-800"></p>
            </div>
        </div>
    </div>

    <script>
        function toggleForm(target) {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const brandTitle = document.getElementById('brand-title');
            const brandSubtitle = document.getElementById('brand-subtitle');
            const toggleInfo = document.getElementById('toggle-info');
            const toggleInfoBack = document.getElementById('toggle-info-back');

            if (target === 'register') {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                brandTitle.innerText = "Join Mey Salon";
                brandSubtitle.innerText = "Dapatkan akses ke layanan eksklusif kami.";
                toggleInfo.classList.add('hidden');
                toggleInfoBack.classList.remove('hidden');
            } else {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                brandTitle.innerText = "Mey Salon";
                brandSubtitle.innerText = "Selamat datang kembali, silakan masuk ke akun Anda.";
                toggleInfo.classList.remove('hidden');
                toggleInfoBack.classList.add('hidden');
            }
        }

        function showNotification(message) {
            const notif = document.getElementById('notif');
            const text = document.getElementById('notif-text');
            text.innerText = message;
            notif.classList.remove('translate-x-[120%]');
            setTimeout(() => {
                notif.classList.add('translate-x-[120%]');
            }, 3000);
        }

        // Form Submission Logic
        document.getElementById('login-form').addEventListener('submit', (e) => {
            e.preventDefault();
            showNotification("Berhasil masuk! Mengalihkan ke Dashboard...");
        });

        document.getElementById('register-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const pass = document.getElementById('reg-pass').value;
            const conf = document.getElementById('reg-conf').value;
            
            if (pass !== conf) {
                showNotification("Error: Password tidak cocok!");
                return;
            }
            showNotification("Pendaftaran Berhasil! Silakan login.");
            setTimeout(() => toggleForm('login'), 1500);
        });
    </script>
</body>
</html>