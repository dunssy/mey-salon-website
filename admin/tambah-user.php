<?php 
// Mengatur judul halaman
$page_title = "Data User";

// Mengatur sub judul halaman
$sub_title = "Tambah User";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Memproses tambah user
if (isset($_POST['submit'])) {
    if (tambah_user($_POST) > 0) {
        echo "<script>
                alert('User berhasil ditambahkan!');
                window.location.href = 'data-user.php';
              </script>";
    } else {
        echo "<script>
                alert('User gagal ditambahkan!');
                window.location.href = 'data-user.php';
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

                <!-- Section tambah user -->
                <section id="section-tambah-user" class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Tambahkan data administrator baru untuk sistem Mey Salon.
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

                    <!-- Card form tambah user -->
                    <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form tambah user -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                Form Tambah Admin
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Role dikunci sebagai Administrator agar halaman ini khusus untuk menambah admin.
                            </p>
                        </div>

                        <!-- Form tambah user -->
                        <form action="" method="POST" class="p-5 sm:p-6 space-y-5">

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
                                        required 
                                        placeholder="Masukkan nama user"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
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
                                        required 
                                        placeholder="Contoh: 081234567890"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
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
                                        required 
                                        placeholder="Masukkan email user"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input password -->
                                <div>
                                    <label for="password" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Password
                                    </label>

                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        required 
                                        placeholder="Masukkan password"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
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
                                        placeholder="Masukkan alamat user"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    ></textarea>
                                </div>

                                <!-- Role terkunci sebagai admin -->
                                <div class="md:col-span-2">
                                    <label for="role_tampil" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Role
                                    </label>

                                    <!-- Input role asli untuk dikirim ke database -->
                                    <input 
                                        type="hidden" 
                                        name="role" 
                                        id="role" 
                                        value="Administrator"
                                    >

                                    <!-- Tampilan role readonly -->
                                    <input 
                                        type="text" 
                                        id="role_tampil" 
                                        value="Administrator" 
                                        readonly
                                        class="w-full px-4 py-3 border border-[#EAD8D0] rounded-xl bg-[#F8F4F2] text-[#7A6F6F] text-sm cursor-not-allowed focus:outline-none"
                                    >

                                    <p class="text-[11px] text-[#B77B8E] mt-2">
                                        Role dikunci sebagai Administrator dan tidak bisa diubah dari form ini.
                                    </p>
                                </div>
                            </div>

                            <!-- Info user -->
                            <div class="p-4 rounded-2xl bg-[#FDEAF1]/60 border border-[#F7D6E4] text-xs text-[#6F5E64] leading-relaxed">
                                <b class="text-[#C75C7A]">Catatan:</b>
                                User yang dibuat dari halaman ini otomatis menjadi admin. Untuk customer, gunakan halaman registrasi customer.
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2 flex justify-end">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    id="submit-button"
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tambah Admin</span>
                                </button>
                            </div>
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
