-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2025 at 05:52 PM
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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_name` enum('Adult','Computer Accessories','Electronics','Others') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `product_id`, `category_name`) VALUES
(1, 12462, 'Adult'),
(2, 16933, 'Electronics'),
(3, 17865, 'Others'),
(4, 27643, 'Others'),
(5, 38910, 'Others'),
(6, 40870, 'Adult'),
(7, 41516, 'Computer Accessories'),
(8, 42043, 'Computer Accessories'),
(9, 45129, 'Electronics'),
(10, 50465, 'Adult'),
(11, 50643, 'Computer Accessories'),
(12, 53859, 'Electronics'),
(13, 54321, 'Adult'),
(14, 62987, 'Computer Accessories'),
(15, 67234, 'Electronics'),
(16, 75966, 'Computer Accessories'),
(17, 91625, 'Computer Accessories'),
(18, 93038, 'Electronics'),
(19, 93586, 'Computer Accessories'),
(20, 95002, 'Electronics');

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
('ORD-2025-06-683c10bf4f591-9e44f01d', 'United States of America', 'Cupertino', 'one apple park way California', '95014', '2025-06-01 10:35:11', 'Credit Card');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `user_id` bigint(20) DEFAULT NULL,
  `orders_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`user_id`, `orders_id`, `product_id`, `quantity`, `price`) VALUES
(4845727533474930302, 'ORD-2025-06-683c10bf4f591-9e44f01d', 12462, 4, 105),
(4845727533474930302, 'ORD-2025-06-683c10bf4f591-9e44f01d', 16933, 5, 7088),
(4845727533474930302, 'ORD-2025-06-683c10bf4f591-9e44f01d', 38910, 1, 410),
(4845727533474930302, 'ORD-2025-06-683c10bf4f591-9e44f01d', 93038, 3, 3150);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_images` varchar(255) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `brand` varchar(20) DEFAULT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `star` decimal(2,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_images`, `description`, `brand`, `original_price`, `price`, `stock`, `star`) VALUES
(12462, 'Durex KY Jelly 100g Water-based Lubricant', 'https://ukdirectbd.com/wp-content/uploads/2022/08/ddd.png', 'It is a moist, colourless, odourless and non-greasy water-based gel that reduces friction to help protect you from intimate discomfort.', 'Durex', 30.00, 25.00, 300, 4.5),
(16933, 'Apple Watch Series 10', 'https://http2.mlstatic.com/D_NQ_NP_929358-MLA80684151617_112024-O.webp', 'Apple Watch Series 10. Our thinnest watch with our biggest display. Health insights including sleep apnea notifications. Our fastest-charging watch.', 'Apple', 1500.00, 1350.00, 429, 4.0),
(17865, 'Essentials Linear Duffel Bag Medium', 'https://m.media-amazon.com/images/I/71NTdyIuaRL.jpg', 'Ideal for gym trips and weekend getaways. This adidas duffel bag has a sturdy base to protect your things.', 'Adidas', 45.00, 39.00, 164, 4.0),
(27643, 'Adicolor Classics 3-Stripes Tee', 'https://m.media-amazon.com/images/I/71HYdxu4IkL.jpg', 'Meet your new favorite tee. This classic adidas t-shirt boasts a slim fit and a contrast hem for some refined vintage vibes.', 'Adidas', 65.00, 50.00, 848, 3.7),
(38910, 'Adidas Ultraboost 22', 'https://m.media-amazon.com/images/I/61aXSkFNTUL.jpg', 'The upper of this shoe contains a minimum of 30% natural and renewable materials to design out finite resources and help end plastic waste.', 'Adidas', 150.00, 135.00, 456, 4.7),
(40870, 'Durex Sensory Sleeve', 'https://m.media-amazon.com/images/I/81Fhxy5BanL._AC_UF894,1000_QL80_.jpg', 'Designed with your ultimate pleasure in mind, this sleeve is a symphony of sensations, soft, stretchy, textured, and dotted – every inch crafted to ignite your desires and bring your fantasies to life.', 'Durex', 135.00, 100.00, 276, 4.3),
(41516, 'Samsung SSD 980 PRO', 'https://cdn.mos.cms.futurecdn.net/AtbWMDpo6Pfr9SJ34SqFeL.jpg', 'Powered by Samsung in-house controller for pcie® 4.0 SSD, the 980 PRO is optimized for speed. It delivers read speeds up to 7,000 MB/s, making it 2 times faster than PCIe® 3.0 SSDs and 12.7 times faster than SATA SSDs.', 'Samsung', 999.00, 642.00, 685, 4.5),
(42043, 'Razer BlackWidow V4 Pro', 'https://m.media-amazon.com/images/I/81Wsrt05uLL._AC_UF894,1000_QL80_.jpg', 'Mechanical gaming keyboard with Razer Command Dial, 8 macro keys, and RGB lighting for enhanced control and an unparalleled gaming experience.', 'Razer', 260.00, 255.00, 601, 4.1),
(45129, 'Samsung Galaxy S25 Ultra', 'https://cdn.mos.cms.futurecdn.net/QRZxiRWrMG2NpMauVPf5aB.jpg', 'The Samsung Galaxy S25 is a series of high-end Android-based smartphones developed and marketed by Samsung Electronics as part of its flagship Galaxy S Series.', 'Samsung', 3000.00, 2800.00, 949, 4.9),
(50465, 'Durex Condoms', 'https://gordonsdirect.com/cdn/shop/products/durex-pleasure-me_1.jpg?v=1620736676', 'Durex Extra Safe condoms are designed for people who need reassurance that the condom they are using is really safe.', 'Durex', 60.00, 55.00, 944, 5.0),
(50643, 'Nvidia RTX 4070 Ti', 'https://miro.medium.com/v2/resize:fit:1400/0*WYxozWGhzEL8erIU.jpg', 'The GeForce RTX 4070 Ti is an enthusiast-class graphics card by NVIDIA, launched on January 3rd, 2023. Built on the 5 nm process, and based on the AD104 graphics processor, in its AD104-400-A1 variant, the card supports DirectX 12 Ultimate. This ensures that all modern games will run on GeForce RTX 4070 Ti.', 'Nvidia', 1760.00, 1565.00, 877, 4.7),
(53859, 'ACER Aspire C | C24-195ES All-in-One', 'https://down-tw.img.susercontent.com/file/tw-11134207-7rasm-m4pguhy409b167', 'The Acer Aspire C 24 C24-195ES redefines efficiency and design in personal computing. This all-in-one PC integrates a 23.8-inch Full HD display with a sleek, space-saving form factor, making it a perfect addition to any workspace.', 'Acer', 0.00, 700.00, 555, 0.0),
(54321, 'Durex 2-in-1 Vibrator', 'https://m.media-amazon.com/images/I/81xFovOzpYL.jpg', 'One of the standout features of this vibrator is its 2-in-1 design. It offers dual functionality – providing both internal stimulation and external teasing.', 'Durex', 400.00, 350.00, 143, 4.2),
(62987, 'Acer Predator Helios 300', 'https://m.media-amazon.com/images/I/81g7AiqWrtL.jpg', 'This 11th Gen gaming laptop is equipped with GeForce RTX™ 30 Series graphics and an RGB backlit keyboard to deliver the high-end performance you want and need.', 'Acer', 45000.00, 43000.00, 10, 4.5),
(67234, 'MacBook Pro M4', 'https://www.apple.com/newsroom/images/2024/10/new-macbook-pro/tile/Apple-MacBook-Pro-M4-lineup-lp.jpg.og.jpg?202505081856', 'MacBook Pro features the most advanced line-up of chips ever built for a pro laptop. Phenomenal single- and multithreaded CPU performance, faster unified memory, enhanced machine learning accelerators — the M4 family of chips gives you the kind of speed and capability you’ve never thought possible.', 'Apple', 3500.00, 3000.00, 824, 4.8),
(75966, 'Acer Nitro AN515', 'https://s.yimg.com/zp/MerchandiseImages/387C4AD3AB-SP-11389424.jpg', 'Acer Nitro 5 Gaming Laptop, 9th Gen Intel Core i5-9300H, NVIDIA GeForce GTX 1650, 15.6\" Full HD IPS Display, 8GB DDR4, 256GB NVMe SSD, Wi-Fi 6, Backlit Keyboard, Alexa Built-in, AN515-54-5812.', 'Acer', 48500.00, 45000.00, 973, 4.3),
(91625, 'Razer Basilisk Ultimate', 'https://assets2.razerzone.com/images/pnx.assets/08f14e963f0f7935f138ac2d2c5387f9/basilisk-ultimate-gallery-3.jpg', 'The new and improved Razer Basilisk Ultimate is the most customizable wireless mouse perfect for FPS games. It comes with 11 programmable buttons, a tilt click scroll wheel paired with a dial to adjust the scroll wheel resistance.', 'Razer', 750.00, 640.00, 394, 4.9),
(93038, 'iphone16 pro', 'https://file1.jyes.com.tw/data/goods/gallery/202409/1725948365064624965.jpg', 'Splash, Water, and Dust Resistant3\r\nRated IP68 (maximum depth of 6 meters up to 30 minutes) under IEC standard 60529', 'Apple', 0.00, 1000.00, 50, 4.5),
(93586, 'Nvidia RTX 5060', 'https://www.overclockers.co.uk/blog/wp-content/uploads/2025/01/ordering-rtx-50-twitter-1536x864.png', 'The Nvidia GeForce RTX 5060 is a mid-range desktop graphics card utilizing the GB206 chip based on the Blackwell architecture. The 5060 offers 8 GB GDDR7 graphics memory with a 128-bit memory bus.', 'Nvidia', 999.00, 655.00, 67, 2.7),
(95002, 'Nvidia AI Developer Kit', 'https://developer-blogs.nvidia.com/wp-content/uploads/2023/03/jetson-orin-nano-developer-kit-3d-render-.png', 'NVIDIA Jetson developer kits are used by professionals to develop and test software for products based on Jetson modules, and by students and enthusiasts.', 'Nvidia', 12000.00, 11000.00, 188, 4.9);

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `user_id` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `admin_role` tinyint(4) NOT NULL DEFAULT 0,
  `token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `account_registered_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`user_id`, `username`, `password`, `email`, `admin_role`, `token`, `token_expiry`, `account_registered_at`, `last_login_time`) VALUES
(219273716, 'Test456', '$2y$10$jCrH3TMWJIyn9AgPHgarhO7SieUGRdkWeqG.U8rvhtqv8uBQoJTgW', 'Test456@gmail.com', 0, NULL, NULL, '2024-12-27 14:35:21', '2025-05-31 12:53:52'),
(4845727533474930302, 'Test123', '$2y$10$wsi0q/wuRyOZ6K9L2.UJ3OI320seI/D1Nj8v2t6yc0PgqPU05r.Wu', 'Test123@gmail.com', 1, NULL, NULL, '2024-12-28 14:41:01', '2025-06-01 16:39:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` bigint(20) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthday` date DEFAULT NULL,
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `phone`, `first_name`, `last_name`, `birthday`, `country`, `city`, `address`, `postal_code`) VALUES
(219273716, '+44 20 7946 0857', 'John', 'Doe', '2009-06-16', 'United Kingdom of Great Britain and Northern Irela', 'London', '12 Rosewood Avenue', 'SW1A 1AA'),
(4845727533474930302, '+1 650-839-4726', 'Douglas', 'McGee', '1987-05-14', 'United States of America', 'Cupertino', 'one apple park way California', '95014');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders_info`
--
ALTER TABLE `orders_info`
  ADD PRIMARY KEY (`orders_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`orders_id`,`product_id`),
  ADD KEY `fk_product_id` (`product_id`),
  ADD KEY `fk_od_uid` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_od_uid` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_id`),
  ADD CONSTRAINT `fk_orders_id` FOREIGN KEY (`orders_id`) REFERENCES `orders_info` (`orders_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `user_accounts` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
