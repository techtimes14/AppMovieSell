-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2020 at 09:51 PM
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
-- Table structure for table `ams_abouts`
--

CREATE TABLE `ams_abouts` (
  `id` int(11) NOT NULL,
  `title` text,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_abouts`
--

INSERT INTO `ams_abouts` (`id`, `title`, `description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'About  <span class=\"highlight\">MartPlace</span>', '<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi they interdum mollis. Praesent pharetra justo ut sceleris que the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt sanctus est Lorem ipsum dolor sit amet consetetur sadipscing.</p>', 'about_1594497861.jpg', '1', '2020-07-11 20:04:22', '2020-07-11 20:04:22', NULL),
(2, 'MartPlace   <span class=\"highlight\">Mission</span>', '<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi they interdum mollis. Praesent pharetra justo ut sceleris que the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt sanctus est Lorem ipsum dolor sit amet consetetur sadipscing.</p>', 'about_1594497955.jpg', '1', '2020-07-11 20:05:55', '2020-07-11 20:05:55', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_abouts`
--
ALTER TABLE `ams_abouts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_abouts`
--
ALTER TABLE `ams_abouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
