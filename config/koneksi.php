<?php 
$username = 'root';
$password = '';
$database = 'db_meysalon';

if(!$koneksi = mysqli_connect('localhost', $username, $password, $database)){
    die("Failed to connect to database!");
}