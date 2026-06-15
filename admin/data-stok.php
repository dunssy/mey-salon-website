<?php 
// Mengatur judul halaman
$page_title = "Data Stok Barang";

// Mengatur sub judul halaman
$sub_title = "Data Stok Barang";

// Koneksi DULU sebelum include header agar header() bisa dipakai
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Menghapus barang berdasarkan request POST (HARUS sebelum ada output HTML)
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

// Baru include header setelah proses redirect selesai
include "../layout/header.php";

// Mengatur jumlah data per halaman
$jumlah_per_halaman = 5;

// Mengambil halaman aktif dari URL
$halaman_aktif = isset($_GET['halaman']) ? (int) $_GET['halaman'] : 1;

// Mencegah halaman kurang dari 1
if ($halaman_aktif < 1) {
    $halaman_aktif = 1;
}

// METODE PENCARIAN BARANG 
if(isset($_GET['search']) && !empty($_GET['search'])){
    $keyword = clean_input($_GET['search']);
    $all_barang = cari_barang($keyword);
    $total_barang = count($all_barang);
} else {
    // Jika tidak ada pencarian, ambil semua data barang
    $all_barang = select("SELECT * FROM stok_barang ORDER BY id_barang DESC");
    $total_barang = count($all_barang);
}

// Menghitung total halaman barang
$total_halaman = ceil($total_barang / $jumlah_per_halaman);

// Menghitung offset untuk pagination
$offset = ($halaman_aktif - 1) * $jumlah_per_halaman;

// Mengambil data barang sesuai halaman (dari array yang sudah diambil)
$barang = array_slice($all_barang, $offset, $jumlah_per_halaman);

// Menghitung nomor awal tabel
$no = (($halaman_aktif - 1) * $jumlah_per_halaman) + 1;
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
            <div class="p-4 md:p-8 flex-1">

                <!-- Section data stok barang -->
                <section id="section-barang" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E]">
                                Kelola stok barang Mey Salon.
                            </p>
                        </div>

                        <!-- Tombol aksi kanan -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 md:justify-end">

                            <!-- Tombol tambah barang -->
                            <a 
                                href="tambah-stok.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-white bg-[#C75C7A] rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                            >
                                <i class="fa-solid fa-plus"></i>
                                <span>Tambah Barang</span>
                            </a>

                            <!-- Tombol restok barang -->
                            <a  
                                href="restok.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-white bg-green-500 rounded-xl hover:bg-green-600 shadow-sm transition-colors"
                            >
                                <i class="fa-solid fa-boxes-stacked"></i>
                                <span>Restok</span>
                            </a>
                        </div>
                    </div>

                    <!-- Pesan error hapus barang -->
                    <?php if (isset($error_message)) : ?>
                        <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm font-medium">
                            <?= htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Card tabel barang -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header tabel stok -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-[#3D3134]">
                                    Daftar Stok Barang
                                </h4>
                            </div>

                            <span class="inline-flex items-center gap-2 text-[11px] font-bold text-[#C75C7A] bg-[#FDEAF1] px-3 py-1.5 rounded-lg w-fit">
                                <i class="fa-solid fa-box"></i>
                                <span><?= count($barang); ?> Barang</span>
                            </span>
                        </div>

                        <!-- Tabel responsive -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[900px]">

                                <!-- Header tabel -->
                                <thead>
                                    <tr class="bg-[#EFA9BF] border-b border-[#EFA9BF] text-white font-semibold">
                                        <th class="p-4 w-16">No</th>
                                        <th class="p-4">Nama Barang</th>
                                        <th class="p-4">Jenis Barang</th>
                                        <th class="p-4">Jumlah</th>
                                        <th class="p-4">Satuan</th>
                                        <th class="p-4">Minimal Stok</th>
                                        <th class="p-4">Harga Beli</th>
                                        <th class="p-4 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <!-- Isi tabel -->
                                <tbody class="divide-y divide-[#F7D6E4]">

                                    <?php if (!empty($barang)) : ?>

                                        <!-- Perulangan data barang -->
                                        <?php foreach ($barang as $data_barang) : ?>

                                            <tr class="hover:bg-[#FDEAF1]/60 transition-colors">

                                                <!-- Nomor urut -->
                                                <td class="p-4">
                                                    <?= $no++; ?>
                                                </td>

                                                <!-- Nama barang -->
                                                <td class="p-4 font-medium text-[#2B2424]">
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
                                                    <div class="flex items-center justify-center gap-2">

                                                        <!-- Tombol edit -->
                                                        <a 
                                                            href="edit-stok.php?id_barang=<?= (int) $data_barang['id_barang']; ?>" 
                                                            class="inline-flex items-center justify-center w-8 h-8 bg-blue-50 text-blue-500 rounded-lg hover:bg-blue-100 transition"
                                                            title="Edit"
                                                        >
                                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                        </a>

                                                        <!-- Tombol hapus -->
                                                        <form method="POST" class="inline">
                                                            <input type="hidden" name="hapus" value="<?= (int) $data_barang['id_barang']; ?>">
                                                            <button 
                                                                type="button" 
                                                                onclick="confirmDelete(this);" 
                                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition"
                                                                title="Hapus"
                                                            >
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>

                                    <?php else : ?>

                                        <!-- Pesan data kosong -->
                                        <tr>
                                            <td colspan="8" class="p-8 text-center text-[#B77B8E]">
                                                Belum ada data barang.
                                            </td>
                                        </tr>

                                    <?php endif; ?>
                                </tbody>

                                <!-- Footer pagination -->
                                <tfoot class="bg-[#FFF7FA] border-t border-[#F7D6E4]">
                                    <tr>
                                        <td colspan="8" class="p-4">

                                            <!-- Navigasi pagination -->
                                            <div class="flex flex-wrap justify-end gap-2">
                                                <?php 
                                                    // Build pagination URL dengan search parameter jika ada
                                                    $search_param = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
                                                ?>

                                                <!-- Tombol sebelumnya -->
                                                <?php if ($halaman_aktif > 1) : ?>
                                                    <a 
                                                        href="?halaman=<?= $halaman_aktif - 1; ?><?= $search_param; ?>" 
                                                        class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors"
                                                    >
                                                        &laquo; Prev
                                                    </a>
                                                <?php else : ?>
                                                    <span class="px-3 py-1.5 text-xs font-medium bg-[#F8F4F2] border border-[#EAD8D0] rounded-lg text-gray-300 cursor-not-allowed">
                                                        &laquo; Prev
                                                    </span>
                                                <?php endif; ?>

                                                <!-- Nomor halaman -->
                                                <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                                                    <a 
                                                        href="?halaman=<?= $i; ?><?= $search_param; ?>" 
                                                        class="px-3 py-1.5 text-xs font-medium border rounded-lg transition-colors <?= $i === $halaman_aktif ? 'bg-pink-600 border-pink-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-pink-50 hover:text-pink-600'; ?>"
                                                    >
                                                        <?= $i; ?>
                                                    </a>
                                                <?php endfor; ?>

                                                <!-- Tombol berikutnya -->
                                                <?php if ($halaman_aktif < $total_halaman) : ?>
                                                    <a 
                                                        href="?halaman=<?= $halaman_aktif + 1; ?><?= $search_param; ?>" 
                                                        class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors"
                                                    >
                                                        Next &raquo;
                                                    </a>
                                                <?php else : ?>
                                                    <span class="px-3 py-1.5 text-xs font-medium bg-[#F8F4F2] border border-[#EAD8D0] rounded-lg text-gray-300 cursor-not-allowed">
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
    <script>
    // / Menampilkan alert success setelah hapus layanan
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get('status') === 'success') {
        Swal.fire({
            title: "Terhapus!",
            text: "Data barang berhasil dihapus.",
            icon: "success",
            confirmButtonColor: "#C75C7A"
        });

        // Menghapus parameter status dari URL agar alert tidak muncul berulang
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    // Fungsi untuk konfirmasi hapus dengan SweetAlert2
    function confirmDelete(button, id) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Anda tidak akan bisa mengembalikan layanan yang telah dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: "Ya, hapus layanan!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form jika user confirm
                button.closest('form').submit();
            }
        });
    }
</script>

            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>