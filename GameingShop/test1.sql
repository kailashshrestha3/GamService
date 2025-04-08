-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 04:30 AM
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
-- Database: `test1`
--

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `credit_type` varchar(50) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `photo_path` varchar(100) NOT NULL,
  `credit_icon` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `name`, `description`, `credit_type`, `amount`, `photo_path`, `credit_icon`) VALUES
(27, 'Clash of Clans', 'Add Clash of Clans Gems to your account instantly. Enter your player tag, choose the amount, pay, and receive the gems immediately.', '', '', 'uploads/1744028466_coc1.jpg', 'uploads/clash rooyrale1.jpg'),
(28, 'PUBG Mobile', 'Buy PUBG Mobile UC voucher in seconds! Simply select your preferred UC amount, choose your preferred payment method, complete the payment, and you will receive your voucher code on the payment confirmation page and via email.', '', '', 'uploads/pubg2.jpg', 'uploads/pubg uc.jpg'),
(29, 'Mobile Legends', 'Top up Mobile Legends Diamonds in seconds! Just enter your Mobile Legends user ID, select the value of Diamonds you wish to purchase, complete the payment, and the Diamonds will be added immediately to your Mobile Legends account', '', '', 'uploads/mobilelegend1.jpg', 'uploads/diamond ml.jpg'),
(30, 'Free Fire', 'Top up Free Fire Diamonds instantly! Enter your Free Fire player ID, select the Diamonds amount, make the payment, and your Diamonds will be delivered to your account right away.', '', '', 'uploads/freefire1.jpg', 'uploads/freefire diamond.jpg'),
(31, 'Call of Duty', 'Add CP to your Call of Duty Mobile account fast and securely. Select the amount, pay, and the CP will be added instantly to your account.', '', '', 'uploads/CallofDuty1.jpg', 'uploads/call of duty cp.jpg'),
(32, 'Genshin Impact', 'Top up Genshin Impact Genesis Crystals easily! Provide your Genshin Impact UID, choose the crystal package, and get the crystals instantly.', '', '', 'uploads/genshin.jpg', 'uploads/genshin impact genesis.jpg'),
(33, 'League of Legends', 'Top up Genshin Impact Genesis Crystals easily! Provide your Genshin Impact UID, choose the crystal package, and get the crystals instantly.', '', '', 'uploads/legendofleague1.jpg', 'uploads/lol rp.jpg'),
(34, 'Clash of Clans', 'Add Clash of Clans Gems to your account instantly. Enter your player tag, choose the amount, pay, and receive the gems immediately.', '', '', 'uploads/coc1.jpg', 'uploads/coc gems.jpg'),
(35, 'Clash of Clans-- Offers', 'Get your Clash of Clans Offers done quickly and securely at Gaming Center! We offer secure and hassle-free top-ups with convenient payment methods, including MyPay, eSewa, IMEpay, and Khalti. Whether you\'re looking to boost your village or gift Offers to friends, Gaming Center is your trusted source for fast and reliable top-ups.', '', '', 'uploads/coc gold.jpg', 'uploads/coc gold pass.png'),
(36, 'Clash Royale', 'Clash Royale TopUp', '', '', 'uploads/clash rooyrale1.jpg', 'uploads/clash royale pass.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `game_topups`
--

CREATE TABLE `game_topups` (
  `id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `credit_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game_topups`
--

INSERT INTO `game_topups` (`id`, `game_id`, `amount`, `credit_type`) VALUES
(21, 27, 12.00, '12 daia'),
(22, 27, 123.00, '89 dias'),
(23, 28, 50.00, '10 UC '),
(24, 28, 87.00, '34 UC'),
(25, 28, 150.00, '69 UC'),
(26, 28, 239.00, '90 UC'),
(27, 29, 29.00, '11 Diamonds'),
(28, 29, 285.00, '112 Diamonds'),
(29, 29, 59.00, '22 Diamonds'),
(30, 29, 149.00, '56 Diamonds'),
(31, 30, 33.00, '25 Diamonds '),
(32, 30, 60.00, '50 Diamonds'),
(33, 30, 120.00, '115 Diamonds'),
(34, 30, 240.00, '210 Diamonds'),
(35, 31, 50.00, '30 CP'),
(36, 31, 135.00, '88 CP'),
(37, 31, 540.00, '460 CP'),
(38, 32, 139.00, '60 Genesis Crysals '),
(39, 32, 390.00, '330  Genesis Crysals '),
(40, 33, 50.00, '10 rp'),
(41, 33, 87.00, '20 rp '),
(42, 33, 150.00, '59 rp'),
(43, 34, 50.00, '12 gems'),
(44, 34, 134.00, '67 gems'),
(45, 34, 299.00, '160 gems'),
(46, 35, 999.00, 'COC Event Pass'),
(47, 36, 1800.00, 'Pass Royale-Supercel store');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'JohnDoe', 'johndoe@example.com', 'password123', '2025-04-07 03:05:55');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `player_id` varchar(50) NOT NULL,
  `credit_type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `purchase_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `user_id`, `game_id`, `player_id`, `credit_type`, `amount`, `payment_method`, `purchase_date`) VALUES
(20, 1, 29, '984726', '56 Diamonds', 149.00, 'Esewa', '2025-04-07 18:16:25'),
(21, 1, 31, '948272', '460 CP', 540.00, 'Esewa', '2025-04-07 18:43:43'),
(22, 12, 28, '982634', '90 UC', 239.00, 'Esewa', '2025-04-07 21:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `signup`
--

CREATE TABLE `signup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `signup`
--

INSERT INTO `signup` (`id`, `name`, `username`, `email`, `password`) VALUES
(2, 'kailash', 'kailash', 'kailash@gmail.com', 'kailash'),
(5, 'anil', 'anil', 'anil@gmail.com', 'anil'),
(6, 'Gokul', 'admin', 'gokul@gmail.com', 'gokul'),
(10, 'hello', 'hello', 'hello@gmail.com', 'hello'),
(12, 'Gokul', 'Gokul12', 'gokul12@gmail.com', '$2y$10$2LgiBi/9NPppd/YYUR.mseutvtDBMCeCLWdc4XGRx.q'),
(13, 'Tika', 'tika25', 'tika@gmail.com', '$2y$10$oAuevJegDtqNP/MD8Bg5He7TCDQC7ROVxXyl7h6lJ/K'),
(14, 'sita', 'sita', 'sita@game.com', '$2y$10$eSh4iW6le1YF70dHS8X/t.GFgkK1cyMiEvr.t8NKGI.'),
(15, 'jharana', 'jharana', 'jharana@gmai.com', '$2y$10$A5I.hl5y06K6XIHMBrrf4.IL2ooYECAqyfNVvPvDwVE'),
(16, 'kailash1', 'kailash1', 'kailash1@gmail.com', '$2y$10$Dg8mnH2u1Qtd5Em56ypcxOSXaUvS/l7DdTHMbvupMFG'),
(17, 'kailash2', 'kailash2', 'kailash2@gmail.com', 'kailash2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_topups`
--
ALTER TABLE `game_topups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `signup`
--
ALTER TABLE `signup`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `game_topups`
--
ALTER TABLE `game_topups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `signup`
--
ALTER TABLE `signup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `game_topups`
--
ALTER TABLE `game_topups`
  ADD CONSTRAINT `game_topups_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
