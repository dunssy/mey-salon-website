<?php 
session_start();
// Mengatur judul halaman
$page_title = "Pengeluaran";

// Mengatur sub judul halaman
$sub_title = "Tambah Pengeluaran";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";
global $koneksi;

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$_SESSION[id_user]'");
$data_user = mysqli_fetch_assoc($query);


// Memproses tambah pengeluaran
if (isset($_POST['submit'])) {
    // Memeriksa apakah session user login tersedia
    if (isset($_SESSION['id_user'])) {
        // Menyisipkan id_user dari session ke array $_POST
        $_POST['id_user'] = $_SESSION['id_user'];
        
        // Menjalankan fungsi dari controller
        if (tambah_pengeluaran($_POST) > 0) {
            echo "<script>
                    alert('Pengeluaran berhasil ditambahkan!');
                    window.location.href = 'data-laporan.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Pengeluaran gagal ditambahkan! Silakan cek kembali.');
                    window.location.href = 'data-laporan.php';
                  </script>";
        }
    } else {
        // Antisipasi jika session login habis / user belum login
        echo "<script>
                alert('Sesi Anda habis, silakan login kembali.');
                window.location.href = '../login.php';
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

                <!-- Section tambah pengeluaran -->
                <section id="section-tambah-pengeluaran" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Tambahkan Pengeluaran
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
                                <label for="jenis_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jenis pengeluaran
                                </label>

                                <input 
                                    type="text" 
                                    name="jenis_pengeluaran" 
                                    id="jenis_pengeluaran" 
                                    required
                                    placeholder="Contoh: Listrik"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input jumlah pengeluaran -->
                            <div>
                                <label for="jumlah_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Pengeluaran
                                </label>

                                <input 
                                    type="number" 
                                    name="jumlah_pengeluaran" 
                                    id="jumlah_pengeluaran" 
                                    required
                                    min="0"
                                    placeholder="Contoh: 100000"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            <!-- Input tanggal pengeluaran -->
                            <div>
                                <label for="tanggal_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Pengeluaran
                                </label>

                                <input 
                                    type="date" 
                                    name="tanggal_pengeluaran" 
                                    id="tanggal_pengeluaran" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >
                            </div>

                            

                            <!-- Input keterangan pengeluaran -->
                            <div>
                                <label for="keterangan_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Keterangan
                                </label>

                                <input 
                                    type="text " 
                                    name="keterangan_pengeluaran" 
                                    id="keterangan_pengeluaran" 
                                    required
                                    placeholder="Contoh: Pembayaran listrik bulan Januari"
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
                                    <span>Tambah Pengeluaran</span>
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