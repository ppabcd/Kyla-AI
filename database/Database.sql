-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 13 Apr 2017 pada 16.47
-- Versi Server: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ai`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alur`
--

CREATE TABLE `alur` (
  `id_alur` int(11) NOT NULL,
  `id_kalimat` text NOT NULL,
  `id_response` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Struktur dari tabel `filter`
--

CREATE TABLE `filter` (
  `id_filter` int(11) NOT NULL,
  `filter` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `filter`
--

INSERT INTO `filter` (`id_filter`, `filter`) VALUES
(1, 'babi'),
(3, 'anjing'),
(4, 'monyet'),
(5, 'kunyuk'),
(6, 'bajingan'),
(7, 'asu'),
(8, 'bangsat'),
(9, 'kampret'),
(10, 'bacot'),
(11, 'kontol'),
(12, 'memek'),
(13, 'ngentot'),
(14, 'ngewe'),
(15, 'perek'),
(16, 'pecun'),
(17, 'bencong'),
(18, 'banci'),
(19, 'jablay'),
(20, 'maho'),
(21, 'bego'),
(22, 'goblok'),
(23, 'idiot'),
(24, 'geblek'),
(25, 'gila'),
(26, 'sinting'),
(27, 'tolol'),
(28, 'sarap'),
(29, 'udik'),
(30, 'kampungan'),
(31, 'buta'),
(32, 'budek'),
(33, 'bolot'),
(34, 'jelek'),
(35, 'tai'),
(36, 'iblis'),
(37, 'keparat'),
(38, 'gembel'),
(39, 'brengsek'),
(40, 'sompret'),
(41, 'jancok'),
(42, 'cok'),
(43, 'eue'),
(44, 'ewe'),
(45, 'hentai'),
(46, 'oppai'),
(47, 'nenen'),
(48, 'tete'),
(49, 'topless'),
(50, 'kanciang');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kalimat`
--

CREATE TABLE `kalimat` (
  `id_kalimat` int(11) NOT NULL,
  `id_kata` longtext NOT NULL,
  `id_response` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kata`
--

CREATE TABLE `kata` (
  `id_kata` int(11) NOT NULL,
  `kata` varchar(225) NOT NULL,
  `hit` int(11) NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `response`
--

CREATE TABLE `response` (
  `id_response` int(11) NOT NULL,
  `response` varchar(225) NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`) VALUES
(1, 'User'),
(2, 'Kyla');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alur`
--
ALTER TABLE `alur`
  ADD PRIMARY KEY (`id_alur`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `filter`
--
ALTER TABLE `filter`
  ADD PRIMARY KEY (`id_filter`);

--
-- Indexes for table `kalimat`
--
ALTER TABLE `kalimat`
  ADD PRIMARY KEY (`id_kalimat`);

--
-- Indexes for table `kata`
--
ALTER TABLE `kata`
  ADD PRIMARY KEY (`id_kata`),
  ADD UNIQUE KEY `kata` (`kata`);

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`id_response`),
  ADD UNIQUE KEY `response` (`response`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alur`
--
ALTER TABLE `alur`
  MODIFY `id_alur` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `filter`
--
ALTER TABLE `filter`
  MODIFY `id_filter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `kalimat`
--
ALTER TABLE `kalimat`
  MODIFY `id_kalimat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `kata`
--
ALTER TABLE `kata`
  MODIFY `id_kata` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;
--
-- AUTO_INCREMENT for table `response`
--
ALTER TABLE `response`
  MODIFY `id_response` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
