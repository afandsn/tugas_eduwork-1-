-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 01:42 PM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `stok` int(11) NOT NULL,
  `kategori` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `harga`, `deskripsi`, `stok`, `kategori`, `gambar`) VALUES
(1, 'boneka', 2000, 'mainan', 1, 'mainan', 'Screenshot 2025-04-04 070438.png'),
(2, 'boneka', 2000, 'mainan', 1, 'mainan', 'Screenshot 2025-02-15 153914.png'),
(3, 'boneka', 2000, 'mainan', 1, 'mainan', 'Screenshot 2025-02-15 153914.png'),
(4, 'boneka', 2000, 'mainan', 1, 'mainan', 'Screenshot 2025-02-15 153914.png'),
(5, 'boneka', 2000, 'mainan', 1, 'mainan', 'Screenshot 2024-01-31 101622.png'),
(6, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743944173_67f279ed2a705.png'),
(7, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945005_67f27d2d42e28.png'),
(8, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945031_67f27d47df951.png'),
(9, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945477_67f27f0549a7b.png'),
(10, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945492_67f27f142354c.png'),
(11, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945671_67f27fc7e25c9.png'),
(12, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945684_67f27fd4da36d.png'),
(13, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945746_67f28012d9a35.png'),
(14, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945754_67f2801a74c5d.png'),
(15, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945775_67f2802fa8155.png'),
(16, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743945798_67f2804693e16.png'),
(17, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743946188_67f281ccdae1c.png'),
(18, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743946195_67f281d301701.png'),
(19, 'laptop asus', 2000, 'mainan', 1, 'elektronik ', '1743946219_67f281eb45451.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
