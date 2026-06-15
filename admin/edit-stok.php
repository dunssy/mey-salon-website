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

<body class="text-[#2B2424] overflow-x-hidden bg-[#FFF7FA]">

    <!-- Wrapper utama halaman admin -->
    <div class="flex h-screen overflow-hidden">

        <!-- Memanggil sidebar -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- Konten utama -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-[#FFF7FA]">

            <!-- Memanggil navbar -->
            <?php include "../layout/navbar.php"; ?>

            <!-- Isi halaman -->
            <div class="p-4 sm:p-5 md:p-8 flex-1">

                <!-- Section edit barang -->
                <section id="section-edit-barang" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E]">
                                Ubah data stok barang yang tersedia di Mey Salon.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-stok.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-white border border-[#F7D6E4] rounded-xl hover:bg-[#FDEAF1] transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Card form edit barang -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form edit barang -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                Form Edit Barang
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Ubah informasi barang tanpa mengubah jumlah stok langsung.
                            </p>
                        </div>

                        <!-- Form edit barang -->
                        <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

                            <!-- Grid input edit barang -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Input id barang tersembunyi -->
                            <input 
                                type="hidden" 
                                name="id_barang" 
                                value="<?= htmlspecialchars($barang['id_barang']); ?>"
                            >

                            <!-- Input nama barang -->
                            <div>
                                <label for="nama_barang" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Nama Barang
                                </label>

                                <input 
                                    type="text" 
                                    name="nama_barang" 
                                    id="nama_barang" 
                                    value="<?= htmlspecialchars($barang['nama_barang']); ?>" 
                                    required 
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                >
                            </div>

                            <!-- Input jenis barang -->
                            <div>
                                <label for="jenis_barang" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Jenis Barang
                                </label>

                                <select 
                                    name="jenis_barang" 
                                    id="jenis_barang" 
                                    required 
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
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

                            <!-- Input jumlah barang hanya untuk dilihat -->
                            <div>
                                <label for="jumlah_barang" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Jumlah Barang
                                </label>

                                <input 
                                    type="number" 
                                    name="jumlah_barang" 
                                    id="jumlah_barang" 
                                    value="<?= htmlspecialchars($barang['jumlah_barang']); ?>"  
                                    min="0"
                                    class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl bg-[#F8F4F2] text-[#7A6F6F] cursor-not-allowed focus:outline-none"
                                    readonly
                                >
                            </div>

                            <!-- Input satuan barang -->
                            <div>
                                <label for="satuan_barang" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Satuan Barang
                                </label>

                                <select 
                                    name="satuan_barang" 
                                    id="satuan_barang" 
                                    required 
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
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
                            <div>
                                <label for="jumlah_barang_perbotol" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Jumlah Barang Per Botol
                                </label>

                                <input 
                                    type="number" 
                                    name="jumlah_barang_perbotol" 
                                    id="jumlah_barang_perbotol" 
                                    value="<?= htmlspecialchars($barang['jumlah_satuan']); ?>" 
                                    required 
                                    min="1"
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                >
                            </div>

                            <!-- Input minimal stok -->
                            <div>
                                <label for="minimal_stok" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Minimal Stok
                                </label>

                                <input 
                                    type="number" 
                                    name="minimal_stok" 
                                    id="minimal_stok" 
                                    value="<?= htmlspecialchars($barang['minimal_stok']); ?>" 
                                    required 
                                    min="0"
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                >
                            </div>

                            <!-- Input harga beli -->
                            <div>
                                <label for="harga_beli" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Harga Beli
                                </label>

                                <input 
                                    type="number" 
                                    name="harga_beli" 
                                    id="harga_beli" 
                                    value="<?= htmlspecialchars($barang['harga_beli']); ?>" 
                                    required 
                                    min="0"
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                >
                            </div>

                            </div>

                            <!-- Info jumlah stok -->
                            <div class="p-4 rounded-2xl bg-[#FDEAF1]/60 border border-[#F7D6E4] text-xs text-[#6F5E64] leading-relaxed">
                                <b class="text-[#C75C7A]">Catatan:</b>
                                Jumlah barang hanya dapat dilihat dan tidak bisa diedit langsung dari halaman ini. Gunakan menu restok untuk menambah stok.
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2 flex justify-end">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
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