-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2024 at 12:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pizzéria`
--

-- --------------------------------------------------------

--
-- Table structure for table `felhasználó`
--

CREATE TABLE `felhasználó` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `jelszó` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `bejelentkezve` tinyint(1) NOT NULL,
  `utolsó belépés` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `felhasználó`
--

INSERT INTO `felhasználó` (`email`, `jelszó`, `név`, `bejelentkezve`, `utolsó belépés`) VALUES
('admin@gmail.com', '$2y$10$gSJydfrpur.YLXWWGhdq/.to1Bch3LZk7chLHHLQ9XDW/6GGRWmsq', 'Admin', 0, '2024-11-28'),
('attila@gmail.com', '$2y$10$RbBL/04.3emmDvHEfOGsOO8NVIGqEhzfEN6aHINCxFOXxHBrXSXcm', 'Attila', 0, '2024-11-23'),
('elek@gmail.com', '$2y$10$ks2C/izQhwaRGAqIflawl.gqmcty.Z57praM.WPIQRPmqVhmV0VQ.', 'Elek', 0, '2024-11-24'),
('jozsef@gmail.com', '$2y$10$4aKwOqocLw01zOnHt5Fam.BbKsPNj4OSrLEjfzQlyBRkvBRTYAki2', 'József', 0, '2024-11-22'),
('laszlo@gmail.com', '$2y$10$apVBUJhq3PbsP7.eDbEsXOmnTbvmtnVtNz.wccnEkUWkBwpWBvFqa', 'László', 0, '2024-11-22');

-- --------------------------------------------------------

--
-- Table structure for table `feltét`
--

CREATE TABLE `feltét` (
  `feltét név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `feltét ár` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `feltét`
--

INSERT INTO `feltét` (`feltét név`, `feltét ár`) VALUES
('bacon', 400),
('feta sajt', 450),
('füstölt sajt', 350),
('gomba', 350),
('juhtúró', 400),
('kukorica', 200),
('olivabogyó', 375),
('sajt', 300),
('sonka', 400),
('szalámi', 450),
('tarja', 450);

-- --------------------------------------------------------

--
-- Table structure for table `feltétopciója`
--

CREATE TABLE `feltétopciója` (
  `pizza név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `feltét név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `feltétopciója`
--

INSERT INTO `feltétopciója` (`pizza név`, `feltét név`) VALUES
('Gombás Pizza 30cm', 'feta sajt'),
('Gombás Pizza 30cm', 'gomba'),
('Gombás Pizza 30cm', 'juhtúró'),
('Gombás Pizza 30cm', 'kukorica'),
('Gombás Pizza 30cm', 'sajt'),
('Gombás Pizza 30cm', 'sonka'),
('Gombás Pizza 40cm', 'feta sajt'),
('Gombás Pizza 40cm', 'gomba'),
('Gombás Pizza 40cm', 'juhtúró'),
('Gombás Pizza 40cm', 'kukorica'),
('Gombás Pizza 40cm', 'sajt'),
('Gombás Pizza 40cm', 'sonka'),
('Kukoricás Pizza 30cm', 'füstölt sajt'),
('Kukoricás Pizza 30cm', 'kukorica'),
('Kukoricás Pizza 30cm', 'sajt'),
('Kukoricás Pizza 30cm', 'sonka'),
('Kukoricás Pizza 30cm', 'tarja'),
('Kukoricás Pizza 40cm', 'füstölt sajt'),
('Kukoricás Pizza 40cm', 'kukorica'),
('Kukoricás Pizza 40cm', 'sajt'),
('Kukoricás Pizza 40cm', 'sonka'),
('Kukoricás Pizza 40cm', 'tarja'),
('Margaréta Pizza 30cm', 'bacon'),
('Margaréta Pizza 30cm', 'kukorica'),
('Margaréta Pizza 30cm', 'sajt'),
('Margaréta Pizza 30cm', 'sonka'),
('Margaréta Pizza 30cm', 'szalámi'),
('Margaréta Pizza 30cm', 'tarja'),
('Margaréta Pizza 40cm', 'bacon'),
('Margaréta Pizza 40cm', 'kukorica'),
('Margaréta Pizza 40cm', 'sajt'),
('Margaréta Pizza 40cm', 'sonka'),
('Margaréta Pizza 40cm', 'szalámi'),
('Margaréta Pizza 40cm', 'tarja'),
('Sonkás Pizza 30cm', 'bacon'),
('Sonkás Pizza 30cm', 'feta sajt'),
('Sonkás Pizza 30cm', 'kukorica'),
('Sonkás Pizza 30cm', 'olivabogyó'),
('Sonkás Pizza 30cm', 'sajt'),
('Sonkás Pizza 30cm', 'sonka'),
('Sonkás Pizza 30cm', 'tarja'),
('Sonkás Pizza 40cm', 'bacon'),
('Sonkás Pizza 40cm', 'feta sajt'),
('Sonkás Pizza 40cm', 'kukorica'),
('Sonkás Pizza 40cm', 'olivabogyó'),
('Sonkás Pizza 40cm', 'sajt'),
('Sonkás Pizza 40cm', 'sonka'),
('Sonkás Pizza 40cm', 'tarja');

-- --------------------------------------------------------

--
-- Table structure for table `pizza`
--

CREATE TABLE `pizza` (
  `pizza név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `pizza ár` int(11) NOT NULL,
  `méret` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `pizza`
--

INSERT INTO `pizza` (`pizza név`, `pizza ár`, `méret`) VALUES
('Gombás Pizza 30cm', 2050, 30),
('Gombás Pizza 40cm', 3100, 40),
('Kukoricás Pizza 30cm', 1950, 30),
('Kukoricás Pizza 40cm', 2950, 40),
('Margaréta Pizza 30cm', 1850, 30),
('Margaréta Pizza 40cm', 2800, 40),
('Sonkás Pizza 30cm', 2050, 30),
('Sonkás Pizza 40cm', 3100, 40);

-- --------------------------------------------------------

--
-- Table structure for table `rendelés`
--

CREATE TABLE `rendelés` (
  `rendelés azonosító` int(11) NOT NULL,
  `időpont` datetime NOT NULL,
  `állapot` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL,
  `végösszeg` int(11) NOT NULL,
  `asztalszám` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `rendelés`
--

INSERT INTO `rendelés` (`rendelés azonosító`, `időpont`, `állapot`, `végösszeg`, `asztalszám`, `email`) VALUES
(1, '2024-11-20 14:00:00', 'fizetve', 13800, 1, 'elek@gmail.com'),
(2, '2024-11-20 14:30:00', 'fizetve', 9900, 2, 'jozsef@gmail.com'),
(3, '2024-11-20 14:30:00', 'fizetve', 1850, 3, 'laszlo@gmail.com'),
(4, '2024-11-21 14:00:00', 'fizetve', 4850, 1, 'jozsef@gmail.com'),
(5, '2024-11-21 14:00:00', 'fizetve', 5950, 2, 'laszlo@gmail.com'),
(6, '2024-11-21 15:00:00', 'fizetve', 16900, 3, 'elek@gmail.com'),
(7, '2024-11-23 16:45:31', 'asztalnál', 12500, 1, 'elek@gmail.com'),
(8, '2024-11-23 16:45:41', 'készül', 7800, 2, 'jozsef@gmail.com'),
(9, '2024-11-23 16:45:55', 'készül', 10650, 3, 'laszlo@gmail.com'),
(10, '2024-11-23 16:49:59', 'készül', 17100, 4, 'attila@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `rendeléstétel`
--

CREATE TABLE `rendeléstétel` (
  `tétel azonosító` int(11) NOT NULL,
  `darabszám` int(11) NOT NULL,
  `sorszám` int(11) NOT NULL,
  `összérték` int(11) NOT NULL,
  `rendelés azonosító` int(11) NOT NULL,
  `pizza név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `feltét név` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_hungarian_ci DEFAULT NULL,
  `melyikhez tétel azonosító` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;

--
-- Dumping data for table `rendeléstétel`
--

INSERT INTO `rendeléstétel` (`tétel azonosító`, `darabszám`, `sorszám`, `összérték`, `rendelés azonosító`, `pizza név`, `feltét név`, `melyikhez tétel azonosító`) VALUES
(1, 1, 1, 2050, 1, 'Gombás Pizza 30cm', NULL, 1),
(2, 1, 2, 400, 1, NULL, 'sonka', 1),
(3, 1, 3, 2050, 1, 'Gombás Pizza 30cm', NULL, 3),
(4, 1, 4, 400, 1, NULL, 'juhtúró', 3),
(5, 1, 5, 350, 1, NULL, 'gomba', 3),
(6, 1, 6, 2950, 1, 'Kukoricás Pizza 40cm', NULL, 6),
(7, 2, 7, 5600, 1, 'Margaréta Pizza 40cm', NULL, 7),
(8, 3, 1, 6150, 2, 'Sonkás Pizza 30cm', NULL, 8),
(9, 3, 2, 1200, 2, NULL, 'bacon', 8),
(10, 3, 3, 1200, 2, NULL, 'sonka', 8),
(11, 3, 4, 1350, 2, NULL, 'tarja', 8),
(12, 1, 1, 1850, 3, 'Margaréta Pizza 30cm', NULL, 13),
(13, 2, 1, 4100, 4, 'Sonkás Pizza 30cm', NULL, 12),
(14, 2, 2, 750, 4, NULL, 'olivabogyó', 13),
(15, 2, 1, 3900, 5, 'Kukoricás Pizza 30cm', NULL, 13),
(16, 1, 2, 2050, 5, 'Sonkás Pizza 30cm', NULL, 15),
(17, 3, 1, 9300, 6, 'Gombás Pizza 40cm', NULL, 16),
(18, 3, 2, 1050, 6, NULL, 'gomba', 17),
(19, 3, 3, 900, 6, NULL, 'sajt', 17),
(20, 1, 4, 1950, 6, 'Kukoricás Pizza 30cm', NULL, 20),
(21, 2, 5, 3700, 6, 'Margaréta Pizza 30cm', NULL, 21),
(22, 5, 1, 9750, 7, 'Kukoricás Pizza 30cm', NULL, 22),
(23, 5, 2, 1750, 7, NULL, 'füstölt sajt', 22),
(24, 5, 3, 1000, 7, NULL, 'kukorica', 22),
(25, 2, 1, 5600, 8, 'Margaréta Pizza 40cm', NULL, 25),
(26, 2, 2, 800, 8, NULL, 'bacon', 25),
(27, 2, 3, 600, 8, NULL, 'sajt', 25),
(28, 2, 4, 800, 8, NULL, 'sonka', 25),
(29, 3, 1, 5550, 9, 'Margaréta Pizza 30cm', NULL, 29),
(30, 3, 2, 1200, 9, NULL, 'bacon', 29),
(31, 3, 3, 1200, 9, NULL, 'sonka', 29),
(32, 3, 4, 1350, 9, NULL, 'szalámi', 29),
(33, 3, 5, 1350, 9, NULL, 'tarja', 29),
(34, 2, 1, 4100, 10, 'Sonkás Pizza 30cm', NULL, 34),
(35, 2, 2, 900, 10, NULL, 'feta sajt', 34),
(36, 3, 3, 8850, 10, 'Kukoricás Pizza 40cm', NULL, 36),
(37, 3, 4, 600, 10, NULL, 'kukorica', 36),
(38, 1, 5, 2050, 10, 'Gombás Pizza 30cm', NULL, 38),
(39, 1, 6, 400, 10, NULL, 'juhtúró', 38),
(40, 1, 7, 200, 10, NULL, 'kukorica', 38);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `felhasználó`
--
ALTER TABLE `felhasználó`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `feltét`
--
ALTER TABLE `feltét`
  ADD PRIMARY KEY (`feltét név`);

--
-- Indexes for table `feltétopciója`
--
ALTER TABLE `feltétopciója`
  ADD PRIMARY KEY (`pizza név`,`feltét név`),
  ADD KEY `pizza_nev` (`pizza név`),
  ADD KEY `feltet_nev` (`feltét név`);

--
-- Indexes for table `pizza`
--
ALTER TABLE `pizza`
  ADD PRIMARY KEY (`pizza név`);

--
-- Indexes for table `rendelés`
--
ALTER TABLE `rendelés`
  ADD PRIMARY KEY (`rendelés azonosító`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `rendeléstétel`
--
ALTER TABLE `rendeléstétel`
  ADD PRIMARY KEY (`tétel azonosító`),
  ADD UNIQUE KEY `sorszám` (`sorszám`,`rendelés azonosító`),
  ADD KEY `rendeles_azonosito` (`rendelés azonosító`),
  ADD KEY `pizza_nev` (`pizza név`),
  ADD KEY `tetel_nev` (`feltét név`),
  ADD KEY `tartozik tétel azonosító` (`melyikhez tétel azonosító`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rendelés`
--
ALTER TABLE `rendelés`
  MODIFY `rendelés azonosító` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `rendeléstétel`
--
ALTER TABLE `rendeléstétel`
  MODIFY `tétel azonosító` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feltétopciója`
--
ALTER TABLE `feltétopciója`
  ADD CONSTRAINT `feltétopciója_ibfk_1` FOREIGN KEY (`feltét név`) REFERENCES `feltét` (`feltét név`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `feltétopciója_ibfk_2` FOREIGN KEY (`pizza név`) REFERENCES `pizza` (`pizza név`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rendelés`
--
ALTER TABLE `rendelés`
  ADD CONSTRAINT `rendelés_ibfk_1` FOREIGN KEY (`email`) REFERENCES `felhasználó` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rendeléstétel`
--
ALTER TABLE `rendeléstétel`
  ADD CONSTRAINT `rendeléstétel_ibfk_1` FOREIGN KEY (`rendelés azonosító`) REFERENCES `rendelés` (`rendelés azonosító`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rendeléstétel_ibfk_3` FOREIGN KEY (`feltét név`) REFERENCES `feltét` (`feltét név`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rendeléstétel_ibfk_4` FOREIGN KEY (`pizza név`) REFERENCES `pizza` (`pizza név`) ON UPDATE CASCADE,
  ADD CONSTRAINT `rendeléstétel_ibfk_5` FOREIGN KEY (`melyikhez tétel azonosító`) REFERENCES `rendeléstétel` (`tétel azonosító`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
