-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2025 at 05:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auction_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `current_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`id`, `user_id`, `title`, `description`, `start_price`, `current_price`, `start_time`, `end_time`, `created_at`, `images`) VALUES
(1, 1, 'sdkvnd', 'vadsvva', 500.00, 500.00, '2025-09-02 19:08:00', '2025-09-22 19:08:00', '2025-09-19 13:38:19', NULL),
(2, 1, 'bsdf bds', 'dsg sg sg', 500.00, 700.00, '2025-09-09 19:09:00', '2025-09-10 19:09:00', '2025-09-19 13:40:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `auction_images`
--

CREATE TABLE `auction_images` (
  `id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auction_images`
--

INSERT INTO `auction_images` (`id`, `auction_id`, `image_path`) VALUES
(1, 1, 'uploads/68cd5ccb914ca.png'),
(2, 2, 'uploads/68cd5d332345f.png'),
(3, 2, 'uploads/68cd5d3324125.png'),
(4, 2, 'uploads/68cd5d3324f56.png'),
(5, 2, 'uploads/68cd5d3325900.png'),
(6, 2, 'uploads/68cd5d332620c.png'),
(7, 2, 'uploads/68cd5d3326a09.png'),
(8, 2, 'uploads/68cd5d33272a2.png');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` int(11) NOT NULL,
  `auction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `bid_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `auction_id`, `user_id`, `amount`, `bid_time`) VALUES
(1, 2, 1, 600.00, '2025-09-19 13:42:27'),
(2, 2, 2, 700.00, '2025-09-19 13:42:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `created_at`) VALUES
(1, 'ami', 'amitdubal2005@gmail.com', '$2y$10$ZP4m0IWjAq8sl1WqwGjDG.KZ5zIy48inzltoUxPzTnzIvmSU5VFn6', '2025-09-19 13:00:37'),
(2, 'aniket', 'sdbvasfbaf@gmail.com', '$2y$10$DjNagLKweOTclslW7X7EGeznmw9g5lYsE5P.U0m9rVpt.erjCvYR2', '2025-09-19 13:42:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_auction_end_time` (`end_time`);

--
-- Indexes for table `auction_images`
--
ALTER TABLE `auction_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auction_id` (`auction_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auction_id` (`auction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `auction_images`
--
ALTER TABLE `auction_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auction_images`
--
ALTER TABLE `auction_images`
  ADD CONSTRAINT `auction_images_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_ibfk_1` FOREIGN KEY (`auction_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
