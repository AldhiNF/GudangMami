-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 13, 2026 at 12:55 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gudang`
--
CREATE DATABASE IF NOT EXISTS `db_gudang` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `db_gudang`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(1, 'gudangku', '180301');

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `stok_sekarang` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `stok_sekarang`) VALUES
(2, 'Coca-Cola 1.5 Liter', 'Minuman', 150),
(3, 'Sprite 1.5 Liter', 'Minuman', 120),
(5, 'Teh Pucuk Harum 350ml (Karton)', 'Minuman', 300),
(7, 'Chitato Rasa Sapi Panggang 68g', 'Cemilan', 125),
(8, 'Taro Net Seaweed 65g', 'Cemilan', 200),
(9, 'Beng-Beng Share It (Box)', 'Cemilan', 150),
(10, 'Indomie Mi Goreng Spesial (Karton)', 'Makanan', 400),
(11, 'Biskuit Roma Kelapa 300g', 'Cemilan', 110),
(12, 'Fanta Strawberry 1.5 Liter', 'Minuman', 100),
(13, 'Air Mineral Aqua Galon 19l', 'Minuman', 30),
(14, 'Indomie Mi Goreng Rasa Rendang (karton)', 'Makanan', 230),
(15, 'Mie Sedap Goreng (kardus)', 'Makanan', 15),
(16, 'Susu Ultra Milk Rasa Cokelat', 'Minuman', 230);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_keluar`
--

CREATE TABLE `riwayat_keluar` (
  `id_riwayat` int NOT NULL,
  `id_barang` int NOT NULL,
  `jumlah_keluar` int NOT NULL,
  `waktu_keluar` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `riwayat_keluar`
--

INSERT INTO `riwayat_keluar` (`id_riwayat`, `id_barang`, `jumlah_keluar`, `waktu_keluar`) VALUES
(1, 15, 35, '2026-07-13 19:50:45'),
(2, 7, 125, '2026-07-13 19:51:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `riwayat_keluar`
--
ALTER TABLE `riwayat_keluar`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_barang` (`id_barang`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `riwayat_keluar`
--
ALTER TABLE `riwayat_keluar`
  MODIFY `id_riwayat` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `riwayat_keluar`
--
ALTER TABLE `riwayat_keluar`
  ADD CONSTRAINT `riwayat_keluar_ibfk_1` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
