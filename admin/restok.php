<?php 
// Mengatur judul halaman
$page_title = "Restok Barang";

// Mengatur sub judul halaman
$sub_title = "Restok Barang";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil id barang dari URL jika ada
$id_barang_get = isset($_GET['id_barang']) ? (int) $_GET['id_barang'] : 0;

// Memproses tambah restok barang
if (isset($_POST['submit'])) {
    $id_barang = (int) $_POST['id_barang'];
    $jumlah_tambah = (int) $_POST['jumlah_tambah'];

    // Mengambil data barang berdasarkan id
    $barang = select("SELECT * FROM stok_barang WHERE id_barang = $id_barang");

    // Mengecek barang dan jumlah valid
    if (!empty($barang) && $jumlah_tambah > 0) {
        $barang = $barang[0];

        // Menghitung total harga restok
        $harga_beli = (int) $barang['harga_beli'];
        $total_harga_restok = $harga_beli * $jumlah_tambah;

        // Menyimpan data restok untuk laporan pengeluaran
        mysqli_query(
            $koneksi,
            "INSERT INTO restok 
                (id_barang, jumlah_tambah, total_harga_restok) 
             VALUES 
                ($id_barang, $jumlah_tambah, $total_harga_restok)"
        );

        // Menambahkan stok barang
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang 
             SET jumlah_barang = jumlah_barang + $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        echo "<script>
                alert('Restok berhasil ditambahkan dan masuk laporan pengeluaran!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data barang atau jumlah restok tidak valid!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    }
}

// Memproses hapus data restok
if (isset($_GET['hapus'])) {
    $id_restok = (int) $_GET['hapus'];

    // Mengambil data restok berdasarkan id
    $restok = select("SELECT * FROM restok WHERE id_restok = $id_restok");

    // Mengecek data restok ditemukan
    if (!empty($restok)) {
        $restok = $restok[0];

        $id_barang = (int) $restok['id_barang'];
        $jumlah_tambah = (int) $restok['jumlah_tambah'];

        // Mengurangi stok barang sesuai restok yang dihapus
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang
             SET jumlah_barang = jumlah_barang - $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        // Menghapus data restok dari laporan
        mysqli_query(
            $koneksi,
            "DELETE FROM restok
             WHERE id_restok = $id_restok"
        );

        echo "<script>
                alert('Data restok berhasil dihapus!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data restok tidak ditemukan!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    }
}

// Mengambil data barang untuk pilihan form
$data_barang = select("
    SELECT * FROM stok_barang
    ORDER BY nama_barang ASC
");

// Mengambil data restok untuk tabel
$data_restok = select("
    SELECT 
        restok.*,
        stok_barang.nama_barang,
        stok_barang.satuan_barang
    FROM restok
    JOIN stok_barang ON restok.id_barang = stok_barang.id_barang
    ORDER BY restok.id_restok DESC
");

// Menghitung total pengeluaran restok
$total_pengeluaran_restok = 0;

foreach ($data_restok as $restok_item) {
    $total_pengeluaran_restok += (int) $restok_item['total_harga_restok'];
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

                <!-- Section restok barang -->
                <section class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-gray-400 mt-1">
                                Kelola restok barang dan otomatis tampil sebagai pengeluaran di laporan.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-stok.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-white border border-pink-100 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Ringkasan restok -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        <!-- Card total data restok -->
                        <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Total Data Restok
                            </p>

                            <h4 class="text-2xl font-bold text-gray-800 mt-1">
                                <?= count($data_restok); ?>
                            </h4>
                        </div>

                        <!-- Card total pengeluaran restok -->
                        <div class="bg-white p-5 rounded-2xl border border-pink-100 shadow-sm md:col-span-2">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                                Total Pengeluaran Restok
                            </p>

                            <h4 class="text-2xl font-bold text-red-600 mt-1">
                                Rp <?= number_format($total_pengeluaran_restok, 0, ',', '.'); ?>
                            </h4>

                            <p class="text-xs text-gray-400 mt-2">
                                Nilai ini ikut dihitung sebagai pengeluaran pada laporan.
                            </p>
                        </div>
                    </div>

                    <!-- Layout form dan tabel -->
                    <div class="grid grid-cols-1 xl:grid-cols-[380px_1fr] gap-6 items-start">

                        <!-- Card form restok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                            <!-- Header form -->
                            <div class="p-6 border-b border-pink-100">
                                <h4 class="font-bold text-gray-700">
                                    Tambah Restok Barang
                                </h4>

                                <p class="text-xs text-gray-400 mt-1">
                                    Pilih barang dan masukkan jumlah tambahan stok.
                                </p>
                            </div>

                            <!-- Form restok -->
                            <form action="" method="POST" class="p-6 space-y-4">

                                <!-- Input pilih barang -->
                                <div>
                                    <label for="id_barang" class="block text-sm font-medium text-gray-700 mb-1">
                                        Pilih Barang
                                    </label>

                                    <select 
                                        name="id_barang"
                                        id="id_barang"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                    >
                                        <option value="">
                                            -- Pilih Barang --
                                        </option>

                                        <?php foreach ($data_barang as $barang) : ?>
                                            <option 
                                                value="<?= (int) $barang['id_barang']; ?>"
                                                <?= ($id_barang_get === (int) $barang['id_barang']) ? 'selected' : ''; ?>
                                            >
                                                <?= htmlspecialchars($barang['nama_barang']); ?>
                                                | Stok: <?= htmlspecialchars($barang['jumlah_barang']); ?>
                                                <?= htmlspecialchars($barang['satuan_barang'] ?? ''); ?>
                                                | Rp <?= number_format($barang['harga_beli'], 0, ',', '.'); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Input jumlah restok -->
                                <div>
                                    <label for="jumlah_tambah" class="block text-sm font-medium text-gray-700 mb-1">
                                        Jumlah Tambah
                                    </label>

                                    <input
                                        type="number"
                                        name="jumlah_tambah"
                                        id="jumlah_tambah"
                                        required
                                        min="1"
                                        placeholder="Masukkan jumlah restok"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                    >
                                </div>

                                <!-- Info laporan -->
                                <div class="p-4 bg-pink-50/50 border border-pink-100 rounded-xl text-xs text-gray-500 leading-relaxed">
                                    Setelah disimpan, total restok akan masuk ke laporan sebagai pengeluaran restok.
                                </div>

                                <!-- Tombol simpan -->
                                <button
                                    type="submit"
                                    name="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition-colors"
                                >
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                    <span>Simpan Restok</span>
                                </button>
                            </form>
                        </div>

                        <!-- Card tabel restok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                            <!-- Header tabel -->
                            <div class="p-6 border-b border-pink-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-gray-700">
                                        Data Restok Barang
                                    </h4>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Riwayat restok yang akan tampil di laporan.
                                    </p>
                                </div>

                                <span class="text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full font-bold">
                                    Pengeluaran Restok
                                </span>
                            </div>

                            <!-- Tabel restok -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left min-w-[800px]">

                                    <!-- Header tabel -->
                                    <thead class="bg-pink-50 text-gray-600">
                                        <tr>
                                            <th class="px-6 py-3">No</th>
                                            <th class="px-6 py-3">Nama Barang</th>
                                            <th class="px-6 py-3">Tanggal</th>
                                            <th class="px-6 py-3">Jumlah</th>
                                            <th class="px-6 py-3">Total Restok</th>
                                            <th class="px-6 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <!-- Isi tabel -->
                                    <tbody class="divide-y divide-pink-100">

                                        <?php if (!empty($data_restok)) : ?>
                                            <?php $no = 1; ?>

                                            <!-- Perulangan data restok -->
                                            <?php foreach ($data_restok as $restok) : ?>
                                                <tr class="hover:bg-pink-50/40 transition-colors">

                                                    <!-- Nomor -->
                                                    <td class="px-6 py-4">
                                                        <?= $no++; ?>
                                                    </td>

                                                    <!-- Nama barang -->
                                                    <td class="px-6 py-4 font-medium text-gray-700">
                                                        <?= htmlspecialchars($restok['nama_barang']); ?>
                                                    </td>

                                                    <!-- Tanggal restok -->
                                                    <td class="px-6 py-4 text-gray-500">
                                                        <?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?>
                                                    </td>

                                                    <!-- Jumlah tambah -->
                                                    <td class="px-6 py-4">
                                                        <?= htmlspecialchars($restok['jumlah_tambah']); ?>
                                                        <?= htmlspecialchars($restok['satuan_barang'] ?? ''); ?>
                                                    </td>

                                                    <!-- Total harga restok -->
                                                    <td class="px-6 py-4 font-bold text-red-600">
                                                        Rp <?= number_format($restok['total_harga_restok'], 0, ',', '.'); ?>
                                                    </td>

                                                    <!-- Tombol aksi -->
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center justify-center">
                                                            <a
                                                                href="?hapus=<?= (int) $restok['id_restok']; ?>"
                                                                onclick="return confirm('Yakin ingin menghapus data restok ini? Stok barang juga akan dikurangi kembali.')"
                                                                class="inline-flex items-center gap-2 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-xs font-bold"
                                                            >
                                                                <i class="fa-solid fa-trash"></i>
                                                                <span>Hapus</span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php else : ?>

                                            <!-- Pesan data kosong -->
                                            <tr>
                                                <td colspan="6" class="px-6 py-8 text-center text-gray-400">
                                                    Data restok belum tersedia.
                                                </td>
                                            </tr>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
