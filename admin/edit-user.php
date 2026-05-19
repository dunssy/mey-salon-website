<?php 
$page_title = "Data User";
$sub_title = "Edit User";
include "../layout/header.php";
include "../config/app.php";
global $koneksi;

// ===============================
// AMBIL ID USER DARI URL
// contoh URL: edit-user.php?id_user=1
// ===============================
$id_user = isset($_GET['id_user']) ? (int) $_GET['id_user'] : 0;

if ($id_user <= 0) {
    echo "<script>
            alert('ID user tidak valid!');
            window.location.href = 'data-user.php';
          </script>";
    exit;
}

// ===============================
// AMBIL DATA USER BERDASARKAN ID
// ===============================
$query_user = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id_user");
$user = mysqli_fetch_assoc($query_user);

if (!$user) {
    echo "<script>
            alert('Data user tidak ditemukan!');
            window.location.href = 'data-user.php';
          </script>";
    exit;
}

// ===============================
// CEK USERNAME REALTIME AJAX
// mengabaikan username user saat ini
// ===============================
if (isset($_POST['cek_username'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $id_user_ajax = (int) $_POST['id_user'];

    $query = mysqli_query(
        $koneksi, 
        "SELECT username FROM user 
         WHERE username = '$username' 
         AND id_user != $id_user_ajax"
    );

    if (mysqli_num_rows($query) > 0) {
        echo "used";
    } else {
        echo "available";
    }

    exit;
}

// ===============================
// PROSES EDIT USER
// ===============================
if(isset($_POST['submit'])){
    if(edit_user($_POST) > 0){
        echo "<script>
                alert('User berhasil diperbarui!');
                window.location.href = 'data-user.php';
              </script>";
    } elseif(edit_user($_POST) == -1) {
        echo "<script>
                alert('Username sudah digunakan!');
                window.location.href = 'edit-user.php?id_user=$id_user';
              </script>";
    } else {
        echo "<script>
                alert('Tidak ada data yang diubah atau user gagal diperbarui!');
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

                <h3 class="text-lg font-bold mb-6"><?php echo $sub_title; ?></h3>

                <a href="data-user.php" class="inline-block px-4 py-2 text-sm font-bold text-gray-400 bg-gray-50 rounded-lg hover:bg-pink-50 hover:text-pink-600 transition-colors">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>    

                <form action="" method="POST" class="mb-6 mt-4 p-6 bg-white rounded-2xl shadow-sm border border-pink-100">

                    <input type="hidden" name="id_user" id="id_user" value="<?php echo $user['id_user']; ?>">

                    <div class="mb-4">
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama User</label>
                        <input 
                            type="text" 
                            name="nama" 
                            id="nama" 
                            value="<?php echo htmlspecialchars($user['nama']); ?>" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input 
                            type="number" 
                            name="no_hp" 
                            id="no_hp" 
                            value="<?php echo htmlspecialchars($user['no_hp']); ?>" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <input 
                            type="text" 
                            name="alamat" 
                            id="alamat" 
                            value="<?php echo htmlspecialchars($user['alamat']); ?>" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            value="<?php echo htmlspecialchars($user['username']); ?>" 
                            required 
                            autocomplete="off"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >

                        <small id="username-message" class="text-sm font-medium"></small>
                    </div>

                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select 
                            name="role" 
                            id="role" 
                            required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                            <option value="">Pilih Role</option>
                            <option value="Administrator" <?php echo ($user['role'] == 'Administrator') ? 'selected' : ''; ?>>
                                Administrator
                            </option>
                            <option value="Customer" <?php echo ($user['role'] == 'Customer') ? 'selected' : ''; ?>>
                                Customer
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            placeholder="Kosongkan jika tidak ingin mengubah password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
                        >
                    </div>

                    <input 
                        type="submit" 
                        name="submit" 
                        id="submit-button"
                        class="px-4 py-2 bg-pink-600 text-white font-bold rounded-lg hover:bg-pink-700 transition-colors" 
                        value="Edit User"
                    >

                </form>
            </div>

            <script>
            const usernameInput = document.getElementById('username');
            const usernameMessage = document.getElementById('username-message');
            const submitButton = document.querySelector('input[name="submit"]');

            let lastUsernameChecked = '';
            let alreadyAlerted = false;

            usernameInput.addEventListener('keyup', function () {
                const username = usernameInput.value.trim();

                if (username === '') {
                    usernameMessage.textContent = '';
                    usernameInput.classList.remove('border-red-500', 'border-green-500');
                    usernameInput.classList.add('border-gray-300');

                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');

                    lastUsernameChecked = '';
                    alreadyAlerted = false;
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'cek-username.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    const response = xhr.responseText.trim();

                    if (response === 'used') {
                        usernameMessage.textContent = 'Username sudah digunakan!';
                        usernameMessage.className = 'text-sm font-medium text-red-600';

                        usernameInput.classList.add('border-red-500');
                        usernameInput.classList.remove('border-gray-300', 'border-green-500');

                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');

                    } else if (response === 'available') {
                        usernameMessage.textContent = 'Username belum digunakan';
                        usernameMessage.className = 'text-sm font-medium text-green-600';

                        usernameInput.classList.add('border-green-500');
                        usernameInput.classList.remove('border-red-500', 'border-gray-300');

                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');

                        alreadyAlerted = false;
                        lastUsernameChecked = username;
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