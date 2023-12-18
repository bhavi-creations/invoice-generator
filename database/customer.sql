-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2023 at 05:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bhavi_invoice_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `Gst_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Id`, `Name`, `Phone`, `Email`, `Address`, `Gst_no`) VALUES
(3, 'Rajkumar Giduthuri', '7981885538', 'rajkumar16371@gmail.com', 'KITS college, Ramachandrapuram.', 'gfbdfbgb'),
(4, 'Rajkumar Giduthuri', '7981885538', 'rajkumar16371@gmail.com', 'KITS college, Ramachandrapuram.', 'gfbdfbgb'),
(5, 'Rajkumar Giduthuri', '7981885538', 'rajkumar16371@gmail.com', 'KITS college, Ramachandrapuram.', 'gfbdfbgb'),
(6, 'ravi', '7981885538', 'ravi@gmail.com', 'kkd', '37Emdffsd520'),
(7, 'ravi', '7981885538', 'ravi@gmail.com', 'kkd', '37Emdffsd520');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
