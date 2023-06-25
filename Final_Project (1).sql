-- Adminer 4.8.0 MySQL 5.5.5-10.5.17-MariaDB-1:10.5.17+maria~ubu2004 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantity` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `editor_by` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `editor_by` (`editor_by`),
  KEY `product_id` (`product_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `carts_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_ibfk_6` FOREIGN KEY (`editor_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_ibfk_7` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `carts_ibfk_9` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `carts` (`id`, `quantity`, `user_id`, `product_id`, `order_id`, `editor_by`, `create_at`) VALUES
(91,	3,	15,	19,	62,	17,	'2023-06-22 02:56:56'),
(93,	2,	12,	15,	63,	11,	'2023-06-22 03:56:01'),
(95,	2,	15,	12,	64,	7,	'2023-06-22 05:46:02'),
(96,	1,	15,	13,	64,	7,	'2023-06-22 05:46:02'),
(97,	5,	25,	19,	65,	17,	'2023-06-22 06:18:26'),
(98,	1,	25,	19,	66,	17,	'2023-06-22 06:19:25'),
(99,	1,	25,	15,	66,	11,	'2023-06-22 06:19:25'),
(102,	5,	16,	19,	67,	17,	'2023-06-22 06:56:22'),
(103,	3,	16,	14,	67,	11,	'2023-06-22 06:56:22'),
(104,	1,	12,	21,	68,	17,	'2023-06-22 07:03:17'),
(105,	1,	12,	21,	68,	17,	'2023-06-22 07:03:17');

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` text NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `comments_ibfk_4` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `comments` (`id`, `comment`, `create_at`, `user_id`, `product_id`) VALUES
(30,	'dddddddddddddddddddddddddd',	'2023-06-17 15:18:54',	16,	14),
(44,	'ABC',	'2023-06-21 07:58:49',	23,	12),
(47,	'got stock now?',	'2023-06-21 08:09:53',	13,	12),
(48,	' விசைகளை உடைப்பது எளிது!',	'2023-06-21 08:11:22',	15,	12),
(50,	'இது மலிவானது மற்றும் பயன்படுத்த எளிதானது',	'2023-06-21 08:14:19',	15,	14),
(51,	'மோசடி',	'2023-06-21 08:15:33',	15,	14),
(52,	'いいですね',	'2023-06-21 08:16:09',	15,	15),
(57,	'hhkjhfjkhfjskdhfk\r\n',	'2023-06-22 02:56:47',	15,	19),
(59,	'sdfghjkl',	'2023-06-22 06:10:30',	12,	13),
(60,	'sdfghj',	'2023-06-22 06:10:46',	12,	11);

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `total_amount` int(11) NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`id`, `total_amount`, `user_id`, `create_at`) VALUES
(62,	270,	15,	'2023-06-22 02:56:56'),
(63,	310,	12,	'2023-06-22 03:56:01'),
(64,	700,	15,	'2023-06-22 05:46:02'),
(65,	450,	25,	'2023-06-22 06:18:26'),
(66,	245,	25,	'2023-06-22 06:19:25'),
(67,	1047,	16,	'2023-06-22 06:56:22'),
(68,	600,	12,	'2023-06-22 07:03:17');

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `price` int(11) NOT NULL,
  `image_url` text NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `modified_by` int(11) NOT NULL DEFAULT 13,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `modified_by` (`modified_by`),
  CONSTRAINT `products_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `products_ibfk_5` FOREIGN KEY (`modified_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `title`, `price`, `image_url`, `status`, `create_at`, `modified_at`, `modified_by`, `user_id`) VALUES
(11,	'G304 LIGHTSPEED Wireless Gaming Mouse',	160,	'https://cdn.shopify.com/s/files/1/1974/9033/products/1_0322aae1-5e89-4f33-9b59-2f63e19851d5_1024x1024.jpg?v=1646126487',	'publish',	'2023-06-01 15:13:06',	'2023-06-17 07:26:44',	7,	7),
(12,	'G502 HERO High Performance Gaming Mouse',	300,	'https://www.apcomputadores.com/wp-content/uploads/2020/10/g502L-6-600x638.jpg.webp',	'publish',	'2023-06-01 15:35:48',	'2023-06-17 07:26:52',	7,	7),
(13,	'G102 LIGHTSYNC RGB Gaming Mouse',	100,	'https://www.techlandbd.com/image/catalog/Mouse/Logitech/G102/logitech-g102-1.jpg',	'publish',	'2023-06-01 16:00:23',	'2023-06-17 07:26:59',	7,	7),
(14,	'RAZER DEATHADDER V2 X HYPERSPEED',	199,	'https://cdn.shopify.com/s/files/1/1974/9033/products/1_fb147bbd-2e0b-48a1-ac3a-668ee7f4a6e3_1024x1024.jpg?v=1636709804',	'publish',	'2023-06-06 08:14:25',	'2023-06-17 07:27:04',	11,	11),
(15,	'RAZER BASILISK X HYPERSPEED',	155,	'https://cdn.shopify.com/s/files/1/1974/9033/products/1_71af9a70-0a2e-443e-8551-f607357c4591_1024x1024.jpg?v=1594635685',	'publish',	'2023-06-06 08:18:34',	'2023-06-17 07:27:13',	11,	11),
(19,	'RAZER VIPER MINI',	90,	'https://www.justlaptops.co.nz/image/cache/data/viper-mini-25805-1200x1200.jpg',	'publish',	'2023-06-22 02:56:21',	'2023-06-22 02:56:26',	17,	17),
(21,	'RAZER OROCHI V2',	300,	'https://www.startech.com.bd/image/cache/catalog/mouse/razer/orochi-v2/orochi-v2-black-01-500x500.jpg',	'publish',	'2023-06-22 06:57:50',	'2023-06-22 06:58:04',	17,	17);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL DEFAULT 'user',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `create_at`) VALUES
(7,	'editor2',	'editor2@gmail.com',	'$2y$10$bQAR3WneGGJk56th1/gKZuGD4s2l8sEN47ZYqn70x9Jgd.oRvB4g.',	'editor',	'2023-06-15 04:08:42'),
(11,	'editor',	'editor@gmail.com',	'$2y$10$b9xMePmixdGZSKgoZyvIYui02gnoFzTvSAwEMgslAzfr0smVCh3dq',	'editor',	'2023-06-12 08:01:56'),
(12,	'user',	'user@gmail.com',	'$2y$10$EqpiqrE.UC1FnEIM2jgSkOU6sK8XvShepA1/PtcW57aIkbNHVucsG',	'user',	'2023-06-13 03:34:52'),
(13,	'admin',	'admin@gmail.com',	'$2y$10$RZMaZuvRUAikYc4t0FXlievAgNZMAPcOR.hxCmKu9djoP/Q2bMLVO',	'admin',	'2023-06-01 15:20:37'),
(14,	'user2',	'user2@gmail.com',	'$2y$10$l5EzYI4hEI/DMG1FRh3hz.AxWudAY90Xo15WF00Y5Ripr8SW667kK',	'user',	'2023-06-14 08:48:38'),
(15,	'user3',	'user3@gmail.com',	'$2y$10$OiIvWWqvdaK8dM3SIodQ9ejokyE2CpL5lyHBD3I5NYge5MP6XgXSm',	'user',	'2023-06-14 12:24:43'),
(16,	'user4',	'user4@gmail.com',	'$2y$10$MCbFNXfDJFRyhOuJMOKYw.3s.yjmSDYiQoefvVmVApr6VQM8yPaVa',	'user',	'2023-06-17 15:23:04'),
(17,	'editor3',	'editor3@gmail.com',	'$2y$10$QbVhmnDlybdLBHPckddRne37b3/1JGOkCPmBk6R1lsWumW2FKKE/m',	'editor',	'2023-06-17 15:25:18'),
(18,	'John',	'john@gmail.com',	'$2y$10$m5YnCRX/jJ3Ulh9eqljyk.c0Eh9N/EZJ/nq2bX/DNNv5srQVOFBhm',	'user',	'2023-06-19 01:07:45'),
(23,	'denish',	'denish@gmail.com',	'$2y$10$QhQUQLsqCgfjdpmx7Ko/4erFkzTcdcNBtXR//UQEh9CDjgSlL4wvm',	'user',	'2023-06-21 07:49:57'),
(24,	'JIe Kai See',	'jiekai0714@gmail.com',	'$2y$10$o6zFvQ5unTsKHZphjOejQeeGobhLP8x0lzeoKK/UruPUP8gROYPw6',	'editor',	'2023-06-21 08:08:36'),
(25,	'alvinlee',	'alvinlee@gmail.com',	'$2y$10$FEY37ymYOHJb/6qm.TJZnOR7VZvcCjRYTX5yczvKJ3ID6xZMhi/US',	'user',	'2023-06-22 06:17:13'),
(26,	'useer5',	'user5@gmail.com',	'$2y$10$/Mqmpt.eJEz2tiyR3Lb7QutDUL3.bGfWmbiQAvgBBjcdXXd2d94u.',	'user',	'2023-06-22 07:00:57');

-- 2023-06-25 13:42:15
