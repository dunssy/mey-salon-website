<?php 


// Mengatur judul halaman
$page_title = "Profil";

// Mengatur sub judul halaman
$sub_title = "Pengaturan Profil";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek user sudah login
if (!isset($_SESSION['user_id']) && !isset($_SESSION['id_user'])) {
    header("Location: ../login.php");
    exit;
}

// Mengambil id user dari session
$id_user = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : (int) $_SESSION['id_user'];

// Mengambil data user login
$query_user = select("SELECT * FROM user WHERE id_user = $id_user");

// Mengecek data user ditemukan
if (empty($query_user)) {
    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Menyimpan data user
$user = $query_user[0];

// Menyiapkan pesan notifikasi
$success_message = '';
$error_message = '';

// Memproses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($koneksi, strip_tags($_POST['nama']));
    $no_hp = mysqli_real_escape_string($koneksi, strip_tags($_POST['no_hp']));
    $alamat = mysqli_real_escape_string($koneksi, strip_tags($_POST['alamat']));

    // Menyimpan perubahan profil
    $query_update = "UPDATE user SET 
                        nama = '$nama',
                        no_hp = '$no_hp',
                        alamat = '$alamat'
                     WHERE id_user = $id_user";

    if (mysqli_query($koneksi, $query_update)) {
        $success_message = "Profil berhasil diperbarui!";
        $user = select("SELECT * FROM user WHERE id_user = $id_user")[0];

        // Memperbarui session nama jika ada
        $_SESSION['nama'] = $user['nama'];
    } else {
        $error_message = "Profil gagal diperbarui!";
    }
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

                <!-- Section pengaturan profil -->
                <section id="section-profil" class="space-y-6">

                    <!-- Header halaman -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- Judul halaman -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    <?= htmlspecialchars($sub_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Kelola informasi profil akun Mey Salon Anda.
                                </p>
                            </div>

                            <!-- Tombol kembali -->
                            <a 
                                href="dashboard-admin.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors w-fit"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>

                    <!-- Card profil -->
                    <div class="w-full bg-white rounded-2xl border border-[#F7D6E4] shadow-sm overflow-hidden">

                        <!-- Header card profil -->
                        <div class="p-5 sm:p-6 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                                <!-- Foto profil placeholder -->
                                <img 
                                    src="https://placehold.co/100x100/fbcfe8/db2777?text=<?= urlencode(substr($user['nama'], 0, 1)); ?>" 
                                    alt="Foto Profil"
                                    class="w-16 h-16 sm:w-20 sm:h-20 rounded-3xl border-4 border-white shadow-sm"
                                >

                                <!-- Info profil -->
                                <div>
                                    <h3 class="font-bold text-xl text-[#2B2424]">
                                        <?= htmlspecialchars($user['nama']); ?>
                                    </h3>

                                    <p class="text-xs text-[#C75C7A] font-semibold uppercase mt-1">
                                        <?= htmlspecialchars($user['role']); ?>
                                    </p>

                                    <p class="text-xs text-[#B77B8E] mt-2">
                                        Email dan role hanya bisa dilihat, tidak bisa diubah dari halaman ini.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Isi card profil -->
                        <div class="p-5 sm:p-6 space-y-6">

                            <!-- Pesan sukses -->
                            <?php if ($success_message) : ?>
                                <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm font-medium">
                                    <?= htmlspecialchars($success_message); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Pesan error -->
                            <?php if ($error_message) : ?>
                                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
                                    <?= htmlspecialchars($error_message); ?>
                                </div>
                            <?php endif; ?>

                            <!-- Form update profil -->
                            <form action="" method="POST" class="space-y-5">

                                <!-- Grid input profil -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Input nama lengkap -->
                                <div>
                                    <label for="nama" class="block text-[11px] font-bold text-[#B77B8E] uppercase tracking-wider mb-2">
                                        Nama Lengkap
                                    </label>

                                    <input 
                                        type="text" 
                                        name="nama" 
                                        id="nama"
                                        value="<?= htmlspecialchars($user['nama']); ?>" 
                                        required
                                        class="w-full px-4 py-3 bg-[#FFF7FA] border border-[#EAD8D0] rounded-xl text-sm outline-none focus:border-[#C75C7A] focus:ring-2 focus:ring-[#FAD7E5]"
                                    >
                                </div>

                                <!-- Input email -->
                                <div>
                                    <label for="email" class="block text-[11px] font-bold text-[#B77B8E] uppercase tracking-wider mb-2">
                                        Email
                                    </label>

                                    <input 
                                        type="text" 
                                        id="email"
                                        value="<?= htmlspecialchars($user['email']); ?>" 
                                        disabled
                                        class="w-full px-4 py-3 bg-[#F8F4F2] border border-[#EAD8D0] rounded-xl text-sm text-[#7A6F6F] outline-none cursor-not-allowed"
                                    >
                                </div>

                                <!-- Input nomor WhatsApp -->
                                <div>
                                    <label for="no_hp" class="block text-[11px] font-bold text-[#B77B8E] uppercase tracking-wider mb-2">
                                        No. WhatsApp
                                    </label>

                                    <input 
                                        type="text" 
                                        name="no_hp" 
                                        id="no_hp"
                                        value="<?= htmlspecialchars($user['no_hp']); ?>" 
                                        required
                                        class="w-full px-4 py-3 bg-[#FFF7FA] border border-[#EAD8D0] rounded-xl text-sm outline-none focus:border-[#C75C7A] focus:ring-2 focus:ring-[#FAD7E5]"
                                    >
                                </div>

                                                                <!-- Input role -->
                                <div>
                                    <label for="role" class="block text-[11px] font-bold text-[#B77B8E] uppercase tracking-wider mb-2">
                                        Role
                                    </label>

                                    <input 
                                        type="text" 
                                        id="role"
                                        value="<?= htmlspecialchars($user['role']); ?>" 
                                        disabled
                                        class="w-full px-4 py-3 bg-[#F8F4F2] border border-[#EAD8D0] rounded-xl text-sm text-[#7A6F6F] outline-none cursor-not-allowed"
                                    >
                                </div>

                                <!-- Input alamat -->
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-[11px] font-bold text-[#B77B8E] uppercase tracking-wider mb-2">
                                        Alamat
                                    </label>

                                    <textarea 
                                        name="alamat" 
                                        id="alamat"
                                        rows="3" 
                                        required
                                        class="w-full px-4 py-3 bg-[#FDEAF1]/50 border border-[#F7D6E4] rounded-2xl text-sm outline-none focus:border-pink-400 resize-none"
                                    ><?= htmlspecialchars($user['alamat']); ?></textarea>
                                </div>

                                </div>

                                <!-- Tombol aksi -->
                                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">

                                    <!-- Tombol simpan -->
                                    <button 
                                        type="submit" 
                                        name="update_profil" 
                                        class="w-full sm:w-auto px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] transition-all shadow-sm shadow-[#FAD7E5]"
                                    >
                                        Simpan Perubahan
                                    </button>

                                    <!-- Tombol kembali bawah -->
                                    <a 
                                        href="dashboard-admin.php"
                                        class="w-full sm:w-auto px-5 py-3 bg-[#F8F4F2] text-[#7A6F6F] font-bold rounded-xl hover:bg-[#FDEAF1] hover:text-[#C75C7A] transition-all text-center"
                                    >
                                        Kembali
                                    </a>
                                </div>
                            </form>
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
