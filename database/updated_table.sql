-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 17, 2022 at 05:08 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `whoyouinto`
--

-- --------------------------------------------------------

--
-- Table structure for table `creator_plans`
--

CREATE TABLE `creator_plans` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_general_ci NOT NULL,
  `amount` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `benefits` longtext COLLATE utf8mb4_general_ci,
  `month_2` json DEFAULT NULL,
  `month_3` json DEFAULT NULL,
  `month_6` json DEFAULT NULL,
  `yearly` json DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1' COMMENT '0 - InActive, 1 - Active, 2 - Archived',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Creator Subscription Plans';

--
-- Dumping data for table `creator_plans`
--

INSERT INTO `creator_plans` (`id`, `user_id`, `title`, `amount`, `benefits`, `month_2`, `month_3`, `month_6`, `yearly`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, 'Subscription 1', '5.00', '[\"Hello\\nThere\\nHow\\nAre you\"]', '{\"off\": \"20\", \"amount\": \"4\"}', NULL, NULL, NULL, 1, '2022-02-17 03:43:46', '2022-02-17 03:43:46'),
(2, 31, 'Subscription 1', '5.00', '[\"Hello\\nThere\\nHow\\nAre you\"]', '{\"off\": \"20\", \"amount\": \"4\"}', NULL, NULL, NULL, 1, '2022-02-17 03:44:08', '2022-02-17 03:44:08'),
(3, 31, 'Subscription 1', '5.00', '[\"Hello\",\"There\",\"How\",\"Are you\"]', '{\"off\": \"20\", \"amount\": \"4\"}', NULL, NULL, NULL, 1, '2022-02-17 03:49:45', '2022-02-17 03:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `plan_promotions`
--

CREATE TABLE `plan_promotions` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `plan_id` int UNSIGNED NOT NULL,
  `prom_amount` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prom_discount` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avail_from` timestamp NOT NULL,
  `avail_to` timestamp NOT NULL,
  `allow_existing` tinyint(1) NOT NULL DEFAULT '0',
  `allow_expired` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Plan Promotions';

--
-- Dumping data for table `plan_promotions`
--

INSERT INTO `plan_promotions` (`id`, `user_id`, `plan_id`, `prom_amount`, `prom_discount`, `avail_from`, `avail_to`, `allow_existing`, `allow_expired`, `status`, `created_at`, `updated_at`) VALUES
(1, 31, 2, '3', NULL, '2022-02-16 18:30:00', '2022-02-17 18:30:00', 0, 0, 1, '2022-02-17 03:44:09', '2022-02-17 03:44:09'),
(2, 31, 3, '3', NULL, '2022-02-16 18:30:00', '2022-02-17 18:30:00', 0, 0, 1, '2022-02-17 03:49:45', '2022-02-17 03:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `text_content` longtext COLLATE utf8mb4_general_ci,
  `publish_schedule` date DEFAULT NULL,
  `delete_schedule` date DEFAULT NULL,
  `is_conditional` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - Pending, 1 - Published, 2 - Archived, 3 - Reported',
  `approved_by` int DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Content Posts';

-- --------------------------------------------------------

--
-- Table structure for table `post_medias`
--

CREATE TABLE `post_medias` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `uid` text COLLATE utf8mb4_general_ci NOT NULL,
  `mime` text COLLATE utf8mb4_general_ci,
  `type` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `name` text COLLATE utf8mb4_general_ci,
  `ext` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `aws_id` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Media Data';

-- --------------------------------------------------------

--
-- Table structure for table `post_previews`
--

CREATE TABLE `post_previews` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `post_id` int UNSIGNED NOT NULL,
  `uid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mime` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `aws_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Post Media Data';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `creator_plans`
--
ALTER TABLE `creator_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plan_promotions`
--
ALTER TABLE `plan_promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_medias`
--
ALTER TABLE `post_medias`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_previews`
--
ALTER TABLE `post_previews`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `creator_plans`
--
ALTER TABLE `creator_plans`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `plan_promotions`
--
ALTER TABLE `plan_promotions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_medias`
--
ALTER TABLE `post_medias`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `post_previews`
--
ALTER TABLE `post_previews`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
