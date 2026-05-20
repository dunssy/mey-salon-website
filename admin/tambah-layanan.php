<?php 
// Mengatur judul halaman
$page_title = "Data Layanan";

// Mengatur sub judul halaman
$sub_title = "Tambah Layanan";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Memproses tambah layanan
if (isset($_POST['submit'])) {
    if (tambah_layanan($_POST) > 0) {
        echo "<script>
                alert('Layanan berhasil ditambahkan!');
                window.location.href = 'data-layanan.php';
              </script>";
    } else {
        echo "<script>
                alert('Layanan gagal ditambahkan!');
                window.location.href = 'data-layanan.php';
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

                <!-- Section tambah layanan -->
                <section id="section-tambah-layanan" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Tambahkan data layanan baru untuk Mey Salon.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-layanan.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Card form tambah layanan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Form tambah layanan -->
                        <form action="" method="POST" class="p-6 space-y-4">

                            <!-- Input nama layanan -->
                            <div>
                                <label for="nama_layanan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Layanan
                                </label>

                                <input 
                                    type="text" 
                                    name="nama_layanan" 
                                    id="nama_layanan" 
                                    required 
                                    placeholder="Contoh: Hair Cut"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input harga layanan -->
                            <div>
                                <label for="harga_layanan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Harga Layanan
                                </label>

                                <input 
                                    type="number" 
                                    name="harga_layanan" 
                                    id="harga_layanan" 
                                    required 
                                    min="0"
                                    placeholder="Contoh: 50000"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input durasi layanan -->
                            <div>
                                <label for="durasi_layanan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Durasi Layanan
                                </label>

                                <input 
                                    type="number" 
                                    name="durasi_layanan" 
                                    id="durasi_layanan" 
                                    required 
                                    min="1"
                                    placeholder="Durasi dalam menit"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tambah Layanan</span>
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