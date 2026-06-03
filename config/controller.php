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


// ======================================================
// CRUD LAYANAN
// ======================================================

// Menambah data layanan
function tambah_layanan($post)
{
    global $koneksi;

    $nama_layanan = clean_input($post['nama_layanan']);
    $harga = clean_number($post['harga_layanan']);
    $durasi = clean_number($post['durasi_layanan']);

    $query = "INSERT INTO layanan 
                (nama_layanan, harga_min, durasi_layanan) 
              VALUES 
                ('$nama_layanan', '$harga', '$durasi')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Mengubah data layanan
function edit_layanan($post)
{
    global $koneksi;

    $id_layanan = (int) $post['id_layanan'];
    $nama_layanan = clean_input($post['nama_layanan']);
    $harga = clean_number($post['harga_layanan']);
    $durasi = clean_number($post['durasi_layanan']);

    $query = "UPDATE layanan SET 
                nama_layanan = '$nama_layanan',
                harga_min = '$harga',
                durasi_layanan = '$durasi'
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

   $cari = mysqli_query($koneksi,"SELECT * FROM layanan WHERE nama_layanan 
   LIKE '%$keyword%' OR harga_min 
   LIKE '%$keyword%' OR durasi_layanan 
   LIKE '%$keyword%' ORDER BY id_layanan DESC");
    
   $hasil = mysqli_fetch_all($cari, MYSQLI_ASSOC);
    mysqli_close($koneksi);
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

    $query = "INSERT INTO stok_barang 
                (nama_barang, jenis_barang, jumlah_barang, satuan_barang, jumlah_satuan, minimal_stok, harga_beli) 
              VALUES 
                ('$nama_barang', '$jenis_barang', '$jumlah_barang', '$satuan_barang', '$jumlah_barang_perbotol', '$minimal_stok', '$harga_beli')";

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// Mengubah data barang
function edit_barang($post)
{
    global $koneksi;

    $id_barang = (int) $post['id_barang'];
    $nama_barang = clean_input($post['nama_barang']);
    $jenis_barang = clean_input($post['jenis_barang']);
    $jumlah_barang = clean_number($post['jumlah_barang']);
    $satuan_barang = clean_input($post['satuan_barang']);
    $jumlah_barang_perbotol = clean_number($post['jumlah_barang_perbotol']);
    $minimal_stok = clean_number($post['minimal_stok']);
    $harga_beli = clean_number($post['harga_beli']);

    $query = "UPDATE stok_barang SET 
                nama_barang = '$nama_barang',
                jenis_barang = '$jenis_barang',
                jumlah_barang = '$jumlah_barang',
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