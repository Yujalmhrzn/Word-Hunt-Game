-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 11:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE `leaderboard` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaderboard`
--

INSERT INTO `leaderboard` (`id`, `username`, `score`, `submitted_at`) VALUES
(1, 'Swastik', 9, '2025-05-21 17:51:29'),
(2, 'Swastik', 13, '2025-05-21 17:51:47'),
(3, 'Swastik', 6, '2025-05-21 17:59:36'),
(4, 'Swastik', 1, '2025-05-21 17:59:43'),
(5, 'Swastik', 17, '2025-05-21 18:00:14'),
(6, 'Swastik', 18, '2025-05-21 18:00:41'),
(7, 'Swastik', 11, '2025-05-21 18:05:23'),
(8, 'Swastik', 21, '2025-05-21 18:05:51'),
(9, 'Swastik', 9, '2025-05-21 18:08:31'),
(10, 'Swastik', 9, '2025-05-21 18:13:49'),
(11, 'Swastik', 5, '2025-05-21 18:17:09'),
(12, 'Swastik', 5, '2025-05-21 18:17:46'),
(13, 'Swastik', 5, '2025-05-21 18:19:50'),
(14, 'admin1', 8, '2025-05-21 18:33:44'),
(15, 'admin1', 9, '2025-05-21 18:40:11'),
(16, 'SwaMIHELL', 3, '2025-05-21 18:41:40'),
(17, 'Swastik', 9, '2025-05-25 03:16:11'),
(18, 'Swastik', 13, '2025-05-25 03:36:31'),
(19, 'Swastik', 2, '2025-05-29 18:47:26'),
(20, 'Swastik', 7, '2025-05-29 18:47:40'),
(21, 'Swastik', 5, '2025-07-02 16:06:51'),
(22, 'Swastik', 11, '2025-07-06 01:41:18'),
(23, 'Swastik', 27, '2025-07-06 01:41:53'),
(24, 'Swastik', 9, '2025-07-06 02:21:19'),
(25, 'Swastik', 127, '2025-07-06 08:49:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'Swastik', NULL, '$2y$10$OG1saGAaU7S0iZJtYmq5aeTI5fNwIhNYYoUQJIeGvGj.L/5dFNyhG'),
(2, 'Swas', NULL, '$2y$10$Mq.S8OXn3KoXxmWz24JBluFY8ph1VfgmLh.sgHFTr4J9Rg08oe2s2'),
(3, 'HELL', NULL, '$2y$10$QiIocjF1LEsfOyw1We1wqu//fsquM1kSO0VPzS5afMwip6Tdb/Cyq'),
(4, 'HELLO', NULL, '$2y$10$yZgRzcrOIeP2KiuPj0IsZetq.Wk7sE.XUKQYa6iNUlQ2.GF4mj0CC'),
(5, 'admin1', NULL, '$2y$10$YURDCD2SlRdff55Yk3/Q1OUmcz3p8vanWSDu3cJtphxJfN/txP2/G'),
(6, 'NAme', 'swastik.maharjan99@gmail.com', '$2y$10$tsAxicaRAFe25bIfFXDV5uAFU45oV69Ng21H8i1CFruA5urvLyehO'),
(7, 'SwaMIHELL', 'swastik.maharjan0@gmail.com', '$2y$10$STNlEE3N4UerNOdu4nET3uddn4YdilxXc/PIBtCV1oLZqnPrZFKC6'),
(8, 'Swastik1', 'hetem18839@bymercy.com', '$2y$10$YZJ4u7wRIwFEhTf0ySee2.0WNg6.Q4R9G0evjZyekWAMtNiYE699i'),
(9, 'Swastik1111', 'swastik.maharjan01@gmail.com', '$2y$10$QP5wbuJJhRg8nUs/uFhpleCyYurWJYl9yZIJIzg2rDj.oO6iXlpvO'),
(10, 'Name1', 'adaa@gmail.com', '$2y$10$cpqLjqq1rDrVuNzX254UKudJd3sadCMw6oZwocL67ngb9Bb8Ek6F2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
