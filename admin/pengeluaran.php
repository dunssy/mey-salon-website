<?php 
// Memulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengatur judul halaman
$page_title = "Pengeluaran";

// Mengatur sub judul halaman
$sub_title = "Tambah Pengeluaran";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>
            alert('Sesi Anda habis, silakan login kembali.');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Mengambil id user login
$id_user_login = (int) $_SESSION['id_user'];

// Fungsi format rupiah
function rupiah_pengeluaran($angka)
{
    return 'Rp ' . number_format((int) $angka, 0, ',', '.');
}

// Fungsi redirect halaman pengeluaran
function redirect_pengeluaran($pesan)
{
    echo "<script>
            alert('$pesan');
            window.location.href = 'pengeluaran.php';
          </script>";
    exit;
}

// Mengambil data pengeluaran yang akan diedit
$id_edit = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$data_edit = null;

if ($id_edit > 0) {
    $query_edit = mysqli_query(
        $koneksi,
        "SELECT * FROM pengeluaran 
         WHERE id_pengeluaran = $id_edit 
         LIMIT 1"
    );

    if ($query_edit && mysqli_num_rows($query_edit) > 0) {
        $data_edit = mysqli_fetch_assoc($query_edit);
        $sub_title = "Edit Pengeluaran";
    }
}

// Memproses tambah pengeluaran
if (isset($_POST['submit'])) {
    $id_user = $id_user_login;
    $jenis_pengeluaran = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['jenis_pengeluaran'] ?? '')));
    $jumlah_pengeluaran = (int) ($_POST['jumlah_pengeluaran'] ?? 0);
    $tanggal_pengeluaran = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengeluaran'] ?? date('Y-m-d'));
    $keterangan_pengeluaran = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['keterangan_pengeluaran'] ?? '')));

    // Mengecek input wajib
    if (empty($jenis_pengeluaran) || empty($tanggal_pengeluaran) || empty($keterangan_pengeluaran)) {
        redirect_pengeluaran('Semua input pengeluaran wajib diisi!');
    }

    // Mengecek jumlah pengeluaran valid
    if ($jumlah_pengeluaran <= 0) {
        redirect_pengeluaran('Jumlah pengeluaran harus lebih dari 0!');
    }

    // Menyimpan pengeluaran manual untuk laporan
    $query = "INSERT INTO pengeluaran 
                (id_user, jenis_pengeluaran, jumlah_pengeluaran, tanggal_pengeluaran, keterangan_pengeluaran)
              VALUES 
                ($id_user, '$jenis_pengeluaran', $jumlah_pengeluaran, '$tanggal_pengeluaran', '$keterangan_pengeluaran')";

    mysqli_query($koneksi, $query);

    // Mengecek data berhasil disimpan
    if (mysqli_affected_rows($koneksi) > 0) {
        redirect_pengeluaran('Pengeluaran berhasil ditambahkan dan masuk ke laporan!');
    }

    redirect_pengeluaran('Pengeluaran gagal ditambahkan!');
}

// Memproses update pengeluaran
if (isset($_POST['update'])) {
    $id_pengeluaran = (int) ($_POST['id_pengeluaran'] ?? 0);
    $jenis_pengeluaran = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['jenis_pengeluaran'] ?? '')));
    $jumlah_pengeluaran = (int) ($_POST['jumlah_pengeluaran'] ?? 0);
    $tanggal_pengeluaran = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengeluaran'] ?? date('Y-m-d'));
    $keterangan_pengeluaran = mysqli_real_escape_string($koneksi, strip_tags(trim($_POST['keterangan_pengeluaran'] ?? '')));

    // Mengecek input edit
    if ($id_pengeluaran <= 0 || empty($jenis_pengeluaran) || empty($tanggal_pengeluaran) || empty($keterangan_pengeluaran)) {
        redirect_pengeluaran('Data edit pengeluaran tidak lengkap!');
    }

    // Mengecek jumlah pengeluaran valid
    if ($jumlah_pengeluaran <= 0) {
        redirect_pengeluaran('Jumlah pengeluaran harus lebih dari 0!');
    }

    // Mengubah data pengeluaran
    $query_update = "UPDATE pengeluaran SET
                        jenis_pengeluaran = '$jenis_pengeluaran',
                        jumlah_pengeluaran = $jumlah_pengeluaran,
                        tanggal_pengeluaran = '$tanggal_pengeluaran',
                        keterangan_pengeluaran = '$keterangan_pengeluaran'
                     WHERE id_pengeluaran = $id_pengeluaran";

    mysqli_query($koneksi, $query_update);

    if (mysqli_affected_rows($koneksi) >= 0) {
        redirect_pengeluaran('Pengeluaran berhasil diperbarui!');
    }

    redirect_pengeluaran('Pengeluaran gagal diperbarui!');
}

// Memproses hapus pengeluaran
if (isset($_POST['hapus_pengeluaran'])) {
    $id_pengeluaran = (int) ($_POST['id_pengeluaran'] ?? 0);

    if ($id_pengeluaran <= 0) {
        redirect_pengeluaran('ID pengeluaran tidak valid!');
    }

    mysqli_query(
        $koneksi,
        "DELETE FROM pengeluaran WHERE id_pengeluaran = $id_pengeluaran"
    );

    if (mysqli_affected_rows($koneksi) > 0) {
        redirect_pengeluaran('Pengeluaran berhasil dihapus!');
    }

    redirect_pengeluaran('Pengeluaran gagal dihapus!');
}

// Mengambil semua data pengeluaran manual
$query_pengeluaran = mysqli_query(
    $koneksi,
    "SELECT 
        p.*,
        u.nama AS nama_admin
     FROM pengeluaran p
     LEFT JOIN user u ON p.id_user = u.id_user
     ORDER BY p.tanggal_pengeluaran DESC, p.id_pengeluaran DESC"
);

// Menghitung total pengeluaran manual
$total_pengeluaran_manual = 0;
$jumlah_data_pengeluaran = 0;
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

                <!-- Section tambah pengeluaran -->
                <section id="section-tambah-pengeluaran" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Tambahkan, edit, atau hapus pengeluaran manual seperti listrik, sewa, gaji, dan operasional.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <?php if ($data_edit) : ?>
                                <a 
                                    href="pengeluaran.php" 
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

                    <!-- Layout form -->
                    <div class="w-full space-y-6">

                        <!-- Card form pengeluaran -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden w-full">

                            <!-- Header card -->
                            <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                                <h4 class="font-bold text-[#3D3134]">
                                    <?= $data_edit ? 'Form Edit Pengeluaran' : 'Form Pengeluaran'; ?>
                                </h4>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Data yang disimpan akan otomatis masuk sebagai pengeluaran di laporan.
                                </p>
                            </div>

                            <!-- Form tambah/edit pengeluaran -->
                            <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

                                <?php if ($data_edit) : ?>
                                    <input type="hidden" name="id_pengeluaran" value="<?= (int) $data_edit['id_pengeluaran']; ?>">
                                <?php endif; ?>

                                <!-- Grid input pengeluaran -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    <!-- Input jenis pengeluaran -->
                                    <div>
                                        <label for="jenis_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                            Jenis Pengeluaran
                                        </label>

                                        <input 
                                            type="text" 
                                            name="jenis_pengeluaran" 
                                            id="jenis_pengeluaran" 
                                            required
                                            value="<?= htmlspecialchars($data_edit['jenis_pengeluaran'] ?? ''); ?>"
                                            placeholder="Contoh: Listrik, Sewa, Gaji, Peralatan"
                                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                        >
                                    </div>

                                    <!-- Input jumlah pengeluaran -->
                                    <div>
                                        <label for="jumlah_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                            Jumlah Pengeluaran
                                        </label>

                                        <input 
                                            type="number" 
                                            name="jumlah_pengeluaran" 
                                            id="jumlah_pengeluaran" 
                                            required
                                            min="1"
                                            value="<?= htmlspecialchars($data_edit['jumlah_pengeluaran'] ?? ''); ?>"
                                            placeholder="Contoh: 100000"
                                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                        >
                                    </div>

                                    <!-- Input tanggal pengeluaran -->
                                    <div>
                                        <label for="tanggal_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                            Tanggal Pengeluaran
                                        </label>

                                        <input 
                                            type="date" 
                                            name="tanggal_pengeluaran" 
                                            id="tanggal_pengeluaran" 
                                            required
                                            value="<?= htmlspecialchars($data_edit['tanggal_pengeluaran'] ?? date('Y-m-d')); ?>"
                                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                        >
                                    </div>

                                    <!-- Input keterangan -->
                                    <div class="md:col-span-2">
                                        <label for="keterangan_pengeluaran" class="block text-sm font-bold text-[#3D3134] mb-2">
                                            Keterangan
                                        </label>

                                        <textarea 
                                            name="keterangan_pengeluaran" 
                                            id="keterangan_pengeluaran" 
                                            rows="4"
                                            required
                                            placeholder="Contoh: Pembayaran listrik bulan Januari"
                                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A] resize-none"
                                        ><?= htmlspecialchars($data_edit['keterangan_pengeluaran'] ?? ''); ?></textarea>
                                    </div>
                                </div>

                                <!-- Tombol submit -->
                                <div class="flex flex-col sm:flex-row justify-end gap-2">
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
                                            <span>Tambah Pengeluaran</span>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>

                        <!-- Card data pengeluaran bawah form -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                            <!-- Header tabel -->
                            <div class="px-5 py-4 border-b border-[#F7D6E4] bg-white flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-[#3D3134]">
                                        Data Pengeluaran Manual
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

                            <!-- Tabel data pengeluaran -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-[#FFF7FA] border-b border-[#F7D6E4]">
                                        <tr class="text-left text-[11px] uppercase tracking-wider text-[#B77B8E]">
                                            <th class="px-5 py-3">No</th>
                                            <th class="px-5 py-3">Tanggal</th>
                                            <th class="px-5 py-3">Jenis</th>
                                            <th class="px-5 py-3">Keterangan</th>
                                            <th class="px-5 py-3 text-right">Jumlah</th>
                                            <th class="px-5 py-3">Admin</th>
                                            <th class="px-5 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-[#F7D6E4]">
                                        <?php if ($query_pengeluaran && mysqli_num_rows($query_pengeluaran) > 0) : ?>
                                            <?php $no = 1; ?>
                                            <?php while ($pengeluaran = mysqli_fetch_assoc($query_pengeluaran)) : ?>
                                                <?php
                                                $total_pengeluaran_manual += (int) $pengeluaran['jumlah_pengeluaran'];
                                                $jumlah_data_pengeluaran++;
                                                ?>

                                                <tr class="hover:bg-[#FFF7FA]/70 transition-colors">
                                                    <td class="px-5 py-4 text-gray-500 font-semibold">
                                                        <?= $no++; ?>
                                                    </td>

                                                    <td class="px-5 py-4 whitespace-nowrap">
                                                        <span class="font-bold text-[#3D3134]">
                                                            <?= date('d M Y', strtotime($pengeluaran['tanggal_pengeluaran'])); ?>
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <span class="inline-flex px-3 py-1 rounded-full bg-[#FDEAF1] text-[#C75C7A] text-xs font-bold">
                                                            <?= htmlspecialchars($pengeluaran['jenis_pengeluaran']); ?>
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-4 min-w-[240px]">
                                                        <p class="text-gray-600 leading-relaxed">
                                                            <?= htmlspecialchars($pengeluaran['keterangan_pengeluaran']); ?>
                                                        </p>
                                                    </td>

                                                    <td class="px-5 py-4 text-right whitespace-nowrap">
                                                        <span class="font-bold text-red-500">
                                                            <?= rupiah_pengeluaran($pengeluaran['jumlah_pengeluaran']); ?>
                                                        </span>
                                                    </td>

                                                    <td class="px-5 py-4 whitespace-nowrap text-gray-500">
                                                        <?= htmlspecialchars($pengeluaran['nama_admin'] ?? '-'); ?>
                                                    </td>

                                                    <td class="px-5 py-4">
                                                        <div class="flex items-center justify-center gap-2">
                                                            <!-- Tombol edit -->
                                                            <a 
                                                                href="pengeluaran.php?edit=<?= (int) $pengeluaran['id_pengeluaran']; ?>"
                                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition"
                                                                title="Edit pengeluaran"
                                                            >
                                                                <i class="fa-solid fa-pen-to-square text-xs"></i>
                                                            </a>

                                                            <!-- Tombol hapus -->
                                                            <form action="" method="POST" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')" class="inline">
                                                                <input type="hidden" name="id_pengeluaran" value="<?= (int) $pengeluaran['id_pengeluaran']; ?>">

                                                                <button 
                                                                    type="submit"
                                                                    name="hapus_pengeluaran"
                                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition"
                                                                    title="Hapus pengeluaran"
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
                                                <td colspan="7" class="px-5 py-12 text-center">
                                                    <div class="w-14 h-14 bg-pink-50 text-pink-200 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                        <i class="fa-solid fa-receipt text-2xl"></i>
                                                    </div>

                                                    <p class="text-sm font-bold text-gray-500">
                                                        Belum ada pengeluaran manual.
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
                                    Total data: <?= (int) $jumlah_data_pengeluaran; ?> pengeluaran
                                </p>

                                <p class="text-sm font-bold text-[#3D3134]">
                                    Total Pengeluaran Manual:
                                    <span class="text-red-500">
                                        <?= rupiah_pengeluaran($total_pengeluaran_manual); ?>
                                    </span>
                                </p>
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
