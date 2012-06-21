-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 21, 2012 at 04:27 PM
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
-- Table structure for table `form_data_demographics`
--

DROP TABLE IF EXISTS `form_data_demographics`;
CREATE TABLE IF NOT EXISTS `form_data_demographics` (
  `pid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Patient ID',
  `date_created` datetime NOT NULL COMMENT 'date form saved for the first time',
  `title` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `sex` varchar(255) DEFAULT NULL,
  `DOB` varchar(255) DEFAULT NULL,
  `marital_status` varchar(255) DEFAULT NULL,
  `SS` varchar(255) DEFAULT NULL,
  `pubpid` varchar(255) DEFAULT NULL,
  `drivers_license` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `home_phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `work_phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mothers_name` varchar(255) DEFAULT NULL,
  `guardians_name` varchar(255) DEFAULT NULL,
  `emer_contact` varchar(255) DEFAULT NULL,
  `emer_phone` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `pharmacy` varchar(255) DEFAULT NULL,
  `hipaa_notice` varchar(255) DEFAULT NULL,
  `allow_leave_msg` varchar(255) DEFAULT NULL,
  `allow_voice_msg` varchar(255) DEFAULT NULL,
  `allow_mail_msg` varchar(255) DEFAULT NULL,
  `allow_sms` varchar(255) DEFAULT NULL,
  `allow_email` varchar(255) DEFAULT NULL,
  `allow_immunization_registry` varchar(255) DEFAULT NULL,
  `allow_immunization_info_sharing` varchar(255) DEFAULT NULL,
  `allow_health_info_exchange` varchar(255) DEFAULT NULL,
  `allow_patient_web_portal` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `employer_name` varchar(255) DEFAULT NULL,
  `employer_address` varchar(255) DEFAULT NULL,
  `employer_city` varchar(255) DEFAULT NULL,
  `employer_state` varchar(255) DEFAULT NULL,
  `employer_country` varchar(255) DEFAULT NULL,
  `employer_postal_code` varchar(255) DEFAULT NULL,
  `primary_insurance_provider` text,
  `primary_plan_name` text,
  `primary_effective_date` text,
  `primary_subscriber_title` text,
  `primary_subscriber_fname` text,
  `primary_subscriber_mname` text,
  `primary_subscriber_lname` text,
  `primary_policy_number` text,
  `primary_group_number` text,
  `primary_subscriber_street` text,
  `primary_subscriber_city` text,
  `primary_subscriber_state` text,
  `primary_subscriber_country` text,
  `primary_subscriber_zip_code` text,
  `primary_subscriber_relationship` text,
  `primary_subscriber_phone` text,
  `primary_subscriber_employer` text,
  `primary_subscriber_employer_city` text,
  `primary_subscriber_employer_state` text,
  `primary_subscriber_employer_country` text,
  `primary_subscriber_employer_zip_code` text,
  `secondary_insurance_provider` text,
  `secondary_plan_name` text,
  `secondary_effective_date` text,
  `secondary_policy_number` text,
  `secondary_group_number` text,
  `secondary_subscriber_city` text,
  `secondary_subscriber_state` text,
  `secondary_subscriber_country` text,
  `secondary_subscriber_zip_code` text,
  `secondary_subscriber_title` text,
  `secondary_subscriber_fname` text,
  `secondary_subscriber_mname` text,
  `secondary_subscriber_lname` text,
  `secondary_subscriber_street` text,
  `secondary_subscriber_relationship` text,
  `secondary_subscriber_phone` text,
  `secondary_subscriber_employer` text,
  `secondary_subscriber_employer_city` text,
  `secondary_subscriber_employer_state` text,
  `secondary_subscriber_employer_country` text,
  `secondary_subscriber_employer_zip_code` text,
  `tertiary_insurance_provider` text,
  `tertiary_plan_name` text,
  `tertiary_effective_date` text,
  `tertiary_policy_number` text,
  `tertiary_group_number` text,
  `tertiary_subscriber_title` text,
  `tertiary_subscriber_fname` text,
  `tertiary_subscriber_mname` text,
  `tertiary_subscriber_lname` text,
  `tertiary_subscriber_street` text,
  `tertiary_subscriber_relationship` text,
  `tertiary_subscriber_state` text,
  `tertiary_subscriber_country` text,
  `tertiary_subscriber_phone` text,
  `tertiary_subscriber_city` text,
  `tertiary_subscriber_zip_code` text,
  `tertiary_subscriber_employer` text,
  `tertiary_subscriber_employer_city` text,
  `tertiary_subscriber_employer_state` text,
  `tertiary_subscriber_employer_street` text,
  `tertiary_subscriber_employer_country` text,
  `tertiary_subscriber__employer_zip_code` text,
  `primary_subscriber_employer_street` text,
  `secondary_subscriber_employer_street` text,
  `zipcode` text,
  `race` text,
  `ethnicity` text,
  `lenguage` text,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Table holds all the Demographics form data for all the patie' AUTO_INCREMENT=15 ;

--
-- Dumping data for table `form_data_demographics`
--

INSERT INTO `form_data_demographics` (`pid`, `date_created`, `title`, `fname`, `mname`, `lname`, `sex`, `DOB`, `marital_status`, `SS`, `pubpid`, `drivers_license`, `address`, `city`, `state`, `country`, `home_phone`, `mobile_phone`, `work_phone`, `email`, `mothers_name`, `guardians_name`, `emer_contact`, `emer_phone`, `provider`, `pharmacy`, `hipaa_notice`, `allow_leave_msg`, `allow_voice_msg`, `allow_mail_msg`, `allow_sms`, `allow_email`, `allow_immunization_registry`, `allow_immunization_info_sharing`, `allow_health_info_exchange`, `allow_patient_web_portal`, `occupation`, `employer_name`, `employer_address`, `employer_city`, `employer_state`, `employer_country`, `employer_postal_code`, `primary_insurance_provider`, `primary_plan_name`, `primary_effective_date`, `primary_subscriber_title`, `primary_subscriber_fname`, `primary_subscriber_mname`, `primary_subscriber_lname`, `primary_policy_number`, `primary_group_number`, `primary_subscriber_street`, `primary_subscriber_city`, `primary_subscriber_state`, `primary_subscriber_country`, `primary_subscriber_zip_code`, `primary_subscriber_relationship`, `primary_subscriber_phone`, `primary_subscriber_employer`, `primary_subscriber_employer_city`, `primary_subscriber_employer_state`, `primary_subscriber_employer_country`, `primary_subscriber_employer_zip_code`, `secondary_insurance_provider`, `secondary_plan_name`, `secondary_effective_date`, `secondary_policy_number`, `secondary_group_number`, `secondary_subscriber_city`, `secondary_subscriber_state`, `secondary_subscriber_country`, `secondary_subscriber_zip_code`, `secondary_subscriber_title`, `secondary_subscriber_fname`, `secondary_subscriber_mname`, `secondary_subscriber_lname`, `secondary_subscriber_street`, `secondary_subscriber_relationship`, `secondary_subscriber_phone`, `secondary_subscriber_employer`, `secondary_subscriber_employer_city`, `secondary_subscriber_employer_state`, `secondary_subscriber_employer_country`, `secondary_subscriber_employer_zip_code`, `tertiary_insurance_provider`, `tertiary_plan_name`, `tertiary_effective_date`, `tertiary_policy_number`, `tertiary_group_number`, `tertiary_subscriber_title`, `tertiary_subscriber_fname`, `tertiary_subscriber_mname`, `tertiary_subscriber_lname`, `tertiary_subscriber_street`, `tertiary_subscriber_relationship`, `tertiary_subscriber_state`, `tertiary_subscriber_country`, `tertiary_subscriber_phone`, `tertiary_subscriber_city`, `tertiary_subscriber_zip_code`, `tertiary_subscriber_employer`, `tertiary_subscriber_employer_city`, `tertiary_subscriber_employer_state`, `tertiary_subscriber_employer_street`, `tertiary_subscriber_employer_country`, `tertiary_subscriber__employer_zip_code`, `primary_subscriber_employer_street`, `secondary_subscriber_employer_street`, `zipcode`, `race`, `ethnicity`, `lenguage`) VALUES
(1, '2012-05-19 13:46:52', 'Mr.', 'Jose', 'H.', 'Figueroa', 'Male', '1989-05-31', 'single', '234-432-2345', '23455432', '234587543', 'University Gardens Clemson Street 234d', 'Carolina', 'AK', 'USA', '787-787-7878', '123-123-1234', '122-123-4567', 'test@hotmail.com', 'Georgina', NULL, 'Angel Pato', '123-123-1234', NULL, '1', NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', 'Desempleado', NULL, NULL, NULL, NULL, NULL, NULL, 'SSS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00987', NULL, NULL, NULL),
(2, '2012-05-19 13:48:14', 'Mr.', 'Omar', 'Ulises', 'Rodriguez', 'Male', '1989-05-31T00:00:00', 'divorced', '234-432-2345', '23455432', '234587543', 'University Gardens Clemson Street 234d', 'Carolina', 'AK', 'USA', '787-787-7878', '123-123-1234', '122-123-4567', 'test@hotmail.com', 'Georgina', NULL, 'Angel Pato', '123-123-1234', '87', '2', NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', 'Desempleado', NULL, NULL, NULL, NULL, NULL, NULL, 'SSS', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hispanic or Latino', 'cuban', 'german'),
(3, '0000-00-00 00:00:00', NULL, 'enano', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '2012-05-26 01:05:09', 'Mr.', 'Julio', 'Joel', 'Acosta', 'Male', '1988-11-26 00:00:00', 'single', '678-976-5679', NULL, '345672', 'University Gardens Calle Clemson 345c', 'Carolina', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, '2012-05-26 01:24:38', 'Mr.', 'Julio', 'Enrique', 'Acosta', 'Male', '1983-05-08', 'divorced', '897-234-6543', NULL, '9786532', 'Villa carolina Bloque 74 calle A 1224', 'San Juan', 'PR', 'USA', '787-777-7777', '787-888-8888', '787-999-9999', 'newemail@newemail.com', 'Fernanda Torres', 'Alexandra Uninov', 'Alexandra Uninov', '787-000-0000', '1', '0', '1', NULL, '1', '1', '1', '1', '1', '1', '1', '1', 'desempleado', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00983', 'white', 'puerto_rican', 'Spanish'),
(6, '2012-05-26 17:44:38', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, '2012-06-08 16:11:38', 'Mr.', 'German', 'A.', 'Draco', 'Male', '1968-06-13 00:00:00', 'single', '678-876-4567', NULL, '5676556', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'white', 'filipino', 'English'),
(8, '2012-06-08 23:08:23', 'Mr.', 'Jose', 'O', 'Ortega', 'Male', '1980-06-19 00:00:00', 'single', '123-123-1234', NULL, '65765765765', 'University Gardens Clemson Street 623 C', 'San Juan', 'PR', 'USA', '232-232-2323', '343-343-3434', '454-454-4545', 'JOrtega@gmail.com', 'Beatriz Ayala', NULL, 'Julio', '787-787-7878', NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00982', 'Hispanic or Latino', 'puerto_rican', 'Spanish'),
(9, '2012-06-09 07:52:19', 'Mr.', 'Julio', 'Enrique', 'Acosta', NULL, NULL, NULL, '576-980-9876', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, '2012-06-09 07:59:59', 'Mr.', 'Julio', 'Enrique', 'Acosta', NULL, NULL, NULL, '576-980-9876', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, '2012-06-09 08:06:20', 'Mr.', 'Gabriel', 'F.', 'Torres', 'Male', '1980-10-23 00:00:00', 'single', '523-13-5234', NULL, '5425631', 'University Gardens Clemson Street 623 C', 'San Juan', 'PR', 'USA', '232-232-2323', '454-454-4545', NULL, 'gtorres@gmail.com', 'Beatriz Ayala', NULL, 'Alberto Torres', '787-787-7878', '87', '2', '1', NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '00982', 'Hispanic or Latino', 'puerto_rican', 'Spanish'),
(12, '2012-06-09 13:33:31', 'Mr.', 'Gabriel', 'F.', 'Torres', 'Male', '1980-06-10 00:00:00', 'single', '523-13-5234', NULL, '5425631', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hispanic or Latino', 'puerto_rican', 'Spanish'),
(13, '2012-06-16 20:39:06', NULL, 'angel', NULL, 'pagan', 'Female', '1952-10-16 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Hispanic or Latino', NULL, NULL),
(14, '2012-06-20 19:25:17', NULL, 'test', 'test', 'test', 'Male', '2012-06-04 00:00:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0', '0', '0', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
