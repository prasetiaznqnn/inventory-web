-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 25, 2024 at 06:04 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id` int NOT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `jumlah_keluar` int DEFAULT NULL,
  `note` text,
  `status_approve` enum('pending','approved','rejected') NOT NULL,
  `alasan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id`, `tanggal_keluar`, `user`, `kode_barang`, `jumlah_keluar`, `note`, `status_approve`, `alasan`) VALUES
(167, '2024-11-25', 'Rizaaaa', 'IT-001', 10, 'no', 'rejected', 'Barang ditolak oleh '),
(168, '2024-11-25', 'Rizaaaa', 'IT-002', 10, 'note', 'rejected', 'Barang ditolak oleh '),
(169, '2024-11-25', 'Rizaaaa', 'IT-030', 10, 'not', 'rejected', 'Barang ditolak oleh '),
(170, '2024-11-25', 'riza', 'IT-001', 10, 'not', 'approved', 'Disetujui oleh '),
(171, '2024-11-25', 'riza', 'IT-002', 10, 'note', 'approved', 'Disetujui oleh '),
(172, '2024-11-25', 'riza', 'IT-030', 20, 'no', 'approved', 'Disetujui oleh '),
(173, '2024-11-25', 'Rrrrr', 'IT-001', 10, 'no', 'approved', 'Disetujui oleh '),
(174, '2024-11-25', 'Rrrrr', 'IT-002', 10, 'no', 'approved', 'Disetujui oleh '),
(175, '2024-11-25', 'Rrrrr', 'IT-030', 10, 'no', 'approved', 'Disetujui oleh '),
(176, '2024-11-25', 'Riz', 'IT-001', 10, '10', 'approved', 'Disetujui oleh '),
(177, '2024-11-25', 'Riz', 'IT-001', 10, '10', 'rejected', 'Barang ditolak oleh ');

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id` int NOT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `jumlah_masuk` int DEFAULT NULL,
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id`, `tanggal_masuk`, `supplier`, `kode_barang`, `jumlah_masuk`, `note`) VALUES
(153, '2024-11-25', 'RRRRRRRRRrrr', 'IT-001', 10, 'NOT'),
(154, '2024-11-25', 'RRRRRRRRRrrr', 'IT-002', 10, 'NOTE'),
(155, '2024-11-25', 'RRRRRRRRRrrr', 'IT-030', 19, 'NOT'),
(156, '2024-11-25', 'ri', 'IT-001', 10, 'no'),
(157, '2024-11-25', 'ri', 'IT-002', 10, 'no'),
(158, '2024-11-25', 'ri', 'IT-030', 1, 'not'),
(159, '2024-11-25', 'Rrrrr', 'IT-001', 10, 'no'),
(160, '2024-11-25', 'Rrrrr', 'IT-002', 10, 'no'),
(161, '2024-11-25', 'Rrrrr', 'IT-030', 20, 'no'),
(162, '2024-10-24', 'Riza', '1', 30, 'note'),
(163, '2023-11-25', '1', '2', 100, 'not'),
(164, '2025-11-25', 'rizz', '3', 67, 'not');

-- --------------------------------------------------------

--
-- Table structure for table `master_barang`
--

CREATE TABLE `master_barang` (
  `kode_barang` varchar(50) NOT NULL,
  `jenis_barang` varchar(100) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `deskripsi` text,
  `maker` varchar(100) DEFAULT NULL,
  `jumlah` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `master_barang`
--

INSERT INTO `master_barang` (`kode_barang`, `jenis_barang`, `nama_barang`, `deskripsi`, `maker`, `jumlah`) VALUES
('1', '-', '-', '-', '-', 30),
('2', '-', '-', '-', '-', 100),
('3', '-', '-', '-', '-', 67),
('IT-001', 'FURNITURE', 'KEYBOARD', 'DESKRIPSI', 'MAKER', 0),
('IT-002', 'ELEKTRONIK', 'KURSI', 'KURSI OFFICE', 'MAKER', 10),
('IT-030', 'ELEKTRONIK', 'MEJA', 'MEJA OFFICE', 'MAKER', 10);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` int NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `role_name`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `role_id`, `created_at`) VALUES
(1, 'superadmin', '21232f297a57a5a743894a0e4a801fc3', 1, '2024-11-21 13:42:09'),
(2, 'pengguna', 'ee11cbb19052e40b07aac0ca060c23ee', 2, '2024-11-21 15:49:48'),
(18, 'riza', 'd5f275885bd96778f7f01c814e405e7c', 1, '2024-11-25 01:11:59'),
(19, 'dwi', '7aa2602c588c05a93baf10128861aeb9', 2, '2024-11-25 01:12:19'),
(20, 'prasetia', '719bdd15707077aa65bf225f5cc85457', 2, '2024-11-25 01:12:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_keluar_ibfk_1` (`kode_barang`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `barang_masuk_ibfk_1` (`kode_barang`);

--
-- Indexes for table `master_barang`
--
ALTER TABLE `master_barang`
  ADD PRIMARY KEY (`kode_barang`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_ibfk_1` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `master_barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `barang_masuk_ibfk_1` FOREIGN KEY (`kode_barang`) REFERENCES `master_barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
