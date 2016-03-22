
DROP TABLE IF EXISTS `accvoucher`;
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


DROP TABLE IF EXISTS `acl_groups`;
CREATE TABLE `acl_groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `acl_permissions`;
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


DROP TABLE IF EXISTS `acl_role_perms`;
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


DROP TABLE IF EXISTS `acl_roles`;
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


DROP TABLE IF EXISTS `acl_user_perms`;
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


DROP TABLE IF EXISTS `acl_user_roles`;
CREATE TABLE `acl_user_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `addresses`;
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


DROP TABLE IF EXISTS `allergies`;
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


DROP TABLE IF EXISTS `applications`;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(120) DEFAULT NULL,
  `pvt_key` varchar(80) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cpt_codes`;
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


DROP TABLE IF EXISTS `calendar_categories`;
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


DROP TABLE IF EXISTS `support_rules`;
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


DROP TABLE IF EXISTS `support_rule_concepts`;
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


DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `documents_templates`;
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


DROP TABLE IF EXISTS `encounter_event_history`;
CREATE TABLE `encounter_event_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `event` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `user_title` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `user_mname` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `user_lname` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `patient_title` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `patient_fname` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `patient_mname` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `patient_lname` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Encounter History Events';


DROP TABLE IF EXISTS `facility`;
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


DROP TABLE IF EXISTS `facility_structures`;
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


DROP TABLE IF EXISTS `floor_plans_zones`;
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


DROP TABLE IF EXISTS `floor_plans`;
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


DROP TABLE IF EXISTS `forms_fields`;
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


DROP TABLE IF EXISTS `forms_field_options`;
CREATE TABLE `forms_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` varchar(255) DEFAULT NULL COMMENT 'Field ID',
  `options` text COMMENT 'Field Options JSON Format',
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1687 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `forms_layout`;
CREATE TABLE `forms_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `form_data` varchar(80) DEFAULT NULL,
  `model` varchar(80) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `geo_ip_location`;
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


DROP TABLE IF EXISTS `globals`;
CREATE TABLE `globals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gl_name` varchar(255) DEFAULT NULL COMMENT 'Global Setting Unique Name or Key',
  `gl_index` int(11) DEFAULT NULL COMMENT 'Global Setting Index',
  `gl_value` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Global Setting Value',
  `gl_category` varchar(255) DEFAULT NULL COMMENT 'Category',
  PRIMARY KEY (`id`),
  KEY `gl_name` (`gl_name`,`gl_index`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `hl7_clients`;
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


DROP TABLE IF EXISTS `hl7_messages`;
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


DROP TABLE IF EXISTS `hl7_servers`;
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


DROP TABLE IF EXISTS `insurance_companies`;
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


DROP TABLE IF EXISTS `insurance_numbers`;
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


DROP TABLE IF EXISTS `ip_access_log`;
CREATE TABLE `ip_access_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) DEFAULT NULL,
  `country_code` varchar(130) DEFAULT NULL,
  `event` varchar(10) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `ip_access_rules`;
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


DROP TABLE IF EXISTS `labs_panels`;
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


DROP TABLE IF EXISTS `laboratories`;
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


DROP TABLE IF EXISTS `combo_lists_options`;
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


DROP TABLE IF EXISTS `combo_lists`;
CREATE TABLE `combo_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'Title of the combo',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `rxinstructions`;
CREATE TABLE `rxinstructions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rxcui` varchar(255) DEFAULT NULL,
  `occurrence` int(11) DEFAULT NULL,
  `instruction` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IK_rxcui` (`rxcui`),
  KEY `IK_occurrence` (`occurrence`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `modules`;
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


DROP TABLE IF EXISTS `pharmacies`;
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


DROP TABLE IF EXISTS `phones`;
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


DROP TABLE IF EXISTS `provider_credentializations`;
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


DROP TABLE IF EXISTS `referring_providers`;
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


DROP TABLE IF EXISTS `referring_providers_facilities`;
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


DROP TABLE IF EXISTS `specialties`;
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


DROP TABLE IF EXISTS `specialties`;
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


DROP TABLE IF EXISTS `template_panels`;
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


DROP TABLE IF EXISTS `template_panels_templates`;
CREATE TABLE `template_panels_templates` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `panel_id` int(11) DEFAULT NULL,
  `template_type` varchar(80) DEFAULT NULL COMMENT 'rx lab rad etc',
  `description` varchar(300) DEFAULT NULL,
  `template_data` mediumtext,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `audit_transaction_log`;
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
) ENGINE=InnoDB AUTO_INCREMENT=1126714 DEFAULT CHARSET=utf8 COMMENT='Data INSERT UPDATE DELETE Logs';


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(40) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL COMMENT 'acl_user_roles relation',
  `facility_id` int(11) DEFAULT NULL COMMENT 'default facility',
  `department_id` INT(11) DEFAULT NULL COMMENT 'default department',
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
  KEY `IK_department_id` (`department_id`),
  KEY `IK_taxonomy` (`taxonomy`),
  KEY `IK_calendar` (`calendar`),
  KEY `IK_phone` (`phone`),
  KEY `IK_active` (`active`),
  KEY `IK_is_attending` (`is_attending`)
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8 COMMENT='User accounts';


DROP TABLE IF EXISTS `users_sessions`;
CREATE TABLE `users_sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) DEFAULT NULL COMMENT 'Session ID',
  `uid` int(11) DEFAULT NULL COMMENT 'User ID',
  `login` int(11) DEFAULT NULL,
  `logout` int(11) DEFAULT NULL,
  `last_request` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5127 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `patient_pools`;
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
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pool_areas`;
CREATE TABLE `pool_areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) DEFAULT NULL,
  `floor_plan_id` bigint(20) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `calendar_categories`;
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


DROP TABLE IF EXISTS `calendar_events`;
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


DROP TABLE IF EXISTS `messages`;
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


DROP TABLE IF EXISTS `address_book`;
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


DROP TABLE IF EXISTS `patient_amendments`;
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


DROP TABLE IF EXISTS `office_notes`;
CREATE TABLE `office_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `facility_id` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='Office Notes';


DROP TABLE IF EXISTS `patient_advance_directives`;
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


DROP TABLE IF EXISTS `patient_allergies`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `patient_appointment_requests`;
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


DROP TABLE IF EXISTS `cvx_codes`;
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


DROP TABLE IF EXISTS `patient_care_plan_goals`;
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


DROP TABLE IF EXISTS `patient_cognitive_functional_status`;
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


DROP TABLE IF EXISTS `encounter_dictation`;
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


DROP TABLE IF EXISTS `patient_disclosures`;
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


DROP TABLE IF EXISTS `patient_doctors_notes`;
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


DROP TABLE IF EXISTS `encounters`;
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `encounter_dx`;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `encounter_services`;
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


DROP TABLE IF EXISTS `encounter_history`;
CREATE TABLE `encounter_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `date` datetime DEFAULT NULL COMMENT 'date created',
  `user` varchar(255) DEFAULT NULL,
  `event` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `patient_family_history`;
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


DROP TABLE IF EXISTS `encounter_1500_options`;
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `patient_insurances`;
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


DROP TABLE IF EXISTS `patient_medications`;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `patient_notes`;
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


DROP TABLE IF EXISTS `patient`;
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
) ENGINE=InnoDB AUTO_INCREMENT=9573 DEFAULT CHARSET=utf8 COMMENT='Patients/Demographics';


DROP TABLE IF EXISTS `patient_active_problems`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `patient_chart_checkout`;
CREATE TABLE `patient_chart_checkout` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `chart_out_time` datetime DEFAULT NULL,
  `chart_in_time` datetime DEFAULT NULL,
  `pool_area_id` int(11) DEFAULT NULL,
  `read_only` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2317 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `patient_contacts`;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Patient Contacts';


DROP TABLE IF EXISTS `patient_documents`;
CREATE TABLE `patient_documents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(120) DEFAULT NULL COMMENT 'external reference id',
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `docType` varchar(255) DEFAULT NULL,
  `docTypeCode` varchar(80) DEFAULT NULL,
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


DROP TABLE IF EXISTS `patient_documents_temp`;
CREATE TABLE `patient_documents_temp` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_date` datetime DEFAULT NULL,
  `document` longtext,
  `document_name` varchar(180) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Documents Temporary Storage';


DROP TABLE IF EXISTS `patient_immunizations`;
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


DROP TABLE IF EXISTS `patient_social_history`;
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


DROP TABLE IF EXISTS `patient_zone`;
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


DROP TABLE IF EXISTS `patient_order_results_observations`;
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


DROP TABLE IF EXISTS `patient_order_results`;
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
  `void` tinyint(1) NOT NULL DEFAULT '0',
  `void_comment` varchar(100) DEFAULT NULL,
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


DROP TABLE IF EXISTS `patient_orders`;
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
  `patient_orderscol` varchar(45) DEFAULT NULL,
  `void` tinyint(1) NOT NULL DEFAULT '0',
  `void_comment` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`),
  KEY `order_type` (`order_type`),
  KEY `date_ordered` (`date_ordered`),
  KEY `date_collected` (`date_collected`),
  KEY `priority` (`priority`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `patient_referrals`;
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


DROP TABLE IF EXISTS `patient_reminders`;
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


DROP TABLE IF EXISTS `encounter_review_of_systems`;
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `encounter_soap`;
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
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `patient_smoke_status`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Smoke status';


DROP TABLE IF EXISTS `encounter_vitals`;
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `encounter_procedures`;
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


DROP TABLE IF EXISTS `soap_snippets`;
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

