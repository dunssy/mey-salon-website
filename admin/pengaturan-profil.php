

    <?php 
$page_title = "Profil";
$sub_title = "Pengaturan Profil";
include "../layout/header.php";
include "../config/app.php";
global $koneksi;

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

// MENGAMBIL DATA user yang sedang login
$id_user = $_SESSION['user_id'];
$user = select("SELECT * FROM user WHERE id_user = $id_user")[0];

// PROSES UPDATE PROFIL
$success_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profil'])) {
    $nama = strip_tags($_POST['nama']);
    $no_hp = strip_tags($_POST['no_hp']);
    $alamat = strip_tags($_POST['alamat']);
    
    $nama = mysqli_real_escape_string($koneksi, $nama);
    $no_hp = mysqli_real_escape_string($koneksi, $no_hp);
    $alamat = mysqli_real_escape_string($koneksi, $alamat);
    
    $query = "UPDATE user SET nama = '$nama', no_hp = '$no_hp', alamat = '$alamat' WHERE id_user = $id_user";
    if (mysqli_query($koneksi, $query)) {
        $success_message = "Profil berhasil diperbarui!";
        // Refresh data user
        $user = select("SELECT * FROM user WHERE id_user = $id_user")[0];
    } else {
        $success_message = "Error: " . mysqli_error($koneksi);
    }
}
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
                <section id="section-profil" class="content-section">
                            <div class="max-w-xl mx-auto">
                                <div class="bg-white p-8 rounded-[2.5rem] border border-pink-100 shadow-sm space-y-6">
                                    <?php if ($success_message): ?>
                                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                                            <?= $success_message ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="text-center">
                                        <img src="https://placehold.co/100x100/fbcfe8/db2777?text=<?= substr($user['nama'], 0, 1) ?>" class="w-24 h-24 rounded-3xl mx-auto mb-4 border-4 border-pink-50 shadow-sm">
                                        <h3 class="font-bold text-xl">Profil Saya</h3>
                                    </div>
                                    <form method="POST" class="space-y-4">
                                        <div>
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Nama Lengkap</label>
                                            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400" required>
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Username</label>
                                            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400" disabled>
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">No. WhatsApp</label>
                                            <input type="tel" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400" required>
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Alamat</label>
                                            <textarea name="alamat" rows="3" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400" required><?= htmlspecialchars($user['alamat']) ?></textarea>
                                        </div>
                                        <div>
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-1">Role</label>
                                            <input type="text" value="<?= htmlspecialchars($user['role']) ?>" class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400" disabled>
                                        </div>
                                        <button type="submit" name="update_profil" class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100">
                                            Simpan Perubahan
                                        </button>
                                    </form>
                                </div>
                            </div>
                    </section>
            </div>
            <!-- Footer Informatif -->
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>