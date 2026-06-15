<?php
// Memulai session admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengatur judul halaman
$page_title = "Pendapatan Walk-in";

// Mengatur sub judul halaman
$sub_title = "Tambah Pendapatan Walk-in";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek user sudah login
if (!isset($_SESSION['id_user']) && !isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Sesi Anda habis, silakan login kembali.');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Fungsi format rupiah
function rupiah_walkin($angka)
{
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

// Fungsi redirect ke halaman pendapatan walk-in
function redirect_walkin($pesan)
{
    echo "<script>
            alert('$pesan');
            window.location.href = 'pendapatan-walkin.php';
          </script>";
    exit;
}

// Mengecek kolom nama_pelanggan_walkin sudah ada
$cek_nama_walkin = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'nama_pelanggan_walkin'");
$kolom_nama_walkin_ada = mysqli_num_rows($cek_nama_walkin) > 0;

// Mengecek kolom layanan_manual sudah ada
$cek_layanan_manual = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'layanan_manual'");
$kolom_layanan_manual_ada = mysqli_num_rows($cek_layanan_manual) > 0;

// Mengambil data transaksi walk-in yang akan diedit
$id_edit = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$data_edit = null;

if ($id_edit > 0) {
    $query_edit = mysqli_query(
        $koneksi,
        "SELECT * FROM transaksi
         WHERE id_transaksi = $id_edit
         AND jenis_pelanggan = 'walk-in'
         LIMIT 1"
    );

    if ($query_edit && mysqli_num_rows($query_edit) > 0) {
        $data_edit = mysqli_fetch_assoc($query_edit);
        $sub_title = "Edit Pendapatan Walk-in";
    }
}

// Memproses tambah pendapatan walk-in
if (isset($_POST['submit'])) {
    $nama_pelanggan = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['nama_pelanggan'] ?? '')));
    $layanan_manual = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['layanan_manual'] ?? '')));
    $tanggal_transaksi = mysqli_real_escape_string($koneksi, $_POST['tanggal_transaksi'] ?? date('Y-m-d'));
    $total_bayar = isset($_POST['total_bayar']) ? (int) $_POST['total_bayar'] : 0;

    // Mengecek input wajib
    if (empty($nama_pelanggan) || empty($layanan_manual) || empty($tanggal_transaksi) || $total_bayar <= 0) {
        redirect_walkin('Nama, layanan, tanggal, dan total wajib diisi dengan benar!');
    }

    // Mengecek kolom database pendukung
    if (!$kolom_nama_walkin_ada || !$kolom_layanan_manual_ada) {
        redirect_walkin('Kolom walk-in belum ada di tabel transaksi. Jalankan SQL ALTER terlebih dahulu!');
    }

    // Menyimpan pendapatan walk-in ke transaksi agar masuk laporan
    $query_insert = mysqli_query(
        $koneksi,
        "INSERT INTO transaksi 
            (id_booking, nama_pelanggan_walkin, layanan_manual, total_bayar, jenis_pelanggan, catatan_tambahan, tanggal_transaksi)
         VALUES 
            (NULL, '$nama_pelanggan', '$layanan_manual', $total_bayar, 'walk-in', 'Pendapatan pelanggan datang langsung', '$tanggal_transaksi')"
    );

    if ($query_insert) {
        redirect_walkin('Pendapatan walk-in berhasil ditambahkan!');
    }

    redirect_walkin('Pendapatan walk-in gagal ditambahkan!');
}

// Memproses update pendapatan walk-in
if (isset($_POST['update'])) {
    $id_transaksi = (int) ($_POST['id_transaksi'] ?? 0);
    $nama_pelanggan = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['nama_pelanggan'] ?? '')));
    $layanan_manual = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['layanan_manual'] ?? '')));
    $tanggal_transaksi = mysqli_real_escape_string($koneksi, $_POST['tanggal_transaksi'] ?? date('Y-m-d'));
    $total_bayar = isset($_POST['total_bayar']) ? (int) $_POST['total_bayar'] : 0;

    // Mengecek input wajib
    if ($id_transaksi <= 0 || empty($nama_pelanggan) || empty($layanan_manual) || empty($tanggal_transaksi) || $total_bayar <= 0) {
        redirect_walkin('Data edit pendapatan walk-in tidak lengkap!');
    }

    // Mengupdate transaksi walk-in
    $query_update = mysqli_query(
        $koneksi,
        "UPDATE transaksi SET
            nama_pelanggan_walkin = '$nama_pelanggan',
            layanan_manual = '$layanan_manual',
            total_bayar = $total_bayar,
            tanggal_transaksi = '$tanggal_transaksi'
         WHERE id_transaksi = $id_transaksi
         AND jenis_pelanggan = 'walk-in'"
    );

    if ($query_update) {
        redirect_walkin('Pendapatan walk-in berhasil diperbarui!');
    }

    redirect_walkin('Pendapatan walk-in gagal diperbarui!');
}

// Memproses hapus pendapatan walk-in
if (isset($_POST['hapus_walkin'])) {
    $id_transaksi = (int) ($_POST['id_transaksi'] ?? 0);

    if ($id_transaksi <= 0) {
        redirect_walkin('ID transaksi tidak valid!');
    }

    // Menghapus transaksi walk-in saja
    mysqli_query(
        $koneksi,
        "DELETE FROM transaksi 
         WHERE id_transaksi = $id_transaksi 
         AND jenis_pelanggan = 'walk-in'"
    );

    if (mysqli_affected_rows($koneksi) > 0) {
        redirect_walkin('Pendapatan walk-in berhasil dihapus!');
    }

    redirect_walkin('Pendapatan walk-in gagal dihapus!');
}

// Mengambil semua data pendapatan walk-in
$query_walkin = mysqli_query(
    $koneksi,
    "SELECT 
        id_transaksi,
        nama_pelanggan_walkin,
        layanan_manual,
        total_bayar,
        tanggal_transaksi,
        catatan_tambahan
     FROM transaksi
     WHERE jenis_pelanggan = 'walk-in'
     ORDER BY tanggal_transaksi DESC, id_transaksi DESC"
);

// Menghitung total data walk-in
$total_pendapatan_walkin = 0;
$jumlah_data_walkin = 0;
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

                <!-- Section pendapatan walk-in -->
                <section id="section-pendapatan-walkin" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Tambahkan, edit, atau hapus pendapatan pelanggan datang langsung.
                            </p>
                        </div>

                        <!-- Tombol kanan -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <?php if ($data_edit) : ?>
                                <a 
                                    href="pendapatan-walkin.php" 
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-white border border-[#F7D6E4] rounded-xl hover:bg-[#FDEAF1] transition-colors w-fit"
                                >
                                    <i class="fa-solid fa-xmark"></i>
                                    <span>Batal Edit</span>
                                </a>
                            <?php endif; ?>

                            <a 
                                href="data-laporan.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors w-fit"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali Laporan</span>
                            </a>
                        </div>
                    </div>

                    <!-- Peringatan SQL jika kolom belum tersedia -->
                    <?php if (!$kolom_nama_walkin_ada || !$kolom_layanan_manual_ada) : ?>
                        <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-100 text-yellow-700 text-sm leading-relaxed">
                            <b>Perhatian:</b>
                            Kolom <b>nama_pelanggan_walkin</b> atau <b>layanan_manual</b> belum ada di tabel transaksi.
                            Jalankan SQL ALTER terlebih dahulu agar fitur ini bisa menyimpan data.
                        </div>
                    <?php endif; ?>

                    <!-- Card form full width -->
                    <div class="w-full bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                <?= $data_edit ? 'Form Edit Pendapatan Walk-in' : 'Form Pendapatan Walk-in'; ?>
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Data yang disimpan otomatis masuk ke tabel transaksi dan tampil di laporan.
                            </p>
                        </div>

                        <!-- Form tambah/edit walk-in -->
                        <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

                            <?php if ($data_edit) : ?>
                                <input type="hidden" name="id_transaksi" value="<?= (int) $data_edit['id_transaksi']; ?>">
                            <?php endif; ?>

                            <!-- Grid input -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <!-- Input nama pelanggan -->
                                <div>
                                    <label for="nama_pelanggan" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Nama Pelanggan
                                    </label>

                                    <input 
                                        type="text" 
                                        name="nama_pelanggan" 
                                        id="nama_pelanggan" 
                                        required
                                        value="<?= htmlspecialchars($data_edit['nama_pelanggan_walkin'] ?? ''); ?>"
                                        placeholder="Contoh: Ibu Sari"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input tanggal transaksi -->
                                <div>
                                    <label for="tanggal_transaksi" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Tanggal
                                    </label>

                                    <input 
                                        type="date" 
                                        name="tanggal_transaksi" 
                                        id="tanggal_transaksi" 
                                        required
                                        value="<?= htmlspecialchars($data_edit['tanggal_transaksi'] ?? date('Y-m-d')); ?>"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input layanan manual -->
                                <div class="md:col-span-2">
                                    <label for="layanan_manual" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Layanan
                                    </label>

                                    <input 
                                        type="text" 
                                        name="layanan_manual" 
                                        id="layanan_manual" 
                                        required
                                        value="<?= htmlspecialchars($data_edit['layanan_manual'] ?? ''); ?>"
                                        placeholder="Contoh: Potong rambut + creambath"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input total bayar -->
                                <div class="md:col-span-2">
                                    <label for="total_bayar" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Total Pendapatan
                                    </label>

                                    <input 
                                        type="number" 
                                        name="total_bayar" 
                                        id="total_bayar" 
                                        required
                                        min="1"
                                        value="<?= htmlspecialchars($data_edit['total_bayar'] ?? ''); ?>"
                                        placeholder="Contoh: 150000"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >

                                    <p class="text-[11px] text-[#B77B8E] mt-2">
                                        Masukkan total pembayaran manual dari pelanggan walk-in.
                                    </p>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="p-4 rounded-2xl bg-[#FFF7FA] border border-[#F7D6E4] text-xs text-[#6F5E64] leading-relaxed">
                                <b class="text-[#C75C7A]">Catatan:</b>
                                Transaksi ini tidak memakai booking online, sehingga jenis pelanggan menjadi <b>walk-in</b>.
                            </div>

                            <!-- Tombol submit -->
                            <div class="flex justify-end">
                                <?php if ($data_edit) : ?>
                                    <button 
                                        type="submit" 
                                        name="update" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                    >
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        <span>Simpan Perubahan</span>
                                    </button>
                                <?php else : ?>
                                    <button 
                                        type="submit" 
                                        name="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                    >
                                        <i class="fa-solid fa-plus"></i>
                                        <span>Simpan Pendapatan</span>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- Card data pendapatan walk-in bawah form -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header tabel -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <h4 class="font-bold text-[#3D3134]">
                                    Data Pendapatan Walk-in
                                </h4>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Edit atau hapus data jika terjadi kesalahan input.
                                </p>
                            </div>

                            <a 
                                href="data-laporan.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-bold text-pink-600 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors w-fit"
                            >
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Lihat Laporan</span>
                            </a>
                        </div>

                        <!-- Tabel data walk-in -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-[#FFF7FA] border-b border-[#F7D6E4]">
                                    <tr class="text-left text-[11px] uppercase tracking-wider text-[#B77B8E]">
                                        <th class="px-5 py-3">No</th>
                                        <th class="px-5 py-3">Tanggal</th>
                                        <th class="px-5 py-3">Nama</th>
                                        <th class="px-5 py-3">Layanan</th>
                                        <th class="px-5 py-3 text-right">Total</th>
                                        <th class="px-5 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-[#F7D6E4]">
                                    <?php if ($query_walkin && mysqli_num_rows($query_walkin) > 0) : ?>
                                        <?php $no = 1; ?>
                                        <?php while ($walkin = mysqli_fetch_assoc($query_walkin)) : ?>
                                            <?php
                                            $total_pendapatan_walkin += (int) $walkin['total_bayar'];
                                            $jumlah_data_walkin++;
                                            ?>

                                            <tr class="hover:bg-[#FFF7FA]/70 transition-colors">
                                                <td class="px-5 py-4 text-gray-500 font-semibold">
                                                    <?= $no++; ?>
                                                </td>

                                                <td class="px-5 py-4 whitespace-nowrap">
                                                    <span class="font-bold text-[#3D3134]">
                                                        <?= date('d M Y', strtotime($walkin['tanggal_transaksi'])); ?>
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4 whitespace-nowrap">
                                                    <span class="font-bold text-[#3D3134]">
                                                        <?= htmlspecialchars($walkin['nama_pelanggan_walkin'] ?? '-'); ?>
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4 min-w-[240px]">
                                                    <p class="text-gray-600 leading-relaxed">
                                                        <?= htmlspecialchars($walkin['layanan_manual'] ?? '-'); ?>
                                                    </p>
                                                </td>

                                                <td class="px-5 py-4 text-right whitespace-nowrap">
                                                    <span class="font-bold text-green-600">
                                                        <?= rupiah_walkin($walkin['total_bayar']); ?>
                                                    </span>
                                                </td>

                                                <td class="px-5 py-4">
                                                    <div class="flex items-center justify-center gap-2">

                                                        <!-- Tombol edit -->
                                                        <a 
                                                            href="pendapatan-walkin.php?edit=<?= (int) $walkin['id_transaksi']; ?>"
                                                            class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition"
                                                            title="Edit pendapatan"
                                                        >
                                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                        </a>

                                                        <!-- Tombol hapus -->
                                                        <form action="" method="POST" onsubmit="return confirm('Yakin ingin menghapus pendapatan walk-in ini?')" class="inline">
                                                            <input type="hidden" name="id_transaksi" value="<?= (int) $walkin['id_transaksi']; ?>">

                                                            <button 
                                                                type="submit"
                                                                name="hapus_walkin"
                                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition"
                                                                title="Hapus pendapatan"
                                                            >
                                                                <i class="fa-solid fa-trash text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="6" class="px-5 py-12 text-center">
                                                <div class="w-14 h-14 bg-pink-50 text-pink-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                    <i class="fa-solid fa-cash-register text-2xl"></i>
                                                </div>

                                                <p class="text-sm font-bold text-gray-500">
                                                    Belum ada pendapatan walk-in.
                                                </p>

                                                <p class="text-xs text-gray-400 mt-1">
                                                    Data yang ditambahkan akan muncul di sini.
                                                </p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Footer ringkasan -->
                        <div class="px-5 py-4 bg-[#FFF7FA] border-t border-[#F7D6E4] flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <p class="text-xs text-[#B77B8E] font-semibold">
                                Total data: <?= (int) $jumlah_data_walkin; ?> transaksi walk-in
                            </p>

                            <p class="text-sm font-bold text-[#3D3134]">
                                Total Pendapatan Walk-in:
                                <span class="text-green-600">
                                    <?= rupiah_walkin($total_pendapatan_walkin); ?>
                                </span>
                            </p>
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
