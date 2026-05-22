<?php 

// ============================
// JUDUL HALAMAN
// ============================

$page_title = "Restok Barang";
$sub_title  = "Restok Barang";


// ============================
// PANGGIL FILE
// ============================

include "../layout/header.php";
include "../config/app.php";
global $koneksi;

// ============================
// AMBIL ID BARANG DARI BUTTON
// ============================

$id_barang_get = isset($_GET['id_barang']) 
    ? $_GET['id_barang'] 
    : '';


// ============================
// TAMBAH RESTOK
// ============================

if (isset($_POST['submit'])) {

    $id_barang     = htmlspecialchars($_POST['id_barang']);
    $jumlah_tambah = htmlspecialchars($_POST['jumlah_tambah']);

    // Ambil data barang
    $barang = select("
        SELECT * FROM stok_barang 
        WHERE id_barang = '$id_barang'
    ");

    // Cek barang ditemukan
    if ($barang) {

        $barang = $barang[0];

        // Ambil harga beli
        $harga_beli = $barang['harga_beli'];

        // Hitung total harga restok
        $total_harga_restok = $harga_beli * $jumlah_tambah;

        // Simpan data restok
        mysqli_query($koneksi, "
            INSERT INTO restok (
                id_barang,
                jumlah_tambah,
                total_harga_restok
            ) VALUES (
                '$id_barang',
                '$jumlah_tambah',
                '$total_harga_restok'
            )
        ");

        // Tambahkan stok barang
        mysqli_query($koneksi, "
            UPDATE stok_barang 
            SET jumlah_barang = jumlah_barang + '$jumlah_tambah'
            WHERE id_barang = '$id_barang'
        ");

        echo "
        <script>
            alert('Restok berhasil ditambahkan!');
            window.location.href='restok.php';
        </script>
        ";
    }
}


// ============================
// HAPUS RESTOK
// ============================

if (isset($_GET['hapus'])) {

    $id_restok = (int) $_GET['hapus'];

    // Ambil data restok
    $restok = select("
        SELECT * FROM restok
        WHERE id_restok = '$id_restok'
    ");

    // Cek data ditemukan
    if ($restok) {

        $restok = $restok[0];

        $id_barang     = $restok['id_barang'];
        $jumlah_tambah = $restok['jumlah_tambah'];

        // Kurangi stok barang
        mysqli_query($koneksi, "
            UPDATE stok_barang
            SET jumlah_barang = jumlah_barang - '$jumlah_tambah'
            WHERE id_barang = '$id_barang'
        ");

        // Hapus data restok
        mysqli_query($koneksi, "
            DELETE FROM restok
            WHERE id_restok = '$id_restok'
        ");

        echo "
        <script>
            alert('Data restok berhasil dihapus!');
            window.location.href='restok.php';
        </script>
        ";
    }
}

?>

<body class="text-gray-800 overflow-x-hidden">

    <!-- WRAPPER -->
    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <?php include "../layout/sidebar.php"; ?>

        <!-- MAIN -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">

            <!-- NAVBAR -->
            <?php include "../layout/navbar.php"; ?>

            <!-- CONTENT -->
            <div class="p-4 md:p-8 flex-1">

                <!-- SECTION -->
                <section class="space-y-6">

                    <!-- HEADER -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <div>

                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Kelola data restok barang Mey Salon.
                            </p>

                        </div>

                    </div>


                    <!-- CARD FORM -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- HEADER CARD -->
                        <div class="p-6 border-b border-pink-100">

                            <h4 class="font-bold text-gray-700">
                                Tambah Restok Barang
                            </h4>

                        </div>


                        <!-- FORM -->
                        <form action="" method="POST" class="p-6 space-y-4">

                            <!-- PILIH BARANG -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Pilih Barang
                                </label>

                                <select 
                                    name="id_barang"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >

                                    <option value="">
                                        -- Pilih Barang --
                                    </option>

                                    <?php
                                    
                                    $data_barang = select("
                                        SELECT * FROM stok_barang
                                        ORDER BY nama_barang ASC
                                    ");

                                    foreach ($data_barang as $barang) :
                                    
                                    ?>

                                        <option 
                                            value="<?= $barang['id_barang']; ?>"
                                            <?= ($id_barang_get == $barang['id_barang']) ? 'selected' : ''; ?>
                                        >

                                            <?= $barang['nama_barang']; ?>

                                            | Stok : <?= $barang['jumlah_barang']; ?>

                                            | Rp <?= number_format($barang['harga_beli']); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>


                            <!-- JUMLAH RESTOK -->
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Tambah
                                </label>

                                <input
                                    type="number"
                                    name="jumlah_tambah"
                                    required
                                    min="1"
                                    placeholder="Masukkan jumlah restok"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                                >

                            </div>


                            <!-- BUTTON -->
                            <div class="pt-2">

                                <button
                                    type="submit"
                                    name="submit"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-500 text-white font-bold rounded-lg hover:bg-green-600 transition-colors"
                                >

                                    <i class="fa-solid fa-boxes-stacked"></i>

                                    <span>
                                        Simpan Restok
                                    </span>

                                </button>

                            </div>

                        </form>

                    </div>


                    <!-- CARD TABEL -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">

                        <!-- HEADER -->
                        <div class="p-6 border-b border-pink-100">

                            <h4 class="font-bold text-gray-700">
                                Data Restok Barang
                            </h4>

                        </div>


                        <!-- TABEL -->
                        <div class="overflow-x-auto">

                            <table class="w-full text-sm text-left">

                                <!-- HEAD -->
                                <thead class="bg-pink-50 text-gray-600">

                                    <tr>

                                        <th class="px-6 py-3">
                                            No
                                        </th>

                                        <th class="px-6 py-3">
                                            Nama Barang
                                        </th>

                                        <th class="px-6 py-3">
                                            Tanggal Restok
                                        </th>

                                        <th class="px-6 py-3">
                                            Jumlah Tambah
                                        </th>

                                        <th class="px-6 py-3">
                                            Total Harga
                                        </th>

                                        <th class="px-6 py-3 text-center">
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>


                                <!-- BODY -->
                                <tbody class="divide-y divide-pink-100">

                                    <?php
                                    
                                    $no = 1;

                                    $data_restok = select("
                                        SELECT 
                                            restok.*,
                                            stok_barang.nama_barang
                                        FROM restok
                                        JOIN stok_barang
                                        ON restok.id_barang = stok_barang.id_barang
                                        ORDER BY restok.id_restok DESC
                                    ");

                                    ?>

                                    <?php if (!empty($data_restok)) : ?>

                                        <?php foreach ($data_restok as $restok) : ?>

                                            <tr class="hover:bg-pink-50/40 transition-colors">

                                                <!-- NO -->
                                                <td class="px-6 py-4">
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- NAMA BARANG -->
                                                <td class="px-6 py-4 font-medium text-gray-700">
                                                    <?= htmlspecialchars($restok['nama_barang']); ?>
                                                </td>

                                                <!-- TANGGAL -->
                                                <td class="px-6 py-4">
                                                    <?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?>
                                                </td>

                                                <!-- JUMLAH -->
                                                <td class="px-6 py-4">
                                                    <?= $restok['jumlah_tambah']; ?>
                                                </td>

                                                <!-- TOTAL -->
                                                <td class="px-6 py-4">
                                                    Rp <?= number_format($restok['total_harga_restok']); ?>
                                                </td>

                                                <!-- AKSI -->
                                                <td class="px-6 py-4">

                                                    <div class="flex items-center justify-center">

                                                        <a
                                                            href="?hapus=<?= $restok['id_restok']; ?>"
                                                            onclick="return confirm('Yakin ingin menghapus data restok ini?')"
                                                            class="inline-flex items-center gap-2 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-xs font-bold"
                                                        >

                                                            <i class="fa-solid fa-trash"></i>

                                                            Hapus

                                                        </a>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>

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

            <!-- FOOTER -->
            <?php include "../layout/footer-component.php"; ?>

        </main>

    </div>

<?php include "../layout/footer.php"; ?>