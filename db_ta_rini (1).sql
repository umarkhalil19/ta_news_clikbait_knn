-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2023 at 09:11 PM
-- Server version: 5.7.24
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ta_rini`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita_latih`
--

CREATE TABLE `berita_latih` (
  `id` int(11) NOT NULL,
  `judul` text NOT NULL,
  `link` text NOT NULL,
  `klasifikasi` int(1) NOT NULL,
  `isi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `berita_uji`
--

CREATE TABLE `berita_uji` (
  `id` int(11) NOT NULL,
  `judul` text NOT NULL,
  `isi` text NOT NULL,
  `link` text NOT NULL,
  `klasifikasi` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bobot_kata_latih`
--

CREATE TABLE `bobot_kata_latih` (
  `id` int(11) NOT NULL,
  `kata_id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `bobot` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bobot_kata_uji`
--

CREATE TABLE `bobot_kata_uji` (
  `id` int(11) NOT NULL,
  `kata_id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hasil_knn`
--

CREATE TABLE `hasil_knn` (
  `id` int(11) NOT NULL,
  `uji_id` int(11) NOT NULL,
  `latih_id` int(11) NOT NULL,
  `bobot` double NOT NULL,
  `klasifikasi` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kata`
--

CREATE TABLE `kata` (
  `id` int(11) NOT NULL,
  `kata` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kata_latih`
--

CREATE TABLE `kata_latih` (
  `id` int(11) NOT NULL,
  `kata_id` int(11) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `frekuensi` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `kata_uji`
--

CREATE TABLE `kata_uji` (
  `id` int(11) NOT NULL,
  `kata` varchar(50) NOT NULL,
  `berita_id` int(11) NOT NULL,
  `frekuensi` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stopword`
--

CREATE TABLE `stopword` (
  `id` int(11) NOT NULL,
  `kata` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stopword`
--

INSERT INTO `stopword` (`id`, `kata`) VALUES
(1, 'gambasvideo'),
(2, 'detikhealth'),
(3, 'detik'),
(4, 'video'),
(5, 'ap'),
(6, 'haas'),
(7, 'advertisement'),
(8, 'kutip'),
(9, 'scroll'),
(10, 'resume'),
(11, 'content');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita_latih`
--
ALTER TABLE `berita_latih`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita_uji`
--
ALTER TABLE `berita_uji`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bobot_kata_latih`
--
ALTER TABLE `bobot_kata_latih`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bobot_kata_latih_FK` (`kata_id`),
  ADD KEY `bobot_kata_latih_FK_1` (`berita_id`);

--
-- Indexes for table `bobot_kata_uji`
--
ALTER TABLE `bobot_kata_uji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bobot_kata_uji_FK` (`kata_id`),
  ADD KEY `bobot_kata_uji_FK_1` (`berita_id`);

--
-- Indexes for table `hasil_knn`
--
ALTER TABLE `hasil_knn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kata`
--
ALTER TABLE `kata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kata_latih`
--
ALTER TABLE `kata_latih`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kata_uji_FK` (`kata_id`),
  ADD KEY `kata_uji_FK_1` (`berita_id`);

--
-- Indexes for table `kata_uji`
--
ALTER TABLE `kata_uji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kata_uji_FK` (`kata`),
  ADD KEY `kata_uji_FK_1` (`berita_id`);

--
-- Indexes for table `stopword`
--
ALTER TABLE `stopword`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita_latih`
--
ALTER TABLE `berita_latih`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `berita_uji`
--
ALTER TABLE `berita_uji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bobot_kata_latih`
--
ALTER TABLE `bobot_kata_latih`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bobot_kata_uji`
--
ALTER TABLE `bobot_kata_uji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil_knn`
--
ALTER TABLE `hasil_knn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kata`
--
ALTER TABLE `kata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kata_latih`
--
ALTER TABLE `kata_latih`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kata_uji`
--
ALTER TABLE `kata_uji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stopword`
--
ALTER TABLE `stopword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
