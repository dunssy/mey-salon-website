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

// ============ HANDLER TAMBAH PAKET STOK (Sebelum konversi array) ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    // TAMBAH PAKET STOK
    if ($action === 'tambah_paket') {
        $id_barang = isset($_POST['id_barang']) ? (int) $_POST['id_barang'] : 0;
        $jumlah_stok = isset($_POST['jumlah_stok']) ? (int) $_POST['jumlah_stok'] : 0;
        
        if ($id_barang > 0 && $jumlah_stok > 0) {
            // Cek apakah barang sudah ada di paket stok
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
                } else {
                    echo "<script>alert('Gagal menambahkan paket stok: " . mysqli_error($koneksi) . "');</script>";
                }
            }
        } else {
            echo "<script>alert('Pilih barang dan masukkan jumlah stok yang valid!');</script>";
        }
    }
    
    // EDIT PAKET STOK
    else if ($action === 'edit_paket') {
        $id_paket = isset($_POST['id_paket']) ? (int) $_POST['id_paket'] : 0;
        $jumlah_stok = isset($_POST['jumlah_stok']) ? (int) $_POST['jumlah_stok'] : 0;
        
        if ($id_paket > 0 && $jumlah_stok > 0) {
            $update_paket = mysqli_query(
                $koneksi,
                "UPDATE paket_stok SET jumlah_stok = $jumlah_stok 
                 WHERE id_paket = $id_paket AND id_layanan = $id_layanan"
            );
            
            if ($update_paket) {
                echo "<script>
                        alert('Paket stok berhasil diperbarui!');
                        window.location.href = window.location.href;
                      </script>";
                exit;
            } else {
                echo "<script>alert('Gagal memperbarui paket stok!');</script>";
            }
        }
    }
    
    // HAPUS PAKET STOK
    else if ($action === 'hapus_paket') {
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
            } else {
                echo "<script>alert('Gagal menghapus paket stok!');</script>";
            }
        }
    }
}

// RE-QUERY untuk mendapatkan data terbaru setelah INSERT/UPDATE/DELETE
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

// Mengkonversi query result ke array untuk digunakan di modal
$paket_stok_array = [];
if ($query_paket_stok) {
    while ($paket = mysqli_fetch_assoc($query_paket_stok)) {
        $paket_stok_array[] = $paket;
    }
    // Reset pointer untuk digunakan di tabel
    mysqli_data_seek($query_paket_stok, 0);
}

// Mengambil semua stok barang untuk dropdown
$query_semua_stok = mysqli_query(
    $koneksi,
    "SELECT id_barang, nama_barang, jenis_barang, jumlah_barang, satuan_barang, minimal_stok 
     FROM stok_barang 
     ORDER BY nama_barang ASC"
);
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

                                <div class="flex flex-col sm:flex-row gap-2 items-stretch sm:items-center">
                                    <span class="text-xs text-pink-600 bg-pink-50 px-3 py-1 rounded-full font-bold">
                                        Auto Pemakaian Stok
                                    </span>
                                </div>
                            </div>

                            <!-- Tabel paket stok -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-left min-w-[800px]">

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
                                            <th class="px-6 py-4 text-center">Aksi</th>
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

                                                    <!-- Aksi -->
                                                    <td class="px-6 py-4 text-center">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <!-- Tombol Edit -->
                                                            <button 
                                                                type="button"
                                                                onclick="openEditPaketModal(<?= (int) $paket['id_paket']; ?>, <?= (int) $paket['jumlah_stok']; ?>, '<?= htmlspecialchars($paket['nama_barang']); ?>')"
                                                                class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition"
                                                                title="Edit"
                                                            >
                                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                            </button>

                                                            <!-- Tombol Hapus -->
                                                            <form method="POST" style="display: inline;" onsubmit="return confirm('Hapus paket stok ini?');">
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
                                                <td colspan="8" class="px-6 py-10 text-center text-gray-400 italic">
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

                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        Jika rambut panjang atau bahan kurang, admin bisa menambahkan bahan tambahan saat menyelesaikan booking.
                                    </p>
                                     <button 
                                        type="button"
                                        onclick="openTambahPaketModal()"
                                        class="mt-3 inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 transition-colors"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Penggunaan Bahan</span>
                                    </button>
                                        </div>
                                        <!-- MODAL TAMBAH PAKET STOK -->
                                        <div id="tambah-paket-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">
                                            <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-green-100 overflow-hidden">
                                                <div class="p-5 border-b border-green-100 flex items-start justify-between gap-4">
                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-800">Tambah Paket Stok</h4>
                                                        <p class="text-xs text-gray-400 mt-1">Pilih barang dan jumlah yang akan digunakan.</p>
                                                    </div>
                                                    <button type="button" onclick="closeTambahPaketModal()" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                            
                                                <form method="POST" class="p-5 space-y-4">
                                                    <input type="hidden" name="action" value="tambah_paket">
                                                    
                                                    <!-- Pilih Barang -->
                                                    <div>
                                                        <label for="id_barang_tambah" class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang</label>
                                                        <select id="id_barang_tambah" name="id_barang" required class="w-full px-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200">
                                                            <option value="">-- Pilih Barang --</option>
                                                            <?php 
                                                            mysqli_data_seek($query_semua_stok, 0);
                                                            while ($stok = mysqli_fetch_assoc($query_semua_stok)) : 
                                                            ?>
                                                                <option value="<?php echo $stok['id_barang']; ?>">
                                                                    <?php echo htmlspecialchars($stok['nama_barang']); ?> 
                                                                    (<?php echo htmlspecialchars($stok['satuan_barang']); ?>) - Stok: <?php echo htmlspecialchars($stok['jumlah_barang']); ?>
                                                                </option>
                                                            <?php endwhile; ?>
                                                        </select>
                                                    </div>
                            
                                                    <!-- Jumlah Stok yang Dipakai -->
                                                    <div>
                                                        <label for="jumlah_stok_tambah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok yang Dipakai</label>
                                                        <input type="number" id="jumlah_stok_tambah" name="jumlah_stok" min="1" required class="w-full px-3 py-2 border border-green-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Contoh: 5"/>
                                                    </div>
                            
                                                    <!-- Aksi -->
                                                    <div class="flex gap-2 pt-2">
                                                        <button type="button" onclick="closeTambahPaketModal()" class="w-full px-4 py-3 bg-gray-50 text-gray-500 font-bold rounded-xl hover:bg-gray-100 transition">Batal</button>
                                                        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition flex items-center justify-center gap-2">
                                                            <i class="fa-solid fa-check"></i>
                                                            <span>Simpan</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                            
                                        <!-- MODAL EDIT PAKET STOK -->
                                        <div id="edit-paket-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">
                                            <div class="w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-blue-100 overflow-hidden">
                                                <div class="p-5 border-b border-blue-100 flex items-start justify-between gap-4">
                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-800">Edit Paket Stok</h4>
                                                        <p class="text-xs text-gray-400 mt-1">Ubah jumlah stok yang dipakai.</p>
                                                    </div>
                                                    <button type="button" onclick="closeEditPaketModal()" class="w-9 h-9 rounded-xl bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 transition">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </button>
                                                </div>
                            
                                                <form method="POST" class="p-5 space-y-4">
                                                    <input type="hidden" name="action" value="edit_paket">
                                                    <input type="hidden" id="edit_id_paket" name="id_paket" value="">
                                                    
                                                    <!-- Nama Barang (Read Only) -->
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                                                        <input type="text" id="edit_nama_barang" readonly class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700"/>
                                                    </div>
                            
                                                    <!-- Jumlah Stok yang Dipakai -->
                                                    <div>
                                                        <label for="jumlah_stok_edit" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Stok yang Dipakai</label>
                                                        <input type="number" id="jumlah_stok_edit" name="jumlah_stok" min="1" required class="w-full px-3 py-2 border border-blue-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200"/>
                                                    </div>
                            
                                                    <!-- Aksi -->
                                                    <div class="flex gap-2 pt-2">
                                                        <button type="button" onclick="closeEditPaketModal()" class="w-full px-4 py-3 bg-gray-50 text-gray-500 font-bold rounded-xl hover:bg-gray-100 transition">Batal</button>
                                                        <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                                            <i class="fa-solid fa-check"></i>
                                                            <span>Simpan</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

    <!--MEMINDAHKAN SEBUAH FILE JS SEBAGAI MODULAR -->
    <script src="../layout/js/detail-layanan.js"></script>
            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>