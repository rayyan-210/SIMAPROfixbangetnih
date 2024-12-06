-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Des 2024 pada 03.05
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
-- Database: `simapro`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'ajieadmin', 'ajieadmin1234'),
(2, 'ray', 'ray');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `nama` varchar(60) NOT NULL,
  `stok` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id` int(11) NOT NULL,
  `kodeproduk` varchar(100) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `jenis` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id`, `kodeproduk`, `nama`, `gambar`, `harga`, `jenis`) VALUES
(1, 'bks1', 'Bakso Salam Ayam', 'bakso_salam_ayam.jpeg', 19000, 'Bakso'),
(2, 'bks2', 'Bakso Kamil Sapi', 'bakso_kamil_sapi.jpg', 33000, 'Bakso'),
(3, 'nug1', 'Nugget Bartoz 500g', 'nugget_bartoz_500g.jpeg', 25000, 'Nugget'),
(4, 'nug2', 'Nugget So Nice 1kg', 'nugget_so_nice_1kg.jpg', 47000, 'Nugget'),
(5, 'sos1', 'Sosis Bakar Muantap Jumbo', 'sosis_bakar_muantap_jumbo.jpg', 28000, 'Sosis'),
(6, 'sos2', 'Sosis Goreng Ngetop 1kg', 'sosis_goreng_ngetop_1kg.jpeg', 38000, 'Sosis'),
(7, 'temp1', 'Tempura Bintang', 'tempura_bintang.jpg', 13500, 'Tempura'),
(8, 'temp2', 'Cedea Crabstick', 'cedea_crabstick.jpg', 17500, 'Tempura');

-- --------------------------------------------------------

--
-- Struktur dari tabel `promosi`
--

CREATE TABLE `promosi` (
  `id_promosi` int(11) NOT NULL,
  `saran` text NOT NULL,
  `tanggal` date NOT NULL,
  `gambar` varchar(30) NOT NULL,
  `uploud` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `promosi`
--
ALTER TABLE `promosi`
  ADD PRIMARY KEY (`id_promosi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `promosi`
--
ALTER TABLE `promosi`
  MODIFY `id_promosi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
