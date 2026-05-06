<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "mey_salon";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

date_default_timezone_set("Asia/Jakarta");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>