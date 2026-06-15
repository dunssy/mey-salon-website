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

                <!-- Section tambah stok -->
                <section id="section-tambah-stok" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E]">
                                Tambahkan data barang baru ke stok Mey Salon.
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

                    <!-- Card form tambah stok -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form tambah stok -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                Form Tambah Stok
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Total pembelian barang baru akan masuk ke laporan sebagai pengeluaran.
                            </p>
                        </div>

                        <!-- Form tambah stok -->
                        <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

                            <!-- Grid input stok -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Input nama barang -->
                            <div>
                                <label for="nama_barang" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Nama Barang
                                </label>

                                <input 
                                    type="text" 
                                    name="nama_barang" 
                                    id="nama_barang" 
                                    required
                                    placeholder="Contoh: Shampoo Keratin"
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
                                    <option value="Chemical">Chemical</option>
                                    <option value="Hair Care">Hair Care</option>
                                </select>
                            </div>

                            <!-- Input jumlah barang -->
                            <div>
                                <label for="jumlah_barang" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Jumlah Barang
                                </label>

                                <input 
            
                                    type="number" 
                                    name="jumlah_barang_botol" 
                                    id="jumlah_barang_botol" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 5 (jumlah botol)"
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
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
                                    <option value="gram">gram</option>
                                    <option value="ml">Ml</option>
                                </select>
                            </div>
                            <div>
                                <label for="jumlah_barang_perbotol" class="block text-sm font-bold text-[#3D3134] mb-2">
                                    Jumlah Barang per Botol
                                </label>
                                <input 
                                    type="number" 
                                    name="jumlah_barang_perbotol" 
                                    id="jumlah_barang_perbotol" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 100 (ml per botol)"
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
                                    name="minimal_stok_awal" 
                                    id="minimal_stok_awal" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 3 (botol)"
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
                                    required
                                    min="0"
                                    placeholder="Contoh: 50000 (harga per botol)"
                                    class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                >
                            </div>
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2 flex justify-end">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
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