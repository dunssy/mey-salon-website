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
                window.location.href = 'laporan.php';
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

                <!-- Section tambah pengeluaran -->
                <section id="section-tambah-pengeluaran" class="space-y-6">

                    <!-- Header halaman -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- Judul halaman -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    <?= htmlspecialchars($sub_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Tambahkan pengeluaran manual seperti listrik, sewa, gaji, atau kebutuhan operasional.
                                </p>
                            </div>

                            <!-- Tombol kembali -->
                            <a 
                                href="data-laporan.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors w-fit"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>

                    <!-- Layout form -->
                    <div class="w-full">

                        <!-- Card form pengeluaran -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden w-full">

                            <!-- Header card -->
                            <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                                <h4 class="font-bold text-[#3D3134]">
                                    Form Pengeluaran
                                </h4>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Data yang disimpan akan otomatis masuk sebagai pengeluaran di laporan.
                                </p>
                            </div>

                            <!-- Form tambah pengeluaran -->
                            <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

                                <!-- Grid input pengeluaran -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <!-- Input jenis pengeluaran -->
                                <div>
                                    <label for="jenis_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Jenis Pengeluaran
                                    </label>

                                    <input 
                                        type="text" 
                                        name="jenis_pengeluaran" 
                                        id="jenis_pengeluaran" 
                                        required
                                        placeholder="Contoh: Listrik, Sewa, Gaji, Peralatan"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input jumlah pengeluaran -->
                                <div>
                                    <label for="jumlah_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Jumlah Pengeluaran
                                    </label>

                                    <input 
                                        type="number" 
                                        name="jumlah_pengeluaran" 
                                        id="jumlah_pengeluaran" 
                                        required
                                        min="1"
                                        placeholder="Contoh: 100000"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input tanggal pengeluaran -->
                                <div>
                                    <label for="tanggal_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Tanggal Pengeluaran
                                    </label>

                                    <input 
                                        type="date" 
                                        name="tanggal_pengeluaran" 
                                        id="tanggal_pengeluaran" 
                                        required
                                        value="<?= date('Y-m-d'); ?>"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input keterangan -->
                                <div class="md:col-span-2">
                                    <label for="keterangan_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Keterangan
                                    </label>

                                    <textarea 
                                        name="keterangan_pengeluaran" 
                                        id="keterangan_pengeluaran" 
                                        rows="4"
                                        required
                                        placeholder="Contoh: Pembayaran listrik bulan Januari"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A] resize-none"
                                    ></textarea>
                                </div>

                                </div>

                                <!-- Tombol submit -->
                                <div class="flex justify-end">
                                    <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tambah Pengeluaran</span>
                                    </button>
                                </div>
                            </form>
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