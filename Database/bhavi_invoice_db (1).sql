-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2023 at 11:29 AM
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
  `Company_name` varchar(150) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `Gst_no` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`Id`, `Company_name`, `Name`, `Phone`, `Email`, `Address`, `Gst_no`) VALUES
(1, 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'ram@gmail.com', 'KKD', '37AN89852SADSA'),
(2, 'abhinaya', 'raj', '07498188555', 'raj@gmail.com', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', '38GN58POMVD');

-- --------------------------------------------------------

--
-- Table structure for table `gst_no`
--

CREATE TABLE `gst_no` (
  `si_No` int(11) NOT NULL,
  `gst` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gst_no`
--

INSERT INTO `gst_no` (`si_No`, `gst`) VALUES
(1, 0),
(2, 5),
(3, 12),
(4, 18),
(5, 50),
(6, 20);

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `Sid` int(11) NOT NULL,
  `Invoice_no` int(11) NOT NULL,
  `Invoice_date` date NOT NULL,
  `Company_name` varchar(150) NOT NULL,
  `Cname` varchar(50) NOT NULL,
  `Cphone` varchar(150) NOT NULL,
  `Caddress` text NOT NULL,
  `Cmail` varchar(150) NOT NULL,
  `Cgst` varchar(150) NOT NULL,
  `Final` double(10,2) NOT NULL,
  `Gst` int(20) NOT NULL,
  `Gst_total` double(10,2) NOT NULL,
  `Grandtotal` double(10,2) NOT NULL,
  `Totalinwords` text NOT NULL,
  `Terms` text NOT NULL,
  `Note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Sid`, `Invoice_no`, `Invoice_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `Totalinwords`, `Terms`, `Note`) VALUES
(1, 4, '2023-12-22', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', '5-155,Ysr Colony, Madhavapatnam, Samalkota Madal,Kakinada District,AP', 'ram@gmail.com', '37AN89852SADSA', 47.50, 0, 5.70, 47.50, 'forty seven rupees and five paisa only ', 'fsdfgsd', 'dffsgdfsgdsgd'),
(5, 8, '2023-12-22', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', '37AN89852SADSA', 146.00, 0, 17.52, 146.00, 'one hundred and forty six rupees only ', '', ''),
(6, 9, '1970-01-01', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', '37AN89852SADSA', 146.00, 0, 17.52, 146.00, 'one hundred and forty six rupees only ', 'gfhgg', 'hfdhggdh'),
(7, 10, '2023-12-29', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', '37AN89852SADSA', 192.00, 5, 9.60, 201.60, 'two hundred and one rupees and six paisa only ', 'dsfgggggggggggggdsgdf', 'fdssssssssssssssssssssssgfdsg');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `Id` int(11) NOT NULL,
  `Sid` int(11) NOT NULL,
  `Sname` varchar(100) NOT NULL,
  `Description` text NOT NULL,
  `Qty` int(11) NOT NULL,
  `Price` double(10,2) NOT NULL,
  `Totalprice` double(10,2) NOT NULL,
  `Discount` int(20) NOT NULL,
  `Finaltotal` int(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`Id`, `Sid`, `Sname`, `Description`, `Qty`, `Price`, `Totalprice`, `Discount`, `Finaltotal`) VALUES
(1, 1, 'Printing', '', 5, 10.00, 50.00, 5, 48),
(2, 2, 'Log-Design', '', 2, 10.00, 200.00, 5, 48),
(3, 2, 'Image Designing', '', 5, 20.00, 100.00, 2, 98),
(4, 3, 'Log-Design', '', 2, 10.00, 200.00, 5, 48),
(5, 4, 'Log-Design', '', 2, 10.00, 200.00, 5, 48),
(6, 5, 'Log-Design', '', 2, 10.00, 200.00, 5, 48),
(7, 6, 'Log-Design', 'hfghgfh', 2, 10.00, 200.00, 5, 48),
(8, 6, 'Log-Design', 'fdhgfdfh', 5, 20.00, 100.00, 2, 98),
(9, 7, 'Log-Design', 'fgjjngfjhngfjhgfhjgggggggggggggggggggggggfhgj', 5, 20.00, 100.00, 5, 95),
(10, 7, 'Social Media Management', 'dsgfdgdrfxhgfdhgfjhgfjhhgjytghhgjjhggggggggggggggggggggggggggggghj', 5, 20.00, 100.00, 3, 97);

-- --------------------------------------------------------

--
-- Table structure for table `service_names`
--

CREATE TABLE `service_names` (
  `si_No` int(11) NOT NULL,
  `service_Name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_names`
--

INSERT INTO `service_names` (`si_No`, `service_Name`) VALUES
(1, 'Log-Design'),
(2, 'Google My Business'),
(3, 'Website'),
(4, 'Social Media Management'),
(5, 'Image Designing'),
(6, 'Video Creation'),
(7, 'Video Editing'),
(8, 'SEO'),
(9, 'Printing'),
(10, 'Visiting Cards'),
(11, 'Letter Heads'),
(12, 'Pamphlet'),
(13, 'Flex'),
(14, 'Brouchers'),
(15, 'Viny Stickers'),
(16, 'Calenders'),
(17, 'Diaries'),
(18, 'Branding'),
(19, 'fssadfdsf');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `gst_no`
--
ALTER TABLE `gst_no`
  ADD PRIMARY KEY (`si_No`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`Sid`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `service_names`
--
ALTER TABLE `service_names`
  ADD PRIMARY KEY (`si_No`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gst_no`
--
ALTER TABLE `gst_no`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `service_names`
--
ALTER TABLE `service_names`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
