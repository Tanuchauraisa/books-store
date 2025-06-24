-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2025 at 10:21 AM
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
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(1, 3, 2, 5, '2025-06-24 05:08:35'),
(2, 3, 1, 1, '2025-06-24 05:12:36'),
(3, 3, 3, 1, '2025-06-24 05:12:40'),
(4, 4, 1, 2, '2025-06-24 05:26:35'),
(11, 6, 1, 1, '2025-06-24 06:48:52'),
(12, 6, 9, 5, '2025-06-24 06:50:13'),
(20, 5, 9, 6, '2025-06-24 08:13:00'),
(21, 5, 6, 3, '2025-06-24 08:13:08');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `shipping_name` varchar(255) NOT NULL,
  `shipping_email` varchar(100) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `created_at`, `shipping_name`, `shipping_email`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_zip`, `payment_method`, `shipping_phone`) VALUES
(1, 5, 20.00, 'cancelled', '2025-06-24 07:40:41', 'admin bhi', 'admin@admin.com', 'test', 'Irinjalakuda', 'Kerala', 'ASF', 'Credit Card', NULL),
(2, 5, 20.00, 'cancelled', '2025-06-24 07:51:35', 'admin bhi', 'admin@admin.com', 'test', 'Irinjalakuda', 'Kerala', 'ASF', 'Credit Card', NULL),
(3, 5, 20.00, 'completed', '2025-06-24 07:56:54', 'admin bhi', 'admin@admin.com', 'test', 'Irinjalakuda', 'Kerala', 'ASF', 'Credit Card', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 9, 1, 20.00),
(2, 2, 9, 1, 20.00),
(3, 3, 9, 1, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `author`, `price`, `description`, `image`, `category`, `created_at`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 12.99, 'A classic novel of the Jazz Age', NULL, 'Fiction', '2025-06-23 11:54:39'),
(2, 'To Kill a Mockingbird', 'Harper Lee', 10.99, 'Pulitzer Prize-winning masterpiece', NULL, 'Fiction', '2025-06-23 11:54:39'),
(3, '1984', 'George Orwell', 9.99, 'Dystopian social science fiction', NULL, 'Sci-Fi', '2025-06-23 11:54:39'),
(4, 'Pride and Prejudice', 'Jane Austen', 8.99, 'Romantic novel of manners', NULL, 'Romance', '2025-06-23 11:54:39'),
(5, 'The Hobbit', 'J.R.R. Tolkien', 14.99, 'Fantasy novel and children\'s book', NULL, 'Fantasy', '2025-06-23 11:54:39'),
(6, 'The Catcher in the Rye', 'J.D. Salinger', 11.49, 'Coming-of-age novel', NULL, 'Fiction', '2025-06-23 11:54:39'),
(7, 'Brave New World', 'Aldous Huxley', 10.29, 'Dystopian science fiction', NULL, 'Sci-Fi', '2025-06-23 11:54:39'),
(8, 'The Lord of the Rings', 'J.R.R. Tolkien', 24.99, 'Epic high-fantasy novel', NULL, 'Fantasy', '2025-06-23 11:54:39'),
(9, 'hello hhhhhhhhhhh', 'gourav', 20.00, 'mai to hu', '685a43471c4d0_ChatGPT Image Jun 7, 2025, 10_46_58 AM.png', 'Non-Fiction', '2025-06-24 06:18:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@bookstore.com', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9', 'admin', '2025-06-23 11:54:38'),
(3, 'gourav', 'gourav@gmail.com', '$2y$10$DGCE22Ob/dCvkBItFrkbVOAmcsx28cpnnmmz1/meK931R9j1Vp6le', 'customer', '2025-06-24 05:03:59'),
(5, 'admin', 'admin@admin.com', '$2y$10$3D3V9PLfbcCMFrT98/SQY.GGduv69i.2c41T8y4iFRq3TZHag.Hge', 'admin', '2025-06-24 06:00:31'),

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
