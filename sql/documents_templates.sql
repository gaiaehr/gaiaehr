-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 20, 2012 at 06:00 PM
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
  `template_type` varchar(255) DEFAULT NULL COMMENT '1= documents 2= headers and footers',
  `body` text,
  `date` datetime DEFAULT NULL,
  `created_by_uid` bigint(20) DEFAULT NULL,
  `update_by_uid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `documents_templates`
--

INSERT INTO `documents_templates` (`id`, `title`, `template_type`, `body`, `date`, `created_by_uid`, `update_by_uid`) VALUES
(1, 'Return to work', 'documenttemplate', 'Certificate to return to work<br><br>Patient Information:<br><br>Name: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp; Date: [CURRENT_DATE]<br>Address: [PATIENT_ADDRESS], [PATIENT_CITY] [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br><br>Has been under care of: [PHYSICIAN_LAST_NAME]<br>From: [FROM_DATE]&nbsp;&nbsp;&nbsp;&nbsp; To:[TO_DATE]<br><br>Return to work on: [RETURN_DATE]<br><br>[Body]<br><br>For more information, please call<br>[CLINIC_PHONE_NUMBER]<br><br><br><br>', '2012-05-14 12:34:51', NULL, 85),
(2, 'Return to school', 'documenttemplate', 'Certificate to return to school<br><br>Patient Information:<br><br>Name: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp; Date: [CURRENT_DATE]<br>Address: [PATIENT_ADDRESS], [PATIENT_CITY] [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br><br>Has been under care of: [PHYSICIAN_LAST_NAME]<br>From: [FROM_DATE]&nbsp;&nbsp;&nbsp;&nbsp; To:[TO_DATE]<br><br>Return to school on: [RETURN_DATE]<br><br>[Body]<br><br>For more information, please call<br>[CLINIC_PHONE_NUMBER]<br><br>&nbsp;<br>', '2012-05-14 12:36:08', NULL, 85),
(3, 'Encounter Report', 'defaulttemplate', 'Name: [PATIENT_FULL_NAME]&nbsp; ID:[PATIENT_ID]<br>Encounter Date: [ENCOUNTER_DATE]<br>Birth date: [PATIENT_BIRTHDATE]<br>Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_PICTURE]<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>Vitals<br>Height:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_HEIGHT]<br>Weight:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_WEIGHT]<br>Temperature:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_TEMPERATURE]<br>Blood Pressure:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_BLOOD_PREASURE]<br>Respiration:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_RESPIRATORY_RATE]<br>Pulse:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_PULSE]<br>BMI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_BMI]<br>Subjective:<br>[ENCOUNTER_SUBJECTIVE]<br>Objective:<br>[ENCOUNTER_OBJECTIVE]<br>Assessment:<br>[ENCOUNTER_ASSESMENT]<br>Plan:<br>[ENCOUNTER_PLAN]<br>Medications:<br>[ENCOUNTER_MEDICATIONS]<br>Follow Up:<br>[ENCOUNTER_FOLLOW_UP]<br>Services:<br>[ENCOUNTER_SERVICES]<br>Signature:<br>[ENCOUNTER_SIGNATURE]<br><br><br>', '2012-05-14 12:46:22', NULL, 85),
(4, 'Lab Orders', 'defaulttemplate', 'Patient Information:<br>Name: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; ID: [PATIENT_ID]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp; &nbsp;<br>Address: [PATIENT_ADDRESS], [PATIENT_CITY] [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br>Date: [CURRENT_DATE]<br><br>Lab Orders<br><br>[LABS_LIST]<br>Provider: [CURRENT_USER_FULL_NAME]<br><br>', '2012-05-14 12:47:12', NULL, 85),
(5, 'Rx  Orders', 'defaulttemplate', 'Patient Information:<br>Name: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; ID: [PATIENT_ID]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp; <br>Address: [PATIENT_ADDRESS], [PATIENT_CITY] [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br>Date: [CURRENT_DATE]<br><br>Rx Order<br><br>[MEDICATIONS_LIST]<br>Provider: [CURRENT_USER_FULL_NAME]<br><br><br>', '2012-05-14 12:48:41', NULL, 85),
(6, 'X-Ray Orders', 'defaulttemplate', 'Patient Information:<br>Name: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; ID: [PATIENT_ID]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp; <br>Address: [PATIENT_ADDRESS], [PATIENT_CITY] [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br>Date: [CURRENT_DATE]<br><br>Dx: [CURRENT_ENCOUNTER_ASSESMENT_CODE_LIST]<br>X-Rays Orders<br><br>[XRAYS_LIST]<br><br>Provider: [CURRENT_USER_FULL_NAME]<br><br><br>', '2012-05-14 12:54:33', 85, 85),
(7, 'Doctor''s Note', 'documenttemplate', 'Doctor''s Note<br>Patient Information:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br><br>[PATIENT_PICTURE]<br><br>Name: [PATIENT_FULL_NAME]&nbsp;&nbsp;&nbsp;&nbsp; Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp; Date: [CURRENT_DATE]<br>Address: [PATIENT_ADDRESS], [PATIENT_CITY] [PATIENT_STATE]&nbsp; [PATIENT_COUNTRY]<br><br><br>[Body]<br><br>For more information, please call<br>[CLINIC_PHONE_NUMBER]<br><br><br>', '2012-05-29 13:47:22', 85, 85),
(8, 'Footer Classic', 'headerorfootertemplate', 'TTteawdeaD<br>', '2012-05-29 14:45:31', 85, 85),
(9, 'Header Classic', 'headerorfootertemplate', 'qweqweqwe<br>', '2012-05-29 14:46:53', 85, 85),
(10, 'Referrals', 'documenttemplate', NULL, '2012-06-06 10:25:43', 85, 85),
(11, 'testDOC', 'documenttemplate', 'Name: [PATIENT_FULL_NAME]&nbsp; ID:[PATIENT_ID]<br>Encounter Date: [ENCOUNTER_DATE_START]<br>Birth date: [PATIENT_BIRTHDATE]<br>Age: [PATIENT_AGE]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [PATIENT_PICTURE]<br>Vitals<br>Height:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_HEIGHT_IN]<br>Weight:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_WEIGHT_LBS]<br>Temperature:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_TEMP_FAHRENHEIT]<br>Blood Pressure:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_BP_SYSTOLIC]/[ENCOUNTER_BP_DIASTOLIC]<br>Respiration:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_RESPIRATION]<br>Pulse:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_PULSE]<br>BMI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_BMI]<br>BMI Status:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; [ENCOUNTER_BMI_STATUS]<br>Subjective:<br>[ENCOUNTER_SUBJECTIVE]<br>Objective:<br>[ENCOUNTER_OBJECTIVE]<br>Assessment:<br>[ENCOUNTER_ASSESMENT]<br>Plan:<br>[ENCOUNTER_PLAN]<br><br><br>', '2012-07-04 19:41:23', 85, 85);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
