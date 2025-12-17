-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 10:48 PM
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
-- Database: `food`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` varchar(5) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `status` enum('Pending','Reviewed','Resolved') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `message`, `rating`, `status`, `created_at`) VALUES
('F0001', 'U001', 'Great service! I am very happy with the product.', 5, 'Reviewed', '2025-04-27 23:12:33'),
('F0002', 'U002', 'The experience was good, but the delivery was a bit slow.', 4, 'Resolved', '2025-04-27 23:12:33'),
('F0003', 'U003', 'Loved the quality, but the packaging could be better.', 4, 'Resolved', '2025-04-27 23:12:33'),
('F0004', 'U004', 'Excellent customer support! I will definitely recommend.', 5, 'Resolved', '2025-04-27 23:12:33'),
('F0005', 'U005', 'Not satisfied with the product. It arrived damaged.', 2, 'Reviewed', '2025-04-27 23:12:33'),
('F0006', 'U006', 'Great quality, fast shipping, very happy with the purchase!', 5, 'Resolved', '2025-04-27 23:12:33');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` varchar(5) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status` enum('Pending','Paid','Shipped','Completed','Cancelled') DEFAULT 'Pending',
  `order_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `status`, `order_date`) VALUES
('O0001', 'U001', 13.49, 'Pending', '2025-12-15 00:42:29'),
('O0002', 'U002', 12.00, 'Completed', '2025-12-15 00:43:21'),
('O0003', 'U003', 13.07, 'Pending', '2025-12-15 00:44:07'),
('O0004', 'U004', 13.07, 'Pending', '2025-12-15 00:47:02'),
('O0005', 'U005', 21.76, 'Pending', '2025-12-15 00:47:24'),
('O0006', 'U004', 11.37, 'Completed', '2025-12-15 00:54:10'),
('O0007', 'U001', 13.49, 'Pending', '2025-12-17 05:42:16'),
('O0008', 'U001', 23.99, 'Pending', '2025-12-17 05:43:01'),
('O0009', 'U001', 13.49, 'Pending', '2025-12-17 05:47:20');

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` varchar(5) NOT NULL,
  `order_id` varchar(10) NOT NULL,
  `product_id` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_item`
--

INSERT INTO `order_item` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`) VALUES
('OI001', 'O0001', 'P0001', 1, 8.90, 8.90),
('OI002', 'O0001', 'P0005', 1, 3.50, 3.50),
('OI003', 'O0002', 'P0002', 1, 9.90, 9.90),
('OI004', 'O0002', 'P0005', 1, 2.10, 2.10),
('OI005', 'O0003', 'P0003', 1, 7.90, 7.90),
('OI006', 'O0003', 'P0005', 1, 3.50, 3.50),
('OI007', 'O0007', 'P0002', 1, 9.90, 9.90),
('OI008', 'O0008', 'P0001', 1, 8.90, 8.90),
('OI010', 'O0008', 'P0003', 1, 10.90, 10.90),
('OI011', 'O0009', 'P0002', 1, 9.90, 9.90);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` varchar(5) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `product_description` text DEFAULT NULL,
  `category` enum('Rice','Noodles','Chicken','Burgers','Drinks','Desserts') NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `product_description`, `category`, `quantity`, `price`, `photo`) VALUES
('P0001', 'Spicy Fried Chicken Rice', 'Crispy fried chicken with spicy sauce served with rice', 'Rice', 99, 8.90, 'rice1.png'),
('P0002', 'Salted Egg Chicken Rice', 'Fried chicken coated in creamy salted egg sauce', 'Rice', 98, 9.90, 'rice2.png'),
('P0003', 'Korean Chicken Rice', 'Korean style fried chicken with sweet spicy sauce', 'Rice', 99, 10.90, 'rice3.png'),
('P0004', 'Ayam Geprek Rice', 'Indonesian style smashed chicken with sambal', 'Rice', 100, 9.50, 'rice4.png'),
('P0005', 'Black Pepper Chicken Rice', 'Chicken stir fried in black pepper sauce', 'Rice', 100, 9.20, 'rice5.png'),
('P0006', 'Butter Chicken Rice', 'Creamy butter chicken served with rice', 'Rice', 100, 9.80, 'rice6.png'),
('P0007', 'Teriyaki Chicken Rice', 'Grilled chicken with teriyaki sauce', 'Rice', 100, 9.60, 'rice7.png'),
('P0008', 'Sweet and Sour Chicken Rice', 'Chicken in sweet and sour sauce', 'Rice', 100, 9.40, 'rice8.png'),
('P0009', 'Crispy Chicken Rice', 'Golden crispy chicken served with rice', 'Rice', 100, 8.80, 'rice9.png'),
('P0010', 'Sambal Chicken Rice', 'Spicy sambal chicken with fragrant rice', 'Rice', 100, 9.00, 'rice10.png'),
('P0011', 'Fried Mee Goreng', 'Classic Malaysian fried noodles', 'Noodles', 100, 7.90, 'noodle1.png'),
('P0012', 'Chicken Kuey Teow', 'Stir fried flat noodles with chicken', 'Noodles', 100, 8.50, 'noodle2.png'),
('P0013', 'Char Kuey Teow', 'Penang style fried kuey teow', 'Noodles', 100, 8.90, 'noodle3.png'),
('P0014', 'Chicken Ramen', 'Japanese ramen with chicken broth', 'Noodles', 100, 11.50, 'noodle4.png'),
('P0015', 'Curry Mee', 'Spicy coconut curry noodle soup', 'Noodles', 100, 9.80, 'noodle5.png'),
('P0016', 'Tom Yam Noodles', 'Thai spicy and sour noodle soup', 'Noodles', 100, 9.50, 'noodle6.png'),
('P0017', 'Wantan Mee', 'Egg noodles with wantan and char siu chicken', 'Noodles', 100, 8.80, 'noodle7.png'),
('P0018', 'Stir Fried Udon', 'Japanese udon stir fried with vegetables', 'Noodles', 100, 10.50, 'noodle8.png'),
('P0019', 'Crispy Fried Chicken', 'Crispy deep fried chicken pieces', 'Chicken', 100, 7.90, 'chicken1.png'),
('P0020', 'Korean Fried Chicken Spicy', 'Spicy Korean fried chicken', 'Chicken', 100, 9.90, 'chicken2.png'),
('P0021', 'Korean Fried Chicken Soy Garlic', 'Soy garlic flavoured fried chicken', 'Chicken', 100, 9.90, 'chicken3.png'),
('P0022', 'BBQ Chicken Wings', 'Smoky barbecue chicken wings', 'Chicken', 100, 8.50, 'chicken4.png'),
('P0023', 'Chicken Popcorn', 'Bite sized crispy chicken popcorn', 'Chicken', 100, 6.90, 'chicken5.png'),
('P0024', 'Chicken Nuggets', 'Golden fried chicken nuggets', 'Chicken', 100, 6.50, 'chicken6.png'),
('P0025', 'Honey Glazed Chicken', 'Sweet honey glazed chicken', 'Chicken', 100, 8.90, 'chicken7.png'),
('P0026', 'Black Pepper Chicken', 'Spicy black pepper chicken', 'Chicken', 100, 8.80, 'chicken8.png'),
('P0027', 'Teriyaki Chicken', 'Chicken cooked in teriyaki sauce', 'Chicken', 100, 9.20, 'chicken9.png'),
('P0028', 'Spicy Chicken Bites', 'Hot and spicy chicken bites', 'Chicken', 100, 7.50, 'chicken10.png'),
('P0029', 'Classic Chicken Burger', 'Classic burger with crispy chicken patty', 'Burgers', 100, 7.50, 'burger1.png'),
('P0030', 'Spicy Chicken Burger', 'Spicy crispy chicken burger', 'Burgers', 100, 7.90, 'burger2.png'),
('P0031', 'Cheese Chicken Burger', 'Chicken burger topped with cheese', 'Burgers', 100, 8.20, 'burger3.png'),
('P0032', 'Crispy Zinger Burger', 'Crispy spicy chicken burger', 'Burgers', 100, 8.90, 'burger4.png'),
('P0033', 'Double Chicken Burger', 'Double chicken patty burger', 'Burgers', 100, 9.90, 'burger5.png'),
('P0034', 'Beef Burger', 'Juicy beef patty burger', 'Burgers', 100, 8.50, 'burger6.png'),
('P0035', 'Cheese Beef Burger', 'Beef burger with melted cheese', 'Burgers', 100, 9.20, 'burger7.png'),
('P0036', 'Ramly Special Burger', 'Malaysian style Ramly burger', 'Burgers', 100, 7.80, 'burger8.png'),
('P0037', 'Lemon Tea', 'Refreshing iced lemon tea', 'Drinks', 100, 3.50, 'drink1.png'),
('P0038', 'Iced Milk Tea', 'Classic iced milk tea', 'Drinks', 100, 4.50, 'drink2.png'),
('P0039', 'Iced Coffee', 'Cold brewed iced coffee', 'Drinks', 100, 4.90, 'drink3.png'),
('P0040', 'Milo Ice', 'Chilled Milo chocolate drink', 'Drinks', 100, 4.00, 'drink4.png'),
('P0041', 'Chocolate Milkshake', 'Rich chocolate milkshake', 'Drinks', 100, 5.90, 'drink5.png'),
('P0042', 'Strawberry Milkshake', 'Creamy strawberry milkshake', 'Drinks', 100, 5.90, 'drink6.png'),
('P0043', 'Fresh Orange Juice', 'Freshly squeezed orange juice', 'Drinks', 100, 4.80, 'drink7.png'),
('P0044', 'Green Tea', 'Refreshing iced green tea', 'Drinks', 100, 3.80, 'drink8.png'),
('P0045', 'Chocolate Cake Slice', 'Rich chocolate cake slice', 'Desserts', 100, 6.50, 'dessert1.png'),
('P0046', 'Cheesecake Slice', 'Creamy cheesecake slice', 'Desserts', 100, 6.90, 'dessert2.png'),
('P0047', 'Brownies', 'Fudgy chocolate brownies', 'Desserts', 100, 5.90, 'dessert3.png'),
('P0048', 'Chocolate Muffin', 'Soft chocolate muffin', 'Desserts', 100, 4.90, 'dessert4.png'),
('P0049', 'Vanilla Ice Cream', 'Classic vanilla ice cream scoop', 'Desserts', 100, 4.50, 'dessert5.png'),
('P0050', 'Waffle with Chocolate Sauce', 'Warm waffle topped with chocolate sauce', 'Desserts', 100, 7.50, 'dessert6.png');

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `token_id` varchar(100) NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `expiry_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` varchar(5) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `role` enum('admin','member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `name`, `photo`, `role`) VALUES
('U001', 'joeylaumh06@gmail.com', '$2y$10$3FEyxrf07ljSkDzHx3QXFuN9PXvpQfaPhPmFGLfl8YBnbyvXEWSmq', 'Joey Lau Ming Hui', '1.png', 'admin'),
('U002', 'qiyan@gmail.com', '$2y$10$10xd/6I65HbDZbiDGXtSv.ttkimNpNQ.HsasnF5ac3oopSMyOXGoK', 'Chong Qi Yan', '2.png', 'admin'),
('U003', 'chunhoe@gmail.com', '$2y$10$.LBlSGhMjOv1mXHJplSjJ.60lPTxvTrh8Uw759cZV4DUI8MFk3qI.', 'Lew Chun Hoe', '20251216_193231_Screenshot 2025-12-17 023210.png', 'admin'),
('U004', 'alice@gmail.com', '$2y$10$TG/J28SrULpi4Bx0N.H1reAxpIlryVySOQmom.ImRl7JSiS7BQLyy', 'Alice Tan Li Ying', 'cat.jpg', 'member'),
('U005', 'brian@example.com', '$2y$10$KfrWoVwvIqCoERfAP7FgP.djjvsLOJOHASrJZe8CJmYtEDCR10cBW', 'Brian Ng Wei Jie', '7.png', 'member'),
('U006', 'carmen@example.com', '$2y$10$Z79DoZUKNDeAuwmaddfOXeE4a3x7C00RoajhrWgYQVYM2tgX9VhYa', 'Carmen Lee Jia Xin', '8.png', 'member'),
('U007', 'greentea@gmail.com', '3cf5364a255428e39d5a0ec6017c9df9cb42b34f', 'YUXUAN', '20251216_193110_5.png', 'member'),
('U008', 'qilin@gmail.com', '$2y$10$fzovKuCjJqn5eeLRhkEB6.IZGKlEUM1XF/OUU7Lb0gKQa6PjLhD.W', 'Chong Qi Lin', '20251216_195215_Screenshot 2025-03-05 121726.png', 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `fk_feedback_user` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `fk_order_item_order` (`order_id`),
  ADD KEY `fk_order_item_product` (`product_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `fk_token_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_item`
--
ALTER TABLE `order_item`
  ADD CONSTRAINT `fk_order_item_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `fk_order_item_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`);

--
-- Constraints for table `token`
--
ALTER TABLE `token`
  ADD CONSTRAINT `fk_token_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
