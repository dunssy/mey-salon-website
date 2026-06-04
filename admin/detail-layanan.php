<?php 
// Mengatur judul halaman
$page_title = "Detail Layanan";

// Mengatur sub judul halaman
$sub_title = "Detail Layanan";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil id layanan dari URL
$id_layanan = isset($_GET['id_layanan']) ? (int) $_GET['id_layanan'] : 0;

// Mengecek id layanan valid
if ($id_layanan <= 0) {
    echo "<script>
            alert('ID layanan tidak valid!');
            window.location.href = 'data-layanan.php';
          </script>";
    exit;
}

// Mengambil data layanan
$query_layanan = mysqli_query(
    $koneksi,
    "SELECT * FROM layanan 
     WHERE id_layanan = $id_layanan"
);

// Mengecek layanan ditemukan
if (mysqli_num_rows($query_layanan) === 0) {
    echo "<script>
            alert('Data layanan tidak ditemukan!');
            window.location.href = 'data-layanan.php';
          </script>";
    exit;
}

// Menyimpan data layanan
$layanan = mysqli_fetch_assoc($query_layanan);

// Mengambil paket stok layanan
$query_paket_stok = mysqli_query(
    $koneksi,
    "SELECT 
        ps.id_paket,
        ps.id_layanan,
        ps.id_barang,
        ps.jumlah_stok,
        sb.nama_barang,
        sb.jenis_barang,
        sb.jumlah_barang,
        sb.satuan_barang,
        sb.minimal_stok,
        sb.harga_beli
     FROM paket_stok ps
     JOIN stok_barang sb ON ps.id_barang = sb.id_barang
     WHERE ps.id_layanan = $id_layanan
     ORDER BY sb.nama_barang ASC"
);

// Menyiapkan total bahan
$total_bahan = 0;
$total_estimasi_modal = 0;
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

                <!-- Section detail layanan -->
                <section id="section-detail-layanan" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Lihat bahan atau paket stok yang digunakan oleh layanan ini.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-layanan.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-white border border-pink-100 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Grid detail layanan -->
                    <div class="grid grid-cols-1 lg:grid-cols-[360px_1fr] gap-6 items-start">

                        <!-- Card informasi layanan -->
                        <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                            <!-- Header card -->
                            <div class="p-6 border-b border-pink-100">
                                <h4 class="font-bold text-gray-700">
                                    Informasi Layanan
                                </h4>

                                <p class="text-xs text-gray-400 mt-1">
                                    Data utama layanan salon.
                                </p>
                            </div>

                            <!-- Isi informasi layanan -->
                            <div class="p-6 space-y-5">

                                <!-- Nama layanan -->
                                <div>
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                        Nama Layanan
                                    </p>

                                    <h4 class="text-lg font-bold text-gray-800 mt-1">
                                        <?= htmlspecialchars($layanan['nama_layanan']); ?>
                                    </h4>
                                </div>

                                <!-- Harga layanan -->
                                <div>
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                        Harga Layanan
                                    </p>

                                    <p class="text-xl font-bold text-pink-600 mt-1">
                                        Rp <?= number_format($layanan['harga_min'], 0, ',', '.'); ?>
                                    </p>
                                </div>

                                <!-- Durasi layanan -->
                                <div>
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                        Durasi Layanan
                                    </p>

                                    <p class="text-sm font-bold text-gray-800 mt-1">
                                        <?= htmlspecialchars($layanan['durasi_layanan']); ?> Menit
                                    </p>
                                </div>

                                <!-- Info paket stok -->
                                <div class="p-4 bg-pink-50/50 border border-pink-100 rounded-xl">
                                    <p class="text-xs text-gray-500 leading-relaxed">
                                        Paket stok akan otomatis mengurangi stok barang saat admin menyelesaikan booking.
                                    </p>
                                </div>

                                <!-- Tombol edit layanan -->
                                <a 
                                    href="edit-layanan.php?id_layanan=<?= (int) $layanan['id_layanan']; ?>"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-pink-600 text-white text-sm font-bold rounded-xl hover:bg-pink-700 transition"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Edit Layanan</span>
                                </a>
                            </div>
                        </div>

                        <!-- Card paket stok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                            <!-- Header paket stok -->
                            <div class="p-6 border-b border-pink-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-gray-700">
                                        Paket Stok Digunakan
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Daftar bahan yang otomatis berkurang saat layanan selesai.
                                    </p>
                                </div>

                                <span class="text-xs text-pink-600 bg-pink-50 px-3 py-1 rounded-full font-bold">
                                    Auto Pemakaian Stok
                                </span>
                            </div>

                            <!-- Tabel paket stok -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left min-w-[800:px]">

                                    <!-- Header tabel -->
                                    <thead class="bg-pink-50/40 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                        <tr>
                                            <th class="px-6 py-4">No</th>
                                            <th class="px-6 py-4">Nama Barang</th>
                                            <th class="px-6 py-4">Jenis</th>
                                            <th class="px-6 py-4">Dipakai</th>
                                            <th class="px-6 py-4">Stok Saat Ini</th>
                                            <th class="px-6 py-4">Minimal Stok</th>
                                            <th class="px-6 py-4 text-center">Status</th>
                                        </tr>
                                    </thead>

                                    <!-- Isi tabel -->
                                    <tbody class="divide-y divide-pink-50">

                                        <?php if (mysqli_num_rows($query_paket_stok) > 0) : ?>
                                            <?php $no = 1; ?>

                                            <!-- Perulangan paket stok -->
                                            <?php while ($paket = mysqli_fetch_assoc($query_paket_stok)) : ?>
                                                <?php
                                                    // Menghitung estimasi modal sederhana
                                                    $jumlah_stok = (int) $paket['jumlah_stok'];
                                                    $harga_beli = (int) $paket['harga_beli'];

                                                    $total_bahan += $jumlah_stok;
                                                    $total_estimasi_modal += $harga_beli;
                                                ?>

                                                <tr class="hover:bg-pink-50/20 transition">

                                                    <!-- Nomor -->
                                                    <td class="px-6 py-4 text-gray-500">
                                                        <?= $no++; ?>
                                                    </td>

                                                    <!-- Nama barang -->
                                                    <td class="px-6 py-4">
                                                        <p class="font-bold text-gray-800">
                                                            <?= htmlspecialchars($paket['nama_barang']); ?>
                                                        </p>
                                                    </td>

                                                    <!-- Jenis barang -->
                                                    <td class="px-6 py-4 text-gray-500">
                                                        <?= htmlspecialchars($paket['jenis_barang']); ?>
                                                    </td>

                                                    <!-- Jumlah dipakai -->
                                                    <td class="px-6 py-4 font-bold text-pink-600">
                                                        <?= htmlspecialchars($paket['jumlah_stok']); ?>
                                                        <?= htmlspecialchars($paket['satuan_barang']); ?>
                                                    </td>

                                                    <!-- Stok saat ini -->
                                                    <td class="px-6 py-4">
                                                        <span class="font-bold text-gray-800">
                                                            <?= htmlspecialchars($paket['jumlah_barang']); ?>
                                                        </span>
                                                        <span class="text-gray-400">
                                                            <?= htmlspecialchars($paket['satuan_barang']); ?>
                                                        </span>
                                                    </td>

                                                    <!-- Minimal stok -->
                                                    <td class="px-6 py-4">
                                                        <?= htmlspecialchars($paket['minimal_stok']); ?>
                                                        <?= htmlspecialchars($paket['satuan_barang']); ?>
                                                    </td>

                                                    <!-- Status stok -->
                                                    <td class="px-6 py-4 text-center">
                                                        <?php if ($paket['jumlah_barang'] <= $paket['minimal_stok']) : ?>
                                                            <span class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-bold rounded-lg uppercase">
                                                                Menipis
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-lg uppercase">
                                                                Aman
                                                            </span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>

                                        <?php else : ?>

                                            <!-- Pesan paket kosong -->
                                            <tr>
                                                <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic">
                                                    Paket stok untuk layanan ini belum diatur.
                                                </td>
                                            </tr>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer ringkasan -->
                            <div class="p-6 border-t border-pink-100 grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <!-- Total jenis bahan -->
                                <div class="p-4 bg-pink-50/40 rounded-2xl border border-pink-100">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                                        Total Pemakaian
                                    </p>

                                    <p class="text-lg font-bold text-gray-800 mt-1">
                                        <?= (int) $total_bahan; ?> Satuan
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Total jumlah bahan dari paket stok.
                                    </p>
                                </div>

                                <!-- Info tambahan bahan -->
                                <div class="p-4 bg-yellow-50/60 rounded-2xl border border-yellow-100">
                                    <p class="text-[11px] font-bold text-yellow-600 uppercase tracking-widest">
                                        Tambahan Bahan
                                    </p>
                                    
                                  

                                    
                                </div>                                        
                            </div>
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