-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 06:49 AM
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

--
-- Dumping data for table `advancehistory`
--

INSERT INTO `advancehistory` (`id`, `Invoice_no`, `Date`, `advance`) VALUES
(1, 116, '2024-01-13', 500),
(2, 118, '2024-01-13', 50),
(3, 0, '2024-01-19', 500),
(4, 121, '2024-01-13', 786),
(5, 47, '2024-01-13', 500),
(6, 19, '2024-01-13', 5),
(7, 19, '2024-01-13', 5),
(8, 49, '2024-01-17', 500),
(9, 49, '2024-01-17', 500),
(10, 49, '2024-01-17', 250),
(11, 4, '2024-01-18', 100000),
(12, 7, '2024-01-22', 0),
(13, 8, '2024-01-22', 0),
(14, 8, '2024-01-22', 0),
(15, 9, '1969-12-31', 0),
(16, 10, '1969-12-31', 0),
(17, 12, '2024-02-12', 50000),
(18, 13, '1970-01-01', 0),
(19, 14, '2024-02-13', 0),
(20, 13, '1970-01-01', 0),
(21, 14, '1970-01-01', 0),
(22, 15, '1970-01-01', 0),
(23, 16, '1970-01-01', 0),
(24, 17, '2024-05-04', 40000),
(25, 17, '2024-05-08', 40000),
(26, 18, '1970-01-01', 210000),
(27, 19, '1970-01-01', 50000),
(28, 21, '1970-01-01', 0),
(29, 22, '1970-01-01', 0),
(30, 25, '1970-01-01', 0),
(31, 26, '1970-01-01', 111111),
(32, 27, '2025-01-30', 0),
(33, 28, '2025-02-22', 60000),
(34, 29, '2025-02-22', 240000),
(35, 30, '2025-02-22', 29008),
(36, 31, '2025-02-22', 9000),
(37, 33, '2025-05-08', 0);

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
(6, 'Coco Farms', 'Mr.Satish Garu', '9885943399', '', 'Y Junction, Chebrolu, Gollaprolu Highway, Kakinada, Andhra Pradesh 533449', ''),
(7, 'Kakinada pharmacy', 'Mr. Raju garu', '8977812367', '', 'Pulavarthi Vari St, Kakinada, Andhra Pradesh 533001', ''),
(8, 'ABC Multispecialty hospital ', 'Mr.Harsh garu', '91777 77277', '', 'pitapuram', ''),
(9, 'MEDENG Collage', 'Mr.Kiran Garu', '+91 99853 81111', '', 'Vijayawada', ''),
(10, 'One stop Vascular', 'Dr.Rahul Garu', '+91 90300 97940', '', 'Hyderabad', ''),
(12, 'oxalate', 'Mr.Raju garu Oxlate', '+91 95818 57857', '', 'Rajamundry', ''),
(14, 'BHR', 'Mr.Bhanu Prakash garu', '+91 99660 63050', '', 'Hyderabad', ''),
(15, 'subbayyagarihoteltirupati', '', '8686394079', 'info@subbayyagarihoteltirupati.com', 'Padmavathipuram', ''),
(16, 'SMART Physio care', 'Mrs. B. Srividhya', '+91 77300 44533', '', 'Rajamundry', ''),
(17, 'Srinivasa Multi Speciality Dental Hospital Kakinada', 'Dr.Kiran Raju', '9569568567', '', 'Rama Rao Peta, Kakinada, Andhra Pradesh 533001', ''),
(18, 'Honda Showroom', 'Kishan Raj', '8886089966', '', 'Beside Sona vision', ''),
(19, 'REACH FOREIGN EDUCATION CONSULTANCY SERVICES', 'Mrs.Satya Rapaka', '093474 72799', '', '66-5-1/2B, Narasanna Nagar, Kakinada, Andhra Pradesh 533003', ''),
(20, 'BRAND BUZZ', 'Mr.Revanth', ' 096422 88873', '', 'No 6 Upstairs, Honey Group, 3-16B-93,Revenue Ward, Kakinada, Andhra Pradesh 533003', ''),
(21, 'Havells India pvt ltd', 'Navya Home appliances', '9030209250', '', 'Kakinada', ''),
(22, 'Garuda traders', 'Mr. Durgesh', '91 95053 53350', '', 'Kakinada', ''),
(23, 'Anish Dental kkd', 'Dr.Anish', '073962 56474', '', '1st floor, Kokila junction, RTC Complex Rd, above Vantillu, beside carewell hospital, G O Colony, Kakinada, Andhra Pradesh 533003', ''),
(24, 'earthbased', 'Mrs.Khushi Agrawal', '+91 9646492525', 'hello@earthbased.store', 'Flat B, 37-2-14, Vijayasindhu Residency, Market St, Urban, Kakinada, Andhra Pradesh 533005', ''),
(25, 'Earthbased', 'Mrs.Khushi Agrawal', ' +91 9646492525', 'hello@earthbased.store', 'Flat B, 37-2-14, Vijayasindhu Residency, Market St, Urban, Kakinada, Andhra Pradesh 533005', '37AAJFE4559K1ZX'),
(26, 'E THREYAS', 'Mr.Murali', '+91-8074291515 ', 'accounts@ethreyas.com', 'Jatlapeda kapu Street,  Ashok Nagar,Kakinada,  East Godavari,AP-533003', '37AAHFE1065C1ZT'),
(27, 'V Prime', 'Mr.Venkat', '+91 94947 89987', '', 'Kakinada', ''),
(28, 'Preachers Training School', 'Mr.K.G. Kumar', '08842342125', 'kedarisetti_2000@yahoo.com', 'Turangi', ''),
(29, 'Janasena ', 'Mr.Uday Garu ', '80967 37846', '', 'Kakinada', ''),
(30, 'Srinivasas Dental', 'Raju Garu', '9569568567', '', 'Kakinada', ''),
(31, 'Aruna Hospital', 'Dr.Seshagiri garu', '7997990181', '', 'Kakinada', ''),
(32, 'Intellitots', 'Mrs.Bindhu', ' 93983 84317', '', 'Kakinada', ''),
(33, 'AV studios', 'K.Venkata Ramana', '96032 02436', '', 'Rajahmundry', ''),
(34, 'Preachers Training School', 'K.G.Kumar', '08842342125', '', 'turangi', ''),
(35, 'Trident one', 'I. Charan ', '9848253144', '', 'Kakinada', ''),
(36, 'Varma Group', 'Varma Group', '7286 878 607', 'varmaindustrialenterprises@gmail.com', 'Kakinada', ''),
(37, 'Sri venkataramana Gaia Pvt LTD', 'SVR OILS', '7660001040', '', 'samaralakota', ''),
(38, 'Devarsh Hospital', 'Mr.Anji Nayak', '94945 73105', '', 'Pattipadu', ''),
(39, 'Leela hosptals', 'Krishnam Raju garu', '9676266667', '', 'Kakinada', ''),
(40, ' P.T.School, Turangi', ' P.T.School, Turangi', '94417 54711', '', ' P.T.School, Turangi', ''),
(41, 'Dr. Appaji Rayi', 'Dr. Appaji Rayi', '99592 18190', '', 'Hyderabad', ''),
(42, 'NABHAS Construction Pvt Ltd', 'G.Venkatesh', '9505999952', 'nabhasconstruction@gmail.com', '70-7-2/1,2 G1, Siddardha Nagar ,Kakinada', '37AAICN2867M1ZS'),
(43, 'SAMHITA Soilsolutions', 'Balusu Parvathi Rajyam', '9848549349', 'samhitasoilsolutions@gmail.com', 'Pandravada,Samalkota mandal', ''),
(44, 'Brews & Bites Restro Kakinada', 'Vignesh Garu', '9676744337', '', 'opp. Electrical Sub Station, G O Colony, Kakinada, Andhra Pradesh 533003', ''),
(45, 'sampe soultions', 'sample', '0000000000', 'sample@gmai.com', 'nothing', '2342');

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

--
-- Dumping data for table `expenditure_desc_tbl`
--

INSERT INTO `expenditure_desc_tbl` (`id`, `main_expenditure_id`, `exp_name`, `exp_description`, `mode_payment`, `amount`) VALUES
(1, 1, 'Rajkumar Giduthuri', 'asdsad', 'Phone-pay', 50),
(2, 1, 'Rajkumar Giduthuri', 'sadsad', 'Google-pay', 50),
(3, 3, 'Rajkumar Giduthuri', 'asdsads', 'select', 50);

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

--
-- Dumping data for table `exp_type`
--

INSERT INTO `exp_type` (`id`, `name`) VALUES
(1, 'BOOKS'),
(2, 'Printing'),
(6, 'other');

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
(7, 60),
(8, 3);

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
  `status` varchar(150) NOT NULL,
  `payment_details_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`Sid`, `Invoice_no`, `Invoice_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `Totalinwords`, `Terms`, `Note`, `advance`, `balance`, `balancewords`, `status`, `payment_details_type`) VALUES
(38, 4, '2024-01-18', 'One stop Vascular', 'Dr.Rahul Garu', '+91 90300 97940', 'Hyderabad', '', '', 300000, 0, 0, 300000, 'three lakh only ', 'Installment 1 = Rs. 1,00,000/-(Done)\r\nInstallment 2 = Rs. 1,00,000/-(23-02-2024)\r\nInstallment 3 = Rs. 1,00,000/-(24-06-2024)\r\n\r\n', 'Terms and conditions apply.', 100000, 200000, 'two lakh only ', 'pending', NULL),
(39, 5, '2024-01-20', 'oxalate', 'Mr.Raju garu Oxlate', '+91 95818 57857', 'Rajamundry', '', '', 134200, 18, 24156, 158356, 'one lakh fifty eight thousand three hundred and fifty six rupees only ', '1) This is 1st phase it will be 20-01-2024 to 15-02-2024(if payment done before 23-01-2024)\r\n2) Will visit 2 times in this period.\r\n3) terms and conditions apply.', '1)Training period valid for one year will visit every 45 days.\r\n2)monthly 2 google meets with team and take reports and implements.\r\n3)Designing set only one set between valid dates.\r\n4)Website making and 1 year maintenance.', 0, 158356, 'one lakh fifty eight thousand three hundred and fifty six rupees only ', 'pending', NULL),
(40, 6, '2024-01-20', 'REACH FOREIGN EDUCATION CONSULTANCY SERVICES', 'Mrs.Satya garu', '+91 93474 72799', 'Kakinada', '', '', 61800, 0, 0, 61800, 'sixty one thousand eight hundred only ', 'terms and conditions apply.\r\n', '1st Phase - 20-01-2024 to 10-02-2024', 0, 61800, 'sixty one thousand eight hundred only ', 'pending', NULL),
(41, 7, '2024-01-22', 'BHR', 'Mr.Bhanu Prakash garu', '+91 99660 63050', 'Hyderabad', '', '', 22000, 0, 0, 22000, 'twenty two thousand only ', '', '', 0, 22000, 'twenty two thousand only ', 'pending', NULL),
(46, 8, '1969-12-31', 'SMART Physio care', 'Mr.Pawan Kumar', '+91 77300 44533', 'Rajamundry', '', '', 1007500, 0, 0, 1007500, 'ten lakh seven thousand five hundred only ', 'Terms and conditions apply.\r\n60% payment should be before work starts.', 'In offline marketing charges might be increase.\r\n ', 0, 1007500, 'ten lakh seven thousand five hundred only ', ' ', NULL),
(47, 9, '1969-12-31', 'Srinivasa Multi Speciality Dental Hospital Kakinada', 'Dr.Kiran Raju', '9569568567', 'Rama Rao Peta, Kakinada, Andhra Pradesh 533001', '', '', 90000, 0, 0, 90000, 'ninety thousand only ', 'Total 3 installments \r\n1st -   07-02-2024  = 30,000\r\n1st -   07-04-2024  = 30,000\r\n1st -   07-06-2024  = 30,000\r\nTerms and conditions apply.', '07-02-2024 to 07-08-2024 (Project life)\r\nSocial media handling.\r\nExtra charge to social media influencer groups.\r\nNo limit for videos and reels.\r\n', 0, 90000, 'ninety thousand only ', 'pending', NULL),
(48, 10, '1969-12-31', 'REACH FOREIGN EDUCATION CONSULTANCY SERVICES', 'Mrs.Satya Rapaka', '093474 72799', '66-5-1/2B, Narasanna Nagar, Kakinada, Andhra Pradesh 533003', '', '', 38560, 0, 0, 38560, 'thirty eight thousand five hundred and sixty rupees only ', '', '', 0, 38560, 'thirty eight thousand five hundred and sixty rupees only ', 'paid', NULL),
(49, 11, '1969-12-31', 'BRAND BUZZ', 'Mr.Revanth', ' 096422 88873', 'No 6 Upstairs, Honey Group, 3-16B-93,Revenue Ward, Kakinada, Andhra Pradesh 533003', '', '', 24000, 0, 0, 24000, 'twenty four thousand only ', 'terms and conditions apply', 'Google pay , Phone pay. 8686394079', 0, 24000, 'twenty four thousand only ', ' ', NULL),
(50, 12, '1969-12-31', 'BRAND BUZZ', 'Mr.Revanth', ' 096422 88873', 'No 6 Upstairs, Honey Group, 3-16B-93,Revenue Ward, Kakinada, Andhra Pradesh 533003', '', '', 100300, 18, 18054, 118354, 'one lakh eighteen thousand three hundred and fifty four rupees only ', 'Terms and Conditions apply\r\n', 'Project timeline - 15-02-2024 to 15-04-2024', 50000, 68354, 'sixty eight thousand three hundred and fifty four rupees only ', ' ', NULL),
(53, 13, '1970-01-01', 'REACH FOREIGN EDUCATION CONSULTANCY SERVICES', 'Mrs.Satya Rapaka', '093474 72799', '66-5-1/2B, Narasanna Nagar, Kakinada, Andhra Pradesh 533003', '', '', 243000, 18, 43740, 286740, 'two lakh eighty six thousand seven hundred and forty rupees only ', 'terms and conditions apply.', '', 0, 286740, 'two lakh eighty six thousand seven hundred and forty rupees only ', 'pending', NULL),
(54, 14, '1970-01-01', 'Earthbased', 'Mrs.Khushi Agrawal', ' +91 9646492525', 'Flat B, 37-2-14, Vijayasindhu Residency, Market St, Urban, Kakinada, Andhra Pradesh 533005', 'hello@earthbased.store', '37AAJFE4559K1ZX', 150000, 18, 27000, 177000, 'one lakh seventy seven thousand only ', '@ Total payment in 2 instalments @ \r\n1)  Advance 60% payment means = Rs.106200/-. \r\n\r\n2) Remain after complete the work. \r\n\r\n3) Terms and conditions apply.', '@ 21-02-2024 to 20-04 -2024 @\r\n\r\n1) 21 -02 -2024 to 10 - 03 - 2024 = UI/UX Design.\r\n\r\n2)11-03-2024 to 26 -03-2024 = Home,About,Contact & Blog pages.\r\n\r\n3) 26-03-2024 to 10-04-2024 = Product Pages.\r\n\r\n4) 10-04-2024 to 20-04-2024 = Payment setting pannel setting.', 0, 177000, 'one lakh seventy seven thousand only ', '', NULL),
(55, 15, '1970-01-01', 'E THREYAS', 'Mr.Murali', '+91-8074291515 ', 'Jatlapeda kapu Street,  Ashok Nagar,Kakinada,  East Godavari,AP-533003', 'accounts@ethreyas.com', '37AAHFE1065C1ZT', 25000, 0, 0, 25000, 'twenty five thousand only ', '||Extra pricing for the US location plugin for mapping.||\r\nTotal payment in 3 installments:\r\n|50% in advance|\r\n|20% after template submission|\r\n|30% upon completion of work.|', 'Terms and conditions apply.\r\nGoogle Pay, Phone Pay, Paytm: 8686394079.\r\n', 0, 25000, 'twenty five thousand only ', '', NULL),
(56, 16, '1970-01-01', 'ABC Multispecialty hospital ', 'Mr.Harsh garu', '91777 77277', 'pitapuram', '', '', 7000, 0, 0, 7000, 'seven thousand only ', '', '', 0, 7000, 'seven thousand only ', 'pending', NULL),
(59, 17, '2024-05-08', 'Janasena ', 'Mr.Uday Garu ', '80967 37846', 'Kakinada', '', '', 80000, 0, 0, 80000, 'eighty thousand only ', '', '', 40000, 40000, 'forty thousand only ', 'paid', NULL),
(60, 18, '1970-01-01', 'Srinivasas Dental', 'Raju Garu', '9569568567', 'Kakinada', '', '', 210000, 0, 0, 210000, 'two lakh ten thousand only ', '', 'Time frame 10-02-2024 to 20-01-2025', 210000, 0, 'only ', '', NULL),
(61, 19, '1970-01-01', 'Aruna Hospital', 'Dr.Seshagiri garu', '7997990181', 'Kakinada', '', '', 174320, 0, 0, 174320, 'one lakh seventy four thousand three hundred and twenty rupees only ', 'Terms and conditions apply', '', 50000, 124320, 'one lakh twenty four thousand three hundred and twenty rupees only ', 'pending', NULL),
(62, 20, '1970-01-01', 'Intellitots', 'Mrs.Bindhu', ' 93983 84317', 'Kakinada', '', '', 77000, 0, 0, 77000, 'seventy seven thousand only ', 'terms and conditions apply.', '', 0, 77000, 'seventy seven thousand only ', '', NULL),
(63, 21, '1970-01-01', 'Intellitots', 'Mrs.Bindhu', ' 93983 84317', 'Kakinada', '', '', 77000, 0, 0, 77000, 'seventy seven thousand only ', 'terms & conditions apply.', 'Ph pay - 8686394079', 0, 77000, 'seventy seven thousand only ', 'pending', NULL),
(64, 22, '1970-01-01', 'AV studios', 'K.Venkata Ramana', '96032 02436', 'Rajahmundry', '', '', 57000, 0, 0, 57000, 'fifty seven thousand only ', '1st instalment - 27000(14-06-2024)\r\n\r\n\r\n2nd Instalment - 30000(14-9-2024)', 'Terms and Conditions Apply.', 0, 57000, 'fifty seven thousand only ', 'pending', NULL),
(65, 23, '1970-01-01', 'Preachers Training School', 'Mr.K.G. Kumar', '08842342125', 'Turangi', 'kedarisetti_2000@yahoo.com', '', 49200, 0, 0, 49200, 'forty nine thousand two hundred only ', 'terms and conditions apply', '', 20000, 29200, 'twenty nine thousand two hundred only ', ' ', NULL),
(66, 24, '1970-01-01', 'Varma Group', 'Varma Group', '7286 878 607', 'Kakinada', 'varmaindustrialenterprises@gmail.com', '', 87575, 18, 15763.5, 103338, 'one lakh three thousand three hundred and thirty eight rupees and five  paisa only ', 'terms and conditions apply.', '', 0, 103338, 'one lakh three thousand three hundred and thirty eight rupees and five  paisa only ', ' ', NULL),
(67, 25, '1970-01-01', 'Sri venkataramana Gaia Pvt LTD', 'SVR OILS', '7660001040', 'samaralakota', '', '', 27000, 18, 4860, 31860, 'thirty one thousand eight hundred and sixty rupees only ', '', '', 0, 31860, 'thirty one thousand eight hundred and sixty rupees only ', 'pending', NULL),
(68, 26, '1970-01-01', 'Leela hosptals', 'Krishnam Raju garu', '9676266667', 'Kakinada', '', '', 175000, 0, 0, 175000, 'one lakh seventy five thousand only ', '', 'Leela dental and gynic two websites, two social media handels SEO for two (dental and gynic)', 111111, 63889, 'sixty three thousand eight hundred and eighty nine rupees only ', 'paid', NULL),
(69, 27, '2025-01-30', '', '', '', '', '', '', 0, 0, 0, 0, 'only ', '', '', 0, 0, 'only ', 'paid', NULL),
(70, 28, '2025-02-22', 'Srinivasas Dental', 'Raju Garu', '9569568567', 'Kakinada', '', '', 60000, 0, 0, 60000, 'sixty thousand only ', 'December 5th 2024 to December 5th 2025', '', 60000, 0, 'only ', 'paid', NULL),
(71, 29, '2025-02-22', 'Srinivasas Dental', 'Raju Garu', '9569568567', 'Kakinada', '', '', 240000, 0, 0, 240000, 'two lakh forty thousand only ', '6 feb 2024 to 6 feb 2025', '', 240000, 0, 'only ', 'paid', NULL),
(72, 30, '2025-02-22', ' P.T.School, Turangi', ' P.T.School, Turangi', '94417 54711', ' P.T.School, Turangi', '', '', 29008, 0, 0, 29008, 'twenty nine thousand and eight rupees only ', '', '', 29008, 0, 'only ', 'paid', NULL),
(73, 31, '2025-02-22', ' P.T.School, Turangi', ' P.T.School, Turangi', '94417 54711', ' P.T.School, Turangi', '', '', 9000, 0, 0, 9000, 'nine thousand only ', '12 oct 2024 to 12 oct  2025', '', 9000, 0, 'only ', 'paid', NULL),
(74, 32, '1970-01-01', 'Dr. Appaji Rayi', 'Dr. Appaji Rayi', '99592 18190', 'Hyderabad', '', '', 25020, 0, 0, 25020, 'twenty five thousand and twenty rupees only ', 'Payment through ph pay', 'Google pay , Phone pay. Paytm 8686394079', 0, 25020, 'twenty five thousand and twenty rupees only ', ' ', NULL),
(75, 33, '2025-05-08', 'Dr. Appaji Rayi', 'Dr. Appaji Rayi', '99592 18190', 'Hyderabad', '', '', 70500, 0, 0, 70500, 'seventy thousand five hundred only ', 'you can use this credits anytime.', 'payment to personal account only.', 0, 70500, 'seventy thousand five hundred only ', 'paid', NULL),
(85, 34, '2025-07-10', 'sampe soultions', 'sample', '0000000000', 'nothing', 'sample@gmai.com', '2342', 1089, 0, 0, 1089, '0', '', '', 0, 1089, 'one thousand and eighty nine rupees only ', ' ', NULL),
(86, 35, '2025-07-10', 'sampe soultions', 'sample', '0000000000', 'nothing', 'sample@gmai.com', '2342', 1089, 0, 0, 1089, '0', '', '', 0, 1089, 'one thousand and eighty nine rupees only ', ' ', NULL),
(87, 36, '2025-07-10', 'sampe soultions', 'sample', '0000000000', 'nothing', 'sample@gmai.com', '2342', 1089, 0, 0, 1089, '0', '', '', 0, 1089, 'one thousand and eighty nine rupees only ', ' ', NULL);

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
(1, 77, 'uploads/77-686e45fa65ca3-CocoFarms Luxury Resort (2) (3).pdf'),
(2, 77, 'uploads/77-686e45fa660aa-stock-vector-alphabet-letters-icon-logo-vk-or-kv-monogram-2203519181_1747721447.jpg'),
(3, 78, '78-686e491f00e2d-Sales_by_safi_20250610_063145.pdf'),
(4, 78, '78-686e491f01377-vk new 4.png'),
(5, 79, '79-686e4b7c5d110-ss.pdf'),
(6, 80, '80-686e4f974c7e5-ss.pdf'),
(7, 81, '81-686e512e39b1d-2-1 RESULTS MAY  2025.pdf'),
(8, 82, '82-686f56fa6c5ec-ss.pdf'),
(9, 82, '82-686f56fa6cb6c-th_1747721447_2024393584.jpg');

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
  `payment_details_type` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quotation`
--

INSERT INTO `quotation` (`Sid`, `quotation_no`, `quotation_date`, `Company_name`, `Cname`, `Cphone`, `Caddress`, `Cmail`, `Cgst`, `Final`, `Gst`, `Gst_total`, `Grandtotal`, `Totalinwords`, `Terms`, `Note`, `advance`, `balance`, `balancewords`, `payment_details_type`) VALUES
(9, 5, '1969-12-31', 'SMART Physio care', 'Mrs. B. Srividhya', '+91 77300 44533', 'Rajamundry', '', '', 1007500, 0, 0, 1007500, 'ten lakh seven thousand five hundred only ', 'Terms and conditions apply.\r\n 60% payment should be before work starts.', 'In oï¬„ine marketing charges might be increase', 0, 1007500, 'ten lakh seven thousand five hundred only ', NULL),
(10, 6, '1969-12-31', 'Honda Showroom', 'Kishan Raj', '8886089966', 'Beside Sona vision', '', '', 15000, 18, 2700, 17700, 'seventeen thousand seven hundred only ', 'terms and conditions apply', '', 0, 17700, 'seventeen thousand seven hundred only ', NULL),
(38, 16, '1970-01-01', 'Srinivasas Dental', 'Raju Garu', '9569568567', 'Kakinada', '', '', 240000, 0, 0, 240000, 'two lakh forty thousand only ', '', 'Project life 07-02-2024 to 07-02-2025', 210000, 30000, 'thirty thousand only ', NULL),
(40, 18, '1970-01-01', 'Intellitots', 'Mrs.Bindhu', ' 93983 84317', 'Kakinada', '', '', 35000, 0, 0, 35000, 'thirty five thousand only ', '1 year full service of social media management and all type of designs', 'terms and conditions apply', 0, 35000, 'thirty five thousand only ', NULL),
(41, 19, '1970-01-01', 'Preachers Training School', 'Mr.K.G. Kumar', '08842342125', 'Turangi', 'kedarisetti_2000@yahoo.com', '', 49200, 0, 0, 49200, 'forty nine thousand two hundred only ', 'terms and conditions apply', '', 20000, 29200, 'twenty nine thousand two hundred only ', NULL),
(43, 21, '1970-01-01', 'Varma Group', 'Varma Group', '7286 878 607', 'Kakinada', 'varmaindustrialenterprises@gmail.com', '', 51875, 18, 9337.5, 61212.5, 'sixty one thousand two hundred and twelve rupees and five  paisa only ', 'terms and conditions apply.', '1) Advance = 30000\r\n2 After work complete = 31212', 0, 61212.5, 'sixty one thousand two hundred and twelve rupees and five  paisa only ', NULL),
(44, 22, '1970-01-01', 'Sri venkataramana Gaia Pvt LTD', 'SVR OILS', '7660001040', 'samaralakota', '', '', 18000, 18, 3240, 21240, 'twenty one thousand two hundred and forty rupees only ', '', '', 0, 21240, 'twenty one thousand two hundred and forty rupees only ', NULL),
(45, 23, '1970-01-01', 'Dr. Appaji Rayi', 'Dr. Appaji Rayi', '99592 18190', 'Hyderabad', '', '', 25020, 0, 0, 25020, 'twenty five thousand and twenty rupees only ', 'Payment through ph pay', 'Google pay , Phone pay. Paytm 8686394079', 0, 25020, 'twenty five thousand and twenty rupees only ', NULL),
(46, 24, '2025-05-23', 'SAMHITA Soilsolutions', 'Balusu Parvathi Rajyam', '9848549349', 'Pandravada,Samalkota mandal', 'samhitasoilsolutions@gmail.com', '', 83000, 18, 14940, 97940, 'ninety seven thousand nine hundred and forty rupees only ', '', '', 0, 97940, 'ninety seven thousand nine hundred and forty rupees only ', NULL),
(47, 25, '2025-06-18', 'Brews & Bites Restro Kakinada', 'Vignesh Garu', '9676744337', 'opp. Electrical Sub Station, G O Colony, Kakinada, Andhra Pradesh 533003', '', '', 22170, 0, 0, 22170, 'twenty two thousand one hundred and seventy rupees only ', 'Full payment is required upfront. Final delivery will be completed within 2 days after design confirmation.', 'Payment - ph pay/gpay\r\n8686394079', 0, 22170, 'twenty two thousand one hundred and seventy rupees only ', NULL),
(48, 26, '2025-06-18', 'Brews & Bites Restro Kakinada', 'Vignesh Garu', '9676744337', 'opp. Electrical Sub Station, G O Colony, Kakinada, Andhra Pradesh 533003', '', '', 36400, 0, 0, 36400, 'thirty six thousand four hundred only ', '1-Year Website Maintenance Policy:**\r\n\r\n> ✅ **Included (Free of Cost):**\r\n\r\n> * Text/content changes\r\n> * Menu updates\r\n> * Price modifications\r\n\r\n> ❌ **Not Included (Extra Charges Apply):**\r\n\r\n> * Adding new pages\r\n> * Creating new sections\r\n> * Functional or structural changes\r\n\r\n', '1)50% advance payment before starting the website development.\r\n\r\n2)Remaining 50% to be paid after the website is completed and approved\r\n\r\nPayment - ph pay/gpay\r\n8686394079', 0, 36400, 'thirty six thousand four hundred only ', NULL),
(49, 27, '2025-06-18', 'Brews & Bites Restro Kakinada', 'Vignesh Garu', '9676744337', 'opp. Electrical Sub Station, G O Colony, Kakinada, Andhra Pradesh 533003', '', '', 37400, 0, 0, 37400, 'thirty seven thousand four hundred only ', 'Full payment is required upfront after the design is finalized.\r\n\r\nYou are allowed up to 2 free changes after the design is approved.\r\n\r\nAny additional changes beyond 2 will be charged extra based on the scope of work.', '', 0, 37400, 'thirty seven thousand four hundred only ', NULL),
(60, 30, '2025-07-11', 'sampe soultions', 'sample', '0000000000', 'nothing', 'sample@gmai.com', '2342', 484, 0, 0, 484, '0', '', '', 0, 484, 'four hundred and eighty four rupees only ', 'office');

-- --------------------------------------------------------

--
-- Table structure for table `quote_files`
--

CREATE TABLE `quote_files` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 1, 'Google My Business', 'dfgdsfg', 2, 50.00, 100.00, 5, 95),
(2, 1, 'Log-Design', 'fdgfdg', 5, 50.00, 250.00, 5, 238),
(3, 2, 'Website', 'dfgdfg', 50, 50.00, 2500.00, 5, 2375),
(4, 3, 'Social Media Management', 'dgdsfg', 50, 50.00, 2500.00, 8, 2300),
(5, 4, 'Log-Design', '', 50, 5.00, 250.00, 5, 238),
(6, 5, 'Log-Design', 'dhffghfg', 50, 5.00, 250.00, 5, 238),
(7, 5, 'SEO', 'hgfhgf', 80, 5.00, 400.00, 5, 380),
(8, 5, 'Letter Heads', 'dhfhdghgfh', 80, 5.00, 400.00, 8, 368),
(9, 0, 'Log-Design', 'testing', 1, 23.00, 23.00, 0, 23),
(10, 6, 'Log-Design', 'test', 1, 23.00, 23.00, 0, 23),
(11, 7, 'fastile', '', 100, 50.00, 5000.00, 10, 4500),
(12, 8, 'offline marketing', 'Visiting cards, Pamphlets, Brousers, Hoardings and etc', 1, 700000.00, 700000.00, 15, 595000),
(13, 8, 'online marketing', 'Website, SEO, Social media management and etc', 1, 300000.00, 300000.00, 10, 270000),
(14, 8, 'Branding', 'Ideas about marketing', 1, 150000.00, 150000.00, 5, 142500),
(15, 9, 'offline marketing', 'Visiting cards, Pamphlets, Brousers, Hoardings and etc', 1, 700000.00, 700000.00, 15, 595000),
(16, 9, 'online marketing', 'Website, SEO, Social media management and etc', 1, 300000.00, 300000.00, 10, 270000),
(17, 9, 'Branding', 'Ideas about marketing', 1, 150000.00, 150000.00, 5, 142500),
(18, 10, 'Image Designing', 'Special days and festivals', 30, 500.00, 15000.00, 0, 15000),
(19, 11, 'Website', 'Lions Club, Home,About,Gallery,Contact us&service page', 1, 10000.00, 10000.00, 0, 10000),
(20, 11, 'customized back panels for website images edit', '2 pannels for gallery and home page', 2, 5000.00, 10000.00, 0, 10000),
(21, 11, 'Hosting', '1 year hosting fee', 1, 2500.00, 2500.00, 0, 2500),
(22, 12, 'Website', 'dynamic', 1, 20000.00, 20000.00, 0, 20000),
(23, 12, 'Hosting', '1 year', 1, 2500.00, 2500.00, 0, 2500),
(24, 12, 'Website maintenance', '1 year', 1, 7500.00, 7500.00, 0, 7500),
(25, 13, 'Designing Set', '5 textbooks design', 5, 20000.00, 100000.00, 0, 100000),
(26, 14, 'Designing Set', 'customized font', 1, 30000.00, 30000.00, 20, 24000),
(27, 15, 'Image Designing', '', 30, 600.00, 18000.00, 5, 17100),
(28, 15, 'Reels', 'GIF/Reels', 30, 800.00, 24000.00, 5, 22800),
(29, 15, 'Video Creation', '', 4, 3000.00, 12000.00, 5, 11400),
(30, 15, 'Video Editing', '', 20, 1000.00, 20000.00, 0, 20000),
(31, 15, 'Social Media Management', 'Fb,insta,youtube and Linkedin', 2, 15000.00, 30000.00, 10, 27000),
(32, 15, 'Google My Business', '', 1, 2000.00, 2000.00, 0, 2000),
(33, 16, 'Google My Business', 'test', 5, 80.00, 400.00, 0, 400),
(34, 17, 'Google My Business', 'test', 50, 50.00, 2500.00, 0, 2500),
(35, 17, 'Video Creation', 'test', 80, 5.00, 400.00, 0, 400),
(36, 18, 'Website', 'test', 20, 20.00, 400.00, 0, 400),
(37, 19, 'Website', 'test', 50, 50.00, 2500.00, 0, 2500),
(38, 20, 'Log-Design', 'test', 50, 50.00, 2500.00, 5, 2375),
(39, 21, 'Google My Business', 'test', 50, 50.00, 2500.00, 5, 2375),
(40, 21, 'Flex', 'test', 900, 800.00, 720000.00, 8, 662400),
(41, 22, 'Website', 'test', 50, 50.00, 2500.00, 5, 2375),
(42, 22, 'Flex', 'test', 80, 80.00, 6400.00, 5, 6080),
(43, 23, 'Log-Design', 'test', 80, 80.00, 6400.00, 5, 6080),
(44, 24, 'Log-Design', 'test', 50, 50.00, 2500.00, 5, 2375),
(45, 25, 'Log-Design', 'test', 50, 50.00, 2500.00, 0, 2500),
(46, 26, 'Google My Business', 'test', 10, 10.00, 100.00, 0, 100),
(47, 27, 'Website', 'test', 50, 5.00, 250.00, 0, 250),
(48, 28, 'Log-Design', 'test', 50, 50.00, 2500.00, 0, 2500),
(49, 29, 'Log-Design', 'test', 50, 50.00, 2500.00, 0, 2500),
(50, 29, 'Log-Design', 'test', 9, 88.00, 792.00, 0, 792),
(51, 30, 'Google My Business', 'test', 10, 20.00, 200.00, 5, 190),
(52, 30, 'Image Designing', 'test', 60, 60.00, 3600.00, 0, 3600),
(53, 31, 'Social Media Management', 'test', 900, 55.00, 49500.00, 2, 48510),
(54, 31, 'Video Creation', 'test', 80, 90.00, 7200.00, 1, 7128),
(55, 32, 'Website', '', 50, 50.00, 2500.00, 5, 2375),
(56, 32, 'Website', '', 20, 10.00, 200.00, 6, 188),
(57, 33, 'fb ad', '15-20 km around Kakinada', 90, 400.00, 36000.00, 0, 36000),
(58, 33, 'insta ad', '15-20 km around Kakinada', 90, 600.00, 54000.00, 0, 54000),
(59, 33, 'Youtube ad', '15-20 km around Kakinada', 90, 700.00, 63000.00, 0, 63000),
(60, 33, 'Service charge', '90 days service charge', 90, 300.00, 27000.00, 0, 27000),
(61, 34, 'Image Designing', '', 1, 300.00, 300.00, 0, 300),
(62, 34, 'Fb/insta ads', 'reach kakinada 15-20km\r\nreach 5L people \r\nLeads 50-60', 1, 5000.00, 5000.00, 0, 5000),
(63, 34, 'Service charge', '', 1, 500.00, 500.00, 0, 500),
(64, 35, 'Website', 'Dynamic Website, Domain,Hosting', 1, 15000.00, 15000.00, 10, 13500),
(65, 35, 'Website maintenance', '', 1, 3000.00, 3000.00, 100, 0),
(66, 35, 'Google My Business', 'Photos and revies update', 1, 3000.00, 3000.00, 0, 3000),
(67, 35, 'Social Media Management', 'Fb/Inst - 8 Images, 4 Reels\r\nYoutube - 4 videos', 12, 15000.00, 180000.00, 20, 144000),
(68, 35, 'SEO', '8 Keywords - time line 8 months minmum', 12, 8000.00, 96000.00, 25, 72000),
(69, 36, 'Image Designing', '26 stickers 2*3', 26, 150.00, 3900.00, 0, 3900),
(70, 36, 'flex', 'eco vinyl printing', 156, 28.00, 4368.00, 0, 4368),
(71, 37, 'Log-Design', 'testt', 2, 22.00, 44.00, 0, 44),
(72, 37, 'Demo tents', 'test', 2, 22.00, 44.00, 0, 44),
(73, 38, 'Video Creation', '', 12, 20000.00, 240000.00, 0, 240000),
(74, 39, 'Hoarding', 'D-Mart Water tank (10*20)\r\n1YEAR', 1, 55000.00, 55000.00, 0, 55000),
(75, 39, 'SRMT LED Screen', '1 moth', 1, 15000.00, 15000.00, 0, 15000),
(76, 39, 'SRMT LED Screen', 'food court - 1 MONTH', 1, 12000.00, 12000.00, 0, 12000),
(77, 39, 'Theater Ads ', 'Anadh one screen - 1month', 1, 10000.00, 10000.00, 0, 10000),
(78, 40, 'Digital marketing', '1 year digital service', 1, 35000.00, 35000.00, 0, 35000),
(79, 41, 'Website', '', 1, 60000.00, 60000.00, 18, 49200),
(80, 42, 'Bulk Whattapp', '1Lakh messages', 100000, 0.10, 10000.00, 0, 10000),
(81, 42, 'Hosting', '1 Year Pannel', 10000, 1.00, 10000.00, 0, 10000),
(82, 43, 'Domain', 'varmagroup.in , varmagroup.net', 1, 9000.00, 9000.00, 0, 9000),
(83, 43, 'Hosting', '5 years space for hosting\r\n516bandwidth, 2 gb space', 5, 2700.00, 13500.00, 15, 11475),
(84, 43, 'SSL', 'Safe and secure for website', 5, 1600.00, 8000.00, 25, 6000),
(85, 43, 'Website', 'frontend html, css and react', 1, 25000.00, 25000.00, 56, 11000),
(86, 43, 'Website maintenance', '1 year minor maintained ', 1, 10000.00, 10000.00, 100, 0),
(87, 43, 'customized back panels for website images edit', '3 Backend pannels', 3, 12000.00, 36000.00, 60, 14400),
(88, 43, 'Social Media Management', 'fb, instagram and youtube page creation', 1, 3000.00, 3000.00, 100, 0),
(89, 43, 'Google My Business', 'Adding details  of website, google analytics and contact number', 1, 5000.00, 5000.00, 100, 0),
(90, 44, 'Auto Stickers', 'Normal quality with laminated', 200, 90.00, 18000.00, 0, 18000),
(91, 45, 'Video Editing', 'Youtube Videos and Thumbnails', 30, 834.00, 25020.00, 0, 25020),
(92, 46, 'Website', 'Domain . SSL, Hosting and creation', 1, 20000.00, 20000.00, 0, 20000),
(93, 46, 'Designing Set', 'Packing stickers, social media creation, GMB', 1, 13000.00, 13000.00, 0, 13000),
(94, 46, 'Photo shoot', '1 time shoot and editing', 1, 30000.00, 30000.00, 0, 30000),
(95, 46, 'Lighting Board', 'Sq feet -500', 1, 20000.00, 20000.00, 0, 20000),
(96, 47, 'Pamphlet', '90 GSM, back and back art paper', 10000, 0.80, 8000.00, 0, 8000),
(97, 47, 'Coupons', '130 GSM , Numbering tier', 9000, 1.00, 9000.00, 0, 9000),
(98, 47, 'Paper distb', '5 points', 5, 400.00, 2000.00, 0, 2000),
(99, 47, 'Man D/b at Particular Point', '4 collages', 4, 500.00, 2000.00, 0, 2000),
(100, 47, 'Pamphlet', 'Designing b&b', 2, 500.00, 1000.00, 10, 900),
(101, 47, 'Coupons', 'Designing', 1, 300.00, 300.00, 10, 270),
(102, 48, 'Website', 'This is a customized dynamic website (non-eCommerce).', 1, 20000.00, 20000.00, 15, 17000),
(103, 48, 'Domain', 'brewsbiteskakinada.com', 1, 1300.00, 1300.00, 0, 1300),
(104, 48, 'Hosting', '', 1, 2200.00, 2200.00, 0, 2200),
(105, 48, 'Website maintenance', '1 year ', 1, 12000.00, 12000.00, 15, 10200),
(106, 48, 'Designing Set', 'Visiting card, Menu card, QR Code, Table Mat (paper)', 1, 6000.00, 6000.00, 5, 5700),
(107, 49, 'Reel shoot by Camera ', '4 reels shoot , editing, thumbnails and posting', 4, 3000.00, 12000.00, 0, 12000),
(108, 49, 'Social Media Management', '26 images designing and posting', 26, 400.00, 10400.00, 0, 10400),
(109, 49, 'Paid AD', 'Every creative will get 10k plus reach in Brews and Bites insta account', 1, 15000.00, 15000.00, 0, 15000),
(110, 50, 'Log-Design', '', 11, 11.00, 121.00, 0, 121),
(111, 51, 'Log-Design', '', 12, 12.00, 144.00, 0, 144),
(112, 52, 'Log-Design', '', 11, 111.00, 1221.00, 0, 1221),
(117, 53, 'Log-Design', '', 12, 12.00, 0.00, 0, 144),
(118, 53, 'Log-Design', '', 12, 12.00, 0.00, 0, 0),
(119, 53, 'Log-Design', '', 12, 12.00, 0.00, 0, 144),
(120, 53, 'Log-Design', '', 12, 12.00, 0.00, 0, 0),
(121, 54, 'Log-Design', '', 1, 1.00, 1.00, 0, 1),
(122, 55, 'Log-Design', '', 2, 2.00, 4.00, 0, 4),
(123, 56, 'Log-Design', '', 1, 1.00, 1.00, 0, 1),
(124, 57, 'Log-Design', '', 11, 22.00, 242.00, 0, 242),
(125, 58, 'Log-Design', '', 33, 33.00, 1089.00, 0, 1089),
(126, 59, 'Log-Design', '', 11, 11.00, 121.00, 0, 121),
(127, 60, 'Log-Design', '', 22, 22.00, 484.00, 0, 484),
(128, 61, 'Log-Design', '', 33, 33.00, 1089.00, 0, 1089);

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
(83, 33, 'Demo tents', '6*6', 3, 7000.00, 21000.00, 13, 18270),
(84, 33, 'Pamphlet', '3 sets ,  3k, 3k and 2k', 8000, 2.50, 20000.00, 10, 18000),
(85, 33, 'Brouchers', '4 fold browser', 500, 14.00, 7000.00, 10, 6300),
(86, 33, 'Social Media Influencers ', 'total 15 pages , 3 sets', 3, 7300.00, 21900.00, 0, 21900),
(87, 33, 'Fb/insta ads', '3 ad sets CPM (Cost per message)', 310, 35.00, 10850.00, 0, 10850),
(88, 33, 'Fb/insta ads', 'reach ad(7days)', 7, 500.00, 3500.00, 0, 3500),
(89, 34, 'Social Media Management', '', 1, 3000.00, 3000.00, 0, 3000),
(90, 35, 'Social Media Management', '', 1, 7000.00, 7000.00, 0, 7000),
(91, 36, 'Pamphlet', '90 GSM , b&b Multi color, art paper', 20000, 0.80, 16000.00, 0, 16000),
(92, 36, 'Stickers', 'A3 stickers', 1000, 14.00, 14000.00, 0, 14000),
(93, 37, 'Website', 'dfgdfg', 50, 50.00, 2500.00, 5, 2375),
(94, 38, 'Branding', 'Digital Marketing', 1, 300000.00, 300000.00, 0, 300000),
(95, 39, 'Google My Business', 'Optimization like services add, pics and proper response on reviews', 1, 3000.00, 3000.00, 10, 2700),
(96, 39, 'Website', 'Dynamic Website with ui/ux design and 1 year maintenance', 1, 50000.00, 50000.00, 15, 42500),
(97, 39, 'Social Media Management', 'Social media optimization , fb,insta,pinterest and linkedin', 1, 6000.00, 6000.00, 0, 6000),
(98, 39, 'Designing Set', 'Visiting cards,PPT,Pamphelts,brousers and required designs', 1, 20000.00, 20000.00, 0, 20000),
(99, 39, 'Training', 'Employees hiring and training', 1, 70000.00, 70000.00, 10, 63000),
(100, 40, 'Google My Business', '', 1, 3000.00, 3000.00, 20, 2400),
(101, 40, 'Website', 'Dynamic website with ui/ux with 1 year militance', 1, 40000.00, 40000.00, 10, 36000),
(102, 40, 'Designing Set', 'Animation videos, required designs for 1st phase', 1, 20000.00, 20000.00, 10, 18000),
(103, 40, 'Social Media Management', 'optimization and will create if any ', 1, 6000.00, 6000.00, 10, 5400),
(104, 41, 'Website', 'phase 2 balance', 1, 5000.00, 5000.00, 0, 5000),
(105, 41, 'Designing Set', 'Animation videos , Pamphlets, browsers,visiting cards and more', 1, 20000.00, 20000.00, 15, 17000),
(106, 42, 'Log-Design', '', 12, 0.00, 0.00, 0, 0),
(107, 43, 'Log-Design', '', 12, 0.00, 0.00, 0, 0),
(108, 44, 'Google My Business', 'dfgdsfg', 2, 50.00, 100.00, 5, 95),
(109, 44, 'Log-Design', 'fdgfdg', 5, 50.00, 250.00, 5, 238),
(110, 45, 'fastile', '', 100, 50.00, 5000.00, 10, 4500),
(111, 46, 'offline marketing', 'Visiting cards, Pamphlets, Brousers, Hoardings and etc', 1, 700000.00, 700000.00, 15, 595000),
(112, 46, 'online marketing', 'Website, SEO, Social media management and etc', 1, 300000.00, 300000.00, 10, 270000),
(113, 46, 'Branding', 'Ideas about marketing', 1, 150000.00, 150000.00, 5, 142500),
(114, 47, 'online marketing', '8 youtube videos, 8 reels and 4 blogs in linkedin ', 6, 20000.00, 120000.00, 25, 90000),
(115, 48, 'Pamphlet', '1/8 multi color both side and ', 25000, 0.80, 20000.00, 0, 20000),
(116, 48, 'offline marketing', 'd/b on news papers ,\r\n29 points * 450 \r\n11 points 500 ', 40, 464.00, 18560.00, 0, 18560),
(117, 49, 'Designing Set', 'customized font', 1, 30000.00, 30000.00, 20, 24000),
(118, 50, 'Image Designing', '', 30, 600.00, 18000.00, 5, 17100),
(119, 50, 'Reels', 'GIF/Reels', 30, 800.00, 24000.00, 5, 22800),
(120, 50, 'Video Creation', '', 4, 3000.00, 12000.00, 5, 11400),
(121, 50, 'Video Editing', '', 20, 1000.00, 20000.00, 0, 20000),
(122, 50, 'Social Media Management', 'Fb,insta,youtube and Linkedin', 2, 15000.00, 30000.00, 10, 27000),
(123, 50, 'Google My Business', '', 1, 2000.00, 2000.00, 0, 2000),
(124, 51, 'Image Designing', 'test', 50, 50.00, 2500.00, 0, 2500),
(125, 51, 'Calenders', 'test', 20, 20.00, 400.00, 0, 400),
(126, 52, 'Image Designing', 'test', 50, 50.00, 2500.00, 0, 2500),
(127, 52, 'Pamphlet', 'test', 50, 30.00, 1500.00, 5, 1425),
(128, 53, 'Fb/insta ads', '', 90, 900.00, 81000.00, 0, 81000),
(129, 53, 'Youtube ad', '', 90, 1700.00, 153000.00, 0, 153000),
(130, 53, 'Service charge', '', 90, 100.00, 9000.00, 0, 9000),
(131, 54, 'Website', 'E Commerce with UI/UX ', 1, 150000.00, 150000.00, 0, 150000),
(132, 55, 'Website', 'Dynamic Wordpress website', 1, 25000.00, 25000.00, 0, 25000),
(133, 56, 'Social Media Management', '', 1, 7000.00, 7000.00, 0, 7000),
(134, 57, 'Video Editing', '', 1, 85000.00, 85000.00, 0, 85000),
(135, 58, 'Image Designing', '26 stickers 2*3', 26, 150.00, 3900.00, 0, 3900),
(136, 58, 'flex', 'eco vinyl printing', 156, 28.00, 4368.00, 0, 4368),
(137, 59, 'Video Editing', '', 1, 80000.00, 80000.00, 0, 80000),
(138, 60, 'Video Creation', '', 10, 21000.00, 210000.00, 0, 210000),
(139, 61, 'Flex', '25*25(Pawara)', 625, 12.00, 7500.00, 0, 7500),
(140, 61, 'Flex', '40*25(Ysr bridge) - 2 times \r\n2000 feet', 2000, 12.00, 24000.00, 0, 24000),
(141, 61, 'Flex', '12*8 (gandhi nagar)', 96, 12.00, 1152.00, 100, 0),
(142, 61, 'Flex', '20*10 (Dairy farm) vinyal', 200, 18.00, 3600.00, 0, 3600),
(143, 61, 'Flex', '20*10 (Kusuma satya)', 200, 12.00, 2400.00, 0, 2400),
(144, 61, 'Flex', '20*10(Zila parishath)', 200, 12.00, 2400.00, 0, 2400),
(145, 61, 'Flex', '20*10(Vinayaka cafe)', 200, 12.00, 2400.00, 0, 2400),
(146, 61, 'Flex', '20*10 (Beach road)', 200, 12.00, 2400.00, 0, 2400),
(147, 61, 'Hosting', '3 websites', 3, 1820.00, 5460.00, 0, 5460),
(148, 61, 'Digital marketing', 'Bal Amount ', 1, 115000.00, 115000.00, 0, 115000),
(149, 61, 'Sticking', 'total feet 1000 feet', 1000, 5.00, 5000.00, 0, 5000),
(150, 61, 'Website', '3 - domains', 1, 4160.00, 4160.00, 0, 4160),
(151, 62, 'Hoarding', 'D-Mart Water tank (10*20)\r\n1YEAR', 1, 55000.00, 55000.00, 0, 55000),
(152, 62, 'SRMT LED Screen', '1 moth', 1, 15000.00, 15000.00, 0, 15000),
(153, 62, 'SRMT LED Screen', 'food court - 1 MONTH', 1, 12000.00, 12000.00, 0, 12000),
(154, 62, 'Theater Ads ', 'Anadh one screen - 1month', 1, 10000.00, 10000.00, 0, 10000),
(155, 63, 'Hoarding', 'D-Mart Water tank \r\n10*20', 1, 55000.00, 55000.00, 0, 55000),
(156, 63, 'SRMT LED Screen', 'Food Court - 30sec ad', 1, 12000.00, 12000.00, 0, 12000),
(157, 63, 'Theater Ads ', 'Anandh - single screen - \r\n10 sec Ad', 1, 10000.00, 10000.00, 0, 10000),
(158, 64, 'Social Media Management', 'Fb, Insta and You tube', 6, 9500.00, 57000.00, 0, 57000),
(159, 65, 'Website', '', 1, 60000.00, 60000.00, 18, 49200),
(160, 66, 'Domain', 'varmagroup.in , varmagroup.net', 1, 9000.00, 9000.00, 0, 9000),
(161, 66, 'Hosting', '5 years space for hosting\r\n516bandwidth, 2 gb space', 5, 2700.00, 13500.00, 15, 11475),
(162, 66, 'SSL', 'Safe and secure for website', 5, 1600.00, 8000.00, 25, 6000),
(163, 66, 'Website', 'frontend html, css and react', 1, 25000.00, 25000.00, 10, 22500),
(164, 66, 'Website maintenance', '1 year minor maintained ', 1, 10000.00, 10000.00, 100, 0),
(165, 66, 'customized back panels for website images edit', '3 Backend pannels', 3, 12000.00, 36000.00, 15, 30600),
(166, 66, 'Social Media Management', 'fb, instagram and youtube page creation', 1, 3000.00, 3000.00, 0, 3000),
(167, 66, 'Google My Business', 'Adding details  of website, google analytics and contact number', 1, 5000.00, 5000.00, 0, 5000),
(168, 67, 'Auto Stickers', 'Premium quality with laminated', 200, 135.00, 27000.00, 0, 27000),
(169, 68, 'Branding', '1 year total digital works', 1, 175000.00, 175000.00, 0, 175000),
(170, 69, 'Log-Design', 'sample', 0, 0.00, 0.00, 0, 0),
(171, 70, 'Hoarding', '1 YEAR ', 1, 60000.00, 60000.00, 0, 60000),
(172, 71, 'Branding', '1 year total branding', 1, 240000.00, 240000.00, 0, 240000),
(173, 72, 'Website', '', 1, 39200.00, 39200.00, 26, 29008),
(174, 73, 'Website maintenance', '', 1, 10000.00, 10000.00, 10, 9000),
(175, 74, 'Video Editing', 'Youtube Videos and Thumbnails', 30, 834.00, 25020.00, 0, 25020),
(176, 75, 'Video Editing', 'Thumbnails , videos ,reels', 75, 1000.00, 75000.00, 6, 70500),
(177, 76, 'Log-Design', '', 2, 2.00, 0.00, 0, 0),
(178, 77, 'Log-Design', '', 3, 3.00, 9.00, 0, 9),
(179, 78, 'Log-Design', '', 1, 1.00, 1.00, 0, 1),
(180, 79, 'Log-Design', '', 10, 10.00, 100.00, 0, 100),
(181, 80, 'Log-Design', '', 12, 12.00, 144.00, 0, 144),
(183, 82, 'Log-Design', '11', 11, 11.00, 121.00, 0, 121),
(184, 83, 'Website', 'This is a customized dynamic website (non-eCommerce).', 1, 20000.00, 20000.00, 15, 17000),
(185, 83, 'Domain', 'brewsbiteskakinada.com', 1, 1300.00, 1300.00, 0, 1300),
(186, 83, 'Hosting', '', 1, 2200.00, 2200.00, 0, 2200),
(187, 83, 'Website maintenance', '1 year ', 1, 12000.00, 12000.00, 15, 10200),
(188, 83, 'Designing Set', 'Visiting card, Menu card, QR Code, Table Mat (paper)', 1, 6000.00, 6000.00, 5, 5700),
(189, 84, 'Log-Design', '', 22, 2.00, 44.00, 0, 44),
(190, 85, 'Log-Design', '', 33, 33.00, 1089.00, 0, 1089),
(191, 86, 'Log-Design', '', 33, 33.00, 1089.00, 0, 1089),
(192, 87, 'Log-Design', '', 33, 33.00, 1089.00, 0, 1089);

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
(17, 'Diary'),
(21, 'Demo tents'),
(22, 'Social Media Influencers '),
(23, 'Fb/insta ads'),
(24, 'browser'),
(25, 'Stickers'),
(26, 'Branding'),
(27, 'Training'),
(28, 'Designing Set'),
(29, 'Visiting charge'),
(30, 'flex'),
(31, 'fastile'),
(32, 'offline marketing'),
(33, 'online marketing'),
(34, 'Branding'),
(35, 'customized back panels for website images edit'),
(36, 'Hosting'),
(37, 'Website maintance'),
(38, 'Website maintenance'),
(39, 'Reels'),
(40, 'Youtube ad'),
(41, 'Service charge'),
(42, 'fb ad'),
(43, 'insta ad'),
(44, 'Wordpress Plugin'),
(45, 'Sticking'),
(46, 'Hoarding'),
(47, 'Theater Ads '),
(48, 'SRMT LED Screen'),
(49, 'Bhanugudi Junction'),
(50, 'Digital marketing'),
(51, 'Bulk Whattapp'),
(52, 'Domain'),
(53, 'SSL'),
(54, 'Login Panel'),
(55, 'Auto Stickers'),
(56, 'uytrhy'),
(57, 'Lighting Board'),
(58, 'Photo shoot'),
(59, 'Coupons'),
(60, 'Paper distb'),
(61, 'Man D/b at Particular Point'),
(62, 'Reel shoot by Camera '),
(63, 'Paid AD');

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
-- Indexes for table `lgtable`
--
ALTER TABLE `lgtable`
  ADD PRIMARY KEY (`Id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `expenditure_desc_tbl`
--
ALTER TABLE `expenditure_desc_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `expenditure_tbl`
--
ALTER TABLE `expenditure_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exp_name`
--
ALTER TABLE `exp_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exp_type`
--
ALTER TABLE `exp_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gst_no`
--
ALTER TABLE `gst_no`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `invoice_files`
--
ALTER TABLE `invoice_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `lgtable`
--
ALTER TABLE `lgtable`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quotation`
--
ALTER TABLE `quotation`
  MODIFY `Sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `quote_files`
--
ALTER TABLE `quote_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quservice`
--
ALTER TABLE `quservice`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `service_names`
--
ALTER TABLE `service_names`
  MODIFY `si_No` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `quote_files`
--
ALTER TABLE `quote_files`
  ADD CONSTRAINT `quote_files_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `quotation` (`Sid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
