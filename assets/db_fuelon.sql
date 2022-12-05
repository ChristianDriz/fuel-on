-- phpMyAdmin SQL Dump
-- version 5.2.1-dev+20220928.a4d273f5cf
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2022 at 02:24 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_fuelon`
--
CREATE DATABASE IF NOT EXISTS `db_fuelon` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_fuelon`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_carts`
--

CREATE TABLE `tbl_carts` (
  `cartID` int(11) NOT NULL,
  `customerID` int(11) DEFAULT NULL,
  `shopID` int(11) NOT NULL,
  `productID` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_carts`
--

INSERT INTO `tbl_carts` (`cartID`, `customerID`, `shopID`, `productID`, `product_name`, `quantity`) VALUES
(36, 11, 5, 10, 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L)', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chats`
--

CREATE TABLE `tbl_chats` (
  `chatID` int(11) NOT NULL,
  `user_1` int(11) NOT NULL,
  `user_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_chats`
--

INSERT INTO `tbl_chats` (`chatID`, `user_1`, `user_2`) VALUES
(1, 1, 5),
(2, 1, 6),
(3, 5, 9),
(4, 10, 5),
(5, 11, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chat_contents`
--

CREATE TABLE `tbl_chat_contents` (
  `convoID` int(11) NOT NULL,
  `senderID` int(11) NOT NULL,
  `receiverID` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `opened` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_chat_contents`
--

INSERT INTO `tbl_chat_contents` (`convoID`, `senderID`, `receiverID`, `message`, `opened`, `created_at`) VALUES
(1, 1, 5, 'low po', 1, '2022-06-20 21:40:03'),
(2, 5, 1, 'sdfsdf ', 1, '2022-06-20 21:40:07'),
(3, 5, 1, 'adasd', 1, '2022-06-20 22:22:36'),
(4, 5, 1, 'yow ', 1, '2022-06-20 22:22:45'),
(5, 5, 1, 'asnashjahja', 1, '2022-06-20 22:23:00'),
(6, 1, 5, 'sige po', 1, '2022-06-21 00:06:35'),
(7, 1, 6, 'low po', 1, '2022-06-21 01:24:00'),
(8, 6, 1, 'oy baket gabi na', 1, '2022-06-21 01:33:40'),
(9, 1, 6, 'ala lang bibili sana', 0, '2022-06-21 01:37:59'),
(10, 5, 9, 'oyyy', 1, '2022-06-21 11:33:44'),
(11, 9, 5, 'pabile', 1, '2022-06-21 11:34:02'),
(12, 5, 9, 'ala sarado e', 1, '2022-06-21 11:34:08'),
(13, 5, 9, 'Okay thanks', 1, '2022-06-21 11:48:46'),
(14, 10, 5, 'Do you deliver gasoline?', 1, '2022-06-21 11:50:34'),
(15, 5, 10, 'Delivery method isn\'t available. Thankyou', 1, '2022-06-21 11:50:56'),
(16, 11, 5, 'Good day!', 1, '2022-06-21 15:42:10'),
(17, 5, 11, 'How can we help you?', 1, '2022-06-21 15:42:38'),
(18, 10, 5, 'oy', 0, '2022-09-28 13:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fuel`
--

CREATE TABLE `tbl_fuel` (
  `fuelID` int(11) NOT NULL,
  `fuel_type` varchar(100) NOT NULL,
  `fuel_category` varchar(15) NOT NULL,
  `quantity` int(11) NOT NULL,
  `fuel_image` varchar(200) NOT NULL,
  `new_price` decimal(10,2) NOT NULL,
  `old_price` decimal(10,2) DEFAULT NULL,
  `date_updated` date NOT NULL,
  `shopID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_fuel`
--

INSERT INTO `tbl_fuel` (`fuelID`, `fuel_type`, `fuel_category`, `quantity`, `fuel_image`, `new_price`, `old_price`, `date_updated`, `shopID`) VALUES
(2, 'Petron XCS Euro 4', 'Unleaded', 0, 'petron-xcs-euro4.jpg', '65.48', '68.20', '2022-06-19', 5),
(3, 'Petron Xtra Advance ', 'Premium', 0, 'petron-xtra-advance-euro4.jpg', '63.00', '33.00', '2022-06-21', 5),
(7, 'Petron Diesel Max Euro 4', 'Diesel', 100, 'petron-diesel-max-euro4.jpg', '63.50', '62.00', '2022-06-14', 5),
(13, 'UniOil Euro5 Diesel', 'Diesel', 100, 'pump-diesel.png', '74.00', '73.00', '2022-06-15', 6),
(14, 'UniOil  Euro5 Gasoline 91', 'Unleaded', 100, 'pump-leaded.png', '80.06', '71.06', '2022-06-17', 6),
(15, 'UniOil Euro5 Gasoline 95', 'Premium', 100, 'pump-unleaded.png', '77.80', '78.00', '2022-06-15', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_locations`
--

CREATE TABLE `tbl_locations` (
  `locationID` int(11) NOT NULL,
  `shopID` int(11) DEFAULT NULL,
  `longitude` decimal(8,6) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_permits`
--

CREATE TABLE `tbl_permits` (
  `permitID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `permit_image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `productID` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `prod_image` varchar(200) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `shopID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`productID`, `product_name`, `description`, `prod_image`, `quantity`, `price`, `shopID`) VALUES
(8, 'Petron Blaze Racing BR450 Premium Multigrade Gasoline Engine Oil (Ultron Touring) 20W50 (1 L)', 'PETRON BLAZE RACING BR450 PREMIUM MULTI-GRADE (formerly ULTRON TOURING) with TS3 is a high-quality, multi-grade gasoline engine oil designed to meet the high-performance requirements of most modern vehicles.  ', 'Petron Blaze Racing BR450.png', 25, '226.00', 5),
(9, 'Petron Rev-X HD 40 Diesel Engine Oil (4 liters)', 'PETRON REV-X HD 40 is a heavy-duty, high quality diesel engine oil recommended for use in engines operating under severe operating conditions.  It is suitable for passenger-type vehicles such jeepneys, buses, AUVs, and mixed commercial fleets.', 'Petron Rev-X HD 40 Diesel Engine Oil (4 liters).PNG', 5, '697.00', 5),
(10, 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L)', 'Petron ATF Premium HTP is a high premium, fully synthetic automatic transmission fluid with high temperature protection. It is specially designed for the latest generation of planetary gear transmissions and it is formulated to provide excellent protection that meets and exceeds the requirement of various Asian, European, and North American vehicles.', 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L).PNG', 9, '280.00', 5),
(11, 'Unioil Gmax 1000 SN 5W-40 Fully Synthetic Gasoline Engine Oil (1L)', 'Unioil GMAX 1000 is a high performance fully synthetic gasoline engine oil formulated with premium quality base oils and advanced molecular technology additives that provide superior engine protection, excellent performance, and enhanced fuel efficiency.', 'Unioil Gmax 1000 SN 5W-40 Fully Synthetic Gasoline Engine Oil (1L).PNG', 15, '380.00', 6),
(12, 'Unioil ATF Automatic Transmission Fluid (1L)', 'Unioil ATF is an Automatic Transmission Fluid especially formulated to help protect your vehicle’s automatic transmission from damage that may arise due to intense heat and friction. It also assists the transmission achieve smoother gear shifting required to attain faster acceleration. ', 'Unioil ATF Automatic Transmission Fluid (1L).PNG', 16, '190.00', 6),
(13, 'Unioil Motosport Scooter 500 20W-40 Scooter Oil (1L)', 'Unioil Motosport Scooter 500 is a multigrade engine oil formulated for four-stroke motorcycles with a dry clutch system (automatic transmission).', 'Unioil Motosport Scooter 500 20W-40 Scooter Oil (1L).PNG', 19, '205.00', 6);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shop_ratings`
--

CREATE TABLE `tbl_shop_ratings` (
  `ratingID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `shopID` int(11) NOT NULL,
  `rating` enum('1','2','3','4','5') DEFAULT NULL,
  `feedback` varchar(1000) DEFAULT NULL,
  `rating_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_shop_ratings`
--

INSERT INTO `tbl_shop_ratings` (`ratingID`, `customerID`, `shopID`, `rating`, `feedback`, `rating_date`) VALUES
(1, 10, 5, '5', 'The service is good and i will buy to this shop again soon...', '2022-09-28'),
(2, 1, 5, '5', 'Good job', '2022-09-28'),
(3, 1, 6, '1', NULL, '2022-09-28'),
(4, 11, 6, '5', 'I am pleased of your service', '2022-09-28'),
(6, 11, 5, '5', 'I am pleased with the service.', '2022-09-28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `transacID` int(11) NOT NULL,
  `shopID` int(11) NOT NULL,
  `customerID` int(11) NOT NULL,
  `productID` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `orderID` varchar(128) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `transac_date` date NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(20) NOT NULL DEFAULT 'Ordered'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`transacID`, `shopID`, `customerID`, `productID`, `product_name`, `quantity`, `total`, `orderID`, `payment_method`, `transac_date`, `order_status`) VALUES
(1, 5, 1, 10, 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L)', 2, '560.00', 'ORD-905601176', 'Online Payment', '2022-06-18', 'Completed'),
(2, 6, 4, 12, 'Unioil ATF Automatic Transmission Fluid (1L)', 2, '380.00', 'ORD-937597465', 'Cash Upon Pickup', '2022-06-18', 'Completed'),
(3, 6, 4, 13, 'Unioil Motosport Scooter 500 20W-40 Scooter Oil (1L)', 1, '205.00', 'ORD-937597465', 'Cash Upon Pickup', '2022-06-18', 'Completed'),
(4, 6, 4, 12, 'Unioil ATF Automatic Transmission Fluid (1L)</br>', 1, '190.00', 'ORD-633292162', 'Cash Upon Pickup', '2022-06-18', 'Ordered'),
(5, 6, 4, 11, 'Unioil Gmax 1000 SN 5W-40 Fully Synthetic Gasoline Engine Oil (1L)</br>', 2, '760.00', 'ORD-633292162', 'Cash Upon Pickup', '2022-06-18', 'Ordered'),
(6, 5, 1, 10, 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L)', 1, '280.00', 'ORD-1565618214', 'Cash Upon Pickup', '2022-06-19', 'Cancelled'),
(7, 5, 1, 10, 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L)', 1, '280.00', 'ORD-510469795', 'Online Payment', '2022-06-19', 'Unpaid'),
(8, 6, 1, 13, 'Unioil Motosport Scooter 500 20W-40 Scooter Oil (1L)', 1, '205.00', 'ORD-1499145619', 'Cash Upon Pickup', '2022-06-19', 'Cancelled'),
(9, 5, 1, 10, 'Petron ATF Premium HTP Fully Synthetic Automatic Transmission Fluid (1L)', 1, '280.00', 'ORD-379447518', 'Cash Upon Pickup', '2022-06-19', 'Cancelled'),
(10, 5, 1, 9, 'Petron Rev-X HD 40 Diesel Engine Oil (4 liters)', 1, '697.00', 'ORD-948940830', 'Cash Upon Pickup', '2022-06-19', 'Cancelled'),
(11, 6, 1, 13, 'Unioil Motosport Scooter 500 20W-40 Scooter Oil (1L)', 1, '205.00', 'ORD-823031955', 'Cash Upon Pickup', '2022-06-19', 'Cancelled'),
(12, 5, 9, 8, 'Petron Blaze Racing BR450 Premium Multigrade Gasoline Engine Oil (Ultron Touring) 20W50 (1 L)', 2, '452.00', 'ORD-771967078', 'Cash Upon Pickup', '2022-06-20', 'Declined'),
(13, 6, 9, 11, 'Unioil Gmax 1000 SN 5W-40 Fully Synthetic Gasoline Engine Oil (1L)', 2, '760.00', 'ORD-1646911977', 'Cash Upon Pickup', '2022-06-20', 'Declined'),
(14, 5, 11, 9, 'Petron Rev-X HD 40 Diesel Engine Oil (4 liters)', 20, '13.00', 'ORD-1281561703', 'Cash Upon Pickup', '2022-06-21', 'Completed'),
(15, 6, 11, 12, 'Unioil ATF Automatic Transmission Fluid (1L)', 1, '190.00', 'ORD-1456702855', 'Cash Upon Pickup', '2022-06-21', 'Ordered');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `userID` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(30) NOT NULL,
  `phone_num` varchar(13) NOT NULL,
  `password` varchar(128) NOT NULL,
  `user_image` varchar(200) NOT NULL DEFAULT 'default-img.jpg',
  `user_type` int(11) NOT NULL,
  `account_status` varchar(30) NOT NULL DEFAULT 'activated',
  `otp_code` int(128) DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `active_status` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`userID`, `email`, `firstname`, `lastname`, `phone_num`, `password`, `user_image`, `user_type`, `account_status`, `otp_code`, `verified`, `active_status`) VALUES
(1, 'grldheavier@gmail.com', 'Gerald', 'Javier', '09106290138', '$2y$10$iT4QOPSnfQg4YAW9hKTGUOqWGjQcsGuRECHlYqp5PiDwiYvQeXjMi', 'gerald.jpg', 1, 'activated', 849260, 1, '2022-06-27 19:55:28'),
(2, 'greenoil_pilar@gmail.com', 'Total', 'Pilar', '09487688783', '$2y$10$2tGxB9lmDysrUFnF7CkIFO9qzfmWfjGbcLZSnm.C65buvQp5XAgtO', '284133694_824528131841545_8799996455029655294_n.png', 2, 'activated', 767455, 1, '0000-00-00 00:00:00'),
(4, 'dzeraldhabyer@gmail.com', 'Jarvis', 'Heaviest', '09483651320', '$2y$10$p.CdzG8IbAIlC6I0/7cJveiMGk34mpu4Y0uiE/c4K1amD9NmBqCbG', '16556140294686329929520921653578.jpg', 1, 'activated', 961446, 1, '0000-00-00 00:00:00'),
(5, 'petronpilar@gmail.com', 'Petron', 'Pilar', '09957688783', '$2y$10$EGxaMPXFGTj0Z9AR8MwoIuAtZ17wyZ/ChflL6C9BaNc1kPZyUWIoK', '284105943_325537509761051_7446789442195767846_n.png', 2, 'activated', 662342, 1, '2022-06-27 20:12:17'),
(6, 'unioil@gmail.com', 'UniOil', 'Pilar', '09346829834', '$2y$10$oSuFlKZDJK/6YHyr6dEt5u4GX1rFJXNsOCzPQ9ZGNPQ.qfEFfw1Xe', 'unioil.png', 2, 'activated', 164214, 1, '2022-06-21 01:35:28'),
(9, 'patvmerin@gmail.com', 'Patricia', 'Merin', '09454613818', '$2y$10$sD/8F65HJv2xr7MOq0YZsOTSujs8LkPlSBF2yfd07Wz60a8pWVr6u', 'FB_IMG_1655458370137.jpg', 1, 'activated', 789167, 1, '2022-06-21 11:33:48'),
(10, 'cjdimla1227@gmail.com', 'Christian Joseph', 'Dimla', '09455377999', '$2y$10$Y9siJes5j9qHGu214BMHrOvXgxH9htSOeNJ2gshs/1bxW2iMCA.z.', 'ako.jpg', 1, 'activated', 828600, 1, '2022-09-28 13:56:27'),
(11, 'winskeytoogood@gmail.com', 'Dale', 'Pineda', '09123456789', '$2y$10$vvO8hwIgRCUjVAuHfkROwOcRSfuWlA7TmpqKmOahv6KOGz57YCvpe', 'default-img.jpg', 1, 'activated', 388294, 1, '2022-06-27 20:12:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_carts`
--
ALTER TABLE `tbl_carts`
  ADD PRIMARY KEY (`cartID`),
  ADD KEY `customerID` (`customerID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `shopID` (`shopID`);

--
-- Indexes for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  ADD PRIMARY KEY (`chatID`),
  ADD KEY `shopID` (`user_2`),
  ADD KEY `customerID` (`user_1`);

--
-- Indexes for table `tbl_chat_contents`
--
ALTER TABLE `tbl_chat_contents`
  ADD PRIMARY KEY (`convoID`);

--
-- Indexes for table `tbl_fuel`
--
ALTER TABLE `tbl_fuel`
  ADD PRIMARY KEY (`fuelID`),
  ADD KEY `shopID` (`shopID`);

--
-- Indexes for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  ADD PRIMARY KEY (`locationID`),
  ADD KEY `shopID` (`shopID`);

--
-- Indexes for table `tbl_permits`
--
ALTER TABLE `tbl_permits`
  ADD PRIMARY KEY (`permitID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `shopID` (`shopID`);

--
-- Indexes for table `tbl_shop_ratings`
--
ALTER TABLE `tbl_shop_ratings`
  ADD PRIMARY KEY (`ratingID`),
  ADD KEY `userID` (`customerID`),
  ADD KEY `shopID` (`shopID`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`transacID`),
  ADD KEY `productID` (`productID`),
  ADD KEY `shopID` (`shopID`),
  ADD KEY `customerID` (`customerID`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_carts`
--
ALTER TABLE `tbl_carts`
  MODIFY `cartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  MODIFY `chatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_chat_contents`
--
ALTER TABLE `tbl_chat_contents`
  MODIFY `convoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tbl_fuel`
--
ALTER TABLE `tbl_fuel`
  MODIFY `fuelID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  MODIFY `locationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_permits`
--
ALTER TABLE `tbl_permits`
  MODIFY `permitID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `productID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tbl_shop_ratings`
--
ALTER TABLE `tbl_shop_ratings`
  MODIFY `ratingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `transacID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_carts`
--
ALTER TABLE `tbl_carts`
  ADD CONSTRAINT `tbl_carts_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_carts_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `tbl_products` (`productID`),
  ADD CONSTRAINT `tbl_carts_ibfk_3` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  ADD CONSTRAINT `tbl_chats_ibfk_1` FOREIGN KEY (`user_1`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_chats_ibfk_2` FOREIGN KEY (`user_2`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_chats_ibfk_3` FOREIGN KEY (`user_1`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_fuel`
--
ALTER TABLE `tbl_fuel`
  ADD CONSTRAINT `tbl_fuel_ibfk_1` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_fuel_ibfk_2` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_fuel_ibfk_3` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_locations`
--
ALTER TABLE `tbl_locations`
  ADD CONSTRAINT `tbl_locations_ibfk_1` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_locations_ibfk_2` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_permits`
--
ALTER TABLE `tbl_permits`
  ADD CONSTRAINT `tbl_permits_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD CONSTRAINT `tbl_products_ibfk_1` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_products_ibfk_2` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_shop_ratings`
--
ALTER TABLE `tbl_shop_ratings`
  ADD CONSTRAINT `tbl_shop_ratings_ibfk_1` FOREIGN KEY (`customerID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_shop_ratings_ibfk_2` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`);

--
-- Constraints for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD CONSTRAINT `tbl_transactions_ibfk_1` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_transactions_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `tbl_products` (`productID`),
  ADD CONSTRAINT `tbl_transactions_ibfk_3` FOREIGN KEY (`shopID`) REFERENCES `tbl_users` (`userID`),
  ADD CONSTRAINT `tbl_transactions_ibfk_4` FOREIGN KEY (`customerID`) REFERENCES `tbl_users` (`userID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
