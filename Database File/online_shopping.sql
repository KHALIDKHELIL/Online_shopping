-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 16, 2024 at 11:14 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_level` int NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '''no_image.jpg''',
  `status` int NOT NULL,
  `verification_token` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `user_level` (`user_level`),
  KEY `user_level_2` (`user_level`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `username`, `password`, `email`, `user_level`, `image`, `status`, `verification_token`, `last_login`) VALUES
(1, 'Khalid Khelil Abdurehman', 'admin24', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'khalidkhelil19@gmail.com', 3, '94ij592j1.jpg', 1, '', '2024-12-16 10:47:15'),
(2, 'brbsfse', 'Admin2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'khalidkhelil20@gmail.com', 1, '0uni6fv72.jpg', 1, '', '2024-12-16 11:12:07'),
(3, 'Khalid Khelil Abdurehman', 'Adminnn', '43814346e21444aaf4f70841bf7ed5ae93f55a9d', 'yordanosteferi7@gmail.com', 1, '\'no_image.jpg\'', 1, '112f8dbee6ff0a3733d807803a506b59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cartid` int NOT NULL AUTO_INCREMENT,
  `productid` int NOT NULL,
  `Customerid` int NOT NULL,
  `Quantity` int NOT NULL,
  `Color` varchar(25) DEFAULT NULL,
  `Size` varchar(25) DEFAULT NULL,
  `carttimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cartid`),
  KEY `productid` (`productid`),
  KEY `userid` (`Customerid`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartid`, `productid`, `Customerid`, `Quantity`, `Color`, `Size`, `carttimestamp`) VALUES
(106, 7, 6, 2, 'Red', 'M', '2024-12-16 10:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `categoryname` varchar(25) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `categoryname` (`categoryname`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Id`, `categoryname`) VALUES
(23, 'Car'),
(1, 'Clothes'),
(2, 'Electronics'),
(71, 'Motors'),
(74, 'Shoe');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `userFname` varchar(25) NOT NULL,
  `userLname` varchar(25) NOT NULL,
  `userEmail` varchar(50) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userCode` varchar(255) NOT NULL,
  `verified` int NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`userid`, `userFname`, `userLname`, `userEmail`, `userPassword`, `userCode`, `verified`) VALUES
(1, 'fasil', 'Teferi', 'fasatefe@gmail.com', 'fasil', '', 0),
(2, 'Jordi', 'Teferi', 'jordisavage@gmail.com', 'jordi', '', 0),
(3, 'Jovani', 'Teferi', 'jovani@gmail.com', '1212', '', 0),
(4, 'joyee', 'tefe', 'Joyee29@gmail.com', '1515', '', 0),
(5, 'Jordi', 'Teferi', 'yordanosteferi7@gmail.com', '$2y$10$KCTbIoljvKz8LATjIhdYkOS/Fo7JBYQwrGLioLRvTJX9BxY.vz3gi', 'd8663a32326d4d2089f3bc372d9a7a11', 1),
(6, 'Khalid', 'Abdurehman', 'khalidkhelil19@gmail.com', '$2y$10$3ZP8vSMoy6chk8Bw3LMmjebL7QcS72ewEEtKtnpUObPyOVTnruQCC', '8a9738ccfb3a542af95b5168cbbf7cd6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customorder`
--

DROP TABLE IF EXISTS `customorder`;
CREATE TABLE IF NOT EXISTS `customorder` (
  `OrderId` int NOT NULL AUTO_INCREMENT,
  `userid` int NOT NULL,
  `city` varchar(25) NOT NULL,
  `Kebele` varchar(11) NOT NULL,
  `country` varchar(50) NOT NULL,
  `states` varchar(15) NOT NULL,
  `Payment_Method` varchar(50) NOT NULL,
  `Order_Status` varchar(25) NOT NULL,
  `Phone_Number` int NOT NULL,
  PRIMARY KEY (`OrderId`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customorder`
--

INSERT INTO `customorder` (`OrderId`, `userid`, `city`, `Kebele`, `country`, `states`, `Payment_Method`, `Order_Status`, `Phone_Number`) VALUES
(8, 1, 'hawasa', '01', 'Ethiopia', 'Sidama', 'cash on delivery', '', 910269050),
(9, 1, 'Adama', 'meskel squa', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(10, 1, 'Adama', 'meskel squa', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(11, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(12, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(13, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(14, 1, 'hawasa', 'meskel squa', 'Ethiopia', 'welayta', 'cash on delivery', '', 910269050),
(15, 1, 'Adama', '01', 'Ethiopia', 'Tigray', 'paypal', '', 910269050),
(16, 1, 'Adama', '01', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(17, 1, 'Adama', '01', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(18, 1, 'Adama', '01', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(19, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Sidama', 'paypal', '', 910269050),
(20, 1, 'Adama', '01', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(21, 1, 'Adama', 'meskel squa', 'Ethiopia', 'oromia', 'cash on delivery', '', 910269050),
(22, 1, 'Adama', '01', 'Ethiopia', 'Tigray', 'cash on delivery', '', 910269050),
(23, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Sidama', 'cash on delivery', '', 910269050),
(24, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Sidama', 'cash on delivery', '', 910269050),
(26, 1, 'adiss abeba', 'aywww', 'Ethiopia', 'kefa', 'cash on delivery', '', 910269050),
(27, 1, 'adiss abeba', 'aywww', 'Ethiopia', 'Sidama', 'paypal', '', 910269050),
(28, 1, 'adiss abeba', '05', 'Ethiopia', 'Tigray', 'paypal', '', 910269050),
(29, 1, 'Mekele', '3', 'Ethiopia', 'Tigray', 'paypal', '', 910269050),
(30, 1, 'hawasa', 'fikr', 'Ethiopia', 'Sidama', 'paypal', '', 910269050),
(33, 1, 'Adama', '22', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(34, 1, 'adiss abeba', '09', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(35, 1, 'adiss abeba', '09', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(36, 1, 'adiss abeba', '09', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(37, 1, 'adiss abeba', '09', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269050),
(38, 1, 'adiss abeba', '01', 'Ethiopia', 'shewa', 'cash on delivery', '', 910269055),
(39, 1, 'Adama', 'goro', 'Ethiopia', 'oromia', 'cash on delivery', '', 910269050),
(40, 1, 'Adama', 'meskel squa', 'Ethiopia', 'Sidama', 'cash on delivery', '', 910269050),
(41, 1, 'Adama', 'meskel squa', 'Ethiopia', 'Sidama', 'cash on delivery', '', 910269050),
(42, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'shewa', 'paypal', '', 910269058),
(43, 1, 'adiss abeba', '01', 'Ethiopia', 'shewa', 'paypal', '', 910269057),
(44, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'shewa', 'paypal', '', 910269050),
(45, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Amhara', 'cash on delivery', '', 910269050),
(46, 1, 'Bd', '01', 'Ethiopia', 'Amhara', 'cash on delivery', '', 910269050),
(47, 1, 'Gonder', '01', 'Ethiopia', 'Amhara', 'cash on delivery', 'pending', 910269050),
(48, 1, 'Adama', 'meskel squa', 'Ethiopia', 'oromia', 'cash on delivery', 'pending', 910269050),
(49, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Amhara', 'paypal', 'Delivered', 910269052),
(50, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Tigray', 'paypal', 'Delivered', 910269050),
(51, 1, 'adiss abeba', '01', 'Ethiopia', 'shewa', 'cash on delivery', 'Delivered', 910269050),
(52, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Adiss', 'cash on delivery', 'pending', 910269050),
(53, 1, 'adiss abeba', 'meskel squa', 'Ethiopia', 'Adiss', 'paypal', 'pending', 910269050),
(54, 2, 'adiss abeba', '01', 'Ethiopia', 'DC', 'paypal', 'pending', 910269050),
(55, 5, 'Option 2', '05', 'Option 1', 'Option 1', 'paypal', 'pending', 999557653),
(56, 5, 'Adama', '01', 'Ethiopia', 'Oromia', 'paypal', 'pending', 910269050),
(57, 5, 'Adama', '05', 'Ethiopia', 'Oromia', '', 'pending', 910269050),
(58, 5, 'Bahirdar', '22', 'Ethiopia', 'Amhara', '', 'pending', 910269050),
(59, 5, 'Mekele', '05', 'Ethiopia', 'Somali', '', 'pending', 910269050),
(60, 5, 'Adama', '01', 'Ethiopia', 'Oromia', 'cash on delivery', 'pending', 910269050),
(61, 5, 'Adama', '01', 'Ethiopia', 'Amhara', '', 'pending', 910269050),
(62, 5, 'Adama', '01', 'Ethiopia', 'Amhara', '', 'pending', 910269050),
(63, 5, 'Harrer', '01', 'Ethiopia', 'Hareri', 'paypal', 'pending', 910269050),
(64, 5, 'Bahirdar', '11', 'Ethiopia', 'Amhara', 'Chapa', 'pending', 910269050),
(65, 5, 'Mekele', 'aywww', 'Ethiopia', 'Tigray', 'Chapa', 'pending', 910269050),
(66, 5, 'Adama', '05', 'Ethiopia', 'Oromia', 'Chapa', 'pending', 910269050);

-- --------------------------------------------------------

--
-- Table structure for table `orderdetail`
--

DROP TABLE IF EXISTS `orderdetail`;
CREATE TABLE IF NOT EXISTS `orderdetail` (
  `OrderDId` int NOT NULL AUTO_INCREMENT,
  `Orderid` int NOT NULL,
  `Productid` int NOT NULL,
  `Product_Name` varchar(50) NOT NULL,
  `Quantity` int NOT NULL,
  `productPrice` int NOT NULL,
  PRIMARY KEY (`OrderDId`),
  KEY `Orderid` (`Orderid`),
  KEY `Productid` (`Productid`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orderdetail`
--

INSERT INTO `orderdetail` (`OrderDId`, `Orderid`, `Productid`, `Product_Name`, `Quantity`, `productPrice`) VALUES
(18, 8, 32, 'Iphone 14 (2) ', 2, 90000),
(19, 9, 32, 'Iphone 14 (2) ', 2, 90000),
(20, 10, 32, 'Iphone 14 (2) ', 2, 90000),
(21, 11, 32, 'Iphone 14 (2) ', 2, 90000),
(22, 12, 32, 'Iphone 14 (2) ', 2, 90000),
(23, 13, 32, 'Iphone 14 (2) ', 2, 90000),
(24, 14, 32, 'Iphone 14 (2) ', 2, 90000),
(25, 15, 32, 'Iphone 14 (2) ', 2, 90000),
(26, 16, 32, 'Iphone 14 (2) ', 2, 90000),
(27, 17, 32, 'Iphone 14 (2) ', 2, 90000),
(28, 18, 32, 'Iphone 14 (2) ', 2, 90000),
(29, 19, 32, 'Iphone 14 (2) ', 2, 90000),
(30, 20, 32, 'Iphone 14 (2) ', 2, 90000),
(31, 21, 32, 'Iphone 14 (2) ', 2, 90000),
(32, 22, 32, 'Iphone 14 (2) ', 2, 90000),
(33, 23, 32, 'Iphone 14 (2) ', 2, 90000),
(34, 24, 32, 'Iphone 14 (2) ', 2, 90000),
(36, 26, 32, 'Iphone 14 (2) ', 2, 90000),
(37, 27, 32, 'Iphone 14 (1) ', 1, 45000),
(38, 28, 32, 'Iphone 14 (1) ', 1, 45000),
(39, 29, 32, 'Iphone 14 (1) ', 1, 45000),
(40, 30, 32, 'Iphone 14 (1) ', 1, 45000),
(45, 33, 46, 'Gucci Hat (1) ', 1, 599),
(46, 34, 46, 'Gucci Hat (2) ', 2, 1198),
(47, 35, 46, 'Gucci Hat (2) ', 2, 1198),
(48, 36, 46, 'Gucci Hat (2) ', 2, 1198),
(49, 37, 46, 'Gucci Hat (2) ', 2, 1198),
(50, 38, 46, 'Gucci Hat (2) ', 2, 1198),
(51, 39, 46, 'Gucci Hat (2) ', 2, 1198),
(52, 40, 46, 'Gucci Hat (2) ', 2, 1198),
(53, 41, 46, 'Gucci Hat (2) ', 2, 1198),
(54, 42, 46, 'Gucci Hat (3) ', 3, 1797),
(55, 43, 46, 'Gucci Hat (3) ', 3, 1797),
(56, 44, 46, 'Gucci Hat (4) ', 4, 2396),
(57, 45, 43, 'hp Laptop (2) ', 2, 9332),
(58, 46, 43, 'hp Laptop (3) ', 3, 13998),
(59, 47, 43, 'hp Laptop (4) ', 4, 18664),
(60, 48, 43, 'hp Laptop (4) ', 4, 18664),
(62, 49, 41, 'Gucci T-Shirt (3) ', 3, 1500),
(64, 50, 41, 'Gucci T-Shirt (3) ', 3, 1500),
(65, 51, 46, 'Gucci Hat (5) ', 5, 2995),
(66, 52, 29, 'MHD Camera (1) ', 1, 80000),
(67, 52, 46, 'Gucci Hat (2) ', 2, 1198),
(68, 53, 29, 'MHD Camera (1) ', 1, 80000),
(69, 53, 46, 'Gucci Hat (2) ', 2, 1198),
(70, 54, 32, 'Iphone 14 (2) ', 2, 90000),
(71, 55, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(72, 55, 32, 'Iphone 14 (1) ', 1, 45000),
(73, 56, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(74, 56, 32, 'Iphone 14 (1) ', 1, 45000),
(75, 57, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(76, 57, 32, 'Iphone 14 (1) ', 1, 45000),
(77, 58, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(78, 58, 32, 'Iphone 14 (1) ', 1, 45000),
(79, 59, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(80, 59, 32, 'Iphone 14 (1) ', 1, 45000),
(81, 60, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(82, 60, 32, 'Iphone 14 (1) ', 1, 45000),
(83, 61, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(84, 61, 32, 'Iphone 14 (1) ', 1, 45000),
(85, 62, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(86, 62, 32, 'Iphone 14 (1) ', 1, 45000),
(87, 63, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(88, 63, 32, 'Iphone 14 (1) ', 1, 45000),
(89, 64, 41, 'Gucci T-Shirt (5) ', 5, 2500),
(90, 64, 32, 'Iphone 14 (1) ', 1, 45000),
(91, 64, 43, 'hp Laptop (2) ', 2, 9332),
(92, 65, 32, 'Iphone 14 (1) ', 1, 45000),
(93, 65, 43, 'hp Laptop (10) ', 10, 46660),
(94, 66, 29, 'MHD Camera (1) ', 1, 80000),
(95, 66, 41, 'Gucci T-Shirt (1) ', 1, 500);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `PayId` int NOT NULL AUTO_INCREMENT,
  `PUserid` int NOT NULL,
  `POrderId` int NOT NULL,
  `amount` double NOT NULL,
  `Currency` varchar(25) NOT NULL,
  `OrderStatus` varchar(100) NOT NULL,
  PRIMARY KEY (`PayId`),
  KEY `OrderId` (`POrderId`),
  KEY `OrderId_2` (`POrderId`),
  KEY `OrderId_3` (`POrderId`),
  KEY `POrderId` (`POrderId`),
  KEY `PUserid` (`PUserid`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PayId`, `PUserid`, `POrderId`, `amount`, `Currency`, `OrderStatus`) VALUES
(109, 1, 49, 46500, 'ETB', 'Well Payed'),
(110, 1, 50, 46500, 'ETB', 'Well Payed'),
(111, 1, 51, 2995, 'ETB', 'Well Payed'),
(112, 1, 52, 81198, 'ETB', 'UnPayed'),
(113, 1, 53, 81198, 'ETB', 'Well Payed'),
(114, 2, 54, 90000, 'ETB', 'Well Payed'),
(115, 5, 55, 47500, 'ETB', 'UnPayed'),
(116, 5, 56, 47500, 'ETB', 'UnPayed'),
(117, 5, 57, 47500, 'ETB', 'UnPayed'),
(118, 5, 58, 47500, 'ETB', 'UnPayed'),
(119, 5, 59, 47500, 'ETB', 'UnPayed'),
(120, 5, 60, 47500, 'ETB', 'UnPayed'),
(121, 5, 61, 47500, 'ETB', 'UnPayed'),
(122, 5, 62, 47500, 'ETB', 'UnPayed'),
(123, 5, 63, 47500, 'ETB', 'Well Payed'),
(124, 5, 64, 56832, 'ETB', 'Well Payed'),
(125, 5, 65, 91660, 'ETB', 'UnPayed'),
(126, 5, 66, 80500, 'ETB', 'Well Payed');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `ProductCatID` int NOT NULL,
  `Prodsubcatid` int NOT NULL,
  `Productname` varchar(25) NOT NULL,
  `productImage` blob NOT NULL,
  `ProductDetail` varchar(255) NOT NULL,
  `AdminID` int NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `PoductCatID` (`ProductCatID`),
  KEY `Prodsubcatid` (`Prodsubcatid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Id`, `ProductCatID`, `Prodsubcatid`, `Productname`, `productImage`, `ProductDetail`, `AdminID`) VALUES
(7, 2, 4, 'dell', '', 'fdgd', 1),
(8, 2, 4, 'hp', '', 'bgffyt', 1),
(9, 2, 4, 'clothvds', '', 'bdsvdx', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE IF NOT EXISTS `purchase` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `itemId` int NOT NULL,
  `supplierId` int NOT NULL,
  `quantity` varchar(25) NOT NULL,
  `unitPrice` decimal(25,2) NOT NULL,
  `totalPrice` decimal(25,2) NOT NULL,
  `stockId` int NOT NULL,
  `status` varchar(25) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `itemId` (`itemId`),
  KEY `supplierId` (`supplierId`),
  KEY `stockId` (`stockId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `purchase`
--

INSERT INTO `purchase` (`Id`, `itemId`, `supplierId`, `quantity`, `unitPrice`, `totalPrice`, `stockId`, `status`) VALUES
(3, 7, 1, '5', 43.00, 215.00, 0, 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `rating & review`
--

DROP TABLE IF EXISTS `rating & review`;
CREATE TABLE IF NOT EXISTS `rating & review` (
  `RId` int NOT NULL AUTO_INCREMENT,
  `userid` int NOT NULL,
  `Productid` int NOT NULL,
  `Rating` int NOT NULL,
  `Review` text NOT NULL,
  PRIMARY KEY (`RId`),
  KEY `Productid` (`Productid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

DROP TABLE IF EXISTS `stock`;
CREATE TABLE IF NOT EXISTS `stock` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `itemId` int NOT NULL,
  `quantity` int NOT NULL,
  `buyPrice` float(15,2) NOT NULL,
  `sellPrice` float(15,2) NOT NULL,
  `dateCreated` date NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `itemId` (`itemId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`Id`, `itemId`, `quantity`, `buyPrice`, `sellPrice`, `dateCreated`) VALUES
(1, 7, 5, 43.00, 50.00, '2024-12-16');

-- --------------------------------------------------------

--
-- Table structure for table `subcatgory`
--

DROP TABLE IF EXISTS `subcatgory`;
CREATE TABLE IF NOT EXISTS `subcatgory` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `Subcategoryname` varchar(25) NOT NULL,
  `categoryid` int DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Subcategoryname` (`Subcategoryname`),
  KEY `categoryid` (`categoryid`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subcatgory`
--

INSERT INTO `subcatgory` (`Id`, `Subcategoryname`, `categoryid`) VALUES
(1, 'Tshirt', 1),
(2, 'shoe', 1),
(3, 'Bag', 1),
(4, 'PC', 2),
(5, 'Tablets', 2),
(6, 'Mobile', 2),
(32, 'HAt', 1),
(44, 'Toyota', 23),
(45, 'Apach', 71);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers` (
  `Id` int NOT NULL AUTO_INCREMENT,
  `supplierName` varchar(50) NOT NULL,
  `supplierContact` varchar(25) NOT NULL,
  `status` varchar(25) NOT NULL,
  `supplierAddress` varchar(100) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`Id`, `supplierName`, `supplierContact`, `status`, `supplierAddress`) VALUES
(1, 'Test 1', '910101010', 'Active', 'Adama City'),
(2, 'Test 2', '920202020', 'Active', 'Mekele City'),
(5, 'erg', 'gre', 'gref', 'rtshtrsd');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
CREATE TABLE IF NOT EXISTS `user_groups` (
  `id` int NOT NULL AUTO_INCREMENT,
  `group_name` varchar(20) NOT NULL,
  `group_level` int NOT NULL,
  `group_status` int NOT NULL,
  PRIMARY KEY (`group_level`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`id`, `group_name`, `group_level`, `group_status`) VALUES
(1, 'Super Admin', 1, 1),
(5, 'Manager', 2, 1),
(6, 'Employee', 3, 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_user_groups_admin` FOREIGN KEY (`user_level`) REFERENCES `user_groups` (`group_level`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`Customerid`) REFERENCES `customer` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`productid`) REFERENCES `product` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customorder`
--
ALTER TABLE `customorder`
  ADD CONSTRAINT `customorder_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `customer` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orderdetail`
--
ALTER TABLE `orderdetail`
  ADD CONSTRAINT `orderdetail_ibfk_1` FOREIGN KEY (`Orderid`) REFERENCES `customorder` (`OrderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orderdetail_ibfk_2` FOREIGN KEY (`Productid`) REFERENCES `products` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`POrderId`) REFERENCES `customorder` (`OrderId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`PUserid`) REFERENCES `customer` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`ProductCatID`) REFERENCES `category` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`Prodsubcatid`) REFERENCES `subcatgory` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase`
--
ALTER TABLE `purchase`
  ADD CONSTRAINT `purchase_ibfk_1` FOREIGN KEY (`supplierId`) REFERENCES `suppliers` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `purchase_ibfk_2` FOREIGN KEY (`itemId`) REFERENCES `product` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rating & review`
--
ALTER TABLE `rating & review`
  ADD CONSTRAINT `rating & review_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `customer` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `rating & review_ibfk_3` FOREIGN KEY (`Productid`) REFERENCES `product` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`itemId`) REFERENCES `product` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcatgory`
--
ALTER TABLE `subcatgory`
  ADD CONSTRAINT `subcatgory_ibfk_1` FOREIGN KEY (`categoryid`) REFERENCES `category` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
