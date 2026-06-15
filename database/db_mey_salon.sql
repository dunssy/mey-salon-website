-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2026 at 09:44 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `status_booking` enum('Waiting','On-going','Pending','Cancel','Done') NOT NULL,
  `total_dp` int(11) NOT NULL DEFAULT 0,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `catatan_costumer` text DEFAULT NULL,
  `tanggal_saran` date DEFAULT NULL,
  `jam_saran` time DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id_detail_booking` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama_layanan` varchar(50) NOT NULL,
  `harga_min` int(11) NOT NULL,
  `harga_max` int(11) DEFAULT NULL,
  `durasi_layanan` int(11) DEFAULT NULL,
  `keterangan_harga` varchar(100) DEFAULT NULL,
  `gambar_layanan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `harga_min`, `harga_max`, `durasi_layanan`, `keterangan_harga`, `gambar_layanan`) VALUES
(1, 'Creambath CBD Blow', 60000, NULL, 75, NULL, 'creambath.jpg'),
(2, 'Hair Mask Keratin CBD Catok', 80000, NULL, 90, NULL, 'hair-mask.avif'),
(3, 'Highlight', 100000, 300000, 120, 'Tergantung panjang dan ketebalan rambut', 'highlight.jpg'),
(4, 'Bleaching Full', 200000, 400000, 180, 'Tergantung panjang dan ketebalan rambut', 'bleaching.jpg'),
(5, 'Cat Warna / Coloring', 200000, 500000, 180, 'Tergantung warna, panjang, dan bahan', 'cat-color.jpg'),
(6, 'Cat Hitam / Toning', 150000, 400000, 150, 'Tergantung panjang rambut', 'cat-hitam.jpg'),
(7, 'Rebonding Makarizo', 200000, 500000, 180, 'Tergantung panjang rambut', 'rebonding.webp'),
(8, 'Smoothing Keratin / Matrix', 250000, 600000, 180, 'Tergantung panjang rambut dan produk', 'smoothing.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `paket_stok`
--

CREATE TABLE `paket_stok` (
  `id_paket` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `jumlah_stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket_stok`
--

INSERT INTO `paket_stok` (`id_paket`, `id_layanan`, `id_barang`, `jumlah_stok`) VALUES
(1, 1, 3, 50),
(2, 1, 4, 5),
(3, 2, 5, 50),
(4, 2, 4, 5),
(5, 3, 7, 40),
(6, 3, 6, 40),
(7, 4, 6, 70),
(8, 5, 7, 80),
(9, 5, 6, 80),
(10, 6, 7, 70),
(11, 6, 6, 70),
(12, 7, 4, 8),
(13, 8, 5, 60),
(14, 8, 4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `pemakaian_stok`
--

CREATE TABLE `pemakaian_stok` (
  `id_pemakaian` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `jumlah_pemakaian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
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
-- Table structure for table `restok`
--

CREATE TABLE `restok` (
  `id_restok` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `tanggal_restok` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `jumlah_tambah` int(11) NOT NULL,
  `harga_restok` int(11) NOT NULL,
  `total_harga_restok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stok_barang`
--

CREATE TABLE `stok_barang` (
  `id_barang` int(11) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `jenis_barang` varchar(50) NOT NULL,
  `jumlah_barang` int(11) NOT NULL,
  `satuan_barang` varchar(10) DEFAULT NULL,
  `jumlah_satuan` int(11) NOT NULL,
  `minimal_stok` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stok_barang`
--

INSERT INTO `stok_barang` (`id_barang`, `nama_barang`, `jenis_barang`, `jumlah_barang`, `satuan_barang`, `jumlah_satuan`, `minimal_stok`, `harga_beli`) VALUES
(1, 'CBD Cica+Vit Hair Mask', 'Hair Mask', 9992, 'gram', 500, 500, 45000),
(2, 'CBD Color Hair Mask Pomegranate', 'Hair Mask', 2500, 'gram', 500, 500, 45000),
(3, 'CBD Collagen Repair Hair Mask', 'Hair Mask', 8450, 'gram', 500, 500, 45000),
(4, 'CBD Collagen Repair Hair Serum Oil', 'Serum', 495, 'ml', 100, 100, 35000),
(5, 'CBD Keratin Pro Hair Mask', 'Hair Mask', 2500, 'gram', 500, 500, 50000),
(6, 'CBD ColorMax Developer 9%', 'Developer', 34860, 'ml', 1000, 1000, 45000),
(7, 'CBD ColorMax Color Cream', 'Cat Rambut', 500, 'ml', 100, 100, 55000);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_booking` int(11) DEFAULT NULL,
  `nama_pelanggan_walkin` varchar(100) DEFAULT NULL,
  `layanan_manual` varchar(255) DEFAULT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `total_bayar` int(11) NOT NULL,
  `jenis_pelanggan` enum('booking','walk-in') NOT NULL,
  `catatan_tambahan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
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
-- Table structure for table `user`
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `no_hp`, `alamat`, `email`, `role`, `password`) VALUES
(1, 'Mas Diman', '0895358711991', 'Subang', 'diman@gmail.com', 'Administrator', 'diman123'),
(5, 'Laely Fauziah Az', '082295450340', 'Pagaden', 'f02290511@gmail.com', 'Customer', '$2y$10$MEai8/4xW3jy3sZNoLB./eFxcLjjhukMexRXuLYynBCRyTJJUe7q.'),
(6, 'Tegar Zulian', '089507953836', 'Soklat, Subang', 'karbitan55@gmail.com', 'Customer', '$2y$10$r2412c62CAZ7aPMc5pd7B.kMYvWOEzoCw.x4GYYDIeOAfkyLT7nMm'),
(7, 'moch alipp', '082310326703', 'Cianjur', 'mchdalief24@gmail.com', 'Customer', '$2y$10$UpllWfpib5fZZzXJjlOJ2ukTHPO38mAoa.i2mlWiYgn1mf1b7vHzu'),
(8, 'Hilman', '0882002224583', 'Soklat, Subang', 'kybercunk@gmail.com', 'Customer', '$2y$10$3cTw.8IPBewGRtTRiA3ai.r2r0F5GDvF9d03jaBIL7Fp3wjXVEk0i'),
(9, 'Dryan Pasha Albani', '089678227707', 'Jepangdiperum', 'dryanpasha452@gmail.com', 'Customer', '$2y$10$dt2qO9vsbLcSiFFnRlqXcO6GCZ4obS4UhHMGBayB.nn1f8BcEEspK'),
(10, 'Yusuf', '081234567890', 'Subang', 'sireveloved@gmail.com', 'Customer', '$2y$10$JzIRQ3Te9PNn658.DYhN7OnrwaGsSdxld9BJ6AaCW5FJQ2TiPdBrS');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD PRIMARY KEY (`id_detail_booking`),
  ADD KEY `id_booking` (`id_booking`,`id_layanan`),
  ADD KEY `id_layanan` (`id_layanan`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `paket_stok`
--
ALTER TABLE `paket_stok`
  ADD PRIMARY KEY (`id_paket`),
  ADD UNIQUE KEY `id_layanan` (`id_layanan`,`id_barang`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `pemakaian_stok`
--
ALTER TABLE `pemakaian_stok`
  ADD PRIMARY KEY (`id_pemakaian`),
  ADD KEY `id_barang` (`id_barang`,`id_transaksi`),
  ADD KEY `id_tranksaksi` (`id_transaksi`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id_pengeluaran`),
  ADD KEY `user` (`id_user`) USING BTREE;

--
-- Indexes for table `restok`
--
ALTER TABLE `restok`
  ADD PRIMARY KEY (`id_restok`),
  ADD KEY `id_barang` (`id_barang`),
  ADD KEY `id_barang_2` (`id_barang`);

--
-- Indexes for table `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD UNIQUE KEY `id_booking` (`id_booking`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id_transaksi_detail`),
  ADD KEY `transaksi` (`id_transaksi`) USING BTREE,
  ADD KEY `layanan` (`id_layanan`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `booking_detail`
--
ALTER TABLE `booking_detail`
  MODIFY `id_detail_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `paket_stok`
--
ALTER TABLE `paket_stok`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `pemakaian_stok`
--
ALTER TABLE `pemakaian_stok`
  MODIFY `id_pemakaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id_pengeluaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `restok`
--
ALTER TABLE `restok`
  MODIFY `id_restok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stok_barang`
--
ALTER TABLE `stok_barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `id_transaksi_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_detail`
--
ALTER TABLE `booking_detail`
  ADD CONSTRAINT `booking_detail_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_detail_ibfk_2` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paket_stok`
--
ALTER TABLE `paket_stok`
  ADD CONSTRAINT `paket_stok_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paket_stok_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `stok_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pemakaian_stok`
--
ALTER TABLE `pemakaian_stok`
  ADD CONSTRAINT `pemakaian_stok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `stok_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemakaian_stok_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `restok`
--
ALTER TABLE `restok`
  ADD CONSTRAINT `restok_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `stok_barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_booking`) REFERENCES `booking` (`id_booking`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
