-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2024 at 01:07 PM
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
-- Database: `naturegear`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `equipment_rental`
--

CREATE TABLE `equipment_rental` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `equipment_id` int(11) DEFAULT NULL,
  `rental_start` date DEFAULT NULL,
  `rental_end` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `payment_method` varchar(50) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_rental`
--

INSERT INTO `equipment_rental` (`id`, `user_id`, `equipment_id`, `rental_start`, `rental_end`, `status`, `payment_method`, `payment_proof`, `total_amount`) VALUES
(20, 1, 4, '2024-06-14', '2024-06-15', 'Completed', 'Bank Transfer', 'payment_proofs/rental/666969c48e36e.jpg', 20000.00);

-- --------------------------------------------------------

--
-- Table structure for table `gear_cleaning`
--

CREATE TABLE `gear_cleaning` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_description` text DEFAULT NULL,
  `cleaning_date` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `image` varchar(255) DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gear_cleaning`
--

INSERT INTO `gear_cleaning` (`id`, `user_id`, `item_description`, `cleaning_date`, `status`, `image`, `payment_method`, `payment_proof`, `total_amount`) VALUES
(18, 1, 'Carrier 45 L', '2024-06-13', 'Completed', 'upload/cleaning/contoh.jpg', 'Bank Transfer', 'payment_proofs/cleaning/contoh.jpg', 30000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `status`, `total_amount`, `payment_method`, `payment_proof`) VALUES
(76, 1, '2024-06-12 15:56:15', 'Completed', 800000.00, 'Bank Transfer', 'payment_proofs/shopping/contoh.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `availability` varchar(50) NOT NULL DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `image`, `availability`) VALUES
(1, 'Tenda Biru', 'Tenda berkualitas tinggi untuk berkemah di luar ruangan dengan kapasitas 4/5 orang', 1000000.00, 'Camping', 'images/blue-tent.png', 'Available'),
(2, 'Sleeping Bag', 'Sleeping bag yang nyaman dan berkualitas', 435000.00, 'Camping', 'images/sleeping-bag.png', 'Available'),
(3, 'Sepatu Hiking', 'Sepatu hiking yang tahan lama dan berkualitas', 1450000.00, 'Hiking', 'images/boots.png', 'Available'),
(4, 'Carrier 60L', 'Carrier besar untuk hiking dengan kapasitas 60L', 800000.00, 'Hiking', 'images/carier.png', 'Unavailable'),
(5, 'Flysheet', 'Flysheet ringan untuk berkemah', 290000.00, 'Camping', 'images/flysheet.png', 'Available'),
(6, 'Headlamp', 'Headlamp terang untuk aktivitas malam hari', 217500.00, 'Hiking', 'images/headlamp.png', 'Available'),
(7, 'Kompor Bunga', 'Kompor portabel dengan desain bunga', 362500.00, 'Camping', 'images/komporbunga.png', 'Available'),
(8, 'Kompor Kupu-Kupu', 'Kompor ringkas dengan desain kupu-kupu', 362500.00, 'Camping', 'images/komporkupu.png', 'Available'),
(9, 'Nesting', 'Set nesting standar untuk berkemah', 507500.00, 'Camping', 'images/nesting.png', 'Available'),
(10, 'Nesting Kotak', 'Set nesting berbentuk kotak', 507500.00, 'Camping', 'images/nestingkotak.png', 'Available'),
(11, 'Kompor Parafin', 'Kompor parafin berkualitas tinggi', 145000.00, 'Camping', 'images/parafin.png', 'Available'),
(12, 'Tenda Kuning', 'Tenda kuning untuk 5/6 orang', 1200000.00, 'Camping', 'images/tendakuning.png', 'Available'),
(13, 'Tenda Merah', 'Tenda merah untuk 4/5 orang', 1015000.00, 'Camping', 'images/tendamerah.png', 'Available'),
(14, 'Carrier 50-60L', 'Carrier luas cocok untuk kapasitas 50-60L', 797500.00, 'Hiking', 'images/carrier50-60.png', 'Available'),
(15, 'Carrier 65-75L', 'Carrier besar cocok untuk kapasitas 65-75L', 942500.00, 'Hiking', 'images/carrier65-75.png', 'Available'),
(16, 'Carrier 75+15L', 'Carrier ekstra besar dengan tambahan kapasitas 15L', 1087500.00, 'Hiking', 'images/carrier75+15.png', 'Available'),
(17, 'Carrier 60L', 'Carrier dengan kapasitas 60L', 725000.00, 'Hiking', 'images/carrier60.png', 'Available'),
(18, 'Matras', 'Matras berkemah yang nyaman dan berkualitas', 80000.00, 'Camping', 'images/mattress.png', 'Available'),
(19, 'Sandal Hiking', 'Sandal yang nyaman untuk hiking', 507500.00, 'Hiking', 'images/hiking-sandals.png', 'Available'),
(20, 'Kompor Portabel', 'Kompor berkemah ringkas dan portabel', 652500.00, 'Camping', 'images/portable-stove.png', 'Available');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(10) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `komentar` varchar(500) NOT NULL,
  `tanggal` varchar(20) DEFAULT NULL,
  `jam` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `nama`, `username`, `foto`, `komentar`, `tanggal`, `jam`) VALUES
(12, 20, 'Dwinanda Muhammad Keyzha', '222212576', NULL, 'Produk NatureGear sangat membantu dalam aktivitas outdoor saya. Kualitasnya luar biasa dan sangat tahan lama. Pelayanan pelanggannya juga sangat responsif dan membantu. Highly recommended!', '05/06/2024', '21:40:11'),
(13, 23, 'Cristiano Ronaldo', 'cr7', NULL, 'Sebagai seorang penggemar kegiatan outdoor, saya memerlukan perlengkapan terbaik untuk mendukung aktivitas saya. Produk NatureGear benar-benar memenuhi harapan saya. Kualitasnya tidak tertandingi dan sangat nyaman digunakan. Saya sangat merekomendasikannya untuk semua penggemar olahraga dan aktivitas outdoor!', '06/06/2024', '01:17:43'),
(14, 25, 'Elon Musk', 'tesla', NULL, 'Produk-produk NatureGear sangat berkualitas dan sesuai dengan kebutuhan saya. Pelayanan yang diberikan juga sangat baik. Saya sangat puas dengan pengalaman di NatureGear.', '06/06/2024', '01:18:50'),
(15, 24, 'Lionel Messi', 'goat', NULL, 'NatureGear adalah pilihan terbaik untuk peralatan outdoor! Kualitasnya luar biasa dan sangat tahan lama. Saya sangat merekomendasikan produk mereka.', '06/06/2024', '01:19:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `telp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `username`, `alamat`, `telp`, `password`, `role`) VALUES
(1, 'admin', 'admin@stis.ac.id', 'admin', 'admin', '1234567890', '$2y$10$vLXZD5re1IbW0QqOm1fY6OcipiX7ebO6aXcdNpnCN0bHI4jY6mo0W', 'admin'),
(20, 'Dwinanda Muhammad Keyzha', '222212576@stis.ac.id', '222212576', 'Griya Katulampa', '082213104552', '$2y$10$ZVqEROfCePvH.jTY/yGKcuKfR7or.PhyEjD3hOA5HbNYydwMZGrJ2', NULL),
(23, 'Cristiano Ronaldo', 'cr7@gmail.com', 'cr7', 'Portugal', '085811046621', '$2y$10$.So5tkhKhW/X9dJNOdkvpeIkQBhDPWuAeWiBIMIj0SmQp.uOOfVvG', NULL),
(24, 'Lionel Messi', 'lm10@gmail.com', 'goat', 'Argentina', '085811046621', '$2y$10$UaVPt42.Z9/UyzspxsPEgeh8REJmmukDy2ZMQzvagKuYPuCmfOMV.', NULL),
(25, 'Elon Musk', 'tesla@gmail.com', 'tesla', 'America', '085811046621', '$2y$10$tYcjHYZg/PeZ7xMRn1BMteFipOq9.mHohjyNGYMGRJZXJxOU6esmu', NULL);

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
-- Indexes for table `equipment_rental`
--
ALTER TABLE `equipment_rental`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `gear_cleaning`
--
ALTER TABLE `gear_cleaning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_reviews` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `equipment_rental`
--
ALTER TABLE `equipment_rental`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `gear_cleaning`
--
ALTER TABLE `gear_cleaning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `equipment_rental`
--
ALTER TABLE `equipment_rental`
  ADD CONSTRAINT `equipment_rental_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `equipment_rental_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `gear_cleaning`
--
ALTER TABLE `gear_cleaning`
  ADD CONSTRAINT `gear_cleaning_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_user_order` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_user_reviews` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
