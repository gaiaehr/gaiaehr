-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2012 at 05:34 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mitosdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `preventive_care_inactive_patient`
--

DROP TABLE IF EXISTS `preventive_care_inactive_patient`;
CREATE TABLE IF NOT EXISTS `preventive_care_inactive_patient` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `preventive_care_id` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `dismiss` varchar(255) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `preventive_care_inactive_patient`
--

INSERT INTO `preventive_care_inactive_patient` (`id`, `pid`, `eid`, `preventive_care_id`, `uid`, `dismiss`, `reason`, `date`, `observation`) VALUES
(1, 1, 14, 40, 85, '1', 'asdfasdfasdf', '2012-06-07', 'asfasdfasdf'),
(2, 2, 14, 20, 85, '1', 'asdfasdf', '2012-07-10', 'adsfasdf');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
