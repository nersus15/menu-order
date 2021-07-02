-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 02, 2021 at 04:22 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `category_name`, `category_description`, `created_at`) VALUES
(1, 'Elektronik', '', '2021-04-23 21:19:36'),
(2, 'Makanan & Minuman', '', '2021-04-23 21:19:43'),
(3, 'Bahan Baku', '', '2021-04-23 21:19:50'),
(4, 'ATK (Alat Tulis Kantor)', '', '2021-06-11 17:42:46');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id_customer` int(11) NOT NULL,
  `customer_code` varchar(64) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` char(16) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id_customer`, `customer_code`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `created_at`) VALUES
(1, 'CUS24042021001', 'Site Customer', 'customer@mail.com', '081939448487', 'Jl. Bunga Matahari, No.11 Gomong Lama, Mataram.', '2021-04-24 10:37:52'),
(2, 'CUS11062021002', 'Muhammad Kuswari', 'ahsdkjashdsad', '12632179863', 'kajlhdskdhasd asdjasd', '2021-06-11 17:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `incoming_items`
--

CREATE TABLE `incoming_items` (
  `id_incoming_items` int(11) NOT NULL,
  `id_items` int(11) DEFAULT NULL,
  `id_supplier` int(11) DEFAULT NULL,
  `incoming_item_code` varchar(64) NOT NULL,
  `incoming_item_qty` int(11) NOT NULL,
  `incoming_item_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `incoming_items`
--

INSERT INTO `incoming_items` (`id_incoming_items`, `id_items`, `id_supplier`, `incoming_item_code`, `incoming_item_qty`, `incoming_item_date`) VALUES
(2, 1, 2, 'TRX-M11062021001', 21, '2021-06-11 18:40:19'),
(3, 1, 2, 'TRX-M23062021002', 32, '2021-06-23 12:59:02'),
(4, 1, 1, 'TRX-M23062021003', 6, '2021-06-23 13:21:57'),
(5, 1, 1, 'TRX-M23062021003', 6, '2021-06-23 13:26:08'),
(6, 1, 1, 'TRX-M23062021003', 6, '2021-06-23 13:27:12'),
(7, 2, 1, 'TRX-M23062021004', 1, '2021-06-23 13:31:06'),
(8, 1, 1, 'TRX-M23062021005', 2, '2021-06-23 13:38:33'),
(9, 2, 2, 'TRX-M23062021006', 33, '2021-06-23 13:38:54'),
(10, 2, 1, 'TRX-M23062021007', 2, '2021-06-23 13:40:31'),
(11, 2, 1, 'TRX-M23062021008', 1, '2021-06-23 13:40:50'),
(12, 1, 1, 'TRX-M23062021009', 6, '2021-06-23 13:41:33'),
(13, 2, 2, 'TRX-M23062021010', 4, '2021-06-23 13:43:16'),
(14, 3, 2, 'TRX-M24062021011', 5, '2021-06-24 15:16:38'),
(15, 3, 2, 'TRX-M24062021012', 2, '2021-06-24 15:24:22');

--
-- Triggers `incoming_items`
--
DELIMITER $$
CREATE TRIGGER `barang_masuk` AFTER INSERT ON `incoming_items` FOR EACH ROW BEGIN
	UPDATE items SET item_stock=item_stock+NEW.incoming_item_qty
    
    WHERE id_item = NEW.id_items;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id_item` int(11) NOT NULL,
  `id_category` int(11) DEFAULT NULL,
  `id_unit` int(11) DEFAULT NULL,
  `item_code` varchar(64) NOT NULL,
  `item_name` varchar(128) NOT NULL,
  `item_image` varchar(255) NOT NULL,
  `item_stock` int(11) NOT NULL,
  `item_stock_min` int(11) NOT NULL,
  `item_price` float NOT NULL,
  `item_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id_item`, `id_category`, `id_unit`, `item_code`, `item_name`, `item_image`, `item_stock`, `item_stock_min`, `item_price`, `item_description`, `created_at`) VALUES
(1, 1, 3, 'BRG23042021001', 'Monitor SPC Pro SM-24', 'BRG23042021001.jpeg', 110, 0, 1400000, 'Monitor SPC PRO SM-24, Brand Lokal dengan Kualitas Mantap', '2021-06-23 13:41:33'),
(2, 4, 2, 'BRG11062021002', 'test', 'BRG11062021002.png', 120, 0, 50000, 'ashajhsasasasas', '2021-06-23 13:43:16'),
(3, 1, 3, 'BRG24062021003', 'PCB', 'default.png', 8, 0, 290400, 'Testing Average', '2021-06-24 15:52:27');

-- --------------------------------------------------------

--
-- Table structure for table `outcoming_items`
--

CREATE TABLE `outcoming_items` (
  `id_outcoming_item` int(11) NOT NULL,
  `id_items` int(11) DEFAULT NULL,
  `id_customer` int(11) DEFAULT NULL,
  `outcoming_item_code` varchar(64) NOT NULL,
  `outcoming_item_qty` int(11) NOT NULL,
  `outcoming_item_price` float NOT NULL,
  `outcoming_item_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `outcoming_items`
--

INSERT INTO `outcoming_items` (`id_outcoming_item`, `id_items`, `id_customer`, `outcoming_item_code`, `outcoming_item_qty`, `outcoming_item_price`, `outcoming_item_date`) VALUES
(3, 1, 1, 'TRX-K11062021001', 12, 0, '2021-06-11 18:30:28'),
(4, 2, 2, 'TRX-K11062021002', 21, 0, '2021-06-11 18:57:48');

--
-- Triggers `outcoming_items`
--
DELIMITER $$
CREATE TRIGGER `barang_keluar` AFTER INSERT ON `outcoming_items` FOR EACH ROW BEGIN
	UPDATE items SET item_stock=item_stock-NEW.outcoming_item_qty
    WHERE id_item = NEW.id_items;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id_supplier` int(11) NOT NULL,
  `supplier_code` varchar(64) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_email` varchar(100) NOT NULL,
  `supplier_phone` char(16) DEFAULT NULL,
  `supplier_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id_supplier`, `supplier_code`, `supplier_name`, `supplier_email`, `supplier_phone`, `supplier_address`, `created_at`) VALUES
(1, 'SPL24042021001', 'Site Supplier', 'supplier@mail.com', '081939448487', 'Jl. Bunga Matahari, No.11 Gomong Lama, Mataram.', '2021-04-24 10:37:18'),
(2, 'SPL11062021002', 'Muhammad Kuswari', 'muhammad.kuswari10@gmail.com', '081939448487', 'Jl. Bunga Matahari, No.11 Gomong Lama, Mataram.', '2021-06-11 17:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id_unit` int(11) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `unit_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id_unit`, `unit_name`, `unit_description`, `created_at`) VALUES
(1, 'Bungkus', '', '2021-04-23 21:18:37'),
(2, 'Kotak', '', '2021-04-23 21:18:42'),
(3, 'Pcs', '', '2021-04-23 21:18:48'),
(4, 'Liter', '', '2021-04-23 21:18:52'),
(5, 'Kilogram', '', '2021-04-23 21:19:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_phone` char(16) DEFAULT NULL,
  `user_address` text DEFAULT NULL,
  `user_avatar` varchar(255) DEFAULT 'default.jpg',
  `user_password` varchar(255) NOT NULL,
  `user_role` enum('admin','staff') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `user_name`, `user_email`, `user_phone`, `user_address`, `user_avatar`, `user_password`, `user_role`, `created_at`) VALUES
(1, 'Site Administrator', 'admin@mail.com', '081939448487', 'Jl. Bunga Matahari, No.11 Gomong Lama, Mataram.', 'default.jpg', '$2y$10$/ePeCDWkJXlcHhqjD4vje.zO6r2ejISJTJ4AS4mtLt1JIDBNwoFRu', 'admin', '2021-04-24 10:36:22'),
(2, 'Site Staff', 'staff@mail.com', '085156031903', 'Jl. Bunga Matahari, No.11 Gomong Lama, Mataram', 'default.jpg', '$2y$10$ychqFGRIZFy5UzR0FIrxV.kHbpSZLIK7Dnabw1NUh6eED/iCbE0Uu', 'staff', '2021-04-24 10:39:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `incoming_items`
--
ALTER TABLE `incoming_items`
  ADD PRIMARY KEY (`id_incoming_items`),
  ADD KEY `id_items` (`id_items`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `id_category` (`id_category`),
  ADD KEY `id_unit` (`id_unit`);

--
-- Indexes for table `outcoming_items`
--
ALTER TABLE `outcoming_items`
  ADD PRIMARY KEY (`id_outcoming_item`),
  ADD KEY `id_items` (`id_items`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id_unit`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `incoming_items`
--
ALTER TABLE `incoming_items`
  MODIFY `id_incoming_items` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `outcoming_items`
--
ALTER TABLE `outcoming_items`
  MODIFY `id_outcoming_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `incoming_items`
--
ALTER TABLE `incoming_items`
  ADD CONSTRAINT `incoming_items_ibfk_1` FOREIGN KEY (`id_items`) REFERENCES `items` (`id_item`),
  ADD CONSTRAINT `incoming_items_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id_supplier`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`id_category`) REFERENCES `categories` (`id_category`),
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`id_unit`) REFERENCES `units` (`id_unit`);

--
-- Constraints for table `outcoming_items`
--
ALTER TABLE `outcoming_items`
  ADD CONSTRAINT `outcoming_items_ibfk_1` FOREIGN KEY (`id_items`) REFERENCES `items` (`id_item`),
  ADD CONSTRAINT `outcoming_items_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customers` (`id_customer`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
