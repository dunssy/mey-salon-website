<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "mey_salon";
// UNTUK MENYAMBUNGKAN KE DATABASE
$koneksi = mysqli_connect($host, $user, $pass, $db);

// UNTUK MENGECEK KONEKSI
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

?>