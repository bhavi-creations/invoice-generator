-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 11:29 AM
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
-- Database: `invoice`
--

-- --------------------------------------------------------

--
-- Table structure for table `advancehistory`
--

CREATE TABLE `advancehistory` (
  `id` int(11) NOT NULL,
  `Invoice_no` int(150) NOT NULL,
  `Date` date NOT NULL,
  `advance` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 'ram raj cotton', 'rams', '97897879988', 'sample@gmai.com', 'kkd', 'ERWGE'),
(2, 'reddy rs', 'reddy', '0000000000', 'sample@gmai.com', 'kkd', '2342'),
(3, 'sample', 'ram', '97897879988', 'dnfksn@gmail.com', 'kkd', '2342');

-- --------------------------------------------------------

--
-- Table structure for table `expenditure_desc_tbl`
--

CREATE TABLE `expenditure_desc_tbl` (
  `id` int(11) NOT NULL,
  `main_expenditure_id` int(11) NOT NULL,
  `exp_name` varchar(255) NOT NULL,
  `exp_description` text NOT NULL,
  `mode_payment` text NOT NULL,
  `amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure_tbl`
--

CREATE TABLE `expenditure_tbl` (
  `id` int(11) NOT NULL,
  `total_amount` int(225) NOT NULL,
  `amount_in_words` text NOT NULL,
  `exp_note` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exp_name`
--

CREATE TABLE `exp_name` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exp_type`
--

CREATE TABLE `exp_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 3),
(2, 8);

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
  `Customer_id` int(11) DEFAULT NULL,
  `Final` float NOT NULL,
  `Gst` int(20) NOT NULL,
  `Gst_total` float NOT NULL,
  `Grandtotal` float NOT NULL,
  `total_paid` decimal(10,2) DEFAULT 0.00,
  `balance_due` decimal(10,2) DEFAULT 0.00,
  `payment_status` varchar(20) DEFAULT 'Unpaid',
  `Totalinwords` text NOT NULL,
  `Terms` text NOT NULL,
  `Note` text NOT NULL,
  `advance` float NOT NULL,
  `balance` float NOT NULL,
  `balancewords` text NOT NULL,
  `status` varchar(150) NOT NULL,
  `payment_details_type` varchar(20) DEFAULT NULL,
  `stamp_image` varchar(255) DEFAULT NULL,
  `signature_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Sid`, `Invoice_no`, `Invoice_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Customer_id`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `total_paid`, `balance_due`, `payment_status`, `Totalinwords`, `Terms`, `Note`, `advance`, `balance`, `balancewords`, `status`, `payment_details_type`, `stamp_image`, `signature_image`) VALUES
(167, 1, '2025-07-31', 'reddy rs', 'reddy', '0000000000', 'kkd', 'sample@gmai.com', '2342', 2, 4, 3, 0.12, 4.12, 0.00, 4.12, '0', 'four rupees and undefined paisa only ', '', '', 0, 4.12, 'four rupees and undefined paisa only ', 'paid', 'personal', 'stamp_688aefa01bde69.74821057.jpg', 'stamp_688aefaab9b441.83791332.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_files`
--

CREATE TABLE `invoice_files` (
  `id` int(11) NOT NULL,
  `Invoice_id` int(11) NOT NULL,
  `File_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_files`
--

INSERT INTO `invoice_files` (`id`, `Invoice_id`, `File_path`) VALUES
(1, 134, '134-6889fedee6812-invoice_34.pdf'),
(3, 162, '162-688aecae98ef6-invoice_1 (1).pdf'),
(4, 165, '165-688af118988cf-signiture.jpeg'),
(5, 166, '166-688af20ed0cfa-invoice_58.pdf'),
(9, 167, '688b01bceb5af-invoice_25.pdf'),
(10, 167, '688b0a3303df4-invoice_3.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_payments`
--

CREATE TABLE `invoice_payments` (
  `payment_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `receipt_no` varchar(255) NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lgtable`
--

CREATE TABLE `lgtable` (
  `Id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lgtable`
--

INSERT INTO `lgtable` (`Id`, `email`, `password`) VALUES
(1, 'rajkumar16371@gmail.com', '482c811da5d5b4bc6d497ffa98491e38'),
(3, 'bhavicreations', '34e59e638501f09468e1e9364c34f95f');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `invoice_sid` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Cash',
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation`
--

CREATE TABLE `quotation` (
  `Sid` int(11) NOT NULL,
  `quotation_no` int(11) NOT NULL,
  `quotation_date` date NOT NULL,
  `Company_name` varchar(150) NOT NULL,
  `Cname` varchar(50) NOT NULL,
  `Cphone` varchar(150) NOT NULL,
  `Caddress` text NOT NULL,
  `Cmail` varchar(150) NOT NULL,
  `Cgst` varchar(150) NOT NULL,
  `Final` float NOT NULL,
  `Gst` int(20) NOT NULL,
  `Gst_total` float NOT NULL,
  `Grandtotal` float NOT NULL,
  `Totalinwords` text NOT NULL,
  `Terms` text NOT NULL,
  `Note` text NOT NULL,
  `advance` float NOT NULL,
  `balance` float NOT NULL,
  `balancewords` text NOT NULL,
  `selected_stamp_filename` varchar(255) DEFAULT NULL,
  `selected_signature_filename` varchar(255) DEFAULT NULL,
  `payment_details_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`Sid`, `quotation_no`, `quotation_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `Totalinwords`, `Terms`, `Note`, `advance`, `balance`, `balancewords`, `selected_stamp_filename`, `selected_signature_filename`, `payment_details_type`) VALUES
(14, 1, '1970-01-01', 'ram raj cotton', 'rams', '97897879988', 'kkd', 'sample@gmai.com', 'ERWGE', 12, 3, 0.36, 12.36, '0', '', '', 0, 12.36, 'twelve rupees and thirty six  paisa only ', 'stamp_6878c1ccae4d86.17481347.jpg', 'stamp_6878c1f200dc23.18835755.jpeg', 'personal'),
(15, 2, '2025-07-30', 'reddy rs', 'reddy', '0000000000', 'kkd', 'sample@gmai.com', '2342', 1, 3, 0.03, 1.03, '0', '', '', 0, 1.03, 'one rupees and three  paisa only ', '', 'stamp_6878c1f200dc23.18835755.jpeg', 'office'),
(16, 3, '1970-01-01', 'sample', 'ram', '97897879988', 'kkd', 'dnfksn@gmail.com', '2342', 1, 3, 0.03, 1.03, '0', '', 'reger', 0, 1.03, 'one rupees and three  paisa only ', 'stamp_688aefa01bde69.74821057.jpg', 'stamp_688aefaab9b441.83791332.jpeg', 'personal');

-- --------------------------------------------------------

--
-- Table structure for table `quote_files`
--

CREATE TABLE `quote_files` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quote_files`
--

INSERT INTO `quote_files` (`id`, `quote_id`, `file_path`) VALUES
(1, 1, '1-6878a0bbda82f-Bhavi round stand.pdf'),
(5, 16, '16-688b23633227c-invoice_1 (4).pdf');

-- --------------------------------------------------------

--
-- Table structure for table `quservice`
--

CREATE TABLE `quservice` (
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
-- Dumping data for table `quservice`
--

INSERT INTO `quservice` (`Id`, `Sid`, `Sname`, `Description`, `Qty`, `Price`, `Totalprice`, `Discount`, `Finaltotal`) VALUES
(3, 1, 'Log-Design', '', 1, 2.00, 0.00, 0, 2),
(13, 2, 'Logo-Designing', '', 1, 20000.00, 0.00, 0, 20000),
(14, 2, 'Web Designing', '', 1, 12.00, 0.00, 0, 12),
(15, 2, '', '', 0, 0.00, 0.00, 0, 0),
(16, 2, '', '', 0, 0.00, 0.00, 0, 0),
(17, 3, 'Logo-Designing', '', 1, 111.00, 111.00, 0, 111),
(18, 4, 'Web Designing', '', 9, 800.00, 7200.00, 0, 7200),
(19, 5, 'Web Designing', '', 1, 1111.00, 1111.00, 0, 1111),
(20, 6, 'Web Designing', '', 3, 134.00, 402.00, 0, 402),
(21, 7, 'Logo-Designing', '', 1, 12121.00, 12121.00, 0, 12121),
(22, 8, 'Logo-Designing', '', 1, 11211.00, 11211.00, 0, 11211),
(23, 9, 'Logo-Designing', '', 1, 1212.00, 1212.00, 0, 1212),
(24, 10, 'Logo-Designing', '', 1, 1212.00, 1212.00, 0, 1212),
(26, 12, 'Logo-Designing', '', 2, 12.00, 24.00, 0, 24),
(33, 11, 'Logo-Designing', '', 12, 2323.00, 0.00, 0, 27876),
(35, 13, 'Logo-Designing', '', 1, 11.00, 0.00, 0, 11),
(39, 14, 'Logo-Designing', '', 4, 12.00, 0.00, 0, 12),
(40, 15, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(55, 16, 'Logo-Designing', '', 1, 1.00, 0.00, 0, 1);

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
(1, 92, 'Logo-Designing', '', 1, 111.00, 111.00, 0, 111),
(2, 93, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(3, 94, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(4, 95, 'Web Designing', '', 22, 2.00, 44.00, 0, 44),
(6, 97, 'Logo-Designing', '', 22, 200.00, 4400.00, 0, 4400),
(7, 98, 'Logo-Designing', '', 1, 222.00, 222.00, 0, 222),
(8, 99, 'Logo-Designing', '', 2, 22.00, 44.00, 0, 44),
(9, 102, 'Logo-Designing', '', 7, 100.00, 700.00, 0, 700),
(11, 103, 'Web Designing', '', 4, 2000.00, 0.00, 0, 8000),
(13, 105, 'Logo-Designing', '', 1, 11.00, 11.00, 0, 11),
(14, 104, 'Logo-Designing', '', 4, 44.00, 176.00, 0, 176),
(15, 106, 'Logo-Designing', '', 1, 1444.00, 1444.00, 0, 1444),
(16, 107, 'Logo-Designing', '', 3, 12.00, 0.00, 0, 12),
(17, 108, 'Logo-Designing', 'gg', 1, 1.00, 1.00, 0, 1),
(18, 109, 'Web Designing', '3', 1, 2.00, 2.00, 0, 2),
(19, 110, 'Logo-Designing', '111', 11, 1.00, 11.00, 0, 11),
(21, 112, 'Logo-Designing', '111', 11, 1.00, 0.00, 0, 0),
(22, 113, 'Logo-Designing', '2', 1, 1.00, 1.00, 1, 0),
(23, 114, 'Logo-Designing', '', 1, 2.00, 2.00, 0, 2),
(24, 115, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(25, 116, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(26, 117, 'Logo-Designing', 'w', 1, 1.00, 1.00, 0, 1),
(27, 118, 'Logo-Designing', 'w', 1, 1.00, 1.00, 0, 1),
(28, 119, 'Logo-Designing', 'w', 1, 1.00, 0.00, 0, 0),
(29, 120, 'Logo-Designing', 'w', 1, 1.00, 0.00, 0, 0),
(30, 121, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(31, 122, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(32, 123, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(33, 124, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(34, 125, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(35, 126, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(36, 127, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(37, 128, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(38, 129, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(39, 130, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(40, 131, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(41, 132, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(42, 133, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(43, 134, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(44, 135, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(45, 136, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(46, 137, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(47, 138, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(48, 139, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(49, 140, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(50, 141, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(51, 142, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(52, 143, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(53, 144, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(54, 145, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(55, 146, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(56, 147, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(57, 148, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(58, 149, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(59, 150, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(60, 151, 'Logo-Designing', '', 1, 11.00, 11.00, 0, 11),
(61, 152, 'Logo-Designing', '', 1, 11.00, 11.00, 0, 11),
(62, 153, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(63, 154, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(64, 155, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(65, 156, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(66, 157, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(67, 158, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(68, 159, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(73, 111, 'Logo-Designing', '111', 111, 1.00, 0.00, 0, 111),
(75, 161, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(77, 160, 'Logo-Designing', '', 1, 1.00, 0.00, 0, 1),
(78, 162, 'Logo-Designing', '', 2, 2.00, 4.00, 0, 4),
(79, 163, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(81, 165, 'Logo-Designing', '', 1, 1.00, 1.00, 0, 1),
(82, 166, 'Logo-Designing', '', 6, 6.00, 36.00, 0, 36),
(86, 164, 'Logo-Designing', '', 1, 1.00, 0.00, 0, 1),
(87, 164, 'Web Designing', '', 1, 4.00, 4.00, 0, 4),
(100, 167, 'Logo-Designing', '', 2, 2.00, 0.00, 0, 4);

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
(1, 'Logo-Designing'),
(2, 'Web Designing');

-- --------------------------------------------------------

--
-- Table structure for table `stamps`
--

CREATE TABLE `stamps` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `type` enum('company_stamp','director_stamp','signature') NOT NULL,
  `description` text DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1,
  `uploaded_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stamps`
--

INSERT INTO `stamps` (`id`, `file_name`, `display_name`, `type`, `description`, `uploaded_at`, `is_active`, `uploaded_by`) VALUES
(10, 'stamp_688aef936164d5.97996797.png', 'director stamp', 'director_stamp', '', '2025-07-31 09:52:43', 1, NULL),
(11, 'stamp_688aefa01bde69.74821057.jpg', 'company stamp', 'company_stamp', '', '2025-07-31 09:52:56', 1, NULL),
(12, 'stamp_688aefaab9b441.83791332.jpeg', 'signeture', 'signature', '', '2025-07-31 09:53:06', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) NOT NULL,
  `stock_name` text NOT NULL,
  `stock_desc` text NOT NULL,
  `stock_qty` int(11) NOT NULL,
  `stock_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advancehistory`
--
ALTER TABLE `advancehistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `expenditure_desc_tbl`
--
ALTER TABLE `expenditure_desc_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenditure_tbl`
--
ALTER TABLE `expenditure_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exp_name`
--
ALTER TABLE `exp_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exp_type`
--
ALTER TABLE `exp_type`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `invoice_files`
--
ALTER TABLE `invoice_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `receipt_no` (`receipt_no`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `lgtable`
--
ALTER TABLE `lgtable`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_invoice_payment` (`invoice_sid`);

--
-- Indexes for table `quotation`
--
ALTER TABLE `quotation`
  ADD PRIMARY KEY (`Sid`);

--
-- Indexes for table `quote_files`
--
ALTER TABLE `quote_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_id` (`quote_id`);

--
-- Indexes for table `quservice`
--
ALTER TABLE `quservice`
  ADD PRIMARY KEY (`Id`);

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
-- Indexes for table `stamps`
--
ALTER TABLE `stamps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advancehistory`
--
ALTER TABLE `advancehistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenditure_desc_tbl`
--
ALTER TABLE `expenditure_desc_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenditure_tbl`
--
ALTER TABLE `expenditure_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exp_name`
--
ALTER TABLE `exp_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exp_type`
--
ALTER TABLE `exp_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gst_no`
--
ALTER TABLE `gst_no`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `invoice_files`
--
ALTER TABLE `invoice_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lgtable`
--
ALTER TABLE `lgtable`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `quote_files`
--
ALTER TABLE `quote_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quservice`
--
ALTER TABLE `quservice`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `service_names`
--
ALTER TABLE `service_names`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stamps`
--
ALTER TABLE `stamps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_payments`
--
ALTER TABLE `invoice_payments`
  ADD CONSTRAINT `invoice_payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`Sid`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_invoice_payment` FOREIGN KEY (`invoice_sid`) REFERENCES `invoice` (`Sid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
