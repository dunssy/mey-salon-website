<?php 
require 'db.php';

// memangil fungsi query dari file db.php
// digunakan secara global agar bisa digunakan di semua file yang membutuhkan
function query($query){
    global $con;
    $result = mysqli_query($con, $query);
    $rows = [];
    while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
    }
    return $rows;
}

function tambah_user($data){
    
    global $con;
    $nama = htmlspecialchars($data['nama']);
    $username = htmlspecialchars($data['username']);
    $email = htmlspecialchars($data['email']);
    $no_hp = htmlspecialchars($data['no_hp']);
    $level = htmlspecialchars($data['level']);
    $password = htmlspecialchars($data['password']);
    $query = "INSERT INTO karyawan (nama, username, email, posisi, no_hp, password) VALUES ('$nama', '$username', '$email',  '$level','$no_hp', '$password')";
    mysqli_query($con, $query);
    // cek apakah data berhasil ditambahkan atau tidak
    if (mysqli_affected_rows($con) > 0) {
        return true;
    } else {
        return false;
    }
}

function edit_user($data){
    global $con;
    $id = $data['id'];
    $nama = htmlspecialchars($data['nama']);
    $username = htmlspecialchars($data['username']);
    $email = htmlspecialchars($data['email']);
    $no_hp = htmlspecialchars($data['no_hp']);
    $level = htmlspecialchars($data['level']);
    $password = htmlspecialchars($data['password']);
    $query = "UPDATE user SET nama='$nama', username='$username', email='$email', no_hp='$no_hp', level='$level', password='$password' WHERE id='$id'";
    mysqli_query($con, $query);
    // cek apakah data berhasil diubah atau tidak
    if (mysqli_affected_rows($con) > 0) {
        return true;
    } else {
        return false;
    }
}

function hapus_user($id){
    global $con;
    mysqli_query($con, "DELETE FROM user WHERE id='$id'");
    // cek apakah data berhasil dihapus atau tidak
    if (mysqli_affected_rows($con) > 0) {
        return true;
    } else {
        return false;
    }
}