-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 21, 2012 at 03:49 AM
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
-- Table structure for table `documents_templates`
--

DROP TABLE IF EXISTS `documents_templates`;
CREATE TABLE IF NOT EXISTS `documents_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `body` text,
  `date` datetime DEFAULT NULL,
  `created_by_uid` bigint(20) DEFAULT NULL,
  `update_by_uid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `documents_templates`
--

INSERT INTO `documents_templates` (`id`, `title`, `body`, `date`, `created_by_uid`, `update_by_uid`) VALUES
(1, 'Return to work', '<div align="center">[HEADER]<br>\n[LINE]<br><br>\n<font size="4"><b>Certificate to return to work</b></font><br>\n  <br>\n  <div align="left"><b>Patient Information:</b><br>\n    <br>\nName: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp; Date: [CURRENT_DATE]<br>\nAddress: [PATIENT_ADDRESS], [PATIENT_CITY]  [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br><br>Has been under care of: [PHYSICIAN_LAST_NAME]<br>From: [FROM_DATE]&nbsp;&nbsp;&nbsp;&nbsp; To:[TO_DATE]<br><br>Return to work on: [RETURN_DATE]<br><br>[Body]<br><br>For more information, please call<br>[CLINIC_PHONE_NUMBER]<br><br></div>\n  <br>\n[LINE]<br>\n[FOOTER]<br>\n</div>', '2012-05-14 12:34:51', NULL, 85),
(2, 'Return to school', '<div align="center">[HEADER]<br>\n[LINE]<br><br>\n<font size="4"><b>Certificate to return to school</b></font><br>\n  <br>\n  <div align="left"><b>Patient Information:</b><br>\n    <br>\nName: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp; Date: [CURRENT_DATE]<br>\nAddress: [PATIENT_ADDRESS], [PATIENT_CITY]  [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br><br>Has been under care of: [PHYSICIAN_LAST_NAME]<br>From: [FROM_DATE]&nbsp;&nbsp;&nbsp;&nbsp; To:[TO_DATE]<br><br>Return to school on: [RETURN_DATE]<br><br>[Body]<br><br>For more information, please call<br>[CLINIC_PHONE_NUMBER]<br><br></div>\n  <br>\n[LINE]<br>\n[FOOTER]<br>\n</div>', '2012-05-14 12:36:08', NULL, 85),
(3, 'Encounter Report', '<div align="left"><div style="text-align: center;">[HEADER]<br>\n[LINE]</div><u>Name: [PATIENT_FULL_NAME]&nbsp; ID:[PATIENT_ID]</u><br>Encounter Date: [ENCOUNTER_DATE]<br>Birth date: [PATIENT_BIRTHDATE]<br>Age: [PATIENT_AGE]<br><div style="text-align: right;"> [PATIENT_PICTURE]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div><b><u>Vitals</u></b><br>Height:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; [PATIENT_HEIGHT]<br>Weight:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; [PATIENT_WEIGHT]<br>Temperature:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_TEMPERATURE]<br>Blood Pressure:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_BLOOD_PREASURE]<br>Respiration:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; [PATIENT_RESPIRATORY_RATE]<br>Pulse:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_PULSE]<br>BMI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_BMI]<br><div style="text-align: center;">[LINE]<br><div style="text-align: left;"><font style="font-weight: bold;" size="3">Subjective:</font><br>[ENCOUNTER_SUBJECTIVE]<br><font size="3"><span style="font-weight: bold;">Objective:</span></font><br>[ENCOUNTER_OBJECTIVE]<br><font size="3"><span style="font-weight: bold;">Assessment:</span></font><br>[ENCOUNTER_ASSESMENT]<br><font size="3"><span style="font-weight: bold;">Plan:</span></font><br>[ENCOUNTER_PLAN]<br><font size="3"><span style="font-weight: bold;">Medications:</span></font><br>[ENCOUNTER_MEDICATIONS]<br><font size="3"><span style="font-weight: bold;">Follow Up:</span></font><br>[ENCOUNTER_FOLLOW_UP]<br><font size="3"><span style="font-weight: bold;">Services:</span></font><br>[ENCOUNTER_SERVICES]<br><font size="3"><span style="font-weight: bold;">Signature:</span></font><br>[ENCOUNTER_SIGNATURE]<br></div></div><div style="text-align: center;"><br>\n[LINE]<br>[FOOTER]<br></div></div>', '2012-05-14 12:46:22', NULL, 85),
(4, 'Lab Orders', '<b>Patient Information:</b><br>\nName: [PATIENT_FULL_NAME] &nbsp; &nbsp; ID: [PATIENT_ID] &nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp; <br>\nAddress: [PATIENT_ADDRESS], [PATIENT_CITY]  [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br>Date: [CURRENT_DATE]<br><br><br>Dx: [CURRENT_ENCOUNTER_ASSESMENT_CODE_LIST]<br><br><font size="4"><span style="font-weight: bold;">Lab Orders</span></font><br><br>[LABS]<br>[LABS_LIST]<br>', '2012-05-14 12:47:12', NULL, 85),
(5, 'Rx  Orders', '<b>Patient Information:</b><br>\nName: [PATIENT_FULL_NAME] &nbsp; &nbsp; ID: [PATIENT_ID] &nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp; <br>\nAddress: [PATIENT_ADDRESS], [PATIENT_CITY]  [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br>Date: [CURRENT_DATE]<br><br><font size="4"><span style="font-weight: bold;"><br>Rx<br></span></font><br>[MEDICATIONS_LIST]<br><span style="font-weight: bold;"><font size="6"><br><br></font></span>', '2012-05-14 12:48:41', NULL, 85),
(6, 'X-Ray Orders', '<b>Patient Information:</b><br>\nName: [PATIENT_FULL_NAME] &nbsp; &nbsp; ID: [PATIENT_ID] &nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp; <br>\nAddress: [PATIENT_ADDRESS], [PATIENT_CITY]  [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br>Date: [CURRENT_DATE]<br><br><br>Dx: [CURRENT_ENCOUNTER_ASSESMENT_CODE_LIST]<br><br><font size="4"><span style="font-weight: bold;">X-Rays Orders</span></font><br><br>[XRAYS_LIST]', '2012-05-14 12:54:33', 85, 85);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
