<?php 
// Mengatur judul halaman
$page_title = "Data User";

// Mengatur sub judul halaman
$sub_title = "Edit User";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil id user dari URL
$id_user = isset($_GET['id_user']) ? (int) $_GET['id_user'] : 0;

// Mengecek id user valid
if ($id_user <= 0) {
    echo "<script>
            alert('ID user tidak valid!');
            window.location.href = 'data-user.php';
          </script>";
    exit;
}

// Mengambil data user berdasarkan id
$query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id_user");
$user = mysqli_fetch_assoc($query_user);

// Mengecek data user ditemukan
if (!$user) {
    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location.href = 'data-user.php';
          </script>";
    exit;
}

// Mengecek role user
$is_admin_user = $user['role'] === 'Administrator';
$is_customer_user = $user['role'] === 'Customer';

// Memblokir proses edit jika role customer
if (isset($_POST['submit']) && $is_customer_user) {
    echo "<script>
            alert('Data customer tidak dapat diedit dari halaman admin!');
            window.location.href = 'data-user.php';
          </script>";
    exit;
}

// Memproses edit user jika role administrator
if (isset($_POST['submit']) && $is_admin_user) {
    if (edit_user($_POST) > 0) {
        echo "<script>
                alert('User berhasil diperbarui!');
                window.location.href = 'data-user.php';
              </script>";
    } else {
        echo "<script>
                alert('Tidak ada data yang diubah atau user gagal diperbarui!');
                window.location.href = 'edit-user.php?id_user=$id_user';
              </script>";
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

                <!-- Section edit user -->
                <section id="section-edit-user" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                <?= $is_admin_user ? 'Ubah data administrator yang terdaftar di sistem Mey Salon.' : 'Data customer hanya dapat dilihat dan tidak bisa diedit.'; ?>
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-user.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-white border border-[#F7D6E4] rounded-xl hover:bg-[#FDEAF1] transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Peringatan customer tidak bisa diedit -->
                    <?php if ($is_customer_user) : ?>
                        <div class="p-4 rounded-2xl bg-yellow-50 border border-yellow-100 text-yellow-700 text-sm leading-relaxed">
                            <b>Informasi:</b>
                            Role user ini adalah <b>Customer</b>, sehingga data tidak bisa diedit dari halaman admin. Admin hanya bisa mengedit akun dengan role <b>Administrator</b>.
                        </div>
                    <?php endif; ?>

                    <!-- Card form edit user -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form edit user -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                <?= $is_admin_user ? 'Form Edit Admin' : 'Detail Customer'; ?>
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                <?= $is_admin_user ? 'Role administrator dapat diedit dari halaman ini.' : 'Field dikunci karena customer tidak dapat diedit.'; ?>
                            </p>
                        </div>

                        <!-- Form edit user -->
                        <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

                            <!-- Input id user tersembunyi -->
                            <input 
                                type="hidden" 
                                name="id_user" 
                                id="id_user" 
                                value="<?= htmlspecialchars($user['id_user']); ?>"
                            >

                            <!-- Grid input user -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <!-- Input nama user -->
                                <div>
                                    <label for="nama" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Nama User
                                    </label>

                                    <input 
                                        type="text" 
                                        name="nama" 
                                        id="nama" 
                                        value="<?= htmlspecialchars($user['nama']); ?>" 
                                        required
                                        <?= $is_customer_user ? 'readonly' : ''; ?>
                                        class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl text-sm focus:outline-none <?= $is_customer_user ? 'bg-[#F8F4F2] text-[#7A6F6F] cursor-not-allowed' : 'bg-[#FFF7FA] focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]'; ?>"
                                    >
                                </div>

                                <!-- Input nomor telepon -->
                                <div>
                                    <label for="no_hp" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Nomor Telepon
                                    </label>

                                    <input 
                                        type="text" 
                                        name="no_hp" 
                                        id="no_hp" 
                                        value="<?= htmlspecialchars($user['no_hp']); ?>" 
                                        required
                                        <?= $is_customer_user ? 'readonly' : ''; ?>
                                        class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl text-sm focus:outline-none <?= $is_customer_user ? 'bg-[#F8F4F2] text-[#7A6F6F] cursor-not-allowed' : 'bg-[#FFF7FA] focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]'; ?>"
                                    >
                                </div>

                                <!-- Input email -->
                                <div>
                                    <label for="email" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Email
                                    </label>

                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="email" 
                                        value="<?= htmlspecialchars($user['email']); ?>" 
                                        required
                                        <?= $is_customer_user ? 'readonly' : ''; ?>
                                        class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl text-sm focus:outline-none <?= $is_customer_user ? 'bg-[#F8F4F2] text-[#7A6F6F] cursor-not-allowed' : 'bg-[#FFF7FA] focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]'; ?>"
                                    >
                                </div>

                                <!-- Input role -->
                                <div>
                                    <label for="role_tampil" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Role
                                    </label>

                                    <!-- Role asli tetap dikirim saat admin diedit -->
                                    <input 
                                        type="hidden" 
                                        name="role" 
                                        id="role" 
                                        value="<?= htmlspecialchars($user['role']); ?>"
                                    >

                                    <!-- Role tampil readonly -->
                                    <input 
                                        type="text" 
                                        id="role_tampil" 
                                        value="<?= htmlspecialchars($user['role']); ?>" 
                                        readonly
                                        class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl bg-[#F8F4F2] text-[#7A6F6F] text-sm cursor-not-allowed focus:outline-none"
                                    >
                                </div>

                                <!-- Input alamat -->
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Alamat
                                    </label>

                                    <textarea 
                                        name="alamat" 
                                        id="alamat" 
                                        rows="3"
                                        required
                                        <?= $is_customer_user ? 'readonly' : ''; ?>
                                        class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl text-sm resize-none focus:outline-none <?= $is_customer_user ? 'bg-[#F8F4F2] text-[#7A6F6F] cursor-not-allowed' : 'bg-[#FFF7FA] focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]'; ?>"
                                    ><?= htmlspecialchars($user['alamat']); ?></textarea>
                                </div>

                                <!-- Input password -->
                                <?php if ($is_admin_user) : ?>
                                    <div class="md:col-span-2">
                                        <label for="password" class="block text-sm font-bold text-[#3D3134] mb-2">
                                            Password
                                        </label>

                                        <input 
                                            type="password" 
                                            name="password" 
                                            id="password" 
                                            placeholder="Kosongkan jika tidak ingin mengubah password"
                                            class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                        >
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Info edit user -->
                            <div class="p-4 rounded-2xl <?= $is_admin_user ? 'bg-[#FDEAF1]/60 border-[#F7D6E4] text-[#6F5E64]' : 'bg-yellow-50 border-yellow-100 text-yellow-700'; ?> border text-xs leading-relaxed">
                                <?php if ($is_admin_user) : ?>
                                    <b class="text-[#C75C7A]">Catatan:</b>
                                    Data administrator dapat diperbarui. Kosongkan password jika tidak ingin mengubah password.
                                <?php else : ?>
                                    <b>Catatan:</b>
                                    Data customer dikunci dan tidak dapat diedit dari halaman ini.
                                <?php endif; ?>
                            </div>

                            <!-- Tombol submit hanya untuk administrator -->
                            <?php if ($is_admin_user) : ?>
                                <div class="pt-2 flex justify-end">
                                    <button 
                                        type="submit" 
                                        name="submit" 
                                        id="submit-button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                    >
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Edit Admin</span>
                                    </button>
                                </div>
                            <?php else : ?>
                                <div class="pt-2 flex justify-end">
                                    <a 
                                        href="data-user.php"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#FDEAF1] text-[#C75C7A] font-bold rounded-xl hover:bg-[#FAD7E5] transition-colors"
                                    >
                                        <i class="fa-solid fa-arrow-left"></i>
                                        <span>Kembali ke Data User</span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
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
