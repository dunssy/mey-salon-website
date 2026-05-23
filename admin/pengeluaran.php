<?php 
// Memulai session user
session_start();

// Mengatur judul halaman
$page_title = "Pengeluaran";

// Mengatur sub judul halaman
$sub_title = "Tambah Pengeluaran";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
            alert('Sesi Anda habis, silakan login kembali.');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Memproses tambah pengeluaran
if (isset($_POST['submit'])) {
    $id_user = (int) $_SESSION['id_user'];
    $jenis_pengeluaran = mysqli_real_escape_string($koneksi, strip_tags($_POST['jenis_pengeluaran']));
    $jumlah_pengeluaran = (int) $_POST['jumlah_pengeluaran'];
    $tanggal_pengeluaran = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengeluaran']);
    $keterangan_pengeluaran = mysqli_real_escape_string($koneksi, strip_tags($_POST['keterangan_pengeluaran']));

    // Mengecek jumlah pengeluaran valid
    if ($jumlah_pengeluaran <= 0) {
        echo "<script>
                alert('Jumlah pengeluaran harus lebih dari 0!');
                window.location.href = 'pengeluaran.php';
              </script>";
        exit;
    }

    // Menyimpan pengeluaran manual untuk laporan
    $query = "INSERT INTO pengeluaran 
                (id_user, jenis_pengeluaran, jumlah_pengeluaran, tanggal_pengeluaran, keterangan_pengeluaran)
              VALUES 
                ($id_user, '$jenis_pengeluaran', $jumlah_pengeluaran, '$tanggal_pengeluaran', '$keterangan_pengeluaran')";

    mysqli_query($koneksi, $query);

    // Mengecek data berhasil disimpan
    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Pengeluaran berhasil ditambahkan dan masuk ke laporan!');
                window.location.href = 'data-laporan.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Pengeluaran gagal ditambahkan!');
                window.location.href = 'pengeluaran.php';
              </script>";
        exit;
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
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Tambahkan pengeluaran manual seperti listrik, sewa, gaji, atau kebutuhan operasional.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-laporan.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-white border border-pink-100 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Layout form -->
                    <div class="grid grid-cols-1 lg:grid-cols-[1fr_360px] gap-6 items-start">

                        <!-- Card form pengeluaran -->
                        <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                            <!-- Header card -->
                            <div class="p-6 border-b border-pink-100">
                                <h4 class="font-bold text-gray-700">
                                    Form Pengeluaran
                                </h4>

                                <p class="text-xs text-gray-400 mt-1">
                                    Data yang disimpan akan otomatis masuk sebagai pengeluaran di laporan.
                                </p>
                            </div>

                            <!-- Form tambah pengeluaran -->
                            <form action="" method="POST" class="p-6 space-y-4">

                                <!-- Input jenis pengeluaran -->
                                <div>
                                    <label for="jenis_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jenis Pengeluaran
                                    </label>

                                    <input 
                                        type="text" 
                                        name="jenis_pengeluaran" 
                                        id="jenis_pengeluaran" 
                                        required
                                        placeholder="Contoh: Listrik, Sewa, Gaji, Peralatan"
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
                                        min="1"
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
                                        value="<?= date('Y-m-d'); ?>"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                    >
                                </div>

                                <!-- Input keterangan -->
                                <div>
                                    <label for="keterangan_pengeluaran" class="block text-sm font-medium text-gray-700 mb-1">
                                        Keterangan
                                    </label>

                                    <textarea 
                                        name="keterangan_pengeluaran" 
                                        id="keterangan_pengeluaran" 
                                        rows="4"
                                        required
                                        placeholder="Contoh: Pembayaran listrik bulan Januari"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200 resize-none"
                                    ></textarea>
                                </div>

                                <!-- Tombol submit -->
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-pink-600 text-white font-bold rounded-xl hover:bg-pink-700 transition-colors"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tambah Pengeluaran</span>
                                </button>
                            </form>
                        </div>

                        <!-- Card informasi laporan -->
                        <div class="bg-white rounded-2xl shadow-sm border border-pink-100 p-6">

                            <!-- Icon info -->
                            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center mb-4">
                                <i class="fa-solid fa-money-bill-wave text-xl"></i>
                            </div>

                            <h4 class="font-bold text-gray-800">
                                Masuk ke Laporan
                            </h4>

                            <p class="text-sm text-gray-500 mt-2 leading-relaxed">
                                Pengeluaran manual akan ditampilkan di laporan bersama pengeluaran restok barang.
                            </p>

                            <div class="mt-5 p-4 bg-pink-50/50 border border-pink-100 rounded-xl">
                                <p class="text-xs text-gray-500 leading-relaxed">
                                    Total pengeluaran laporan dihitung dari:
                                    <br>
                                    <b>Restok Barang + Pengeluaran Manual</b>
                                </p>
                            </div>

                            <a 
                                href="data-laporan.php" 
                                class="mt-5 inline-flex w-full items-center justify-center gap-2 px-4 py-3 text-sm font-bold text-pink-600 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors"
                            >
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Lihat Laporan</span>
                            </a>
                        </div>
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