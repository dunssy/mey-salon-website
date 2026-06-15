<?php 
// Mengatur judul halaman
$page_title = "Data Layanan";

// Mengatur sub judul halaman
$sub_title = "Tambah Layanan";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Memproses tambah layanan
if (isset($_POST['submit'])) {
    if (tambah_layanan($_POST) > 0) {
        echo "<script>
                alert('Layanan berhasil ditambahkan!');
                window.location.href = 'data-layanan.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Layanan gagal ditambahkan!');
                window.location.href = 'data-layanan.php';
              </script>";
        exit;
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

                <!-- Section tambah layanan -->
                <section id="section-tambah-layanan" class="space-y-6">

                    <!-- Header halaman -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- Judul halaman -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    <?= htmlspecialchars($sub_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Tambahkan data layanan baru untuk Mey Salon.
                                </p>
                            </div>

                            <!-- Tombol kembali -->
                            <a 
                                href="data-layanan.php" 
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-[#FDEAF1] rounded-xl hover:bg-[#FAD7E5] transition-colors w-fit"
                            >
                                <i class="fa-solid fa-arrow-left"></i>
                                <span>Kembali</span>
                            </a>
                        </div>

                    <!-- Card form tambah layanan -->
                    <div class="w-full bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form tambah layanan -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                Form Tambah Layanan
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Isi nama layanan, rentang harga, durasi, keterangan harga, dan gambar layanan.
                            </p>
                        </div>

                        <!-- Form tambah layanan -->
                        <form action="" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-5">

                            <!-- Grid input layanan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                <!-- Input nama layanan -->
                                <div class="md:col-span-2">
                                    <label for="nama_layanan" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Nama Layanan
                                    </label>

                                    <input 
                                        type="text" 
                                        name="nama_layanan" 
                                        id="nama_layanan" 
                                        required 
                                        placeholder="Contoh: Hair Cut"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input harga layanan minimal -->
                                <div>
                                    <label for="harga_min" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Harga Minimal
                                    </label>

                                    <input 
                                        type="number" 
                                        name="harga_min" 
                                        id="harga_min" 
                                        required 
                                        min="0"
                                        placeholder="Contoh: 200000"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input harga layanan maksimal -->
                                <div>
                                    <label for="harga_max" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Harga Maksimal
                                    </label>

                                    <input 
                                        type="number" 
                                        name="harga_max" 
                                        id="harga_max" 
                                        required 
                                        min="0"
                                        placeholder="Contoh: 500000"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input durasi layanan -->
                                <div>
                                    <label for="durasi_layanan" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Durasi Layanan
                                    </label>

                                    <input 
                                        type="number" 
                                        name="durasi_layanan" 
                                        id="durasi_layanan" 
                                        required 
                                        min="1"
                                        placeholder="Durasi dalam menit"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input keterangan harga -->
                                <div>
                                    <label for="keterangan_harga" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Keterangan Harga
                                    </label>

                                    <input 
                                        type="text" 
                                        name="keterangan_harga" 
                                        id="keterangan_harga" 
                                        required
                                        placeholder="Contoh: Harga tergantung panjang rambut"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input gambar layanan -->
                                <div class="md:col-span-2">
                                    <label for="gambar_layanan" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Gambar Layanan
                                    </label>

                                    <input 
                                        type="file" 
                                        name="gambar_layanan" 
                                        id="gambar_layanan" 
                                        accept="image/*"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >

                                    <p class="text-[11px] text-[#B77B8E] mt-2">
                                        Gunakan gambar dengan ukuran rapi agar tampilan layanan lebih bagus.
                                    </p>
                                </div>
                            </div>

                            <!-- Info layanan -->
                            <div class="p-4 rounded-2xl bg-[#FDEAF1]/60 border border-[#F7D6E4] text-xs text-[#6F5E64] leading-relaxed">
                                <b class="text-[#C75C7A]">Catatan:</b>
                                Pastikan harga minimal, harga maksimal, dan durasi sudah sesuai karena data ini akan digunakan pada halaman booking customer.
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2 flex justify-end">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                >
                                    <i class="fa-solid fa-plus"></i>
                                    <span>Tambah Layanan</span>
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
