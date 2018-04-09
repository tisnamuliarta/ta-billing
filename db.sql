-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.18 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for diatmika
CREATE DATABASE IF NOT EXISTS `diatmika` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `diatmika`;

-- Dumping structure for table diatmika.tb_admin
CREATE TABLE IF NOT EXISTS `tb_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `tlpn` varchar(20) DEFAULT NULL,
  `alamat` text,
  `password` varchar(250) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'active',
  `avatar` varchar(200) NOT NULL DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table diatmika.tb_admin: ~3 rows (approximately)
DELETE FROM `tb_admin`;
/*!40000 ALTER TABLE `tb_admin` DISABLE KEYS */;
INSERT INTO `tb_admin` (`id`, `username`, `jabatan`, `tlpn`, `alamat`, `password`, `status`, `avatar`, `created_at`) VALUES
	(1, 'admin', 'admin', '23', '23', '$2y$10$dyVRF/dV2xyh8IvJIuxgveg5rkGdtZCmeLAKJxYSanLUO3wem6Wia', 'active', 'default.png', '2018-04-02 22:42:30'),
	(13, 'admin2', 'desainer', '123', 'Denpasar', '$2y$10$/D7vyxTDw5iyyjvZBc8d9.eU2947yhfYfLkAs4xL6F6jwed2HqOHS', 'non-active', 'default.png', '2018-04-02 22:36:47'),
	(14, 'admin1', 'desainer', '123', 'Denpasar', '$2y$10$QewNquBMiKk/iFj3F0TKiuFjz.aWUquLxKHZmdd3NBI9CMSGGPswm', 'active', 'default.png', '2018-04-02 22:48:00');
/*!40000 ALTER TABLE `tb_admin` ENABLE KEYS */;

-- Dumping structure for table diatmika.tb_customer
CREATE TABLE IF NOT EXISTS `tb_customer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `tlpn` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table diatmika.tb_customer: ~0 rows (approximately)
DELETE FROM `tb_customer`;
/*!40000 ALTER TABLE `tb_customer` DISABLE KEYS */;
INSERT INTO `tb_customer` (`id`, `nama`, `tlpn`, `alamat`, `status`, `created_at`) VALUES
	(1, 'Tisna Adi', '232332323', 'sasa', 'active', '2018-04-02 23:40:32');
/*!40000 ALTER TABLE `tb_customer` ENABLE KEYS */;

-- Dumping structure for table diatmika.tb_pengerjaan
CREATE TABLE IF NOT EXISTS `tb_pengerjaan` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_transaksi` int(10) unsigned NOT NULL,
  `awal_pengerjaan` datetime NOT NULL,
  `akhir_pengerjaan` datetime NOT NULL,
  `waktu_stop` datetime NOT NULL,
  `selesai` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_tb_pengerjaan_tb_transaksi` (`id_transaksi`),
  CONSTRAINT `FK_tb_pengerjaan_tb_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table diatmika.tb_pengerjaan: ~0 rows (approximately)
DELETE FROM `tb_pengerjaan`;
/*!40000 ALTER TABLE `tb_pengerjaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `tb_pengerjaan` ENABLE KEYS */;

-- Dumping structure for table diatmika.tb_transaksi
CREATE TABLE IF NOT EXISTS `tb_transaksi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_customer` int(10) unsigned NOT NULL,
  `tgl` date NOT NULL,
  `pengerjaan` varchar(200) NOT NULL,
  `uang_muka` decimal(20,2) NOT NULL,
  `total_transaksi` decimal(20,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Belum dikerjakan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `FK_tb_transaksi_tb_customer` (`id_customer`),
  CONSTRAINT `FK_tb_transaksi_tb_customer` FOREIGN KEY (`id_customer`) REFERENCES `tb_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table diatmika.tb_transaksi: ~0 rows (approximately)
DELETE FROM `tb_transaksi`;
/*!40000 ALTER TABLE `tb_transaksi` DISABLE KEYS */;
INSERT INTO `tb_transaksi` (`id`, `id_customer`, `tgl`, `pengerjaan`, `uang_muka`, `total_transaksi`, `status`, `created_at`) VALUES
	(1, 1, '2018-02-04', 'Desain Baju wwewe', 20000.00, 100000.00, 'Belum dikerjakan', '2018-04-04 08:10:09');
/*!40000 ALTER TABLE `tb_transaksi` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
