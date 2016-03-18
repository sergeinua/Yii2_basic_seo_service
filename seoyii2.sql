-- phpMyAdmin SQL Dump
-- version 4.3.8deb0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2016 at 04:19 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `seoyii2`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_assignment`
--

CREATE TABLE IF NOT EXISTS `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item`
--

CREATE TABLE IF NOT EXISTS `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_item_child`
--

CREATE TABLE IF NOT EXISTS `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_rule`
--

CREATE TABLE IF NOT EXISTS `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `googlehost` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `title`, `description`, `googlehost`, `language`, `status`) VALUES
(1, 'Группа 1', '', '', '', 1),
(2, 'Группа 2', '', '', '', 0),
(3, 'Группа 3', 'fwef', '', '', 1),
(7, 'Первая группа', NULL, 'google.com.ua', 'ua', 1);

-- --------------------------------------------------------

--
-- Table structure for table `group_key`
--

CREATE TABLE IF NOT EXISTS `group_key` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `key_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `group_key`
--

INSERT INTO `group_key` (`id`, `group_id`, `key_id`) VALUES
(22, 2, 24),
(23, 2, 25),
(25, 2, 26),
(28, 7, 29),
(29, 7, 30),
(30, 7, 31),
(31, 7, 32);

-- --------------------------------------------------------

--
-- Table structure for table `group_visibility`
--

CREATE TABLE IF NOT EXISTS `group_visibility` (
  `id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `visibility` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `group_visibility`
--

INSERT INTO `group_visibility` (`id`, `group_id`, `date`, `visibility`) VALUES
('8218fd9ee50387ebbaa3e63b4be4e4fb', 7, 16032016, 35),
('9acf8322997043651234a2104c0bca89', 7, 17032016, 50),
('a48c15989e905b7a6121107510021650', 7, 18032016, 50);

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

CREATE TABLE IF NOT EXISTS `keys` (
  `id` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `date_added` int(11) NOT NULL,
  `date_modified` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `title`, `status`, `date_added`, `date_modified`) VALUES
(24, 'asdqrqwrfghfgh', 1, 0, 0),
(25, 'ygkyuk', 1, 0, 0),
(26, 'new', 1, 0, 0),
(27, 'the first keyword\r', 1, 0, 0),
(28, 'the second keyword', 1, 0, 0),
(29, 'разработка интернет магазина автозапчастей', 1, 0, 0),
(30, 'сайт под ключ киев', 1, 0, 0),
(31, 'создание сайтов', 1, 0, 0),
(32, 'разработка сайтов', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `key_position`
--

CREATE TABLE IF NOT EXISTS `key_position` (
  `id` int(11) NOT NULL,
  `key_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `time_from_today` int(5) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `key_position`
--

INSERT INTO `key_position` (`id`, `key_id`, `date`, `time_from_today`, `position`) VALUES
(34, 29, 1457827200, 30868, 4),
(35, 29, 1457913600, 30962, 15),
(36, 29, 1458000000, 31020, 2),
(37, 30, 1457827200, 31042, 2),
(38, 30, 1457913600, 31070, 4),
(39, 30, 1458000000, 31095, 2),
(40, 31, 1457827200, 31112, 3),
(41, 31, 1457913600, 31140, 10),
(42, 31, 1458000000, 31167, 3),
(43, 32, 1457827200, 31198, 10),
(44, 32, 1457913600, 31226, 15),
(128, 30, 1458172800, 52458, 2),
(129, 31, 1458172800, 52459, 14),
(130, 32, 1458172800, 52459, 22),
(131, 29, 1458172800, 52508, 2),
(191, 29, 1458259200, 50257, 2),
(192, 30, 1458259200, 50257, 2),
(193, 31, 1458259200, 50258, 14),
(194, 32, 1458259200, 50259, 22);

-- --------------------------------------------------------

--
-- Table structure for table `migration`
--

CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migration`
--

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1456321859),
('m140506_102106_rbac_init', 1458137473),
('m160120_090320_user', 1456321861);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `googlehost` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `upd_period` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `googlehost`, `language`, `status`, `upd_period`) VALUES
(2, 'http://www.reclamare.ua/', 'описание первого проекта', '', '', 1, 86400);

-- --------------------------------------------------------

--
-- Table structure for table `project_group`
--

CREATE TABLE IF NOT EXISTS `project_group` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project_group`
--

INSERT INTO `project_group` (`id`, `project_id`, `group_id`) VALUES
(4, 2, 1),
(13, 2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `firstName` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `role` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `authKey` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `firstName`, `lastName`, `email`, `password`, `created_at`, `role`, `authKey`) VALUES
(9, 'admin', '', '', '', '$2y$13$1ssie9EIsLB6c.WiIkDJtOEYC3aoGWv4Ur.vq9pNxbZzvYFZJyKcC', 1458133294, 'admin', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
  ADD PRIMARY KEY (`item_name`,`user_id`);

--
-- Indexes for table `auth_item`
--
ALTER TABLE `auth_item`
  ADD PRIMARY KEY (`name`), ADD KEY `rule_name` (`rule_name`), ADD KEY `idx-auth_item-type` (`type`);

--
-- Indexes for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
  ADD PRIMARY KEY (`parent`,`child`), ADD KEY `child` (`child`);

--
-- Indexes for table `auth_rule`
--
ALTER TABLE `auth_rule`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_key`
--
ALTER TABLE `group_key`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_visibility`
--
ALTER TABLE `group_visibility`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `key_position`
--
ALTER TABLE `key_position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migration`
--
ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project_group`
--
ALTER TABLE `project_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `group_key`
--
ALTER TABLE `group_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `key_position`
--
ALTER TABLE `key_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=216;
--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `project_group`
--
ALTER TABLE `project_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `auth_assignment`
--
ALTER TABLE `auth_assignment`
ADD CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `auth_item`
--
ALTER TABLE `auth_item`
ADD CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `auth_item_child`
--
ALTER TABLE `auth_item_child`
ADD CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
