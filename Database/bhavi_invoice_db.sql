-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2024 at 12:15 PM
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
(4, 'smart physiocare', 'pawan', '7730000000000', 'phanichalikonda@gmail.com', 'apsp', '2245452JNDKLWSAFC'),
(5, 'smart physiocare', 'pawan', '7730000000000', '', 'apsp', ''),
(6, 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'ram@gmail.com', 'KKD', '37AN89852'),
(7, 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'ram@gmail.com', 'KKD', '37AN89852');

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
  `balancewords` text NOT NULL,
  `status` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Sid`, `Invoice_no`, `Invoice_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `Totalinwords`, `Terms`, `Note`, `advance`, `balance`, `balancewords`, `status`) VALUES
(24, 19, '2023-12-21', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 38.00, 0, 0.00, 38.00, 'thirty eight rupees only ', 'gbdfsgdfg', 'dfgdfgds', 5.00, 33.00, '', 'paid'),
(25, 20, '2023-12-30', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 38.00, 12, 4.56, 42.56, 'forty two rupees and fifty six  paisa only ', 'gfdsgdsgffdg', 'sdfhgbfdgdffg', 5.00, 37.56, '', 'paid'),
(28, 21, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 95.00, 5, 4.75, 99.75, 'ninety nine rupees and seventy five  paisa only ', 'fdsssssssssssssssssssssssssssgrdfeeeeegszzzzzzzzbvtvehtrtrtrtrtrtrtrtrtrtrtrtrhghdrt', 'rethbgfuvfdluihrru ieuirgjhgreo', 50.00, 49.75, '', 'pending'),
(29, 22, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 213750.00, 5, 10687.50, 224437.50, 'two lakh twenty four thousand four hundred and thirty seven rupees and five  paisa only ', 'please pay with in 15 days', 'only transaction available like gpay and ppay', 20000.00, 204437.50, '', 'pending'),
(30, 22, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 213750.00, 5, 10687.50, 224437.50, 'two lakh twenty four thousand four hundred and thirty seven rupees and five  paisa only ', 'please pay with in 15 days', 'only transaction available like gpay and ppay', 20000.00, 204437.50, '', 'pending'),
(31, 23, '2023-12-30', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 4750.00, 5, 237.50, 4987.50, 'four thousand nine hundred and eighty seven rupees and five  paisa only ', 'sdzdgfdfgfdgfdgdf', 'gdsfgdfsgdfjgbhlrdriguyoli', 200.00, 4787.50, 'four thousand seven hundred and eighty seven rupees and five  paisa only ', 'pending'),
(32, 24, '2023-12-29', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 142.40, 5, 7.12, 149.52, 'one hundred and forty nine rupees and fifty two  paisa only ', 'tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt', 'tttttttttttttttttttttttttttttttttttttttttttttttttttttt', 23.00, 126.52, 'only ', 'paid'),
(34, 25, '2024-01-18', 'smart physiocare', 'pawan', '7730000000000', 'apsp', 'phanichalikonda@gmail.com', '2245452JNDKLWSAFC', 62040.00, 18, 11167.20, 73207.20, 'seventy three thousand two hundred and seven rupees and two  paisa only ', 'gfjgfjgfjhgj', 'gfjghfjh', 800.00, 72407.20, 'seventy two thousand four hundred and seven rupees and two  paisa only ', 'pending'),
(35, 26, '2024-01-25', 'abhinaya', 'raj', '07498188555', '5-155 ysr colony madhavapatnam eg dist kakinada ap india 533005', 'raj@gmail.com', '38GN58POMVD', 13411.80, 50, 6705.90, 20117.70, 'twenty thousand one hundred and seventeen rupees and ninety seven  paisa only ', 'gfj hggjfgjh ', ' jgf gjhhgj', 50.00, 20067.70, 'twenty thousand and sixty seven rupees and seven  paisa only ', 'pending'),
(36, 27, '1970-01-01', 'smart physiocare', 'pawan', '7730000000000', 'apsp', 'phanichalikonda@gmail.com', '2245452JNDKLWSAFC', 27550.00, 5, 1377.50, 28927.50, 'twenty eight thousand nine hundred and twenty seven rupees and five  paisa only ', '', '', 0.00, 28927.50, 'twenty eight thousand nine hundred and twenty seven rupees and five  paisa only ', 'paid'),
(37, 28, '1970-01-01', '', '', '', '', '', '', 27550.00, 0, 0.00, 27550.00, 'twenty seven thousand five hundred and fifty rupees only ', '', '', 500.00, 27050.00, 'twenty seven thousand and fifty rupees only ', 'paid'),
(38, 29, '1970-01-01', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 2375095.00, 0, 0.00, 2375095.00, 'twenty three lakh seventy five thousand and ninety five rupees only ', '', '', 0.00, 2375095.00, 'twenty three lakh seventy five thousand and ninety five rupees only ', 'pending'),
(39, 30, '2024-01-25', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 2375.00, 18, 427.50, 2802.50, 'two thousand eight hundred and two rupees and five  paisa only ', '', '', 0.00, 2802.50, 'two thousand eight hundred and two rupees and five  paisa only ', 'pending'),
(40, 30, '2024-01-25', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 2375.00, 18, 427.50, 2802.50, 'two thousand eight hundred and two rupees and five  paisa only ', '', '', 0.00, 2802.50, 'two thousand eight hundred and two rupees and five  paisa only ', 'pending'),
(41, 31, '2024-01-08', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 23750.00, 0, 0.00, 23750.00, 'twenty three thousand seven hundred and fifty rupees only ', '', '', 0.00, 23750.00, 'twenty three thousand seven hundred and fifty rupees only ', 'paid'),
(42, 32, '2024-02-10', '', '', '', '', '', '', 2375.00, 0, 0.00, 2375.00, 'two thousand three hundred and seventy five rupees only ', '', '', 100.00, 2275.00, 'two thousand two hundred and seventy five rupees only ', 'pending'),
(43, 32, '2024-03-15', 'smart physiocare', 'pawan', '7730000000000', 'apsp', 'phanichalikonda@gmail.com', '2245452JNDKLWSAFC', 2375.00, 0, 0.00, 2375.00, 'two thousand three hundred and seventy five rupees only ', '', '', 100.00, 0.00, 'only ', 'pending'),
(44, 33, '2024-01-26', 'Bhavi Creations', 'Rajkumar Giduthuri', '09848012555', 'KKD', 'ram@gmail.com', 'EWQRWEREW', 2300.00, 0, 0.00, 2300.00, 'two thousand three hundred only ', '', '', 0.00, 2300.00, 'two thousand three hundred only ', 'paid'),
(45, 34, '2024-05-08', 'smart physiocare', 'pawan', '7730000000000', 'apsp', '', '', 2375.00, 0, 0.00, 2375.00, 'two thousand three hundred and seventy five rupees only ', '', '', 0.00, 2375.00, 'two thousand three hundred and seventy five rupees only ', 'paid');

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
(82, 32, 'Log-Design', 'ggggggggggggggggggggggggggggggggggg', 4, 40.00, 160.00, 11, 142),
(83, 33, 'Log-Design', '', 50, 50.00, 2500.00, 5, 2375),
(84, 34, 'Log-Design', '', 50, 80.00, 4000.00, 5, 3800),
(85, 34, 'Log-Design', '', 80, 800.00, 64000.00, 9, 58240),
(86, 35, 'Log-Design', 'lhluidffh h', 50, 50.00, 2500.00, 5, 2375),
(87, 35, 'Log-Design', 'fghfh fgjh ', 58, 80.00, 4640.00, 8, 4269),
(88, 35, 'Log-Design', 'fdgd hfg dfg', 80, 90.00, 7200.00, 6, 6768),
(89, 36, 'Log-Design', '', 50, 580.00, 29000.00, 5, 27550),
(90, 37, 'Log-Design', '', 50, 580.00, 29000.00, 5, 27550),
(91, 38, 'Log-Design', '', 2, 50.00, 100.00, 5, 95),
(92, 38, 'Log-Design', '', 500, 5000.00, 2500000.00, 5, 2375000),
(93, 39, 'Log-Design', '', 50, 50.00, 2500.00, 5, 2375),
(94, 40, 'Log-Design', '', 50, 50.00, 2500.00, 5, 2375),
(95, 41, 'Log-Design', '', 50, 500.00, 25000.00, 5, 23750),
(96, 42, 'Log-Design', '', 50, 50.00, 2500.00, 5, 2375),
(97, 43, 'Log-Design', '', 50, 50.00, 2500.00, 5, 2375),
(98, 44, 'Log-Design', '', 50, 50.00, 2500.00, 8, 2300),
(99, 45, 'Log-Design', '', 50, 50.00, 2500.00, 5, 2375);

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
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `gst_no`
--
ALTER TABLE `gst_no`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `service_names`
--
ALTER TABLE `service_names`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
