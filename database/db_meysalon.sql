-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Bulan Mei 2026 pada 03.36
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_mey_salon`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status_booking` enum('Waiting','On-going','Pending','Cancel','Done') NOT NULL,
  `catatan` text DEFAULT NULL,
  `tanggal_saran` date DEFAULT NULL,
  `jam_saran` time DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id_detail_booking` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama_layanan` varchar(50) NOT NULL,
  `harga_layanan` int(11) NOT NULL,
  `harga_min` int(11) DEFAULT NULL,
  `harga_max` int(11) DEFAULT NULL,
  `keterangan_harga` varchar(100) DEFAULT NULL,
  `durasi_layanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `harga_layanan`, `harga_min`, `harga_max`, `keterangan_harga`, `durasi_layanan`) VALUES
(30, 'Gunting Rambut', 25000, NULL, NULL, NULL, 30),
(31, 'Cuci Gunting Blow', 40000, NULL, NULL, NULL, 45),
(32, 'Cuci Gunting Catok', 50000, NULL, NULL, NULL, 60),
(33, 'Cuci Catok Pendek', 40000, NULL, NULL, NULL, 45),
(34, 'Cuci Catok Panjang', 45000, NULL, NULL, NULL, 60),
(35, 'Cuci Blow Pendek', 30000, NULL, NULL, NULL, 45),
(36, 'Cuci Blow Panjang', 35000, NULL, NULL, NULL, 60),
(37, 'Cuci Curly', 50000, NULL, NULL, NULL, 60),
(38, 'Creambath CBD Blow', 60000, NULL, NULL, NULL, 75),
(39, 'Hair Mask Keratin CBD Catok', 80000, NULL, NULL, NULL, 90),
(40, 'Highlight', 100000, 100000, 300000, 'Tergantung panjang dan ketebalan rambut', 120),
(41, 'Bleaching Full', 200000, 200000, 400000, 'Tergantung panjang dan ketebalan rambut', 180),
(42, 'Cat Warna / Coloring', 200000, 200000, 500000, 'Tergantung warna, panjang, dan bahan', 180),
(43, 'Cat Hitam / Toning', 150000, 150000, 400000, 'Tergantung panjang rambut', 150),
(44, 'Rebonding Makarizo', 200000, 200000, 500000, 'Tergantung panjang rambut', 180),
(45, 'Smoothing Keratin / Matrix', 250000, 250000, 600000, 'Tergantung panjang rambut dan produk', 180),
(46, 'Extension', 10000, 10000, NULL, 'Harga per helai', 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `paket_stok`
--

CREATE TABLE `paket_stok` (
  `id_paket` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `paket_stok`
--

INSERT INTO `paket_stok` (`id_paket`, `id_layanan`, `id_barang`, `jumlah_stok`) VALUES
(9, 30, 27, 5),
(10, 31, 9, 20),
(11, 31, 10, 10),
(12, 31, 11, 5),
(13, 32, 9, 20),
(14, 32, 10, 10),
(15, 32, 11, 5),
(16, 33, 9, 20),
(17, 33, 11, 5),
(18, 34, 9, 30),
(19, 34, 11, 8),
(20, 35, 9, 20),
(21, 35, 11, 5),
(22, 36, 9, 30),
(23, 36, 11, 8),
(24, 37, 9, 25),
(25, 37, 11, 8),
(26, 38, 9, 25),
(27, 38, 19, 50),
(28, 38, 11, 5),
(29, 39, 9, 25),
(30, 39, 20, 50),
(31, 39, 21, 10),
(32, 40, 13, 40),
(33, 40, 16, 40),
(34, 40, 29, 100),
(35, 41, 18, 80),
(36, 41, 17, 120),
(37, 42, 13, 80),
(38, 42, 16, 80),
(39, 43, 13, 70),
(40, 43, 15, 70),
(41, 44, 22, 80),
(42, 44, 23, 80),
(43, 45, 24, 80),
(44, 45, 25, 80),
(45, 45, 21, 20),
(46, 46, 26, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemakaian_stok`
--

CREATE TABLE `pemakaian_stok` (
  `id_pemakaian` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `jumlah_pemakaian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id_pengeluaran` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jenis_pengeluaran` varchar(50) NOT NULL,
  `jumlah_pengeluaran` int(11) NOT NULL,
  `tanggal_pengeluaran` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan_pengeluaran` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `restok`
--

CREATE TABLE `restok` (
  `id_restok` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `tanggal_restok` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jumlah_tambah` int(11) NOT NULL,
  `total_harga_restok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jenis_barang` varchar(50) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `satuan_barang` varchar(10) DEFAULT NULL,
  `minimal_stok` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `stok_barang`
--

INSERT INTO `stok_barang` (`id_barang`, `nama_barang`, `jenis_barang`, `jumlah_barang`, `satuan_barang`, `minimal_stok`, `harga_beli`) VALUES
(9, 'Shampoo Salon', 'Bahan Cuci Rambut', 5000, 'ml', 500, 35000),
(10, 'Conditioner Salon', 'Bahan Cuci Rambut', 3000, 'ml', 300, 35000),
(11, 'Hair Serum Collagen', 'Vitamin Rambut', 1200, 'ml', 150, 45000),
(12, 'Hair Repair Serum Vanestrix', 'Vitamin Rambut', 600, 'ml', 100, 40000),
(13, 'CBD ColorMax Color Cream', 'Cat Rambut', 1200, 'ml', 200, 55000),
(14, 'CBD Color Cream Jar', 'Cat Rambut', 2000, 'gram', 300, 65000),
(15, 'Developer 6%', 'Developer', 2000, 'ml', 300, 45000),
(16, 'Developer 9%', 'Developer', 2000, 'ml', 300, 45000),
(17, 'Developer 12%', 'Developer', 2000, 'ml', 300, 45000),
(18, 'Bleaching Powder', 'Bleaching', 1000, 'gram', 200, 60000),
(19, 'Creambath CBD', 'Creambath', 2500, 'gram', 300, 55000),
(20, 'Hair Mask Keratin', 'Hair Mask', 2000, 'gram', 300, 65000),
(21, 'Keratin Treatment', 'Treatment', 2000, 'ml', 300, 80000),
(22, 'Rebonding Cream Makarizo', 'Rebonding', 2000, 'gram', 300, 85000),
(23, 'Neutralizer Rebonding', 'Rebonding', 2000, 'ml', 300, 60000),
(24, 'Smoothing Cream Matrix', 'Smoothing', 2000, 'gram', 300, 95000),
(25, 'Neutralizer Smoothing', 'Smoothing', 2000, 'ml', 300, 65000),
(26, 'Hair Extension Helai', 'Extension', 100, 'helai', 20, 5000),
(27, 'Tissue Salon', 'Perlengkapan', 100, 'lembar', 20, 15000),
(28, 'Sarung Tangan', 'Perlengkapan', 100, 'pcs', 20, 20000),
(29, 'Aluminium Foil', 'Perlengkapan Coloring', 1000, 'cm', 200, 25000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_bayar` int(11) NOT NULL,
  `jenis_pelanggan` enum('booking','datang') NOT NULL,
  `tambahan_harga` int(11) NOT NULL DEFAULT 0,
  `catatan_tambahan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id_transaksi_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `jumlah_layanan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `no_hp`, `alamat`, `email`, `role`, `password`) VALUES
(1, 'Mas Diman', '0895358711991', 'Subang', 'diman@gmail.com', 'Administrator', 'diman123'),
(5, 'Laely Fauziah Az', '082295450340', 'Pagaden', 'f02290511@gmail.com', 'Customer', '$2y$10$MEai8/4xW3jy3sZNoLB./eFxcLjjhukMexRXuLYynBCRyTJJUe7q.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`id_detail_booking`),
  ADD KEY `id_booking` (`id_booking`,`id_layanan`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indeks untuk tabel `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indeks untuk tabel `paket_stok`
--
ALTER TABLE `paket_stok`
  ADD PRIMARY KEY (`id_paket`),
  ADD UNIQUE KEY `id_layanan` (`id_layanan`,`id_barang`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indeks untuk tabel `pemakaian_stok`
--
ALTER TABLE `pemakaian_stok`
  ADD PRIMARY KEY (`id_pemakaian`),
  ADD KEY `id_barang` (`id_barang`,`id_transaksi`),
  ADD KEY `id_tranksaksi` (`id_transaksi`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `user` (`id_user`) USING BTREE;

--
-- Indeks untuk tabel `restok`
--
ALTER TABLE `restok`
  ADD PRIMARY KEY (`id_restok`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_barang_2` (`id_barang`);

--
-- Indeks untuk tabel `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `id_booking` (`id_booking`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id_transaksi_detail`),
  ADD KEY `transaksi` (`id_transaksi`) USING BTREE,
  ADD KEY `layanan` (`id_layanan`) USING BTREE;

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `id_detail_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `paket_stok`
--
ALTER TABLE `paket_stok`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `pemakaian_stok`
--
ALTER TABLE `pemakaian_stok`
  MODIFY `id_pemakaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `restok`
--
ALTER TABLE `restok`
  MODIFY `id_restok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `stok_barang`
--
ALTER TABLE `stok_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id_transaksi_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD CONSTRAINT `booking_detail_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_detail_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `paket_stok`
--
ALTER TABLE `paket_stok`
  ADD CONSTRAINT `paket_stok_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paket_stok_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `stok_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pemakaian_stok`
--
ALTER TABLE `pemakaian_stok`
  ADD CONSTRAINT `pemakaian_stok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `stok_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemakaian_stok_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `restok`
--
ALTER TABLE `restok`
  ADD CONSTRAINT `restok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `stok_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
