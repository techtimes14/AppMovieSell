-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2020 at 08:54 PM
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
-- Table structure for table `ams_banners`
--

CREATE TABLE `ams_banners` (
  `id` int(11) NOT NULL,
  `title` text CHARACTER SET utf8mb4 NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_croatian_ci DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Dumping data for table `ams_banners`
--

INSERT INTO `ams_banners` (`id`, `title`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Home', 'banner_1594920651.jpg', '1', '2020-07-16 17:30:53', '2020-07-16 17:30:53', NULL),
(2, 'home2', 'banner_1594920691.jpg', '1', '2020-07-16 17:31:31', '2020-07-16 17:31:31', NULL),
(3, 'home3', 'banner_1594920712.jpg', '1', '2020-07-16 17:31:52', '2020-07-16 17:31:52', NULL),
(4, 'about', 'banner_1594920752.jpg', '1', '2020-07-16 17:32:33', '2020-07-16 17:32:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_banners`
--
ALTER TABLE `ams_banners`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_banners`
--
ALTER TABLE `ams_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
