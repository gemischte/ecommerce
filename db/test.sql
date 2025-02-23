-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2025 at 05:08 PM
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
-- Table structure for table `orders_info`
--

CREATE TABLE `orders_info` (
  `orders_id` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `orders_created_date` datetime NOT NULL,
  `payment_method` enum('Credit Card','PayPal','Bank Transfer','Cash') NOT NULL,
  `postal_code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders_info`
--

INSERT INTO `orders_info` (`orders_id`, `country`, `city`, `address`, `orders_created_date`, `payment_method`, `postal_code`) VALUES
('ORD-2025-02-322691-631907', 'United States', 'California', 'one apple park way cupertino ca 95014 united states', '2025-02-23 17:03:28', 'PayPal', '95014');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `product_star` decimal(2,1) DEFAULT 0.0,
  `original_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `image_path`, `product_star`, `original_price`) VALUES
(41516, 'Samsung SSD 990 PRO', 'Secure victory with 990 PRO\'s expanded 4TB capacity, empowering you with a random read speed of up to 1,600K IOPS. Witness faster loading times on your PC and PlayStation® 5, as it breathes life into your games.', 1000.00, 999, 'https://farm66.static.flickr.com/65535/52505350197_3e0819ecc8_b.jpg', 4.5, 99999.00),
(93038, 'iphone16 pro', 'Splash, Water, and Dust Resistant3\nRated IP68 (maximum depth of 6 meters up to 30 minutes) under IEC standard 60529', 1000.00, 999, 'https://www.apple.com/v/iphone-16-pro/d/images/meta/iphone-16-pro_overview__ejy873nl8yi6_og.png?202412122331a', 5.0, 99999.00),
(93586, ' Razer Basilisk Ultimate ', 'The new and improved Razer Basilisk Ultimate is the most customizable wireless mouse perfect for FPS games. It comes with 11 programmable buttons, a tilt click scroll wheel paired with a dial to adjust the scroll wheel resistance.', 1000.00, 999, 'https://m.media-amazon.com/images/I/71eXWvF3bEL.jpg', 5.0, 99999.00);

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
  `last_login_time` datetime DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`username`, `email`, `password`, `token`, `token_expiry`, `account_registered_date`, `user_id`, `last_login_time`, `first_name`, `last_name`) VALUES
('Test456', 'Test456@yahoo.com', '$2y$10$pCpFn0jPv9wUQOhCx4j5u.LBs2DHdGRb/MVgfVw00bP0w/3PILXqu', NULL, NULL, '2024-12-27 14:35:21', 219273716, '2024-12-28 17:07:58', NULL, NULL),
('Test789', 'Test789@icloud.com', '$2y$10$Wl7OBBlrq8PtvrxfWr27Zuq4eoadSoVkgAwG2RSPFumcn7Bf2wAFa', NULL, NULL, '2024-12-28 14:41:58', 3253652496792670337, '2024-12-29 14:12:21', NULL, NULL),
('Test123', 'Test123@gmail.com', '$2y$10$llpH5dn.MyYyIXOsk.1H0ueebvX.y7YDJUupZuCTx8UlDA/qv943K', NULL, NULL, '2024-12-28 14:41:01', 4845727533474930302, '2025-02-20 15:38:18', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders_info`
--
ALTER TABLE `orders_info`
  ADD PRIMARY KEY (`orders_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98858;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8964933552502346846;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
