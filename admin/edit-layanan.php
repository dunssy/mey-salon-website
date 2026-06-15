<!-- edit layanan -->
<?php 
// Mengatur judul halaman
$page_title = "Data Layanan";

// Mengatur sub judul halaman
$sub_title = "Edit Layanan";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Mengambil id layanan dari URL
$id_layanan = isset($_GET['id_layanan']) ? (int) $_GET['id_layanan'] : 0;

// Mengecek id layanan valid
if ($id_layanan <= 0) {
    echo "<script>
            alert('ID layanan tidak valid!');
            window.location.href = 'data-layanan.php';
          </script>";
    exit;
}

// Mengambil data layanan berdasarkan id
$query = select("SELECT * FROM layanan WHERE id_layanan = $id_layanan");

// Mengecek data layanan ditemukan
if (empty($query)) {
    echo "<script>
            alert('Data layanan tidak ditemukan!');
            window.location.href = 'data-layanan.php';
          </script>";
    exit;
}

// Menyimpan data layanan ke variabel
$layanan = $query[0];

// Memproses edit layanan
if (isset($_POST['submit'])) {
    if (edit_layanan($_POST) > 0) {
        echo "<script>
                alert('Layanan berhasil diubah!');
                window.location.href = 'data-layanan.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Layanan gagal diubah atau tidak ada perubahan data!');
                window.location.href = 'edit-layanan.php?id_layanan=$id_layanan';
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

                <!-- Section edit layanan -->
                <section id="section-edit-layanan" class="space-y-6">

                    <!-- Header halaman -->
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <!-- Judul halaman -->
                            <div>
                                <h3 class="text-xl font-bold text-[#2B2424]">
                                    <?= htmlspecialchars($sub_title); ?>
                                </h3>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Ubah data layanan yang tersedia di Mey Salon.
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

                    <!-- Card form edit layanan -->
                    <div class="w-full bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                        <!-- Header form edit layanan -->
                        <div class="px-5 py-4 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                            <h4 class="font-bold text-[#3D3134]">
                                Form Edit Layanan
                            </h4>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Perbarui nama layanan, rentang harga, durasi, keterangan harga, dan gambar layanan.
                            </p>
                        </div>

                        <!-- Form edit layanan -->
                        <form action="" method="POST" enctype="multipart/form-data" class="p-5 sm:p-6 space-y-5">

                            <!-- Input id layanan tersembunyi -->
                            <input 
                                type="hidden" 
                                name="id_layanan" 
                                value="<?= htmlspecialchars($layanan['id_layanan']); ?>"
                            >

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
                                        value="<?= htmlspecialchars($layanan['nama_layanan']); ?>" 
                                        required 
                                        placeholder="Contoh: Hair Cut"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input harga minimal -->
                                <div>
                                    <label for="harga_min" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Harga Minimal
                                    </label>

                                    <input 
                                        type="number" 
                                        name="harga_min" 
                                        id="harga_min" 
                                        value="<?= htmlspecialchars($layanan['harga_min'] ?? 0); ?>" 
                                        required 
                                        min="0"
                                        placeholder="Contoh: 200000"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Input harga maksimal -->
                                <div>
                                    <label for="harga_max" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Harga Maksimal
                                    </label>

                                    <input 
                                        type="number" 
                                        name="harga_max" 
                                        id="harga_max" 
                                        value="<?= htmlspecialchars($layanan['harga_max'] ?? ($layanan['harga_min'] ?? 0)); ?>" 
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
                                        value="<?= htmlspecialchars($layanan['durasi_layanan']); ?>" 
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
                                        value="<?= htmlspecialchars($layanan['keterangan_harga'] ?? ''); ?>"
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

                                    <?php if (!empty($layanan['gambar_layanan'])) : ?>
                                        <div class="mb-3 p-3 rounded-2xl border border-[#F7D6E4] bg-[#FFF7FA] flex items-center gap-3">
                                            <img 
                                                src="../uploads/layanan/<?= htmlspecialchars($layanan['gambar_layanan']); ?>" 
                                                alt="<?= htmlspecialchars($layanan['nama_layanan']); ?>"
                                                class="w-16 h-16 rounded-xl object-cover border border-[#F7D6E4]"
                                                onerror="this.style.display='none'"
                                            >

                                            <div>
                                                <p class="text-xs font-bold text-[#3D3134]">
                                                    Gambar saat ini
                                                </p>

                                                <p class="text-[11px] text-[#B77B8E] mt-1">
                                                    <?= htmlspecialchars($layanan['gambar_layanan']); ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <input 
                                        type="file" 
                                        name="gambar_layanan" 
                                        id="gambar_layanan" 
                                        accept="image/*"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >

                                    <p class="text-[11px] text-[#B77B8E] mt-2">
                                        Kosongkan jika tidak ingin mengganti gambar layanan.
                                    </p>
                                </div>
                            </div>

                            <!-- Info layanan -->
                            <div class="p-4 rounded-2xl bg-[#FDEAF1]/60 border border-[#F7D6E4] text-xs text-[#6F5E64] leading-relaxed">
                                <b class="text-[#C75C7A]">Catatan:</b>
                                Perubahan harga, durasi, keterangan harga, dan gambar akan digunakan pada halaman booking customer berikutnya.
                            </div>

                            <!-- Tombol submit -->
                            <div class="pt-2 flex justify-end">
                                <button 
                                    type="submit" 
                                    name="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Ubah Layanan</span>
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
