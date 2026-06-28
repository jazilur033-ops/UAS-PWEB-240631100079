SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `db_keuangan`
CREATE DATABASE IF NOT EXISTS `db_keuangan`;
USE `db_keuangan`;

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `jenis` enum('Pemasukan','Pengeluaran') NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id_transaksi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaksi` (`id_transaksi`, `tanggal`, `jenis`, `kategori`, `keterangan`, `jumlah`) VALUES
(1, '2026-06-01', 'Pemasukan', 'Gaji', 'Gaji bulan Juni', 5000000.00),
(2, '2026-06-03', 'Pengeluaran', 'Makanan', 'Makan di luar', 150000.00),
(3, '2026-06-05', 'Pemasukan', 'Freelance', 'Project website', 1200000.00),
(4, '2026-06-07', 'Pengeluaran', 'Transportasi', 'Bensin motor', 50000.00),
(5, '2026-06-10', 'Pengeluaran', 'Belanja', 'Belanja bulanan', 850000.00);

COMMIT;
