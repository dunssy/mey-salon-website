<?php 
$user = 'root';
$password = '';
$database = 'db_salon';

if(!$con = mysqli_connect('localhost', $user, $password, $database)){
    die("Failed to connect to database!");
}