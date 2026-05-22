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

    // Mengecek barang ditemukan dan jumlah valid
    if (!empty($barang) && $jumlah_tambah > 0) {
        $barang = $barang[0];

        // Menghitung total harga restok
        $harga_beli = (int) $barang['harga_beli'];
        $total_harga_restok = $harga_beli * $jumlah_tambah;

        // Menyimpan data restok
        mysqli_query(
            $koneksi,
            "INSERT INTO restok 
                (id_barang, jumlah_tambah, total_harga_restok) 
             VALUES 
                ($id_barang, $jumlah_tambah, $total_harga_restok)"
        );

        // Menambahkan jumlah stok barang
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang 
             SET jumlah_barang = jumlah_barang + $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        echo "<script>
                alert('Restok berhasil ditambahkan!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data barang tidak valid!');
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

        // Mengurangi stok barang sesuai jumlah restok
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang
             SET jumlah_barang = jumlah_barang - $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        // Menghapus data restok
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

// Mengambil data barang untuk select form
$data_barang = select("
    SELECT * FROM stok_barang
    ORDER BY nama_barang ASC
");

// Mengambil data restok untuk tabel
$data_restok = select("
    SELECT 
        restok.*,
        stok_barang.nama_barang
    FROM restok
    JOIN stok_barang ON restok.id_barang = stok_barang.id_barang
    ORDER BY restok.id_restok DESC
");
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
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Kelola data restok barang Mey Salon.
                            </p>

                            <!-- Tombol kembali -->
                            <a 
                                href="data-stok.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>

                        </div>
                    </div>

                    <!-- Card form restok -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Header card form -->
                        <div class="p-6 border-b border-pink-100">
                            <h4 class="font-bold text-gray-700">
                                Tambah Restok Barang
                            </h4>
                        </div>

                        <!-- Form restok barang -->
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

                            <!-- Tombol simpan restok -->
                            <div class="pt-2">
                                <button
                                    type="submit"
                                    name="submit"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition-colors"
                                >
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                    <span>Simpan Restok</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Card tabel restok -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- Header card tabel -->
                        <div class="p-6 border-b border-pink-100">
                            <h4 class="font-bold text-gray-700">
                                Data Restok Barang
                            </h4>
                        </div>

                        <!-- Tabel restok responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left min-w-[800px]">

                                <!-- Header tabel -->
                                <thead class="bg-pink-50 text-gray-600">
                                    <tr>
                                        <th class="px-6 py-3">No</th>
                                        <th class="px-6 py-3">Nama Barang</th>
                                        <th class="px-6 py-3">Tanggal Restok</th>
                                        <th class="px-6 py-3">Jumlah Tambah</th>
                                        <th class="px-6 py-3">Total Harga</th>
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

                                                <!-- Nomor urut -->
                                                <td class="px-6 py-4">
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- Nama barang -->
                                                <td class="px-6 py-4 font-medium text-gray-700">
                                                    <?= htmlspecialchars($restok['nama_barang']); ?>
                                                </td>

                                                <!-- Tanggal restok -->
                                                <td class="px-6 py-4">
                                                    <?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?>
                                                </td>

                                                <!-- Jumlah tambah -->
                                                <td class="px-6 py-4">
                                                    <?= htmlspecialchars($restok['jumlah_tambah']); ?>
                                                </td>

                                                <!-- Total harga restok -->
                                                <td class="px-6 py-4">
                                                    Rp <?= number_format($restok['total_harga_restok'], 0, ',', '.'); ?>
                                                </td>

                                                <!-- Tombol aksi -->
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center justify-center">
                                                        <a
                                                            href="?hapus=<?= (int) $restok['id_restok']; ?>"
                                                            onclick="return confirm('Yakin ingin menghapus data restok ini?')"
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
                                            <td colspan="6" class="px-6 py-6 text-center text-gray-400">
                                                Data restok belum tersedia.
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                </tbody>
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