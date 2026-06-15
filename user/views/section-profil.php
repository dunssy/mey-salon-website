<?php global $user; ?>

<!-- Section profil -->
<section id="section-profil" class="content-section hidden">

    <!-- Header profil -->
    <div class="max-w-xl mx-auto mb-4">
        <button 
            type="button"
            onclick="showSection('layanan')"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-white border border-pink-100 rounded-xl hover:bg-pink-50 hover:text-pink-600 transition"
        >
            <i class="fa-solid fa-arrow-left"></i>
            <span>Kembali</span>
        </button>
    </div>

    <!-- Card profil -->
    <div class="max-w-xl mx-auto bg-white rounded-[2rem] border border-pink-100 shadow-sm overflow-hidden">

        <!-- Header card profil -->
        <div class="relative p-8 bg-pink-50/30 border-b border-pink-100">

            <!-- Foto dan identitas profil -->
            <div class="text-center">
                <img 
                    src="https://placehold.co/100x100/fbcfe8/db2777?text=<?= urlencode(substr($user['nama'], 0, 1)); ?>" 
                    class="w-24 h-24 rounded-3xl mx-auto mb-4 border-4 border-white shadow-sm" 
                    alt="Foto Profil"
                >

                <h3 class="font-bold text-xl text-gray-800">
                    <?= htmlspecialchars($user['nama']); ?>
                </h3>

                <p class="text-xs text-pink-500 font-bold uppercase mt-1">
                    <?= htmlspecialchars($user['role']); ?>
                </p>

                <p class="text-xs text-gray-400 mt-2">
                    Kelola data akun dan informasi kontak Anda.
                </p>
            </div>
        </div>

        <!-- Form edit profil -->
        <form id="profile-form-user" action="" method="POST" class="p-6 md:p-8 space-y-4">

            <!-- Input nama -->
            <div>
                <label for="nama" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                    Nama
                </label>

                <input 
                    type="text" 
                    name="nama"
                    id="nama"
                    value="<?= htmlspecialchars($user['nama']); ?>" 
                    required
                    class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400"
                >
            </div>

            <!-- Input no hp -->
            <div>
                <label for="no_hp" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                    No HP
                </label>

                <input 
                    type="text" 
                    name="no_hp"
                    id="no_hp"
                    value="<?= htmlspecialchars($user['no_hp']); ?>" 
                    required
                    class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400"
                >
            </div>

            <!-- Input email -->
            <div>
                <label for="email" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                    Email
                </label>

                <input 
                    type="email" 
                    name="email"
                    id="email"
                    value="<?= htmlspecialchars($user['email']); ?>" 
                    required
                    class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400"
                >
            </div>

            <!-- Input alamat -->
            <div>
                <label for="alamat" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                    Alamat
                </label>

                <textarea 
                    name="alamat"
                    id="alamat"
                    rows="3" 
                    required
                    class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400 resize-none"
                ><?= htmlspecialchars($user['alamat']); ?></textarea>
            </div>

            <!-- Role -->
            <div>
                <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                    Role
                </label>

                <input 
                    type="text" 
                    value="<?= htmlspecialchars($user['role']); ?>" 
                    readonly
                    class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-400 outline-none cursor-not-allowed"
                >
            </div>

            <!-- Tombol simpan -->
            <button 
                type="submit" 
                name="update_profil"
                class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100"
            >
                Simpan Perubahan
            </button>
        </form>
    </div>
</section>

<!-- Script dropdown profil user -->
<script>
    // Membuka dan menutup dropdown profil user
    function toggleProfileMenuUser() {
        const menu = document.getElementById('profile-menu-user');

        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    // Scroll ke form pengaturan profil
    function scrollToProfileForm() {
        const menu = document.getElementById('profile-menu-user');
        const form = document.getElementById('profile-form-user');

        if (menu) {
            menu.classList.add('hidden');
        }

        if (form) {
            form.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    // Menutup dropdown saat klik di luar menu
    window.addEventListener('click', function(event) {
        const menu = document.getElementById('profile-menu-user');

        if (!event.target.closest('#profile-menu-user') && !event.target.closest('[onclick="toggleProfileMenuUser()"]')) {
            if (menu) {
                menu.classList.add('hidden');
            }
        }
    });
</script>
