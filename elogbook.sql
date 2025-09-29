-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Jul 2025 pada 05.07
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elogbook`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `logbook`
--

CREATE TABLE `logbook` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `unit` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `intervensi` text DEFAULT NULL,
  `no_rm` varchar(50) DEFAULT NULL,
  `spo` varchar(10) DEFAULT NULL,
  `ttd` varchar(100) DEFAULT NULL,
  `shift` varchar(10) DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `logbook`
--

INSERT INTO `logbook` (`id`, `nama`, `unit`, `tanggal`, `intervensi`, `no_rm`, `spo`, `ttd`, `shift`, `status`) VALUES
(5, 'Tyfani', 'Siti Khadijah', '2025-07-02', 'Perawatan Luka', '12342', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Pagi', 'disetujui'),
(6, 'Devi Setiawan, S.Kep., Ners', 'Siti Khadijah', '2025-07-02', 'Manajemen Nyeri', '12123', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Pagi', 'disetujui'),
(7, 'Fauji Priatala, A.Md.Kep', 'Aisyah Lt.2', '2025-07-03', 'Pemberian Obat', '12343', 'Ya', 'Popi Rohyati, S.Kep.Ners', 'Pagi', 'ditolak'),
(9, 'Raka Maya Daud', 'Siti Khadijah', '2025-07-01', 'Bantuan Aktivitas Sehari-hari\r\nMembantu pasien dengan mandi, berpakaian, makan, dan aktivitas lainnya. ', '2123', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'disetujui'),
(12, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-03', 'Pemantauan Tanda Vital\r\nMengukur suhu tubuh, tekanan darah, denyut nadi, dan pernapasan. ', '12121', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Pagi', 'disetujui'),
(13, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-02', 'Perawatan Luka', '1212', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Pagi', 'disetujui'),
(14, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-02', 'Manajemen Nyeri', '12123', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'disetujui'),
(15, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-03', 'Manajemen Nyeri', '1213', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Pagi', 'disetujui'),
(16, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-01', 'Perawatan Luka', '2323', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'pending'),
(17, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-02', 'Pemberian Obat', '1223', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'pending'),
(18, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-02', 'Mobilisasi', '12233', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'pending'),
(19, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-03', 'Pemberian Obat', '12123', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Pagi', 'disetujui'),
(20, 'Raka Maya Daud, S.Kep., Ners', 'Siti Khadijah', '2025-07-03', 'Manajemen Nyeri', '1223', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'pending'),
(21, 'Devi Setiawan, S.Kep., Ners', 'Siti Khadijah', '2025-07-02', 'Manajemen Nyeri\r\nMemberikan perawatan ,.,...', '134351', 'Ya', 'Iyud Wahyudi, S.Kep., Ners', 'Siang', 'disetujui'),
(22, 'Asni Yulia, S.E', 'Ade Irma Suryani Lt.1', '2025-07-02', 'Perawatan Luka', '1213', 'Ya', 'Eli Irianti, S.Kep.Ners', 'Pagi', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_kunjungan`
--

CREATE TABLE `log_kunjungan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_kunjungan`
--

INSERT INTO `log_kunjungan` (`id`, `user_id`, `nama`, `role`, `waktu`) VALUES
(4, 1, 'Imam Wahyudiarto', 'admin', '2025-07-02 21:54:44'),
(5, 2, 'Asni Yulia', 'petugas', '2025-07-02 21:55:13'),
(6, 1, 'Imam Wahyudiarto', 'admin', '2025-07-02 21:55:26'),
(7, 4, 'Cecep Fatahilah, S.Kep, Ners, M.Kep, M.M.', 'admin', '2025-07-02 22:04:29'),
(8, 1, 'Imam Wahyudiarto', 'admin', '2025-07-02 22:09:46'),
(9, 1, 'Imam Wahyudiarto', 'admin', '2025-07-03 10:14:53'),
(10, 4, 'Cecep Fatahilah, S.Kep, Ners, M.Kep, M.M.', 'admin', '2025-07-03 10:19:07'),
(11, 2, 'Asni Yulia', 'petugas', '2025-07-03 10:23:28'),
(12, 1, 'Imam Wahyudiarto', 'admin', '2025-07-03 11:59:43'),
(13, 4, 'Cecep Fatahilah, S.Kep, Ners, M.Kep, M.M.', 'admin', '2025-07-03 14:26:47'),
(14, 2, 'Asni Yulia', 'petugas', '2025-07-03 14:34:09'),
(15, 1, 'Imam Wahyudiarto', 'admin', '2025-07-03 14:37:43'),
(16, 1, 'Imam Wahyudiarto', 'admin', '2025-07-03 14:40:35'),
(17, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:18:29'),
(18, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:34:11'),
(19, 2, 'Asni Yulia', 'petugas', '2025-07-09 08:34:35'),
(20, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:35:29'),
(21, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:36:43'),
(22, 2, 'Asni Yulia', 'petugas', '2025-07-09 08:39:06'),
(23, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:39:43'),
(24, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:42:36'),
(25, 2, 'Asni Yulia', 'petugas', '2025-07-09 08:42:46'),
(26, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 08:46:12'),
(27, 3, 'Eli Iriyanti S.Kep., Ners', 'supervisor', '2025-07-09 08:56:05'),
(28, 2, 'Asni Yulia', 'petugas', '2025-07-09 09:00:53'),
(29, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 09:03:24'),
(30, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 09:10:55'),
(31, 3, 'Eli Iriyanti S.Kep., Ners', 'supervisor', '2025-07-09 09:29:27'),
(32, 2, 'Asni Yulia', 'petugas', '2025-07-09 09:29:53'),
(33, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 09:30:38'),
(34, 3, 'Eli Iriyanti S.Kep., Ners', 'supervisor', '2025-07-09 09:58:11'),
(35, 1, 'Imam Wahyudiarto', 'admin', '2025-07-09 10:00:48');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','supervisor') NOT NULL,
  `unit` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `role`, `unit`) VALUES
(1, 'Imam Wahyudiarto', 'imam', '$2y$10$NvARTtTpGcUO.YE3VIxrneA/CABb5xC4dudqG9YA09Zujt105NM.y', 'admin', NULL),
(2, 'Asni Yulia', 'asni', '$2y$10$CKMJa.QlsqdqjOSZ36TiAuy8CLl5VkFFCwhaGlnCDvOTlJlM6JVau', 'petugas', NULL),
(3, 'Eli Iriyanti S.Kep., Ners', 'eli', '$2y$10$P14iIeCqMQm85ilVIx8fTOdY9t/dh.Q7HOcOmfOZDXjSprpe8Dc4.', 'supervisor', NULL),
(4, 'Cecep Fatahilah, S.Kep, Ners, M.Kep, M.M.', 'cecep', '$2y$10$cLLDmKgX1tUn33atnZSu/OUqEDfLmMN2qv7ThaN8SSZ2HgAnEyf0W', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `logbook`
--
ALTER TABLE `logbook`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_kunjungan`
--
ALTER TABLE `log_kunjungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `logbook`
--
ALTER TABLE `logbook`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `log_kunjungan`
--
ALTER TABLE `log_kunjungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `log_kunjungan`
--
ALTER TABLE `log_kunjungan`
  ADD CONSTRAINT `log_kunjungan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
