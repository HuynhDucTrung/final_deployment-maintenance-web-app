-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2024 at 03:03 AM
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
-- Database: `login_register`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`) VALUES
(1, 'Samsung Galaxy S21', 19990000, 'Samsung Galaxy S21 Smartphone', '66a1e960292c45.50937410.jpg'),
(2, 'Samsung Galaxy S21+', 23990000, 'Samsung Galaxy S21+ Smartphone', '66a1e957690f03.80161301.jpg'),
(3, 'Samsung Galaxy S21 Ultra', 30990000, 'Samsung Galaxy S21 Ultra Smartphone', '66a1e9eb4d6175.82402939.jpg'),
(4, 'Samsung Galaxy Note20', 22990000, 'Samsung Galaxy Note20 Smartphone', '66a1eb3b3bdfc3.23676429.jpg'),
(5, 'Samsung Galaxy Note20 Ultra', 29990000, 'Samsung Galaxy Note20 Ultra Smartphone', '66a1ec30387ca4.25693593.jpg'),
(6, 'Samsung Galaxy A52', 8990000, 'Samsung Galaxy A52 Smartphone', '66a1ec7bf3b5d0.70441582.jpg'),
(7, 'Samsung Galaxy A72', 10990000, 'Samsung Galaxy A72 Smartphone', '66a1edf265d5e7.99807301.jpg'),
(8, 'Samsung Galaxy M32', 5990000, 'Samsung Galaxy M32 Smartphone', '66a1ee143c0c19.29962525.jpg'),
(9, 'Samsung Galaxy M42', 7990000, 'Samsung Galaxy M42 Smartphone', '66a1ee3036c402.19205162.jpg'),
(10, 'Samsung Galaxy Z Fold3', 40990000, 'Samsung Galaxy Z Fold3 Smartphone', '66a1ee97891143.11747053.jpg'),
(11, 'Samsung Galaxy Z Flip3', 25990000, 'Samsung Galaxy Z Flip3 Smartphone', '66a1eee9403611.46352978.jpg'),
(12, 'Samsung Galaxy Buds Pro', 4990000, 'Samsung Galaxy Buds Pro Accessory', '66a1ef757899b3.79764352.jpg'),
(13, 'Samsung Galaxy Buds Live', 3490000, 'Samsung Galaxy Buds Live Accessory', '66a76ebf362fa3.11610646.jpg'),
(14, 'Samsung Galaxy Buds+', 2990000, 'Samsung Galaxy Buds+ Accessory', '66a77190b26da6.23746314.jpg'),
(15, 'Samsung Wireless Charger', 1490000, 'Samsung Wireless Charger Accessory', '66a771e9162228.62942646.jpg'),
(16, 'Samsung Portable SSD T7', 2990000, 'Samsung Portable SSD T7 Accessory', '66a772ce396fb8.84822399.jpg'),
(17, 'Samsung Galaxy SmartTag', 890000, 'Samsung Galaxy SmartTag Accessory', '66a77318488db7.81285302.jpg'),
(18, 'Samsung DeX Station', 2390000, 'Samsung DeX Station Accessory', '66a7733db9c446.66399348.jpg'),
(19, 'Samsung 45W USB-C Charger', 790000, 'Samsung 45W USB-C Charger Accessory', '66a7736b8bb6f7.04840731.jpg'),
(20, 'Samsung 25W USB-C Charger', 590000, 'Samsung 25W USB-C Charger Accessory', '66a773a1d6aaa3.79867734.jpg'),
(21, 'Samsung Galaxy Watch4', 6990000, 'Samsung Galaxy Watch4 Smartwatch', '66a779579cdd17.56197822.jpg'),
(22, 'Samsung Galaxy Watch4 Classic', 8990000, 'Samsung Galaxy Watch4 Classic Smartwatch', '66a77475741e74.60423391.jpg'),
(23, 'Samsung Galaxy Watch3', 7990000, 'Samsung Galaxy Watch3 Smartwatch', '66a779e23519f7.85771581.jpg'),
(24, 'Samsung Galaxy Fit2', 1190000, 'Samsung Galaxy Fit2 Smartwatch', '66a77548e7e664.52895813.jpg'),
(25, 'Samsung Galaxy Fit', 990000, 'Samsung Galaxy Fit Smartwatch', '66a7761f9160c3.84341071.jpg'),
(26, 'Samsung Galaxy Watch Active2', 5990000, 'Samsung Galaxy Watch Active2 Smartwatch', '66a7764b007cf6.06201333.jpg'),
(27, 'Samsung Galaxy Watch 6 Classic', 5990000, 'Samsung Galaxy Watch Active Smartwatch', '66a77e7c7be839.49099906.jpeg'),
(28, 'Samsung Galaxy Watch 5', 5990000, 'Samsung Galaxy Watch Smartwatch', '66a7798b0c78c3.71705274.jpg'),
(29, 'Samsung Gear S3', 5990000, 'Samsung Gear S3 Smartwatch', '66a776e8f3cc10.28488840.jpg'),
(30, 'Samsung Gear Sport', 4990000, 'Samsung Gear Sport Smartwatch', '66a7770e02b0b0.22012476.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Aktar', 'aktar@gmail.com', '$2y$10$Jmf9Xk2y8m.fo3c/ZgKmzOrdIRkU05KSGLI0picKLEtr68ll7hjB.'),
(2, 'Duy', 'abc@gmail.com', '$2y$10$95kBpMgCp6paWAQJ0QGemeXtJ1P9u7VOUC8juF4mPY1V4X2r4RHaW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
