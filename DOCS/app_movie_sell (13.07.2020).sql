-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2020 at 07:58 PM
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
-- Table structure for table `ams_categories`
--

CREATE TABLE `ams_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `allow_format` text CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_categories`
--

INSERT INTO `ams_categories` (`id`, `title`, `allow_format`, `image`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Movies', 'mp3,mp4,Highquty', 'category_1594660733.png', 'movies', '1', '2020-07-13 17:18:54', '2020-07-13 17:18:54', NULL),
(2, 'Files', 'pdf ,csv', 'category_1594660772.png', 'files', '1', '2020-07-13 17:19:33', '2020-07-13 17:19:33', NULL),
(3, 'Apps', 'Andoried/Ios', 'category_1594660794.png', 'apps', '1', '2020-07-13 17:19:54', '2020-07-13 17:20:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_products`
--

CREATE TABLE `ams_products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4,
  `description` text CHARACTER SET utf8mb4 NOT NULL,
  `price` float(8,2) DEFAULT NULL,
  `is_feature` tinyint(1) DEFAULT '0',
  `slug` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Dumping data for table `ams_products`
--

INSERT INTO `ams_products` (`id`, `category_id`, `title`, `description`, `price`, `is_feature`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'De dana Dan', '<p>Test description</p>', 12.30, 1, 'de-dana-dan', '1', '2020-07-13 17:21:21', '2020-07-13 17:21:21', NULL),
(2, 1, 'De dana Dan 2', '<p>Test description two</p>', 12.30, 1, 'de-dana-dan-2', '1', '2020-07-13 17:25:09', '2020-07-13 17:25:09', NULL),
(3, 1, 'De dana Dan 3', '<p>Test description twos</p>', 12.00, 0, 'de-dana-dan-3', '1', '2020-07-13 17:25:40', '2020-07-13 17:25:40', NULL),
(4, 2, 'Lorem Ipsum', '<p>Test description</p>', 12.00, 0, 'lorem-ipsum', '1', '2020-07-13 17:26:38', '2020-07-13 17:26:38', NULL),
(5, 2, 'CSV', '<p>Test description</p>', 12.30, 1, 'csv', '1', '2020-07-13 17:27:14', '2020-07-13 17:27:14', NULL),
(6, 2, 'EXCLE', '<p>Test description three</p>', 12.32, 0, 'excle', '1', '2020-07-13 17:27:57', '2020-07-13 17:27:57', NULL),
(7, 3, 'Android', '<p>Test description&nbsp;</p>', 120.00, 1, 'android', '1', '2020-07-13 17:29:41', '2020-07-13 17:29:41', NULL),
(8, 3, 'Ios', '<p>Test description two&nbsp;</p>', 120.00, 0, 'ios', '1', '2020-07-13 17:30:12', '2020-07-13 17:30:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_product_features`
--

CREATE TABLE `ams_product_features` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `feature_label` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `feature_value` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Dumping data for table `ams_product_features`
--

INSERT INTO `ams_product_features` (`id`, `product_id`, `feature_label`, `feature_value`) VALUES
(1, 1, 'advance', '200'),
(2, 1, 'basic', '100'),
(3, 2, 'advance', '200'),
(4, 2, 'basics', '20'),
(5, 3, 'advance', '200'),
(6, 4, 'pdf', '200'),
(7, 5, 'pdf', 'wp'),
(8, 5, 'csv', 'test'),
(9, 6, 'microsoft', 'word file'),
(10, 7, 'Samsung', 'A5'),
(11, 7, 'MI', 'Redmi'),
(12, 8, 'Apple', 'i10');

-- --------------------------------------------------------

--
-- Table structure for table `ams_product_images`
--

CREATE TABLE `ams_product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `image` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `default_image` enum('Y','N') CHARACTER SET utf32 NOT NULL DEFAULT 'N' COMMENT 'Y=Yes, N=No'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_categories`
--
ALTER TABLE `ams_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_products`
--
ALTER TABLE `ams_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_product_features`
--
ALTER TABLE `ams_product_features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_product_images`
--
ALTER TABLE `ams_product_images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_categories`
--
ALTER TABLE `ams_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ams_products`
--
ALTER TABLE `ams_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ams_product_features`
--
ALTER TABLE `ams_product_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `ams_product_images`
--
ALTER TABLE `ams_product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
