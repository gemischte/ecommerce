-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 05:13 PM
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
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `orders_created_at` datetime NOT NULL,
  `payment_method` enum('Credit Card','PayPal','Bank Transfer','Cash on delivery') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders_info`
--

INSERT INTO `orders_info` (`orders_id`, `country`, `city`, `address`, `postal_code`, `orders_created_at`, `payment_method`) VALUES
('ORD-2025-02-322691-631907', 'United States', 'California', 'one apple park way cupertino ca united states', '95014', '2025-02-23 17:03:28', 'PayPal');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `product_images` varchar(255) DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `brand` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `product_star` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_images`, `description`, `brand`, `original_price`, `price`, `stock_quantity`, `product_star`) VALUES
(41516, 'Samsung SSD 980 PRO', 'https://upload.wikimedia.org/wikipedia/commons/7/75/Samsung_980_PRO_PCIe_4.0_NVMe_SSD_1TB-top_PNr%C2%B00915.jpg', 'Powered by Samsung in-house controller for pcie® 4.0 SSD, the 980 PRO is optimized for speed. It delivers read speeds up to 7,000 MB/s, making it 2 times faster than PCIe® 3.0 SSDs and 12.7 times faster than SATA SSDs. The 980 PRO achieves max speeds on PCIe® 4.0 and may vary in other environments.', 'Samsung', 999.00, 642.00, 999, 4.5),
(93038, 'iphone16 pro', 'https://www.apple.com/v/iphone-16-pro/d/images/meta/iphone-16-pro_overview__ejy873nl8yi6_og.png?202412122331a', 'Splash, Water, and Dust Resistant3\r\nRated IP68 (maximum depth of 6 meters up to 30 minutes) under IEC standard 60529', 'Apple', 0.00, 1000.00, 999, 5.0),
(93586, 'Nvidia RTX 5060', 'https://www.overclockers.co.uk/blog/wp-content/uploads/2025/01/ordering-rtx-50-twitter-1536x864.png', 'The Nvidia GeForce RTX 5060 is a mid-range desktop graphics card utilizing the GB206 chip based on the Blackwell architecture. The 5060 offers 8 GB GDDR7 graphics memory with a 128-bit memory bus.', 'Nvidia', 999.00, 655.00, 999, 5.0);

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `user_id` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `account_registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login_time` datetime DEFAULT NULL,
  `first_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_id`, `username`, `password`, `email`, `token`, `token_expiry`, `account_registered_at`, `last_login_time`, `first_name`, `last_name`) VALUES
(219273716, 'Test456', '$2y$10$jCrH3TMWJIyn9AgPHgarhO7SieUGRdkWeqG.U8rvhtqv8uBQoJTgW', 'Test456@gmail.com', NULL, NULL, '2024-12-27 14:35:21', '2025-03-25 14:18:18', '', ''),
(4845727533474930302, 'Test123', '$2y$10$llpH5dn.MyYyIXOsk.1H0ueebvX.y7YDJUupZuCTx8UlDA/qv943K', 'Test123@gmail.com', NULL, NULL, '2024-12-28 14:41:01', '2025-04-22 11:46:19', 'Douglas', 'McGee');

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
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8964933552502346846;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
