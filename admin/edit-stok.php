<!-- edit barang  -->
 <?php 
$page_title = "Edit barang";
$sub_title = "Edit barang";
include "../layout/header.php";
include "../config/app.php";
// memanggil fungsi edit_barang dari controller.php untuk menambahkan barang baru ke database
if(isset($_POST['submit'])){
    // memanggil fungsi edit_barang dari controller.php untuk menambahkan barang baru ke database
    if(edit_barang($_POST) > 0){
        echo "<script>
                alert('barang berhasil diubah!');
                window.location.href = 'data-stok.php';
              </script>";
    } else {
        echo "<script>
                alert('barang gagal diubah!');
                window.location.href = 'data-stok.php';
              </script>";
    }
}

$query = select("SELECT * FROM stok_barang WHERE id_barang = " . $_GET['id_barang']);
$barang = $query[0];

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
                   <!-- form edit barang -->
                   <a href="data-stok.php" class="inline-block px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors">
                       <i class="fa-solid fa-arrow-left"></i> Kembali
                   </a>    
                     <form action="" method="POST" class="mb-6 mt-4 p-6 bg-white p-6 rounded-2xl shadow-sm border border-pink-100 ">
                        <div class="mb-4">
                             <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-1">Nama barang</label>
                             <input type="text" name="nama_barang" id="nama_barang" value="<?php echo $barang['nama_barang']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="jenis_barang" class="block text-sm font-medium text-gray-700 mb-1">Jenis barang</label>
                             <input type="text" name="jenis_barang" id="jenis_barang" value="<?php echo $barang['jenis_barang']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="jumlah_barang" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Barang</label>
                             <input type="number" name="jumlah_barang" id="jumlah_barang" value="<?php echo $barang['jumlah_barang']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                            <label for="satuan_barang" class="block text-sm font-medium text-gray-700 mb-1">Satuan Barang</label>
                            <select name="satuan_barang" id="satuan_barang" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                                <option value="ml" <?php if ($barang['satuan_barang'] == 'ml') echo 'selected'; ?>>ml</option>
                                <option value="pcs" <?php if ($barang['satuan_barang'] == 'pcs') echo 'selected'; ?>>pcs</option>
                            </select>                        
                        </div>
                        <div class="mb-4">
                             <label for="minimal_stok" class="block text-sm font-medium text-gray-700 mb-1">Minimal Stok</label>
                             <input type="number" name="minimal_stok" id="minimal_stok" value="<?php echo $barang['minimal_stok']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="harga_beli" class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                             <input type="number" name="harga_beli" id="harga_beli" value="<?php echo $barang['harga_beli']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                            <input type="hidden" name="id_barang" value="<?php echo $barang['id_barang']; ?>">
                            <input type="submit" name="submit" class="px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors" value="Ubah barang">
                    </form>
            </div>
            <!-- Footer Informatif -->
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>