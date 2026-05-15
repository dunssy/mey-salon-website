<?php
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
function tambah_layanan(){
    global $koneksi;

    if(isset($_POST['submit'])){
        $nama_layanan = $_POST['nama_layanan'];
        $harga = $_POST['harga'];
        $deskripsi = $_POST['deskripsi'];

        $query = "INSERT INTO layanan (nama_layanan, harga, deskripsi) VALUES ('$nama_layanan', '$harga', '$deskripsi')";
        mysqli_query($koneksi , $query);
    }
}


// PRORSES CRUD PELANGGAN