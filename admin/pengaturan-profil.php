<?php 
// Memulai session
session_start();

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

// Mengambil data user yang sedang login
$query_user = select("SELECT * FROM user WHERE id_user = $id_user");

// Mengecek data user ditemukan
if (empty($query_user)) {
    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location.href = '../login.php';
          </script>";
    exit;
}

// Menyimpan data user ke variabel
$user = $query_user[0];

// Menyiapkan pesan notifikasi
$success_message = '';
$error_message = '';

// Memproses update profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profil'])) {
    $nama = mysqli_real_escape_string($koneksi, strip_tags($_POST['nama']));
    $no_hp = mysqli_real_escape_string($koneksi, strip_tags($_POST['no_hp']));
    $alamat = mysqli_real_escape_string($koneksi, strip_tags($_POST['alamat']));

    $query_update = "UPDATE user SET 
                        nama = '$nama',
                        no_hp = '$no_hp',
                        alamat = '$alamat'
                     WHERE id_user = $id_user";

    if (mysqli_query($koneksi, $query_update)) {
        $success_message = "Profil berhasil diperbarui!";
        $user = select("SELECT * FROM user WHERE id_user = $id_user")[0];
    } else {
        $error_message = "Profil gagal diperbarui!";
    }
}
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

                <!-- Section pengaturan profil -->
                <section id="section-profil" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">
                                <?= $sub_title; ?>
                            </h3>

                            <p class="text-xs text-gray-400">
                                Kelola informasi profil akun Mey Salon Anda.
                            </p>
                        </div>
                    </div>

                    <!-- Card profil -->
                    <div class="max-w-xl mx-auto bg-white rounded-2xl border border-pink-100 shadow-sm overflow-hidden">

                        <!-- Isi card profil -->
                        <div class="p-6 md:p-8 space-y-6">

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

                            <!-- Header profil -->
                            <div class="text-center">

                                <!-- Foto profil placeholder -->
                                <img 
                                    src="https://placehold.co/100x100/fbcfe8/db2777?text=<?= urlencode(substr($user['nama'], 0, 1)); ?>" 
                                    alt="Foto Profil"
                                    class="w-24 h-24 rounded-3xl mx-auto mb-4 border-4 border-pink-50 shadow-sm"
                                >

                                <!-- Nama profil -->
                                <h3 class="font-bold text-xl text-gray-800">
                                    <?= htmlspecialchars($user['nama']); ?>
                                </h3>

                                <!-- Role profil -->
                                <p class="text-xs text-pink-500 font-semibold uppercase mt-1">
                                    <?= htmlspecialchars($user['role']); ?>
                                </p>
                            </div>

                            <!-- Form update profil -->
                            <form action="" method="POST" class="space-y-4">

                                <!-- Input nama lengkap -->
                                <div>
                                    <label for="nama" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                        Nama Lengkap
                                    </label>

                                    <input 
                                        type="text" 
                                        name="nama" 
                                        id="nama"
                                        value="<?= htmlspecialchars($user['nama']); ?>" 
                                        required
                                        class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400"
                                    >
                                </div>

                                <!-- Input email -->
                                <div>
                                    <label for="email" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                        email
                                    </label>

                                    <input 
                                        type="text" 
                                        id="email"
                                        value="<?= htmlspecialchars($user['email']); ?>" 
                                        disabled
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-400 outline-none cursor-not-allowed"
                                    >
                                </div>

                                <!-- Input nomor WhatsApp -->
                                <div>
                                    <label for="no_hp" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                        No. WhatsApp
                                    </label>

                                    <input 
                                        type="text" 
                                        name="no_hp" 
                                        id="no_hp"
                                        value="<?= htmlspecialchars($user['no_hp']); ?>" 
                                        required
                                        class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400"
                                    >
                                </div>

                                <!-- Input alamat -->
                                <div>
                                    <label for="alamat" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                        Alamat
                                    </label>

                                    <textarea 
                                        name="alamat" 
                                        id="alamat"
                                        rows="3" 
                                        required
                                        class="w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400 resize-none"
                                    ><?= htmlspecialchars($user['alamat']); ?></textarea>
                                </div>

                                <!-- Input role -->
                                <div>
                                    <label for="role" class="text-[11px] font-bold text-gray-400 uppercase ml-1">
                                        Role
                                    </label>

                                    <input 
                                        type="text" 
                                        id="role"
                                        value="<?= htmlspecialchars($user['role']); ?>" 
                                        disabled
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm text-gray-400 outline-none cursor-not-allowed"
                                    >
                                </div>

                                <!-- Tombol submit -->
                                <button 
                                    type="submit" 
                                    name="update_profil" 
                                    class="w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100"
                                >
                                    Simpan Perubahan
                                </button>
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