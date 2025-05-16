-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Bulan Mei 2025 pada 10.24
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
-- Database: `mealystics`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `username` text DEFAULT NULL,
  `password` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`) VALUES
(2, 'admini', 'admini123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `food_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `food_id`, `message`, `rating`, `created_at`) VALUES
(2, 'Rin', '', 1, 'enak', 5, '2025-05-14 05:08:03'),
(3, 'aqiyah', '', 3, 'rasanya krispi dan gak hambar', 4, '2025-05-14 05:13:49'),
(4, 'lisa', '', 2, 'dagingnyaaa enak pedas maniss', 5, '2025-05-14 05:17:51'),
(5, 'nely', '', 3, 'kriuk banget', 5, '2025-05-14 07:50:51'),
(6, 'uci', '', 1, 'enak tapi bumbunya kurang pedas', 3, '2025-05-14 08:22:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kontak`
--

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL,
  `plattform` varchar(50) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `icon_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kontak`
--

INSERT INTO `kontak` (`id`, `plattform`, `link`, `icon_path`) VALUES
(1, 'Instagram', 'https://www.instagram.com/waroenkkangmas.smd?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==', 'gambar/LOGO IG.png'),
(2, 'WhatsAap', 'https://wa.me/+6281345557551', 'gambar/LOGO WA.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `jumlah_porsi` int(11) NOT NULL,
  `foto_menu` varchar(255) DEFAULT NULL,
  `sold_out` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `deskripsi`, `harga`, `jumlah_porsi`, `foto_menu`, `sold_out`) VALUES
(1, 'Paket Ayam Bakar Jumbo + Es Teh', 'Ayam bakar jumbo(paha/dada), nasi, sambal, es teh', 20000, 80, 'gambar/ayam bakar.jpg', 0),
(2, 'Nasi Daging Lada Hitam', 'Nasi dan Daging Lada Hitam yang pedas', 15000, 40, 'gambar/lada hitam.jpg', 0),
(3, 'Nasi Ayam Kulit', 'Nasi, Ayam Kulit Krispi, dan Sambal', 15000, 35, 'gambar/nasi ayam kulit.jpeg', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu_catering`
--

CREATE TABLE `menu_catering` (
  `id_menu` int(11) NOT NULL,
  `nama_paket` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `jumlah_porsi` int(11) DEFAULT NULL,
  `foto_menu` varchar(255) DEFAULT NULL,
  `sold_out` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu_catering`
--

INSERT INTO `menu_catering` (`id_menu`, `nama_paket`, `deskripsi`, `harga`, `jumlah_porsi`, `foto_menu`, `sold_out`) VALUES
(1, 'Paket A', 'Ayam Bakar, Nasi Putih, Sambal dan Lalapan', 500000, 20, 'gambar/cater.png', 0),
(2, 'Paket B', 'Daging Lada Hitam, Nasi Putih, Capcay, Sambal dan Kerupuk', 800000, 15, 'gambar/catering.jpg', 0),
(3, 'Paket C', 'Ayam Kulit, Nasi Goreng, Fiesta Gelas, Sambal dan Kerupuk', 750000, 20, 'gambar/nasigoreng.jpg', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ordercatering`
--

CREATE TABLE `ordercatering` (
  `id_order` int(11) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_order` timestamp NOT NULL DEFAULT current_timestamp(),
  `jumlah_porsi` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ordermenu`
--

CREATE TABLE `ordermenu` (
  `id_order` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `waktu_pengambilan` time DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `tanggal_order` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ordermenu`
--

INSERT INTO `ordermenu` (`id_order`, `nama_pelanggan`, `no_hp`, `id_menu`, `jumlah`, `waktu_pengambilan`, `catatan`, `bukti_pembayaran`, `tanggal_order`) VALUES
(1, 'Irene MNS', '081770059576', 1, 1, '00:00:12', 'ayam paha', NULL, '2025-05-14 05:02:47'),
(2, 'Rin', '081770059576', 2, 3, '00:00:12', 'dibuat pedas', NULL, '2025-05-14 05:05:20'),
(3, 'rara', '081770059576', 1, 4, '00:00:15', '2 paha, 2 dada', NULL, '2025-05-14 05:06:54'),
(4, 'Nely', '081770059576', 3, 1, '00:00:13', 'mau kulit yang banyak', NULL, '2025-05-14 07:51:53');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`) USING HASH;

--
-- Indeks untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_food` (`food_id`);

--
-- Indeks untuk tabel `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `menu_catering`
--
ALTER TABLE `menu_catering`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `ordercatering`
--
ALTER TABLE `ordercatering`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_paket` (`id_paket`);

--
-- Indeks untuk tabel `ordermenu`
--
ALTER TABLE `ordermenu`
  ADD PRIMARY KEY (`id_order`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `menu_catering`
--
ALTER TABLE `menu_catering`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `ordercatering`
--
ALTER TABLE `ordercatering`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ordermenu`
--
ALTER TABLE `ordermenu`
  MODIFY `id_order` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_food` FOREIGN KEY (`food_id`) REFERENCES `menu` (`id_menu`);

--
-- Ketidakleluasaan untuk tabel `ordercatering`
--
ALTER TABLE `ordercatering`
  ADD CONSTRAINT `ordercatering_ibfk_1` FOREIGN KEY (`id_paket`) REFERENCES `menu` (`id_menu`);

--
-- Ketidakleluasaan untuk tabel `ordermenu`
--
ALTER TABLE `ordermenu`
  ADD CONSTRAINT `ordermenu_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
