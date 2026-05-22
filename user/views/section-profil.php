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
    <div class="max-w-xl mx-auto">

            <!-- Header profil -->
            <div class="text-center">
                <img 
                    src="https://placehold.co/100x100/fbcfe8/db2777?text=<?= urlencode(substr($user['nama'], 0, 1)); ?>" 
                    class="w-24 h-24 rounded-3xl mx-auto mb-4 border-4 border-pink-50 shadow-sm" 
                    alt="Foto Profil"
                >

                <h3 class="font-bold text-xl">
                    <?= htmlspecialchars($user['nama']); ?>
                </h3>

                <p class="text-xs text-pink-500 font-bold uppercase mt-1">
                    <?= htmlspecialchars($user['role']); ?>
                </p>
            </div>

            <!-- Form edit profil -->
            <form action="" method="POST" class="space-y-4">

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

                <!-- Tombol logout -->
                <a 
                    href="../logout.php" 
                    class="block text-center w-full py-4 bg-red-50 text-red-600 font-bold rounded-2xl hover:bg-red-100 transition-all"
                >
                    Logout
                </a>
            </form>
        </div>
    </div>
</section>