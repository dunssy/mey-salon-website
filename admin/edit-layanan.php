<!-- edit layanan  -->
 <?php 
$page_title = "Edit Layanan";
$sub_title = "Edit Layanan";
include "../layout/header.php";
include "../config/app.php";
// memanggil fungsi edit_layanan dari controller.php untuk menambahkan layanan baru ke database
if(isset($_POST['submit'])){
    // memanggil fungsi edit_layanan dari controller.php untuk menambahkan layanan baru ke database
    if(edit_layanan($_POST) > 0){
        echo "<script>
                alert('Layanan berhasil diubah!');
                window.location.href = 'data-layanan.php';
              </script>";
    } else {
        echo "<script>
                alert('Layanan gagal diubah!');
                window.location.href = 'data-layanan.php';
              </script>";
    }
}

$query = select("SELECT* FROM layanan WHERE id_layanan = " . $_GET['id_layanan']);
$layanan = $query[0];

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
                   <!-- form edit layanan -->
                   <a href="data-layanan.php" class="inline-block px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors">
                       <i class="fa-solid fa-arrow-left"></i> Kembali
                   </a>    
                     <form action="" method="POST" class="mb-6 mt-4 p-6 bg-white p-6 rounded-2xl shadow-sm border border-pink-100 ">
                        <div class="mb-4">
                             <label for="nama_layanan" class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan</label>
                             <input type="text" name="nama_layanan" id="nama_layanan" value="<?php echo $layanan['nama_layanan']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="harga_layanan" class="block text-sm font-medium text-gray-700 mb-1">Harga Layanan</label>
                             <input type="number" name="harga_layanan" id="harga_layanan" value="<?php echo $layanan['harga_layanan']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="durasi_layanan" class="block text-sm font-medium text-gray-700 mb-1">Durasi Layanan </label>
                             <input type="number" name="durasi_layanan" id="durasi_layanan" value="<?php echo $layanan['durasi_layanan']; ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                            <input type="submit" name="submit" class="px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors" value="Ubah Layanan">
                    </form>
            </div>
            <!-- Footer Informatif -->
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>