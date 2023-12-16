-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2023 at 12:48 PM
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
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Sid` int(11) NOT NULL,
  `Invoice_no` int(11) NOT NULL,
  `Invoice_date` date NOT NULL,
  `Cname` varchar(50) NOT NULL,
  `Cphone` int(11) NOT NULL,
  `Caddress` varchar(150) NOT NULL,
  `Cmail` varchar(150) NOT NULL,
  `Cgst` varchar(150) NOT NULL,
  `Grandtotal` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Sid`, `Invoice_no`, `Invoice_date`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Grandtotal`) VALUES
(1, 0, '1970-01-01', '', 0, '', '', '', 982.80),
(2, 1, '2023-12-13', 'Rajkumar Giduthuri', 2147483647, 'KITS college, Ramachandrapuram.', 'rajkumar16371@gmail.com', '', 95.40),
(3, 1, '2023-12-16', 'Rajkumar Giduthuri', 2147483647, '', 'rajkumar16371@gmail.com', '', 95.40),
(4, 1, '2023-12-16', 'Rajkumar Giduthuri', 2147483647, '', 'rajkumar16371@gmail.com', '', 95.40),
(5, 1, '2023-12-16', 'Rajkumar Giduthuri', 2147483647, '', 'rajkumar16371@gmail.com', '', 0.00),
(6, 1, '2023-12-16', 'Rajkumar Giduthuri', 2147483647, '', 'rajkumar16371@gmail.com', '', 0.00),
(7, 1, '2023-12-16', 'Rajkumar Giduthuri', 2147483647, '', 'rajkumar16371@gmail.com', 'ewqrwerew', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`Sid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
