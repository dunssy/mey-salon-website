<?php 
$page_title = "Data User";
$sub_title = "Tambah User";
include "../layout/header.php";
include "../config/app.php";

// memanggil fungsi tambah_user dari controller.php untuk menambahkan user baru ke database
if(isset($_POST['submit'])){
    // memanggil fungsi tambah_user dari controller.php untuk menambahkan user baru ke database
    if(tambah_user($_POST) > 0){
        echo "<script>
                alert('user berhasil ditambahkan!');
                window.location.href = 'data-user.php';
              </script>";
    } else {
        echo "<script>
                alert('user gagal ditambahkan!');
                window.location.href = 'data-user.php';
              </script>";
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
                <!-- Section Service -->
                    <!-- SUB JUDUL -->
                    <h3 class="text-lg font-bold mb-6"><?php echo $sub_title; ?></h3>
                   <!-- form tambah user -->
                   <a href="data-user.php" class="inline-block px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors">
                       <i class="fa-solid fa-arrow-left"></i> Kembali
                   </a>    
                     <form action="" method="POST" class="mb-6 mt-4 p-6 bg-white p-6 rounded-2xl shadow-sm border border-pink-100 ">
                        <div class="mb-4">
                             <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama user</label>
                             <input type="text" name="nama" id="nama" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                             <input type="number" name="no_hp" id="no_hp" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                             <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                             <input type="text" name="alamat" id="alamat" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input 
                                type="text" 
                                name="username" 
                                id="username" 
                                required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                            >

                            <small id="username-message" class="text-sm font-medium"></small>
                        </div>
                        <div class="mb-4">
                             <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                             <select name="role" id="role" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                                 <option value="">Pilih Role</option>
                                 <option value="Administrator">Administrator</option>
                                 <option value="Customer">Customer</option>
                             </select>
                        </div>
                        <div class="mb-4">
                             <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                             <input type="password" name="password" id="password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200">
                        </div>
                            <input type="submit" name="submit" class="px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors" value="Tambah user">
                    </form>
            </div>
            <script>
            const usernameInput = document.getElementById('username');
            const usernameMessage = document.getElementById('username-message');
            const submitButton = document.querySelector('input[name="submit"]');

            usernameInput.addEventListener('keyup', function () {
                const username = usernameInput.value.trim();

                if (username.length === 0) {
                    usernameMessage.textContent = '';
                    submitButton.disabled = false;
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'cek-username.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    if (this.responseText.trim() === 'used') {
                        usernameMessage.textContent = 'Username sudah digunakan!';
                        usernameMessage.className = 'text-sm font-medium text-red-600';

                        usernameInput.classList.add('border-red-500');
                        usernameInput.classList.remove('border-gray-300');

                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        usernameMessage.textContent = 'Username tersedia';
                        usernameMessage.className = 'text-sm font-medium text-green-600';

                        usernameInput.classList.remove('border-red-500');
                        usernameInput.classList.add('border-gray-300');

                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                };

                xhr.send('username=' + encodeURIComponent(username));
            });
            </script>
            <!-- Footer Informatif -->
                <?php include "../layout/footer-component.php"; ?>
        </main>
    </div>

<?php
include "../layout/footer.php";
?>