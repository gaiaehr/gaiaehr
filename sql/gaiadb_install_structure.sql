-- MySQL dump 10.13  Distrib 5.6.24, for Win32 (x86)
--
-- Host: localhost    Database: gaiadb
-- ------------------------------------------------------
-- Server version	5.5.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accvoucher`
--

DROP TABLE IF EXISTS `accvoucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accvoucher` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createUid` int(11) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `writeUid` int(11) DEFAULT NULL,
  `writeDate` datetime DEFAULT NULL,
  `dateDue` datetime DEFAULT NULL COMMENT 'Due Date',
  `date` datetime DEFAULT NULL COMMENT 'Date',
  `encounterId` int(11) DEFAULT NULL COMMENT 'Encounter',
  `accountId` int(11) DEFAULT NULL COMMENT 'Account',
  `journalId` int(11) DEFAULT NULL COMMENT 'Journal',
  `moveId` int(11) DEFAULT NULL COMMENT 'Account Entry',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Comment',
  `reference` varchar(255) DEFAULT NULL COMMENT 'Ref',
  `number` varchar(255) DEFAULT NULL COMMENT 'Number',
  `narration` varchar(255) DEFAULT NULL COMMENT 'Notes',
  `state` varchar(255) DEFAULT NULL COMMENT 'Status',
  `type` varchar(255) DEFAULT NULL COMMENT 'visit/product/office',
  `amount` float(10,2) DEFAULT '0.00' COMMENT 'Total Amount',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Voucher / Receipt';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acl_groups`
--

DROP TABLE IF EXISTS `acl_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acl_permissions`
--

DROP TABLE IF EXISTS `acl_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perm_key` varchar(255) DEFAULT NULL,
  `perm_name` varchar(255) DEFAULT NULL COMMENT 'Permission Name',
  `perm_cat` varchar(255) DEFAULT NULL COMMENT 'Permission Category',
  `seq` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permKey` (`perm_key`),
  KEY `IK_perm_key` (`perm_key`),
  KEY `IK_seq` (`seq`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acl_role_perms`
--

DROP TABLE IF EXISTS `acl_role_perms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_role_perms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `value` tinyint(1) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `perm_id` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL COMMENT 'Date Added',
  PRIMARY KEY (`id`),
  KEY `IK_role_id` (`role_id`),
  KEY `IK_perm_id` (`perm_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1126 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acl_roles`
--

DROP TABLE IF EXISTS `acl_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) DEFAULT NULL COMMENT 'Role Name',
  `seq` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_group_id` (`group_id`),
  KEY `IK_seq` (`seq`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acl_user_perms`
--

DROP TABLE IF EXISTS `acl_user_perms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_user_perms` (
  `id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL COMMENT 'Value',
  `add_date` datetime DEFAULT NULL COMMENT 'Date Added',
  `perm_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_perm_id` (`perm_id`),
  KEY `IK_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `acl_user_roles`
--

DROP TABLE IF EXISTS `acl_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acl_user_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `address_book`
--

DROP TABLE IF EXISTS `address_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address_book` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(10) DEFAULT NULL,
  `fname` varchar(80) DEFAULT NULL,
  `mname` varchar(80) DEFAULT NULL,
  `lname` varchar(80) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `url` varchar(150) DEFAULT NULL,
  `organization` varchar(160) DEFAULT NULL,
  `street` varchar(180) DEFAULT NULL,
  `street_cont` varchar(180) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip` varchar(15) DEFAULT NULL,
  `country` varchar(160) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `phone2` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `notes` varchar(600) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL COMMENT 'cell phone',
  `direct_address` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fname` (`fname`),
  KEY `mname` (`mname`),
  KEY `lname` (`lname`),
  KEY `email` (`email`),
  KEY `city` (`city`),
  KEY `state` (`state`),
  KEY `zip` (`zip`),
  KEY `phone` (`phone`),
  KEY `direct_address` (`direct_address`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Address Book';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line1` varchar(255) DEFAULT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(35) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `plus_four` varchar(4) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `write_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `address_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `allergies`
--

DROP TABLE IF EXISTS `allergies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `allergies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `allergy` varchar(500) NOT NULL COMMENT 'Allergy Name',
  `allergy_term` varchar(255) NOT NULL,
  `allergy_code` varchar(20) DEFAULT NULL,
  `allergy_code_type` varchar(15) NOT NULL,
  `allergy_type` varchar(5) NOT NULL COMMENT 'PT = Preferred Term, SN = Systematic Name, SY = Synonym, CD = Code, TR = Trade',
  PRIMARY KEY (`id`),
  KEY `allergy_code` (`allergy_code`,`allergy_term`)
) ENGINE=InnoDB AUTO_INCREMENT=552244 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(120) DEFAULT NULL,
  `pvt_key` varchar(80) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'Encounter ID',
  `pid` int(11) DEFAULT NULL COMMENT 'Patient ID',
  `uid` int(11) DEFAULT NULL COMMENT 'User ID',
  `fid` int(11) DEFAULT NULL COMMENT 'Facility ID',
  `event` varchar(200) DEFAULT NULL COMMENT 'Event description',
  `date` datetime DEFAULT NULL COMMENT 'Date of the event',
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `fid` (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Audit Logs';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `audit_transaction_log`
--

DROP TABLE IF EXISTS `audit_transaction_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_transaction_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL COMMENT 'Date of the event',
  `pid` int(11) DEFAULT NULL COMMENT 'Patient ID',
  `eid` int(11) DEFAULT NULL COMMENT 'Encounter ID',
  `uid` int(11) DEFAULT NULL COMMENT 'User ID',
  `fid` int(11) DEFAULT NULL COMMENT 'Facility ID',
  `event` varchar(10) DEFAULT NULL COMMENT 'Event UPDATE INSERT DELETE',
  `table_name` varchar(60) DEFAULT NULL,
  `sql_string` mediumtext,
  `data` mediumtext COMMENT 'serialized data',
  `ip` varchar(40) DEFAULT NULL,
  `checksum` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=856621 DEFAULT CHARSET=utf8 COMMENT='Data INSERT UPDATE DELETE Logs';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calendar_categories`
--

DROP TABLE IF EXISTS `calendar_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_categories` (
  `catid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(160) DEFAULT NULL,
  `catcolor` varchar(10) DEFAULT NULL,
  `catdesc` varchar(255) DEFAULT NULL,
  `duration` bigint(20) NOT NULL DEFAULT '0',
  `cattype` int(11) DEFAULT NULL COMMENT 'Category Type',
  PRIMARY KEY (`catid`),
  KEY `basic_cat` (`catname`,`catcolor`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'patient id of the event patient',
  `uid` int(11) DEFAULT NULL COMMENT 'user id of the event owner',
  `category` int(11) DEFAULT NULL COMMENT 'Ty of calendar category',
  `facility` int(11) DEFAULT NULL COMMENT 'faccility id',
  `billing_facility` int(11) DEFAULT NULL,
  `title` varchar(180) DEFAULT NULL,
  `status` varchar(80) DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `rrule` varchar(80) DEFAULT NULL,
  `loc` varchar(160) DEFAULT NULL,
  `notes` varchar(600) DEFAULT NULL,
  `url` varchar(180) DEFAULT NULL,
  `ad` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cdt_codes`
--

DROP TABLE IF EXISTS `cdt_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cdt_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=594 DEFAULT CHARSET=latin1 COMMENT='Dental Codes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `claims`
--

DROP TABLE IF EXISTS `claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `claims` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `encounter_id` int(11) NOT NULL,
  `version` int(10) unsigned NOT NULL,
  `payer_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  `payer_type` tinyint(4) NOT NULL DEFAULT '0',
  `bill_process` tinyint(2) NOT NULL DEFAULT '0',
  `bill_time` datetime DEFAULT NULL,
  `process_time` datetime DEFAULT NULL,
  `process_file` varchar(255) DEFAULT NULL,
  `target` varchar(30) DEFAULT NULL,
  `x12_partner_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `combo_lists`
--

DROP TABLE IF EXISTS `combo_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combo_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'Title of the combo',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `combo_lists_options`
--

DROP TABLE IF EXISTS `combo_lists_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `combo_lists_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` int(11) NOT NULL DEFAULT '0' COMMENT 'List ID',
  `option_value` varchar(255) NOT NULL DEFAULT '' COMMENT 'Value',
  `option_name` varchar(255) DEFAULT NULL COMMENT 'Name',
  `code` varchar(15) DEFAULT NULL COMMENT 'value code',
  `code_type` varchar(10) DEFAULT NULL COMMENT 'CPT4 LOINC SNOMEDCT ICD9 ICD10 RXNORM',
  `seq` int(11) DEFAULT NULL COMMENT 'Sequence',
  `notes` varchar(255) DEFAULT NULL COMMENT 'Notes',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  PRIMARY KEY (`id`,`list_id`,`option_value`),
  KEY `code` (`code`),
  KEY `IK_code` (`code`),
  KEY `IK_list_id` (`list_id`),
  KEY `IK_option_value` (`option_value`)
) ENGINE=InnoDB AUTO_INCREMENT=3024 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cpt_codes`
--

DROP TABLE IF EXISTS `cpt_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cpt_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ConceptID` bigint(20) NOT NULL,
  `code` varchar(50) NOT NULL,
  `code_text` text,
  `code_text_medium` text,
  `code_text_short` text,
  `active` tinyint(1) DEFAULT NULL,
  `isRadiology` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9640 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cpt_icd`
--

DROP TABLE IF EXISTS `cpt_icd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cpt_icd` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cpt` varchar(40) NOT NULL,
  `icd` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=450629 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cvx_codes`
--

DROP TABLE IF EXISTS `cvx_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cvx_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cvx_code` varchar(6) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `note` text,
  `status` varchar(50) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=335 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cvx_cpt`
--

DROP TABLE IF EXISTS `cvx_cpt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cvx_cpt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cvx` varchar(25) DEFAULT NULL,
  `cpt` varchar(25) DEFAULT NULL,
  `active` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cvx_mvx`
--

DROP TABLE IF EXISTS `cvx_mvx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cvx_mvx` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cdc_product_name` varchar(255) DEFAULT NULL,
  `description` text,
  `cvx_code` varchar(255) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `mvx_code` varchar(255) DEFAULT NULL,
  `mvx_status` varchar(255) DEFAULT NULL,
  `product_name_status` varchar(255) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=latin1 COMMENT='CVX munufactures';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `documents_templates`
--

DROP TABLE IF EXISTS `documents_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documents_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `template_type` varchar(50) DEFAULT NULL,
  `body` text,
  `date` datetime DEFAULT NULL COMMENT 'to be replace by created_date',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `created_by_uid` int(11) DEFAULT NULL,
  `updated_by_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `template_type` (`template_type`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drug_inventory`
--

DROP TABLE IF EXISTS `drug_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drug_inventory` (
  `inventory_id` int(11) NOT NULL AUTO_INCREMENT,
  `drug_id` int(11) NOT NULL,
  `lot_number` varchar(20) DEFAULT NULL,
  `expiration` date DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `on_hand` int(11) NOT NULL DEFAULT '0',
  `warehouse_id` varchar(31) NOT NULL DEFAULT '',
  `vendor_id` bigint(20) NOT NULL DEFAULT '0',
  `last_notify` date NOT NULL DEFAULT '0000-00-00',
  `destroy_date` date DEFAULT NULL,
  `destroy_method` varchar(255) DEFAULT NULL,
  `destroy_witness` varchar(255) DEFAULT NULL,
  `destroy_notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drug_sales`
--

DROP TABLE IF EXISTS `drug_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drug_sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `drug_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `prescription_id` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `encounter` int(11) NOT NULL DEFAULT '0',
  `user` varchar(255) DEFAULT NULL,
  `sale_date` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT '0',
  `fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `billed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'indicates if the sale is posted to accounting',
  `xfer_inventory_id` int(11) NOT NULL DEFAULT '0',
  `distributor_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references users.id',
  `notes` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drug_templates`
--

DROP TABLE IF EXISTS `drug_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drug_templates` (
  `drug_id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL DEFAULT '',
  `dosage` varchar(10) DEFAULT NULL,
  `period` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `refills` int(11) NOT NULL DEFAULT '0',
  `taxrates` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`drug_id`,`selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `drugs`
--

DROP TABLE IF EXISTS `drugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `drugs` (
  `drug_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `ndc_number` varchar(20) NOT NULL DEFAULT '',
  `on_order` int(11) NOT NULL DEFAULT '0',
  `reorder_point` int(11) NOT NULL DEFAULT '0',
  `last_notify` date NOT NULL DEFAULT '0000-00-00',
  `reactions` text,
  `form` int(3) NOT NULL DEFAULT '0',
  `size` float unsigned NOT NULL DEFAULT '0',
  `unit` int(11) NOT NULL DEFAULT '0',
  `route` int(11) NOT NULL DEFAULT '0',
  `substitute` int(11) NOT NULL DEFAULT '0',
  `related_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'may reference a related codes.code',
  `cyp_factor` float NOT NULL DEFAULT '0' COMMENT 'quantity representing a years supply',
  `active` tinyint(1) DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `allow_combining` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = allow filling an order from multiple lots',
  `allow_multiple` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = allow multiple lots at one warehouse',
  PRIMARY KEY (`drug_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eligibility_response`
--

DROP TABLE IF EXISTS `eligibility_response`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eligibility_response` (
  `response_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `response_description` varchar(255) DEFAULT NULL,
  `response_status` enum('A','D') NOT NULL DEFAULT 'A',
  `response_vendor_id` bigint(20) DEFAULT NULL,
  `response_create_date` date DEFAULT NULL,
  `response_modify_date` date DEFAULT NULL,
  PRIMARY KEY (`response_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eligibility_verification`
--

DROP TABLE IF EXISTS `eligibility_verification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eligibility_verification` (
  `verification_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `response_id` bigint(20) DEFAULT NULL,
  `insurance_id` bigint(20) DEFAULT NULL,
  `eligibility_check_date` datetime DEFAULT NULL,
  `copay` int(11) DEFAULT NULL,
  `deductible` int(11) DEFAULT NULL,
  `deductiblemet` enum('Y','N') DEFAULT 'Y',
  `create_date` date DEFAULT NULL,
  PRIMARY KEY (`verification_id`),
  KEY `insurance_id` (`insurance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `emergencies`
--

DROP TABLE IF EXISTS `emergencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emergencies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_1500_options`
--

DROP TABLE IF EXISTS `encounter_1500_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_1500_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `employment_related` tinyint(1) DEFAULT NULL,
  `auto_accident` tinyint(1) DEFAULT NULL,
  `state` varchar(80) DEFAULT NULL,
  `other_accident` tinyint(1) DEFAULT NULL,
  `similar_illness_date` date DEFAULT NULL,
  `unable_to_work_from` date DEFAULT NULL,
  `unable_to_work_to` date DEFAULT NULL,
  `hops_date_to` date DEFAULT NULL,
  `out_lab_used` tinyint(1) DEFAULT NULL,
  `amount_charges` varchar(10) DEFAULT NULL,
  `medicaid_resubmission_code` varchar(15) DEFAULT NULL,
  `medicaid_original_reference_number` varchar(60) DEFAULT NULL,
  `prior_authorization_number` varchar(60) DEFAULT NULL,
  `replacement_claim` tinyint(1) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_dictation`
--

DROP TABLE IF EXISTS `encounter_dictation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_dictation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `dictation` longtext,
  `additional_notes` longtext,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_dx`
--

DROP TABLE IF EXISTS `encounter_dx`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_dx` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `code` varchar(25) DEFAULT NULL COMMENT 'code number',
  `code_type` varchar(25) DEFAULT NULL,
  `dx_group` int(11) DEFAULT NULL,
  `dx_order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`),
  KEY `uid` (`uid`),
  KEY `IK_dx_group` (`dx_group`),
  KEY `IK_dx_order` (`dx_order`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_family_history`
--

DROP TABLE IF EXISTS `encounter_family_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_family_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `auth_uid` int(11) DEFAULT NULL,
  `tuberculosis` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_history`
--

DROP TABLE IF EXISTS `encounter_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `date` datetime DEFAULT NULL COMMENT 'date created',
  `user` varchar(255) DEFAULT NULL,
  `event` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_procedures`
--

DROP TABLE IF EXISTS `encounter_procedures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_procedures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `eid` int(11) DEFAULT NULL COMMENT 'Encounter ID',
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `code` varchar(40) DEFAULT NULL,
  `code_text` varchar(300) DEFAULT NULL,
  `code_type` varchar(15) DEFAULT NULL,
  `observation` varchar(255) DEFAULT NULL COMMENT 'observation found',
  `uid` int(11) DEFAULT NULL,
  `procedure_date` datetime DEFAULT NULL COMMENT 'when procedure has done',
  `encounter_dx_id` int(11) DEFAULT NULL,
  `status_code` varchar(40) DEFAULT NULL,
  `status_code_text` varchar(300) DEFAULT NULL,
  `status_code_type` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Patient Encounter Procedures';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_review_of_systems`
--

DROP TABLE IF EXISTS `encounter_review_of_systems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_review_of_systems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `weight_change` tinyint(1) DEFAULT NULL,
  `weakness` tinyint(1) DEFAULT NULL,
  `fatigue` tinyint(1) DEFAULT NULL,
  `anorexia` tinyint(1) DEFAULT NULL,
  `fever` tinyint(1) DEFAULT NULL,
  `chills` tinyint(1) DEFAULT NULL,
  `night_sweats` tinyint(1) DEFAULT NULL,
  `insomnia` tinyint(1) DEFAULT NULL,
  `irritability` tinyint(1) DEFAULT NULL,
  `heat_or_cold` tinyint(1) DEFAULT NULL,
  `intolerance` tinyint(1) DEFAULT NULL,
  `change_in_vision` tinyint(1) DEFAULT NULL,
  `eye_pain` tinyint(1) DEFAULT NULL,
  `family_history_of_glaucoma` tinyint(1) DEFAULT NULL,
  `irritation` tinyint(1) DEFAULT NULL,
  `redness` tinyint(1) DEFAULT NULL,
  `excessive_tearing` tinyint(1) DEFAULT NULL,
  `double_vision` tinyint(1) DEFAULT NULL,
  `blind_spots` tinyint(1) DEFAULT NULL,
  `photophobia` tinyint(1) DEFAULT NULL,
  `hearing_loss` tinyint(1) DEFAULT NULL,
  `discharge` tinyint(1) DEFAULT NULL,
  `pain` tinyint(1) DEFAULT NULL,
  `vertigo` tinyint(1) DEFAULT NULL,
  `tinnitus` tinyint(1) DEFAULT NULL,
  `frequent_colds` tinyint(1) DEFAULT NULL,
  `sore_throat` tinyint(1) DEFAULT NULL,
  `sinus_problems` tinyint(1) DEFAULT NULL,
  `post_nasal_drip` tinyint(1) DEFAULT NULL,
  `nosebleed` tinyint(1) DEFAULT NULL,
  `snoring` tinyint(1) DEFAULT NULL,
  `apnea` tinyint(1) DEFAULT NULL,
  `breast_mass` tinyint(1) DEFAULT NULL,
  `abnormal_mammogram` tinyint(1) DEFAULT NULL,
  `biopsy` tinyint(1) DEFAULT NULL,
  `cough` tinyint(1) DEFAULT NULL,
  `sputum` tinyint(1) DEFAULT NULL,
  `shortness_of_breath` tinyint(1) DEFAULT NULL,
  `wheezing` tinyint(1) DEFAULT NULL,
  `hemoptysis` tinyint(1) DEFAULT NULL,
  `asthma` tinyint(1) DEFAULT NULL,
  `copd` tinyint(1) DEFAULT NULL,
  `thyroid_problems` tinyint(1) DEFAULT NULL,
  `diabetes` tinyint(1) DEFAULT NULL,
  `abnormal_blood_test` tinyint(1) DEFAULT NULL,
  `chest_pain` tinyint(1) DEFAULT NULL,
  `palpitation` tinyint(1) DEFAULT NULL,
  `syncope` tinyint(1) DEFAULT NULL,
  `pnd` tinyint(1) DEFAULT NULL,
  `doe` tinyint(1) DEFAULT NULL,
  `orthopnea` tinyint(1) DEFAULT NULL,
  `peripheral` tinyint(1) DEFAULT NULL,
  `edema` tinyint(1) DEFAULT NULL,
  `leg_pain_cramping` tinyint(1) DEFAULT NULL,
  `arrythmia` tinyint(1) DEFAULT NULL,
  `heart_problem` tinyint(1) DEFAULT NULL,
  `history_of_heart_murmur` tinyint(1) DEFAULT NULL,
  `polyuria` tinyint(1) DEFAULT NULL,
  `polydypsia` tinyint(1) DEFAULT NULL,
  `dysuria` tinyint(1) DEFAULT NULL,
  `hematuria` tinyint(1) DEFAULT NULL,
  `frequency` tinyint(1) DEFAULT NULL,
  `urgency` tinyint(1) DEFAULT NULL,
  `utis` tinyint(1) DEFAULT NULL,
  `incontinence` tinyint(1) DEFAULT NULL,
  `renal_stones` tinyint(1) DEFAULT NULL,
  `hesitancy` tinyint(1) DEFAULT NULL,
  `dribbling` tinyint(1) DEFAULT NULL,
  `stream` tinyint(1) DEFAULT NULL,
  `nocturia` tinyint(1) DEFAULT NULL,
  `erections` tinyint(1) DEFAULT NULL,
  `ejaculations` tinyint(1) DEFAULT NULL,
  `cancer` tinyint(1) DEFAULT NULL,
  `psoriasis` tinyint(1) DEFAULT NULL,
  `acne` tinyint(1) DEFAULT NULL,
  `disease` tinyint(1) DEFAULT NULL,
  `other` tinyint(1) DEFAULT NULL,
  `anemia` tinyint(1) DEFAULT NULL,
  `hiv` tinyint(1) DEFAULT NULL,
  `f_h_blood_problems` tinyint(1) DEFAULT NULL,
  `hai_status` tinyint(1) DEFAULT NULL,
  `allergies` tinyint(1) DEFAULT NULL,
  `bleeding_problems` tinyint(1) DEFAULT NULL,
  `frequent_illness` tinyint(1) DEFAULT NULL,
  `dysphagia` tinyint(1) DEFAULT NULL,
  `heartburn` tinyint(1) DEFAULT NULL,
  `food_intolerance` tinyint(1) DEFAULT NULL,
  `belching` tinyint(1) DEFAULT NULL,
  `bloating` tinyint(1) DEFAULT NULL,
  `flatulence` tinyint(1) DEFAULT NULL,
  `nausea` tinyint(1) DEFAULT NULL,
  `vomiting` tinyint(1) DEFAULT NULL,
  `jaundice` tinyint(1) DEFAULT NULL,
  `h_o_hepatitis` tinyint(1) DEFAULT NULL,
  `hematemesis` tinyint(1) DEFAULT NULL,
  `diarrhea` tinyint(1) DEFAULT NULL,
  `hematochezia` tinyint(1) DEFAULT NULL,
  `changed_bowel` tinyint(1) DEFAULT NULL,
  `constipation` tinyint(1) DEFAULT NULL,
  `female_g` tinyint(1) DEFAULT NULL,
  `female_p` tinyint(1) DEFAULT NULL,
  `female_ap` tinyint(1) DEFAULT NULL,
  `lmp` tinyint(1) DEFAULT NULL,
  `female_lc` tinyint(1) DEFAULT NULL,
  `menopause` tinyint(1) DEFAULT NULL,
  `flow` tinyint(1) DEFAULT NULL,
  `abnormal_hair_growth` tinyint(1) DEFAULT NULL,
  `menarche` tinyint(1) DEFAULT NULL,
  `symptoms` tinyint(1) DEFAULT NULL,
  `f_h_female_hirsutism_striae` tinyint(1) DEFAULT NULL,
  `anxiety` tinyint(1) DEFAULT NULL,
  `depression` tinyint(1) DEFAULT NULL,
  `psychiatric_medication` tinyint(1) DEFAULT NULL,
  `social_difficulties` tinyint(1) DEFAULT NULL,
  `psychiatric_diagnosis` tinyint(1) DEFAULT NULL,
  `fms` tinyint(1) DEFAULT NULL,
  `swelling` tinyint(1) DEFAULT NULL,
  `Warm` tinyint(1) DEFAULT NULL,
  `muscle` tinyint(1) DEFAULT NULL,
  `stiffness` tinyint(1) DEFAULT NULL,
  `aches` tinyint(1) DEFAULT NULL,
  `arthritis` tinyint(1) DEFAULT NULL,
  `chronic_joint_pain` tinyint(1) DEFAULT NULL,
  `loc` tinyint(1) DEFAULT NULL,
  `stroke` tinyint(1) DEFAULT NULL,
  `paralysis` tinyint(1) DEFAULT NULL,
  `tia` tinyint(1) DEFAULT NULL,
  `numbness` tinyint(1) DEFAULT NULL,
  `memory_problems` tinyint(1) DEFAULT NULL,
  `seizures` tinyint(1) DEFAULT NULL,
  `intellectual_decline` tinyint(1) DEFAULT NULL,
  `dementia` tinyint(1) DEFAULT NULL,
  `headache` tinyint(1) DEFAULT NULL,
  `cons_weakness` tinyint(1) DEFAULT NULL,
  `brest_discharge` tinyint(1) DEFAULT NULL,
  `fem_frequency` tinyint(1) DEFAULT NULL,
  `notes` mediumtext,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_review_of_systems_check`
--

DROP TABLE IF EXISTS `encounter_review_of_systems_check`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_review_of_systems_check` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `fever` text,
  `chills` text,
  `notes` text,
  `night_sweats` text,
  `fatigued` text,
  `depressed` text,
  `rashes` text,
  `hyperactive` text,
  `exposure_to_foreign_countries` text,
  `weight_loss` text,
  `insomnia` text,
  `poor_appetite` text,
  `infections` text,
  `ulcerations` text,
  `pemphigus` text,
  `herpes` text,
  `non_insulin_dependent_diabetes` text,
  `insulin_dependent_diabetes` text,
  `hypothyroidism` text,
  `hyperthyroidism` text,
  `cushing_syndrome` text,
  `addison_syndrome` text,
  `emphysema` text,
  `pheumothorax` text,
  `chronic_bronchitis` text,
  `lung_cancer_surgery` text,
  `interstitial_lung_disease` text,
  `shortness_of_breath` text,
  `lung_cancer` text,
  `cataracts` text,
  `swollen_lymph_nodes` text,
  `glaucoma` text,
  `throat_cancer_surgery` text,
  `cataract_surgery` text,
  `ringing_in_ears` text,
  `headaches` text,
  `blurred_vision` text,
  `sinusitis` text,
  `dry_mouth` text,
  `double_vision` text,
  `poor_hearing` text,
  `tonsillectomy` text,
  `bloody_nose` text,
  `throat_cancer` text,
  `strep_throat` text,
  `sinus_surgery` text,
  `hbox` text,
  `coronary_artery_bypass` text,
  `cardiac_catheterization` text,
  `high_blood_pressure` text,
  `heart_transplant` text,
  `vascular_surger` text,
  `irregular_heart_beat` text,
  `chest_pains` text,
  `heart_attack` text,
  `stress_test` text,
  `poor_circulation` text,
  `heart_failure` text,
  `burning_with_urination` text,
  `discharge_from_urethra` text,
  `sexually_transmitted_disease` text,
  `prostate_problems` text,
  `bladder_infections` text,
  `kidney_infections` text,
  `kidney_transplant` text,
  `prostate_cancer` text,
  `bladder_cancer` text,
  `kidney_cancer` text,
  `kidney_failure` text,
  `kidney_stones` text,
  `colon_cancer_surgery` text,
  `peptic_ulcer_disease` text,
  `diverticulitis_surgery` text,
  `cirrhosis_of_the_liver` text,
  `cholecystectomy` text,
  `crohns_disease` text,
  `ulcerative_colitis` text,
  `stomach_pains` text,
  `appendectomy` text,
  `colon_cancer` text,
  `colonoscopy` text,
  `endoscopy` text,
  `splenectomy` text,
  `diverticulitis` text,
  `gall_stones` text,
  `hepatitis` text,
  `gastritis` text,
  `polyps` text,
  `ankylosing_spondlilitis` text,
  `rheumotoid_arthritis` text,
  `shoulder_problems` text,
  `elbow_problems` text,
  `ankle_problems` text,
  `back_problems` text,
  `back_surgery` text,
  `broken_bones` text,
  `swollen_joints` text,
  `neck_problems` text,
  `osetoarthritis` text,
  `wrist_problems` text,
  `hand_problems` text,
  `knee_problems` text,
  `herniated_disc` text,
  `foot_problems` text,
  `hip_problems` text,
  `stiff_joints` text,
  `scoliosis` text,
  `lupus` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_services`
--

DROP TABLE IF EXISTS `encounter_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_services` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `reference_type` varchar(40) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `billing_reference` varchar(20) DEFAULT NULL,
  `code` varchar(40) DEFAULT NULL,
  `code_text` text,
  `code_type` varchar(40) DEFAULT NULL,
  `units` int(5) DEFAULT NULL,
  `modifiers` mediumtext,
  `dx_group_id` int(11) DEFAULT NULL,
  `dx_pointers` mediumtext,
  `status` varchar(20) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `date_create` datetime DEFAULT NULL,
  `date_update` datetime DEFAULT NULL,
  `tooth` varchar(10) DEFAULT NULL,
  `surface` varchar(5) DEFAULT NULL,
  `cavity_quadrant` varchar(2) DEFAULT NULL,
  `financial_class` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_pid` (`pid`),
  KEY `IK_eid` (`eid`),
  KEY `IK_reference_type` (`reference_type`),
  KEY `IK_reference_id` (`reference_id`),
  KEY `IK_code` (`code`),
  KEY `IK_billing_reference` (`billing_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_soap`
--

DROP TABLE IF EXISTS `encounter_soap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_soap` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `subjective` mediumtext,
  `objective` mediumtext,
  `assessment` mediumtext,
  `plan` mediumtext,
  `instructions` mediumtext,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounter_vitals`
--

DROP TABLE IF EXISTS `encounter_vitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounter_vitals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` bigint(20) NOT NULL COMMENT 'User (id) "who saved the vitals data"',
  `auth_uid` bigint(20) DEFAULT NULL,
  `date` datetime NOT NULL COMMENT 'date vitals were taken',
  `weight_lbs` varchar(10) DEFAULT NULL,
  `weight_kg` float DEFAULT NULL,
  `height_in` float DEFAULT NULL,
  `height_cm` float DEFAULT NULL,
  `bp_systolic` float DEFAULT NULL,
  `bp_diastolic` float DEFAULT NULL,
  `pulse` int(10) DEFAULT NULL,
  `respiration` int(10) DEFAULT NULL,
  `temp_f` float DEFAULT NULL,
  `temp_c` float DEFAULT NULL,
  `temp_location` varchar(40) DEFAULT NULL,
  `oxygen_saturation` float DEFAULT NULL,
  `head_circumference_in` float DEFAULT NULL,
  `head_circumference_cm` float DEFAULT NULL,
  `waist_circumference_in` float DEFAULT NULL,
  `waist_circumference_cm` float DEFAULT NULL,
  `bmi` float DEFAULT NULL,
  `bmi_status` varchar(10) DEFAULT NULL,
  `other_notes` varchar(600) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `encounters`
--

DROP TABLE IF EXISTS `encounters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encounters` (
  `eid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Encounter ID',
  `pid` int(11) DEFAULT NULL,
  `open_uid` int(11) DEFAULT NULL,
  `provider_uid` int(11) DEFAULT NULL,
  `supervisor_uid` int(11) DEFAULT NULL,
  `requires_supervisor` tinyint(1) DEFAULT NULL,
  `technician_uid` int(11) DEFAULT NULL,
  `specialty_id` int(11) DEFAULT NULL,
  `service_date` datetime DEFAULT NULL,
  `close_date` datetime DEFAULT NULL COMMENT 'Date when the encounter was sign/close',
  `onset_date` datetime DEFAULT NULL,
  `priority` varchar(60) DEFAULT NULL,
  `brief_description` varchar(600) DEFAULT NULL COMMENT 'chief complaint',
  `visit_category` varchar(80) DEFAULT NULL,
  `facility` int(1) DEFAULT NULL,
  `billing_stage` int(1) DEFAULT NULL,
  `followup_time` varchar(25) DEFAULT NULL,
  `followup_facility` varchar(80) DEFAULT NULL,
  `review_immunizations` tinyint(1) DEFAULT NULL,
  `review_allergies` tinyint(1) DEFAULT NULL,
  `review_active_problems` tinyint(1) DEFAULT NULL,
  `review_alcohol` varchar(40) DEFAULT NULL,
  `review_smoke` tinyint(1) DEFAULT NULL,
  `review_pregnant` varchar(40) DEFAULT NULL,
  `review_surgery` tinyint(1) DEFAULT NULL,
  `review_dental` tinyint(1) DEFAULT NULL,
  `review_medications` tinyint(1) DEFAULT NULL,
  `message` text COMMENT 'message for the visit checkout ',
  `rid` varchar(80) DEFAULT NULL COMMENT 'reference ID',
  `patient_class` varchar(255) DEFAULT NULL,
  `referring_physician` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`eid`),
  KEY `pid` (`pid`),
  KEY `open_uid` (`open_uid`),
  KEY `provider_uid` (`provider_uid`),
  KEY `supervisor_uid` (`supervisor_uid`),
  KEY `service_date` (`service_date`),
  KEY `facility` (`facility`),
  KEY `billing_stage` (`billing_stage`),
  KEY `requires_supervisor` (`requires_supervisor`),
  KEY `specialty_id` (`specialty_id`),
  KEY `IK_requires_supervisor` (`requires_supervisor`),
  KEY `IK_specialty_id` (`specialty_id`),
  KEY `IK_technician_uid` (`technician_uid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facility`
--

DROP TABLE IF EXISTS `facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(80) DEFAULT NULL,
  `name` varchar(120) DEFAULT NULL COMMENT 'Facility Name',
  `legal_name` varchar(180) DEFAULT NULL,
  `attn` varchar(80) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `fax` varchar(25) DEFAULT NULL,
  `address` varchar(120) DEFAULT NULL,
  `address_cont` varchar(120) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `state` varchar(80) DEFAULT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `country_code` varchar(5) DEFAULT NULL,
  `service_location` tinyint(1) DEFAULT NULL,
  `billing_location` tinyint(1) DEFAULT NULL,
  `pos_code` varchar(3) DEFAULT NULL,
  `ssn` varchar(15) DEFAULT NULL,
  `ein` varchar(15) DEFAULT NULL,
  `clia` varchar(15) DEFAULT NULL,
  `fda` varchar(15) DEFAULT NULL,
  `npi` varchar(15) DEFAULT NULL,
  `ess` varchar(15) DEFAULT NULL,
  `lic` varchar(15) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `facility_structures`
--

DROP TABLE IF EXISTS `facility_structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility_structures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fid` int(11) DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  `foreign_type` varchar(1) DEFAULT NULL COMMENT 'D = department S = specialty',
  `parentId` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`),
  KEY `foreign_id` (`foreign_id`),
  KEY `foreign_type` (`foreign_type`),
  KEY `parentId` (`parentId`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 COMMENT='Facilities Dept and Specialties';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fee_sheet_options`
--

DROP TABLE IF EXISTS `fee_sheet_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fee_sheet_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fs_category` varchar(63) DEFAULT NULL,
  `fs_option` varchar(63) DEFAULT NULL,
  `fs_codes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `floor_plans`
--

DROP TABLE IF EXISTS `floor_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `floor_plans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(180) DEFAULT NULL COMMENT 'Floor Title',
  `facility_id` int(11) DEFAULT NULL COMMENT 'facility ID',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active Floor Plan?',
  PRIMARY KEY (`id`),
  KEY `facility_id` (`facility_id`),
  KEY `active` (`active`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `floor_plans_zones`
--

DROP TABLE IF EXISTS `floor_plans_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `floor_plans_zones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `floor_plan_id` int(11) DEFAULT NULL,
  `code` varchar(40) DEFAULT NULL,
  `title` varchar(180) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `bg_color` varchar(10) DEFAULT NULL,
  `border_color` varchar(10) DEFAULT NULL,
  `scale` varchar(255) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `show_priority_color` tinyint(1) DEFAULT NULL,
  `show_patient_preview` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `floor_plan_id` (`floor_plan_id`),
  KEY `active` (`active`),
  KEY `IK_active` (`active`),
  KEY `IK_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `form_misc_billing_options`
--

DROP TABLE IF EXISTS `form_misc_billing_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `form_misc_billing_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  `employment_related` tinyint(1) DEFAULT NULL,
  `auto_accident` tinyint(1) DEFAULT NULL,
  `accident_state` varchar(2) DEFAULT NULL,
  `other_accident` tinyint(1) DEFAULT NULL,
  `outside_lab` tinyint(1) DEFAULT NULL,
  `lab_amount` decimal(5,2) DEFAULT NULL,
  `is_unable_to_work` tinyint(1) DEFAULT NULL,
  `off_work_from` date DEFAULT NULL,
  `off_work_to` date DEFAULT NULL,
  `is_hospitalized` tinyint(1) DEFAULT NULL,
  `hospitalization_date_from` date DEFAULT NULL,
  `hospitalization_date_to` date DEFAULT NULL,
  `medicaid_resubmission_code` varchar(10) DEFAULT NULL,
  `medicaid_original_reference` varchar(15) DEFAULT NULL,
  `prior_auth_number` varchar(20) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  `replacement_claim` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forms_field_options`
--

DROP TABLE IF EXISTS `forms_field_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` varchar(255) DEFAULT NULL COMMENT 'Field ID',
  `options` text COMMENT 'Field Options JSON Format',
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1687 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forms_fields`
--

DROP TABLE IF EXISTS `forms_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms_fields` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) DEFAULT NULL,
  `xtype` varchar(80) COLLATE latin1_bin DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  `x_index` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `parentId` (`parentId`)
) ENGINE=InnoDB AUTO_INCREMENT=1311 DEFAULT CHARSET=latin1 COLLATE=latin1_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `forms_layout`
--

DROP TABLE IF EXISTS `forms_layout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `form_data` varchar(80) DEFAULT NULL,
  `model` varchar(80) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `geo_ip_location`
--

DROP TABLE IF EXISTS `geo_ip_location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geo_ip_location` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip_start` varchar(255) DEFAULT NULL,
  `ip_end` varchar(255) DEFAULT NULL,
  `ip_start_num` bigint(15) DEFAULT NULL,
  `ip_end_num` bigint(15) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `country_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ip_start_num` (`ip_start_num`),
  KEY `ip_end_num` (`ip_end_num`),
  KEY `ip_start_end_num` (`ip_start_num`,`ip_end_num`)
) ENGINE=InnoDB AUTO_INCREMENT=167701 DEFAULT CHARSET=utf8 COMMENT='IPs Country codes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `globals`
--

DROP TABLE IF EXISTS `globals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `globals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gl_name` varchar(255) DEFAULT NULL COMMENT 'Global Setting Unique Name or Key',
  `gl_index` int(11) DEFAULT NULL COMMENT 'Global Setting Index',
  `gl_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Global Setting Value',
  `gl_category` varchar(255) DEFAULT NULL COMMENT 'Category',
  PRIMARY KEY (`id`),
  KEY `gl_name` (`gl_name`,`gl_index`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` longtext,
  `user` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hcpcs_codes`
--

DROP TABLE IF EXISTS `hcpcs_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hcpcs_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `HCPCS_CD` varchar(5) DEFAULT NULL,
  `HCPCS_LONG_DESC_TXT` varchar(255) DEFAULT NULL,
  `HCPCS_SHRT_DESC_TXT` varchar(30) DEFAULT NULL,
  `HCPCS_PRCNG_IND_CD` varchar(2) DEFAULT NULL,
  `HCPCS_MLTPL_PRCNG_IND_CD` varchar(1) DEFAULT NULL,
  `HCPCS_CIM_RFRNC_SECT_NUM` varchar(6) DEFAULT NULL,
  `HCPCS_MCM_RFRNC_SECT_NUM` varchar(8) DEFAULT NULL,
  `HCPCS_STATUTE_NUM` varchar(10) DEFAULT NULL,
  `HCPCS_LAB_CRTFCTN_CD` int(10) DEFAULT NULL,
  `HCPCS_XREF_CD` int(5) DEFAULT NULL,
  `HCPCS_CVRG_CD` int(1) DEFAULT NULL,
  `HCPCS_ASC_PMT_GRP_CD` varchar(2) DEFAULT NULL,
  `HCPCS_ASC_PMT_GRP_EFCTV_DT` varchar(8) DEFAULT NULL,
  `HCPCS_MOG_PMT_GRP_CD` varchar(3) DEFAULT NULL,
  `HCPCS_MOG_PMT_PLCY_IND_CD` varchar(1) DEFAULT NULL,
  `HCPCS_MOG_PMT_GRP_EFCTV_DT` varchar(8) DEFAULT NULL,
  `HCPCS_PRCSG_NOTE_NUM` varchar(4) DEFAULT NULL,
  `HCPCS_BETOS_CD` varchar(3) DEFAULT NULL,
  `HCPCS_TYPE_SRVC_CD` varchar(1) DEFAULT NULL,
  `HCPCS_ANSTHSA_BASE_UNIT_QTY` varchar(3) DEFAULT NULL,
  `HCPCS_CD_ADD_DT` varchar(8) DEFAULT NULL,
  `HCPCS_ACTN_EFCTV_DT` varchar(8) DEFAULT NULL,
  `HCPCS_TRMNTN_DT` varchar(8) DEFAULT NULL,
  `HCPCS_ACTN_CD` varchar(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hl7_clients`
--

DROP TABLE IF EXISTS `hl7_clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hl7_clients` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `facility` varchar(80) DEFAULT NULL COMMENT 'Facility Name',
  `physical_address` varchar(1000) DEFAULT NULL COMMENT 'Facility Name',
  `address` varchar(255) DEFAULT NULL COMMENT 'URL IP',
  `port` varchar(10) DEFAULT NULL COMMENT 'url port if any',
  `isSecure` tinyint(1) DEFAULT NULL COMMENT 'If secure then user secret_key',
  `secret_key` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `application_name` varchar(80) DEFAULT NULL COMMENT 'Application Name',
  `route` varchar(255) DEFAULT NULL COMMENT 'socket or http',
  `allow_messages` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='hl7 Clients';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hl7_messages`
--

DROP TABLE IF EXISTS `hl7_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hl7_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msg_type` varchar(15) DEFAULT NULL COMMENT 'example VXU ADT OBX',
  `message` mediumtext COMMENT 'Original HL7 message',
  `response` mediumtext COMMENT 'HL7 acknowledgment message',
  `foreign_facility` varchar(60) DEFAULT NULL COMMENT 'From or To external facility',
  `foreign_application` varchar(60) DEFAULT NULL COMMENT 'From or To external Application',
  `foreign_address` varchar(180) DEFAULT NULL COMMENT 'incoming or outgoing address',
  `isOutbound` tinyint(1) DEFAULT NULL COMMENT 'outbound 1, inbound 0',
  `date_processed` datetime DEFAULT NULL COMMENT 'When Message was Received or Send',
  `status` int(1) DEFAULT NULL COMMENT '0 = hold, 1 = processing, 2 = queue, 3 = processed, 4 = error',
  `error` varchar(255) DEFAULT NULL COMMENT 'connection error message',
  `reference` varchar(60) DEFAULT NULL COMMENT 'Reference number or file name',
  PRIMARY KEY (`id`),
  KEY `msg_type` (`msg_type`),
  KEY `status` (`status`),
  KEY `error` (`error`)
) ENGINE=InnoDB AUTO_INCREMENT=565 DEFAULT CHARSET=utf8 COMMENT='hl7 messages data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hl7_servers`
--

DROP TABLE IF EXISTS `hl7_servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hl7_servers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(255) DEFAULT NULL,
  `allow_messages` longtext,
  `allow_ips` longtext,
  `port` varchar(10) DEFAULT NULL,
  `ip` varchar(40) DEFAULT NULL,
  `token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_dx_order_code`
--

DROP TABLE IF EXISTS `icd10_dx_order_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_dx_order_code` (
  `dx_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_code` varchar(7) DEFAULT NULL,
  `formatted_dx_code` varchar(10) DEFAULT NULL,
  `valid_for_coding` char(1) DEFAULT NULL,
  `short_desc` varchar(60) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `dx_id` (`dx_id`),
  KEY `formatted_dx_code` (`formatted_dx_code`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=79504 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_gem_dx_10_9`
--

DROP TABLE IF EXISTS `icd10_gem_dx_10_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_dx_10_9` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_icd10_source` varchar(7) DEFAULT NULL,
  `dx_icd9_target` varchar(5) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=78841 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_gem_dx_9_10`
--

DROP TABLE IF EXISTS `icd10_gem_dx_9_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_dx_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_icd9_source` varchar(5) DEFAULT NULL,
  `dx_icd10_target` varchar(7) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23913 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_gem_pcs_10_9`
--

DROP TABLE IF EXISTS `icd10_gem_pcs_10_9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_pcs_10_9` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_icd10_source` varchar(7) DEFAULT NULL,
  `pcs_icd9_target` varchar(5) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=82570 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_gem_pcs_9_10`
--

DROP TABLE IF EXISTS `icd10_gem_pcs_9_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_gem_pcs_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_icd9_source` varchar(5) DEFAULT NULL,
  `pcs_icd10_target` varchar(7) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69363 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_pcs_order_code`
--

DROP TABLE IF EXISTS `icd10_pcs_order_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_pcs_order_code` (
  `pcs_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_code` varchar(7) DEFAULT NULL,
  `valid_for_coding` char(1) DEFAULT NULL,
  `short_desc` varchar(60) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `pcs_id` (`pcs_id`),
  KEY `pcs_code` (`pcs_code`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=72764 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_reimbr_dx_9_10`
--

DROP TABLE IF EXISTS `icd10_reimbr_dx_9_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_reimbr_dx_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(8) DEFAULT NULL,
  `code_cnt` tinyint(4) DEFAULT NULL,
  `ICD9_01` varchar(5) DEFAULT NULL,
  `ICD9_02` varchar(5) DEFAULT NULL,
  `ICD9_03` varchar(5) DEFAULT NULL,
  `ICD9_04` varchar(5) DEFAULT NULL,
  `ICD9_05` varchar(5) DEFAULT NULL,
  `ICD9_06` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69834 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd10_reimbr_pcs_9_10`
--

DROP TABLE IF EXISTS `icd10_reimbr_pcs_9_10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd10_reimbr_pcs_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(8) DEFAULT NULL,
  `code_cnt` tinyint(4) DEFAULT NULL,
  `ICD9_01` varchar(5) DEFAULT NULL,
  `ICD9_02` varchar(5) DEFAULT NULL,
  `ICD9_03` varchar(5) DEFAULT NULL,
  `ICD9_04` varchar(5) DEFAULT NULL,
  `ICD9_05` varchar(5) DEFAULT NULL,
  `ICD9_06` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71919 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd9_dx_code`
--

DROP TABLE IF EXISTS `icd9_dx_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd9_dx_code` (
  `dx_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_code` varchar(5) DEFAULT NULL,
  `formatted_dx_code` varchar(6) DEFAULT NULL,
  `short_desc` varchar(60) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `dx_id` (`dx_id`),
  KEY `dx_code` (`dx_code`),
  KEY `formatted_dx_code` (`formatted_dx_code`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=14568 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd9_dx_long_code`
--

DROP TABLE IF EXISTS `icd9_dx_long_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd9_dx_long_code` (
  `dx_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_code` varchar(5) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `dx_id` (`dx_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14568 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd9_sg_code`
--

DROP TABLE IF EXISTS `icd9_sg_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd9_sg_code` (
  `sg_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sg_code` varchar(5) DEFAULT NULL,
  `formatted_sg_code` varchar(6) DEFAULT NULL,
  `short_desc` varchar(60) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `sg_id` (`sg_id`),
  KEY `sg_code` (`sg_code`),
  KEY `formatted_sg_code` (`formatted_sg_code`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=3879 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `icd9_sg_long_code`
--

DROP TABLE IF EXISTS `icd9_sg_long_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `icd9_sg_long_code` (
  `sq_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sg_code` varchar(5) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `sq_id` (`sq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3879 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `immunizations`
--

DROP TABLE IF EXISTS `immunizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immunizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_text` varchar(255) NOT NULL DEFAULT '',
  `code_text_short` varchar(24) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `code_type` tinyint(2) DEFAULT NULL,
  `modifier` varchar(5) NOT NULL DEFAULT '',
  `units` tinyint(3) DEFAULT NULL,
  `fee` decimal(12,2) DEFAULT NULL,
  `superbill` varchar(31) NOT NULL DEFAULT '',
  `related_code` varchar(255) NOT NULL DEFAULT '',
  `taxrates` varchar(255) NOT NULL DEFAULT '',
  `cyp_factor` float NOT NULL DEFAULT '0' COMMENT 'quantity representing a years supply',
  `active` tinyint(1) DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `reportable` tinyint(1) DEFAULT '0' COMMENT '0 = non-reportable, 1 = reportable',
  `sex` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `frequency_number` int(11) DEFAULT NULL,
  `times_to_perform` int(11) DEFAULT NULL,
  `age_start` int(11) DEFAULT NULL,
  `age_end` int(11) DEFAULT NULL,
  `pregnant` smallint(1) DEFAULT NULL,
  `only_once` tinyint(1) DEFAULT NULL,
  `medications` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `labs` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `frequency_time` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `active_problems` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `immunizations_relations`
--

DROP TABLE IF EXISTS `immunizations_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `immunizations_relations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `immunization_id` bigint(20) DEFAULT NULL,
  `foreign_id` bigint(20) DEFAULT NULL,
  `code_type` varchar(255) DEFAULT NULL COMMENT 'medication,active problem or labs',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `insurance_companies`
--

DROP TABLE IF EXISTS `insurance_companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(80) DEFAULT NULL COMMENT 'use to reference the insurance to another software',
  `name` varchar(120) DEFAULT NULL,
  `attn` varchar(120) DEFAULT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `city` varchar(80) DEFAULT NULL,
  `state` varchar(80) DEFAULT NULL,
  `zip_code` varchar(15) DEFAULT NULL,
  `country` varchar(80) DEFAULT NULL,
  `phone1` varchar(20) DEFAULT NULL,
  `phone2` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `dx_type` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_external_ref` (`code`),
  KEY `IK_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `insurance_numbers`
--

DROP TABLE IF EXISTS `insurance_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insurance_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL DEFAULT '0',
  `insurance_company_id` int(11) DEFAULT NULL,
  `group_number` varchar(20) DEFAULT NULL,
  `provider_number` varchar(20) DEFAULT NULL,
  `provider_number_type` varchar(4) DEFAULT NULL,
  `rendering_provider_number` varchar(20) DEFAULT NULL,
  `rendering_provider_number_type` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ip_access_log`
--

DROP TABLE IF EXISTS `ip_access_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_access_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) DEFAULT NULL,
  `country_code` varchar(130) DEFAULT NULL,
  `event` varchar(10) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ip_access_rules`
--

DROP TABLE IF EXISTS `ip_access_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_access_rules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) DEFAULT NULL,
  `country_code` varchar(130) DEFAULT NULL,
  `rule` varchar(10) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_weight` (`weight`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `issue_encounter`
--

DROP TABLE IF EXISTS `issue_encounter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `issue_encounter` (
  `pid` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `encounter` int(11) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  PRIMARY KEY (`pid`,`list_id`,`encounter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `laboratories`
--

DROP TABLE IF EXISTS `laboratories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `laboratories` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `transmit_method` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `phone_id` int(11) DEFAULT NULL,
  `fax_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labs_guidelines`
--

DROP TABLE IF EXISTS `labs_guidelines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labs_guidelines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) DEFAULT NULL,
  `less_than` float DEFAULT NULL,
  `greater_than` float DEFAULT NULL,
  `equal_to` float DEFAULT NULL,
  `preventive_care_id` int(11) DEFAULT NULL,
  `value_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labs_loinc`
--

DROP TABLE IF EXISTS `labs_loinc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labs_loinc` (
  `LOINC_NUM` varchar(255) NOT NULL,
  `COMPONENT` text,
  `PROPERTY` text,
  `TIME_ASPCT` text,
  `SYSTEM` text,
  `SCALE_TYP` text,
  `CLASS` text,
  `SOURCE` text,
  `COMMENTS` text,
  `STATUS` text,
  `CLASSTYPE` text,
  `UNITSREQUIRED` text,
  `SUBMITTED_UNITS` varchar(255) DEFAULT NULL,
  `RELATEDNAMES2` text,
  `SHORTNAME` text,
  `ORDER_OBS` text,
  `CDISC_COMMON_TESTS` text,
  `HL7_FIELD_SUBFIELD_ID` text,
  `LONG_COMMON_NAME` text,
  `HL7_V2_DATATYPE` text,
  `HL7_V3_DATATYPE` text,
  `COMMON_TEST_RANK` text,
  `COMMON_ORDER_RANK` text,
  `ANSWER_LIST_TYPE` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labs_panels`
--

DROP TABLE IF EXISTS `labs_panels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labs_panels` (
  `id` bigint(20) NOT NULL,
  `code_text_short` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) DEFAULT NULL,
  `parent_loinc` text,
  `parent_name` text,
  `sequence` text,
  `loinc_number` text,
  `loinc_name` text,
  `default_unit` varchar(255) DEFAULT NULL,
  `range_start` varchar(255) DEFAULT NULL,
  `range_end` varchar(255) DEFAULT NULL,
  `required_in_panel` text,
  `description` text,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `labs_preventive_care`
--

DROP TABLE IF EXISTS `labs_preventive_care`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `labs_preventive_care` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `label` text,
  `unit` text,
  `range_start` text,
  `range_end` text,
  `notes` text,
  `coding` text,
  `code` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `facility` varchar(255) NOT NULL,
  `comments` longtext,
  `user_notes` longtext,
  `patient_id` bigint(20) DEFAULT NULL,
  `success` tinyint(1) DEFAULT '1',
  `checksum` longtext,
  `crt_user` varchar(255) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loinc`
--

DROP TABLE IF EXISTS `loinc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loinc` (
  `loinc_num` varchar(10) NOT NULL,
  `component` varchar(255) DEFAULT NULL,
  `property` varchar(30) DEFAULT NULL,
  `time_aspct` varchar(15) DEFAULT NULL,
  `system` varchar(100) DEFAULT NULL,
  `scale_typ` varchar(30) DEFAULT NULL,
  `method_typ` varchar(50) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `source` varchar(8) DEFAULT NULL,
  `date_last_changed` datetime DEFAULT NULL,
  `chng_type` varchar(3) DEFAULT NULL,
  `DefinitionDescription` text,
  `status` varchar(11) DEFAULT NULL,
  `consumer_name` varchar(255) DEFAULT NULL,
  `classtype` int(11) DEFAULT NULL,
  `formula` varchar(255) DEFAULT NULL,
  `species` varchar(20) DEFAULT NULL,
  `exmpl_answers` text,
  `survey_quest_text` text,
  `survey_quest_src` varchar(50) DEFAULT NULL,
  `unitsrequired` varchar(1) DEFAULT NULL,
  `submitted_units` varchar(30) DEFAULT NULL,
  `relatednames2` text,
  `shortname` varchar(40) DEFAULT NULL,
  `order_obs` varchar(15) DEFAULT NULL,
  `cdisc_common_tests` varchar(1) DEFAULT NULL,
  `hl7_field_subfield_id` varchar(50) DEFAULT NULL,
  `external_copyright_notice` text,
  `example_units` varchar(255) DEFAULT NULL,
  `long_common_name` varchar(255) DEFAULT NULL,
  `UnitsAndRange` text,
  `document_section` varchar(255) DEFAULT NULL,
  `example_ucum_units` varchar(255) DEFAULT NULL,
  `example_si_ucum_units` varchar(255) DEFAULT NULL,
  `status_reason` varchar(9) DEFAULT NULL,
  `status_text` text,
  `change_reason_public` text,
  `common_test_rank` int(11) DEFAULT NULL,
  `common_order_rank` int(11) DEFAULT NULL,
  `common_si_test_rank` int(11) DEFAULT NULL,
  `hl7_attachment_structure` varchar(15) DEFAULT NULL,
  `ExternalCopyrightLink` varchar(255) DEFAULT NULL,
  `PanelType` varchar(50) DEFAULT NULL,
  `AskAtOrderEntry` varchar(255) DEFAULT NULL,
  `AssociatedObservations` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`loinc_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loinc_extra`
--

DROP TABLE IF EXISTS `loinc_extra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loinc_extra` (
  `LOINC_NUM` varchar(7) NOT NULL,
  `HAS_CHILDREN` tinyint(1) NOT NULL,
  `HAS_PARENT` tinyint(1) NOT NULL,
  `ALIAS` varchar(140) DEFAULT NULL,
  `DEFAULT_UNIT` varchar(25) DEFAULT NULL,
  `RANGE_START` varchar(15) DEFAULT NULL,
  `RANGE_END` varchar(15) DEFAULT NULL,
  `SECONDARY_CODE` varchar(15) DEFAULT NULL,
  `SECONDARY_CODE_TYPE` varchar(10) DEFAULT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  `ACTIVE` tinyint(1) NOT NULL,
  KEY `LOINC_NUM` (`LOINC_NUM`),
  KEY `HAS_CHILDREN` (`HAS_CHILDREN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loinc_map_to`
--

DROP TABLE IF EXISTS `loinc_map_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loinc_map_to` (
  `loinc` varchar(10) NOT NULL DEFAULT '',
  `map_to` varchar(10) NOT NULL DEFAULT '',
  `comment` text,
  PRIMARY KEY (`loinc`,`map_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loinc_old`
--

DROP TABLE IF EXISTS `loinc_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loinc_old` (
  `loinc_num` varchar(10) NOT NULL,
  `component` varchar(255) DEFAULT NULL,
  `property` varchar(30) DEFAULT NULL,
  `time_aspct` varchar(15) DEFAULT NULL,
  `system` varchar(100) DEFAULT NULL,
  `scale_typ` varchar(30) DEFAULT NULL,
  `method_typ` varchar(50) DEFAULT NULL,
  `class` varchar(20) DEFAULT NULL,
  `source` varchar(8) DEFAULT NULL,
  `date_last_changed` varchar(8) DEFAULT NULL,
  `chng_type` varchar(3) DEFAULT NULL,
  `comments` text,
  `status` varchar(11) DEFAULT NULL,
  `consumer_name` varchar(255) DEFAULT NULL,
  `molar_mass` varchar(13) DEFAULT NULL,
  `classtype` int(11) DEFAULT NULL,
  `formula` varchar(255) DEFAULT NULL,
  `species` varchar(20) DEFAULT NULL,
  `exmpl_answers` text,
  `acssym` text,
  `base_name` varchar(50) DEFAULT NULL,
  `naaccr_id` varchar(20) DEFAULT NULL,
  `code_table` varchar(10) DEFAULT NULL,
  `survey_quest_text` text,
  `survey_quest_src` varchar(50) DEFAULT NULL,
  `unitsrequired` varchar(1) DEFAULT NULL,
  `submitted_units` varchar(30) DEFAULT NULL,
  `relatednames2` text,
  `shortname` varchar(40) DEFAULT NULL,
  `order_obs` varchar(15) DEFAULT NULL,
  `cdisc_common_tests` varchar(1) DEFAULT NULL,
  `hl7_field_subfield_id` varchar(50) DEFAULT NULL,
  `external_copyright_notice` text,
  `example_units` varchar(255) DEFAULT NULL,
  `long_common_name` varchar(255) DEFAULT NULL,
  `hl7_v2_datatype` varchar(255) DEFAULT NULL,
  `hl7_v3_datatype` varchar(255) DEFAULT NULL,
  `curated_range_and_units` text,
  `document_section` varchar(255) DEFAULT NULL,
  `example_ucum_units` varchar(255) DEFAULT NULL,
  `example_si_ucum_units` varchar(255) DEFAULT NULL,
  `status_reason` varchar(9) DEFAULT NULL,
  `status_text` text,
  `change_reason_public` text,
  `common_test_rank` int(11) DEFAULT NULL,
  `common_order_rank` int(11) DEFAULT NULL,
  `common_si_test_rank` int(11) DEFAULT NULL,
  `hl7_attachment_structure` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`loinc_num`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loinc_panels`
--

DROP TABLE IF EXISTS `loinc_panels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loinc_panels` (
  `PARENT_ID` int(11) DEFAULT NULL,
  `PARENT_LOINC` varchar(7) DEFAULT NULL,
  `PARENT_NAME` varchar(255) DEFAULT NULL,
  `ID` int(11) NOT NULL DEFAULT '0',
  `SEQUENCE` varchar(10) NOT NULL,
  `LOINC_NUM` varchar(7) NOT NULL,
  `LOINC_NAME` varchar(255) NOT NULL,
  `DISPLAY_NAME_FOR_FORM` varchar(255) NOT NULL,
  `OBSERVATION_REQUIRED_IN_PANEL` varchar(10) NOT NULL,
  `OBSERVATION_ID_IN_FORM` varchar(19) NOT NULL,
  `SKIP_LOGIC_TARGET` varchar(10) NOT NULL,
  `SKIP_LOGIC_TARGET_ANSWER` varchar(10) NOT NULL,
  `SKIP_LOGIC_HELP_TEXT` varchar(255) NOT NULL,
  `ANSWER_REQUIRED_YN` text NOT NULL,
  `MAXIMUM_NUMBER_OF_ANSWERS` text NOT NULL,
  `DEFAULT_VALUE` varchar(100) NOT NULL,
  `TYPE_OF_ENTRY` varchar(10) NOT NULL,
  `DATA_TYPE_IN_FORM` varchar(10) NOT NULL,
  `DATA_TYPE_SOURCE` varchar(10) NOT NULL,
  `ANSWER_SEQUENCE_OVERRIDE` varchar(10) NOT NULL,
  `CONDITION_FOR_INCLUSION` text NOT NULL,
  `ALLOWABLE_ALTERNATIVE` varchar(10) NOT NULL,
  `OBSERVATION_CATEGORY` varchar(10) NOT NULL,
  `CONTEXT` varchar(10) NOT NULL,
  `CONSISTENCY_CHECKS` varchar(10) NOT NULL,
  `RELEVANCE_EQUATION` varchar(100) NOT NULL,
  `CODING_INSTRUCTIONS` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT_ID` (`PARENT_ID`),
  KEY `LOINC_NUM` (`LOINC_NUM`),
  KEY `LOINC_NAME` (`LOINC_NAME`),
  KEY `PARENT_LOINC` (`PARENT_LOINC`),
  FULLTEXT KEY `DISPLAY_NAME_FOR_FORM` (`DISPLAY_NAME_FOR_FORM`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `loinc_source_organization`
--

DROP TABLE IF EXISTS `loinc_source_organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loinc_source_organization` (
  `copyright_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `copyright` text,
  `terms_of_use` text,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`copyright_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `map_to`
--

DROP TABLE IF EXISTS `map_to`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map_to` (
  `loinc` varchar(10) NOT NULL DEFAULT '',
  `map_to` varchar(10) NOT NULL DEFAULT '',
  `comment` text,
  PRIMARY KEY (`loinc`,`map_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `medications`
--

DROP TABLE IF EXISTS `medications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `PRODUCTNDC` text,
  `PRODUCTTYPENAME` text,
  `PROPRIETARYNAME` text,
  `PROPRIETARYNAMESUFFIX` text,
  `NONPROPRIETARYNAME` text,
  `DOSAGEFORMNAME` text,
  `ROUTENAME` text,
  `STARTMARKETINGDATE` text,
  `ENDMARKETINGDATE` text,
  `MARKETINGCATEGORYNAME` text,
  `APPLICATIONNUMBER` text,
  `LABELERNAME` text,
  `SUBSTANCENAME` text,
  `ACTIVE_NUMERATOR_STRENGTH` text,
  `ACTIVE_INGRED_UNIT` text,
  `PHARM_CLASSES` text,
  `DEASCHEDULE` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` varchar(255) DEFAULT NULL COMMENT 'Date of message',
  `body` text COMMENT 'Message',
  `pid` int(11) DEFAULT NULL COMMENT 'Patient ID',
  `patient_name` varchar(255) DEFAULT NULL COMMENT 'Patient Name',
  `from_user` varchar(255) DEFAULT NULL COMMENT 'Message is from user',
  `to_user` varchar(255) DEFAULT NULL COMMENT 'Message to user',
  `subject` varchar(255) DEFAULT NULL COMMENT 'Subject of the message',
  `facility_id` varchar(255) DEFAULT NULL COMMENT 'Facility',
  `authorized` varchar(255) DEFAULT NULL COMMENT 'Authorized?',
  `to_id` int(11) DEFAULT NULL COMMENT 'To',
  `from_id` int(11) DEFAULT NULL COMMENT 'From',
  `message_status` varchar(255) DEFAULT NULL COMMENT 'Message Status',
  `note_type` varchar(255) DEFAULT NULL COMMENT 'Message Type',
  `to_deleted` tinyint(1) DEFAULT NULL COMMENT 'Deleted to the user',
  `from_deleted` tinyint(1) DEFAULT NULL COMMENT 'Deleted from the source',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `enable` tinyint(1) DEFAULT NULL,
  `installed_version` varchar(20) DEFAULT NULL,
  `licensekey` varchar(255) DEFAULT NULL,
  `localkey` varchar(255) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=238 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules_data`
--

DROP TABLE IF EXISTS `modules_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `data` longtext,
  PRIMARY KEY (`id`),
  KEY `data` (`data`(767))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='this table if a convivnient table to store module related data';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notes` (
  `id` int(11) NOT NULL DEFAULT '0',
  `foreign_id` int(11) NOT NULL DEFAULT '0',
  `note` varchar(255) DEFAULT NULL,
  `owner` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `revision` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`owner`),
  KEY `foreign_id_2` (`foreign_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_log`
--

DROP TABLE IF EXISTS `notification_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_log` (
  `iLogId` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(7) NOT NULL,
  `pc_eid` int(11) unsigned DEFAULT NULL,
  `sms_gateway_type` varchar(50) NOT NULL,
  `smsgateway_info` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `email_sender` varchar(255) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `type` enum('SMS','Email') NOT NULL,
  `patient_info` text NOT NULL,
  `pc_eventDate` date NOT NULL,
  `pc_endDate` date NOT NULL,
  `pc_startTime` time NOT NULL,
  `pc_endTime` time NOT NULL,
  `dSentDateTime` datetime NOT NULL,
  PRIMARY KEY (`iLogId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `notification_settings`
--

DROP TABLE IF EXISTS `notification_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notification_settings` (
  `SettingsId` int(3) NOT NULL AUTO_INCREMENT,
  `Send_SMS_Before_Hours` int(3) NOT NULL,
  `Send_Email_Before_Hours` int(3) NOT NULL,
  `SMS_gateway_username` varchar(100) NOT NULL,
  `SMS_gateway_password` varchar(100) NOT NULL,
  `SMS_gateway_apikey` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`SettingsId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `office_notes`
--

DROP TABLE IF EXISTS `office_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `facility_id` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Office Notes';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient`
--

DROP TABLE IF EXISTS `patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient` (
  `pid` bigint(20) NOT NULL AUTO_INCREMENT,
  `pubpid` varchar(40) DEFAULT NULL COMMENT 'external reference id',
  `title` varchar(10) DEFAULT NULL COMMENT 'Title Mr. Sr.',
  `fname` varchar(60) DEFAULT NULL COMMENT 'first name',
  `mname` varchar(40) DEFAULT NULL COMMENT 'middle name',
  `lname` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL COMMENT 'last name',
  `sex` varchar(10) DEFAULT NULL COMMENT 'sex',
  `DOB` datetime DEFAULT NULL COMMENT 'day of birth',
  `marital_status` varchar(40) DEFAULT NULL COMMENT 'marital status',
  `SS` varchar(40) DEFAULT NULL COMMENT 'social security',
  `drivers_license` varchar(40) DEFAULT NULL COMMENT 'driver licence #',
  `drivers_license_state` varchar(40) DEFAULT NULL,
  `drivers_license_exp` date DEFAULT NULL,
  `provider` varchar(40) DEFAULT NULL COMMENT 'default provider',
  `pharmacy` varchar(40) DEFAULT NULL COMMENT 'default pharmacy',
  `hipaa_notice` varchar(40) DEFAULT NULL COMMENT 'HIPAA notice status',
  `race` varchar(40) DEFAULT NULL COMMENT 'race',
  `ethnicity` varchar(40) DEFAULT NULL COMMENT 'ethnicity',
  `language` varchar(10) DEFAULT NULL COMMENT 'language',
  `allow_leave_msg` tinyint(1) DEFAULT NULL,
  `allow_voice_msg` tinyint(1) DEFAULT NULL,
  `allow_mail_msg` tinyint(1) DEFAULT NULL,
  `allow_sms` tinyint(1) DEFAULT NULL,
  `allow_email` tinyint(1) DEFAULT NULL,
  `allow_immunization_registry` tinyint(1) DEFAULT NULL,
  `allow_immunization_info_sharing` tinyint(1) DEFAULT NULL,
  `allow_health_info_exchange` tinyint(1) DEFAULT NULL,
  `allow_patient_web_portal` tinyint(1) DEFAULT NULL,
  `occupation` varchar(40) DEFAULT NULL COMMENT 'patient occupation',
  `employer_name` varchar(40) DEFAULT NULL COMMENT 'employer name',
  `employer_address` varchar(40) DEFAULT NULL COMMENT 'employer address',
  `employer_city` varchar(40) DEFAULT NULL COMMENT 'employer city',
  `employer_state` varchar(40) DEFAULT NULL COMMENT 'employer state',
  `employer_country` varchar(40) DEFAULT NULL COMMENT 'employer country',
  `employer_postal_code` varchar(10) DEFAULT NULL COMMENT 'employer postal code',
  `rating` int(11) DEFAULT NULL COMMENT 'patient stars rating',
  `image` mediumtext COMMENT 'patient image base64 string',
  `qrcode` mediumtext COMMENT 'patient QRCode base64 string',
  `pubaccount` varchar(40) DEFAULT NULL COMMENT 'external reference account',
  `birth_place` varchar(150) DEFAULT NULL,
  `birth_multiple` tinyint(1) DEFAULT NULL,
  `birth_order` int(2) DEFAULT '1',
  `is_veteran` varchar(1) DEFAULT NULL,
  `deceased` varchar(1) DEFAULT NULL,
  `death_date` datetime DEFAULT NULL,
  `alias` varchar(80) DEFAULT NULL,
  `citizenship` varchar(80) DEFAULT NULL,
  `primary_facility` int(11) DEFAULT NULL,
  `primary_provider` int(11) DEFAULT NULL,
  `administrative_status` varchar(15) DEFAULT NULL COMMENT 'active | inactive | merged',
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `portal_password` blob,
  `portal_username` varchar(40) DEFAULT NULL,
  `phone_publicity` varchar(10) DEFAULT NULL,
  `phone_home` varchar(25) DEFAULT NULL,
  `phone_mobile` varchar(25) DEFAULT NULL,
  `phone_work` varchar(25) DEFAULT NULL,
  `phone_work_ext` varchar(25) DEFAULT NULL,
  `phone_fax` varchar(25) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `postal_address` varchar(40) DEFAULT NULL,
  `postal_address_cont` varchar(40) DEFAULT NULL,
  `postal_city` varchar(35) DEFAULT NULL,
  `postal_state` varchar(35) DEFAULT NULL,
  `postal_zip` varchar(35) DEFAULT NULL,
  `physical_address` varchar(40) DEFAULT NULL,
  `physical_address_cont` varchar(40) DEFAULT NULL,
  `physical_city` varchar(35) DEFAULT NULL,
  `physical_state` varchar(35) DEFAULT NULL,
  `physical_zip` varchar(35) DEFAULT NULL,
  `guardians_relation` varchar(20) DEFAULT NULL,
  `guardians_fname` varchar(80) DEFAULT NULL,
  `guardians_mname` varchar(80) DEFAULT NULL,
  `guardians_lname` varchar(80) DEFAULT NULL,
  `guardians_phone` varchar(20) DEFAULT NULL,
  `guardians_phone_type` varchar(10) DEFAULT NULL,
  `emergency_contact_relation` varchar(20) DEFAULT NULL,
  `emergency_contact_fname` varchar(80) DEFAULT NULL,
  `emergency_contact_mname` varchar(80) DEFAULT NULL,
  `emergency_contact_lname` varchar(80) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `emergency_contact_phone_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`pid`),
  KEY `fname` (`fname`),
  KEY `mname` (`mname`),
  KEY `lname` (`lname`),
  KEY `sex` (`sex`),
  KEY `DOB` (`DOB`),
  KEY `SS` (`SS`),
  KEY `pubpid` (`pubpid`),
  KEY `drivers_license` (`drivers_license`),
  KEY `LiveSearchIndex` (`pid`,`pubpid`,`fname`,`mname`,`lname`,`SS`),
  KEY `pubaccount` (`pubaccount`),
  KEY `IK_DOB` (`DOB`)
) ENGINE=InnoDB AUTO_INCREMENT=9572 DEFAULT CHARSET=utf8 COMMENT='Patients/Demographics';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_account`
--

DROP TABLE IF EXISTS `patient_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entity that maintains patient''s account transactions ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_active_problems`
--

DROP TABLE IF EXISTS `patient_active_problems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_active_problems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `code` varchar(80) DEFAULT NULL,
  `code_text` varchar(300) DEFAULT NULL,
  `code_type` varchar(20) DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `occurrence` varchar(255) DEFAULT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `status_code` varchar(20) DEFAULT NULL,
  `status_code_type` varchar(20) DEFAULT NULL,
  `note` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_advance_directives`
--

DROP TABLE IF EXISTS `patient_advance_directives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_advance_directives` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `code` varchar(80) DEFAULT NULL,
  `code_text` varchar(160) DEFAULT NULL,
  `code_type` varchar(20) DEFAULT NULL,
  `status_code` varchar(80) DEFAULT NULL,
  `status_code_text` varchar(160) DEFAULT NULL,
  `status_code_type` varchar(20) DEFAULT NULL,
  `notes` varchar(300) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `verified_date` datetime DEFAULT NULL,
  `verified_uid` int(11) DEFAULT NULL,
  `created_uid` int(11) DEFAULT NULL,
  `updated_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_eid` (`eid`),
  KEY `IK_pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_allergies`
--

DROP TABLE IF EXISTS `patient_allergies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_allergies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `allergy` varchar(80) DEFAULT NULL,
  `allergy_code` varchar(20) DEFAULT NULL COMMENT 'RxNORM RXCUI code if food allergy',
  `allergy_code_type` varchar(20) DEFAULT NULL,
  `allergy_type` varchar(80) DEFAULT NULL,
  `allergy_type_code` varchar(20) DEFAULT NULL,
  `allergy_type_code_type` varchar(20) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `status_code` varchar(20) DEFAULT NULL,
  `status_code_type` varchar(20) DEFAULT NULL,
  `severity` varchar(80) DEFAULT NULL,
  `severity_code` varchar(20) DEFAULT NULL,
  `severity_code_type` varchar(20) DEFAULT NULL,
  `reaction` varchar(80) DEFAULT NULL,
  `reaction_code` varchar(20) DEFAULT NULL,
  `reaction_code_type` varchar(20) DEFAULT NULL,
  `location` varchar(80) DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_amendments`
--

DROP TABLE IF EXISTS `patient_amendments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_amendments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `portal_id` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `assigned_to_uid` int(11) DEFAULT NULL,
  `response_uid` int(11) DEFAULT NULL,
  `approved_by` varchar(80) DEFAULT NULL,
  `amendment_type` varchar(1) DEFAULT NULL COMMENT 'P = patient or D = Doctor or O = organization',
  `amendment_data` mediumtext,
  `amendment_message` text,
  `amendment_status` varchar(1) DEFAULT NULL COMMENT 'W = waiting or A = approved or D = denied or C = canceled',
  `response_message` varchar(500) DEFAULT NULL COMMENT 'denial or approval reason',
  `assigned_date` datetime DEFAULT NULL COMMENT 'Assigned date',
  `response_date` datetime DEFAULT NULL COMMENT 'create date',
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `cancel_date` datetime DEFAULT NULL COMMENT 'create date',
  `cancel_by` varchar(15) DEFAULT NULL COMMENT 'U for user P patient and ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `is_read` tinyint(1) DEFAULT NULL,
  `is_viewed` tinyint(1) DEFAULT NULL,
  `is_synced` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_appointment_requests`
--

DROP TABLE IF EXISTS `patient_appointment_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_appointment_requests` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `requested_uid` int(11) DEFAULT NULL,
  `approved_uid` int(11) DEFAULT NULL,
  `requested_date` date DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `procedure1_code` varchar(10) DEFAULT NULL,
  `procedure1_code_type` varchar(10) DEFAULT NULL,
  `procedure2_code` varchar(10) DEFAULT NULL,
  `procedure2_code_type` varchar(10) DEFAULT NULL,
  `procedure3_code` varchar(10) DEFAULT NULL,
  `procedure3_code_type` varchar(10) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_care_plan_goals`
--

DROP TABLE IF EXISTS `patient_care_plan_goals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_care_plan_goals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `goal` varchar(300) DEFAULT NULL,
  `goal_code` varchar(20) DEFAULT NULL,
  `goal_code_type` varchar(15) DEFAULT NULL,
  `instructions` varchar(500) DEFAULT NULL,
  `plan_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Care Plan Goals';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_chart_checkout`
--

DROP TABLE IF EXISTS `patient_chart_checkout`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_chart_checkout` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `chart_out_time` datetime DEFAULT NULL,
  `chart_in_time` datetime DEFAULT NULL,
  `pool_area_id` int(11) DEFAULT NULL,
  `read_only` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2018 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_cognitive_functional_status`
--

DROP TABLE IF EXISTS `patient_cognitive_functional_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_cognitive_functional_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `category_code` varchar(20) DEFAULT NULL,
  `category_code_type` varchar(20) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `code_text` varchar(300) DEFAULT NULL,
  `code_type` varchar(15) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `status_code` varchar(40) DEFAULT NULL,
  `status_code_type` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Cognitive Functional Status';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_contacts`
--

DROP TABLE IF EXISTS `patient_contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `relationship` varchar(20) NOT NULL,
  `street_mailing_address` varchar(200) DEFAULT NULL,
  `city` varchar(70) DEFAULT NULL,
  `state` varchar(70) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `phone_use_code` varchar(17) DEFAULT NULL,
  `phone_area_code` varchar(17) DEFAULT NULL,
  `phone_local_number` varchar(17) DEFAULT NULL,
  `contact_role` varchar(17) DEFAULT NULL,
  `publicity` varchar(3) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Patient Contacts';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_disclosures`
--

DROP TABLE IF EXISTS `patient_disclosures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_disclosures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL COMMENT 'user ID',
  `date` datetime NOT NULL,
  `type` varchar(25) DEFAULT NULL,
  `recipient` varchar(25) DEFAULT NULL,
  `description` text,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_doctors_notes`
--

DROP TABLE IF EXISTS `patient_doctors_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_doctors_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `restrictions` mediumtext,
  `comments` text,
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_documents`
--

DROP TABLE IF EXISTS `patient_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_documents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(120) DEFAULT NULL COMMENT 'external reference id',
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `docType` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT 'No title',
  `hash` varchar(255) DEFAULT NULL,
  `encrypted` tinyint(1) DEFAULT '0',
  `document` longblob,
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `docType` (`docType`),
  KEY `date` (`date`),
  KEY `IK_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_documents_temp`
--

DROP TABLE IF EXISTS `patient_documents_temp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_documents_temp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_date` datetime DEFAULT NULL,
  `document` longtext,
  `document_name` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Documents Temporary Storage';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_erx_messages`
--

DROP TABLE IF EXISTS `patient_erx_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_erx_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `updateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='patient erx messages';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_family_history`
--

DROP TABLE IF EXISTS `patient_family_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_family_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `condition` varchar(60) DEFAULT NULL,
  `condition_code` varchar(60) DEFAULT NULL,
  `condition_code_type` varchar(60) DEFAULT NULL,
  `relation` varchar(60) DEFAULT NULL,
  `relation_code` varchar(60) DEFAULT NULL,
  `relation_code_type` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_images`
--

DROP TABLE IF EXISTS `patient_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_images` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `notes` text,
  `image` mediumtext,
  `drawing` mediumtext,
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`),
  KEY `create_uid` (`create_uid`),
  KEY `update_uid` (`update_uid`),
  KEY `create_date` (`create_date`),
  KEY `update_date` (`update_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='patient images added in the image form panel';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_immunizations`
--

DROP TABLE IF EXISTS `patient_immunizations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_immunizations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL COMMENT 'vaccine code (CVX)',
  `code_type` varchar(15) DEFAULT NULL,
  `vaccine_name` varchar(300) DEFAULT NULL,
  `lot_number` varchar(60) DEFAULT NULL,
  `administer_amount` varchar(40) DEFAULT NULL,
  `administer_units` varchar(40) DEFAULT NULL,
  `administered_date` datetime DEFAULT NULL,
  `administered_uid` int(11) DEFAULT NULL,
  `manufacturer` varchar(180) DEFAULT NULL,
  `education_date` date DEFAULT NULL,
  `education_doc_published` date DEFAULT NULL,
  `note` varchar(300) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `exp_date` date DEFAULT NULL,
  `administration_site` varchar(40) DEFAULT NULL,
  `route` varchar(40) DEFAULT NULL,
  `is_error` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_insurance_covers`
--

DROP TABLE IF EXISTS `patient_insurance_covers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_insurance_covers` (
  `id` varchar(60) NOT NULL,
  `insurance_id` int(11) DEFAULT NULL,
  `cover_radiology` varchar(40) DEFAULT NULL,
  `cover_medical` varchar(40) DEFAULT NULL,
  `cover_dental` varchar(40) DEFAULT NULL,
  `cover_emergency` varchar(40) DEFAULT NULL,
  `cover_inpatient` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_cover_radiology` (`cover_radiology`),
  KEY `IK_cover_medical` (`cover_medical`),
  KEY `IK_cover_dental` (`cover_dental`),
  KEY `IK_cover_emergency` (`cover_emergency`),
  KEY `IK_cover_inpatient` (`cover_inpatient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_insurances`
--

DROP TABLE IF EXISTS `patient_insurances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_insurances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(40) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `insurance_id` int(11) DEFAULT NULL,
  `insurance_type` varchar(1) DEFAULT NULL COMMENT 'P = primary S = supplemental C =complementary D = Disable',
  `effective_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `group_number` varchar(40) DEFAULT NULL COMMENT 'group number',
  `policy_number` varchar(40) DEFAULT NULL,
  `cover_medical` varchar(10) DEFAULT NULL,
  `cover_dental` varchar(10) DEFAULT NULL,
  `subscriber_title` varchar(10) NOT NULL,
  `subscriber_given_name` varchar(80) DEFAULT NULL,
  `subscriber_middle_name` varchar(80) DEFAULT NULL,
  `subscriber_surname` varchar(80) DEFAULT NULL,
  `subscriber_relationship` varchar(40) DEFAULT NULL,
  `subscriber_sex` varchar(1) DEFAULT NULL,
  `subscriber_dob` date DEFAULT NULL,
  `subscriber_ss` varchar(10) DEFAULT NULL,
  `subscriber_street` varchar(80) DEFAULT NULL,
  `subscriber_city` varchar(80) DEFAULT NULL,
  `subscriber_state` varchar(80) DEFAULT NULL,
  `subscriber_country` varchar(80) DEFAULT NULL,
  `subscriber_postal_code` varchar(20) DEFAULT NULL,
  `subscriber_phone` varchar(20) NOT NULL,
  `subscriber_employer` varchar(80) DEFAULT NULL,
  `display_order` tinyint(3) unsigned DEFAULT NULL,
  `notes` varchar(320) DEFAULT NULL,
  `image` mediumtext COMMENT 'insurance image base64 string',
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `IK_code` (`code`),
  KEY `IK_insurance_id` (`insurance_id`),
  KEY `IK_insurance_type` (`insurance_type`),
  KEY `IK_cover_medical` (`cover_medical`),
  KEY `IK_cover_dental` (`cover_dental`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_labs`
--

DROP TABLE IF EXISTS `patient_labs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_labs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `auth_uid` bigint(20) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_labs_results`
--

DROP TABLE IF EXISTS `patient_labs_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_labs_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_lab_id` int(11) DEFAULT NULL,
  `observation_loinc` varchar(255) DEFAULT NULL,
  `observation_value` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_medications`
--

DROP TABLE IF EXISTS `patient_medications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_medications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `ref_order` varchar(100) DEFAULT NULL COMMENT 'reference order number',
  `STR` varchar(180) DEFAULT NULL,
  `CODE` varchar(40) DEFAULT NULL,
  `RXCUI` varchar(40) DEFAULT NULL,
  `NDC` varchar(40) DEFAULT NULL,
  `dxs` mediumtext,
  `route` varchar(80) DEFAULT NULL,
  `dispense` varchar(80) DEFAULT NULL,
  `dose` varchar(180) DEFAULT NULL,
  `form` varchar(80) DEFAULT NULL,
  `directions` varchar(255) DEFAULT NULL,
  `refill` varchar(80) DEFAULT NULL,
  `potency_code` varchar(10) DEFAULT NULL,
  `days_supply` int(11) DEFAULT NULL,
  `daw` tinyint(1) DEFAULT NULL COMMENT 'Dispensed As Written',
  `notes` varchar(210) DEFAULT NULL,
  `administered_uid` int(11) DEFAULT NULL,
  `administered_date` datetime DEFAULT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `referred_by` varchar(180) DEFAULT NULL,
  `date_ordered` date DEFAULT NULL,
  `begin_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `is_compound` tinyint(1) DEFAULT NULL,
  `is_supply` tinyint(1) DEFAULT NULL,
  `system_notes` varchar(210) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_notes`
--

DROP TABLE IF EXISTS `patient_notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `body` varchar(600) DEFAULT NULL,
  `type` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`),
  KEY `uid` (`uid`),
  KEY `date` (`date`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_order_results`
--

DROP TABLE IF EXISTS `patient_order_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_order_results` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `ordered_uid` int(11) DEFAULT NULL,
  `signed_uid` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL COMMENT 'OBR-2',
  `code` varchar(40) DEFAULT NULL,
  `code_text` varchar(150) DEFAULT NULL,
  `code_type` varchar(20) DEFAULT NULL,
  `lab_order_id` varchar(50) DEFAULT NULL COMMENT 'OBR-3',
  `lab_name` varchar(150) DEFAULT NULL,
  `lab_address` varchar(200) DEFAULT NULL,
  `result_date` date DEFAULT NULL,
  `observation_date` date DEFAULT NULL,
  `result_status` varchar(40) DEFAULT NULL,
  `specimen_code` varchar(40) DEFAULT NULL,
  `specimen_text` varchar(180) DEFAULT NULL,
  `specimen_code_type` varchar(40) DEFAULT NULL,
  `specimen_notes` varchar(255) DEFAULT NULL,
  `reason_code` varchar(40) DEFAULT NULL,
  `documentId` varchar(40) DEFAULT NULL COMMENT 'this is the document or hl7 message id - example -> doc|123 or hl7|123',
  `create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `lab_order_id` (`lab_order_id`),
  KEY `result_date` (`result_date`),
  KEY `observation_date` (`observation_date`),
  KEY `IK_create_date` (`create_date`),
  KEY `IK_ordered_uid` (`ordered_uid`),
  KEY `IK_signed_uid` (`signed_uid`),
  KEY `IK_pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patients Results OBR';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_order_results_observations`
--

DROP TABLE IF EXISTS `patient_order_results_observations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_order_results_observations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `result_id` int(11) DEFAULT NULL COMMENT 'Order ID',
  `code` varchar(255) DEFAULT NULL COMMENT 'OBX 3',
  `code_text` varchar(255) DEFAULT NULL COMMENT 'OBX 3',
  `code_type` varchar(255) DEFAULT NULL COMMENT 'OBX 3',
  `value` varchar(255) DEFAULT NULL COMMENT 'OBX 5',
  `units` varchar(255) DEFAULT NULL COMMENT 'OBX 6',
  `reference_rage` varchar(255) DEFAULT NULL COMMENT 'OBX 7',
  `probability` varchar(255) DEFAULT NULL COMMENT 'OBX 9',
  `abnormal_flag` varchar(255) DEFAULT NULL COMMENT 'OBX 8',
  `nature_of_abnormal` varchar(255) DEFAULT NULL COMMENT 'OBX 10',
  `observation_result_status` varchar(255) DEFAULT NULL COMMENT 'OBX 11',
  `date_rage_values` datetime DEFAULT NULL COMMENT 'OBX 12 Effective Date of Reference Range Values',
  `date_observation` datetime DEFAULT NULL COMMENT 'OBX 14',
  `observer` varchar(255) DEFAULT NULL COMMENT 'OBX 16',
  `date_analysis` datetime DEFAULT NULL COMMENT 'OBX 19',
  `notes` varchar(255) DEFAULT NULL COMMENT 'OBX NTE segments',
  `performing_org_name` varchar(255) DEFAULT NULL COMMENT 'OBX 23',
  `performing_org_address` varchar(255) DEFAULT NULL COMMENT 'OBX 24',
  PRIMARY KEY (`id`),
  KEY `result_id` (`result_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Order Result Observations OBX';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_orders`
--

DROP TABLE IF EXISTS `patient_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `eid` int(11) DEFAULT NULL COMMENT 'encounter id',
  `uid` int(11) DEFAULT NULL COMMENT 'user ID who created the order',
  `order_type` varchar(255) DEFAULT NULL COMMENT 'rad || lab',
  `code` varchar(25) DEFAULT NULL COMMENT 'Order code',
  `description` varchar(255) DEFAULT NULL COMMENT 'Order Text Description',
  `code_type` varchar(15) DEFAULT NULL COMMENT 'Order code type LOINC',
  `date_ordered` datetime DEFAULT NULL COMMENT 'when the order was generated',
  `date_collected` datetime DEFAULT NULL COMMENT 'when the results were collected',
  `priority` varchar(25) DEFAULT NULL COMMENT 'order priority',
  `status` varchar(25) DEFAULT NULL COMMENT 'order status',
  `note` varchar(255) DEFAULT NULL,
  `hl7_recipient_id` int(11) DEFAULT NULL COMMENT 'laboratory id if electronic request',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`),
  KEY `order_type` (`order_type`),
  KEY `date_ordered` (`date_ordered`),
  KEY `date_collected` (`date_collected`),
  KEY `priority` (`priority`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_pools`
--

DROP TABLE IF EXISTS `patient_pools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_pools` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL COMMENT 'user id that is treating the patient',
  `eid` bigint(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `time_in` datetime DEFAULT NULL COMMENT 'checkin time',
  `time_out` datetime DEFAULT NULL COMMENT 'checkout time',
  `area_id` int(11) DEFAULT NULL COMMENT 'pool area id',
  `priority` varchar(15) DEFAULT NULL,
  `in_queue` tinyint(1) DEFAULT NULL,
  `checkout_timer` time DEFAULT NULL COMMENT 'timer user to automatically checkout from the pool area, and return to the previous pool area ',
  `parent_id` int(11) DEFAULT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_appointment_id` (`appointment_id`),
  KEY `IK_parent_id` (`parent_id`),
  KEY `IK_provider_id` (`provider_id`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_prescriptions`
--

DROP TABLE IF EXISTS `patient_prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_prescriptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `document_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_referrals`
--

DROP TABLE IF EXISTS `patient_referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_referrals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'encounter id',
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `refer_by` varchar(80) DEFAULT NULL,
  `refer_to` varchar(80) DEFAULT NULL,
  `refer_by_text` varchar(120) DEFAULT NULL,
  `refer_to_text` varchar(120) DEFAULT NULL,
  `referral_date` date DEFAULT NULL,
  `referal_reason` varchar(1000) DEFAULT NULL,
  `service_text` varchar(300) DEFAULT NULL,
  `service_code` varchar(10) DEFAULT NULL,
  `service_code_type` varchar(10) DEFAULT NULL COMMENT 'CPT SNOMED',
  `diagnosis_text` varchar(300) DEFAULT NULL,
  `diagnosis_code` varchar(10) DEFAULT NULL,
  `diagnosis_code_type` varchar(10) DEFAULT NULL,
  `is_external_referral` tinyint(1) DEFAULT NULL,
  `risk_level` varchar(20) DEFAULT NULL,
  `send_record` tinyint(1) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL COMMENT 'user ID who created the referral',
  `update_uid` int(11) DEFAULT NULL COMMENT 'user ID who updated the referral',
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patients Referrals';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_reminders`
--

DROP TABLE IF EXISTS `patient_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_reminders` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `body` varchar(600) DEFAULT NULL,
  `type` varchar(80) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `uid` (`uid`),
  KEY `date` (`date`),
  KEY `eid` (`eid`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_smoke_status`
--

DROP TABLE IF EXISTS `patient_smoke_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_smoke_status` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'encounter id',
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `status` varchar(80) DEFAULT NULL,
  `status_code` varchar(20) DEFAULT NULL,
  `status_code_type` varchar(20) DEFAULT NULL,
  `counseling` tinyint(1) DEFAULT NULL COMMENT '1 if counseling received',
  `note` text,
  `create_uid` int(11) DEFAULT NULL COMMENT 'user ID who created the record',
  `update_uid` int(11) DEFAULT NULL COMMENT 'user ID who updated the record',
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Patient Smoke status';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_social_history`
--

DROP TABLE IF EXISTS `patient_social_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_social_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'encounter id',
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `category_code` varchar(25) DEFAULT NULL,
  `category_code_type` varchar(20) DEFAULT NULL,
  `category_code_text` varchar(120) DEFAULT NULL,
  `observation` varchar(400) DEFAULT NULL COMMENT 'clinical observation for this history',
  `observation_code` varchar(20) DEFAULT NULL,
  `observation_code_type` varchar(20) DEFAULT NULL,
  `note` text,
  `start_date` datetime DEFAULT NULL COMMENT 'same as CCD low time',
  `end_date` datetime DEFAULT NULL COMMENT 'same as CCD high time',
  `create_uid` int(11) DEFAULT NULL COMMENT 'user ID who created the record',
  `update_uid` int(11) DEFAULT NULL COMMENT 'user ID who updated the record',
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Social History';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_surgery`
--

DROP TABLE IF EXISTS `patient_surgery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_surgery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `surgery` varchar(255) DEFAULT NULL,
  `surgery_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `outcome` varchar(50) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patient_zone`
--

DROP TABLE IF EXISTS `patient_zone`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patient_zone` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `uid` int(11) DEFAULT NULL COMMENT 'user ID who assigned the patient to this zone',
  `zone_id` int(11) DEFAULT NULL,
  `time_in` datetime DEFAULT NULL COMMENT 'patient in time',
  `time_out` datetime DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_zone_id` (`zone_id`),
  KEY `IK_time_out` (`time_out`),
  KEY `IK_pid` (`pid`),
  KEY `IK_provider_id` (`provider_id`),
  KEY `IK_pid_timeout` (`pid`,`time_out`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payment_transactions`
--

DROP TABLE IF EXISTS `payment_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_transactions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `paying_entity` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `payer_id` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `pay_to` varchar(255) CHARACTER SET latin1 COLLATE latin1_bin DEFAULT NULL,
  `post_to_date` datetime DEFAULT NULL,
  `check_number` varchar(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entity that maintains patient''s account transactions ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `dtime` datetime NOT NULL,
  `encounter` bigint(20) NOT NULL DEFAULT '0',
  `user` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `amount1` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount2` decimal(12,2) NOT NULL DEFAULT '0.00',
  `posted1` decimal(12,2) NOT NULL DEFAULT '0.00',
  `posted2` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pharmacies`
--

DROP TABLE IF EXISTS `pharmacies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pharmacies` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `transmit_method` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `phone_id` int(11) DEFAULT NULL,
  `fax_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `phone_numbers`
--

DROP TABLE IF EXISTS `phone_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phone_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(5) DEFAULT NULL,
  `area_code` char(3) DEFAULT NULL,
  `prefix` char(3) DEFAULT NULL,
  `number` varchar(4) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `phones`
--

DROP TABLE IF EXISTS `phones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `write_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `country_code` varchar(255) DEFAULT NULL,
  `area_code` varchar(5) DEFAULT NULL,
  `prefix` varchar(5) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `number_type` varchar(255) DEFAULT NULL,
  `foreign_type` varchar(255) DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User/Contacts phones';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pnotes`
--

DROP TABLE IF EXISTS `pnotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pnotes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `to_id` bigint(20) NOT NULL COMMENT 'user ID receiving the message',
  `from_id` bigint(20) NOT NULL COMMENT 'user ID sending the message',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `body` longtext,
  `pid` bigint(20) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `authorized` tinyint(4) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT '0' COMMENT 'flag indicates note is deleted',
  `to_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `from_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `message_status` varchar(20) NOT NULL DEFAULT 'New',
  `subject` varchar(254) DEFAULT NULL,
  `note_type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pool_areas`
--

DROP TABLE IF EXISTS `pool_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pool_areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) DEFAULT NULL,
  `floor_plan_id` bigint(20) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prescriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `filled_by_id` int(11) DEFAULT NULL,
  `pharmacy_id` int(11) DEFAULT NULL,
  `date_added` date DEFAULT NULL,
  `date_modified` date DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `drug` varchar(150) DEFAULT NULL,
  `drug_id` int(11) NOT NULL DEFAULT '0',
  `form` int(3) DEFAULT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `quantity` varchar(31) DEFAULT NULL,
  `size` float unsigned DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `route` int(11) DEFAULT NULL,
  `interval` int(11) DEFAULT NULL,
  `substitute` int(11) DEFAULT NULL,
  `refills` int(11) DEFAULT NULL,
  `per_refill` int(11) DEFAULT NULL,
  `filled_date` date DEFAULT NULL,
  `medication` int(11) DEFAULT NULL,
  `note` text,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preventive_care_guidelines`
--

DROP TABLE IF EXISTS `preventive_care_guidelines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preventive_care_guidelines` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `concept_id` bigint(20) DEFAULT NULL,
  `description` text,
  `age_start` text,
  `age_end` text,
  `sex` text,
  `pregnant` tinyint(1) NOT NULL DEFAULT '0',
  `frequency` text,
  `category_id` text,
  `code` text,
  `coding_system` text,
  `frequency_type` text,
  `times_to_perform` text,
  `active_problems` text,
  `medications` text,
  `doc_url1` text,
  `doc_url2` text,
  `doc_url3` text,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `preventive_care_inactive_patient`
--

DROP TABLE IF EXISTS `preventive_care_inactive_patient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preventive_care_inactive_patient` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) DEFAULT NULL,
  `codeType` int(10) DEFAULT NULL,
  `insuranceCompanyId` bigint(20) DEFAULT NULL,
  `price` decimal(19,2) NOT NULL DEFAULT '0.00' COMMENT 'price in local currency',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procedure_order`
--

DROP TABLE IF EXISTS `procedure_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedure_order` (
  `procedure_order_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `procedure_type_id` bigint(20) NOT NULL COMMENT 'references procedure_type.procedure_type_id',
  `provider_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references users.id',
  `patient_id` bigint(20) NOT NULL COMMENT 'references patient_data.pid',
  `encounter_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references form_encounter.encounter',
  `date_collected` datetime DEFAULT NULL COMMENT 'time specimen collected',
  `date_ordered` date DEFAULT NULL,
  `order_priority` varchar(31) NOT NULL DEFAULT '',
  `order_status` varchar(31) NOT NULL DEFAULT '' COMMENT 'pending,routed,complete,canceled',
  `patient_instructions` text NOT NULL,
  `activity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 if deleted',
  `control_id` bigint(20) NOT NULL COMMENT 'This is the CONTROL ID that is sent back from lab',
  PRIMARY KEY (`procedure_order_id`),
  KEY `datepid` (`date_ordered`,`patient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procedure_report`
--

DROP TABLE IF EXISTS `procedure_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedure_report` (
  `procedure_report_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `procedure_order_id` bigint(20) DEFAULT NULL COMMENT 'references procedure_order.procedure_order_id',
  `date_collected` datetime DEFAULT NULL,
  `date_report` date DEFAULT NULL,
  `source` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references users.id, who entered this data',
  `specimen_num` varchar(63) NOT NULL DEFAULT '',
  `report_status` varchar(31) NOT NULL DEFAULT '' COMMENT 'received,complete,error',
  `review_status` varchar(31) NOT NULL DEFAULT 'received' COMMENT 'panding reivew status: received,reviewed',
  PRIMARY KEY (`procedure_report_id`),
  KEY `procedure_order_id` (`procedure_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procedure_result`
--

DROP TABLE IF EXISTS `procedure_result`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedure_result` (
  `procedure_result_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `procedure_report_id` bigint(20) NOT NULL COMMENT 'references procedure_report.procedure_report_id',
  `procedure_type_id` bigint(20) NOT NULL COMMENT 'references procedure_type.procedure_type_id',
  `date` datetime DEFAULT NULL COMMENT 'lab-provided date specific to this result',
  `facility` varchar(255) NOT NULL DEFAULT '' COMMENT 'lab-provided testing facility ID',
  `units` varchar(31) NOT NULL DEFAULT '',
  `result` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `abnormal` varchar(31) NOT NULL DEFAULT '' COMMENT 'no,yes,high,low',
  `comments` text NOT NULL COMMENT 'comments from the lab',
  `document_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references documents.id if this result is a document',
  `result_status` varchar(31) NOT NULL DEFAULT '' COMMENT 'preliminary, cannot be done, final, corrected, incompete...etc.',
  PRIMARY KEY (`procedure_result_id`),
  KEY `procedure_report_id` (`procedure_report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procedure_type`
--

DROP TABLE IF EXISTS `procedure_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedure_type` (
  `procedure_type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references procedure_type.procedure_type_id',
  `name` varchar(63) NOT NULL DEFAULT '' COMMENT 'name for this category, procedure or result type',
  `lab_id` bigint(20) NOT NULL DEFAULT '0' COMMENT 'references users.id, 0 means default to parent',
  `procedure_code` varchar(31) NOT NULL DEFAULT '' COMMENT 'code identifying this procedure',
  `procedure_type` varchar(31) NOT NULL DEFAULT '' COMMENT 'see list proc_type',
  `body_site` varchar(31) NOT NULL DEFAULT '' COMMENT 'where to do injection, e.g. arm, buttok',
  `specimen` varchar(31) NOT NULL DEFAULT '' COMMENT 'blood, urine, saliva, etc.',
  `route_admin` varchar(31) NOT NULL DEFAULT '' COMMENT 'oral, injection',
  `laterality` varchar(31) NOT NULL DEFAULT '' COMMENT 'left, right, ...',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'descriptive text for procedure_code',
  `standard_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'industry standard code type and code (e.g. CPT4:12345)',
  `related_code` varchar(255) NOT NULL DEFAULT '' COMMENT 'suggested code(s) for followup services if result is abnormal',
  `units` varchar(31) NOT NULL DEFAULT '' COMMENT 'default for procedure_result.units',
  `range` varchar(255) NOT NULL DEFAULT '' COMMENT 'default for procedure_result.range',
  `seq` int(11) NOT NULL DEFAULT '0' COMMENT 'sequence number for ordering',
  PRIMARY KEY (`procedure_type_id`),
  KEY `parent` (`parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `provider_credentializations`
--

DROP TABLE IF EXISTS `provider_credentializations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provider_credentializations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) DEFAULT NULL,
  `insurance_company_id` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `credentialization_notes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_provider_id` (`provider_id`),
  KEY `IK_insurance_company_id` (`insurance_company_id`),
  KEY `IK_start_date` (`start_date`),
  KEY `IK_end_date` (`end_date`)
) ENGINE=InnoDB AUTO_INCREMENT=391 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referring_providers`
--

DROP TABLE IF EXISTS `referring_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referring_providers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `title` varchar(10) DEFAULT NULL COMMENT 'title (Mr. Mrs.)',
  `fname` varchar(80) DEFAULT NULL COMMENT 'first name',
  `mname` varchar(80) DEFAULT NULL COMMENT 'middle name',
  `lname` varchar(120) DEFAULT NULL COMMENT 'last name',
  `upin` varchar(25) DEFAULT NULL COMMENT 'Carrier Claim Referring Physician UPIN Number',
  `lic` varchar(25) DEFAULT NULL COMMENT 'Licence Number',
  `npi` varchar(25) DEFAULT NULL COMMENT 'National Provider Identifier',
  `ssn` varchar(25) DEFAULT NULL COMMENT 'federal tax id',
  `taxonomy` varchar(40) DEFAULT NULL COMMENT 'taxonomy',
  `accept_mc` tinyint(1) DEFAULT NULL COMMENT 'Accepts Medicare',
  `notes` varchar(600) DEFAULT NULL,
  `email` varchar(180) DEFAULT NULL COMMENT 'email',
  `direct_address` varchar(180) DEFAULT NULL COMMENT 'direct_address',
  `phone_number` varchar(25) DEFAULT NULL COMMENT 'phone number',
  `fax_number` varchar(25) DEFAULT NULL COMMENT 'fax number',
  `cel_number` varchar(25) DEFAULT NULL COMMENT 'cell phone number',
  `active` tinyint(1) DEFAULT NULL,
  `code` varchar(40) DEFAULT NULL,
  `fda` varchar(25) DEFAULT NULL,
  `ess` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Referring Providers';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `referring_providers_facilities`
--

DROP TABLE IF EXISTS `referring_providers_facilities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referring_providers_facilities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `referring_provider_id` int(11) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `address` varchar(35) DEFAULT NULL,
  `address_cont` varchar(35) DEFAULT NULL,
  `city` varchar(35) DEFAULT NULL,
  `state` varchar(35) DEFAULT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `country` varchar(10) DEFAULT NULL,
  `taxonomy` varchar(40) DEFAULT NULL COMMENT 'taxonomy',
  `accept_mc` tinyint(1) DEFAULT NULL COMMENT 'Accepts Medicare',
  `email` varchar(180) DEFAULT NULL,
  `direct_address` varchar(180) DEFAULT NULL,
  `phone_number` varchar(25) DEFAULT NULL,
  `fax_number` varchar(25) DEFAULT NULL,
  `notes` varchar(600) DEFAULT NULL,
  `is_default` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `update_uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_referring_provider_id` (`referring_provider_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxinstructions`
--

DROP TABLE IF EXISTS `rxinstructions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxinstructions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rxcui` varchar(255) DEFAULT NULL,
  `occurrence` int(11) DEFAULT NULL,
  `instruction` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_rxcui` (`rxcui`),
  KEY `IK_occurrence` (`occurrence`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnatomarchive`
--

DROP TABLE IF EXISTS `rxnatomarchive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnatomarchive` (
  `RXAUI` varchar(8) NOT NULL,
  `AUI` varchar(10) DEFAULT NULL,
  `STR` varchar(4000) NOT NULL,
  `ARCHIVE_TIMESTAMP` varchar(280) NOT NULL,
  `CREATED_TIMESTAMP` varchar(280) NOT NULL,
  `UPDATED_TIMESTAMP` varchar(280) NOT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `IS_BRAND` varchar(1) DEFAULT NULL,
  `LAT` varchar(3) DEFAULT NULL,
  `LAST_RELEASED` varchar(30) DEFAULT NULL,
  `SAUI` varchar(50) DEFAULT NULL,
  `VSAB` varchar(40) DEFAULT NULL,
  `RXCUI` varchar(8) DEFAULT NULL,
  `SAB` varchar(20) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  `MERGED_TO_RXCUI` varchar(8) DEFAULT NULL,
  KEY `X_RXNATOMARCHIVE_RXAUI` (`RXAUI`),
  KEY `X_RXNATOMARCHIVE_RXCUI` (`RXCUI`),
  KEY `X_RXNATOMARCHIVE_MERGED_TO` (`MERGED_TO_RXCUI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnconso`
--

DROP TABLE IF EXISTS `rxnconso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnconso` (
  `RXCUI` varchar(8) NOT NULL,
  `LAT` varchar(3) NOT NULL DEFAULT 'ENG',
  `TS` varchar(1) DEFAULT NULL,
  `LUI` varchar(8) DEFAULT NULL,
  `STT` varchar(3) DEFAULT NULL,
  `SUI` varchar(8) DEFAULT NULL,
  `ISPREF` varchar(1) DEFAULT NULL,
  `RXAUI` varchar(8) NOT NULL,
  `SAUI` varchar(50) DEFAULT NULL,
  `SCUI` varchar(50) DEFAULT NULL,
  `SDUI` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) NOT NULL,
  `TTY` varchar(20) NOT NULL,
  `CODE` varchar(50) NOT NULL,
  `STR` varchar(3000) NOT NULL,
  `SRL` varchar(10) DEFAULT NULL,
  `SUPPRESS` varchar(1) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL,
  KEY `X_RXNCONSO_STR` (`STR`(255)),
  KEY `X_RXNCONSO_RXCUI` (`RXCUI`),
  KEY `X_RXNCONSO_TTY` (`TTY`),
  KEY `X_RXNCONSO_CODE` (`CODE`),
  KEY `X_RXNCONSO_SAB` (`SAB`) USING BTREE,
  KEY `X_RXNCONSO_SEARCH` (`STR`(255),`RXCUI`,`SAB`,`TTY`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxncui`
--

DROP TABLE IF EXISTS `rxncui`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxncui` (
  `cui1` varchar(8) DEFAULT NULL,
  `ver_start` varchar(40) DEFAULT NULL,
  `ver_end` varchar(40) DEFAULT NULL,
  `cardinality` varchar(8) DEFAULT NULL,
  `cui2` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxncuichanges`
--

DROP TABLE IF EXISTS `rxncuichanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxncuichanges` (
  `RXAUI` varchar(8) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  `STR` varchar(3000) DEFAULT NULL,
  `OLD_RXCUI` varchar(8) NOT NULL,
  `NEW_RXCUI` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxndoc`
--

DROP TABLE IF EXISTS `rxndoc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxndoc` (
  `DOCKEY` varchar(50) NOT NULL,
  `VALUE` varchar(1000) DEFAULT NULL,
  `TYPE` varchar(50) NOT NULL,
  `EXPL` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnrel`
--

DROP TABLE IF EXISTS `rxnrel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnrel` (
  `RXCUI1` varchar(8) DEFAULT NULL,
  `RXAUI1` varchar(8) DEFAULT NULL,
  `STYPE1` varchar(50) DEFAULT NULL,
  `REL` varchar(4) DEFAULT NULL,
  `RXCUI2` varchar(8) DEFAULT NULL,
  `RXAUI2` varchar(8) DEFAULT NULL,
  `STYPE2` varchar(50) DEFAULT NULL,
  `RELA` varchar(100) DEFAULT NULL,
  `RUI` varchar(10) DEFAULT NULL,
  `SRUI` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) NOT NULL,
  `SL` varchar(1000) DEFAULT NULL,
  `DIR` varchar(1) DEFAULT NULL,
  `RG` varchar(10) DEFAULT NULL,
  `SUPPRESS` varchar(1) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL,
  KEY `X_RXNREL_RXCUI1` (`RXCUI1`),
  KEY `X_RXNREL_RXCUI2` (`RXCUI2`),
  KEY `X_RXNREL_RELA` (`RELA`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnsab`
--

DROP TABLE IF EXISTS `rxnsab`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnsab` (
  `VCUI` varchar(8) DEFAULT NULL,
  `RCUI` varchar(8) DEFAULT NULL,
  `VSAB` varchar(40) DEFAULT NULL,
  `RSAB` varchar(20) NOT NULL,
  `SON` varchar(3000) DEFAULT NULL,
  `SF` varchar(20) DEFAULT NULL,
  `SVER` varchar(20) DEFAULT NULL,
  `VSTART` varchar(10) DEFAULT NULL,
  `VEND` varchar(10) DEFAULT NULL,
  `IMETA` varchar(10) DEFAULT NULL,
  `RMETA` varchar(10) DEFAULT NULL,
  `SLC` varchar(1000) DEFAULT NULL,
  `SCC` varchar(1000) DEFAULT NULL,
  `SRL` int(11) DEFAULT NULL,
  `TFR` int(11) DEFAULT NULL,
  `CFR` int(11) DEFAULT NULL,
  `CXTY` varchar(50) DEFAULT NULL,
  `TTYL` varchar(300) DEFAULT NULL,
  `ATNL` varchar(1000) DEFAULT NULL,
  `LAT` varchar(3) DEFAULT NULL,
  `CENC` varchar(20) DEFAULT NULL,
  `CURVER` varchar(1) DEFAULT NULL,
  `SABIN` varchar(1) DEFAULT NULL,
  `SSN` varchar(3000) DEFAULT NULL,
  `SCIT` varchar(4000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnsat`
--

DROP TABLE IF EXISTS `rxnsat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnsat` (
  `RXCUI` varchar(8) DEFAULT NULL,
  `LUI` varchar(8) DEFAULT NULL,
  `SUI` varchar(8) DEFAULT NULL,
  `RXAUI` varchar(8) DEFAULT NULL,
  `STYPE` varchar(50) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `ATUI` varchar(11) DEFAULT NULL,
  `SATUI` varchar(50) DEFAULT NULL,
  `ATN` varchar(1000) NOT NULL,
  `SAB` varchar(20) NOT NULL,
  `ATV` varchar(4000) DEFAULT NULL,
  `SUPPRESS` varchar(1) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL,
  KEY `X_RXNSAT_RXCUI` (`RXCUI`),
  KEY `X_RXNSAT_ATV` (`ATV`(255)),
  KEY `X_RXNSAT_ATN` (`ATN`(255)),
  KEY `X_RXNSAT_CODE` (`CODE`) USING BTREE,
  KEY `X_RXNSAT_SAB` (`SAB`) USING BTREE,
  KEY `X_RXNSAT_SEARCH` (`RXCUI`,`SAB`,`ATN`(255),`ATV`(255)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `rxnsty`
--

DROP TABLE IF EXISTS `rxnsty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rxnsty` (
  `RXCUI` varchar(8) NOT NULL,
  `TUI` varchar(4) DEFAULT NULL,
  `STN` varchar(100) DEFAULT NULL,
  `STY` varchar(50) DEFAULT NULL,
  `ATUI` varchar(11) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_concepts`
--

DROP TABLE IF EXISTS `sct_concepts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_concepts` (
  `ConceptId` bigint(20) NOT NULL,
  `ConceptStatus` int(11) NOT NULL,
  `FullySpecifiedName` varchar(255) NOT NULL,
  `CTV3ID` varchar(5) NOT NULL,
  `SNOMEDID` varchar(8) NOT NULL,
  `IsPrimitive` tinyint(1) NOT NULL,
  PRIMARY KEY (`ConceptId`),
  KEY `X_SNOMEDID` (`SNOMEDID`),
  KEY `X_FullySpecifiedName` (`FullySpecifiedName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_descriptions`
--

DROP TABLE IF EXISTS `sct_descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_descriptions` (
  `DescriptionId` bigint(20) NOT NULL,
  `DescriptionStatus` int(11) NOT NULL,
  `ConceptId` bigint(20) NOT NULL,
  `Term` varchar(255) NOT NULL,
  `InitialCapitalStatus` tinyint(1) NOT NULL,
  `DescriptionType` int(11) NOT NULL,
  `LanguageCode` varchar(8) NOT NULL,
  PRIMARY KEY (`DescriptionId`),
  KEY `X_ConceptId` (`ConceptId`),
  KEY `X_Term` (`Term`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_problem_list`
--

DROP TABLE IF EXISTS `sct_problem_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_problem_list` (
  `SNOMED_CID` varchar(15) NOT NULL,
  `SNOMED_FSN` varchar(255) NOT NULL,
  `SNOMED_CONCEPT_STATUS` varchar(20) NOT NULL,
  `UMLS_CUI` varchar(20) NOT NULL,
  `OCCURRENCE` varchar(20) NOT NULL,
  `CONCEPT_USAGE` varchar(20) NOT NULL,
  `FIRST_IN_SUBSET` varchar(20) NOT NULL,
  `IS_RETIRED_FROM_SUBSET` varchar(20) NOT NULL,
  `LAST_IN_SUBSET` varchar(20) NOT NULL,
  `REPLACED_BY_SNOMED_CID` varchar(20) NOT NULL,
  PRIMARY KEY (`SNOMED_CID`),
  KEY `X_SNOMED_FSN` (`SNOMED_FSN`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_procedure_list`
--

DROP TABLE IF EXISTS `sct_procedure_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_procedure_list` (
  `ConceptId` varchar(15) CHARACTER SET utf8 NOT NULL,
  `FullySpecifiedName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Occurrence` int(11) NOT NULL,
  PRIMARY KEY (`ConceptId`),
  KEY `X_FullySpecifiedName` (`FullySpecifiedName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_relationships`
--

DROP TABLE IF EXISTS `sct_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_relationships` (
  `RelationshipId` bigint(20) NOT NULL,
  `ConceptId1` bigint(20) NOT NULL,
  `RelationshipType` bigint(20) NOT NULL,
  `ConceptId2` bigint(20) NOT NULL,
  `CharacteristicType` int(11) NOT NULL,
  `Refinability` int(11) NOT NULL,
  `RelationshipGroup` int(11) NOT NULL,
  PRIMARY KEY (`RelationshipId`),
  KEY `X_ConceptId1` (`ConceptId1`),
  KEY `X_ConceptId2` (`ConceptId2`),
  KEY `X_RelationshipType` (`RelationshipType`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_relationships_icd10`
--

DROP TABLE IF EXISTS `sct_relationships_icd10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_relationships_icd10` (
  `TARGETID` varchar(15) NOT NULL,
  `TARGETSCHEMEID` varchar(50) NOT NULL,
  `TARGETCODES` varchar(50) NOT NULL,
  `TARGETRULE` varchar(50) NOT NULL,
  `TARGETADVICE` varchar(50) NOT NULL,
  KEY `X_TARGETID` (`TARGETID`),
  KEY `X_TARGETCODES` (`TARGETCODES`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_relationships_icd9`
--

DROP TABLE IF EXISTS `sct_relationships_icd9`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_relationships_icd9` (
  `TARGETID` varchar(15) NOT NULL,
  `TARGETSCHEMEID` varchar(50) NOT NULL,
  `TARGETCODES` varchar(50) NOT NULL,
  `TARGETRULE` varchar(50) NOT NULL,
  `TARGETADVICE` varchar(50) NOT NULL,
  KEY `X_TARGETID` (`TARGETID`),
  KEY `X_TARGETCODES` (`TARGETCODES`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sct_relationships_loinc`
--

DROP TABLE IF EXISTS `sct_relationships_loinc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sct_relationships_loinc` (
  `LoincNum` varchar(7) NOT NULL,
  `Component` varchar(150) NOT NULL,
  `Property` varchar(25) NOT NULL,
  `TimAspect` varchar(25) NOT NULL,
  `System` varchar(50) NOT NULL,
  `ScaleType` varchar(25) NOT NULL,
  `MethodType` varchar(60) NOT NULL,
  `RelationNms` varchar(256) NOT NULL,
  `AnswerList` varchar(256) NOT NULL,
  `RelationshipType` varchar(25) NOT NULL,
  `ConceptId` varchar(25) NOT NULL,
  KEY `X_LoincNum` (`LoincNum`),
  KEY `X_ConceptId` (`ConceptId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `soap_snippets`
--

DROP TABLE IF EXISTS `soap_snippets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `soap_snippets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parentId` varchar(20) DEFAULT NULL,
  `specialty_id` varchar(11) DEFAULT NULL,
  `index` int(11) DEFAULT NULL,
  `text` text,
  `category` varchar(50) DEFAULT NULL,
  `leaf` tinyint(1) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`parentId`),
  KEY `category` (`category`),
  KEY `IK_specialty_id` (`specialty_id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `source_organization`
--

DROP TABLE IF EXISTS `source_organization`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `source_organization` (
  `copyright_id` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `copyright` text,
  `terms_of_use` text,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`copyright_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `specialties`
--

DROP TABLE IF EXISTS `specialties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `taxonomy` varchar(30) DEFAULT NULL,
  `modality` varchar(50) DEFAULT NULL,
  `ges` varchar(5) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL,
  `update_uid` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `standardized_tables_track`
--

DROP TABLE IF EXISTS `standardized_tables_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `standardized_tables_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_type` varchar(50) DEFAULT NULL,
  `imported_date` datetime DEFAULT NULL,
  `revision_name` varchar(255) DEFAULT NULL COMMENT 'name of standardized tables such as RXNORM',
  `revision_number` varchar(255) DEFAULT NULL,
  `revision_version` varchar(255) DEFAULT NULL COMMENT 'revision of standardized tables that were imported',
  `revision_date` varchar(255) DEFAULT NULL COMMENT 'revision of standardized tables that were imported',
  `file_checksum` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `support_rule_concepts`
--

DROP TABLE IF EXISTS `support_rule_concepts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_rule_concepts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rule_id` int(11) DEFAULT NULL COMMENT 'support_rule.id',
  `concept_type` varchar(10) DEFAULT NULL COMMENT 'PROC PROB MEDI SOCI ALLE LAB VITA',
  `concept_text` varchar(25) DEFAULT NULL,
  `concept_code` varchar(25) DEFAULT NULL,
  `concept_code_type` varchar(10) DEFAULT NULL,
  `frequency` int(3) DEFAULT NULL,
  `frequency_interval` varchar(3) DEFAULT NULL COMMENT '1D = one day 2M = two month 1Y = one year',
  `frequency_operator` varchar(5) DEFAULT NULL COMMENT '== != <= >= < >',
  `value` varchar(10) DEFAULT NULL,
  `value_operator` varchar(5) DEFAULT NULL COMMENT '== != <= >= < >',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `support_rules`
--

DROP TABLE IF EXISTS `support_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_rules` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category` varchar(10) DEFAULT NULL COMMENT 'C = Clinical A = Administrative',
  `alert_type` varchar(2) DEFAULT NULL COMMENT 'A = Active P = Passive',
  `description` varchar(255) DEFAULT NULL,
  `service_type` varchar(10) DEFAULT NULL COMMENT 'PROC IMMU DX MEDI LAB RAD',
  `service_text` varchar(255) DEFAULT NULL,
  `service_code` varchar(25) DEFAULT NULL,
  `service_code_type` varchar(10) DEFAULT NULL,
  `age_start` int(11) DEFAULT '0',
  `age_end` int(11) DEFAULT '0',
  `sex` varchar(5) DEFAULT NULL,
  `warning` varchar(5) DEFAULT NULL COMMENT 'examples 1W or 5M or 1Y',
  `past_due` varchar(5) DEFAULT NULL COMMENT 'examples 1W or 5M or 1Y',
  `reference` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `surgeries`
--

DROP TABLE IF EXISTS `surgeries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `surgeries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `type_num` bigint(255) DEFAULT NULL,
  `surgery` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=369 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `template_panels`
--

DROP TABLE IF EXISTS `template_panels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_panels` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `specialty_id` int(11) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  `sex` varchar(1) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_sex` (`sex`),
  KEY `IK_specialty_id` (`specialty_id`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `template_panels_templates`
--

DROP TABLE IF EXISTS `template_panels_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `template_panels_templates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `panel_id` int(11) DEFAULT NULL,
  `template_type` varchar(80) DEFAULT NULL COMMENT 'rx lab rad etc',
  `description` varchar(300) DEFAULT NULL,
  `template_data` mediumtext,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_settings` (
  `setting_user` bigint(20) NOT NULL DEFAULT '0',
  `setting_label` varchar(63) NOT NULL,
  `setting_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`setting_user`,`setting_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(40) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL COMMENT 'acl_user_roles relation',
  `facility_id` int(11) DEFAULT NULL COMMENT 'default facility',
  `warehouse_id` int(11) DEFAULT NULL COMMENT 'default warehouse',
  `username` varchar(20) DEFAULT NULL COMMENT 'username',
  `password` blob COMMENT 'password',
  `pwd_history1` blob COMMENT 'first password history backwards',
  `pwd_history2` blob COMMENT 'second password history backwards',
  `title` varchar(10) DEFAULT NULL COMMENT 'title (Mr. Mrs.)',
  `fname` varchar(80) DEFAULT NULL COMMENT 'first name',
  `mname` varchar(80) DEFAULT NULL COMMENT 'middle name',
  `lname` varchar(120) DEFAULT NULL COMMENT 'last name',
  `pin` varchar(10) DEFAULT NULL COMMENT 'pin number',
  `npi` varchar(15) DEFAULT NULL COMMENT 'National Provider Identifier',
  `lic` varchar(80) DEFAULT NULL,
  `ess` varchar(80) DEFAULT NULL,
  `upin` varchar(80) DEFAULT NULL,
  `fedtaxid` varchar(80) DEFAULT NULL COMMENT 'federal tax id',
  `feddrugid` varchar(80) DEFAULT NULL COMMENT 'federal drug id',
  `notes` varchar(300) DEFAULT NULL COMMENT 'notes',
  `email` varchar(150) DEFAULT NULL COMMENT 'email',
  `specialty` mediumtext COMMENT 'specialty',
  `taxonomy` varchar(40) DEFAULT NULL COMMENT 'taxonomy',
  `calendar` tinyint(1) DEFAULT NULL COMMENT 'has calendar? 0=no 1=yes',
  `authorized` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `direct_address` varchar(150) DEFAULT NULL COMMENT 'direct_address',
  `city` varchar(55) DEFAULT NULL,
  `state` varchar(55) DEFAULT NULL,
  `postal_code` varchar(15) DEFAULT NULL,
  `street` varchar(55) DEFAULT NULL,
  `street_cont` varchar(55) DEFAULT NULL,
  `country_code` varchar(15) DEFAULT NULL,
  `phone` varchar(80) DEFAULT NULL,
  `mobile` varchar(80) DEFAULT NULL,
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `providerCode` varchar(40) DEFAULT NULL,
  `is_attending` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fname` (`fname`),
  KEY `mname` (`mname`),
  KEY `lname` (`lname`),
  KEY `npi` (`npi`),
  KEY `email` (`email`),
  KEY `facility_id` (`facility_id`),
  KEY `username` (`username`),
  KEY `direct_address` (`direct_address`),
  KEY `IK_taxonomy` (`taxonomy`),
  KEY `IK_calendar` (`calendar`),
  KEY `IK_phone` (`phone`),
  KEY `IK_active` (`active`),
  KEY `IK_is_attending` (`is_attending`)
) ENGINE=InnoDB AUTO_INCREMENT=522 DEFAULT CHARSET=utf8 COMMENT='User accounts';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_facility`
--

DROP TABLE IF EXISTS `users_facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_facility` (
  `tablename` varchar(64) NOT NULL,
  `table_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`tablename`,`table_id`,`facility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='joins users or patient_data to facility table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_sessions`
--

DROP TABLE IF EXISTS `users_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) DEFAULT NULL COMMENT 'Session ID',
  `uid` int(11) DEFAULT NULL COMMENT 'User ID',
  `login` int(11) DEFAULT NULL,
  `logout` int(11) DEFAULT NULL,
  `last_request` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4757 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vector_graphs`
--

DROP TABLE IF EXISTS `vector_graphs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vector_graphs` (
  `type` int(11) NOT NULL,
  `sex` int(11) NOT NULL,
  `age_mos` float DEFAULT NULL,
  `height` float DEFAULT NULL,
  `L` float NOT NULL,
  `M` float NOT NULL,
  `S` float NOT NULL,
  `P3` float NOT NULL,
  `P5` float NOT NULL,
  `P10` float NOT NULL,
  `P25` float NOT NULL,
  `P50` float NOT NULL,
  `P75` float NOT NULL,
  `P85` float DEFAULT NULL,
  `P90` float NOT NULL,
  `P95` float NOT NULL,
  `P97` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `version`
--

DROP TABLE IF EXISTS `version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v_major` int(11) NOT NULL DEFAULT '0',
  `v_minor` int(11) NOT NULL DEFAULT '0',
  `v_patch` int(11) NOT NULL DEFAULT '0',
  `v_tag` varchar(31) NOT NULL DEFAULT '',
  `v_database` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `x12_partners`
--

DROP TABLE IF EXISTS `x12_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `x12_partners` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `id_number` varchar(255) DEFAULT NULL,
  `x12_sender_id` varchar(255) DEFAULT NULL,
  `x12_receiver_id` varchar(255) DEFAULT NULL,
  `x12_version` varchar(255) DEFAULT NULL,
  `processing_format` enum('standard','medi-cal','cms','proxymed') DEFAULT NULL,
  `x12_isa05` char(2) NOT NULL DEFAULT 'ZZ',
  `x12_isa07` char(2) NOT NULL DEFAULT 'ZZ',
  `x12_isa14` char(1) NOT NULL DEFAULT '0',
  `x12_isa15` char(1) NOT NULL DEFAULT 'P',
  `x12_gs02` varchar(15) NOT NULL DEFAULT '',
  `x12_per06` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'gaiadb'
--

--
-- Dumping routines for database 'gaiadb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-01-11 11:14:16
