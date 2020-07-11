-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2020 at 09:39 PM
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
(1, 'tests', '<h1>Welcome to MartPlace!</h1>\r\n\r\n<p>Lorem ipsum dolor adipisicing elit</p>', 'about_1594496189.png', '1', '2020-07-11 19:36:30', '2020-07-11 19:36:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_contacts`
--

CREATE TABLE `ams_contacts` (
  `id` int(11) NOT NULL,
  `full_name` text,
  `first_name` text,
  `last_name` text,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `subject` text,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ams_contact_widgets`
--

CREATE TABLE `ams_contact_widgets` (
  `id` int(11) NOT NULL,
  `title` text,
  `description` text,
  `icon_class` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=inactive,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_contact_widgets`
--

INSERT INTO `ams_contact_widgets` (`id`, `title`, `description`, `icon_class`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(2, 'Office Address', '202 New Hampshire Avenue , Northwest #100, New York-2573', 'tiles__icon lnr lnr-map-marker', '2020-07-11 14:01:14', '2020-07-11 14:01:14', NULL, '1'),
(3, 'Phone Number', '1-800-643-4500 <br>  \r\n1-800-643-4500', 'tiles__icon lnr lnr-phone', '2020-07-11 14:03:11', '2020-07-11 16:25:49', NULL, '1'),
(4, 'Email', 'support@aazztech.com\r\nsupport@aazztech.com', 'tiles__icon lnr lnr-inbox', '2020-07-11 14:03:53', '2020-07-11 14:22:11', NULL, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_abouts`
--
ALTER TABLE `ams_abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_contacts`
--
ALTER TABLE `ams_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_contact_widgets`
--
ALTER TABLE `ams_contact_widgets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_abouts`
--
ALTER TABLE `ams_abouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ams_contacts`
--
ALTER TABLE `ams_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ams_contact_widgets`
--
ALTER TABLE `ams_contact_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
