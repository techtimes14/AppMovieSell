-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 10, 2020 at 09:38 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
(1, 'tests', 'banner_1593807172.png', '1', '2020-07-03 14:41:38', '2020-07-03 14:42:53', NULL),
(2, 'Musics', 'banner_1593807414.jpg', '0', '2020-07-03 14:46:54', '2020-07-03 14:48:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_categories`
--

CREATE TABLE `ams_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `allow_format` text CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_croatian_ci NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_categories`
--

INSERT INTO `ams_categories` (`id`, `title`, `allow_format`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(7, 'Movie', 'mp3,mp4,Highquty', 'movie', '1', '2020-06-28 16:42:10', '2020-06-28 17:08:33', NULL),
(8, 'Music', 'mp3,mp4,Highquty', 'music', '1', '2020-06-28 17:08:20', '2020-06-28 17:09:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_cms`
--

CREATE TABLE `ams_cms` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description2` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `page_banner` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT 1 COMMENT '1 = Super Admin',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_cms`
--

INSERT INTO `ams_cms` (`id`, `name`, `slug`, `title`, `description`, `description2`, `image`, `meta_title`, `meta_keyword`, `meta_description`, `page_banner`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'home', 'Home', NULL, NULL, NULL, 'Home', 'Home', 'Home', NULL, 1, '2020-03-13 10:46:34', '2020-05-03 15:28:32'),
(2, 'About Us', 'about-us', 'About Us', NULL, NULL, NULL, 'About Us', 'About Us', 'About Us', NULL, 1, '2020-03-13 10:46:34', '2020-06-17 11:04:26'),
(3, 'Services', 'services', 'Services', NULL, NULL, NULL, 'Services', 'Services', 'Services', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(4, '<h1>Go Through Our <span class=\"highlighted\">Legal Informations</span></h1>', 'legal', 'Legal', '<h3>Privacy Policies</h3>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut volutpat metus, sit amet convallis lectus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque ex nunc, auctor id laoreet eu, hendrerit nec mi. Fusce tempus, erat vel auctor efficitur, orci ante ultrices lacus, a dignissim lorem augue ut nulla. Vivamus dictum metus a mi vulputate, et blandit lorem fermentum. Sed a placerat diam. Suspendisse lacinia enim at aliquam vulputate.</p>\r\n\r\n<p>Pellentesque pretium, tellus sed sagittis feugiat, purus diam eleifend eros, id porta ex enim at metus. Ut sed sagittis augue. Curabitur lobortis tincidunt congue. Vestibulum facilisis mauris et diam pharetra porttitor. Mauris purus arcu, congue quis neque vel, varius ultricies nisl. Praesent mattis purus aliquam risus commodo, id maximus nunc tempus.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Ut non ligula sed nibh efficitur euismod. Aliquam efficitur, sapien sed rhoncus sodales, tellus ligula rhoncus est, ac tempor tortor est maximus ex. Mauris cursus leo at accumsan ullamcorper.</p>\r\n\r\n<p>Pellentesque eget elementum dui. Fusce quis augue aliquet tortor semper auctor. Suspendisse in sodales diam. Pellentesque vitae nisi vel magna vestibulum rhoncus. Phasellus enim augue, placerat semper porttitor in, suscipit ac orci. Aenean sit amet sapien auctor, iaculis metus ut, eleifend odio. Morbi convallis nisi et faucibus lacinia.</p>\r\n\r\n<p>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Cras vitae nibh suscipit, elementum sapien sed, vulputate tellus. Sed malesuada est vel nibh ornare, eget iaculis nibh maximus. Ut consequat arcu sed felis scelerisque, congue ornare ipsum vehicula.</p>\r\n\r\n<p>Ut bibendum commodo tellus, id tempus est scelerisque sit amet. Fusce eu sapien quis lacus rhoncus lobortis sit amet a metus. Vestibulum at laoreet sapien. Sed blandit dui a risus molestie</p>\r\n\r\n<h3>Terms &amp; Conditions</h3>\r\n\r\n<p>Pellentesque pretium, tellus sed sagittis feugiat, purus diam eleifend eros, id porta ex enim at metus. Ut sed sagittis augue. Curabitur lobortis tincidunt congue. Vestibulum facilisis mauris et diam pharetra porttitor. Mauris purus arcu, congue quis neque vel, varius ultricies nisl. Praesent mattis purus aliquam risus commodo, id maximus nunc tempus.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut volutpat metus, sit amet convallis lectus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque ex nunc, auctor id laoreet eu, hendrerit nec mi. Fusce tempus, erat vel auctor efficitur, orci ante ultrices lacus, a dignissim lorem augue ut nulla. Vivamus dictum metus a mi vulputate, et blandit lorem fermentum. Sed a placerat diam. Suspendisse lacinia enim at aliquam vulputate.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Ut non ligula sed nibh efficitur euismod. Aliquam efficitur, sapien sed rhoncus sodales, tellus ligula rhoncus est, ac tempor tortor est maximus ex. Mauris cursus leo at accumsan ullamcorper.</p>\r\n\r\n<p>Pellentesque eget elementum dui. Fusce quis augue aliquet tortor semper auctor. Suspendisse in sodales diam. Pellentesque vitae nisi vel magna vestibulum rhoncus. Phasellus enim augue, placerat semper porttitor in, suscipit ac orci. Aenean sit amet sapien auctor, iaculis metus ut, eleifend odio. Morbi convallis nisi et faucibus lacinia.</p>\r\n\r\n<p>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</p>\r\n\r\n<p>Cras vitae nibh suscipit, elementum sapien sed, vulputate tellus. Sed malesuada est vel nibh ornare, eget iaculis nibh maximus. Ut consequat arcu sed felis scelerisque, congue ornare ipsum vehicula.</p>\r\n\r\n<p>Ut bibendum commodo tellus, id tempus est scelerisque sit amet. Fusce eu sapien quis lacus rhoncus lobortis sit amet a metus. Vestibulum at laoreet sapien. Sed blandit dui a risus molestie</p>\r\n\r\n<h3>Refund Policy</h3>\r\n\r\n<p>Pellentesque pretium, tellus sed sagittis feugiat, purus diam eleifend eros, id porta ex enim at metus. Ut sed sagittis augue. Curabitur lobortis tincidunt congue. Vestibulum facilisis mauris et diam pharetra porttitor. Mauris purus arcu, congue quis neque vel, varius ultricies nisl. Praesent mattis purus aliquam risus commodo, id maximus nunc tempus.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Ut non ligula sed nibh efficitur euismod. Aliquam efficitur, sapien sed rhoncus sodales, tellus ligula rhoncus est, ac tempor tortor est maximus ex. Mauris cursus leo at accumsan ullamcorper.</p>\r\n\r\n<p>Pellentesque eget elementum dui. Fusce quis augue aliquet tortor semper auctor. Suspendisse in sodales diam. Pellentesque vitae nisi vel magna vestibulum rhoncus. Phasellus enim augue, placerat semper porttitor in, suscipit ac orci. Aenean sit amet sapien auctor, iaculis metus ut, eleifend odio. Morbi convallis nisi et faucibus lacinia.</p>', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>', NULL, 'Legal', 'Legal', 'Legal', NULL, 1, '2020-03-13 10:46:34', '2020-07-10 07:17:29'),
(5, 'Contact Us', 'contact-us', 'Contact Us', NULL, NULL, NULL, 'Contact Us', 'Contact Us', 'Contact Us', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(6, 'Market Place', 'market-place', 'All Products', NULL, NULL, NULL, 'Market Place', 'Market Place', 'Market Place', NULL, 1, '2020-07-10 08:23:52', '2020-06-17 10:29:12'),
(7, 'Favourites', 'favourites', 'Favourites', '<p>&nbsp; Re-visit your favourite classes for round two.&nbsp;</p>', NULL, NULL, 'Favourites', 'Favourites', 'Favourites', NULL, 1, NULL, '2020-06-17 10:29:48'),
(8, 'Browse By', 'browse-by', 'Browse By', '<p>&nbsp; Filter your workout by brand, type or muscle group.&nbsp;</p>', NULL, NULL, 'Browse By', 'Browse By', 'Browse By', NULL, 1, NULL, '2020-06-17 10:30:28'),
(9, 'Brand', 'brand', 'Brand Profile', NULL, NULL, NULL, 'Brand', 'Brand', 'Brand', NULL, 1, NULL, NULL),
(10, 'Profile', 'profile', 'Profile', NULL, NULL, NULL, 'Profile', 'Profile', 'Profile', NULL, 1, NULL, NULL),
(11, 'My Favourite', 'my-favourite', 'Favourites', NULL, NULL, NULL, 'My Favourite', 'My Favourite', 'My Favourite ', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_products`
--

CREATE TABLE `ams_products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 NOT NULL,
  `price` float(8,2) DEFAULT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Dumping data for table `ams_products`
--

INSERT INTO `ams_products` (`id`, `category_id`, `title`, `description`, `price`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'ABCD', 'Best movie', 122.00, 'abcd', '1', '2020-07-04 05:49:26', '2020-07-04 06:16:23', NULL),
(2, 2, 'test', '<p>tests</p>', 12.30, 'test', '1', '2020-07-04 05:53:04', '2020-07-04 07:02:07', NULL);

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
(38, 2, 'advance', '23311'),
(39, 2, 'advance1', '2999554545454'),
(41, 2, 'basics', 'gdfhdgg');

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
-- Dumping data for table `ams_product_images`
--

INSERT INTO `ams_product_images` (`id`, `product_id`, `image`, `default_image`) VALUES
(1, 1, 'product-6945061593943264637.png', 'N'),
(4, 1, 'product-7265381593943264645.png', 'Y'),
(6, 2, 'product-1234481593945591543.png', 'N'),
(7, 2, 'product-9788801593945591546.png', 'N'),
(8, 2, 'product-9017271593945591548.png', 'N'),
(9, 2, 'product-6298101593945591550.png', 'N'),
(10, 2, 'product-1623021593945591552.png', 'N'),
(11, 2, 'product-9068931593945591553.jpg', 'Y'),
(12, 2, 'product-4017491593945627605.png', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `ams_roles`
--

CREATE TABLE `ams_roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 = Yes, 0 = No',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
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
-- Table structure for table `ams_services`
--

CREATE TABLE `ams_services` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=inactive,1=active',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_services`
--

INSERT INTO `ams_services` (`id`, `title`, `description`, `image`, `created_at`, `updated_at`, `status`, `deleted_at`) VALUES
(1, 'Curabitur aliquam neque', '<p>Nunc non metus quis dolor ultricies hendrerit eget id risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed id mauris eget elit pharetra auctor sed sed enim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer id neque ipsum. Morbi venenatis pharetra ex ac gravida. Donec vulputate, odio ut porta rutrum, ipsum dui pretium arcu, vitae maximus ipsum lectus vel nunc. Phasellus quis lacinia turpis.</p>', 'service_1594361597.jpg', '2020-07-09 14:19:41', '2020-07-10 06:13:17', '1', NULL),
(2, 'Curabitur aliquam neque1', '<p>Nunc non metus quis dolor ultricies hendrerit eget id risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed id mauris eget elit pharetra auctor sed sed enim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer id neque ipsum. Morbi venenatis pharetra ex ac gravida. Donec vulputate, odio ut porta rutrum, ipsum dui pretium arcu, vitae maximus ipsum lectus vel nunc. Phasellus quis lacinia turpis.</p>', 'service_1594361762.jpg', '2020-07-10 06:16:02', '2020-07-10 06:16:02', '1', NULL),
(3, 'Curabitur aliquam neque2', '<p>Nunc non metus quis dolor ultricies hendrerit eget id risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed id mauris eget elit pharetra auctor sed sed enim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer id neque ipsum. Morbi venenatis pharetra ex ac gravida. Donec vulputate, odio ut porta rutrum, ipsum dui pretium arcu, vitae maximus ipsum lectus vel nunc. Phasellus quis lacinia turpis.</p>', 'service_1594361791.jpg', '2020-07-10 06:16:32', '2020-07-10 06:16:32', '1', NULL),
(4, 'Curabitur aliquam neque3', '<p>Nunc non metus quis dolor ultricies hendrerit eget id risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed id mauris eget elit pharetra auctor sed sed enim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer id neque ipsum. Morbi venenatis pharetra ex ac gravida. Donec vulputate, odio ut porta rutrum, ipsum dui pretium arcu, vitae maximus ipsum lectus vel nunc. Phasellus quis lacinia turpis.</p>', 'service_1594361821.jpg', '2020-07-10 06:17:01', '2020-07-10 06:17:01', '1', NULL),
(5, 'Curabitur aliquam neque4', '<p>Nunc non metus quis dolor ultricies hendrerit eget id risus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Sed id mauris eget elit pharetra auctor sed sed enim. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Integer id neque ipsum. Morbi venenatis pharetra ex ac gravida. Donec vulputate, odio ut porta rutrum, ipsum dui pretium arcu, vitae maximus ipsum lectus vel nunc. Phasellus quis lacinia turpis.</p>', 'service_1594362165.jpg', '2020-07-10 06:17:33', '2020-07-10 06:22:45', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_site_settings`
--

CREATE TABLE `ams_site_settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `googleplus_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rss_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pinterest_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_meta_keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `default_meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `home_short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_site_settings`
--

INSERT INTO `ams_site_settings` (`id`, `from_email`, `to_email`, `website_title`, `website_link`, `facebook_link`, `linkedin_link`, `youtube_link`, `googleplus_link`, `twitter_link`, `rss_link`, `pinterest_link`, `instagram_link`, `default_meta_title`, `default_meta_keywords`, `default_meta_description`, `address`, `phone_no`, `home_short_description`) VALUES
(1, 'support@aazztech.com', 'martplace@yopmail.com', 'Mart Place', 'https://www.google.com', 'https://www.facebook.com', 'https://www.linkedin.com', 'https://www.youtube.com', 'https://www.google.com', 'https://www.twitter.com', NULL, 'https://pinterest.com', 'https://www.instagram.com', 'Mart Place', 'Mart Place', 'Mart Place', '202 New Hampshire Avenue Northwest #100, New York-2573', '+6789-875-2235', 'Nunc placerat mi id nisi interdum they mollis. Praesent pharetra, justo ut scel erisque the mattis, leo quam.');

-- --------------------------------------------------------

--
-- Table structure for table `ams_tags`
--

CREATE TABLE `ams_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_croatian_ci NOT NULL DEFAULT '1' COMMENT '1=Active,0=Inactive',
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Dumping data for table `ams_tags`
--

INSERT INTO `ams_tags` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Movie', 'movie', '1', '2020-06-30 12:07:03', '2020-06-30 12:08:18', NULL),
(2, 'Mp3', 'mp3', '1', '2020-06-30 12:08:41', '2020-06-30 12:08:41', NULL);

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
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `agree` int(11) NOT NULL DEFAULT 1,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` enum('N','G','AU') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N' COMMENT 'N=Normal User, G=Gmail user,AU=Affiliate User',
  `name_on_card` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Name on card',
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Card number',
  `expiry_month` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Expiry month',
  `expiry_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Expiry year',
  `cvv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'CVV number',
  `lastlogintime` int(11) DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auth_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_reset_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `referral_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ams_users`
--

INSERT INTO `ams_users` (`id`, `first_name`, `last_name`, `full_name`, `email`, `user_name`, `phone_no`, `profile_pic`, `password`, `role_id`, `agree`, `postal_code`, `user_type`, `name_on_card`, `card_number`, `expiry_month`, `expiry_year`, `cvv`, `lastlogintime`, `remember_token`, `auth_token`, `password_reset_token`, `status`, `referral_code`, `referred_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super', 'Admin', 'Super Admin', 'admin@example.com', NULL, '9876543210', NULL, '$2y$10$7knL0d07gT0XDVA7W/QQM.6.lDrj5LBPCzH60uGuhzm3k5KgaGyLC', 1, 1, NULL, 'N', NULL, NULL, NULL, NULL, NULL, 1594361456, 'eDyjOvqpdd2LD97gtKeHMVWqlct3EvRzOBp7EKH9jXNzd0czEL6g6i5A2aCw', '$2y$10$ThMAm6g.DxloFDkFXO.XA.CV8MYCqCWjzl1o/EGMKi9jxOu8Hb7RS', NULL, '1', NULL, NULL, '2020-03-13 05:30:26', '2020-07-10 07:37:39', NULL),
(4, 'Harry', 'Potter', 'Harry Potter', 'harry@yopmail.com', 'harry potter', NULL, 'profile_pic_1593957407.jpg', '$2y$10$mDMGZbXbKZf87GiIGF6eUODu993hGezUZKYzCqn8E6UMzYjkzZj2G', NULL, 1, NULL, 'N', 'Harry Potter', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'VXkycllQYkJublVpRUxzYVJNbzIwZz09', 'MERpb0JPUERvVWVOQytkZ0ZjTWowZz09', 1593944852, '', NULL, NULL, '1', NULL, NULL, '2020-07-03 19:57:28', '2020-07-05 14:02:27', NULL),
(5, 'Ron', 'Potter', 'Ron Potter', 'ron@yopmail.com', NULL, NULL, NULL, '$2y$10$xWz9DdKJr15GooONsCQLpun74g5eaZPS7FDL4niQUpz71nwzpo/Va', NULL, 1, '743503', 'AU', 'Ron Potter', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'NzU2TmU1ckJoUGRDM0d3b1pEVTFFUT09', 'eWdUWmRCZUJCelUrUGNIM0VTL2g5dz09', NULL, NULL, NULL, NULL, '1', NULL, NULL, '2020-07-04 17:37:41', '2020-07-04 17:37:41', NULL),
(6, 'Dean', 'Elgar', 'Dean Elgar', 'dean@yopmail.com', 'dean', NULL, NULL, '$2y$10$osjBmcePMg/xZsR9XnWTx.oTVQusjLtI0H./Q/XLo9hfA.NWEXk2.', NULL, 1, NULL, 'N', 'Dean Elgar', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'VXkycllQYkJublVpRUxzYVJNbzIwZz09', 'MERpb0JPUERvVWVOQytkZ0ZjTWowZz09', 1593965008, NULL, NULL, NULL, '1', NULL, NULL, '2020-07-05 15:59:46', '2020-07-05 16:03:28', NULL),
(7, 'Mark', 'Taylor', 'Mark Taylor', 'marktaylor@yopmail.com', 'marktaylor', NULL, NULL, '$2y$10$vxEUtV5mnI1t1teICufaVea0xf3Rgpryt6pn7dJakctK8isvPUFfi', NULL, 1, NULL, 'N', 'Mark Taylor', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'NzU2TmU1ckJoUGRDM0d3b1pEVTFFUT09', 'eWdUWmRCZUJCelUrUGNIM0VTL2g5dz09', NULL, NULL, NULL, NULL, '1', '7VBEMKDJ', NULL, '2020-07-09 19:46:05', '2020-07-09 19:46:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_user_details`
--

CREATE TABLE `ams_user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `author_bio` text DEFAULT NULL,
  `billing_first_name` varchar(255) DEFAULT NULL,
  `billing_last_name` varchar(255) DEFAULT NULL,
  `billing_email` varchar(255) DEFAULT NULL,
  `billing_country` int(11) DEFAULT NULL,
  `billing_address_line_1` varchar(255) DEFAULT NULL,
  `billing_address_line_2` varchar(255) DEFAULT NULL,
  `billing_city` varchar(255) DEFAULT NULL,
  `billing_postal_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_user_details`
--

INSERT INTO `ams_user_details` (`id`, `user_id`, `author_bio`, `billing_first_name`, `billing_last_name`, `billing_email`, `billing_country`, `billing_address_line_1`, `billing_address_line_2`, `billing_city`, `billing_postal_code`) VALUES
(2, 4, 'Test about you', '111', '222', '222@yopmail.com', 2, '1212121212', '84849494', '877878787878787', '77777');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_banners`
--
ALTER TABLE `ams_banners`
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
-- Indexes for table `ams_services`
--
ALTER TABLE `ams_services`
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
-- Indexes for table `ams_user_details`
--
ALTER TABLE `ams_user_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_banners`
--
ALTER TABLE `ams_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_categories`
--
ALTER TABLE `ams_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ams_cms`
--
ALTER TABLE `ams_cms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ams_products`
--
ALTER TABLE `ams_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_product_features`
--
ALTER TABLE `ams_product_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `ams_product_images`
--
ALTER TABLE `ams_product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- AUTO_INCREMENT for table `ams_services`
--
ALTER TABLE `ams_services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ams_site_settings`
--
ALTER TABLE `ams_site_settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ams_tags`
--
ALTER TABLE `ams_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_users`
--
ALTER TABLE `ams_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ams_user_details`
--
ALTER TABLE `ams_user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
