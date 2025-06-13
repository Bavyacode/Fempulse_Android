-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 13, 2025 at 12:14 PM
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
-- Database: `fem`
--

-- --------------------------------------------------------

--
-- Table structure for table `cycle_data`
--

CREATE TABLE `cycle_data` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `interval_days` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cycle_data`
--

INSERT INTO `cycle_data` (`id`, `user_id`, `start_date`, `duration`, `interval_days`) VALUES
(1, 1, '2025-05-01', NULL, 28),
(4, 127, '2024-09-20', 5, 30),
(5, 128, '2024-09-20', 5, 30),
(6, 128, '2024-02-28', 7, 25),
(7, 129, '2024-11-28', 4, 35),
(10, 131, '2023-08-30', 5, 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(233) NOT NULL,
  `username` varchar(233) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(233) NOT NULL,
  `age` varchar(233) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `age`) VALUES
(1, 'testuser', 'test@example.com', '$2y$10$3z0saJNoDaTbzDPBPReulOTKgdmmtzSL3bM3VuuAMKQHe3LdJ8aF.', '20'),
(126, 'bhavy', 'bhavy@gmail.com', '$2y$10$UysImYtRyrQbBZEOrif0KeHPYmtK.4IY.p3gMajgGKYUgycVhaN3O', '30'),
(127, 'test2', 'test2@gmail.com', '$2y$10$eju4D9ND./pYrAz2lw9VteGRNIShxUEV6Geo7E9H70qfBIJ099Yg6', '30'),
(128, 'test4', 'test4@gmail.com', '$2y$10$wI6QCkazNQiDu6Yct63wEOS8HlDsYZmqjzhUxLcLytbPQ544OBaeu', '30'),
(129, 'meena', 'meena@gmail.com', '$2y$10$9GXd5VzRW5frJn5H8gsYquOffobO2yqrHNiMuOUuooR5AggckHdKe', '25'),
(131, 'red', 'red@gmail.com', '$2y$10$qUzXRe4qS4e4QzGCcdRSnurdUOqMJEJuOQrSrxnkidMaxboaY88TC', '45'),
(133, 'bavya', 'bavya@gmail.com', '$2y$10$bAn1CaC1UmWFLRc3Qzb9QO3EuwER.ypmb5HbSn7ej2JFF2qgaJXPC', '19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cycle_data`
--
ALTER TABLE `cycle_data`
  ADD PRIMARY KEY (`id`),
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
-- AUTO_INCREMENT for table `cycle_data`
--
ALTER TABLE `cycle_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(233) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cycle_data`
--
ALTER TABLE `cycle_data`
  ADD CONSTRAINT `cycle_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
