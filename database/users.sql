-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 11, 2022 at 12:19 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `avatar` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'users/default.png',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `settings` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `balance` double(10,2) NOT NULL DEFAULT '0.00',
  `isAdmin` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'No',
  `role` tinyint NOT NULL DEFAULT '0' COMMENT '0 - User, 1 - Creator',
  `ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `isBanned` enum('No','Yes') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `phone`, `avatar`, `email_verified_at`, `password`, `remember_token`, `settings`, `balance`, `isAdmin`, `role`, `ip`, `isBanned`, `created_at`, `updated_at`) VALUES
(22, 'admin', 'Site Admin', 'admin@example.org', NULL, 'users/default.png', NULL, '$2y$10$W7J09QNB3MY5PlvUSyUNQOEssNDNGF9sQavauc0AUVtcpLleBf3.G', NULL, NULL, 0.00, 'Yes', 0, '127.0.0.1', 'No', '2020-12-04 08:19:47', '2022-02-07 06:34:30'),
(29, 'naveenteh', 'Naveen Tehpariya', 'naveen@fpdemo.com', '9876543210', 'users/default.png', NULL, '$2y$10$h9i3zF2//C8WWXybCnt5pewABDtiR.6t03Gzfi40BESSYChnccs1m', NULL, NULL, 0.00, 'No', 0, NULL, 'No', '2022-02-09 04:23:25', '2022-02-09 04:23:25'),
(30, 'habuzabu', 'hhhbbhj ', 'pradeep@fpdemo.com', '7014111037', 'users/default.png', NULL, '$2y$10$C/U6TtWyFU7bUMm.k7Gyeewt0WY9JmrAIUkUrupjOv5YSKyYmSKSe', NULL, NULL, 0.00, 'No', 0, NULL, 'No', '2022-02-10 01:27:44', '2022-02-10 01:27:44'),
(31, 'naveen_', 'Naveen Tehp', 'naveen_@fpdemo.com', '9876543210', 'users/default.png', NULL, '$2y$10$4XS72uNJqCyAAOThaz2.relijtTgP/a7BQXR/vERFvBqqCzAzAlDO', NULL, NULL, 0.00, 'No', 0, NULL, 'No', '2022-02-11 01:16:28', '2022-02-11 01:16:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
