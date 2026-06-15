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

// Mengambil id restok untuk mode edit
$id_edit = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$data_edit = null;

// Fungsi redirect restok
function redirect_restok($pesan)
{
    echo "<script>
            alert('$pesan');
            window.location.href = 'restok.php';
          </script>";
    exit;
}

// Fungsi format rupiah
function rupiah_restok($angka)
{
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

// Mengambil data edit restok
if ($id_edit > 0) {
    $query_edit = mysqli_query(
        $koneksi,
        "SELECT 
            r.*,
            s.nama_barang,
            s.jumlah_satuan,
            s.satuan_barang,
            s.harga_beli
         FROM restok r
         JOIN stok_barang s ON r.id_barang = s.id_barang
         WHERE r.id_restok = $id_edit
         LIMIT 1"
    );

    if ($query_edit && mysqli_num_rows($query_edit) > 0) {
        $data_edit = mysqli_fetch_assoc($query_edit);
        $id_barang_get = (int) $data_edit['id_barang'];
        $sub_title = "Edit Restok Barang";
    }
}

// Memproses tambah restok barang
if (isset($_POST['submit'])) {
    $id_barang = (int) ($_POST['id_barang'] ?? 0);
    $jumlah_tambah_awal = (int) ($_POST['jumlah_tambah_awal'] ?? 0);

    // Mengambil data barang berdasarkan id
    $barang = select("SELECT * FROM stok_barang WHERE id_barang = $id_barang");

    // Mengecek barang dan jumlah valid
    if (!empty($barang) && $jumlah_tambah_awal > 0) {
        $barang = $barang[0];

        // Jumlah tambah dikonversi ke satuan isi barang, contoh 2 botol x 100 ml
        $jumlah_tambah = $jumlah_tambah_awal * (int) $barang['jumlah_satuan'];

        // Harga beli per botol
        $harga_beli = (int) $barang['harga_beli'];

        // Menghitung total harga restok
        $total_harga_restok = $harga_beli * $jumlah_tambah_awal;

        // Menyimpan data restok untuk laporan pengeluaran
        mysqli_query(
            $koneksi,
            "INSERT INTO restok 
                (id_barang, jumlah_tambah, harga_restok, total_harga_restok) 
             VALUES 
                ($id_barang, $jumlah_tambah, $harga_beli, $total_harga_restok)"
        );

        // Menambahkan stok barang
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang 
             SET jumlah_barang = jumlah_barang + $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        redirect_restok('Restok berhasil ditambahkan dan masuk laporan pengeluaran!');
    }

    redirect_restok('Data barang atau jumlah restok tidak valid!');
}

// Memproses update restok barang
if (isset($_POST['update'])) {
    $id_restok = (int) ($_POST['id_restok'] ?? 0);
    $id_barang_baru = (int) ($_POST['id_barang'] ?? 0);
    $jumlah_tambah_awal_baru = (int) ($_POST['jumlah_tambah_awal'] ?? 0);

    if ($id_restok <= 0 || $id_barang_baru <= 0 || $jumlah_tambah_awal_baru <= 0) {
        redirect_restok('Data edit restok tidak lengkap!');
    }

    // Mengambil data restok lama
    $restok_lama = select("SELECT * FROM restok WHERE id_restok = $id_restok");

    if (empty($restok_lama)) {
        redirect_restok('Data restok yang akan diedit tidak ditemukan!');
    }

    $restok_lama = $restok_lama[0];
    $id_barang_lama = (int) $restok_lama['id_barang'];
    $jumlah_tambah_lama = (int) $restok_lama['jumlah_tambah'];

    // Mengambil data barang baru
    $barang_baru = select("SELECT * FROM stok_barang WHERE id_barang = $id_barang_baru");

    if (empty($barang_baru)) {
        redirect_restok('Barang baru tidak ditemukan!');
    }

    $barang_baru = $barang_baru[0];

    // Menghitung jumlah tambah baru dalam satuan isi barang
    $jumlah_tambah_baru = $jumlah_tambah_awal_baru * (int) $barang_baru['jumlah_satuan'];
    $harga_beli_baru = (int) $barang_baru['harga_beli'];
    $total_harga_restok_baru = $harga_beli_baru * $jumlah_tambah_awal_baru;

    // Memulai transaksi database agar stok dan restok tetap sinkron
    mysqli_begin_transaction($koneksi);

    try {
        // Mengembalikan stok barang lama terlebih dahulu
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang
             SET jumlah_barang = jumlah_barang - $jumlah_tambah_lama
             WHERE id_barang = $id_barang_lama"
        );

        // Menambahkan stok barang baru sesuai data edit
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang
             SET jumlah_barang = jumlah_barang + $jumlah_tambah_baru
             WHERE id_barang = $id_barang_baru"
        );

        // Mengupdate data restok
        mysqli_query(
            $koneksi,
            "UPDATE restok SET
                id_barang = $id_barang_baru,
                jumlah_tambah = $jumlah_tambah_baru,
                harga_restok = $harga_beli_baru,
                total_harga_restok = $total_harga_restok_baru
             WHERE id_restok = $id_restok"
        );

        mysqli_commit($koneksi);
        redirect_restok('Data restok berhasil diperbarui!');
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        redirect_restok('Data restok gagal diperbarui!');
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

        redirect_restok('Data restok berhasil dihapus!');
    }

    redirect_restok('Data restok tidak ditemukan!');
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
        stok_barang.satuan_barang,
        stok_barang.jumlah_satuan
    FROM restok
    JOIN stok_barang ON restok.id_barang = stok_barang.id_barang
    ORDER BY restok.id_restok DESC
");

// Menghitung total pengeluaran restok
$total_pengeluaran_restok = 0;

foreach ($data_restok as $restok_item) {
    $total_pengeluaran_restok += (int) $restok_item['total_harga_restok'];
}

// Menghitung jumlah awal untuk mode edit, contoh 200 ml / 100 ml = 2 botol
$jumlah_tambah_awal_edit = '';

if ($data_edit) {
    $jumlah_satuan_edit = (int) ($data_edit['jumlah_satuan'] ?? 1);
    $jumlah_satuan_edit = $jumlah_satuan_edit > 0 ? $jumlah_satuan_edit : 1;
    $jumlah_tambah_awal_edit = (int) ceil(((int) $data_edit['jumlah_tambah']) / $jumlah_satuan_edit);
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

                <!-- Section restok barang -->
                <section class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Kelola restok barang dan otomatis tampil sebagai pengeluaran di laporan.
                            </p>
                        </div>

                        <!-- Tombol kanan -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <?php if ($data_edit) : ?>
                                <a 
                                    href="restok.php" 
                                    class="inline-flex items-center justify-center gap-2 px-6 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Batal Edit</span>
                                </a>
                            <?php endif; ?>

                            <a 
                                href="data-stok.php" 
                                class="inline-flex items-center justify-center gap-2 px-6 py-2 text-sm font-bold text-[#C75C7A] bg-white border border-[#F7D6E4] rounded-xl hover:bg-[#FDEAF1] transition-colors"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>
                    </div>

                    <!-- Layout form dan tabel -->
                    <div class="grid grid-cols-1 gap-6 items-start">

                        <!-- Card form restok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                            <!-- Header form -->
                            <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                                <h4 class="font-bold text-[#3D3134]">
                                    <?= $data_edit ? 'Form Edit Restok' : 'Form Restok'; ?>
                                </h4>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    <?= $data_edit ? 'Edit restok akan menyesuaikan stok barang lama dan stok barang baru.' : 'Restok yang disimpan akan masuk sebagai pengeluaran di laporan.'; ?>
                                </p>
                            </div>

                            <!-- Form restok -->
                            <form action="" method="POST" class="p-6 space-y-4">

                                <?php if ($data_edit) : ?>
                                    <input type="hidden" name="id_restok" value="<?= (int) $data_edit['id_restok']; ?>">
                                <?php endif; ?>

                                <!-- Input id barang terpilih -->
                                <input 
                                    type="hidden" 
                                    name="id_barang" 
                                    id="id_barang"
                                    value="<?= $id_barang_get > 0 ? (int) $id_barang_get : ''; ?>"
                                    required
                                >

                                <!-- Pilihan barang lewat tombol popup -->
                                <div>
                                    <label class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Pilih Barang
                                    </label>

                                    <!-- Info barang terpilih -->
                                    <div id="barang-terpilih-box" class="mb-3 p-4 rounded-2xl bg-[#FDEAF1]/60 border border-[#F7D6E4] text-sm">
                                        <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                            Barang Terpilih
                                        </p>

                                        <p id="barang-terpilih-nama" class="font-bold text-[#C75C7A] mt-1">
                                            <?= $data_edit ? htmlspecialchars($data_edit['nama_barang']) : 'Belum ada barang dipilih'; ?>
                                        </p>

                                        <p id="barang-terpilih-detail" class="text-xs text-[#7A6F6F] mt-1">
                                            <?php if ($data_edit) : ?>
                                                Stok restok sekarang: <?= htmlspecialchars($data_edit['jumlah_tambah']); ?> <?= htmlspecialchars($data_edit['satuan_barang'] ?? ''); ?> | Isi per botol: <?= htmlspecialchars($data_edit['jumlah_satuan'] ?? 0); ?> <?= htmlspecialchars($data_edit['satuan_barang'] ?? ''); ?> | Harga: <?= rupiah_restok($data_edit['harga_beli']); ?> / botol
                                            <?php else : ?>
                                                Klik tombol pilih barang untuk melihat semua data barang.
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Input jumlah restok -->
                                <div>
                                    <label for="jumlah_tambah_awal" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Jumlah Tambah
                                    </label>

                                    <input
                                        type="number"
                                        name="jumlah_tambah_awal"
                                        id="jumlah_tambah_awal"
                                        required
                                        min="1"
                                        value="<?= htmlspecialchars($jumlah_tambah_awal_edit); ?>"
                                        placeholder="Masukkan jumlah restok"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >

                                    <p class="text-[11px] text-[#B77B8E] mt-2">
                                        Jumlah diisi berdasarkan satuan pembelian awal, contoh: 2 botol.
                                    </p>
                                </div>

                                <!-- Tombol aksi form -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">

                                    <!-- Tombol buka daftar barang -->
                                    <button
                                        type="button"
                                        onclick="openBarangModal()"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#FDEAF1] text-[#C75C7A] text-sm font-bold rounded-xl hover:bg-[#FAD7E5] transition"
                                    >
                                        <i class="fa-solid fa-box-open"></i>
                                        <span>Pilih Barang</span>
                                    </button>

                                    <!-- Tombol simpan/update -->
                                    <?php if ($data_edit) : ?>
                                        <button
                                            type="submit"
                                            name="update"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#C75C7A] text-white text-sm font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                        >
                                            <i class="fa-solid fa-floppy-disk"></i>
                                            <span>Simpan Perubahan</span>
                                        </button>
                                    <?php else : ?>
                                        <button
                                            type="submit"
                                            name="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#C75C7A] text-white text-sm font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                        >
                                            <i class="fa-solid fa-boxes-stacked"></i>
                                            <span>Simpan Restok</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Card tabel restok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                            <!-- Header tabel -->
                            <div class="p-5 sm:p-6 border-b border-[#F7D6E4] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-[#3D3134]">
                                        Data Restok Barang
                                    </h4>

                                    <p class="text-xs text-[#B77B8E] mt-1">
                                        Riwayat restok yang akan tampil di laporan. Gunakan edit jika terjadi salah input.
                                    </p>
                                </div>

                                <span class="text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full font-bold">
                                    Pengeluaran Restok: <?= rupiah_restok($total_pengeluaran_restok); ?>
                                </span>
                            </div>

                            <!-- Tabel restok -->
                            <div class="w-full overflow-x-auto">
                                <table class="w-full text-xs sm:text-sm text-left">

                                    <!-- Header tabel -->
                                    <thead class="bg-[#EFA9BF] text-white text-[11px] uppercase font-bold tracking-wider">
                                        <tr>
                                            <th class="px-3 py-4 whitespace-nowrap">No</th>
                                            <th class="px-3 py-4 min-w-[180px]">Nama Barang</th>
                                            <th class="px-3 py-4 whitespace-nowrap">Tanggal</th>
                                            <th class="px-3 py-4 whitespace-nowrap">Jumlah</th>
                                            <th class="px-3 py-4 whitespace-nowrap">Total Restok</th>
                                            <th class="px-3 py-4 whitespace-nowrap text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <!-- Isi tabel -->
                                    <tbody class="divide-y divide-[#F7D6E4]">

                                        <?php if (!empty($data_restok)) : ?>
                                            <?php $no = 1; ?>

                                            <!-- Perulangan data restok -->
                                            <?php foreach ($data_restok as $restok) : ?>
                                                <tr class="hover:bg-[#FDEAF1]/40 transition-colors">

                                                    <!-- Nomor -->
                                                    <td class="px-3 py-3">
                                                        <?= $no++; ?>
                                                    </td>

                                                    <!-- Nama barang -->
                                                    <td class="px-3 py-3 font-medium text-[#3D3134]">
                                                        <?= htmlspecialchars($restok['nama_barang']); ?>
                                                    </td>

                                                    <!-- Tanggal restok -->
                                                    <td class="px-3 py-3 text-[#7A6F6F] whitespace-nowrap">
                                                        <?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?>
                                                    </td>

                                                    <!-- Jumlah tambah -->
                                                    <td class="px-3 py-3 whitespace-nowrap">
                                                        <?= htmlspecialchars($restok['jumlah_tambah']); ?>
                                                        <?= htmlspecialchars($restok['satuan_barang'] ?? ''); ?>
                                                    </td>

                                                    <!-- Total harga restok -->
                                                    <td class="px-3 py-3 font-bold text-red-600 whitespace-nowrap">
                                                        <?= rupiah_restok($restok['total_harga_restok']); ?>
                                                    </td>

                                                    <!-- Tombol aksi -->
                                                    <td class="px-3 py-3">
                                                        <div class="flex items-center justify-center gap-2">

                                                            <!-- Tombol edit -->
                                                            <a
                                                                href="restok.php?edit=<?= (int) $restok['id_restok']; ?>"
                                                                class="inline-flex items-center justify-center w-8 h-8 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition shrink-0"
                                                                title="Edit"
                                                            >
                                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                            </a>

                                                            <!-- Tombol hapus -->
                                                            <a
                                                                href="?hapus=<?= (int) $restok['id_restok']; ?>"
                                                                onclick="return confirm('Yakin ingin menghapus data restok ini? Stok barang juga akan dikurangi kembali.')"
                                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition shrink-0"
                                                                title="Hapus"
                                                            >
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php else : ?>

                                            <!-- Pesan data kosong -->
                                            <tr>
                                                <td colspan="6" class="px-3 py-8 text-center text-[#B77B8E]">
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

<!-- Modal pilih barang restok -->
<div id="barang-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <!-- Box modal pilih barang -->
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header modal -->
        <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/70 flex items-start justify-between gap-4">
            <div>
                <h4 class="font-bold text-[#3D3134]">
                    Pilih Barang
                </h4>

                <p class="text-xs text-[#B77B8E] mt-1">
                    Semua data barang ditampilkan di sini. Klik pilih pada barang yang ingin direstok.
                </p>
            </div>

            <!-- Tombol tutup modal -->
            <button
                type="button"
                onclick="closeBarangModal()"
                class="w-9 h-9 rounded-xl bg-white text-[#C75C7A] border border-[#F7D6E4] hover:bg-[#FDEAF1] transition"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Isi modal -->
        <div class="p-4 sm:p-5 overflow-y-auto">

            <!-- Daftar semua barang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php foreach ($data_barang as $barang) : ?>
                    <?php
                    // Mengecek barang sudah terpilih
                    $is_selected_barang = $id_barang_get === (int) $barang['id_barang'];
                    ?>

                    <!-- Card barang -->
                    <div 
                        class="barang-option-card p-4 rounded-2xl border <?= $is_selected_barang ? 'border-[#C75C7A] bg-[#FDEAF1]' : 'border-[#F7D6E4] bg-white'; ?> hover:bg-[#FDEAF1]/70 transition"
                        data-id="<?= (int) $barang['id_barang']; ?>"
                    >
                        <div class="flex items-start justify-between gap-3">

                            <!-- Detail barang -->
                            <div class="min-w-0">
                                <p class="font-bold text-[#3D3134] text-sm">
                                    <?= htmlspecialchars($barang['nama_barang']); ?>
                                </p>

                                <p class="text-xs text-[#7A6F6F] mt-1">
                                    Stok:
                                    <b><?= htmlspecialchars($barang['jumlah_barang']); ?> <?= htmlspecialchars($barang['satuan_barang'] ?? ''); ?></b>
                                </p>

                                <p class="text-xs text-[#7A6F6F] mt-1">
                                    Isi per botol:
                                    <b><?= htmlspecialchars($barang['jumlah_satuan'] ?? 0); ?> <?= htmlspecialchars($barang['satuan_barang'] ?? ''); ?></b>
                                </p>

                                <p class="text-xs text-[#C75C7A] font-bold mt-1">
                                    Rp <?= number_format($barang['harga_beli'], 0, ',', '.'); ?> / botol
                                </p>
                            </div>

                            <!-- Tombol pilih -->
                            <button
                                type="button"
                                onclick="pilihBarangRestok(
                                    <?= (int) $barang['id_barang']; ?>,
                                    '<?= htmlspecialchars(addslashes($barang['nama_barang'])); ?>',
                                    '<?= htmlspecialchars(addslashes($barang['jumlah_barang'])); ?>',
                                    '<?= htmlspecialchars(addslashes($barang['satuan_barang'] ?? '')); ?>',
                                    '<?= htmlspecialchars(addslashes($barang['jumlah_satuan'] ?? 0)); ?>',
                                    '<?= number_format($barang['harga_beli'], 0, ',', '.'); ?>'
                                )"
                                class="btn-pilih-barang shrink-0 px-3 py-2 rounded-xl text-xs font-bold <?= $is_selected_barang ? 'bg-[#C75C7A] text-white' : 'bg-[#FDEAF1] text-[#C75C7A]'; ?> hover:bg-[#C75C7A] hover:text-white transition"
                            >
                                <?= $is_selected_barang ? 'Dipilih' : 'Pilih'; ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($data_barang)) : ?>
                <div class="p-8 text-center text-[#B77B8E] text-sm">
                    Belum ada data barang.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Script pilih barang restok -->
<script>
    // Membuka modal daftar barang
    function openBarangModal() {
        const modal = document.getElementById('barang-modal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Menutup modal daftar barang
    function closeBarangModal() {
        const modal = document.getElementById('barang-modal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Memilih barang dari daftar barang
    function pilihBarangRestok(idBarang, namaBarang, jumlahBarang, satuanBarang, jumlahSatuan, hargaBeli) {
        const inputIdBarang = document.getElementById('id_barang');
        const namaBox = document.getElementById('barang-terpilih-nama');
        const detailBox = document.getElementById('barang-terpilih-detail');
        const cards = document.querySelectorAll('.barang-option-card');
        const buttons = document.querySelectorAll('.btn-pilih-barang');

        if (inputIdBarang) {
            inputIdBarang.value = idBarang;
        }

        if (namaBox) {
            namaBox.textContent = namaBarang;
        }

        if (detailBox) {
            detailBox.textContent = 'Stok saat ini: ' + jumlahBarang + ' ' + satuanBarang + ' | Isi per botol: ' + jumlahSatuan + ' ' + satuanBarang + ' | Harga: Rp ' + hargaBeli + ' / botol';
        }

        cards.forEach(function (card) {
            card.classList.remove('border-[#C75C7A]', 'bg-[#FDEAF1]');
            card.classList.add('border-[#F7D6E4]', 'bg-white');

            if (Number(card.dataset.id) === Number(idBarang)) {
                card.classList.remove('border-[#F7D6E4]', 'bg-white');
                card.classList.add('border-[#C75C7A]', 'bg-[#FDEAF1]');
            }
        });

        buttons.forEach(function (button) {
            button.textContent = 'Pilih';
            button.classList.remove('bg-[#C75C7A]', 'text-white');
            button.classList.add('bg-[#FDEAF1]', 'text-[#C75C7A]');
        });

        const selectedButton = document.querySelector('.barang-option-card[data-id="' + idBarang + '"] .btn-pilih-barang');

        if (selectedButton) {
            selectedButton.textContent = 'Dipilih';
            selectedButton.classList.remove('bg-[#FDEAF1]', 'text-[#C75C7A]');
            selectedButton.classList.add('bg-[#C75C7A]', 'text-white');
        }

        closeBarangModal();
    }

    // Mengisi barang terpilih dari URL atau mode edit jika ada
    document.addEventListener('DOMContentLoaded', function () {
        const selectedCard = document.querySelector('.barang-option-card.border-\\[\\#C75C7A\\]');
        const selectedButton = selectedCard ? selectedCard.querySelector('.btn-pilih-barang') : null;

        if (selectedButton) {
            selectedButton.click();
        }
    });

    // Menutup modal saat klik area luar
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('barang-modal');

        if (!modal || modal.classList.contains('hidden')) return;

        if (event.target === modal) {
            closeBarangModal();
        }
    });

    // Menutup modal saat tombol escape ditekan
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeBarangModal();
        }
    });
</script>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>
