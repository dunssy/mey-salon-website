<?php

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
        
        // PAGINATION LAYANAN
        // Fungsi untuk mengambil data layanan dengan LIMIT dan OFFSET
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
        
        
        // EDIT LAYANAN
        function edit_layanan($post){
            global $koneksi;
            $id_layanan = strip_tags($post['id_layanan']);
            $nama_layanan = strip_tags($post['nama_layanan']);
            $harga = strip_tags($post['harga_layanan']);
            $durasi = strip_tags($post['durasi_layanan']);
            
            $query = "UPDATE layanan SET nama_layanan = '$nama_layanan', harga_layanan = '$harga', durasi_layanan = '$durasi' WHERE id_layanan = '$id_layanan'";
            mysqli_query($koneksi , $query);
            
            return mysqli_affected_rows($koneksi);
        }
// END CRUD LAYANAN