-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2024 at 10:25 AM
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
-- Database: `dealkade`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-09-11 15:01:23', '2024-09-11 15:01:23'),
(2, 1, '2024-09-11 15:01:23', '2024-09-11 15:01:23'),
(3, 2, '2024-09-11 15:01:23', '2024-09-11 15:01:23'),
(4, 2, '2024-09-11 15:01:23', '2024-09-11 15:01:23'),
(5, 3, '2024-09-11 15:01:23', '2024-09-11 15:01:23'),
(6, 7, '2024-09-20 02:03:41', '2024-09-20 02:03:41');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '2024-09-11 15:02:25', '2024-09-11 15:02:25'),
(2, 1, 3, 1, '2024-09-11 15:02:25', '2024-09-11 15:02:25'),
(3, 2, 2, 1, '2024-09-11 15:02:25', '2024-09-11 15:02:25'),
(4, 2, 4, 1, '2024-09-11 15:02:25', '2024-09-11 15:02:25'),
(5, 3, 5, 3, '2024-09-11 15:02:25', '2024-09-11 15:02:25'),
(6, 6, 1, 2, '2024-09-20 02:39:35', '2024-09-20 02:39:35'),
(7, 6, 3, 1, '2024-09-20 02:39:45', '2024-09-20 02:39:45');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Fiction', 'Imaginary events and people', '2024-09-20 08:12:27', '2024-09-20 08:12:27'),
(2, 'Non-Fiction', 'Real events and facts', '2024-09-20 08:12:27', '2024-09-20 08:12:27'),
(3, 'Science', 'Books about scientific concepts', '2024-09-20 08:12:27', '2024-09-20 08:12:27'),
(4, 'Children\'s Books', 'Books for young readers', '2024-09-20 08:12:27', '2024-09-20 08:12:27'),
(5, 'Self-Help', 'Books for personal development', '2024-09-20 08:12:27', '2024-09-20 08:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL CHECK (`status` in ('Active','Expired')),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_percentage`, `expiry_date`, `status`, `created_at`) VALUES
(1, 'SAVE10', 10.00, '2024-12-31', 'active', '2024-09-11 14:23:49'),
(2, 'HOLIDAY20', 20.00, '2024-12-25', 'expired', '2024-09-11 14:23:49'),
(3, 'NEWYEAR30', 30.00, '2025-01-01', 'active', '2024-09-11 14:23:49'),
(4, 'SUMMER15', 15.00, '2024-08-31', 'expired', '2024-09-11 14:23:49'),
(5, 'FALL5', 5.00, '2024-10-15', 'active', '2024-09-11 14:23:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 799.98, 'Delivered', '2024-09-11 14:59:44', '2024-09-11 14:59:44'),
(2, 2, 999.99, 'Processing', '2024-09-11 14:59:44', '2024-09-11 14:59:44'),
(3, 3, 89.99, 'Shipped', '2024-09-11 14:59:44', '2024-09-11 14:59:44'),
(4, 4, 499.99, 'Cancelled', '2024-09-11 14:59:44', '2024-09-11 14:59:44');

-- --------------------------------------------------------

--
-- Table structure for table `orders_items`
--

CREATE TABLE `orders_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price_at_purchase` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_items`
--

INSERT INTO `orders_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 699.99, '2024-09-11 15:03:38', '2024-09-11 15:03:38'),
(2, 1, 3, 1, 89.99, '2024-09-11 15:03:38', '2024-09-11 15:03:38'),
(3, 2, 2, 1, 999.99, '2024-09-11 15:03:38', '2024-09-11 15:03:38'),
(4, 3, 3, 1, 89.99, '2024-09-11 15:03:38', '2024-09-11 15:03:38'),
(5, 4, 4, 1, 499.99, '2024-09-11 15:03:38', '2024-09-11 15:03:38');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(4) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'The Great Gatsby', 'A classic novel set in the 1920s', 1500.00, 100, 1, '2024-09-20 08:15:51', '2024-09-20 08:15:51'),
(2, 'Sapiens: A Brief History of Humankind', 'Explores the history of humankind', 2200.00, 75, 2, '2024-09-20 08:15:51', '2024-09-20 08:15:51'),
(3, 'A Brief History of Time', 'An overview of cosmology by Stephen Hawking', 2500.00, 50, 3, '2024-09-20 08:15:51', '2024-09-20 08:15:51'),
(4, 'The Cat in the Hat', 'A children\'s book by Dr. Seuss', 900.00, 150, 4, '2024-09-20 08:15:51', '2024-09-20 08:15:51'),
(5, 'How to Win Friends and Influence People', 'A self-help classic', 1800.00, 80, 5, '2024-09-20 08:15:51', '2024-09-20 08:15:51');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `img_url`, `product_id`) VALUES
(1, '../images/great_gatsby.jpg', 1),
(2, '../images/sapiens.jpg', 2),
(3, '../images/brief_history_of_time.jpg', 3),
(4, '../images/cat_in_the_hat.jpg', 4),
(5, '../images/how_to_win_friends.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 'Great product!', '2024-09-11 15:04:50', '2024-09-11 15:04:50'),
(2, 2, 2, 5, 'Loved it!', '2024-09-11 15:04:50', '2024-09-11 15:04:50'),
(3, 1, 3, 3, 'Good value for money', '2024-09-11 15:04:50', '2024-09-11 15:04:50'),
(4, 3, 4, 2, 'Not satisfied', '2024-09-11 15:04:50', '2024-09-11 15:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username_or_email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username_or_email`, `password`, `role`, `name`, `address`, `phone_number`, `created_at`, `updated_at`) VALUES
(1, 'john.doe@example.com', 'password123', 'customer', 'John Doe', '123 Main St', '0712345678', '2024-09-11 14:47:32', '2024-09-11 14:47:32'),
(2, 'jane.smith@example.com', 'password456', 'customer', 'Jane Smith', '456 Oak St', '0712345679', '2024-09-11 14:47:32', '2024-09-11 14:47:32'),
(3, 'admin@example.com', 'adminpass', 'admin', 'Admin User', '789 Pine St', '0712345680', '2024-09-11 14:47:32', '2024-09-11 14:47:32'),
(4, 'mark.will@example.com', 'markpass', 'customer', 'Mark Will', '321 Birch St', '0712345681', '2024-09-11 14:47:32', '2024-09-11 14:47:32'),
(6, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', NULL, NULL, NULL, '2024-09-20 02:00:44', '2024-09-20 02:00:44'),
(7, 'ucsc', 'd32934b31349d77e70957e057b1bcd28', 'customer', NULL, NULL, NULL, '2024-09-20 02:02:45', '2024-09-20 02:02:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_ibfk_1` (`user_id`);

--
-- Indexes for table `orders_items`
--
ALTER TABLE `orders_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`username_or_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders_items`
--
ALTER TABLE `orders_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders_items`
--
ALTER TABLE `orders_items`
  ADD CONSTRAINT `orders_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orders_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
