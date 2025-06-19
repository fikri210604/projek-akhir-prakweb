-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2025 at 12:26 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projek-akhir-prakweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `jumlah` int DEFAULT '1',
  `status` enum('tersedia','dipinjam','tidak tersedia') DEFAULT 'tersedia',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto` varchar(255) DEFAULT NULL,
  `penulis_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul`, `penerbit`, `tahun_terbit`, `kategori_id`, `jumlah`, `status`, `created_at`, `foto`, `penulis_id`) VALUES
(1, 'Jagoan Neon', 'Erlangga', 2023, 2, 0, 'dipinjam', '2025-06-12 01:12:19', '5.png', NULL),
(2, 'Laskar Pelangi', 'Erlangga', 2012, 2, 2, 'dipinjam', '2025-06-12 01:16:32', '4.png', NULL),
(3, 'Historia', 'Histogram', 2020, 3, 0, 'tersedia', '2025-06-12 09:29:21', 'buku.jpg', NULL),
(4, 'Historia 2', 'Erlangga', 2024, 3, 0, 'tersedia', '2025-06-12 10:02:12', 'buku.jpg', NULL),
(5, 'Jagoan Neon', 'Erlangga', 2023, 2, 2, 'tersedia', '2025-06-18 00:57:59', 'jagoancilik.jpg', 1),
(6, 'Jagoan Neon', 'Erlangga', 2023, 2, 2, 'tersedia', '2025-06-18 00:58:24', 'jagoancilik.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `foto_kategori` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `foto_kategori`) VALUES
(2, 'Fiction', 'bagus', '2025-06-12 01:11:11', '684a292f9de9e_4.png'),
(3, 'History', 'Sejarah yang terjadi pada dunia', '2025-06-12 08:39:16', '684a9234d2956_4.png');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int NOT NULL,
  `users_id` int NOT NULL,
  `buku_id` int NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` enum('dipinjam','kembali','tidak dipinjam') DEFAULT 'tidak dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `users_id`, `buku_id`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
(1, 1, 1, '2025-06-12', '2025-06-19', 'dipinjam'),
(2, 1, 2, '2025-06-12', '2025-06-19', 'dipinjam'),
(3, 1, 1, '2025-06-12', '2025-06-19', 'dipinjam'),
(4, 4, 1, '2025-06-12', '2025-06-19', 'dipinjam'),
(5, 5, 1, '2025-06-12', '2025-06-19', 'dipinjam'),
(6, 5, 3, '2025-06-12', '2025-06-19', 'dipinjam'),
(7, 11, 3, '2025-06-12', '2025-06-19', 'dipinjam'),
(10, 13, 1, '2025-06-17', '2025-06-18', 'dipinjam');

-- --------------------------------------------------------

--
-- Table structure for table `penulis`
--

CREATE TABLE `penulis` (
  `id` int NOT NULL,
  `nama_penulis` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `bio` text,
  `tanggal_lahir` date DEFAULT NULL,
  `kebangsaan` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penulis`
--

INSERT INTO `penulis` (`id`, `nama_penulis`, `foto`, `bio`, `tanggal_lahir`, `kebangsaan`, `jenis_kelamin`) VALUES
(1, 'Andre Hirata', 'penulis_68520c3e833be9.78659702.jpg', 'Hebat Banget', '2008-02-19', 'Indonesia', 'Perempuan');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nomor_telepon` varchar(20) DEFAULT NULL,
  `password` text NOT NULL,
  `role` enum('petugas','penyewa') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `nama`, `nomor_telepon`, `password`, `role`, `created_at`) VALUES
(1, 'afh.fikri2106@gmail.com', 'Ahmad Fikri Hanif', '082278434859', '$2y$10$j6fl6G/v2HaS6ieoc5PdT.itDi0//rubDsjJMhkzGVMPjLnKV3/Pm', 'penyewa', '2025-06-11 04:59:07'),
(2, 'afh.fikri21188@gmail.com', 'admin123', '087788822', '$2y$10$NfLzJeiGl3afEClV06aiaODMYKuaMiUIfLJlXMVX/QdpS3wA6gSjS', 'petugas', '2025-06-11 05:00:33'),
(3, 'afh.fikri210609@gmail.com', 'admin', '089576650899', '$2y$10$VDdwgHh268gCH/k8NtKWNOnK6RxpAtKUy3av0KPbnKRZIYrjJfL/e', 'petugas', '2025-06-12 00:23:46'),
(4, 'meyta@gmail.com', 'Meyta Zaskia', '0899911277', '$2y$10$IMvOq2SpG47V0psNRAseh.cgiVkWKkgYQIlFtJ/clNrY8DuzM/Ww2', 'penyewa', '2025-06-12 07:34:18'),
(5, 'root@gmail.com', 'root', 'root', '$2y$10$tMTfLGKLpJJr1HcDveyPTOMOsueCok4VBppEOjarWcsggzN2nqflS', 'petugas', '2025-06-12 07:56:54'),
(6, 'root1@gmail.com', 'root', '082278434859', '$2y$10$bbswbBlJ8kr.KDAybOptWuUiqO4wr/5.9zOU99JLpzk2GGtIX3F4a', 'petugas', '2025-06-12 07:57:41'),
(7, 'root2@gmail.com', 'root2@gmail.com', '082278434859', '$2y$10$k7ono3qdVLiTI4JGoigaNOEFTkZSEntE0FgGfcNo/XDm1iZzIqOvu', 'petugas', '2025-06-12 08:02:03'),
(8, 'root3@gmail.com', 'admin', 'root', '$2y$10$CBAjqcS.0PEUpLPjZN1eN.MtKsJxN2SsPyjCW63PN7TlkJgFVtQbS', 'petugas', '2025-06-12 08:04:06'),
(9, 'root21@gmail.com', 'root', '92172718319', '$2y$10$5mSXcD6UWpMBkHlL2WnJ8uR6t0OZyIvLQdR/B7y1xZK113pXzHymW', 'penyewa', '2025-06-12 09:23:29'),
(10, 'meytaa@gmail.com', 'meytaaz', '8297329921', '$2y$10$aXHT10jnallVQvzbVs.pp.rcKjuDYQt.sBSA7MYZcI2pRO1dtpQrC', 'penyewa', '2025-06-12 09:25:24'),
(11, 'meytazz@gmail.com', 'Meyta Zaskiaa', '090990890', '$2y$10$BOwcqj/QK54giNrUZ2RV1.5zCpIgNPcRYOMxaNmnc1aJN9UqatLWC', 'penyewa', '2025-06-12 09:59:19'),
(13, 'rootii@gmai.com', 'admin233', '082278434888', '$2y$10$c5l/f1uhBana6opi1vi0wul59WrE7kBzEHFenxvuC3vRiVH2Y/mXC', 'penyewa', '2025-06-16 07:15:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `fk_penulis` (`penulis_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `buku_id` (`buku_id`);

--
-- Indexes for table `penulis`
--
ALTER TABLE `penulis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `penulis`
--
ALTER TABLE `penulis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_penulis` FOREIGN KEY (`penulis_id`) REFERENCES `penulis` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
