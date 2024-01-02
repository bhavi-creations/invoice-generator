-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2024 at 05:34 AM
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
(2, 'abhinaya', 'raj', '07498188555', 'raj@gmail.com', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', '38GN58POMVD'),
(3, 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'ram@gmail.com', 'KKD', 'EWQRWEREW'),
(4, 'smart physiocare', 'pawan', '7730000000000', 'phanichalikonda@gmail.com', 'apsp', '2245452JNDKLWSAFC');

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
(6, 20),
(7, 60);

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
  `Note` text NOT NULL,
  `advance` double(10,2) NOT NULL,
  `balance` double(10,2) NOT NULL,
  `balancewords` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Sid`, `Invoice_no`, `Invoice_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `Totalinwords`, `Terms`, `Note`, `advance`, `balance`, `balancewords`) VALUES
(7, 10, '2023-12-29', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', '5-155,YSR Colony, Madhavapatnam, Samalkota Mandal, Kakinada District, Andhrapradesh , 533005', 'ram@gmail.com', '37AN89852SADSA', 192.00, 5, 9.60, 201.60, 'two hundred and one rupees and six paisa only ', '1.pay within 115 days.\r\n2.i want immeadiatly', 'bfldsfgdskfjhfds and you have to ppay with in 15 days', 0.00, 0.00, ''),
(8, 11, '2023-12-28', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', '37AN89852SADSA', 38.00, 5, 1.90, 39.90, 'thirty nine rupees and nine paisa only ', 'dfsdffffffffffffffffff', 'fdsgfdggfh', 0.00, 0.00, ''),
(16, 12, '2023-12-28', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 38.00, 0, 0.00, 38.00, 'thirty eight rupees only ', 'hfdfghfghg', 'fghfghfgh', 0.00, 0.00, ''),
(17, 13, '2023-12-28', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', '37AN89852SADSA', 38.00, 0, 0.00, 38.00, 'thirty eight rupees only ', 'dsgfdsgfdg', 'dgfgdfgfdgfdgdg', 0.00, 0.00, ''),
(18, 14, '1970-01-01', 'smart physiocare', 'pawan', '7730000000000', 'apsp', 'phanichalikonda@gmail.com', '2245452JNDKLWSAFC', 45000.00, 12, 5400.00, 50400.00, 'fifty thousand four hundred only ', 'full amount before', '', 0.00, 0.00, ''),
(19, 15, '1970-01-01', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', '37AN89852SADSA', 19.00, 12, 2.28, 21.28, 'twenty one rupees and two eight paisa only ', 'dffdsdfgsdgdfg', 'dfgdfgds', 0.00, 0.00, ''),
(20, 16, '2023-12-28', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 95.00, 5, 4.75, 99.75, 'ninety nine rupees and seven five paisa only ', '', '', 0.00, 0.00, ''),
(23, 18, '2023-12-29', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 58890.44, 5, 2944.52, 61834.96, 'sixty one thousand eight hundred and thirty four rupees and six two paisa only ', 'ffyuykujyrfucvuy', 'gfuyfoi8u7f78otoltl.', 0.00, 0.00, ''),
(24, 19, '2023-12-21', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 38.00, 0, 0.00, 38.00, 'thirty eight rupees only ', 'gbdfsgdfg', 'dfgdfgds', 5.00, 33.00, ''),
(25, 20, '2023-12-30', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 38.00, 12, 4.56, 42.56, 'forty two rupees and fifty six  paisa only ', 'gfdsgdsgffdg', 'sdfhgbfdgdffg', 5.00, 37.56, ''),
(28, 21, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 95.00, 5, 4.75, 99.75, 'ninety nine rupees and seventy five  paisa only ', 'fdsssssssssssssssssssssssssssgrdfeeeeegszzzzzzzzbvtvehtrtrtrtrtrtrtrtrtrtrtrtrhghdrt', 'rethbgfuvfdluihrru ieuirgjhgreo', 50.00, 49.75, ''),
(29, 22, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 213750.00, 5, 10687.50, 224437.50, 'two lakh twenty four thousand four hundred and thirty seven rupees and five  paisa only ', 'please pay with in 15 days', 'only transaction available like gpay and ppay', 20000.00, 204437.50, ''),
(30, 22, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 213750.00, 5, 10687.50, 224437.50, 'two lakh twenty four thousand four hundred and thirty seven rupees and five  paisa only ', 'please pay with in 15 days', 'only transaction available like gpay and ppay', 20000.00, 204437.50, ''),
(31, 23, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 4750.00, 5, 237.50, 4987.50, 'four thousand nine hundred and eighty seven rupees and five  paisa only ', 'sdzdgfdfgfdgfdgdf', 'gdsfgdfsgdfjgbhlrdriguyoli', 200.00, 4787.50, 'four thousand seven hundred and eighty seven rupees and five  paisa only '),
(32, 24, '2023-12-29', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 142.40, 5, 7.12, 149.52, 'one hundred and forty nine rupees and fifty two  paisa only ', 'tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt', 'tttttttttttttttttttttttttttttttttttttttttttttttttttttt', 23.00, 126.52, 'only ');

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
(10, 7, 'Social Media Management', 'dsgfdgdrfxhgfdhgfjhgfjhhgjytghhgjjhggggggggggggggggggggggggggggghj', 5, 20.00, 100.00, 3, 97),
(11, 7, 'Log-Design', '', 1, 565.00, 444.00, 959, 44),
(12, 7, 'Log-Design', '', 1, 565.00, 444.00, 959, 44),
(13, 8, 'Image Designing', 'hgjhgjhgj', 2, 20.00, 40.00, 5, 38),
(14, 9, 'Video Creation', '', 2, 20.00, 40.00, 5, 38),
(15, 10, 'Video Creation', '', 2, 20.00, 40.00, 5, 38),
(16, 11, 'Video Creation', '', 2, 20.00, 40.00, 5, 38),
(17, 12, 'Log-Design', '', 2, 20.00, 40.00, 5, 38),
(18, 13, 'Log-Design', '', 2, 20.00, 40.00, 5, 38),
(19, 14, 'Letter Heads', 'jhgbcfcdjhggj', 2, 20.00, 40.00, 5, 38),
(20, 14, 'Pamphlet', 'fjhghfjytgjytfj', 2, 50.00, 100.00, 5, 95),
(21, 15, 'Letter Heads', 'jhgbcfcdjhggj', 2, 20.00, 40.00, 5, 38),
(22, 15, 'Pamphlet', 'fjhghfjytgjytfj', 2, 50.00, 100.00, 5, 95),
(23, 16, 'Log-Design', 'fdhxhbfgh', 2, 20.00, 40.00, 5, 38),
(24, 17, 'Image Designing', 'gdfgd', 2, 20.00, 40.00, 5, 38),
(25, 18, 'physiotherapy report', '10 days unadu', 10, 5000.00, 50000.00, 10, 45000),
(26, 19, 'Website', 'i need good pics', 2, 10.00, 20.00, 5, 19),
(27, 20, 'Google My Business', '', 2, 50.00, 100.00, 5, 95),
(28, 21, 'Log-Design', '', 2, 50.00, 100.00, 5, 95),
(29, 21, 'Visiting Cards', '', 5, 20.00, 100.00, 1, 99),
(30, 21, 'Visiting Cards', '', 8, 50.00, 400.00, 5, 380),
(31, 21, 'Letter Heads', '', 5, 20.00, 100.00, 5, 95),
(32, 21, 'Visiting Cards', '', 8, 50.00, 400.00, 5, 380),
(33, 21, 'Flex', '', 8, 50.00, 400.00, 0, 400),
(34, 21, 'Pamphlet', '', 20, 8.00, 160.00, 0, 160),
(35, 21, 'Printing', '', 80, 9.00, 720.00, 0, 720),
(36, 21, 'Visiting Cards', '', 8, 1.00, 8.00, 2, 8),
(37, 21, 'Social Media Management', '', 8, 64.00, 512.00, 2, 502),
(38, 22, 'Log-Design', '', 2, 50.00, 100.00, 5, 95),
(39, 22, 'Visiting Cards', '', 5, 20.00, 100.00, 1, 99),
(40, 22, 'Visiting Cards', '', 8, 50.00, 400.00, 5, 380),
(41, 22, 'Letter Heads', '', 5, 20.00, 100.00, 5, 95),
(42, 22, 'Visiting Cards', '', 8, 50.00, 400.00, 5, 380),
(43, 22, 'Flex', '', 8, 50.00, 400.00, 0, 400),
(44, 22, 'Pamphlet', '', 20, 8.00, 160.00, 0, 160),
(45, 22, 'Printing', '', 80, 9.00, 720.00, 0, 720),
(46, 22, 'Visiting Cards', '', 8, 1.00, 8.00, 2, 8),
(47, 22, 'Social Media Management', '', 8, 64.00, 512.00, 2, 502),
(48, 23, 'Log-Design', '', 2, 50.00, 100.00, 5, 95),
(49, 23, 'Log-Design', '', 5, 20.00, 100.00, 1, 99),
(50, 23, 'Log-Design', '', 8, 50.00, 400.00, 5, 380),
(51, 23, 'Log-Design', '', 5, 20.00, 100.00, 5, 95),
(52, 23, 'Log-Design', '', 8, 50.00, 400.00, 5, 380),
(53, 23, 'Log-Design', '', 8, 50.00, 400.00, 0, 400),
(54, 23, 'Log-Design', '', 20, 8.00, 160.00, 0, 160),
(55, 23, 'Log-Design', '', 80, 9.00, 720.00, 0, 720),
(56, 23, 'Log-Design', '', 8, 1.00, 8.00, 2, 8),
(57, 23, 'Log-Design', '', 8, 50.00, 400.00, 2, 392),
(58, 23, 'Log-Design', '', 2, 10.00, 20.00, 2, 20),
(59, 23, 'Log-Design', '', 5, 10.00, 50.00, 2, 49),
(60, 23, 'Log-Design', '', 5, 10.00, 50.00, 5, 48),
(61, 23, 'Log-Design', '', 2, 20.00, 40.00, 5, 38),
(62, 23, 'Log-Design', '', 1, 10.00, 10.00, 5, 10),
(63, 23, 'Log-Design', '', 1, 5000.00, 5000.00, 8, 4600),
(64, 23, 'Log-Design', '', 1, 5000.00, 5000.00, 5, 4750),
(65, 23, 'Log-Design', '', 10, 5000.00, 50000.00, 8, 46000),
(66, 23, 'Log-Design', '', 2, 10.00, 20.00, 5, 19),
(67, 23, 'Log-Design', '', 10, 10.00, 100.00, 9, 91),
(68, 23, 'Log-Design', '', 1, 50.00, 50.00, 4, 48),
(69, 23, 'Log-Design', '', 10, 50.00, 500.00, 2, 490),
(70, 24, 'Log-Design', '', 2, 20.00, 40.00, 5, 38),
(71, 25, 'Log-Design', 'dsfewsfdfgdsfgdsgd', 2, 20.00, 40.00, 5, 38),
(72, 26, 'Log-Design', 'rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrtttttttttttttttrtuytiuytkm,jhmherghreirhgfierfgejbdsjhewuyefguyerbevfvyyeguer', 23, 22.00, 506.00, 3, 491),
(73, 26, 'Visiting Cards', 'dfvrgbthnyu', 22, 22.00, 484.00, 3, 469),
(74, 26, 'Calenders', 'trgtrhtytjhytjhtjytj', 22, 22.00, 484.00, 2, 474),
(75, 27, 'Log-Design', 'rrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr', 23, 22.00, 506.00, 3, 491),
(76, 27, 'Visiting Cards', 'dfvrgbthnyu', 22, 22.00, 484.00, 3, 469),
(77, 27, 'Calenders', 'trgtrhtytjhytjhtjytj', 22, 22.00, 484.00, 2, 474),
(78, 28, 'Log-Design', 'fdsdffffffffffffffffffffffffhfgrdtrhtryrhtrhghbnhtyyyyyyyyyyyyyyyyyyyyyyyyytrhtyyyyyyyyyyyhhhhhhhhhhhhhrtythjhytytytytyt', 2, 50.00, 100.00, 5, 95),
(79, 29, 'Log-Design', 'i need good logo with 1515*20', 45, 5000.00, 225000.00, 5, 213750),
(80, 30, 'Log-Design', 'i need good logo with 1515*20', 45, 5000.00, 225000.00, 5, 213750),
(81, 31, 'Log-Design', 'dffgggdsdsdsdsdsdsgfdggggggf', 50, 100.00, 5000.00, 5, 4750),
(82, 32, 'Log-Design', 'ggggggggggggggggggggggggggggggggggg', 4, 40.00, 160.00, 11, 142);

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
(17, 'Diary');

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gst_no`
--
ALTER TABLE `gst_no`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `service_names`
--
ALTER TABLE `service_names`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
