-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2022 at 06:21 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ticketkatun`
--

-- --------------------------------------------------------

--
-- Table structure for table `booked`
--

CREATE TABLE `booked` (
  `id` int(20) NOT NULL,
  `schedule_id` int(20) NOT NULL,
  `payment_id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `code` varchar(90) NOT NULL,
  `class` varchar(20) NOT NULL DEFAULT 'second',
  `no` int(10) NOT NULL DEFAULT 1,
  `seat` varchar(40) NOT NULL,
  `date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booked`
--

INSERT INTO `booked` (`id`, `schedule_id`, `payment_id`, `user_id`, `code`, `class`, `no`, `seat`, `date`) VALUES
(1, 3, 1, 2, '2022/003/1530', 'second', 1, 'S001', 'Sat, 19-Nov-2022 12:23:02 AM'),
(2, 3, 2, 2, '2022/003/2548', 'second', 1, 'S002', 'Sat, 19-Nov-2022 12:27:56 AM'),
(3, 5, 3, 2, '2022/005/1650', 'second', 1, 'S001', 'Sat, 19-Nov-2022 09:34:11 PM'),
(4, 6, 4, 2, '2022/006/1367', 'first', 1, 'F01', 'Sun, 20-Nov-2022 12:04:02 AM'),
(5, 6, 5, 2, '2022/006/257', 'second', 2, 'S001 - S002', 'Sun, 20-Nov-2022 12:46:00 AM'),
(6, 6, 6, 2, '2022/006/4568', 'second', 1, 'S003', 'Sun, 20-Nov-2022 09:59:00 AM'),
(7, 7, 7, 2, '2022/007/1146', 'first', 1, 'F01', 'Wed, 28-Dec-2022 09:31:07 PM'),
(8, 7, 8, 2, '2022/007/2474', 'second', 2, 'S001 - S002', 'Wed, 28-Dec-2022 09:37:13 PM');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(20) NOT NULL,
  `user_id` int(20) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `response` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `message`, `response`) VALUES
(1, 2, 'hello admin', 'how can I help you..?'),
(2, 2, 'hello admin...i cancel my ticket, now i want refund...payment: WULSLJKRDE', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(20) NOT NULL,
  `users_id` int(20) NOT NULL,
  `schedule_id` int(20) NOT NULL,
  `amount` varchar(100) NOT NULL,
  `ref` varchar(100) NOT NULL,
  `date` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `users_id`, `schedule_id`, `amount`, `ref`, `date`) VALUES
(1, 2, 3, '920', '4UMSGBNSD8', 'Sat, 19-Nov-2022 12:23:02 AM'),
(2, 2, 3, '920', 'AVC0WN6GK4', 'Sat, 19-Nov-2022 12:27:56 AM'),
(3, 2, 5, '575', 'O8YFH3HN2G', 'Sat, 19-Nov-2022 09:34:11 PM'),
(4, 2, 6, '1150', 'RLFZICXL93', 'Sun, 20-Nov-2022 12:04:02 AM'),
(5, 2, 6, '1150', 'JQ2U88VQSU', 'Sun, 20-Nov-2022 12:46:00 AM'),
(6, 2, 6, '575', 'Z536YQ29U5', 'Sun, 20-Nov-2022 09:59:00 AM'),
(7, 2, 7, '1725', 'WULSLJKRDE', 'Wed, 28-Dec-2022 09:31:07 PM'),
(8, 2, 7, '2070', 'J7S4WFU4ET', 'Wed, 28-Dec-2022 09:37:13 PM');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `id` int(11) NOT NULL,
  `start` varchar(150) NOT NULL,
  `stop` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`id`, `start`, `stop`) VALUES
(1, 'DHA_KPS', 'COX_ICONIC'),
(3, 'COX_ICONIC', 'DHA_KPS');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(20) NOT NULL,
  `train_id` int(20) NOT NULL,
  `route_id` int(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `time` varchar(20) NOT NULL,
  `first_fee` float NOT NULL,
  `second_fee` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`id`, `train_id`, `route_id`, `date`, `time`, `first_fee`, `second_fee`) VALUES
(2, 1, 1, '18-11-2022', '08:00', 1200, 800),
(3, 1, 3, '19-11-2022', '08:00', 1200, 800),
(5, 1, 3, '19-11-2022', '23:59', 1000, 500),
(6, 1, 1, '20-11-2022', '11:59', 1000, 500),
(7, 1, 1, '30-12-2022', '08:00', 1500, 900);

-- --------------------------------------------------------

--
-- Table structure for table `train`
--

CREATE TABLE `train` (
  `id` int(250) NOT NULL,
  `name` varchar(50) NOT NULL,
  `first_seat` int(12) NOT NULL,
  `second_seat` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `train`
--

INSERT INTO `train` (`id`, `name`, `first_seat`, `second_seat`) VALUES
(1, 'Cox\'s Express', 80, 200);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(250) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(80) NOT NULL,
  `phn` int(20) NOT NULL,
  `loc` varchar(50) NOT NULL,
  `address` varchar(250) NOT NULL,
  `nid` int(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phn`, `loc`, `address`, `nid`, `password`, `status`) VALUES
(1, 'rafsan', 'adpro@gmail.com', 1600000000, '', 'dhaka', 1234567890, '1234', 1),
(2, 'demo1', 'demo1@mail.com', 1300000000, 'file', 'dhaka', 2147483647, '81dc9bdb52d04dc20036dbd8313ed055', 1),
(3, 'Karim', 'karim@gmail.com', 1360000000, 'file', 'dhaka', 2147483647, '81dc9bdb52d04dc20036dbd8313ed055', 1),
(4, 'demo2', 'demo2@mail.com', 1600000000, 'file', 'dhaka', 2147483647, '81dc9bdb52d04dc20036dbd8313ed055', 1),
(5, 'Diu', 'diu.edu@gmail.com', 1854728493, 'file', 'dhaka', 2147483647, '81dc9bdb52d04dc20036dbd8313ed055', 1),
(6, 'mitu', 'mitu@gmail.com', 1958375432, 'file', 'dhaka', 2147483647, '81dc9bdb52d04dc20036dbd8313ed055', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usersadmin`
--

CREATE TABLE `usersadmin` (
  `id` int(250) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usersadmin`
--

INSERT INTO `usersadmin` (`id`, `email`, `password`) VALUES
(1, 'admin2@gmail.com', '1234');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booked`
--
ALTER TABLE `booked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `user_id` (`users_id`),
  ADD KEY `users_id_2` (`users_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `train_id` (`train_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `train`
--
ALTER TABLE `train`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usersadmin`
--
ALTER TABLE `usersadmin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booked`
--
ALTER TABLE `booked`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `train`
--
ALTER TABLE `train`
  MODIFY `id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `usersadmin`
--
ALTER TABLE `usersadmin`
  MODIFY `id` int(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
