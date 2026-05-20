<!-- edit barang -->
<?php 
// Mengatur judul halaman
$page_title = "Edit Barang";

// Mengatur sub judul halaman
$sub_title = "Edit Barang";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Mengambil id barang dari URL
$id_barang = isset($_GET['id_barang']) ? (int) $_GET['id_barang'] : 0;

// Mengecek id barang valid
if ($id_barang <= 0) {
    echo "<script>
            alert('ID barang tidak valid!');
            window.location.href = 'data-stok.php';
          </script>";
    exit;
}

// Mengambil data barang berdasarkan id
$query = select("SELECT * FROM stok_barang WHERE id_barang = $id_barang");

// Mengecek data barang ditemukan
if (empty($query)) {
    echo "<script>
            alert('Data barang tidak ditemukan!');
            window.location.href = 'data-stok.php';
          </script>";
    exit;
}

// Menyimpan data barang ke variabel
$barang = $query[0];

// Memproses edit barang
if (isset($_POST['submit'])) {
    if (edit_barang($_POST) > 0) {
        echo "<script>
                alert('Barang berhasil diubah!');
                window.location.href = 'data-stok.php';
              </script>";
    } else {
        echo "<script>
                alert('Barang gagal diubah atau tidak ada perubahan data!');
                window.location.href = 'edit-stok.php?id_barang=$id_barang';
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

                <!-- Section edit barang -->
                <section id="section-edit-barang" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Ubah data stok barang yang tersedia di Mey Salon.
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

                    <!-- Card form edit barang -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Form edit barang -->
                        <form action="" method="POST" class="p-6 space-y-4">

                            <!-- Input id barang tersembunyi -->
                            <input 
                                type="hidden" 
                                name="id_barang" 
                                value="<?= htmlspecialchars($barang['id_barang']); ?>"
                            >

                            <!-- Input nama barang -->
                            <div>
                                <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Barang
                                </label>

                                <input 
                                    type="text" 
                                    name="nama_barang" 
                                    id="nama_barang" 
                                    value="<?= htmlspecialchars($barang['nama_barang']); ?>" 
                                    required 
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
                                    <option value="Chemical" <?= ($barang['jenis_barang'] == 'Chemical') ? 'selected' : ''; ?>>
                                        Chemical
                                    </option>
                                    <option value="Hair Care" <?= ($barang['jenis_barang'] == 'Hair Care') ? 'selected' : ''; ?>>
                                        Hair Care
                                    </option>
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
                                    value="<?= htmlspecialchars($barang['jumlah_barang']); ?>" 
                                    required 
                                    min="0"
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
                                    <option value="ml" <?= ($barang['satuan_barang'] == 'ml') ? 'selected' : ''; ?>>
                                        Ml
                                    </option>
                                    <option value="pcs" <?= ($barang['satuan_barang'] == 'pcs') ? 'selected' : ''; ?>>
                                        Pcs
                                    </option>
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
                                    value="<?= htmlspecialchars($barang['minimal_stok']); ?>" 
                                    required 
                                    min="0"
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
                                    value="<?= htmlspecialchars($barang['harga_beli']); ?>" 
                                    required 
                                    min="0"
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
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Ubah Barang</span>
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