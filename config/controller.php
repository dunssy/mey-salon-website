<?php

if (defined('CONTROLLER_INCLUDED')) {
    return;
}
define('CONTROLLER_INCLUDED', true);

// FUNGSI TAMPILKAN SEMUA DATABASE
function select($query){
    global $koneksi;
    $result = mysqli_query($koneksi, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }

    return $rows;
}

// PROSES CRUD BOOKING
function tambah_booking(){
    global $koneksi;
    if(isset($_POST['submit'])){
        $nama_pelanggan = $_POST['nama_pelanggan'];
        $layanan = $_POST['layanan'];
        $waktu = $_POST['waktu'];

        $query = "INSERT INTO booking (nama_pelanggan, layanan, waktu) VALUES ('$nama_pelanggan', '$layanan', '$waktu')";
        mysqli_query($koneksi , $query);
    }
}

// PROSES CRUD LAYANAN
function tambah_layanan($post){
    global $koneksi;
    $nama_layanan = strip_tags($post['nama_layanan']);
    $harga = strip_tags($post['harga_layanan']);
    $durasi = strip_tags($post['durasi_layanan']);
    $query = "INSERT INTO layanan (id_layanan, nama_layanan, harga_layanan, durasi_layanan) VALUES ('', '$nama_layanan', '$harga', '$durasi')";
    mysqli_query($koneksi , $query);
    return mysqli_affected_rows($koneksi);
}

function edit_layanan($post){ 
    global $koneksi;
    $id_layanan = (int) $post['id_layanan'];
    $nama_layanan = strip_tags($post['nama_layanan']);
    $harga = strip_tags($post['harga_layanan']);
    $durasi = strip_tags($post['durasi_layanan']);
    $nama_layanan = mysqli_real_escape_string($koneksi, $nama_layanan);
    $harga = mysqli_real_escape_string($koneksi, $harga);
    $durasi = mysqli_real_escape_string($koneksi, $durasi);

    $query = "UPDATE layanan SET nama_layanan = '$nama_layanan', harga_layanan = '$harga', durasi_layanan = '$durasi' WHERE id_layanan = $id_layanan";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

function tampil_layanan_per_halaman($halaman_aktif, $jumlah_per_halaman) {
    global $koneksi; // sesuaikan dengan nama variabel koneksi database Anda
    
    // Hitung offset data
    $offset = ($halaman_aktif - 1) * $jumlah_per_halaman;
    
    $query = "SELECT * FROM layanan ORDER BY id_layanan DESC LIMIT $offset, $jumlah_per_halaman";
    $result = mysqli_query($koneksi, $query);
    
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menghitung total halaman layanan
function hitung_total_halaman_layanan($jumlah_per_halaman) {
    global $koneksi;
    
    $query = "SELECT COUNT(*) AS total FROM layanan";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    $total_data = $data['total'];
    
    // Bulatkan ke atas hasil pembagiannya
    return ceil($total_data / $jumlah_per_halaman);
}

//PROSES CRUD USER
function tambah_user($post){
    global $koneksi;
    $nama = strip_tags($post['nama']);
    $no_hp = strip_tags($post['no_hp']);
    $alamat = strip_tags($post['alamat']);
    $username = strip_tags($post['username']);
    $role = strip_tags($post['role']);
    $password = strip_tags($post['password']);

    $query = "INSERT INTO user (id_user, nama, no_hp, alamat, username, role, password) VALUES ('', '$nama', '$no_hp', '$alamat', '$username', '$role', '$password')";
    mysqli_query($koneksi , $query);
    
    return mysqli_affected_rows($koneksi);
}

// Fungsi untuk mengedit user berdasarkan id_user
function edit_user($post){ 
    global $koneksi;
    $id_user = (int) $post['id_user'];
    $nama = strip_tags($post['nama']);
    $no_hp = strip_tags($post['no_hp']);
    $alamat = strip_tags($post['alamat']);
    $username = strip_tags($post['username']);
    $role = strip_tags($post['role']);
    $password = strip_tags($post['password']);

    $nama = mysqli_real_escape_string($koneksi, $nama);
    $no_hp = mysqli_real_escape_string($koneksi, $no_hp);
    $alamat = mysqli_real_escape_string($koneksi, $alamat);
    $username = mysqli_real_escape_string($koneksi, $username);
    $role = mysqli_real_escape_string($koneksi, $role);
    $password = mysqli_real_escape_string($koneksi, $password);

    // Cek username, tapi abaikan user yang sedang diedit
    $cek_username = mysqli_query(
        $koneksi, 
        "SELECT username FROM user 
         WHERE username = '$username' 
         AND id_user != $id_user"
    );

    if (mysqli_num_rows($cek_username) > 0) {
        return -1;
    }

    // Jika password kosong, password lama tidak diubah
    if ($password == '') {
        $query = "UPDATE user SET nama = '$nama',no_hp = '$no_hp',alamat = '$alamat',username = '$username',role = '$role' WHERE id_user = $id_user";
    } else {
        $query = "UPDATE user SET nama = '$nama',no_hp = '$no_hp', alamat = '$alamat',username = '$username',role = '$role',password = '$password' WHERE id_user = $id_user";
    }

    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}   

// Fungsi untuk mengambil data user dengan LIMIT dan OFFSET
function tampil_user_per_halaman($halaman_aktif, $jumlah_per_halaman) {
    global $koneksi; // sesuaikan dengan nama variabel koneksi database Anda
    
    // Hitung offset data
    $offset = ($halaman_aktif - 1) * $jumlah_per_halaman;
    
    $query = "SELECT * FROM user ORDER BY id_user DESC LIMIT $offset, $jumlah_per_halaman";
    $result = mysqli_query($koneksi, $query);
    
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menghitung total halaman user
function hitung_total_halaman_user($jumlah_per_halaman) {
    global $koneksi;
    
    $query = "SELECT COUNT(*) AS total FROM user";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    $total_data = $data['total'];
    
    // Bulatkan ke atas hasil pembagiannya
    return ceil($total_data / $jumlah_per_halaman);
}

// PRORSES CRUD DATA BARANG
function tambah_barang($post){
    global $koneksi;
    $nama_barang = strip_tags($post['nama_barang']);
    $jenis_barang = strip_tags($post['jenis_barang']);
    $jumlah_barang = strip_tags($post['jumlah_barang']);
    $satuan_barang = strip_tags($post['satuan_barang']);
    $minimal_stok = strip_tags($post['minimal_stok']);
    $harga_beli = strip_tags($post['harga_beli']);

    $query = "INSERT INTO stok_barang (id_barang, nama_barang, jenis_barang, jumlah_barang, satuan_barang, minimal_stok, harga_beli) VALUES ('', '$nama_barang', '$jenis_barang', '$jumlah_barang', '$satuan_barang', '$minimal_stok', '$harga_beli')";
    mysqli_query($koneksi , $query);
    
    return mysqli_affected_rows($koneksi);
}

function edit_barang($post){ 
    global $koneksi;

    $id_barang = (int)$post['id_barang'];
    $nama_barang = strip_tags($post['nama_barang']);
    $jenis_barang = strip_tags($post['jenis_barang']);
    $jumlah_barang = strip_tags($post['jumlah_barang']);
    $satuan_barang = strip_tags($post['satuan_barang']);
    $minimal_stok = strip_tags($post['minimal_stok']);
    $harga_beli = strip_tags($post['harga_beli']);

    $nama_barang = mysqli_real_escape_string($koneksi, $nama_barang);
    $jenis_barang = mysqli_real_escape_string($koneksi, $jenis_barang);
    $satuan_barang = mysqli_real_escape_string($koneksi, $satuan_barang);

    $query = "UPDATE stok_barang SET nama_barang = '$nama_barang', jenis_barang = '$jenis_barang', jumlah_barang = '$jumlah_barang', satuan_barang = '$satuan_barang', minimal_stok = '$minimal_stok', harga_beli = '$harga_beli' WHERE id_barang = $id_barang";
    mysqli_query($koneksi, $query);

    return mysqli_affected_rows($koneksi);
}

// PAGINATION BARANG
function tampil_barang_per_halaman($halaman_aktif, $jumlah_per_halaman) {
    global $koneksi; // sesuaikan dengan nama variabel koneksi database Anda
    
    // Hitung offset data
    $offset = ($halaman_aktif - 1) * $jumlah_per_halaman;
    
    $query = "SELECT * FROM stok_barang ORDER BY id_barang DESC LIMIT $offset, $jumlah_per_halaman";
    $result = mysqli_query($koneksi, $query);
    
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk menghitung total halaman barang
function hitung_total_halaman_barang($jumlah_per_halaman) {
    global $koneksi;
    
    $query = "SELECT COUNT(*) AS total FROM stok_barang";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);
    $total_data = $data['total'];
    
    // Bulatkan ke atas hasil pembagiannya
    return ceil($total_data / $jumlah_per_halaman);
}
