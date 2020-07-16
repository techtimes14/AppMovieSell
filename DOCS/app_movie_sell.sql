-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2020 at 10:36 AM
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
-- Table structure for table `ams_abouts`
--

CREATE TABLE `ams_abouts` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
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
(1, 'About  <span class=\"highlight\">MartPlace</span>', '<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi they interdum mollis. Praesent pharetra justo ut sceleris que the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt sanctus est Lorem ipsum dolor sit amet consetetur sadipscing.</p>', 'about_1594497861.jpg', '1', '2020-07-11 14:34:22', '2020-07-11 14:34:22', NULL),
(2, 'MartPlace   <span class=\"highlight\">Mission</span>', '<p>Nunc placerat mi id nisi interdum mollis. Praesent pharetra justo ut sceler isque the mattis, leo quam aliquet congue this there placerat mi id nisi they interdum mollis. Praesent pharetra justo ut sceleris que the mattis, leo quam aliquet. Nunc placer atmi id nisi interdum mollis quam. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt sanctus est Lorem ipsum dolor sit amet consetetur sadipscing.</p>', 'about_1594497955.jpg', '1', '2020-07-11 14:35:55', '2020-07-11 14:35:55', NULL);

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
(2, 'Musics', 'banner_1593807414.jpg', '0', '2020-07-03 14:46:54', '2020-07-14 19:56:43', '2020-07-14 19:56:43');

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
(1, 'Movies', 'mp3,mp4,Highquty', 'category_1594660733.png', 'movies', '1', '2020-07-13 11:48:54', '2020-07-13 11:48:54', NULL),
(2, 'Files', 'pdf ,csv', 'category_1594660772.png', 'files', '1', '2020-07-13 11:49:33', '2020-07-13 11:49:33', NULL),
(3, 'Apps', 'Andoried/Ios', 'category_1594660794.png', 'apps', '1', '2020-07-13 11:49:54', '2020-07-13 11:50:06', NULL);

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
(1, 'Why Choose <span class=\"highlighted\">MartPlace</span>', 'home', 'Home', '<p>Laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis fugats. Lid est laborum dolo rumes fugats untras.</p>', NULL, NULL, 'Home', 'Home', 'Home', NULL, 1, '2020-03-13 10:46:34', '2020-05-03 15:28:32'),
(2, 'About Us', 'about-us', 'About Us', NULL, NULL, NULL, 'About Us', 'About Us', 'About Us', NULL, 1, '2020-03-13 10:46:34', '2020-06-17 11:04:26'),
(3, 'Services', 'services', 'Services', NULL, NULL, NULL, 'Services', 'Services', 'Services', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(4, '<h1>Go Through Our <span class=\"highlighted\">Legal Informations</span></h1>', 'legal', 'Legal', '<h3>Privacy Policies</h3>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut volutpat metus, sit amet convallis lectus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque ex nunc, auctor id laoreet eu, hendrerit nec mi. Fusce tempus, erat vel auctor efficitur, orci ante ultrices lacus, a dignissim lorem augue ut nulla. Vivamus dictum metus a mi vulputate, et blandit lorem fermentum. Sed a placerat diam. Suspendisse lacinia enim at aliquam vulputate.</p>\r\n\r\n<p>Pellentesque pretium, tellus sed sagittis feugiat, purus diam eleifend eros, id porta ex enim at metus. Ut sed sagittis augue. Curabitur lobortis tincidunt congue. Vestibulum facilisis mauris et diam pharetra porttitor. Mauris purus arcu, congue quis neque vel, varius ultricies nisl. Praesent mattis purus aliquam risus commodo, id maximus nunc tempus.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Ut non ligula sed nibh efficitur euismod. Aliquam efficitur, sapien sed rhoncus sodales, tellus ligula rhoncus est, ac tempor tortor est maximus ex. Mauris cursus leo at accumsan ullamcorper.</p>\r\n\r\n<p>Pellentesque eget elementum dui. Fusce quis augue aliquet tortor semper auctor. Suspendisse in sodales diam. Pellentesque vitae nisi vel magna vestibulum rhoncus. Phasellus enim augue, placerat semper porttitor in, suscipit ac orci. Aenean sit amet sapien auctor, iaculis metus ut, eleifend odio. Morbi convallis nisi et faucibus lacinia.</p>\r\n\r\n<p>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Cras vitae nibh suscipit, elementum sapien sed, vulputate tellus. Sed malesuada est vel nibh ornare, eget iaculis nibh maximus. Ut consequat arcu sed felis scelerisque, congue ornare ipsum vehicula.</p>\r\n\r\n<p>Ut bibendum commodo tellus, id tempus est scelerisque sit amet. Fusce eu sapien quis lacus rhoncus lobortis sit amet a metus. Vestibulum at laoreet sapien. Sed blandit dui a risus molestie</p>\r\n\r\n<h3>Terms &amp; Conditions</h3>\r\n\r\n<p>Pellentesque pretium, tellus sed sagittis feugiat, purus diam eleifend eros, id porta ex enim at metus. Ut sed sagittis augue. Curabitur lobortis tincidunt congue. Vestibulum facilisis mauris et diam pharetra porttitor. Mauris purus arcu, congue quis neque vel, varius ultricies nisl. Praesent mattis purus aliquam risus commodo, id maximus nunc tempus.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ut volutpat metus, sit amet convallis lectus. Interdum et malesuada fames ac ante ipsum primis in faucibus. Quisque ex nunc, auctor id laoreet eu, hendrerit nec mi. Fusce tempus, erat vel auctor efficitur, orci ante ultrices lacus, a dignissim lorem augue ut nulla. Vivamus dictum metus a mi vulputate, et blandit lorem fermentum. Sed a placerat diam. Suspendisse lacinia enim at aliquam vulputate.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Ut non ligula sed nibh efficitur euismod. Aliquam efficitur, sapien sed rhoncus sodales, tellus ligula rhoncus est, ac tempor tortor est maximus ex. Mauris cursus leo at accumsan ullamcorper.</p>\r\n\r\n<p>Pellentesque eget elementum dui. Fusce quis augue aliquet tortor semper auctor. Suspendisse in sodales diam. Pellentesque vitae nisi vel magna vestibulum rhoncus. Phasellus enim augue, placerat semper porttitor in, suscipit ac orci. Aenean sit amet sapien auctor, iaculis metus ut, eleifend odio. Morbi convallis nisi et faucibus lacinia.</p>\r\n\r\n<p>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</p>\r\n\r\n<p>Cras vitae nibh suscipit, elementum sapien sed, vulputate tellus. Sed malesuada est vel nibh ornare, eget iaculis nibh maximus. Ut consequat arcu sed felis scelerisque, congue ornare ipsum vehicula.</p>\r\n\r\n<p>Ut bibendum commodo tellus, id tempus est scelerisque sit amet. Fusce eu sapien quis lacus rhoncus lobortis sit amet a metus. Vestibulum at laoreet sapien. Sed blandit dui a risus molestie</p>\r\n\r\n<h3>Refund Policy</h3>\r\n\r\n<p>Pellentesque pretium, tellus sed sagittis feugiat, purus diam eleifend eros, id porta ex enim at metus. Ut sed sagittis augue. Curabitur lobortis tincidunt congue. Vestibulum facilisis mauris et diam pharetra porttitor. Mauris purus arcu, congue quis neque vel, varius ultricies nisl. Praesent mattis purus aliquam risus commodo, id maximus nunc tempus.</p>\r\n\r\n<ul>\r\n	<li>Fusce lacus lorem, commodo quis nulla non, posuere bibendum massa.</li>\r\n	<li>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae</li>\r\n	<li>Vivamus vitae augue eget dolor accumsan pellentesque eget vel tellus.</li>\r\n	<li>Pellentesque at lobortis arcu, posuere tempus risus.</li>\r\n	<li>Nulla turpis mauris, convallis sed ultrices vel, placerat non massa.</li>\r\n</ul>\r\n\r\n<p>Ut non ligula sed nibh efficitur euismod. Aliquam efficitur, sapien sed rhoncus sodales, tellus ligula rhoncus est, ac tempor tortor est maximus ex. Mauris cursus leo at accumsan ullamcorper.</p>\r\n\r\n<p>Pellentesque eget elementum dui. Fusce quis augue aliquet tortor semper auctor. Suspendisse in sodales diam. Pellentesque vitae nisi vel magna vestibulum rhoncus. Phasellus enim augue, placerat semper porttitor in, suscipit ac orci. Aenean sit amet sapien auctor, iaculis metus ut, eleifend odio. Morbi convallis nisi et faucibus lacinia.</p>', '<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>', NULL, 'Legal', 'Legal', 'Legal', NULL, 1, '2020-03-13 10:46:34', '2020-07-10 07:17:29'),
(5, 'How can We  <span class=\"highlighted\">Help?</span>', 'contact-us', 'Contact Us', '<p>Laborum dolo rumes fugats untras. Etharums ser quidem rerum facilis dolores nemis omnis fugats. Lid est laborum dolo rumes fugats untras.</p>', NULL, NULL, 'Contact Us', 'Contact Us', 'Contact Us', NULL, 1, '2020-03-13 10:46:34', '2020-03-13 10:46:34'),
(6, 'Market Place', 'market-place', 'All Products', NULL, NULL, NULL, 'Market Place', 'Market Place', 'Market Place', NULL, 1, '2020-07-10 08:23:52', '2020-06-17 10:29:12'),
(7, 'Favourites', 'favourites', 'Favourites', '<p>&nbsp; Re-visit your favourite classes for round two.&nbsp;</p>', NULL, NULL, 'Favourites', 'Favourites', 'Favourites', NULL, 1, NULL, '2020-06-17 10:29:48'),
(8, 'Browse By', 'browse-by', 'Browse By', '<p>&nbsp; Filter your workout by brand, type or muscle group.&nbsp;</p>', NULL, NULL, 'Browse By', 'Browse By', 'Browse By', NULL, 1, NULL, '2020-06-17 10:30:28'),
(9, 'Brand', 'brand', 'Brand Profile', NULL, NULL, NULL, 'Brand', 'Brand', 'Brand', NULL, 1, NULL, NULL),
(10, 'Profile', 'profile', 'Profile', NULL, NULL, NULL, 'Profile', 'Profile', 'Profile', NULL, 1, NULL, NULL),
(11, 'My Favourite', 'my-favourite', 'Favourites', NULL, NULL, NULL, 'My Favourite', 'My Favourite', 'My Favourite ', NULL, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_contacts`
--

CREATE TABLE `ams_contacts` (
  `id` int(11) NOT NULL,
  `full_name` text DEFAULT NULL,
  `first_name` text DEFAULT NULL,
  `last_name` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `subject` text DEFAULT NULL,
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
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `icon_class` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=inactive,1=active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_contact_widgets`
--

INSERT INTO `ams_contact_widgets` (`id`, `title`, `description`, `icon_class`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(2, 'Office Address', '202 New Hampshire Avenue , Northwest #100, New York-2573', 'tiles__icon lnr lnr-map-marker', '2020-07-11 08:31:14', '2020-07-11 08:31:14', NULL, '1'),
(3, 'Phone Number', '1-800-643-4500 <br>  \r\n1-800-643-4500', 'tiles__icon lnr lnr-phone', '2020-07-11 08:33:11', '2020-07-11 10:55:49', NULL, '1'),
(4, 'Email', 'support@aazztech.com\r\nsupport@aazztech.com', 'tiles__icon lnr lnr-inbox', '2020-07-11 08:33:53', '2020-07-11 08:52:11', NULL, '1');

-- --------------------------------------------------------

--
-- Table structure for table `ams_membership_plans`
--

CREATE TABLE `ams_membership_plans` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL COMMENT 'Id from plans table',
  `period_id` int(11) NOT NULL COMMENT 'Id from periods table',
  `no_of_downloads` int(11) DEFAULT NULL,
  `amount` float(8,2) NOT NULL DEFAULT 0.00,
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_membership_plans`
--

INSERT INTO `ams_membership_plans` (`id`, `plan_id`, `period_id`, `no_of_downloads`, `amount`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 5, 10.00, '1', '2020-07-11 19:28:18', '2020-07-11 19:28:18', NULL),
(2, 2, 1, 12, 20.00, '1', '2020-07-11 20:03:55', '2020-07-11 20:51:01', NULL),
(3, 3, 1, 20, 30.00, '1', '2020-07-11 20:04:13', '2020-07-11 20:04:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_periods`
--

CREATE TABLE `ams_periods` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `period` int(11) NOT NULL COMMENT 'Number of months',
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_periods`
--

INSERT INTO `ams_periods` (`id`, `title`, `period`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Monthly', 1, '1', '2020-03-15 15:25:11', '2020-07-10 20:36:12', NULL),
(2, '3 Months', 3, '1', '2020-03-16 15:25:11', '2020-07-10 20:35:53', '2020-07-10 23:35:49'),
(3, '6  Months', 6, '1', '2020-03-16 15:25:32', '2020-07-10 20:35:58', '2020-07-11 00:35:54'),
(4, 'Yearly', 12, '1', '2020-03-16 15:25:49', '2020-07-11 17:07:08', '2020-07-11 17:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `ams_plans`
--

CREATE TABLE `ams_plans` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '0=Inactive, 1=Active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ams_plans`
--

INSERT INTO `ams_plans` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Basic', 'basic', '1', '2020-07-11 13:27:43', '2020-07-11 19:03:19', NULL),
(2, 'Standard', 'standard', '1', '2020-07-11 19:05:19', '2020-07-11 19:05:19', NULL),
(3, 'Advance', 'advance', '1', '2020-07-11 19:07:22', '2020-07-11 19:07:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ams_plan_features`
--

CREATE TABLE `ams_plan_features` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `feature` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ams_plan_features`
--

INSERT INTO `ams_plan_features` (`id`, `plan_id`, `feature`) VALUES
(5, 1, 'Contrary to popular belief'),
(6, 1, 'It is a long established fact'),
(7, 1, 'Unlimited Domain Usage'),
(8, 1, '<strong>All the Lorem Ipsum</strong> generators'),
(9, 1, 'going to use a passage'),
(10, 1, '<strong>Lorem Ipsum</strong> is simply'),
(11, 1, 'Contrary to popular'),
(12, 2, '<strong>Contrary to popular belief</strong>'),
(13, 2, 'It is a long established fact'),
(14, 2, 'Unlimited Domain Usage'),
(15, 2, '<strong>All the Lorem Ipsum</strong> generators'),
(16, 2, 'going to use a passage'),
(17, 2, '<strong>Lorem Ipsum</strong> is simply'),
(18, 2, '<strong>Lorem Ipsum</strong> is simply'),
(19, 2, 'Contrary to popular'),
(20, 3, '<strong>Contrary to popular belief</strong>'),
(21, 3, 'It is a long established fact'),
(22, 3, 'Unlimited Domain Usage'),
(23, 3, '<strong>All the Lorem Ipsum</strong> generators'),
(24, 3, 'going to use a passage'),
(25, 3, '<strong>Lorem Ipsum</strong> is simply'),
(26, 3, 'Contrary to popular');

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
  `is_feature` tinyint(1) DEFAULT 0,
  `slug` varchar(200) CHARACTER SET utf8mb4 DEFAULT NULL,
  `status` enum('0','1') CHARACTER SET utf8mb4 NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_croatian_ci;

--
-- Dumping data for table `ams_products`
--

INSERT INTO `ams_products` (`id`, `category_id`, `title`, `description`, `price`, `is_feature`, `slug`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Lorem Ipsum', '<p>Test description</p>', 15.00, 1, 'lorem-ipsum', '1', '2020-07-13 11:51:21', '2020-07-16 06:29:22', NULL),
(2, 1, 'Lorem Ipsum 1', '<p>Test description two</p>', 12.00, 1, 'lorem-ipsum-1', '1', '2020-07-13 11:55:09', '2020-07-16 06:29:38', NULL),
(3, 1, 'Lorem Ipsum 2', '<p>Test description twos</p>', 13.00, 0, 'lorem-ipsum-2', '1', '2020-07-13 11:55:40', '2020-07-16 06:29:55', NULL),
(4, 2, 'Lorem Ipsum 3', '<p>Test description</p>', 10.00, 0, 'lorem-ipsum-3', '1', '2020-07-13 11:56:38', '2020-07-16 06:28:47', NULL),
(5, 2, 'Lorem Ipsum 4', '<p>Test description</p>', 17.00, 1, 'lorem-ipsum-4', '1', '2020-07-13 11:57:14', '2020-07-16 06:30:04', NULL),
(6, 2, 'Lorem Ipsum 5', '<p>Test description three</p>', 50.00, 0, 'lorem-ipsum-5', '1', '2020-07-13 11:57:57', '2020-07-16 06:30:11', NULL),
(7, 1, 'Lorem Ipsum 6', '<p>Test description&nbsp;</p>', 120.00, 1, 'lorem-ipsum-6', '1', '2020-07-13 11:59:41', '2020-07-16 07:02:50', NULL),
(8, 1, 'Lorem Ipsum 7', '<p>Test description two&nbsp;</p>', 40.00, 0, 'lorem-ipsum-7', '1', '2020-07-13 12:00:12', '2020-07-16 07:02:43', NULL);

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
(13, 4, 'pdf', '200'),
(14, 1, 'advance', '200'),
(15, 1, 'basic', '100'),
(16, 2, 'advance', '200'),
(17, 2, 'basics', '20'),
(18, 3, 'advance', '200'),
(19, 5, 'pdf', 'wp'),
(20, 5, 'csv', 'test'),
(21, 6, 'microsoft', 'word file'),
(25, 8, 'Apple', 'i10'),
(26, 7, 'Samsung', 'A5'),
(27, 7, 'MI', 'Redmi');

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
(7, 1, 'product-4178171594887903373.jpg', 'Y'),
(8, 2, 'product-10311371594888041701.jpg', 'Y'),
(9, 3, 'product-9645471594888052937.jpg', 'Y'),
(10, 4, 'product-3781421594888063130.jpg', 'Y'),
(11, 5, 'product-9135821594888070791.jpg', 'Y'),
(12, 6, 'product-4821461594888086608.jpg', 'Y'),
(13, 7, 'product-3632651594888104570.jpg', 'Y'),
(15, 8, 'product-10147491594888182431.jpg', 'Y');

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

-- --------------------------------------------------------

--
-- Table structure for table `ams_role_permissions`
--

CREATE TABLE `ams_role_permissions` (
  `role_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'Movie', 'movie', '1', '2020-07-10 18:57:38', '2020-07-10 18:57:38', NULL),
(2, 'Mp3', 'mp3', '1', '2020-07-10 18:57:42', '2020-07-10 18:57:42', NULL);

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
(1, 'Super', 'Admin', 'Super Admin', 'admin@example.com', NULL, '9876543210', NULL, '$2y$10$7knL0d07gT0XDVA7W/QQM.6.lDrj5LBPCzH60uGuhzm3k5KgaGyLC', 1, 1, NULL, 'N', NULL, NULL, NULL, NULL, NULL, 1594880883, 'ENVdcHoZYCPh70CpcSu2acT4WK3SS8gQcMkqUEJVj18Gw65PCaU6t3dRLOHy', '$2y$10$ThMAm6g.DxloFDkFXO.XA.CV8MYCqCWjzl1o/EGMKi9jxOu8Hb7RS', NULL, '1', NULL, NULL, '2020-03-13 05:30:26', '2020-07-16 06:28:03', NULL),
(4, 'Harry', 'Potter', 'Harry Potter', 'harry@yopmail.com', 'harry potter', NULL, 'profile_pic_1593957407.jpg', '$2y$10$mDMGZbXbKZf87GiIGF6eUODu993hGezUZKYzCqn8E6UMzYjkzZj2G', NULL, 1, NULL, 'N', 'Harry Potter', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'VXkycllQYkJublVpRUxzYVJNbzIwZz09', 'MERpb0JPUERvVWVOQytkZ0ZjTWowZz09', 1594841774, '', NULL, NULL, '1', NULL, NULL, '2020-07-03 19:57:28', '2020-07-15 19:36:14', NULL),
(5, 'Ron', 'Potter', 'Ron Potter', 'ron@yopmail.com', NULL, NULL, NULL, '$2y$10$xWz9DdKJr15GooONsCQLpun74g5eaZPS7FDL4niQUpz71nwzpo/Va', NULL, 1, '743503', 'AU', 'Ron Potter', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'NzU2TmU1ckJoUGRDM0d3b1pEVTFFUT09', 'eWdUWmRCZUJCelUrUGNIM0VTL2g5dz09', NULL, NULL, NULL, NULL, '1', NULL, NULL, '2020-07-04 17:37:41', '2020-07-04 17:37:41', NULL),
(6, 'Dean', 'Elgar', 'Dean Elgar', 'dean@yopmail.com', 'dean', NULL, NULL, '$2y$10$osjBmcePMg/xZsR9XnWTx.oTVQusjLtI0H./Q/XLo9hfA.NWEXk2.', NULL, 1, NULL, 'N', 'Dean Elgar', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'VXkycllQYkJublVpRUxzYVJNbzIwZz09', 'MERpb0JPUERvVWVOQytkZ0ZjTWowZz09', 1593965008, NULL, NULL, NULL, '1', NULL, NULL, '2020-07-05 15:59:46', '2020-07-05 16:03:28', NULL),
(7, 'Mark', 'Taylor', 'Mark Taylor', 'marktaylor@yopmail.com', 'marktaylor', NULL, NULL, '$2y$10$vxEUtV5mnI1t1teICufaVea0xf3Rgpryt6pn7dJakctK8isvPUFfi', NULL, 1, NULL, 'N', 'Mark Taylor', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'NzU2TmU1ckJoUGRDM0d3b1pEVTFFUT09', 'eWdUWmRCZUJCelUrUGNIM0VTL2g5dz09', NULL, NULL, NULL, NULL, '1', '7VBEMKDJ', NULL, '2020-07-09 19:46:05', '2020-07-09 19:46:05', NULL),
(8, 'Steven', 'Gomes', 'Steven Gomes', 'steven@yopmail.com', 'steven', NULL, NULL, '$2y$10$/44LvHqEAEp.Z4wM1plGPuR5SQcINvFXXkQ9k4Q.m0FmJT8m3Qvs2', NULL, 1, NULL, 'N', 'Steven Gomes', NULL, 'Z21qYlJrTk50QkE3aksrRnY2cGZrSy9ndkFpMHJFdGUyZmFIS2RRLy8vND0=', 'NzU2TmU1ckJoUGRDM0d3b1pEVTFFUT09', 'eWdUWmRCZUJCelUrUGNIM0VTL2g5dz09', NULL, NULL, NULL, NULL, '1', '7VBEMKDJ', 7, '2020-07-11 16:57:08', '2020-07-11 16:57:08', NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `ams_why_us_markets`
--

CREATE TABLE `ams_why_us_markets` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `description` text DEFAULT NULL,
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
(1, 'Lorem Ipsum', 'Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,\r\n                                leo quam aliquet diam congue is laoreet elit metus.', 'lnr lnr-license pcolor', '1', '2020-07-12 13:01:12', '2020-07-12 13:01:12', NULL),
(2, 'Lorem Ipsums', 'Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,\r\n                                leo quam aliquet diam congue is laoreet elit metus.', 'lnr lnr-magic-wand scolor', '1', '2020-07-12 13:02:02', '2020-07-12 13:02:02', NULL),
(3, 'Lorems Ipsum', 'Nunc placerat mi id nisi interdum mollis. Praesent pharetra, justo ut scelerisque the mattis,\r\n                                leo quam aliquet diam congue is laoreet elit metus.', 'lnr lnr-lock mcolor1', '1', '2020-07-12 13:02:55', '2020-07-12 13:02:55', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ams_abouts`
--
ALTER TABLE `ams_abouts`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `ams_membership_plans`
--
ALTER TABLE `ams_membership_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_periods`
--
ALTER TABLE `ams_periods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_plans`
--
ALTER TABLE `ams_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ams_plan_features`
--
ALTER TABLE `ams_plan_features`
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
-- Indexes for table `ams_why_us_markets`
--
ALTER TABLE `ams_why_us_markets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ams_abouts`
--
ALTER TABLE `ams_abouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_banners`
--
ALTER TABLE `ams_banners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_categories`
--
ALTER TABLE `ams_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ams_cms`
--
ALTER TABLE `ams_cms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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

--
-- AUTO_INCREMENT for table `ams_membership_plans`
--
ALTER TABLE `ams_membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ams_periods`
--
ALTER TABLE `ams_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `ams_plans`
--
ALTER TABLE `ams_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ams_plan_features`
--
ALTER TABLE `ams_plan_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `ams_products`
--
ALTER TABLE `ams_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ams_product_features`
--
ALTER TABLE `ams_product_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `ams_product_images`
--
ALTER TABLE `ams_product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ams_roles`
--
ALTER TABLE `ams_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_role_pages`
--
ALTER TABLE `ams_role_pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ams_users`
--
ALTER TABLE `ams_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `ams_user_details`
--
ALTER TABLE `ams_user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ams_why_us_markets`
--
ALTER TABLE `ams_why_us_markets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
