-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 12, 2020 at 09:24 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_movie_sell`
--

-- --------------------------------------------------------

--
-- Table structure for table `ams_why_us_markets`
--

CREATE TABLE `ams_why_us_markets` (
  `id` int(11) NOT NULL,
  `title` text,
  `description` text,
  `icon_class` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_why_us_markets`
--

INSERT INTO `ams_why_us_markets` (`id`, `title`, `description`, `icon_class`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Lorem Ipsum', 'Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,\r\n                                leo quam aliquet diam congue is laoreet elit metus.', 'lnr lnr-license pcolor', '1', '2020-07-12 18:31:12', '2020-07-12 18:31:12', NULL),
(2, 'Lorem Ipsums', 'Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,\r\n                                leo quam aliquet diam congue is laoreet elit metus.', 'lnr lnr-magic-wand scolor', '1', '2020-07-12 18:32:02', '2020-07-12 18:32:02', NULL),
(3, 'Lorems Ipsum', 'Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,\r\n                                leo quam aliquet diam congue is laoreet elit metus.', 'lnr lnr-lock mcolor1', '1', '2020-07-12 18:32:55', '2020-07-12 18:32:55', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_why_us_markets`
--
ALTER TABLE `ams_why_us_markets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_why_us_markets`
--
ALTER TABLE `ams_why_us_markets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
