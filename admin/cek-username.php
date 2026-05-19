<?php
include "../config/app.php";
global $koneksi;

if (isset($_POST['username'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);

    $query = mysqli_query($koneksi, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_num_rows($query) > 0) {
        echo "used";
    } else {
        echo "available";
    }
}
?>