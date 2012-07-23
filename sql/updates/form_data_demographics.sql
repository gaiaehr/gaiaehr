-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 23, 2012 at 12:56 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

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
  `DOB` date DEFAULT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Table holds all the Demographics form data for all the patie' AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
