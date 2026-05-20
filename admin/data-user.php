<?php 
$page_title = "User";
$sub_title = "Data User";
include "../layout/header.php";
include "../config/app.php";
global $koneksi;

// MENGAMBIL DATA user DARI TABLE user   
$user = select("SELECT * FROM user");
// nomor urut untuk tabel user
$nomor = 1;

// DELETE USER
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $id_user = $_POST['hapus'];
    $result = mysqli_query($koneksi, "DELETE FROM user WHERE id_user = $id_user");
    
    if ($result) {
        header("Location: data-user.php?status=success");
        exit;
    } else {
        $error_message = "Gagal menghapus user.";
    }
}



//PAGINATION
$jumlah_per_halaman = 5; // Ubah angka ini untuk menentukan jumlah baris per halaman
$halaman_aktif = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($halaman_aktif < 1) {
    $halaman_aktif = 1;
}

// 2. AMBIL DATA & TOTAL HALAMAN
$layanan = tampil_layanan_per_halaman($halaman_aktif, $jumlah_per_halaman);
$total_halaman = hitung_total_halaman_layanan($jumlah_per_halaman);
?>
<body class="text-gray-800 overflow-x-hidden">

    <div class="flex h-screen overflow-hidden">
        <!-- PEMANGGILAN SIDEBAR -->
         <?php include "../layout/sidebar.php"; ?>
        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto bg-pink-50/30">
           <!-- PEMANGGILAN NAVBAR -->
            <?php include "../layout/navbar.php"; ?>
            <!-- Page Content -->
            <div class="p-4 md:p-8 flex-1">
                <!-- Section Service -->
                    <!-- SUB JUDUL -->
                    <h3 class="text-lg font-bold mb-6"><?php echo $sub_title; ?></h3>
                    <!-- tambah layanan -->
                    <a href="tambah-user.php" class="mb-4 inline-block px-4 py-2 text-sm font-bold text-white bg-pink-600 rounded-lg hover:bg-pink-700 transition-colors">
                        <i class="fa-solid fa-plus"></i>Tambah Layanan
                    </a>
                    <!-- TABLE LAYANAN -->
                     <!-- Tabel Data -->
                    <div class="bg-white rounded-2xl shadow-sm border border-pink-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-pink-50/50 border-b border-pink-100 text-gray-700 font-semibold">
                                    <th class="p-4 w-16">No</th>
                                    <th class="p-4">Nama</th>
                                    <th class="p-4">No Handphone</th>
                                    <th class="p-4">Alamat</th>
                                    <th class="p-4">Username</th>
                                    <th class="p-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-pink-50">
                                <?php 
                                $no = (($halaman_aktif - 1) * $jumlah_per_halaman) + 1; 
                                
                                if (!empty($layanan)) {
                                    foreach ($user as $data_user) : 
                                ?>
                                    <tr class="hover:bg-pink-50/20 transition-colors">
                                        <td class="p-4"><?= $no++; ?></td>
                                        <td class="p-4 font-medium"><?= htmlspecialchars($data_user['nama']); ?></td>
                                        <td class="p-4"><?= number_format($data_user['no_hp']); ?></td>
                                        <td class="p-4"><?= htmlspecialchars($data_user['alamat']); ?></td>
                                        <td class="p-4"><?= htmlspecialchars($data_user['username']); ?></td>
                                        <td class="p-4">
                                            <a href="edit-user.php?=<?= $data_user['id_user']; ?>" class="px-3 py-1.5 text-xs font-medium bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">Edit</a>
                                            <form action="" method="POST" class="inline-block">
                                                <input type="hidden" name="hapus" value="<?= $data_user['id_user']; ?>">
                                                <button type="submit" class="px-3 py-1.5 text-xs font-medium bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?');">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php 
                                    endforeach; 
                                }else{
                                ?>
                                    <tr>
                                        <td colspan="8" class="p-8 text-center text-gray-400">Belum ada data user.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>

                            <!-- BAGIAN TFOOT UNTUK PAGINATION DI POJOK KANAN BAWAH -->
                            <tfoot class="bg-gray-50/50 border-t border-pink-100">
                                <tr>
                                    <td colspan="8" class="p-4">
                                        <div class="flex justify-end md-4 gap-2">
                                            
                                            <!-- Tombol Prev -->
                                            <?php if ($halaman_aktif > 1) : ?>
                                                <a href="?halaman=<?= $halaman_aktif - 1; ?>" class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors">&laquo; Prev</a>
                                            <?php else : ?>
                                                <span class="px-3 py-1.5 text-xs font-medium bg-gray-50 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">&laquo; Prev</span>
                                            <?php endif; ?>

                                            <!-- Loop Angka Halaman -->
                                            <?php for ($i = 1; $i <= $total_halaman; $i++) : ?>
                                                <a href="?halaman=<?= $i; ?>" class="px-3 py-1.5 text-xs font-medium border rounded-lg transition-colors <?= $i === $halaman_aktif ? 'bg-pink-600 border-pink-600 text-white' : 'bg-white border-gray-200 text-gray-600 hover:bg-pink-50 hover:text-pink-600'; ?>">
                                                    <?= $i; ?>
                                                </a>
                                            <?php endfor; ?>

                                            <!-- Tombol Next -->
                                            <?php if ($halaman_aktif < $total_halaman) : ?>
                                                <a href="?halaman=<?= $halaman_aktif + 1; ?>" class="px-3 py-1.5 text-xs font-medium bg-white border border-gray-200 rounded-lg text-gray-600 hover:bg-pink-50 hover:text-pink-600 transition-colors">Next &raquo;</a>
                                            <?php else : ?>
                                                <span class="px-3 py-1.5 text-xs font-medium bg-gray-50 border border-gray-200 rounded-lg text-gray-300 cursor-not-allowed">Next &raquo;</span>
                                            <?php endif; ?>

                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
            </div>
            <!-- Footer Informatif -->
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>