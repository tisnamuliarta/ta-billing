-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 17, 2018 at 01:10 AM
-- Server version: 5.7.18
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diatmika`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `tlpn` varchar(20) DEFAULT NULL,
  `alamat` text,
  `password` varchar(250) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `avatar` varchar(200) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id`, `username`, `jabatan`, `tlpn`, `alamat`, `password`, `status`, `avatar`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', '23', '23', '$2y$10$dyVRF/dV2xyh8IvJIuxgveg5rkGdtZCmeLAKJxYSanLUO3wem6Wia', 'active', 'default.png', '2018-04-02 14:42:30', '2018-04-09 23:35:38'),
(14, 'admin1', 'desainer', '123', 'Denpasar', '$2y$10$QewNquBMiKk/iFj3F0TKiuFjz.aWUquLxKHZmdd3NBI9CMSGGPswm', 'active', 'default.png', '2018-04-02 14:48:00', '2018-04-09 23:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer`
--

CREATE TABLE `tb_customer` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(200) NOT NULL,
  `tlpn` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_customer`
--

INSERT INTO `tb_customer` (`id`, `nama`, `tlpn`, `alamat`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tisna Adi', '232332323', 'sasa', 'active', '2018-04-02 15:40:32', '2018-04-09 23:35:21'),
(2, 'ad', '232323', 'asasas', 'active', '2018-04-24 13:05:32', '2018-04-24 13:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengerjaan`
--

CREATE TABLE `tb_pengerjaan` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_transaksi` int(10) UNSIGNED NOT NULL,
  `awal_pengerjaan` datetime NOT NULL,
  `akhir_pengerjaan` datetime NOT NULL,
  `waktu_stop` datetime NOT NULL,
  `selesai` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengerjaan_transaksi`
--

CREATE TABLE `tb_pengerjaan_transaksi` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_transaksi` int(10) UNSIGNED NOT NULL,
  `id_admin` int(10) UNSIGNED DEFAULT NULL,
  `waktu` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `cacheAdditionalTime` varchar(50) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_pengerjaan_transaksi`
--

INSERT INTO `tb_pengerjaan_transaksi` (`id`, `id_transaksi`, `id_admin`, `waktu`, `status`, `cacheAdditionalTime`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '00:00:43', 2, '43288', '2018-04-19 13:31:17', '2018-04-22 02:06:48'),
(2, 5, 14, '00:00:37', 1, '37677', '2018-04-22 02:35:38', '2018-04-23 13:05:00'),
(3, 6, 14, '00:00:20', 2, '20131', '2018-04-22 02:52:37', '2018-04-23 13:08:55'),
(4, 7, 14, '00:00:20', 2, '20131', '2018-04-22 02:52:37', '2018-07-16 23:35:18'),
(5, 8, 14, '00:00:20', 2, '20131', '2018-04-22 02:52:37', '2018-04-23 13:08:55');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_customer` int(10) UNSIGNED NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `tgl` date NOT NULL,
  `pengerjaan` varchar(200) NOT NULL,
  `uang_muka` decimal(20,2) NOT NULL,
  `total_transaksi` decimal(20,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `id_customer`, `kode`, `tgl`, `pengerjaan`, `uang_muka`, `total_transaksi`, `created_at`) VALUES
(4, 1, 'ti2', '2018-01-04', 'Desain Baju', '50000.00', '200000.00', '2018-04-23 22:54:28'),
(5, 1, 'ti2', '2018-02-04', 'Desain Logo', '20000.00', '100000.00', '2018-04-23 22:54:21'),
(6, 1, 'ti2', '2018-01-04', 'Desain Baju', '10000.00', '80000.00', '2018-04-23 22:53:52'),
(7, 1, 'ti1', '2018-01-04', 'Desain Baju', '50000.00', '200000.00', '2018-04-23 22:54:28'),
(8, 1, 'ti1', '2018-01-04', 'Desain Baju', '50000.00', '200000.00', '2018-04-23 22:54:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_customer`
--
ALTER TABLE `tb_customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pengerjaan`
--
ALTER TABLE `tb_pengerjaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tb_pengerjaan_tb_transaksi` (`id_transaksi`);

--
-- Indexes for table `tb_pengerjaan_transaksi`
--
ALTER TABLE `tb_pengerjaan_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tb_pengerjaan_transaksi_tb_transaksi` (`id_transaksi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tb_transaksi_tb_customer` (`id_customer`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tb_customer`
--
ALTER TABLE `tb_customer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_pengerjaan`
--
ALTER TABLE `tb_pengerjaan`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_pengerjaan_transaksi`
--
ALTER TABLE `tb_pengerjaan_transaksi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_pengerjaan`
--
ALTER TABLE `tb_pengerjaan`
  ADD CONSTRAINT `FK_tb_pengerjaan_tb_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pengerjaan_transaksi`
--
ALTER TABLE `tb_pengerjaan_transaksi`
  ADD CONSTRAINT `FK_tb_pengerjaan_transaksi_tb_admin` FOREIGN KEY (`id_admin`) REFERENCES `tb_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tb_pengerjaan_transaksi_tb_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `FK_tb_transaksi_tb_customer` FOREIGN KEY (`id_customer`) REFERENCES `tb_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
