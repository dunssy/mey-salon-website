<?php 
// Mengatur judul halaman
$page_title = "Data User";

// Mengatur sub judul halaman
$sub_title = "Tambah User";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Memproses tambah user
if (isset($_POST['submit'])) {
    if (tambah_user($_POST) > 0) {
        echo "<script>
                alert('User berhasil ditambahkan!');
                window.location.href = 'data-user.php';
              </script>";
    } else {
        echo "<script>
                alert('User gagal ditambahkan!');
                window.location.href = 'data-user.php';
              </script>";
    }
}
?>

<body class="text-gray-800 overflow-x-hidden">

    <!-- Wrapper utama halaman admin -->
    <div class="flex h-screen overflow-hidden">

        <!-- Memanggil sidebar -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- Konten utama -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">

            <!-- Memanggil navbar -->
            <?php include "../layout/navbar.php"; ?>

            <!-- Isi halaman -->
            <div class="p-4 md:p-8 flex-1">

                <!-- Section tambah user -->
                <section id="section-tambah-user" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Tambahkan data user baru untuk sistem Mey Salon.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-user.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Card form tambah user -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Form tambah user -->
                        <form action="" method="POST" class="p-6 space-y-4">

                            <!-- Input nama user -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama User
                                </label>
                                <input type="text" name="nama" id="nama" required placeholder="Masukkan nama user"class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                            </div>

                            <!-- Input nomor telepon -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor Telepon
                                </label>

                                <input 
                                    type="text" 
                                    name="no_hp" 
                                    id="no_hp" 
                                    required 
                                    placeholder="Contoh: 081234567890"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input alamat -->
                            <div>
                                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                                    Alamat
                                </label>

                                <input 
                                    type="text" 
                                    name="alamat" 
                                    id="alamat" 
                                    required 
                                    placeholder="Masukkan alamat user"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email
                                </label>

                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    required 
                                    placeholder="Masukkan email user"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input role -->
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role
                                </label>

                                <select 
                                    name="role" 
                                    id="role" 
                                    required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                                    <option value="">Pilih Role</option>
                                    <option value="Administrator">Administrator</option>
                                    <option value="Customer">Customer</option>
                                </select>
                            </div>

                            <!-- Input password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password
                                </label>

                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    required 
                                    placeholder="Masukkan password"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    id="submit-button"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tambah User</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>

            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>