<?php
// Mencegah controller dipanggil lebih dari satu kali
if (defined('CONTROLLER_INCLUDED')) {
    return;
}
define('CONTROLLER_INCLUDED', true);

// ======================================================
// HELPER UMUM
// ======================================================

// Menjalankan query SELECT dan mengembalikan array data
function select($query)
{
    global $koneksi;

    $result = mysqli_query($koneksi, $query);
    $rows = [];

    if (!$result) {
        return $rows;
    }

    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}

// Membersihkan input string
function clean_input($value)
{
    global $koneksi;

    return mysqli_real_escape_string($koneksi, strip_tags(trim($value)));
}

// Mengambil angka dari input
function clean_number($value)
{
    return (int) preg_replace('/[^0-9]/', '', $value);
}


// ======================================================
// CRUD BOOKING
// ======================================================

// Menambah data booking sederhana
function tambah_booking()
{
    global $koneksi;

    if (isset($_POST['submit'])) {
        $nama_pelanggan = clean_input($_POST['nama_pelanggan']);
        $layanan = clean_input($_POST['layanan']);
        $waktu = clean_input($_POST['waktu']);

        $query = "INSERT INTO booking 
                    (nama_pelanggan, layanan, waktu) 
                  VALUES 
                    ('$nama_pelanggan', '$layanan', '$waktu')";

        mysqli_query($koneksi, $query);
    }
}



// Upload gambar layanan dan mengembalikan nama file yang disimpan
function upload_gambar_layanan($input_name = 'gambar_layanan', $gambar_lama = '')
{
    // Jika tidak ada file baru, gunakan gambar lama
    if (!isset($_FILES[$input_name]) || $_FILES[$input_name]['error'] === UPLOAD_ERR_NO_FILE) {
        return $gambar_lama;
    }

    // Menolak jika upload error
    if ($_FILES[$input_name]['error'] !== UPLOAD_ERR_OK) {
        return $gambar_lama;
    }

    $nama_file = $_FILES[$input_name]['name'];
    $tmp_file = $_FILES[$input_name]['tmp_name'];
    $ukuran_file = (int) $_FILES[$input_name]['size'];
    $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

    $ext_diizinkan = ['jpg', 'jpeg', 'png', 'webp', 'avif'];

    // Validasi format gambar
    if (!in_array($ext, $ext_diizinkan)) {
        return $gambar_lama;
    }

    // Maksimal 2MB agar gambar tidak terlalu berat
    if ($ukuran_file > 2 * 1024 * 1024) {
        return $gambar_lama;
    }

    $folder_upload = __DIR__ . '/../uploads/layanan/';

    // Membuat folder upload jika belum ada
    if (!is_dir($folder_upload)) {
        mkdir($folder_upload, 0777, true);
    }

    $nama_baru = 'layanan_' . date('YmdHis') . '_' . rand(1000, 9999) . '.' . $ext;
    $tujuan = $folder_upload . $nama_baru;

    // Simpan file baru
    if (!move_uploaded_file($tmp_file, $tujuan)) {
        return $gambar_lama;
    }

    // Hapus gambar lama jika memang tersimpan di folder upload
    if (!empty($gambar_lama)) {
        $path_lama = $folder_upload . basename($gambar_lama);
        if (is_file($path_lama)) {
            unlink($path_lama);
        }
    }

    return $nama_baru;
}


// ======================================================
// CRUD LAYANAN
// ======================================================

// Menambah data layanan
function tambah_layanan($post)
{
    global $koneksi;

    // Mengambil input layanan dari form admin
    $nama_layanan = clean_input($post['nama_layanan'] ?? '');
    $harga_min = clean_number($post['harga_min'] ?? ($post['harga_layanan'] ?? 0));
    $harga_max = clean_number($post['harga_max'] ?? $harga_min);
    $durasi = clean_number($post['durasi_layanan'] ?? 0);
    $keterangan_harga = clean_input($post['keterangan_harga'] ?? '');

    // Harga maksimal tidak boleh lebih kecil dari harga minimal
    if ($harga_max < $harga_min) {
        $harga_max = $harga_min;
    }

    // Upload gambar layanan jika ada
    $gambar_layanan = upload_gambar_layanan('gambar_layanan');

    // Menyimpan harga_min dan harga_max agar range harga customer terbaca
    $query = "INSERT INTO layanan 
                (nama_layanan, harga_min, harga_max, durasi_layanan, keterangan_harga, gambar_layanan) 
              VALUES 
                ('$nama_layanan', '$harga_min', '$harga_max', '$durasi', '$keterangan_harga', '$gambar_layanan')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Mengubah data layanan
function edit_layanan($post)
{
    global $koneksi;

    // Mengambil input layanan dari form edit admin
    $id_layanan = (int) ($post['id_layanan'] ?? 0);
    $nama_layanan = clean_input($post['nama_layanan'] ?? '');
    $harga_min = clean_number($post['harga_min'] ?? ($post['harga_layanan'] ?? 0));
    $harga_max = clean_number($post['harga_max'] ?? $harga_min);
    $durasi = clean_number($post['durasi_layanan'] ?? 0);
    $keterangan_harga = clean_input($post['keterangan_harga'] ?? '');

    // Harga maksimal tidak boleh lebih kecil dari harga minimal
    if ($harga_max < $harga_min) {
        $harga_max = $harga_min;
    }

    // Mengambil gambar lama supaya tidak hilang saat tidak upload gambar baru
    $gambar_lama = '';
    $query_lama = mysqli_query($koneksi, "SELECT gambar_layanan FROM layanan WHERE id_layanan = $id_layanan LIMIT 1");
    if ($query_lama && mysqli_num_rows($query_lama) > 0) {
        $data_lama = mysqli_fetch_assoc($query_lama);
        $gambar_lama = $data_lama['gambar_layanan'] ?? '';
    }

    // Upload gambar baru jika ada, jika tidak gunakan gambar lama
    $gambar_layanan = upload_gambar_layanan('gambar_layanan', $gambar_lama);

    // Menyimpan perubahan lengkap sesuai DB final
    $query = "UPDATE layanan SET 
                nama_layanan = '$nama_layanan',
                harga_min = '$harga_min',
                harga_max = '$harga_max',
                durasi_layanan = '$durasi',
                keterangan_harga = '$keterangan_harga',
                gambar_layanan = '$gambar_layanan'
              WHERE id_layanan = $id_layanan";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Menampilkan data layanan per halaman
function tampil_layanan_per_halaman($halaman_aktif, $jumlah_per_halaman)
{
    global $koneksi;

    $halaman_aktif = (int) $halaman_aktif;
    $jumlah_per_halaman = (int) $jumlah_per_halaman;
    $offset = ($halaman_aktif - 1) * $jumlah_per_halaman;

    $query = "SELECT * FROM layanan 
              ORDER BY id_layanan DESC 
              LIMIT $offset, $jumlah_per_halaman";

    return select($query);
}

// Menghitung total halaman layanan
function hitung_total_halaman_layanan($jumlah_per_halaman)
{
    global $koneksi;

    $jumlah_per_halaman = (int) $jumlah_per_halaman;
    $query = "SELECT COUNT(*) AS total FROM layanan";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return ceil($data['total'] / $jumlah_per_halaman);
}

// Mencari Layanan Berdasrkan Keyword dengan nama_layanan, harga_min, atau durasi_layanan 
function cari_layanan($keyword){
   global $koneksi;

   $keyword = mysqli_real_escape_string($koneksi, $keyword);
   $cari = mysqli_query($koneksi, "SELECT * FROM layanan WHERE nama_layanan LIKE '%$keyword%' OR harga_min LIKE '%$keyword%' OR durasi_layanan LIKE '%$keyword%' ORDER BY id_layanan DESC");
   
   $hasil = mysqli_fetch_all($cari, MYSQLI_ASSOC);
   return $hasil;
}

// ======================================================
// CRUD USER
// ======================================================

// Menambah data user
function tambah_user($post)
{
    global $koneksi;

    $nama = clean_input($post['nama']);
    $no_hp = clean_input($post['no_hp']);
    $alamat = clean_input($post['alamat']);
    $email = clean_input($post['email']);
    $role = clean_input($post['role']);
    $password = clean_input($post['password']);

    $query = "INSERT INTO user 
                (nama, no_hp, alamat, email, role, password) 
              VALUES 
                ('$nama', '$no_hp', '$alamat', '$email', '$role', '$password')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Mengubah data user
function edit_user($post)
{
    global $koneksi;

    $id_user = (int) $post['id_user'];
    $nama = clean_input($post['nama']);
    $no_hp = clean_input($post['no_hp']);
    $alamat = clean_input($post['alamat']);
    $email = clean_input($post['email']);
    $role = clean_input($post['role']);
    $password = clean_input($post['password']);

    if ($password == '') {
        $query = "UPDATE user SET 
                    nama = '$nama',
                    no_hp = '$no_hp',
                    alamat = '$alamat',
                    email = '$email',
                    role = '$role'
                  WHERE id_user = $id_user";
    } else {
        $query = "UPDATE user SET 
                    nama = '$nama',
                    no_hp = '$no_hp',
                    alamat = '$alamat',
                    email = '$email',
                    role = '$role',
                    password = '$password'
                  WHERE id_user = $id_user";
    }

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Menampilkan data user per halaman
function tampil_user_per_halaman($halaman_aktif, $jumlah_per_halaman)
{
    $halaman_aktif = (int) $halaman_aktif;
    $jumlah_per_halaman = (int) $jumlah_per_halaman;
    $offset = ($halaman_aktif - 1) * $jumlah_per_halaman;

    $query = "SELECT * FROM user 
              ORDER BY id_user DESC 
              LIMIT $offset, $jumlah_per_halaman";

    return select($query);
}

// Menghitung total halaman user
function hitung_total_halaman_user($jumlah_per_halaman)
{
    global $koneksi;

    $jumlah_per_halaman = (int) $jumlah_per_halaman;
    $query = "SELECT COUNT(*) AS total FROM user";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return ceil($data['total'] / $jumlah_per_halaman);
}

// Fungsi pencarian user berdasarkan nama, email, atau no_hp
function cari_user($keyword){
   global $koneksi;

   $keyword = mysqli_real_escape_string($koneksi, $keyword);
   $cari = mysqli_query($koneksi, "SELECT * FROM user WHERE nama LIKE '%$keyword%' OR email LIKE '%$keyword%' OR no_hp LIKE '%$keyword%' ORDER BY id_user DESC");
   
   $hasil = mysqli_fetch_all($cari, MYSQLI_ASSOC);
   return $hasil;
}


// ======================================================
// CRUD STOK BARANG
// ======================================================
// Menambah data barang
function tambah_barang($post)
{
    global $koneksi;

    $nama_barang = clean_input($post['nama_barang']);
    $jenis_barang = clean_input($post['jenis_barang']);
    $jumlah_barang_botol = clean_number($post['jumlah_barang_botol']);
    $jumlah_barang_perbotol = clean_number($post['jumlah_barang_perbotol']);
    $jumlah_barang = $jumlah_barang_botol * $jumlah_barang_perbotol;
    $satuan_barang = clean_input($post['satuan_barang']);
    $minimal_stok_awal = clean_number($post['minimal_stok_awal']);
    $minimal_stok = $minimal_stok_awal * $jumlah_barang_perbotol;
    $harga_beli = clean_number($post['harga_beli']);
    $total_harga_restok = $jumlah_barang_botol * $harga_beli;

    $query = "INSERT INTO stok_barang 
                (nama_barang, jenis_barang, jumlah_barang, satuan_barang, jumlah_satuan, minimal_stok, harga_beli) 
              VALUES 
                ('$nama_barang', '$jenis_barang', '$jumlah_barang', '$satuan_barang', '$jumlah_barang_perbotol', '$minimal_stok', '$harga_beli')";

    mysqli_query($koneksi, $query);
    $id_barang = mysqli_insert_id($koneksi);

    // Menyimpan data ke tabel restok untuk laporan pengeluaran
    $query_restok = "INSERT INTO restok
                (id_barang, jumlah_tambah, harga_restok, total_harga_restok)
              VALUES 
                ('$id_barang', '$jumlah_barang_botol', '$harga_beli', '$total_harga_restok')";
    
    mysqli_query($koneksi, $query_restok);

    return mysqli_affected_rows($koneksi);
}


// Mengubah data barang
function edit_barang($post)
{
    global $koneksi;

    $id_barang = (int) $post['id_barang'];
    $nama_barang = clean_input($post['nama_barang']);
    $jenis_barang = clean_input($post['jenis_barang']);
    $satuan_barang = clean_input($post['satuan_barang']);
    $jumlah_barang_perbotol = clean_number($post['jumlah_barang_perbotol']);
    $minimal_stok = clean_number($post['minimal_stok']);
    $harga_beli = clean_number($post['harga_beli']);

    $query = "UPDATE stok_barang SET 
                nama_barang = '$nama_barang',
                jenis_barang = '$jenis_barang',
                satuan_barang = '$satuan_barang',
                jumlah_satuan = '$jumlah_barang_perbotol',
                minimal_stok = '$minimal_stok',
                harga_beli = '$harga_beli'
              WHERE id_barang = $id_barang";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Menghapus data barang
function hapus_barang($id_barang)
{
    global $koneksi;

    $id_barang = (int) $id_barang;

    mysqli_query($koneksi, "DELETE FROM stok_barang WHERE id_barang = $id_barang");

    return mysqli_affected_rows($koneksi);
}

// Menampilkan data barang per halaman
function tampil_barang_per_halaman($halaman_aktif, $jumlah_per_halaman)
{
    $halaman_aktif = (int) $halaman_aktif;
    $jumlah_per_halaman = (int) $jumlah_per_halaman;
    $offset = ($halaman_aktif - 1) * $jumlah_per_halaman;

    $query = "SELECT * FROM stok_barang 
              ORDER BY id_barang DESC 
              LIMIT $offset, $jumlah_per_halaman";

    return select($query);
}

// Menghitung total halaman barang
function hitung_total_halaman_barang($jumlah_per_halaman)
{
    global $koneksi;

    $jumlah_per_halaman = (int) $jumlah_per_halaman;
    $query = "SELECT COUNT(*) AS total FROM stok_barang";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    return ceil($data['total'] / $jumlah_per_halaman);
}

// Fungsi pencarian barang berdasarkan nama
function cari_barang($keyword){
   global $koneksi;

   $keyword = mysqli_real_escape_string($koneksi, $keyword);
   $cari = mysqli_query($koneksi, "SELECT * FROM stok_barang WHERE nama_barang LIKE '%$keyword%' OR jenis_barang LIKE '%$keyword%' ORDER BY id_barang DESC");
   
   $hasil = mysqli_fetch_all($cari, MYSQLI_ASSOC);
   return $hasil;
}

function tambah_pengeluaran($data) {
    global $koneksi; 

    // Memaksa id_user menjadi angka murni (jika kosong otomatis jadi 0 atau mencegah error FK)
    $id_user                = (int) $data['id_user']; 
    $jenis_pengeluaran      = clean_input($data['jenis_pengeluaran']);
    $jumlah_pengeluaran     = clean_number($data['jumlah_pengeluaran']); 
    $tanggal_pengeluaran    = clean_input($data['tanggal_pengeluaran']);
    $keterangan_pengeluaran = clean_input($data['keterangan_pengeluaran']);

    // Pastikan query-nya seperti ini
    $query = "INSERT INTO pengeluaran 
                (id_user, jenis_pengeluaran, jumlah_pengeluaran, tanggal_pengeluaran, keterangan_pengeluaran) 
              VALUES 
                ($id_user, '$jenis_pengeluaran', '$jumlah_pengeluaran', '$tanggal_pengeluaran', '$keterangan_pengeluaran')";
                // Catatan: $id_user tidak perlu dibungkus tanda petik tunggal jika tipenya INT

    $hasil = mysqli_query($koneksi, $query);

    // BANTUAN DEBUG: Jika gagal, ini akan memunculkan error asli dari MySQL/Foreign Key di layar browser kamu
    if (!$hasil) {
        die("Gagal menyimpan ke database! Error MySQL: " . mysqli_error($koneksi));
    }

    return mysqli_affected_rows($koneksi);
}
// Menambah stok barang tambahan
function tambah_stok($post)
{
    return tambah_barang($post);
}
?>