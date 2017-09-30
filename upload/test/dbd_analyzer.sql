-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017 年 8 月 18 日 16:49
-- サーバのバージョン： 5.6.20-log
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `dbd_analyzer`
--

CREATE TABLE IF NOT EXISTS `dbd_analyzer` (
  `id` int(20) NOT NULL,
  `Serial_Number` varchar(50) NOT NULL,
  `Valid_Sn` varchar(5) DEFAULT NULL,
  `Unique_Sn` tinyint(1) NOT NULL,
  `Case_Id` varchar(30) NOT NULL,
  `Subcase_Id` varchar(30) DEFAULT NULL,
  `Event_Id` varchar(30) DEFAULT NULL,
  `Unique_Subcase` tinyint(1) NOT NULL,
  `Subcase_Series` int(3) NOT NULL,
  `Xotc All` varchar(50) NOT NULL,
  `Country` varchar(30) NOT NULL,
  `Case_Title` varchar(200) NOT NULL,
  `Open_Date` datetime DEFAULT NULL,
  `Close_Date` datetime DEFAULT NULL,
  `Delivery_Alternative` varchar(30) DEFAULT NULL,
  `Product` varchar(20) NOT NULL,
  `Product_Description` varchar(100) DEFAULT NULL,
  `Owner_Work_Group` varchar(100) NOT NULL,
  `Part_Number` varchar(20) NOT NULL,
  `Part_Desc` varchar(100) NOT NULL,
  `X_Part_Usage` varchar(20) DEFAULT NULL,
  `Product_Line` varchar(5) NOT NULL,
  `Dataperiod` varchar(30) NOT NULL,
  `Pure_Delivery` varchar(30) DEFAULT NULL,
  `GCSS_Customer_Name` varchar(100) DEFAULT NULL,
  `Product_Name` varchar(100) DEFAULT NULL,
  `Title_Code` varchar(20) DEFAULT NULL,
  `Issue` varchar(50) DEFAULT NULL,
  `timing` varchar(30) DEFAULT NULL,
  `howoften` varchar(20) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dbd_analyzer`
--
ALTER TABLE `dbd_analyzer`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dbd_analyzer`
--
ALTER TABLE `dbd_analyzer`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
