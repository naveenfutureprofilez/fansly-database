-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 11, 2022 at 12:18 PM
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
-- Table structure for table `creator_requests`
--

CREATE TABLE `creator_requests` (
  `id` int NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `address` json NOT NULL,
  `id_type` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_expiry` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_expire` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 - No, 1 - Yes',
  `id_no` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `verify_img` text COLLATE utf8mb4_general_ci,
  `social` json DEFAULT NULL,
  `remark` text COLLATE utf8mb4_general_ci,
  `status` tinyint NOT NULL DEFAULT '0' COMMENT '0 - Pending, 1 - Need Update, 2 - Approved',
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for Creator Requests';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `creator_requests`
--
ALTER TABLE `creator_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `creator_requests`
--
ALTER TABLE `creator_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
