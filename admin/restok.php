<?php 
// Mengatur judul halaman
$page_title = "Restok Barang";

// Mengatur sub judul halaman
$sub_title = "Restok Barang";

// Memanggil layout dan koneksi
include "../layout/header.php";
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil id barang dari URL jika ada
$id_barang_get = isset($_GET['id_barang']) ? (int) $_GET['id_barang'] : 0;

// Memproses tambah restok barang
if (isset($_POST['submit'])) {
    $id_barang = (int) $_POST['id_barang'];
    $jumlah_tambah_awal = (int) $_POST['jumlah_tambah_awal'];

    // Mengambil data barang berdasarkan id
    $barang = select("SELECT * FROM stok_barang WHERE id_barang = $id_barang");

    // Mengecek barang dan jumlah valid
    if (!empty($barang) && $jumlah_tambah_awal > 0) {
        $barang = $barang[0];
        $jumlah_tambah = $jumlah_tambah_awal * ($barang['jumlah_satuan']);
        // harga beli satuan
        $harga_beli_satuan = (int) $barang['harga_beli'];
        
        
        
        // Menghitung total harga restok
        $harga_beli = (int) $barang['harga_beli'];
        $total_harga_restok = $harga_beli * $jumlah_tambah_awal;

        // Menyimpan data restok untuk laporan pengeluaran
        mysqli_query(
            $koneksi,
            "INSERT INTO restok 
                (id_barang, jumlah_tambah, harga_restok, total_harga_restok) 
             VALUES 
                ($id_barang, $jumlah_tambah, $harga_beli_satuan, $total_harga_restok)"
        );

        // Menambahkan stok barang
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang 
             SET jumlah_barang = jumlah_barang + $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        echo "<script>
                alert('Restok berhasil ditambahkan dan masuk laporan pengeluaran!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data barang atau jumlah restok tidak valid!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    }
}

// Memproses hapus data restok
if (isset($_GET['hapus'])) {
    $id_restok = (int) $_GET['hapus'];

    // Mengambil data restok berdasarkan id
    $restok = select("SELECT * FROM restok WHERE id_restok = $id_restok");

    // Mengecek data restok ditemukan
    if (!empty($restok)) {
        $restok = $restok[0];

        $id_barang = (int) $restok['id_barang'];
        $jumlah_tambah = (int) $restok['jumlah_tambah'];

        // Mengurangi stok barang sesuai restok yang dihapus
        mysqli_query(
            $koneksi,
            "UPDATE stok_barang
             SET jumlah_barang = jumlah_barang - $jumlah_tambah
             WHERE id_barang = $id_barang"
        );

        // Menghapus data restok dari laporan
        mysqli_query(
            $koneksi,
            "DELETE FROM restok
             WHERE id_restok = $id_restok"
        );

        echo "<script>
                alert('Data restok berhasil dihapus!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Data restok tidak ditemukan!');
                window.location.href = 'restok.php';
              </script>";
        exit;
    }
}

// Mengambil data barang untuk pilihan form
$data_barang = select("
    SELECT * FROM stok_barang
    ORDER BY nama_barang ASC
");

// Mengambil data restok untuk tabel
$data_restok = select("
    SELECT 
        restok.*,
        stok_barang.nama_barang,
        stok_barang.satuan_barang
    FROM restok
    JOIN stok_barang ON restok.id_barang = stok_barang.id_barang
    ORDER BY restok.id_restok DESC
");

// Menghitung total pengeluaran restok
$total_pengeluaran_restok = 0;

foreach ($data_restok as $restok_item) {
    $total_pengeluaran_restok += (int) $restok_item['total_harga_restok'];
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

                <!-- Section restok barang -->
                <section class="space-y-6">

                    <!-- Header halaman -->
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                        <!-- Judul halaman -->
                        <div>
                            <h3 class="text-xl font-bold text-[#2B2424]">
                                <?= htmlspecialchars($sub_title); ?>
                            </h3>

                            <p class="text-xs text-[#B77B8E] mt-1">
                                Kelola restok barang dan otomatis tampil sebagai pengeluaran di laporan.
                            </p>
                        </div>

                        <!-- Tombol kembali -->
                        <a 
                            href="data-stok.php" 
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-bold text-[#C75C7A] bg-white border border-[#F7D6E4] rounded-xl hover:bg-[#FDEAF1] transition-colors"
                        >
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Kembali</span>
                        </a>
                    </div>

                    <!-- Layout form dan tabel -->
                    <div class="grid grid-cols-1 xl:grid-cols-[390px_1fr] gap-6 items-start">

                        <!-- Card form restok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                            <!-- Header form -->
                            <div class="p-5 sm:p-6 border-b border-[#F7D6E4] bg-[#FDEAF1]/60">
                                <h4 class="font-bold text-[#3D3134]">
                                    Tambah Restok Barang
                                </h4>

                                <p class="text-xs text-[#B77B8E] mt-1">
                                    Pilih barang dan masukkan jumlah tambahan stok.
                                </p>
                            </div>

                            <!-- Form restok -->
                            <form action="" method="POST" class="p-6 space-y-4">

                                <!-- Input id barang terpilih -->
                                <input 
                                    type="hidden" 
                                    name="id_barang" 
                                    id="id_barang"
                                    value="<?= $id_barang_get > 0 ? (int) $id_barang_get : ''; ?>"
                                    required
                                >

                                <!-- Pilihan barang lewat tombol popup -->
                                <div>
                                    <label class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Pilih Barang
                                    </label>

                                    <!-- Info barang terpilih -->
                                    <div id="barang-terpilih-box" class="mb-3 p-4 rounded-2xl bg-[#FDEAF1]/60 border border-[#F7D6E4] text-sm">
                                        <p class="text-[10px] font-bold text-[#B77B8E] uppercase tracking-wider">
                                            Barang Terpilih
                                        </p>

                                        <p id="barang-terpilih-nama" class="font-bold text-[#C75C7A] mt-1">
                                            Belum ada barang dipilih
                                        </p>

                                        <p id="barang-terpilih-detail" class="text-xs text-[#7A6F6F] mt-1">
                                            Klik tombol pilih barang untuk melihat semua data barang.
                                        </p>
                                    </div>

                                    <!-- Tombol buka daftar barang -->
                                    <button
                                        type="button"
                                        onclick="openBarangModal()"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#C75C7A] text-white text-sm font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition"
                                    >
                                        <i class="fa-solid fa-box-open"></i>
                                        <span>Pilih Barang</span>
                                    </button>
                                </div>

                                <!-- Input jumlah restok -->
                                <div>
                                    <label for="jumlah_tambah" class="block text-sm font-bold text-[#3D3134] mb-2">
                                        Jumlah Tambah
                                    </label>

                                    <input
                                        type="number"
                                        name="jumlah_tambah_awal"
                                        id="jumlah_tambah_awal"
                                        required
                                        min="1"
                                        placeholder="Masukkan jumlah restok"
                                        class="w-full px-4 py-3 border border-[#EAD8D0] bg-[#FFF7FA] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#FAD7E5] focus:border-[#C75C7A]"
                                    >
                                </div>

                                <!-- Info laporan -->
                                <div class="p-4 bg-[#FDEAF1]/60 border border-[#F7D6E4] rounded-2xl text-xs text-[#7A6F6F] leading-relaxed">
                                    Setelah disimpan, total restok akan masuk ke laporan sebagai pengeluaran restok.
                                </div>

                                <!-- Tombol simpan -->
                                <button
                                    type="submit"
                                    name="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-[#C75C7A] text-white font-bold rounded-xl hover:bg-[#B14F6C] shadow-sm shadow-[#FAD7E5] transition-colors"
                                >
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                    <span>Simpan Restok</span>
                                </button>
                            </form>
                        </div>

                        <!-- Card tabel restok -->
                        <div class="bg-white rounded-2xl shadow-sm border border-[#F7D6E4] overflow-hidden">

                            <!-- Header tabel -->
                            <div class="p-5 sm:p-6 border-b border-[#F7D6E4] bg-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h4 class="font-bold text-[#3D3134]">
                                        Data Restok Barang
                                    </h4>

                                    <p class="text-xs text-[#B77B8E] mt-1">
                                        Riwayat restok yang akan tampil di laporan.
                                    </p>
                                </div>

                                <span class="text-xs text-red-600 bg-red-50 px-3 py-1 rounded-full font-bold">
                                    Pengeluaran Restok
                                </span>
                            </div>

                            <!-- Tabel restok -->
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left min-w-[800px]">

                                    <!-- Header tabel -->
                                    <thead class="bg-[#EFA9BF] text-white text-[11px] uppercase font-bold tracking-wider">
                                        <tr>
                                            <th class="px-6 py-3">No</th>
                                            <th class="px-6 py-3">Nama Barang</th>
                                            <th class="px-6 py-3">Tanggal</th>
                                            <th class="px-6 py-3">Jumlah</th>
                                            <th class="px-6 py-3">Total Restok</th>
                                            <th class="px-6 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <!-- Isi tabel -->
                                    <tbody class="divide-y divide-[#F7D6E4]">

                                        <?php if (!empty($data_restok)) : ?>
                                            <?php $no = 1; ?>

                                            <!-- Perulangan data restok -->
                                            <?php foreach ($data_restok as $restok) : ?>
                                                <tr class="hover:bg-[#FDEAF1]/40 transition-colors">

                                                    <!-- Nomor -->
                                                    <td class="px-6 py-4">
                                                        <?= $no++; ?>
                                                    </td>

                                                    <!-- Nama barang -->
                                                    <td class="px-6 py-4 font-medium text-[#3D3134]">
                                                        <?= htmlspecialchars($restok['nama_barang']); ?>
                                                    </td>

                                                    <!-- Tanggal restok -->
                                                    <td class="px-6 py-4 text-[#7A6F6F]">
                                                        <?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?>
                                                    </td>

                                                    <!-- Jumlah tambah -->
                                                    <td class="px-6 py-4">
                                                        <?= htmlspecialchars($restok['jumlah_tambah']); ?>
                                                        <?= htmlspecialchars($restok['satuan_barang'] ?? ''); ?>
                                                    </td>

                                                    <!-- Total harga restok -->
                                                    <td class="px-6 py-4 font-bold text-red-600">
                                                        Rp <?= number_format($restok['total_harga_restok'], 0, ',', '.'); ?>
                                                    </td>

                                                    <!-- Tombol aksi -->
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center justify-center">
                                                            <a
                                                                href="?hapus=<?= (int) $restok['id_restok']; ?>"
                                                                onclick="return confirm('Yakin ingin menghapus data restok ini? Stok barang juga akan dikurangi kembali.')"
                                                                class="inline-flex items-center gap-2 px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-xs font-bold"
                                                            >
                                                                <i class="fa-solid fa-trash"></i>
                                                                <span>Hapus</span>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>

                                        <?php else : ?>

                                            <!-- Pesan data kosong -->
                                            <tr>
                                                <td colspan="6" class="px-6 py-8 text-center text-[#B77B8E]">
                                                    Data restok belum tersedia.
                                                </td>
                                            </tr>

                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Memanggil footer informatif -->
            <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>



<!-- Modal pilih barang restok -->
<div id="barang-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">

    <!-- Box modal pilih barang -->
    <div class="w-full max-w-3xl bg-white rounded-3xl shadow-2xl border border-[#F7D6E4] overflow-hidden max-h-[90vh] flex flex-col">

        <!-- Header modal -->
        <div class="p-5 border-b border-[#F7D6E4] bg-[#FDEAF1]/70 flex items-start justify-between gap-4">
            <div>
                <h4 class="font-bold text-[#3D3134]">
                    Pilih Barang
                </h4>

                <p class="text-xs text-[#B77B8E] mt-1">
                    Semua data barang ditampilkan di sini. Klik pilih pada barang yang ingin direstok.
                </p>
            </div>

            <!-- Tombol tutup modal -->
            <button
                type="button"
                onclick="closeBarangModal()"
                class="w-9 h-9 rounded-xl bg-white text-[#C75C7A] border border-[#F7D6E4] hover:bg-[#FDEAF1] transition"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Isi modal -->
        <div class="p-4 sm:p-5 overflow-y-auto">

            <!-- Daftar semua barang -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <?php foreach ($data_barang as $barang) : ?>
                    <?php
                    // Mengecek barang sudah terpilih dari URL
                    $is_selected_barang = $id_barang_get === (int) $barang['id_barang'];
                    ?>

                    <!-- Card barang -->
                    <div 
                        class="barang-option-card p-4 rounded-2xl border <?= $is_selected_barang ? 'border-[#C75C7A] bg-[#FDEAF1]' : 'border-[#F7D6E4] bg-white'; ?> hover:bg-[#FDEAF1]/70 transition"
                        data-id="<?= (int) $barang['id_barang']; ?>"
                    >
                        <div class="flex items-start justify-between gap-3">

                            <!-- Detail barang -->
                            <div class="min-w-0">
                                <p class="font-bold text-[#3D3134] text-sm">
                                    <?= htmlspecialchars($barang['nama_barang']); ?>
                                </p>

                                <p class="text-xs text-[#7A6F6F] mt-1">
                                    Stok:
                                    <b><?= htmlspecialchars($barang['jumlah_barang']); ?> <?= htmlspecialchars($barang['satuan_barang'] ?? ''); ?></b>
                                </p>

                                <p class="text-xs text-[#7A6F6F] mt-1">
                                    Isi per botol:
                                    <b><?= htmlspecialchars($barang['jumlah_satuan'] ?? 0); ?> <?= htmlspecialchars($barang['satuan_barang'] ?? ''); ?></b>
                                </p>

                                <p class="text-xs text-[#C75C7A] font-bold mt-1">
                                    Rp <?= number_format($barang['harga_beli'], 0, ',', '.'); ?> / botol
                                </p>
                            </div>

                            <!-- Tombol pilih -->
                            <button
                                type="button"
                                onclick="pilihBarangRestok(
                                    <?= (int) $barang['id_barang']; ?>,
                                    '<?= htmlspecialchars(addslashes($barang['nama_barang'])); ?>',
                                    '<?= htmlspecialchars(addslashes($barang['jumlah_barang'])); ?>',
                                    '<?= htmlspecialchars(addslashes($barang['satuan_barang'] ?? '')); ?>',
                                    '<?= htmlspecialchars(addslashes($barang['jumlah_satuan'] ?? 0)); ?>',
                                    '<?= number_format($barang['harga_beli'], 0, ',', '.'); ?>'
                                )"
                                class="btn-pilih-barang shrink-0 px-3 py-2 rounded-xl text-xs font-bold <?= $is_selected_barang ? 'bg-[#C75C7A] text-white' : 'bg-[#FDEAF1] text-[#C75C7A]'; ?> hover:bg-[#C75C7A] hover:text-white transition"
                            >
                                <?= $is_selected_barang ? 'Dipilih' : 'Pilih'; ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (empty($data_barang)) : ?>
                <div class="p-8 text-center text-[#B77B8E] text-sm">
                    Belum ada data barang.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


    <!-- Script pilih barang restok -->
    <script>
        // Membuka modal daftar barang
        function openBarangModal() {
            const modal = document.getElementById('barang-modal');

            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        // Menutup modal daftar barang
        function closeBarangModal() {
            const modal = document.getElementById('barang-modal');

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Memilih barang dari daftar barang
        function pilihBarangRestok(idBarang, namaBarang, jumlahBarang, satuanBarang, jumlahSatuan, hargaBeli) {
            const inputIdBarang = document.getElementById('id_barang');
            const namaBox = document.getElementById('barang-terpilih-nama');
            const detailBox = document.getElementById('barang-terpilih-detail');
            const cards = document.querySelectorAll('.barang-option-card');
            const buttons = document.querySelectorAll('.btn-pilih-barang');

            if (inputIdBarang) {
                inputIdBarang.value = idBarang;
            }

            if (namaBox) {
                namaBox.textContent = namaBarang;
            }

            if (detailBox) {
                detailBox.textContent = 'Stok saat ini: ' + jumlahBarang + ' ' + satuanBarang + ' | Isi per botol: ' + jumlahSatuan + ' ' + satuanBarang + ' | Harga: Rp ' + hargaBeli + ' / botol';
            }

            cards.forEach(function (card) {
                card.classList.remove('border-[#C75C7A]', 'bg-[#FDEAF1]');
                card.classList.add('border-[#F7D6E4]', 'bg-white');

                if (Number(card.dataset.id) === Number(idBarang)) {
                    card.classList.remove('border-[#F7D6E4]', 'bg-white');
                    card.classList.add('border-[#C75C7A]', 'bg-[#FDEAF1]');
                }
            });

            buttons.forEach(function (button) {
                button.textContent = 'Pilih';
                button.classList.remove('bg-[#C75C7A]', 'text-white');
                button.classList.add('bg-[#FDEAF1]', 'text-[#C75C7A]');
            });

            const selectedButton = document.querySelector('.barang-option-card[data-id="' + idBarang + '"] .btn-pilih-barang');

            if (selectedButton) {
                selectedButton.textContent = 'Dipilih';
                selectedButton.classList.remove('bg-[#FDEAF1]', 'text-[#C75C7A]');
                selectedButton.classList.add('bg-[#C75C7A]', 'text-white');
            }

            closeBarangModal();
        }

        // Mengisi barang terpilih dari URL jika ada
        document.addEventListener('DOMContentLoaded', function () {
            const selectedCard = document.querySelector('.barang-option-card.border-\\[\\#C75C7A\\]');
            const selectedButton = selectedCard ? selectedCard.querySelector('.btn-pilih-barang') : null;

            if (selectedButton) {
                selectedButton.click();
            }
        });

        // Menutup modal saat klik area luar
        document.addEventListener('click', function (event) {
            const modal = document.getElementById('barang-modal');

            if (!modal || modal.classList.contains('hidden')) return;

            if (event.target === modal) {
                closeBarangModal();
            }
        });

        // Menutup modal saat tombol escape ditekan
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeBarangModal();
            }
        });
    </script>

<?php
// Memanggil footer utama
include "../layout/footer.php";
?>
