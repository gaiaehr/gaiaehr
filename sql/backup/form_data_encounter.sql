-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 22, 2012 at 08:17 PM
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
-- Table structure for table `form_data_encounter`
--

CREATE TABLE IF NOT EXISTS `form_data_encounter` (
  `eid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Encounter ID',
  `pid` bigint(20) NOT NULL COMMENT 'Patient ID',
  `open_uid` bigint(20) NOT NULL COMMENT 'User ID who opened the encounter',
  `close_uid` bigint(20) DEFAULT NULL COMMENT 'User ID who Closed/Sign the encounter',
  `prov_uid` bigint(20) DEFAULT NULL COMMENT 'Provider User ID',
  `sup_uid` bigint(20) DEFAULT NULL COMMENT 'Supervisor User ID',
  `brief_description` varchar(255) DEFAULT NULL,
  `visit_category` varchar(255) DEFAULT NULL,
  `facility` varchar(255) DEFAULT NULL,
  `billing_facility` varchar(255) DEFAULT NULL,
  `sensitivity` varchar(255) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `close_date` datetime DEFAULT NULL,
  `onset_date` datetime DEFAULT NULL,
  `billing_stage` int(1) DEFAULT NULL COMMENT 'billing stage of this encounter',
  `review_immunizations` tinyint(1) NOT NULL DEFAULT '0',
  `review_allergies` tinyint(1) NOT NULL DEFAULT '0',
  `review_active_problems` tinyint(1) NOT NULL DEFAULT '0',
  `review_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `review_dental` tinyint(1) NOT NULL DEFAULT '0',
  `review_medications` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `form_data_encounter`
--

INSERT INTO `form_data_encounter` (`eid`, `pid`, `open_uid`, `close_uid`, `prov_uid`, `sup_uid`, `brief_description`, `visit_category`, `facility`, `billing_facility`, `sensitivity`, `start_date`, `close_date`, `onset_date`, `billing_stage`, `review_immunizations`, `review_allergies`, `review_active_problems`, `review_surgery`, `review_dental`, `review_medications`) VALUES
(1, 1, 85, 85, NULL, NULL, 'test', 'medium', 'medium', NULL, 'high', '2012-05-22 21:30:00', '2012-06-08 00:37:17', NULL, NULL, 0, 0, 0, 0, 0, 0),
(2, 2, 85, 85, NULL, NULL, 'el paciente tiene catarro', NULL, NULL, NULL, NULL, '2012-05-24 15:51:00', '2012-06-07 18:17:05', NULL, NULL, 0, 0, 0, 0, 0, 0),
(3, 5, 85, 85, NULL, NULL, 'El paciente dice tener catarro, dolor de cabeza, y vista borrosa', 'low', NULL, NULL, 'low', '2012-05-26 01:29:00', '2012-06-07 18:13:42', NULL, NULL, 0, 0, 0, 0, 0, 0),
(4, 5, 85, 85, NULL, NULL, 'test', NULL, NULL, NULL, NULL, '2012-06-08 02:41:00', '2012-06-08 16:55:57', NULL, NULL, 0, 0, 0, 0, 0, 0),
(5, 5, 85, 85, NULL, NULL, 'dolor de espalda', NULL, NULL, NULL, NULL, '2012-06-08 16:56:00', '2012-06-09 09:31:46', NULL, NULL, 0, 0, 0, 0, 0, 0),
(6, 8, 85, 85, NULL, NULL, 'paciente llega con malestar estomacal', 'high', 'medium', NULL, 'medium', '2012-06-08 23:16:00', '2012-06-08 23:41:23', '2012-06-04 01:15:00', NULL, 0, 0, 0, 0, 0, 0),
(7, 5, 85, 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-06-09 12:33:00', '2012-06-16 12:37:21', NULL, NULL, 0, 0, 0, 0, 0, 0),
(8, 12, 85, 85, NULL, NULL, 'Mareos y ganas frecuentes de ir al bano', 'low', '6', '6', 'low', '2012-06-09 13:34:00', '2012-06-09 13:47:40', NULL, NULL, 0, 0, 0, 0, 0, 0),
(9, 5, 85, 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-06-11 01:22:00', '2012-06-16 04:24:44', NULL, NULL, 0, 0, 0, 0, 0, 0),
(10, 2, 85, NULL, NULL, NULL, 'test', 'medium', '6', '6', 'medium', '2012-06-16 12:47:00', NULL, NULL, NULL, 1, 1, 1, 1, 1, 1),
(11, 12, 85, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2012-06-19 17:24:00', NULL, NULL, NULL, 1, 0, 1, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
