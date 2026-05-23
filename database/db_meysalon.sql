-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Bulan Mei 2026 pada 18.34
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

--
-- Dumping data untuk tabel `booking`
--

INSERT INTO `booking` (`id_booking`, `id_user`, `tanggal_booking`, `jam_mulai`, `jam_selesai`, `status_booking`, `catatan`, `tanggal_saran`, `jam_saran`, `catatan_admin`) VALUES
(5, 5, '2026-05-25', '14:00:00', '15:45:00', 'Done', 'potong wolf cut', '2026-05-25', '14:00:00', 'besok aja sekarang penuh'),
(6, 5, '2026-05-26', '14:00:00', '18:15:00', 'Done', '', NULL, NULL, NULL),
(7, 5, '2026-05-31', '14:00:00', '17:00:00', 'Waiting', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `booking_detail`
--

CREATE TABLE `booking_detail` (
  `id_detail_booking` int(11) NOT NULL,
  `id_booking` int(11) NOT NULL,
  `id_layanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `booking_detail`
--

INSERT INTO `booking_detail` (`id_detail_booking`, `id_booking`, `id_layanan`) VALUES
(11, 5, 3),
(10, 5, 11),
(13, 6, 4),
(12, 6, 9),
(14, 7, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `layanan`
--

CREATE TABLE `layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama_layanan` varchar(50) NOT NULL,
  `harga_layanan` int(11) NOT NULL,
  `durasi_layanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `layanan`
--

INSERT INTO `layanan` (`id_layanan`, `nama_layanan`, `harga_layanan`, `durasi_layanan`) VALUES
(1, 'Potong Pria', 15000, 30),
(2, 'Potong Wanita', 25000, 45),
(3, 'Potong + Cuci', 35000, 60),
(4, 'Smoothing', 250000, 180),
(5, 'Cat Rambut', 200000, 120),
(6, 'Highlight', 100000, 90),
(7, 'Creambath', 50000, 60),
(8, 'Hair Mask', 60000, 60),
(9, 'Hair Spa', 75000, 75),
(10, 'Eyelash Extension', 100000, 90),
(11, 'Nail Art', 50000, 45);

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
(1, 4, 5, 100),
(2, 4, 6, 100),
(3, 5, 1, 50),
(4, 3, 3, 20),
(5, 7, 3, 30),
(6, 7, 4, 1),
(7, 8, 3, 30),
(8, 8, 4, 1);

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

--
-- Dumping data untuk tabel `pemakaian_stok`
--

INSERT INTO `pemakaian_stok` (`id_pemakaian`, `id_barang`, `id_transaksi`, `jumlah_pemakaian`) VALUES
(1, 5, 5, 100),
(2, 6, 5, 100),
(3, 5, 5, 10);

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

--
-- Dumping data untuk tabel `pengeluaran`
--

INSERT INTO `pengeluaran` (`id_pengeluaran`, `id_user`, `jenis_pengeluaran`, `jumlah_pengeluaran`, `tanggal_pengeluaran`, `keterangan_pengeluaran`) VALUES
(5, 5, 'Listrik', 100000, '2026-05-31 17:00:00', 'bulan juni');

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

--
-- Dumping data untuk tabel `restok`
--

INSERT INTO `restok` (`id_restok`, `id_barang`, `tanggal_restok`, `jumlah_tambah`, `total_harga_restok`) VALUES
(2, 5, '2026-05-23 14:29:35', 10, 1800000);

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
(1, 'CBD ColorMax (Warna)', 'Chemical', 512, 'ml', 100, 45000),
(2, 'CBD ColorMax (Natural)', 'Chemical', 500, 'ml', 100, 45000),
(3, 'CBD Keratin Shampoo', 'Hair Care', 1000, 'ml', 200, 120000),
(4, 'Makarizo Hair Energy', 'Hair Care', 20, 'pcs', 5, 8000),
(5, 'Obat Smoothing Step 1', 'Chemical', 900, 'ml', 250, 180000),
(6, 'Neutralizer Step 2', 'Chemical', 900, 'ml', 250, 150000);

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

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_booking`, `tanggal_transaksi`, `total_bayar`, `jenis_pelanggan`, `tambahan_harga`, `catatan_tambahan`) VALUES
(5, 6, '2026-05-23 14:20:37', 374999, 'booking', 49999, 'panjang rambut');

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

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id_transaksi_detail`, `id_transaksi`, `id_layanan`, `harga_satuan`, `jumlah_layanan`, `subtotal`) VALUES
(1, 5, 4, 250000, 1, 250000),
(2, 5, 9, 75000, 1, 75000);

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
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `paket_stok`
--
ALTER TABLE `paket_stok`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
