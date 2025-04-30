-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 30, 2025 at 08:31 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `crimelink`
--
CREATE DATABASE `crimelink` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `crimelink`;

-- --------------------------------------------------------

--
-- Table structure for table `evidence`
--

CREATE TABLE IF NOT EXISTS `evidence` (
  `photo` varchar(225) DEFAULT NULL,
  `document` varchar(225) DEFAULT NULL,
  `reportid` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `evidence`
--

INSERT INTO `evidence` (`photo`, `document`, `reportid`) VALUES
('1744026691.png', '1744026691.', ' Report_b53c3cd5'),
('1745945745.png', '1745945745.pdf', ' Report_53c6ae26'),
('1745989317.png', '1745989317.pdf', ' Report_2551b0ce');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
  `reportid` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `crimetype` varchar(100) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `policeid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`reportid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`reportid`, `date`, `time`, `description`, `location`, `crimetype`, `userid`, `policeid`) VALUES
('Report_2551b0ce', '2025-04-02', '14:42:00', 'A suspicious person was seen trying to lure school children near the main gate of St. Maryâ€™s School.\r\n', 'Jaipur, Rajasthan', 'Women & Children Crime', 'UID_df163a1a', NULL),
('Report_53c6ae26', '2025-04-03', '10:49:00', 'Unauthorized withdrawal of â‚¹25,000 from a user''s bank account without OTP.\r\n', 'Pune, Maharashtra', 'Financial Fraud', 'UID_41bc8c0a', NULL),
('Report_867ab664', '2024-04-02', '13:25:00', 'A suspicious person was seen trying to lure school children near the main gate of St. Maryâ€™s School.', 'Jaipur, Rajasthan', 'Women & Children Crime', 'UID_df163a1a', NULL),
('Report_b61b635c', '2025-04-15', '10:38:00', ' Unauthorized withdrawal of â‚¹25,000 from a user''s bank account without OTP.\r\n', 'jaipur', 'Cyber Crime', 'UID_41bc8c0a', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `statusid` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('Pending','In Progress','Resolved','Case Reported','Complaint Verified','Evidence Collected','Delegated to Further Authority') NOT NULL,
  `reportid` varchar(225) NOT NULL,
  PRIMARY KEY (`statusid`),
  KEY `reportid` (`reportid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusid`, `status`, `reportid`) VALUES
(5, 'Complaint Verified', 'Report_2551b0ce'),
(6, 'Evidence Collected', 'Report_53c6ae26'),
(9, 'Case Reported', 'Report_867ab664'),
(10, 'Case Reported', 'Report_b61b635c');

-- --------------------------------------------------------

--
-- Table structure for table `user1`
--

CREATE TABLE IF NOT EXISTS `user1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('police','admin','citizen') NOT NULL,
  `userid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `unique_id` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `user1`
--

INSERT INTO `user1` (`id`, `name`, `email`, `address`, `phone`, `password`, `role`, `userid`) VALUES
(10, 'Asha Singh', 'asha@gmail.com', 'Jaipur, Rajasthan', '1234567891', 'asha@123', 'citizen', 'UID_df163a1a'),
(11, 'Naina kumari', 'naina123@gmail.com', 'Pune, Maharashtra\r\n', '225687355', 'naina@123', 'citizen', 'UID_41bc8c0a'),
(12, 'Himashi Pandey', 'himashi@gmail.com', 'Newai', '123456789', 'himashi@gmail.com', 'police', 'UID_40bf4e76'),
(13, 'gita', 'gita@gmail.com', 'new delhi', '1111111111', 'gita@123', 'admin', 'UID_cfce5c2c'),
(14, 'Pooja Tripathi', 'pooja@123', ' Bengaluru, Karnataka\r\n', '1111111111', 'pooja@123', 'citizen', 'UID_132e3dd3');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user1` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `status`
--
ALTER TABLE `status`
  ADD CONSTRAINT `status_ibfk_1` FOREIGN KEY (`reportid`) REFERENCES `report` (`reportid`) ON DELETE CASCADE;
