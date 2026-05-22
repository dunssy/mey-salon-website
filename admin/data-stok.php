<?php 
// Mengatur judul halaman
$page_title = "Data Stok Barang";

// Mengatur sub judul halaman
$sub_title = "Data Stok Barang";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Menghapus barang berdasarkan request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $id_barang = (int) $_POST['hapus'];

    $result = mysqli_query($koneksi, "DELETE FROM stok_barang WHERE id_barang = $id_barang");

    if ($result) {
        header("Location: data-stok.php?status=success");
        exit;
    } else {
        $error_message = "Gagal menghapus barang.";
    }
}

// Mengatur jumlah data per halaman
$jumlah_per_halaman = 5;

// Mengambil halaman aktif dari URL
$halaman_aktif = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;

// Mencegah halaman kurang dari 1
if ($halaman_aktif < 1) {
    $halaman_aktif = 1;
}

// Mengambil data barang sesuai halaman
$barang = tampil_barang_per_halaman($halaman_aktif, $jumlah_per_halaman);

// Menghitung total halaman barang
$total_halaman = hitung_total_halaman_barang($jumlah_per_halaman);

// Menghitung nomor awal tabel
$no = (($halaman_aktif - 1) * $jumlah_per_halaman) + 1;
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

                <!-- Section data stok barang -->
                <section id="section-barang" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Kelola stok barang dan kebutuhan operasional Mey Salon.
                            </p>
                        </div>

                        <!-- Tombol aksi kanan -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 md:justify-end">

                            <!-- Tombol tambah barang -->
                            <a 
                                href="tambah-stok.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-white bg-pink-600 rounded-lg hover:bg-pink-700 transition-colors"
                            >
                                <i class="fa-solid fa-plus"></i>
                                <span>Tambah Barang</span>
                            </a>

                            <!-- Tombol restok barang -->
                            <a  
                                href="restok.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-white bg-green-500 rounded-lg hover:bg-green-600 transition-colors"
                            >
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <span>Restok</span>
                            </a>
                        </div>
                    </div>

                    <!-- Form pencarian barang -->
                    <form action="" method="GET" class="bg-white p-4 rounded-2xl border border-pink-100 shadow-sm">

                        <!-- Input pencarian -->
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <input 
                                type="text" 
                                name="search" 
                                placeholder="Cari barang..." 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                            >

                            <button 
                                type="submit" 
                                class="px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors"
                            >
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Pesan error hapus barang -->
                    <?php if (isset($error_message)) : ?>
                        <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm font-medium">
                            <?= htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Card tabel barang -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Tabel responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[900px]">

                                <!-- Header tabel -->
                                <thead>
                                    <tr class="bg-pink-50/50 border-b border-pink-100 text-gray-700 font-semibold">
                                        <th class="p-4 w-16">No</th>
                                        <th class="p-4">Nama Barang</th>
                                        <th class="p-4">Jenis Barang</th>
                                        <th class="p-4">Jumlah</th>
                                        <th class="p-4">Satuan</th>
                                        <th class="p-4">Minimal Stok</th>
                                        <th class="p-4">Harga Beli</th>
                                        <th class="p-4 text-center">Action</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel -->
                                <tbody class="divide-y divide-pink-50">

                                    <?php if (!empty($barang)) : ?>

                                        <!-- Perulangan data barang -->
                                        <?php foreach ($barang as $data_barang) : ?>

                                            <tr class="hover:bg-pink-50/20 transition-colors">

                                                <!-- Nomor urut -->
                                                <td class="p-4">
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- Nama barang -->
                                                <td class="p-4 font-medium text-gray-800">
                                                    <?= htmlspecialchars($data_barang['nama_barang']); ?>
                                                </td>

                                                <!-- Jenis barang -->
                                                <td class="p-4">
                                                    <?= htmlspecialchars($data_barang['jenis_barang']); ?>
                                                </td>

                                                <!-- Jumlah barang -->
                                                <td class="p-4">
                                                    <?= htmlspecialchars($data_barang['jumlah_barang']); ?>
                                                </td>

                                                <!-- Satuan barang -->
                                                <td class="p-4">
                                                    <?= htmlspecialchars($data_barang['satuan_barang']); ?>
                                                </td>

                                                <!-- Minimal stok -->
                                                <td class="p-4">
                                                    <?php if ($data_barang['jumlah_barang'] <= $data_barang['minimal_stok']) : ?>
                                                        <span class="px-2 py-1 text-xs font-bold bg-red-50 text-red-600 rounded-lg">
                                                            <?= htmlspecialchars($data_barang['minimal_stok']); ?>
                                                        </span>
                                                    <?php else : ?>
                                                        <span class="px-2 py-1 text-xs font-bold bg-green-50 text-green-600 rounded-lg">
                                                            <?= htmlspecialchars($data_barang['minimal_stok']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Harga beli -->
                                                <td class="p-4">
                                                    Rp <?= number_format($data_barang['harga_beli'], 0, ',', '.'); ?>
                                                </td>

                                                <!-- Tombol aksi -->
                                                <td class="p-4 text-center">

                                                    <!-- Tombol edit -->
                                                    <a 
                                                        href="edit-stok.php?id_barang=<?= (int) $data_barang['id_barang']; ?>" 
                                                        class="inline-flex px-3 py-1.5 text-xs font-medium bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                                                    >
                                                        Edit
                                                    </a>

                                                    <!-- Tombol hapus -->
                                                    <form method="POST" class="inline">
                                                        <button 
                                                            type="submit" 
                                                            name="hapus" 
                                                            value="<?= (int) $data_barang['id_barang']; ?>" 
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')" 
                                                            class="inline-flex px-3 py-1.5 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors"
                                                        >
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>

                                        <!-- Pesan data kosong -->
                                        <tr>
                                            <td colspan="8" class="p-8 text-center text-gray-400">
                                                Belum ada data barang.
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                </tbody>

                                <!-- Footer pagination -->
                                <tfoot class="bg-gray-50/50 border-t border-pink-100">
                                    <tr>
                                        <td colspan="8" class="p-4">

                                            <!-- Navigasi pagination -->
                                            <div class="flex flex-wrap justify-end gap-2">

                                                <!-- Tombol sebelumnya -->
                                                <?php if ($halaman_aktif > 1) : ?>
                                                    <a 
                                                        href="?halaman=<?= $halaman_aktif - 1; ?>" 
                                                        class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors"
                                                    >
                                                        &laquo; Prev
                                                    </a>
                                                <?php else : ?>
                                                    <span class="px-3 py-1.5 text-xs font-medium bg-gray-50 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">
                                                        &laquo; Prev
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Nomor halaman -->
                                                <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                                                    <a 
                                                        href="?halaman=<?= $i; ?>" 
                                                        class="px-3 py-1.5 text-xs font-medium border rounded-lg transition-colors <?= $i === $halaman_aktif ? 'bg-pink-600 border-pink-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-pink-50 hover:text-pink-600'; ?>"
                                                    >
                                                        <?= $i; ?>
                                                    </a>
                                                <?php endfor; ?>

                                                <!-- Tombol berikutnya -->
                                                <?php if ($halaman_aktif < $total_halaman) : ?>
                                                    <a 
                                                        href="?halaman=<?= $halaman_aktif + 1; ?>" 
                                                        class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors"
                                                    >
                                                        Next &raquo;
                                                    </a>
                                                <?php else : ?>
                                                    <span class="px-3 py-1.5 text-xs font-medium bg-gray-50 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">
                                                        Next &raquo;
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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