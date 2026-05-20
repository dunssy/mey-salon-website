<?php 
// Mengatur judul halaman
$page_title = "Data Stok Barang";

// Mengatur sub judul halaman
$sub_title = "Tambah Stok";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Memproses tambah stok
if (isset($_POST['submit'])) {
    if (tambah_stok($_POST) > 0) {
        echo "<script>
                alert('Stok berhasil ditambahkan!');
                window.location.href = 'data-stok.php';
              </script>";
    } else {
        echo "<script>
                alert('Stok gagal ditambahkan!');
                window.location.href = 'data-stok.php';
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

                <!-- Section tambah stok -->
                <section id="section-tambah-stok" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Tambahkan data barang baru ke stok Mey Salon.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-stok.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Card form tambah stok -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Form tambah stok -->
                        <form action="" method="POST" class="p-6 space-y-4">

                            <!-- Input nama barang -->
                            <div>
                                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Barang
                                </label>

                                <input 
                                    type="text" 
                                    name="nama_barang" 
                                    id="nama_barang" 
                                    required
                                    placeholder="Contoh: Shampoo Keratin"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input jenis barang -->
                            <div>
                                <label for="jenis_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis Barang
                                </label>

                                <select 
                                    name="jenis_barang" 
                                    id="jenis_barang" 
                                    required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                                    <option value="">Pilih Jenis Barang</option>
                                    <option value="Chemical">Chemical</option>
                                    <option value="Hair Care">Hair Care</option>
                                </select>
                            </div>

                            <!-- Input jumlah barang -->
                            <div>
                                <label for="jumlah_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Barang
                                </label>

                                <input 
                                    type="number" 
                                    name="jumlah_barang" 
                                    id="jumlah_barang" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 10"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input satuan barang -->
                            <div>
                                <label for="satuan_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Satuan Barang
                                </label>

                                <select 
                                    name="satuan_barang" 
                                    id="satuan_barang" 
                                    required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                                    <option value="">Pilih Satuan Barang</option>
                                    <option value="pcs">Pcs</option>
                                    <option value="ml">Ml</option>
                                </select>
                            </div>

                            <!-- Input minimal stok -->
                            <div>
                                <label for="minimal_stok" class="block text-sm font-medium text-gray-700 mb-1">
                                    Minimal Stok
                                </label>

                                <input 
                                    type="number" 
                                    name="minimal_stok" 
                                    id="minimal_stok" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input harga beli -->
                            <div>
                                <label for="harga_beli" class="block text-sm font-medium text-gray-700 mb-1">
                                    Harga Beli
                                </label>

                                <input 
                                    type="number" 
                                    name="harga_beli" 
                                    id="harga_beli" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 50000"
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
                                    <span>Tambah Stok</span>
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