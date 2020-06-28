-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2020 at 04:34 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.13

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
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_banners`
--

INSERT INTO `ams_banners` (`id`, `title`, `short_description`, `image`, `mobile_image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'STREAMFIT', '<span class=\"banner_subhead\">Changing The World of</span>\r\n<em>Online Fitness</em>', 'banner_1588430197.jpg', 'banner_mobile_1588515609.jpg', '1', '2020-05-02 14:36:38', '2020-05-03 14:20:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_boards`
--

CREATE TABLE `ams_boards` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Id from users table',
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_boards`
--

INSERT INTO `ams_boards` (`id`, `title`, `user_id`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'Board 1', 27, '1', '2020-05-08 16:08:01', '2020-05-30 17:47:44', NULL),
(3, 'Board 2', 27, '1', '2020-05-08 16:11:11', '2020-05-30 17:47:53', '2020-05-30 17:47:53'),
(6, 'Board 3', 27, '1', '2020-05-09 04:23:09', '2020-05-30 17:44:05', '2020-05-30 17:44:05'),
(10, 'Board 5', 27, '1', '2020-05-09 20:56:56', '2020-05-30 08:30:55', '2020-05-30 08:30:55'),
(11, 'Olivias Favourite', 31, '1', '2020-05-13 04:49:50', '2020-06-04 00:49:25', '2020-06-04 00:49:25'),
(12, 'My first album', 32, '1', '2020-05-16 02:53:53', '2020-05-16 02:53:53', NULL),
(13, 'This is a long test board let see what happens', 32, '1', '2020-05-16 02:57:15', '2020-05-16 02:57:15', NULL),
(14, 'h', 32, '1', '2020-05-16 08:08:47', '2020-05-16 08:08:47', NULL),
(15, 'hkl', 32, '1', '2020-05-16 08:23:29', '2020-05-16 08:23:29', NULL),
(16, 'New', 33, '1', '2020-05-19 03:33:07', '2020-05-19 03:33:07', NULL),
(17, 'Olivia\'s favourites', 31, '1', '2020-05-24 01:02:07', '2020-05-24 01:02:07', NULL),
(18, 'New', 37, '1', '2020-05-30 05:47:48', '2020-05-30 05:47:48', NULL),
(19, 'Board 2', 27, '1', '2020-05-30 17:48:49', '2020-05-30 17:48:49', NULL),
(20, 'My Fav1', 46, '1', '2020-05-31 18:57:50', '2020-06-01 02:36:19', '2020-06-01 02:36:19'),
(21, 'test', 46, '1', '2020-05-31 19:10:05', '2020-05-31 19:21:12', '2020-05-31 19:21:12'),
(22, 'New', 47, '1', '2020-06-01 03:40:08', '2020-06-01 03:40:08', NULL),
(23, 'hiit', 49, '1', '2020-06-27 14:50:58', '2020-06-27 14:50:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_brands`
--

CREATE TABLE `ams_brands` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_brands`
--

INSERT INTO `ams_brands` (`id`, `title`, `short_description`, `image`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(3, 'Audi', 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero\'s De Finibus Bonorum et Malorum for use in a type specimen book.', 'brand_1588875843.jpg', '1', '2020-05-05 09:15:46', '2020-05-10 05:08:27', NULL),
(4, 'Mercedes', 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero\'s De Finibus Bonorum et Malorum for use in a type specimen book.', 'brand_1588875835.jpg', '1', '2020-05-05 10:07:54', '2020-05-10 05:08:23', NULL),
(5, 'BMW', 'Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero\'s De Finibus Bonorum et Malorum for use in a type specimen book.', 'brand_1588875336.jpg', '1', '2020-05-05 10:08:33', '2020-05-10 05:08:18', NULL),
(6, 'TechTimes', 'test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc test desc', 'brand_1590421140.png', '1', '2020-05-26 04:09:02', '2020-05-26 04:09:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_brand_users`
--

CREATE TABLE `ams_brand_users` (
  `brand_id` int(11) NOT NULL COMMENT 'Id from brands tabel',
  `user_id` int(11) NOT NULL COMMENT 'Id from users table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_brand_users`
--

INSERT INTO `ams_brand_users` (`brand_id`, `user_id`) VALUES
(5, 27),
(4, 21),
(3, 22),
(6, 32);

-- --------------------------------------------------------

--
-- Table structure for table `ams_categories`
--

CREATE TABLE `ams_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_categories`
--

INSERT INTO `ams_categories` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Muscle Group', 'muscle-group', '1', '2020-04-26 16:08:47', '2020-05-29 07:11:56', NULL),
(2, 'Brand', 'brand', '1', '2020-04-30 18:34:18', '2020-05-29 07:12:09', NULL),
(3, 'Type', 'type', '1', '2020-04-30 18:34:26', '2020-05-29 07:12:19', NULL),
(4, 'Another One', 'another-one', '0', '2020-06-05 20:35:18', '2020-06-05 20:38:49', '2020-06-05 20:38:49'),
(5, 'Test Category Today', 'test-category-today', '1', '2020-06-05 20:46:38', '2020-06-05 20:49:23', '2020-06-05 20:49:23'),
(6, 'cat for test', 'cat-for-test', '1', '2020-06-05 20:51:01', '2020-06-05 20:51:01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_cms`
--

CREATE TABLE `ams_cms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `description2` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '1' COMMENT '1 = Super Admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_cms`
--

INSERT INTO `ams_cms` (`id`, `name`, `slug`, `title`, `description`, `description2`, `image`, `meta_title`, `meta_keyword`, `meta_description`, `page_banner`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'home', 'Home', '<p>Home Description&nbsp;Home Description&nbsp;Home Description</p>', NULL, NULL, 'Home', 'Home', 'Home', NULL, 1, '2020-03-13 10:46:34', '2020-05-03 15:28:32'),
(2, 'About Us', 'about-us', 'About <span>Streamfit</span>', '<p>Streamfit is an online community that is bringing people together through accessible at home workouts and fitness classes. Our mission&nbsp;developed from a strong belief that a high motivation, high energy and high participatory class should not be reserved for the elite.&nbsp;</p>\r\n\r\n<p>Become a part of the stream fit family and be 1 class closer to your best, happiest and healthiest self.</p>', NULL, NULL, 'About Us', 'About Us', 'About Us', NULL, 1, '2020-03-13 10:46:34', '2020-06-17 11:04:26'),
(3, 'Terms And Conditions', 'terms-and-conditions', NULL, NULL, NULL, NULL, NULL, 'Terms & Conditions', 'Terms & Conditions', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(4, 'Privacy Policy', 'privacy-policy', NULL, NULL, NULL, NULL, NULL, 'Privacy Policy', 'Privacy Policy', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(5, 'Contact Us', 'contact-us', NULL, NULL, NULL, NULL, NULL, 'Contact Us', 'Contact Us', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(6, 'Trending', 'trending', 'Trending', '<p>Check out what&rsquo;s trending today!</p>', NULL, NULL, 'Trending Videos', 'Trending Videos', 'Trending Videos', NULL, 1, NULL, '2020-06-17 10:29:12'),
(7, 'Favourites', 'favourites', 'Favourites', '<p>&nbsp; Re-visit your favourite classes for round two.&nbsp;</p>', NULL, NULL, 'Favourites', 'Favourites', 'Favourites', NULL, 1, NULL, '2020-06-17 10:29:48'),
(8, 'Browse By', 'browse-by', 'Browse By', '<p>&nbsp; Filter your workout by brand, type or muscle group.&nbsp;</p>', NULL, NULL, 'Browse By', 'Browse By', 'Browse By', NULL, 1, NULL, '2020-06-17 10:30:28'),
(9, 'Brand', 'brand', 'Brand Profile', NULL, NULL, NULL, 'Brand', 'Brand', 'Brand', NULL, 1, NULL, NULL),
(10, 'Profile', 'profile', 'Profile', NULL, NULL, NULL, 'Profile', 'Profile', 'Profile', NULL, 1, NULL, NULL),
(11, 'My Favourite', 'my-favourite', 'Favourites', NULL, NULL, NULL, 'My Favourite', 'My Favourite', 'My Favourite ', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_favourite_videos`
--

CREATE TABLE `ams_favourite_videos` (
  `user_id` int(11) NOT NULL COMMENT 'Id from users table',
  `video_id` int(11) NOT NULL COMMENT 'Id from videos table',
  `board_id` int(11) DEFAULT '0' COMMENT 'Id from board table',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_favourite_videos`
--

INSERT INTO `ams_favourite_videos` (`user_id`, `video_id`, `board_id`, `created_at`, `updated_at`) VALUES
(27, 5, 2, '2020-05-09 08:57:28', '2020-05-09 08:57:28'),
(26, 1, 0, '2020-05-10 15:55:31', '2020-05-10 15:55:31'),
(31, 4, 17, '2020-05-13 04:55:21', '2020-06-04 00:42:52'),
(32, 1, 12, '2020-05-16 02:54:38', '2020-05-16 02:54:38'),
(27, 1, 0, '2020-05-16 07:23:25', '2020-05-16 07:23:25'),
(33, 1, 0, '2020-05-19 03:39:46', '2020-05-19 03:39:46'),
(31, 2, 17, '2020-05-24 01:03:37', '2020-05-24 01:03:37'),
(37, 1, 0, '2020-05-30 05:46:47', '2020-05-30 05:46:47'),
(37, 4, 18, '2020-05-30 05:48:20', '2020-05-30 05:48:20'),
(27, 4, 0, '2020-05-31 18:49:21', '2020-05-31 18:50:50'),
(27, 3, 0, '2020-05-31 18:49:28', '2020-05-31 19:43:04'),
(32, 4, 0, '2020-05-31 19:49:06', '2020-05-31 19:56:42'),
(46, 6, 0, '2020-06-01 03:13:54', '2020-06-01 03:13:54'),
(47, 4, 22, '2020-06-01 03:40:17', '2020-06-01 03:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `ams_roles`
--

CREATE TABLE `ams_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Yes, 0 = No',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_roles`
--

INSERT INTO `ams_roles` (`id`, `name`, `slug`, `is_admin`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'super-admin', '1', '2019-05-29 19:29:09', '2019-05-29 19:29:09', NULL),
(2, 'Customer', 'customer', '0', '2020-04-27 19:52:06', '2020-04-27 19:53:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_role_pages`
--

CREATE TABLE `ams_role_pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `routeName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_role_pages`
--

INSERT INTO `ams_role_pages` (`id`, `routeName`) VALUES
(1, 'admin.login'),
(2, 'admin.category.list'),
(3, 'admin.user.list'),
(4, 'admin.user.add'),
(5, 'admin.user.edit'),
(6, 'admin.user.change-status'),
(7, 'admin.user.delete'),
(8, 'admin.category.add'),
(9, 'admin.category.status'),
(10, 'admin.product.list'),
(11, 'admin.product.edit'),
(12, 'admin.product.status'),
(13, 'admin.product.request-list'),
(14, 'admin.coupon.list'),
(15, 'admin.CMS.list'),
(16, 'admin.bids.list'),
(17, 'admin.product.add'),
(18, 'admin.coupon.add'),
(19, 'admin.product.delete'),
(20, 'admin.product.request-approve'),
(21, 'admin.category.edit'),
(22, 'admin.user.list-frontend'),
(23, 'admin.user.add-frontend-user'),
(24, 'admin.user.list-adminend'),
(25, 'admin.user.add-adminend-user'),
(26, 'admin.user.edit-frontend-user'),
(27, 'admin.user.edit-adminend-user'),
(28, 'admin.CMS.edit'),
(29, 'admin.category.delete'),
(30, 'admin.category.change-status'),
(31, 'admin.product.change-status'),
(32, 'admin.product.request-status'),
(33, 'admin.unit.list'),
(34, 'admin.unit.add'),
(35, 'admin.unit.edit'),
(36, 'admin.unit.change-status'),
(37, 'admin.unit.delete'),
(38, 'admin.coupon.edit'),
(39, 'admin.coupon.change-status'),
(40, 'admin.coupon.delete'),
(41, 'admin.faq.list'),
(42, 'admin.faq.add'),
(43, 'admin.faq.edit'),
(44, 'admin.faq.change-status'),
(45, 'admin.faq.delete'),
(46, 'admin.bid.list'),
(47, 'admin.bid.change-status'),
(48, 'admin.bid.details'),
(49, 'admin.contract.list'),
(50, 'admin.newsletter.list'),
(51, 'admin.user.wholesalers-request-list'),
(52, 'admin.user.show-whlolesaler-doc'),
(53, 'admin.user.show-contractor-doc'),
(54, 'admin.user.change-wholesaler-status'),
(55, 'admin.user.contractors-request-list'),
(56, 'admin.banners.upload-banners'),
(57, 'admin.banners.list'),
(58, 'admin.order.details'),
(59, 'admin.bid.update-bid-winner'),
(60, 'admin.contactus.contact-us-details'),
(61, 'admin.contactus.list'),
(62, 'admin.user.change-contractor-status'),
(63, 'admin.order.list'),
(64, 'admin.payout.list'),
(65, 'admin.payout.monthly-payout-export'),
(66, 'admin.payout.payout-export'),
(67, 'admin.payout.whole-payout-export'),
(68, 'admin.payout.details-payout'),
(69, 'admin.payout.details'),
(70, 'admin.user.wholesaler-product-change-status'),
(71, 'admin.user.reject-wholesaler-status'),
(72, 'admin.user.reject-contractor-status');

-- --------------------------------------------------------

--
-- Table structure for table `ams_role_permissions`
--

CREATE TABLE `ams_role_permissions` (
  `role_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_role_permissions`
--

INSERT INTO `ams_role_permissions` (`role_id`, `page_id`) VALUES
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 16),
(7, 17),
(7, 10),
(7, 6),
(7, 3),
(7, 2),
(8, 12),
(8, 20),
(8, 3),
(6, 23),
(6, 24),
(6, 25),
(6, 26),
(6, 27),
(6, 6),
(6, 7),
(6, 15),
(6, 28),
(6, 15),
(6, 15),
(6, 15),
(5, 22),
(10, 22),
(10, 24),
(10, 23),
(10, 25),
(10, 26),
(10, 27),
(10, 7),
(10, 6),
(10, 29),
(10, 30),
(10, 21),
(10, 8),
(10, 2),
(10, 10),
(10, 17),
(10, 11),
(10, 31),
(10, 19),
(10, 13),
(10, 32),
(10, 33),
(10, 34),
(10, 35),
(10, 36),
(10, 37),
(10, 14),
(10, 18),
(10, 38),
(10, 39),
(10, 40),
(10, 41),
(10, 42),
(10, 43),
(10, 44),
(10, 45),
(10, 15),
(10, 28),
(10, 46),
(10, 47),
(10, 48),
(10, 49),
(10, 50),
(5, 51),
(6, 52),
(6, 54),
(6, 55),
(6, 51),
(11, 3),
(11, 2),
(11, 8),
(11, 21),
(11, 30),
(11, 29),
(12, 3),
(12, 51),
(12, 52),
(12, 54),
(12, 55),
(12, 53),
(12, 62),
(12, 2),
(12, 8),
(12, 21),
(12, 30),
(12, 29),
(12, 10),
(12, 11),
(12, 31),
(12, 19),
(12, 13),
(12, 32),
(12, 24),
(12, 23),
(12, 25),
(12, 26),
(12, 27),
(12, 6),
(12, 7),
(12, 70),
(12, 33),
(12, 34),
(12, 35),
(12, 36),
(12, 37),
(12, 40),
(12, 14),
(12, 39),
(12, 18),
(12, 38),
(12, 63),
(12, 15),
(12, 28),
(12, 41),
(12, 42),
(12, 43),
(12, 44),
(12, 45),
(12, 64),
(12, 69),
(12, 67),
(12, 65),
(12, 66),
(12, 46),
(12, 47),
(12, 59),
(12, 48),
(12, 61),
(12, 60),
(12, 57),
(12, 49),
(12, 56),
(12, 50),
(8, 10),
(6, 3),
(12, 22),
(9, 3),
(12, 68),
(9, 10),
(11, 10),
(6, 22),
(6, 2),
(6, 10);

-- --------------------------------------------------------

--
-- Table structure for table `ams_site_settings`
--

CREATE TABLE `ams_site_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `googleplus_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rss_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinterest_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `default_meta_description` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_short_description` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_site_settings`
--

INSERT INTO `ams_site_settings` (`id`, `from_email`, `to_email`, `website_title`, `website_link`, `facebook_link`, `linkedin_link`, `youtube_link`, `googleplus_link`, `twitter_link`, `rss_link`, `pinterest_link`, `instagram_link`, `default_meta_title`, `default_meta_keywords`, `default_meta_description`, `address`, `phone_no`, `home_short_description`) VALUES
(1, 'olivia@streamfit.ca', 'olivia@streamfit.ca', 'Olivia @ StreamFit', 'https://www.streamfit.ca', NULL, NULL, 'https://www.youtube.com/channel/UCn8wkUp38QDjl7iH-6NwyRg?view_as=subscriber', NULL, NULL, NULL, NULL, 'www.instagram.com/Streamfitca', 'Stream Fit', 'Stream Fit', 'Stream Fit', 'Toronto, Ontario', NULL, 'The Pride of a good sweat and the community of a fitness class');

-- --------------------------------------------------------

--
-- Table structure for table `ams_tags`
--

CREATE TABLE `ams_tags` (
  `id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_tags`
--

INSERT INTO `ams_tags` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Tag 1', 'tag-1', '1', '2020-04-26 15:29:39', '2020-05-05 20:29:23', NULL),
(2, 'Tag 2', 'tag-2', '1', '2020-04-26 16:05:22', '2020-05-05 20:29:26', NULL),
(3, 'Tag 3', 'tag-3', '1', '2020-04-30 18:35:00', '2020-05-05 20:29:29', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_users`
--

CREATE TABLE `ams_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('M','F','NB') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'M=Male, F=Female, NB=Non-Binary',
  `role_id` int(11) DEFAULT NULL,
  `agree` int(11) NOT NULL DEFAULT '1',
  `user_type` enum('N','G') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'N=Normal User, G=Gmail user',
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastlogintime` int(11) DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_users`
--

INSERT INTO `ams_users` (`id`, `first_name`, `last_name`, `full_name`, `email`, `phone_no`, `profile_pic`, `password`, `gender`, `role_id`, `agree`, `user_type`, `google_id`, `lastlogintime`, `remember_token`, `auth_token`, `password_reset_token`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super', 'Admin', 'Super Admin', 'admin@streamfit.com', '9876543210', NULL, '$2y$10$7knL0d07gT0XDVA7W/QQM.6.lDrj5LBPCzH60uGuhzm3k5KgaGyLC', 'M', 1, 1, 'N', NULL, 1593272155, 'ZkQVTBjP9Y6FA6KJqG1uyUQKEwtRdhcKMeCGkmStEHJdDBhpg3UnVUI5KbtE', '$2y$10$ThMAm6g.DxloFDkFXO.XA.CV8MYCqCWjzl1o/EGMKi9jxOu8Hb7RS', NULL, '1', '2020-03-13 05:30:26', '2020-06-27 16:10:33', NULL),
(20, 'John', 'Doe', 'John Doe', 'john@yopmail.com', NULL, NULL, '$2y$10$32obolO5hROkuf/0eB7gaukHyk3MqbrABI6wTzeVokG3YX/EpXoYm', 'M', NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-02 07:02:46', '2020-05-02 07:02:46', NULL),
(21, 'Mark', 'Boucher', 'Mark Boucher', 'mark@yopmail.com', NULL, NULL, '$2y$10$w9Ax/FtQ28d8wbYoQHp0TuTEAi/gb2VIVt3gseT1C5YpRN7Cx1kXe', 'M', NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-02 07:06:38', '2020-05-02 07:06:38', NULL),
(22, 'Steve', 'Boucher', 'Steve Boucher', 'steve@yopmail.com', NULL, NULL, '$2y$10$RHbbxa/0EtxEblyuxUp0keFyQjmpbfHdBTaE1fKk5.gt4Zj7haroK', 'M', NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-02 07:19:34', '2020-05-02 07:19:34', NULL),
(23, 'Adam', 'Gilchrist', 'Adam Gilchrist', 'adam@yopmail.com', NULL, NULL, '$2y$10$x05jzaA8ZgQNFvMqiahF6.HCotTjweZfO5ZKQa5HNnEyaKgAQXnpe', 'M', NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-02 07:22:28', '2020-05-02 07:22:28', NULL),
(24, 'Raj', 'Das', 'Raj Das', 'raj@yopmail.com', NULL, NULL, '$2y$10$1QtqOhNdTs6gjIL/kmw.6uiShH3EmHTUEoLY3zESOFq7lF5UnMh7e', 'M', NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-02 09:13:51', '2020-05-02 09:13:51', NULL),
(25, 'Randip', 'Kar', 'Randip Kar', 'randip@yopmail.com', NULL, NULL, '$2y$10$lHDz1Tl3R8VEN6FW1W57k.hFEMRHik4IU56K/snwut98ZwISexx96', 'M', NULL, 1, 'N', NULL, 1589530055, NULL, NULL, '1589529858', '1', '2020-05-02 11:26:18', '2020-05-15 20:37:35', NULL),
(26, 'Dove', 'Man', 'Dove Man', 'dove@streamfit.com', NULL, NULL, '$2y$10$4S.7aNuDnuUgqqOVA.bCIewY/TczMU3cuSsADXAgdBPvQuX4PJr9m', 'M', NULL, 1, 'N', NULL, 1588525195, NULL, NULL, NULL, '1', '2020-05-02 11:27:55', '2020-05-03 18:07:27', NULL),
(27, 'Harry', 'Potter', 'Harry Potter', 'harrypotter@yopmail.com', '9874653299', 'profile_pic_1591817925.jpg', '$2y$10$zbGTETNVWSI9oGv.a0C4XuJ3rg61yGQVMzMSOvKvklNi4msYVcO26', 'M', NULL, 1, 'N', NULL, 1591817895, NULL, NULL, NULL, '1', '2020-05-02 11:52:23', '2020-06-11 08:08:50', NULL),
(28, 'Hello', 'Hi', 'Hello Hi', 'hello@yopmail.com', NULL, NULL, '$2y$10$8h7NTeexekA6f.G94I1sH.sE6WRS0jgFbZR.bt2XPQZU0UqBZbcDm', NULL, NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-07 16:15:35', '2020-05-07 16:15:35', NULL),
(29, 'dsf', 'dsf', 'Dsf dsf', 'dsfdsf@yopmail.com', NULL, NULL, '$2y$10$sitFxCtmj282aH3fFlhole51igMsXfpYduprmuCF8nMJZgH78pcRu', NULL, NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-07 17:40:03', '2020-05-07 17:40:03', NULL),
(30, 'sd', 'sads', 'Sd sads', 'admin123@streamfit.com', NULL, NULL, '$2y$10$T2gI8eG1tHTcD3bGLam.2eFn0iqhrX6x18rgLb/ZGyEkpX3lms0U2', NULL, NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '1', '2020-05-07 17:41:12', '2020-05-07 17:41:12', NULL),
(31, 'Olivia', 'Miletic', 'Olivia Miletic', 'olivia.miletic25@gmail.com', '5191234567', NULL, '$2y$10$F0d7ijF2DvNtmK8XUNV76uG0lXIjBQso6bZ8zOFYTxbaO6uxrnuWK', 'F', NULL, 1, 'G', '116543979636175475674', 1591185983, NULL, NULL, NULL, '1', '2020-05-13 04:44:21', '2020-06-04 00:36:23', NULL),
(32, 'Sukanta', 'Sarkar', 'Sukanta Sarkar', 'sukanta.info2@gmail.com', '1231312333', 'profile_pic_1591855235.JPG', '$2y$10$AAmqjaFFBmOivXyOvxEk1edcDdlkleWbvjJTp2d7ggLJreUwHJCWq', 'M', NULL, 1, 'G', '111394632041920666287', 1591855180, NULL, NULL, '1591950278', '1', '2020-05-16 02:50:03', '2020-06-12 20:54:38', NULL),
(33, 'Eric', 'Henriques', 'Eric Henriques', 'eghyyz@gmail.com', NULL, NULL, '$2y$10$SqlNvR9Ckl2a3VHLj92a9OhiNbvedFi7yPI1ym3XsGINdjUiMGRIG', NULL, NULL, 1, 'N', NULL, 1589814632, NULL, NULL, NULL, '1', '2020-05-19 03:32:23', '2020-05-19 03:40:32', NULL),
(37, 'Esha', 'Saha', 'Esha Saha', 'esha.extra3331@gmail.com', '9632587410', NULL, '$2y$10$5XV9Igpv8VsnkPtBHi4EteFrzxMlAJsbBTT38tqXKBUMsSKXcL8y2', 'F', NULL, 1, 'N', NULL, 1590772563, NULL, NULL, NULL, '1', '2020-05-30 05:45:37', '2020-05-30 05:56:15', NULL),
(45, 'Sanjay', 'Karmakar', 'Sanjay Karmakar', 'sanjoy86.jk@gmail.com', NULL, NULL, '$2y$10$K7aeEiBh7mEsKxWTh8eAxur.Uw0h8B7MPgrmB1RLIfOAEnUCr4uC.', NULL, NULL, 1, 'G', '101766056361128350948', 1590777050, NULL, NULL, NULL, '0', '2020-05-30 06:50:54', '2020-05-30 07:00:50', NULL),
(46, 'Tech', 'Times', 'Tech Times', 'techtimes14@gmail.com', '34567897654', NULL, '$2y$10$cRuQGUaKFYQskNWA0pVlpuErHCR/ynOFDUHyfRhxRd5ivYa.y16BW', 'M', NULL, 1, 'G', '103917521745903454278', 1591200472, NULL, NULL, NULL, '0', '2020-05-31 18:53:50', '2020-06-28 04:08:12', NULL),
(47, 'Eric', 'Henriques', 'Eric Henriques', 'eghtester0205@gmail.com', NULL, NULL, '$2y$10$x/qUmkrrUcddZ1vymG9Z/OIEbkKOIcHEylgh9noqVxMjPU.PVmeJq', NULL, NULL, 1, 'G', '100212264656771206982', 1590937798, NULL, NULL, '1590937791', '1', '2020-06-01 03:39:51', '2020-06-01 03:39:58', NULL),
(48, 'Eric', 'Henriques', 'Eric Henriques', 'ericghenriques@gmail.com', NULL, NULL, '$2y$10$/X1fmdhiVxlML/9a2rm68.cfF/f1f6lof9FDU7hSyKxBuj9iO2Lyy', NULL, NULL, 1, 'G', '108671045198999973946', 1591186957, NULL, NULL, '1591186951', '1', '2020-06-04 00:52:31', '2020-06-04 00:52:37', NULL),
(49, 'Margaret', 'Henriques', 'Margaret Henriques', 'margilhenriques@gmail.com', NULL, NULL, '$2y$10$UEuB3W/u4XpkAGpdJ6LFTuEkvkvJgIIXgw0eOdqeLcbrdUOOCes8O', NULL, NULL, 1, 'G', '118396589352723678963', 1593224427, NULL, NULL, '1593224420', '1', '2020-06-27 14:50:20', '2020-06-27 14:50:27', NULL),
(50, NULL, NULL, 'Sukanta Sarkar', 'adminsdadasd@streamfit.com', '09477482876', NULL, '$2y$10$pPASA5Ja4RZAuRMjXFwBD.XDLty/x1N2JHa1U7X03DT.3oaJShj5K', NULL, NULL, 1, 'N', NULL, NULL, NULL, NULL, NULL, '0', '2020-06-28 05:48:10', '2020-06-28 05:48:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_videos`
--

CREATE TABLE `ams_videos` (
  `id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `video_title` text,
  `image_default_url` varchar(255) DEFAULT NULL COMMENT '120px X 90px',
  `image_medium_url` varchar(255) DEFAULT NULL COMMENT '320px X 180px',
  `image_high_url` varchar(255) DEFAULT NULL COMMENT '480px X 360px',
  `image_standard_url` varchar(255) DEFAULT NULL COMMENT '640px X 480px',
  `image_maxres_url` varchar(255) DEFAULT NULL COMMENT '1280px X 720px',
  `video_duration` int(11) NOT NULL COMMENT 'In seconds',
  `view_count` bigint(11) DEFAULT '0' COMMENT 'Views count',
  `like_count` bigint(11) DEFAULT '0' COMMENT 'Like count',
  `dislike_count` bigint(11) DEFAULT '0' COMMENT 'Dislike count',
  `favorite_count` bigint(11) DEFAULT '0' COMMENT 'Favorite count',
  `comment_count` bigint(11) DEFAULT '0' COMMENT 'Comment count',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_videos`
--

INSERT INTO `ams_videos` (`id`, `title`, `video_url`, `video_id`, `video_title`, `image_default_url`, `image_medium_url`, `image_high_url`, `image_standard_url`, `image_maxres_url`, `video_duration`, `view_count`, `like_count`, `dislike_count`, `favorite_count`, `comment_count`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Video 1', 'https://www.youtube.com/watch?v=fcN37TxBE_s', 'fcN37TxBE_s', 'Fat Burning Cardio Workout - 37 Minute Fitness Blender Cardio Workout at Home', 'https://i.ytimg.com/vi/fcN37TxBE_s/default.jpg', 'https://i.ytimg.com/vi/fcN37TxBE_s/mqdefault.jpg', 'https://i.ytimg.com/vi/fcN37TxBE_s/hqdefault.jpg', 'https://i.ytimg.com/vi/fcN37TxBE_s/sddefault.jpg', 'https://i.ytimg.com/vi/fcN37TxBE_s/maxresdefault.jpg', 2230, 61565145, 502254, 12447, 0, 25469, '1', '2020-05-03 15:12:47', '2020-05-03 18:19:58', NULL),
(2, 'Video 2', 'https://www.youtube.com/watch?v=IFQmOZqvtWg', 'IFQmOZqvtWg', '30-Minute No-Equipment Full-Body Toning Workout', 'https://i.ytimg.com/vi/IFQmOZqvtWg/default.jpg', 'https://i.ytimg.com/vi/IFQmOZqvtWg/mqdefault.jpg', 'https://i.ytimg.com/vi/IFQmOZqvtWg/hqdefault.jpg', 'https://i.ytimg.com/vi/IFQmOZqvtWg/sddefault.jpg', 'https://i.ytimg.com/vi/IFQmOZqvtWg/maxresdefault.jpg', 1804, 1343760, 19107, 340, 0, 765, '1', '2020-05-03 15:13:22', '2020-05-07 19:15:23', NULL),
(3, 'Video 3', 'https://www.youtube.com/watch?v=qB5G6NOh-ww', 'qB5G6NOh-ww', 'THE GYM BEATS Vol.4 (Nonstop-Megamix), BEST WORKOUT MUSIC,FITNESS,MOTIVATION,SPORTS,AEROBIC,CARDIO', 'https://i.ytimg.com/vi/qB5G6NOh-ww/default.jpg', 'https://i.ytimg.com/vi/qB5G6NOh-ww/mqdefault.jpg', 'https://i.ytimg.com/vi/qB5G6NOh-ww/hqdefault.jpg', 'https://i.ytimg.com/vi/qB5G6NOh-ww/sddefault.jpg', 'https://i.ytimg.com/vi/qB5G6NOh-ww/maxresdefault.jpg', 1768, 11330254, 67518, 8587, 0, 1208, '1', '2020-05-03 15:13:47', '2020-05-07 19:15:29', NULL),
(4, 'Video 4', 'https://www.youtube.com/watch?v=UBMk30rjy0o', 'UBMk30rjy0o', '20 MIN FULL BODY WORKOUT // No Equipment | Pamela Reif', 'https://i.ytimg.com/vi/UBMk30rjy0o/default.jpg', 'https://i.ytimg.com/vi/UBMk30rjy0o/mqdefault.jpg', 'https://i.ytimg.com/vi/UBMk30rjy0o/hqdefault.jpg', 'https://i.ytimg.com/vi/UBMk30rjy0o/sddefault.jpg', 'https://i.ytimg.com/vi/UBMk30rjy0o/maxresdefault.jpg', 1221, 27101464, 460215, 6553, 0, 11495, '1', '2020-05-03 15:14:11', '2020-05-03 18:20:18', NULL),
(5, 'Video 5', 'https://www.youtube.com/watch?v=EIdkOWmQaaw', 'EIdkOWmQaaw', 'Lorem Ipsum Meaning', 'https://i.ytimg.com/vi/EIdkOWmQaaw/default.jpg', 'https://i.ytimg.com/vi/EIdkOWmQaaw/mqdefault.jpg', 'https://i.ytimg.com/vi/EIdkOWmQaaw/hqdefault.jpg', 'https://i.ytimg.com/vi/EIdkOWmQaaw/hqdefault.jpg', 'https://i.ytimg.com/vi/EIdkOWmQaaw/hqdefault.jpg', 233, 16072, 53, 53, 0, 22, '0', '2020-05-03 15:14:40', '2020-05-07 19:12:39', NULL),
(6, 'Workout from home', 'https://www.youtube.com/watch?v=ofTiKY3hYdE', 'ofTiKY3hYdE', '10 Minute Home Workout For 6Pack Abs + Fat Burning', 'https://i.ytimg.com/vi/ofTiKY3hYdE/default.jpg', 'https://i.ytimg.com/vi/ofTiKY3hYdE/mqdefault.jpg', 'https://i.ytimg.com/vi/ofTiKY3hYdE/hqdefault.jpg', 'https://i.ytimg.com/vi/ofTiKY3hYdE/sddefault.jpg', 'https://i.ytimg.com/vi/ofTiKY3hYdE/maxresdefault.jpg', 833, 4110666, 155692, 1228, 0, 4641, '1', '2020-05-26 04:10:14', '2020-05-26 04:10:14', NULL),
(7, 'Test video', 'https://www.youtube.com/watch?v=P_SZpxUx3xw', 'P_SZpxUx3xw', 'NO GYM FULL BODY WORKOUT AT HOME | BEST HOME EXERCISES', 'https://i.ytimg.com/vi/P_SZpxUx3xw/default.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/mqdefault.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/hqdefault.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/sddefault.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/maxresdefault.jpg', 763, 5103288, 152471, 4706, 0, 5235, '1', '2020-06-05 20:37:36', '2020-06-05 20:38:21', '2020-06-05 20:38:21'),
(8, 'test video test', 'https://www.youtube.com/watch?v=P_SZpxUx3xw', 'P_SZpxUx3xw', 'NO GYM FULL BODY WORKOUT AT HOME | BEST HOME EXERCISES', 'https://i.ytimg.com/vi/P_SZpxUx3xw/default.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/mqdefault.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/hqdefault.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/sddefault.jpg', 'https://i.ytimg.com/vi/P_SZpxUx3xw/maxresdefault.jpg', 763, 5103293, 152469, 4706, 0, 5235, '1', '2020-06-05 20:48:14', '2020-06-05 20:48:14', NULL),
(9, 'Yoga Routine For Strength & Flexibility', 'https://www.youtube.com/watch?v=b6IFkfSj4Jo', 'b6IFkfSj4Jo', 'Yoga Routine For Strength & Flexibility | ALL LEVELS (Follow Along)', 'https://i.ytimg.com/vi/b6IFkfSj4Jo/default.jpg', 'https://i.ytimg.com/vi/b6IFkfSj4Jo/mqdefault.jpg', 'https://i.ytimg.com/vi/b6IFkfSj4Jo/hqdefault.jpg', 'https://i.ytimg.com/vi/b6IFkfSj4Jo/hqdefault.jpg', 'https://i.ytimg.com/vi/b6IFkfSj4Jo/hqdefault.jpg', 2251, 1306300, 25086, 428, 0, 739, '1', '2020-06-05 20:52:14', '2020-06-05 20:52:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_video_brands`
--

CREATE TABLE `ams_video_brands` (
  `video_id` int(11) NOT NULL COMMENT 'Id from videos table	',
  `brand_id` int(11) NOT NULL COMMENT 'Id from brands table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_video_brands`
--

INSERT INTO `ams_video_brands` (`video_id`, `brand_id`) VALUES
(5, 3),
(5, 5),
(2, 5),
(3, 5),
(6, 6),
(7, 6),
(9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `ams_video_categories`
--

CREATE TABLE `ams_video_categories` (
  `video_id` int(11) NOT NULL COMMENT 'Id from videos table',
  `category_id` int(11) NOT NULL COMMENT 'Id from categories table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_video_categories`
--

INSERT INTO `ams_video_categories` (`video_id`, `category_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(4, 3),
(5, 2),
(2, 2),
(2, 3),
(3, 1),
(3, 2),
(3, 3),
(6, 3),
(7, 4),
(9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `ams_video_tags`
--

CREATE TABLE `ams_video_tags` (
  `video_id` int(11) NOT NULL COMMENT 'Id from videos table',
  `tag_id` int(11) NOT NULL COMMENT 'Id from tags table'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_video_tags`
--

INSERT INTO `ams_video_tags` (`video_id`, `tag_id`) VALUES
(1, 1),
(1, 3),
(4, 1),
(4, 2),
(5, 1),
(5, 1),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 3),
(6, 1),
(6, 2),
(7, 2),
(9, 1),
(9, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_banners`
--
ALTER TABLE `ams_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_boards`
--
ALTER TABLE `ams_boards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_brands`
--
ALTER TABLE `ams_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_categories`
--
ALTER TABLE `ams_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_cms`
--
ALTER TABLE `ams_cms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_roles`
--
ALTER TABLE `ams_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_role_pages`
--
ALTER TABLE `ams_role_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_site_settings`
--
ALTER TABLE `ams_site_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_tags`
--
ALTER TABLE `ams_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_users`
--
ALTER TABLE `ams_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_videos`
--
ALTER TABLE `ams_videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_banners`
--
ALTER TABLE `ams_banners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ams_boards`
--
ALTER TABLE `ams_boards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `ams_brands`
--
ALTER TABLE `ams_brands`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ams_categories`
--
ALTER TABLE `ams_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ams_cms`
--
ALTER TABLE `ams_cms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ams_roles`
--
ALTER TABLE `ams_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_role_pages`
--
ALTER TABLE `ams_role_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `ams_site_settings`
--
ALTER TABLE `ams_site_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ams_tags`
--
ALTER TABLE `ams_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ams_users`
--
ALTER TABLE `ams_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `ams_videos`
--
ALTER TABLE `ams_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
