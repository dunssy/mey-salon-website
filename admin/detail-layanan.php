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

// Memproses tambah, edit, dan hapus paket stok
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Menambah paket stok
    if ($action === 'tambah_paket') {
        $id_barang = isset($_POST['id_barang']) ? (int) $_POST['id_barang'] : 0;
        $jumlah_stok = isset($_POST['jumlah_stok']) ? (int) $_POST['jumlah_stok'] : 0;

        if ($id_barang > 0 && $jumlah_stok > 0) {
            $cek_duplikat = mysqli_query(
                $koneksi,
                "SELECT id_paket FROM paket_stok 
                 WHERE id_layanan = $id_layanan AND id_barang = $id_barang"
            );

            if (mysqli_num_rows($cek_duplikat) > 0) {
                echo "<script>alert('Barang ini sudah ada di paket stok layanan ini!');</script>";
            } else {
                $insert_paket = mysqli_query(
                    $koneksi,
                    "INSERT INTO paket_stok (id_layanan, id_barang, jumlah_stok) 
                     VALUES ($id_layanan, $id_barang, $jumlah_stok)"
                );

                if ($insert_paket) {
                    echo "<script>
                            alert('Paket stok berhasil ditambahkan!');
                            window.location.href = window.location.href;
                          </script>";
                    exit;
                }

                echo "<script>alert('Gagal menambahkan paket stok!');</script>";
            }
        } else {
            echo "<script>alert('Pilih barang dan masukkan jumlah stok yang valid!');</script>";
        }
    }

    // Mengedit paket stok
    if ($action === 'edit_paket') {
        $id_paket = isset($_POST['id_paket']) ? (int) $_POST['id_paket'] : 0;
        $jumlah_stok = isset($_POST['jumlah_stok']) ? (int) $_POST['jumlah_stok'] : 0;

        if ($id_paket > 0 && $jumlah_stok > 0) {
            $update_paket = mysqli_query(
                $koneksi,
                "UPDATE paket_stok 
                 SET jumlah_stok = $jumlah_stok 
                 WHERE id_paket = $id_paket AND id_layanan = $id_layanan"
            );

            if ($update_paket) {
                echo "<script>
                        alert('Paket stok berhasil diperbarui!');
                        window.location.href = window.location.href;
                      </script>";
                exit;
            }

            echo "<script>alert('Gagal memperbarui paket stok!');</script>";
        }
    }

    // Menghapus paket stok
    if ($action === 'hapus_paket') {
        $id_paket = isset($_POST['id_paket']) ? (int) $_POST['id_paket'] : 0;

        if ($id_paket > 0) {
            $delete_paket = mysqli_query(
                $koneksi,
                "DELETE FROM paket_stok 
                 WHERE id_paket = $id_paket AND id_layanan = $id_layanan"
            );

            if ($delete_paket) {
                echo "<script>
                        alert('Paket stok berhasil dihapus!');
                        window.location.href = window.location.href;
                      </script>";
                exit;
            }

            echo "<script>alert('Gagal menghapus paket stok!');</script>";
        }
    }
}

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

// Mengambil semua stok barang untuk modal tambah paket
$query_semua_stok = mysqli_query(
    $koneksi,
    "SELECT id_barang, nama_barang, jenis_barang, jumlah_barang, satuan_barang, minimal_stok 
     FROM stok_barang 
     ORDER BY nama_barang ASC"
);
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

                <!-- Section detail layanan -->
                <section id="section-detail-layanan" class="space-y-6">

                    <!-- Header halaman -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- Judul halaman -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    <?= htmlspecialchars($sub_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Kelola paket stok yang digunakan oleh layanan
                                    <b class="text-[#C75C7A]"><?= htmlspecialchars($layanan['nama_layanan']); ?></b>.
                                </p>
                            </div>

                            <!-- Tombol kembali -->
                            <a 
                                href="data-layanan.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors w-fit"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>

                    <!-- Card paket stok -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header paket stok -->
                        <div class="p-5 sm:p-6 border-b border-[#F7D6E4] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-[#3D3134]">
                                    Paket Stok Digunakan
                                </h4>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Daftar bahan yang otomatis berkurang saat layanan selesai.
                                </p>
                            </div>

                            <button 
                                type="button"
                                onclick="openTambahPaketModal()"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#C75C7A] text-white text-sm font-bold rounded-xl hover:bg-[#B14F6C] transition-colors w-full sm:w-auto"
                            >
                                <i class="fa-solid fa-plus"></i>
                                <span>Tambah Bahan</span>
                            </button>
                        </div>

                        <!-- Tabel paket stok -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[900px] text-sm">

                                <!-- Header tabel -->
                                <thead class="bg-[#EFA9BF] text-white text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-5 py-4">No</th>
                                        <th class="px-5 py-4">Nama Barang</th>
                                        <th class="px-5 py-4">Jenis</th>
                                        <th class="px-5 py-4">Dipakai</th>
                                        <th class="px-5 py-4">Stok Saat Ini</th>
                                        <th class="px-5 py-4">Minimal Stok</th>
                                        <th class="px-5 py-4 text-center">Status</th>
                                        <th class="px-5 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel -->
                                <tbody class="divide-y divide-[#F7D6E4]">

                                    <?php if (mysqli_num_rows($query_paket_stok) > 0) : ?>
                                        <?php $no = 1; ?>

                                        <!-- Perulangan paket stok -->
                                        <?php while ($paket = mysqli_fetch_assoc($query_paket_stok)) : ?>
                                            <?php
                                                $jumlah_stok = (int) $paket['jumlah_stok'];
                                                $total_bahan += $jumlah_stok;
                                            ?>

                                            <tr class="hover:bg-[#FDEAF1]/50 transition">

                                                <!-- Nomor -->
                                                <td class="px-5 py-4 text-[#7A6F6F]">
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- Nama barang -->
                                                <td class="px-5 py-4">
                                                    <p class="font-bold text-[#2B2424]">
                                                        <?= htmlspecialchars($paket['nama_barang']); ?>
                                                    </p>
                                                </td>

                                                <!-- Jenis barang -->
                                                <td class="px-5 py-4 text-[#7A6F6F]">
                                                    <?= htmlspecialchars($paket['jenis_barang']); ?>
                                                </td>

                                                <!-- Jumlah dipakai -->
                                                <td class="px-5 py-4 font-bold text-[#C75C7A]">
                                                    <?= htmlspecialchars($paket['jumlah_stok']); ?>
                                                    <?= htmlspecialchars($paket['satuan_barang']); ?>
                                                </td>

                                                <!-- Stok saat ini -->
                                                <td class="px-5 py-4">
                                                    <span class="font-bold text-[#2B2424]">
                                                        <?= htmlspecialchars($paket['jumlah_barang']); ?>
                                                    </span>

                                                    <span class="text-[#B77B8E]">
                                                        <?= htmlspecialchars($paket['satuan_barang']); ?>
                                                    </span>
                                                </td>

                                                <!-- Minimal stok -->
                                                <td class="px-5 py-4 text-[#6F5E64]">
                                                    <?= htmlspecialchars($paket['minimal_stok']); ?>
                                                    <?= htmlspecialchars($paket['satuan_barang']); ?>
                                                </td>

                                                <!-- Status stok -->
                                                <td class="px-5 py-4 text-center">
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

                                                <!-- Aksi -->
                                                <td class="px-5 py-4 text-center">
                                                    <div class="flex items-center justify-center gap-2">

                                                        <!-- Tombol edit -->
                                                        <button 
                                                            type="button"
                                                            onclick="openEditPaketModal(<?= (int) $paket['id_paket']; ?>, <?= (int) $paket['jumlah_stok']; ?>, '<?= htmlspecialchars($paket['nama_barang'], ENT_QUOTES); ?>')"
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition"
                                                            title="Edit"
                                                        >
                                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                        </button>

                                                        <!-- Tombol hapus -->
                                                        <form method="POST" class="inline" onsubmit="return confirm('Hapus paket stok ini?');">
                                                            <input type="hidden" name="action" value="hapus_paket">
                                                            <input type="hidden" name="id_paket" value="<?= (int) $paket['id_paket']; ?>">

                                                            <button 
                                                                type="submit"
                                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition"
                                                                title="Hapus"
                                                            >
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>

                                    <?php else : ?>

                                        <!-- Pesan paket kosong -->
                                        <tr>
                                            <td colspan="8" class="px-6 py-10 text-center text-[#B77B8E] italic">
                                                Paket stok untuk layanan ini belum diatur.
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer ringkasan -->
                        <div class="p-5 sm:p-6 border-t border-[#F7D6E4] bg-[#FFF7FA]/70">
                            <div class="p-4 bg-white rounded-2xl border border-[#F7D6E4] flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-bold text-[#B77B8E] uppercase tracking-widest">
                                        Total Pemakaian
                                    </p>

                                    <p class="text-lg font-bold text-[#2B2424] mt-1">
                                        <?= (int) $total_bahan; ?> Satuan
                                    </p>
                                </div>

                                <p class="text-xs text-[#7A6F6F] max-w-md">
                                    Total ini adalah jumlah bahan dari semua paket stok layanan ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Modal tambah paket stok -->
            <div id="tambah-paket-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">
                <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden">

                    <!-- Header modal tambah -->
                    <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/60 flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#2B2424]">
                                Tambah Paket Stok
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Pilih barang dan jumlah yang akan digunakan.
                            </p>
                        </div>

                        <button 
                            type="button" 
                            onclick="closeTambahPaketModal()" 
                            class="w-9 h-9 rounded-xl bg-white text-[#C75C7A] border border-[#F7D6E4] hover:bg-red-50 hover:text-red-500 transition"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Form modal tambah -->
                    <form method="POST" class="p-5 space-y-4">
                        <input type="hidden" name="action" value="tambah_paket">

                        <!-- Pilih barang dengan popup -->
                        <div>
                            <label class="block text-sm font-bold text-[#3D3134] mb-2">
                                Pilih Barang
                            </label>

                            <!-- Input id barang yang dikirim ke database -->
                            <input 
                                type="hidden" 
                                id="id_barang_tambah" 
                                name="id_barang" 
                                required
                            >

                            <!-- Box barang terpilih -->
                            <div id="barang-paket-terpilih-box" class="p-4 rounded-2xl border border-[#F7D6E4] bg-[#FFF7FA] mb-3">
                                <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                    Barang Terpilih
                                </p>

                                <p id="barang-paket-terpilih-nama" class="text-sm font-bold text-[#C75C7A] mt-1">
                                    Belum ada barang dipilih
                                </p>

                                <p id="barang-paket-terpilih-detail" class="text-xs text-[#7A6F6F] mt-1">
                                    Klik tombol pilih barang untuk melihat semua data barang.
                                </p>
                            </div>

                            <!-- Tombol buka popup barang -->
                            <button 
                                type="button"
                                onclick="openPilihBarangPaketModal()"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#FDEAF1] text-[#C75C7A] text-sm font-bold rounded-xl hover:bg-[#FAD7E5] transition"
                            >
                                <i class="fa-solid fa-box-open"></i>
                                <span>Pilih Barang</span>
                            </button>
                        </div>

                        <!-- Jumlah stok -->
                        <div>
                            <label for="jumlah_stok_tambah" class="block text-sm font-bold text-[#3D3134] mb-2">
                                Jumlah Stok yang Dipakai
                            </label>

                            <input 
                                type="number" 
                                id="jumlah_stok_tambah" 
                                name="jumlah_stok" 
                                min="1" 
                                required 
                                placeholder="Contoh: 5"
                                class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                            >
                        </div>

                        <!-- Aksi modal tambah -->
                        <div class="flex flex-col sm:flex-row gap-2 pt-2">
                            <button 
                                type="button" 
                                onclick="closeTambahPaketModal()" 
                                class="w-full px-4 py-3 bg-[#F8F4F2] text-[#7A6F6F] font-bold rounded-xl hover:bg-[#EFE7E4] transition"
                            >
                                Batal
                            </button>

                            <button 
                                type="submit" 
                                class="w-full px-4 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] transition flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-check"></i>
                                <span>Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Modal pilih barang paket stok -->
            <div id="pilih-barang-paket-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">

                <!-- Card modal pilih barang -->
                <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden max-h-[90vh] flex flex-col">

                    <!-- Header modal pilih barang -->
                    <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/70 flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#2B2424]">
                                Pilih Barang
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Cari dan pilih barang yang akan digunakan untuk paket stok layanan.
                            </p>
                        </div>

                        <!-- Tombol tutup modal -->
                        <button 
                            type="button"
                            onclick="closePilihBarangPaketModal()"
                            class="w-9 h-9 rounded-xl bg-white text-[#C75C7A] border border-[#F7D6E4] hover:bg-red-50 hover:text-red-500 transition"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Input pencarian barang -->
                    <div class="p-4 border-b border-[#F7D6E4] bg-white">
                        <div class="relative">
                            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#B77B8E] text-sm"></i>

                            <input 
                                type="text"
                                id="search-barang-paket"
                                oninput="filterBarangPaket()"
                                placeholder="Cari nama barang..."
                                class="w-full pl-11 pr-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                            >
                        </div>
                    </div>

                    <!-- Daftar barang -->
                    <div class="p-4 sm:p-5 overflow-y-auto">
                        <div id="list-barang-paket" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php 
                            mysqli_data_seek($query_semua_stok, 0);
                            if (mysqli_num_rows($query_semua_stok) > 0) :
                                while ($stok = mysqli_fetch_assoc($query_semua_stok)) : 
                            ?>
                                <!-- Card barang -->
                                <div 
                                    class="barang-paket-card p-4 rounded-2xl border border-[#F7D6E4] bg-white hover:bg-[#FDEAF1]/60 transition"
                                    data-nama="<?= strtolower(htmlspecialchars($stok['nama_barang'])); ?>"
                                >
                                    <div class="flex items-start justify-between gap-3">

                                        <!-- Detail barang -->
                                        <div class="min-w-0">
                                            <h5 class="text-sm font-bold text-[#2B2424]">
                                                <?= htmlspecialchars($stok['nama_barang']); ?>
                                            </h5>

                                            <p class="text-xs text-[#7A6F6F] mt-1">
                                                Jenis:
                                                <b><?= htmlspecialchars($stok['jenis_barang']); ?></b>
                                            </p>

                                            <p class="text-xs text-[#7A6F6F] mt-1">
                                                Stok:
                                                <b><?= htmlspecialchars($stok['jumlah_barang']); ?> <?= htmlspecialchars($stok['satuan_barang']); ?></b>
                                            </p>

                                            <p class="text-[11px] text-[#B77B8E] mt-1">
                                                Minimal stok: <?= htmlspecialchars($stok['minimal_stok']); ?> <?= htmlspecialchars($stok['satuan_barang']); ?>
                                            </p>
                                        </div>

                                        <!-- Tombol pilih barang -->
                                        <button
                                            type="button"
                                            onclick="pilihBarangPaket(
                                                <?= (int) $stok['id_barang']; ?>,
                                                '<?= htmlspecialchars(addslashes($stok['nama_barang'])); ?>',
                                                '<?= htmlspecialchars(addslashes($stok['jenis_barang'])); ?>',
                                                '<?= htmlspecialchars(addslashes($stok['jumlah_barang'])); ?>',
                                                '<?= htmlspecialchars(addslashes($stok['satuan_barang'])); ?>'
                                            )"
                                            class="btn-pilih-barang-paket shrink-0 px-3 py-2 rounded-xl text-xs font-bold bg-[#FDEAF1] text-[#C75C7A] hover:bg-[#C75C7A] hover:text-white transition"
                                        >
                                            Pilih
                                        </button>
                                    </div>
                                </div>
                            <?php 
                                endwhile;
                            else :
                            ?>
                                <div class="md:col-span-2 p-8 text-center text-[#B77B8E] text-sm">
                                    Data barang tidak tersedia.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Pesan barang tidak ditemukan -->
                        <div id="barang-paket-empty" class="hidden p-8 text-center text-[#B77B8E] text-sm">
                            Barang tidak ditemukan.
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal edit paket stok -->
            <div id="edit-paket-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">
                <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden">

                    <!-- Header modal edit -->
                    <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/60 flex items-start justify-between gap-4">
                        <div>
                            <h4 class="text-lg font-bold text-[#2B2424]">
                                Edit Paket Stok
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Ubah jumlah stok yang dipakai.
                            </p>
                        </div>

                        <button 
                            type="button" 
                            onclick="closeEditPaketModal()" 
                            class="w-9 h-9 rounded-xl bg-white text-[#C75C7A] border border-[#F7D6E4] hover:bg-red-50 hover:text-red-500 transition"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Form modal edit -->
                    <form method="POST" class="p-5 space-y-4">
                        <input type="hidden" name="action" value="edit_paket">
                        <input type="hidden" id="edit_id_paket" name="id_paket" value="">

                        <!-- Nama barang -->
                        <div>
                            <label class="block text-sm font-bold text-[#3D3134] mb-2">
                                Nama Barang
                            </label>

                            <input 
                                type="text" 
                                id="edit_nama_barang" 
                                readonly 
                                class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl bg-[#F8F4F2] text-[#3D3134]"
                            >
                        </div>

                        <!-- Jumlah stok -->
                        <div>
                            <label for="jumlah_stok_edit" class="block text-sm font-bold text-[#3D3134] mb-2">
                                Jumlah Stok yang Dipakai
                            </label>

                            <input 
                                type="number" 
                                id="jumlah_stok_edit" 
                                name="jumlah_stok" 
                                min="1" 
                                required 
                                class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                            >
                        </div>

                        <!-- Aksi modal edit -->
                        <div class="flex flex-col sm:flex-row gap-2 pt-2">
                            <button 
                                type="button" 
                                onclick="closeEditPaketModal()" 
                                class="w-full px-4 py-3 bg-[#F8F4F2] text-[#7A6F6F] font-bold rounded-xl hover:bg-[#EFE7E4] transition"
                            >
                                Batal
                            </button>

                            <button 
                                type="submit" 
                                class="w-full px-4 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] transition flex items-center justify-center gap-2"
                            >
                                <i class="fa-solid fa-check"></i>
                                <span>Simpan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Script popup pilih barang paket stok -->
            <script>
                // Membuka modal pilih barang paket
                function openPilihBarangPaketModal() {
                    const modal = document.getElementById('pilih-barang-paket-modal');

                    if (!modal) return;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');

                    const search = document.getElementById('search-barang-paket');

                    if (search) {
                        search.value = '';
                        filterBarangPaket();

                        setTimeout(function () {
                            search.focus();
                        }, 100);
                    }
                }

                // Menutup modal pilih barang paket
                function closePilihBarangPaketModal() {
                    const modal = document.getElementById('pilih-barang-paket-modal');

                    if (!modal) return;

                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                // Mencari barang di popup paket
                function filterBarangPaket() {
                    const search = document.getElementById('search-barang-paket');
                    const cards = document.querySelectorAll('.barang-paket-card');
                    const empty = document.getElementById('barang-paket-empty');

                    const keyword = search ? search.value.toLowerCase().trim() : '';
                    let visibleCount = 0;

                    cards.forEach(function (card) {
                        const nama = card.dataset.nama || '';
                        const isVisible = nama.includes(keyword);

                        card.style.display = isVisible ? 'block' : 'none';

                        if (isVisible) {
                            visibleCount++;
                        }
                    });

                    if (empty) {
                        empty.classList.toggle('hidden', visibleCount !== 0);
                    }
                }

                // Memilih barang untuk paket stok
                function pilihBarangPaket(idBarang, namaBarang, jenisBarang, jumlahBarang, satuanBarang) {
                    const inputIdBarang = document.getElementById('id_barang_tambah');
                    const namaBox = document.getElementById('barang-paket-terpilih-nama');
                    const detailBox = document.getElementById('barang-paket-terpilih-detail');

                    if (inputIdBarang) {
                        inputIdBarang.value = idBarang;
                    }

                    if (namaBox) {
                        namaBox.textContent = namaBarang;
                    }

                    if (detailBox) {
                        detailBox.textContent = 'Jenis: ' + jenisBarang + ' | Stok: ' + jumlahBarang + ' ' + satuanBarang;
                    }

                    closePilihBarangPaketModal();
                }

                // Menutup modal jika klik area luar
                document.addEventListener('click', function (event) {
                    const modal = document.getElementById('pilih-barang-paket-modal');

                    if (modal && !modal.classList.contains('hidden') && event.target === modal) {
                        closePilihBarangPaketModal();
                    }
                });

                // Menutup modal jika tekan escape
                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        closePilihBarangPaketModal();
                    }
                });
            </script>

            <!-- Memanggil file JS detail layanan -->
            <script src="../layout/js/detail-layanan.js"></script>

            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>
