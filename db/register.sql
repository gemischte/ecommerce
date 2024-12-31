-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2024 at 05:50 PM
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
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `account_registered_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_id` bigint(20) NOT NULL,
  `last_login_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`username`, `email`, `password`, `token`, `token_expiry`, `account_registered_date`, `user_id`, `last_login_time`) VALUES
('Test456', 'Test456@yahoo.com', '$2y$10$pCpFn0jPv9wUQOhCx4j5u.LBs2DHdGRb/MVgfVw00bP0w/3PILXqu', NULL, NULL, '2024-12-27 14:35:21', 219273716, '2024-12-28 17:07:58'),
('Test789', 'Test789@icloud.com', '$2y$10$Wl7OBBlrq8PtvrxfWr27Zuq4eoadSoVkgAwG2RSPFumcn7Bf2wAFa', NULL, NULL, '2024-12-28 14:41:58', 3253652496792670337, '2024-12-29 14:12:21'),
('Test123', 'Test123@gmail.com', '$2y$10$llpH5dn.MyYyIXOsk.1H0ueebvX.y7YDJUupZuCTx8UlDA/qv943K', NULL, NULL, '2024-12-28 14:41:01', 4845727533474930302, '2024-12-28 19:53:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8964933552502346846;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
