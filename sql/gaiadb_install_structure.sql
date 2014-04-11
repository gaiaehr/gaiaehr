SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `accaccount` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createUid` int(11) DEFAULT NULL,
  `writeUid` int(11) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `writeDate` datetime DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL COMMENT 'Parent Account',
  `companyId` int(11) DEFAULT NULL COMMENT 'Company',
  `currencyId` int(11) DEFAULT NULL COMMENT 'Account',
  `level` int(11) DEFAULT NULL COMMENT 'Level',
  `accountType` int(11) DEFAULT NULL COMMENT 'Account Type',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  `reconcile` tinyint(1) DEFAULT NULL COMMENT 'Allow Reconciliation?',
  `name` varchar(255) DEFAULT NULL COMMENT 'Name',
  `code` varchar(255) DEFAULT NULL COMMENT 'Code',
  `shortcut` varchar(255) DEFAULT NULL COMMENT 'Shortcut',
  `note` varchar(255) DEFAULT NULL COMMENT 'Internal Notes',
  `currencyMode` varchar(255) DEFAULT NULL COMMENT 'Outgoing Currencies Rate',
  `type` varchar(255) DEFAULT NULL COMMENT 'Internal Type',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

CREATE TABLE `accvoucher` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dateDue` date DEFAULT NULL COMMENT 'Due Date',
  `date` date DEFAULT NULL COMMENT 'Date',
  `encounterId` int(11) DEFAULT NULL COMMENT 'Encounter',
  `accountId` int(11) DEFAULT NULL COMMENT 'Account',
  `journalId` int(11) DEFAULT NULL COMMENT 'Journal',
  `moveId` int(11) DEFAULT NULL COMMENT 'Account Entry',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Comment',
  `reference` varchar(255) DEFAULT NULL COMMENT 'Ref',
  `number` varchar(255) DEFAULT NULL COMMENT 'Number',
  `notes` varchar(255) DEFAULT NULL COMMENT 'Notes',
  `status` varchar(255) DEFAULT NULL COMMENT 'Status',
  `type` varchar(255) DEFAULT NULL COMMENT 'visit/product/office',
  `amount` float(10,2) DEFAULT '0.00' COMMENT 'Total Amount',
  `createUid` int(11) DEFAULT NULL,
  `createDate` date DEFAULT NULL,
  `writeUid` int(11) DEFAULT NULL,
  `writeDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `accvoucherline` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `voucherId` int(11) DEFAULT NULL COMMENT 'Voucher',
  `accountId` int(11) DEFAULT NULL COMMENT 'Account',
  `moveLineId` int(11) DEFAULT NULL COMMENT 'Journal Item',
  `reconcile` tinyint(1) DEFAULT NULL COMMENT 'Full Reconcile',
  `code` varchar(255) DEFAULT NULL COMMENT 'COPAY/CPT/HCPCS/SKU codes',
  `name` varchar(255) DEFAULT NULL COMMENT 'Description',
  `type` varchar(255) DEFAULT NULL COMMENT 'debit/credit',
  `amountUnreconciled` float(10,2) DEFAULT NULL COMMENT 'Open Balance',
  `amountUntax` float(10,2) DEFAULT NULL COMMENT 'Untax Amount',
  `amountOriginal` float(10,2) DEFAULT NULL COMMENT 'Default Amount',
  `amount` float(10,2) DEFAULT NULL COMMENT 'Amount',
  `createUid` int(11) DEFAULT NULL,
  `createDate` date DEFAULT NULL,
  `writeUid` int(11) DEFAULT NULL,
  `writeDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `acl_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perm_key` varchar(255) DEFAULT NULL COMMENT 'Permission Key',
  `perm_name` varchar(255) DEFAULT NULL COMMENT 'Permission Name',
  `perm_cat` varchar(255) DEFAULT NULL COMMENT 'Permission Category',
  `seq` int(11) DEFAULT NULL COMMENT 'Sequence Order',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permKey` (`perm_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

CREATE TABLE `acl_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) DEFAULT NULL COMMENT 'Role Name',
  `role_key` varchar(255) DEFAULT NULL COMMENT 'Role Key',
  `seq` int(11) DEFAULT NULL COMMENT 'Sequence Order',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE `acl_role_perms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_key` varchar(255) DEFAULT NULL COMMENT 'Role Key',
  `perm_key` varchar(255) DEFAULT NULL COMMENT 'Permission Key',
  `value` tinyint(1) DEFAULT NULL COMMENT 'Value',
  `add_date` datetime DEFAULT NULL COMMENT 'Date Added',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=334 ;

CREATE TABLE `acl_user_perms` (
  `id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `perm_key` varchar(255) DEFAULT NULL COMMENT 'Permission Key',
  `value` int(11) DEFAULT NULL COMMENT 'Value',
  `add_date` datetime DEFAULT NULL COMMENT 'Date Added',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `acl_user_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE `allergies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summary` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Allergy Summary',
  `allergy_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Allergy Name',
  `allergy_type` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) NOT NULL,
  `pvt_key` varchar(255) NOT NULL,
  `active` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE `audit_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL COMMENT 'Encounter ID',
  `event` varchar(255) DEFAULT NULL COMMENT 'Event description',
  `facility` varchar(255) DEFAULT NULL COMMENT 'Witch facility',
  `patient_id` int(11) DEFAULT NULL COMMENT 'Patient ID',
  `user_id` int(11) DEFAULT NULL COMMENT 'User ID',
  `user` varchar(255) DEFAULT NULL COMMENT 'Username',
  `date` datetime DEFAULT NULL COMMENT 'Date of the event',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Audit Logs' AUTO_INCREMENT=1179 ;

CREATE TABLE `billing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `code_type` varchar(7) DEFAULT NULL,
  `code` varchar(9) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `authorized` tinyint(1) DEFAULT NULL,
  `encounter` int(11) DEFAULT NULL,
  `code_text` longtext,
  `billed` tinyint(1) DEFAULT NULL,
  `activity` tinyint(1) DEFAULT NULL,
  `payer_id` int(11) DEFAULT NULL,
  `bill_process` tinyint(2) NOT NULL DEFAULT '0',
  `bill_date` datetime DEFAULT NULL,
  `process_date` datetime DEFAULT NULL,
  `process_file` varchar(255) DEFAULT NULL,
  `modifier` varchar(5) DEFAULT NULL,
  `units` tinyint(3) DEFAULT NULL,
  `fee` decimal(12,2) DEFAULT NULL,
  `justify` varchar(255) DEFAULT NULL,
  `target` varchar(30) DEFAULT NULL,
  `x12_partner_id` int(11) DEFAULT NULL,
  `ndc_info` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `calendar_categories` (
  `catid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(255) DEFAULT NULL COMMENT 'Category Name',
  `catcolor` varchar(255) DEFAULT NULL COMMENT 'Category Color',
  `catdesc` text COMMENT 'Category Description',
  `duration` bigint(20) NOT NULL DEFAULT '0',
  `cattype` int(11) DEFAULT NULL COMMENT 'Category Type',
  PRIMARY KEY (`catid`),
  KEY `basic_cat` (`catname`,`catcolor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE `calendar_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'User Id ',
  `category` int(11) DEFAULT NULL COMMENT 'Ty of calendar category',
  `facility` int(11) DEFAULT NULL COMMENT 'faccility id',
  `billing_facillity` int(11) DEFAULT NULL COMMENT 'billing facility id',
  `patient_id` int(11) DEFAULT NULL COMMENT 'patient id (pid)',
  `title` varchar(255) DEFAULT NULL COMMENT 'We are using the patient fullname as the evnet title',
  `status` varchar(255) DEFAULT NULL COMMENT 'event status',
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `rrule` varchar(255) DEFAULT NULL COMMENT 'repeatable eventevnet',
  `loc` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `ad` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `cdt_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Dental Codes' AUTO_INCREMENT=594 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `combo_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'Title of the combo',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=97 ;

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
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=773 ;

CREATE TABLE `cpt_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ConceptID` bigint(20) NOT NULL,
  `code` varchar(50) NOT NULL,
  `code_text` text,
  `code_text_medium` text,
  `code_text_short` text,
  `active` tinyint(1) DEFAULT '1' COMMENT '0 = inactive, 1 = active',
  `reportable` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9640 ;

CREATE TABLE `cpt_icd` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cpt` varchar(40) NOT NULL,
  `icd` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=450629 ;

CREATE TABLE `cvx_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cvx_code` int(6) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `note` text,
  `status` varchar(50) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `code` (`description`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=154 ;

CREATE TABLE `cvx_cpt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cvx` varchar(25) DEFAULT NULL,
  `cpt` varchar(25) DEFAULT NULL,
  `active` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=103 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='CVX munufactures' AUTO_INCREMENT=87 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

CREATE TABLE `eligibility_response` (
  `response_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `response_description` varchar(255) DEFAULT NULL,
  `response_status` enum('A','D') NOT NULL DEFAULT 'A',
  `response_vendor_id` bigint(20) DEFAULT NULL,
  `response_create_date` date DEFAULT NULL,
  `response_modify_date` date DEFAULT NULL,
  PRIMARY KEY (`response_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `emergencies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE `encounters` (
  `eid` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Encounter ID',
  `pid` bigint(20) NOT NULL COMMENT 'Patient ID',
  `open_uid` bigint(20) NOT NULL COMMENT 'User ID who opened the encounter',
  `provider_uid` bigint(20) DEFAULT NULL COMMENT 'Provider User ID',
  `supervisor_uid` bigint(20) DEFAULT NULL COMMENT 'Supervisor User ID',
  `service_date` datetime NOT NULL COMMENT 'Date when the encounter started',
  `close_date` datetime DEFAULT NULL COMMENT 'Date when the encounter was sign/close',
  `onset_date` datetime DEFAULT NULL,
  `priority` varchar(255) DEFAULT NULL,
  `brief_description` varchar(255) DEFAULT NULL,
  `visit_category` varchar(255) DEFAULT NULL,
  `facility` varchar(255) DEFAULT NULL,
  `billing_facility` varchar(255) DEFAULT NULL,
  `billing_stage` int(1) DEFAULT NULL COMMENT 'billing stage of this encounter',
  `followup_time` varchar(255) DEFAULT NULL,
  `followup_facility` varchar(255) DEFAULT NULL,
  `review_immunizations` tinyint(1) NOT NULL DEFAULT '0',
  `review_allergies` tinyint(1) NOT NULL DEFAULT '0',
  `review_active_problems` tinyint(1) NOT NULL DEFAULT '0',
  `review_alcohol` varchar(255) DEFAULT NULL,
  `review_smoke` varchar(255) DEFAULT NULL,
  `review_pregnant` varchar(255) DEFAULT NULL,
  `review_surgery` tinyint(1) NOT NULL DEFAULT '0',
  `review_dental` tinyint(1) NOT NULL DEFAULT '0',
  `review_medications` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COMMENT 'message for the visit checkout ',
  PRIMARY KEY (`eid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE `encounter_1500_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `employment_related` tinyint(1) DEFAULT NULL,
  `auto_accident` tinyint(1) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

CREATE TABLE `encounter_dictation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `uid` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `dictation` longtext,
  `additional_notes` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE `encounter_dx` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `uid` bigint(20) DEFAULT NULL,
  `code` varchar(25) DEFAULT NULL COMMENT 'code number',
  `code_type` varchar(10) DEFAULT NULL COMMENT 'CPT4 = 1, ICD9= 2, HCPCS = 3 ',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `encounter_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `date` datetime DEFAULT NULL COMMENT 'date created',
  `user` varchar(255) DEFAULT NULL,
  `event` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `encounter_procedures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `eid` int(11) DEFAULT NULL COMMENT 'Encounter ID',
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `code` varchar(255) DEFAULT NULL COMMENT 'procedure code',
  `code_text` varchar(255) DEFAULT NULL COMMENT 'procedure description',
  `code_type` varchar(255) DEFAULT NULL COMMENT 'CPT/ICD-10-PCS/ICD-9-CM/SNOMED/CDT',
  `observation` varchar(255) DEFAULT NULL COMMENT 'observation found',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Patient Encounter Procedures' AUTO_INCREMENT=1 ;

CREATE TABLE `encounter_referrals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Encounter Referrals' AUTO_INCREMENT=1 ;

CREATE TABLE `encounter_review_of_systems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE `encounter_services` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `uid` bigint(20) DEFAULT NULL,
  `code` varchar(25) DEFAULT NULL COMMENT 'code number',
  `code_type` varchar(25) DEFAULT NULL,
  `dx_pointers` varchar(25) DEFAULT NULL,
  `charge` varchar(25) DEFAULT NULL,
  `days_of_units` text,
  `emergency` tinyint(1) NOT NULL DEFAULT '0',
  `essdt_plan` text,
  `modifiers` text,
  `place_of_service` text,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT 'billing status of this cpt',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE `encounter_soap` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `subjective` longtext,
  `objective` longtext,
  `assessment` longtext,
  `plan` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE `encounter_vitals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL COMMENT 'Patient ID',
  `eid` bigint(20) NOT NULL COMMENT 'Encounter ID',
  `uid` bigint(20) NOT NULL COMMENT 'User (id) "who saved the vitals data"',
  `auth_uid` bigint(20) DEFAULT NULL,
  `date` datetime NOT NULL COMMENT 'date vitals were taken',
  `weight_lbs` varchar(255) DEFAULT NULL,
  `weight_kg` varchar(255) DEFAULT NULL,
  `height_in` varchar(255) DEFAULT NULL,
  `height_cm` varchar(255) DEFAULT NULL,
  `bp_systolic` varchar(255) DEFAULT NULL,
  `bp_diastolic` varchar(255) DEFAULT NULL,
  `pulse` varchar(255) DEFAULT NULL,
  `respiration` varchar(255) DEFAULT NULL,
  `temp_f` varchar(255) DEFAULT NULL,
  `temp_c` varchar(255) DEFAULT NULL,
  `temp_location` varchar(255) DEFAULT NULL,
  `oxygen_saturation` varchar(255) DEFAULT NULL,
  `head_circumference_in` varchar(255) DEFAULT NULL,
  `head_circumference_cm` varchar(255) DEFAULT NULL,
  `waist_circumference_in` varchar(255) DEFAULT NULL,
  `waist_circumference_cm` varchar(255) DEFAULT NULL,
  `bmi` varchar(255) DEFAULT NULL,
  `bmi_status` varchar(255) DEFAULT NULL,
  `other_notes` varchar(255) DEFAULT NULL,
  `authorized_by` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'Facility Name',
  `active` tinyint(1) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postal_code` varchar(11) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `federal_ein` varchar(15) DEFAULT NULL,
  `service_location` tinyint(1) DEFAULT NULL,
  `billing_location` tinyint(1) DEFAULT NULL,
  `accepts_assignment` tinyint(1) DEFAULT NULL,
  `pos_code` varchar(255) DEFAULT NULL,
  `x12_sender_id` varchar(25) DEFAULT NULL,
  `attn` varchar(65) DEFAULT NULL,
  `domain_identifier` varchar(60) DEFAULT NULL,
  `facility_npi` varchar(15) DEFAULT NULL,
  `tax_id_type` varchar(31) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE `fee_sheet_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fs_category` varchar(63) DEFAULT NULL,
  `fs_option` varchar(63) DEFAULT NULL,
  `fs_codes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE `floor_plans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'Floor Title',
  `facility_id` int(11) DEFAULT NULL COMMENT 'facility ID',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active Floor Plan?',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE `floor_plans_zones` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `floor_plan_id` bigint(20) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `bg_color` varchar(10) DEFAULT NULL,
  `border_color` varchar(10) DEFAULT NULL,
  `scale` varchar(30) DEFAULT NULL,
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `show_priority_color` tinyint(1) DEFAULT '1',
  `show_patient_preview` tinyint(1) DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

CREATE TABLE `forms_fields` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `form_id` bigint(11) DEFAULT NULL,
  `xtype` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `parentId` varchar(11) COLLATE latin1_bin DEFAULT NULL,
  `index` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `parentId` (`parentId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=1184 ;

CREATE TABLE `forms_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` varchar(255) DEFAULT NULL COMMENT 'Field ID',
  `options` text COMMENT 'Field Options JSON Format',
  PRIMARY KEY (`id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1559 ;

CREATE TABLE `forms_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `form_data` varchar(80) DEFAULT NULL,
  `model` varchar(80) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `geo_country_reference` (
  `countries_id` int(5) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(64) DEFAULT NULL,
  `countries_iso_code_2` char(2) NOT NULL DEFAULT '',
  `countries_iso_code_3` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

CREATE TABLE `geo_zone_reference` (
  `zone_id` int(5) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(5) NOT NULL DEFAULT '0',
  `zone_code` varchar(5) DEFAULT NULL,
  `zone_name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

CREATE TABLE `globals` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `gl_name` varchar(255) DEFAULT NULL COMMENT 'Global Setting Unique Name or Key',
  `gl_index` int(11) DEFAULT NULL COMMENT 'Global Setting Index',
  `gl_value` varchar(255) DEFAULT NULL COMMENT 'Global Setting Value',
  `gl_category` varchar(255) DEFAULT NULL COMMENT 'Category',
  PRIMARY KEY (`id`),
  KEY `gl_name` (`gl_name`,`gl_index`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

CREATE TABLE `groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` longtext,
  `user` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `hl7_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msg_type` varchar(255) DEFAULT NULL COMMENT 'example VXU ADT OBX',
  `message` mediumtext COMMENT 'Original HL7 message',
  `response` mediumtext COMMENT 'HL7 acknowledgment message',
  `foreign_facility` varchar(255) DEFAULT NULL COMMENT 'From or To external facility',
  `foreign_application` varchar(255) DEFAULT NULL COMMENT 'From or To external Application',
  `foreign_address` varchar(255) DEFAULT NULL COMMENT 'incoming or outgoing address',
  `isOutbound` tinyint(1) DEFAULT NULL COMMENT 'outbound 1, inbound 0',
  `date_processed` datetime DEFAULT NULL COMMENT 'When Message was Received or Send',
  `status` int(1) DEFAULT NULL COMMENT '0 = hold, 1 = processing, 2 = queue, 3 = processed, 4 = error',
  `error` varchar(255) DEFAULT NULL COMMENT 'connection error message',
  `reference` varchar(255) DEFAULT NULL COMMENT 'Reference number or file name',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='hl7 messages data' AUTO_INCREMENT=1 ;

CREATE TABLE `hl7_recipients` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `recipient_type` varchar(255) DEFAULT NULL COMMENT 'http or file',
  `recipient_facility` varchar(255) DEFAULT NULL COMMENT 'Facility Name',
  `recipient_application` varchar(255) DEFAULT NULL COMMENT 'Application Name',
  `recipient` varchar(255) DEFAULT NULL COMMENT 'url or Directory Path',
  `port` varchar(255) DEFAULT NULL COMMENT 'url port if any',
  `isSecure` tinyint(1) DEFAULT NULL COMMENT 'If secure then user secret_key',
  `secret_key` blob COMMENT 'This field is encrypted automatically by Matcha',
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='hl7 Recipients Data' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `icd9_dx_long_code` (
  `dx_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_code` varchar(5) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `dx_id` (`dx_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `icd9_sg_long_code` (
  `sq_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sg_code` varchar(5) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `sq_id` (`sq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `icd10_gem_dx_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_icd9_source` varchar(5) DEFAULT NULL,
  `dx_icd10_target` varchar(7) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `icd10_gem_dx_10_9` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_icd10_source` varchar(7) DEFAULT NULL,
  `dx_icd9_target` varchar(5) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `icd10_gem_pcs_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_icd9_source` varchar(5) DEFAULT NULL,
  `pcs_icd10_target` varchar(7) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `icd10_gem_pcs_10_9` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_icd10_source` varchar(7) DEFAULT NULL,
  `pcs_icd9_target` varchar(5) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `immunizations_relations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `immunization_id` bigint(20) DEFAULT NULL,
  `foreign_id` bigint(20) DEFAULT NULL,
  `code_type` varchar(255) DEFAULT NULL COMMENT 'medication,active problem or labs',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `insurance_companies` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `attn` varchar(255) DEFAULT NULL,
  `cms_id` varchar(15) DEFAULT NULL,
  `freeb_type` varchar(255) DEFAULT NULL,
  `x12_receiver_id` varchar(25) DEFAULT NULL,
  `x12_default_partner_id` varchar(255) DEFAULT NULL,
  `alt_cms_id` varchar(15) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL,
  `phone_id` int(11) DEFAULT NULL,
  `fax_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `insurance_data` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` enum('primary','secondary','tertiary') DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `plan_name` varchar(255) DEFAULT NULL,
  `policy_number` varchar(255) DEFAULT NULL,
  `group_number` varchar(255) DEFAULT NULL,
  `subscriber_lname` varchar(255) DEFAULT NULL,
  `subscriber_mname` varchar(255) DEFAULT NULL,
  `subscriber_fname` varchar(255) DEFAULT NULL,
  `subscriber_relationship` varchar(255) DEFAULT NULL,
  `subscriber_ss` varchar(255) DEFAULT NULL,
  `subscriber_DOB` date DEFAULT NULL,
  `subscriber_street` varchar(255) DEFAULT NULL,
  `subscriber_postal_code` varchar(255) DEFAULT NULL,
  `subscriber_city` varchar(255) DEFAULT NULL,
  `subscriber_state` varchar(255) DEFAULT NULL,
  `subscriber_country` varchar(255) DEFAULT NULL,
  `subscriber_phone` varchar(255) DEFAULT NULL,
  `subscriber_employer` varchar(255) DEFAULT NULL,
  `subscriber_employer_street` varchar(255) DEFAULT NULL,
  `subscriber_employer_postal_code` varchar(255) DEFAULT NULL,
  `subscriber_employer_state` varchar(255) DEFAULT NULL,
  `subscriber_employer_country` varchar(255) DEFAULT NULL,
  `subscriber_employer_city` varchar(255) DEFAULT NULL,
  `copay` varchar(255) DEFAULT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `pid` bigint(20) NOT NULL DEFAULT '0',
  `subscriber_sex` varchar(25) DEFAULT NULL,
  `accept_assignment` varchar(5) NOT NULL DEFAULT 'TRUE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pid_type_date` (`pid`,`type`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `insurance_numbers` (
  `id` int(11) NOT NULL DEFAULT '0',
  `provider_id` int(11) NOT NULL DEFAULT '0',
  `insurance_company_id` int(11) DEFAULT NULL,
  `provider_number` varchar(20) DEFAULT NULL,
  `rendering_provider_number` varchar(20) DEFAULT NULL,
  `group_number` varchar(20) DEFAULT NULL,
  `provider_number_type` varchar(4) DEFAULT NULL,
  `rendering_provider_number_type` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `issue_encounter` (
  `pid` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `encounter` int(11) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  PRIMARY KEY (`pid`,`list_id`,`encounter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `labs_guidelines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) DEFAULT NULL,
  `less_than` float DEFAULT NULL,
  `greater_than` float DEFAULT NULL,
  `equal_to` float DEFAULT NULL,
  `preventive_care_id` int(11) DEFAULT NULL,
  `value_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=133 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1910 ;

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
  KEY `LOINC_NUM_2` (`LOINC_NUM`),
  KEY `PARENT_LOINC` (`PARENT_LOINC`),
  FULLTEXT KEY `DISPLAY_NAME_FOR_FORM` (`DISPLAY_NAME_FOR_FORM`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` varchar(255) DEFAULT NULL COMMENT 'Date of message',
  `body` text COMMENT 'Message',
  `pid` int(11) DEFAULT NULL COMMENT 'Patient ID',
  `patient_name` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Messages' AUTO_INCREMENT=1 ;

CREATE TABLE `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `enable` tinyint(1) DEFAULT '0',
  `installed_version` varchar(25) DEFAULT NULL,
  `licensekey` varchar(255) DEFAULT NULL,
  `localkey` longblob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=206 ;

CREATE TABLE `modules_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `data` longtext,
  PRIMARY KEY (`id`),
  KEY `data` (`data`(767))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='this table if a convivnient table to store module related data' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `notification_settings` (
  `SettingsId` int(3) NOT NULL AUTO_INCREMENT,
  `Send_SMS_Before_Hours` int(3) NOT NULL,
  `Send_Email_Before_Hours` int(3) NOT NULL,
  `SMS_gateway_username` varchar(100) NOT NULL,
  `SMS_gateway_password` varchar(100) NOT NULL,
  `SMS_gateway_apikey` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`SettingsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `office_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `body` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `facility_id` varchar(255) DEFAULT NULL,
  `activity` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Office Notes' AUTO_INCREMENT=2 ;

CREATE TABLE `patient` (
  `pid` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `title` varchar(10) DEFAULT NULL COMMENT 'Title Mr. Sr.',
  `fname` varchar(60) DEFAULT NULL COMMENT 'first name',
  `mname` varchar(40) DEFAULT NULL COMMENT 'middle name',
  `lname` varchar(60) DEFAULT NULL COMMENT 'last name',
  `sex` varchar(10) DEFAULT NULL COMMENT 'sex',
  `DOB` datetime DEFAULT NULL COMMENT 'day of birth',
  `marital_status` varchar(40) DEFAULT NULL COMMENT 'marital status',
  `SS` varchar(40) DEFAULT NULL COMMENT 'social security',
  `pubpid` varchar(40) DEFAULT NULL COMMENT 'external/reference id',
  `drivers_license` varchar(40) DEFAULT NULL COMMENT 'driver licence #',
  `address` varchar(80) DEFAULT NULL COMMENT 'address',
  `city` varchar(40) DEFAULT NULL COMMENT 'city',
  `state` varchar(40) DEFAULT NULL COMMENT 'state',
  `country` varchar(40) DEFAULT NULL COMMENT 'country',
  `zipcode` varchar(10) DEFAULT NULL COMMENT 'postal code',
  `home_phone` varchar(15) DEFAULT NULL COMMENT 'home phone #',
  `mobile_phone` varchar(15) DEFAULT NULL COMMENT 'mobile phone #',
  `work_phone` varchar(15) DEFAULT NULL COMMENT 'work phone #',
  `email` varchar(60) DEFAULT NULL COMMENT 'email',
  `mothers_name` varchar(40) DEFAULT NULL COMMENT 'mother name',
  `guardians_name` varchar(40) DEFAULT NULL COMMENT 'guardians name',
  `emer_contact` varchar(40) DEFAULT NULL COMMENT 'emergency contact',
  `emer_phone` varchar(15) DEFAULT NULL COMMENT 'emergency phone #',
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
  `rating` int(11) DEFAULT NULL COMMENT 'patient occupation',
  `image` mediumtext COMMENT 'patient image base64 string',
  `qrcode` mediumtext COMMENT 'patient QRCode base64 string',
  PRIMARY KEY (`pid`),
  KEY `fname` (`fname`),
  KEY `mname` (`mname`),
  KEY `lname` (`lname`),
  KEY `sex` (`sex`),
  KEY `DOB` (`DOB`),
  KEY `SS` (`SS`),
  KEY `pubpid` (`pubpid`),
  KEY `drivers_license` (`drivers_license`),
  KEY `home_phone` (`home_phone`),
  KEY `mobile_phone` (`mobile_phone`),
  KEY `work_phone` (`work_phone`),
  KEY `email` (`email`),
  KEY `LiveSearchIndex` (`pid`,`pubpid`,`fname`,`mname`,`lname`,`SS`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Patients/Demographics' ;

CREATE TABLE `patient_account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entity that maintains patient''s account transactions ' AUTO_INCREMENT=1 ;

CREATE TABLE `patient_active_problems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `code_text` varchar(255) DEFAULT NULL,
  `code_type` varchar(255) DEFAULT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `occurrence` varchar(255) DEFAULT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `outcome` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_allergies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `allergy_type` varchar(50) DEFAULT NULL,
  `allergy` varchar(50) DEFAULT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `reaction` varchar(50) DEFAULT NULL,
  `severity` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `allergy_code` varchar(255) DEFAULT NULL COMMENT 'RxNORM RXCUI code if food allergy',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `patient_dental` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `title` varchar(50) DEFAULT NULL,
  `diagnosis_code` varchar(100) DEFAULT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `ocurrence` varchar(50) DEFAULT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `outcome` varchar(50) DEFAULT NULL,
  `destination` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_dental_plans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `updateDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='patient dental plans' AUTO_INCREMENT=3 ;

CREATE TABLE `patient_dental_plan_items` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) DEFAULT NULL,
  `text` varchar(255) DEFAULT NULL,
  `tooth` varchar(255) DEFAULT NULL,
  `surface` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `planDate` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='dental plan item = procedures' AUTO_INCREMENT=13 ;

CREATE TABLE `patient_dental_prob_charts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `createDate` datetime DEFAULT NULL,
  `updateDate` datetime DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='patient dental Probing chart' AUTO_INCREMENT=4 ;

CREATE TABLE `patient_dental_prob_chart_items` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `chart_id` int(11) DEFAULT NULL COMMENT 'probing chart ID',
  `tooth` varchar(2) DEFAULT NULL,
  `missing` tinyint(1) DEFAULT NULL,
  `facial_mob` varchar(1) DEFAULT NULL,
  `facial_bld` text,
  `facial_cal` text,
  `facial_pla` text,
  `facial_sup` text,
  `facial_cej` text,
  `facial_pd` text,
  `lingual_cej` text,
  `lingual_pd` text,
  `lingual_bld` text,
  `lingual_cal` text,
  `lingual_pla` text,
  `lingual_sup` text,
  PRIMARY KEY (`id`),
  KEY `chart_id` (`chart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='patient dental Probing chart' AUTO_INCREMENT=97 ;

CREATE TABLE `patient_disclosures` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `uid` bigint(20) DEFAULT NULL COMMENT 'user ID',
  `date` datetime NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `recipient` varchar(255) DEFAULT NULL,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_doctors_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `document_id` varchar(255) DEFAULT NULL,
  `doctors_notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_documents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `docType` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT 'No title',
  `hash` varchar(255) DEFAULT NULL,
  `encrypted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `eid` (`eid`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_immunizations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `administered_date` datetime DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `administered_by` varchar(255) DEFAULT NULL,
  `lot_number` varchar(50) DEFAULT NULL,
  `education_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `code` int(11) DEFAULT NULL COMMENT 'vaccine code (CVX)',
  `code_type` varchar(255) DEFAULT NULL,
  `vaccine_name` varchar(255) DEFAULT NULL,
  `administer_amount` varchar(255) DEFAULT NULL,
  `administer_units` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE `patient_insurances` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'Patient ID',
  `provider` varchar(80) DEFAULT NULL COMMENT 'provider',
  `planName` varchar(40) DEFAULT NULL COMMENT 'plan name',
  `effectiveDate` datetime DEFAULT NULL COMMENT 'affective date',
  `policyNumber` varchar(40) DEFAULT NULL COMMENT 'policy number',
  `groupNumber` varchar(40) DEFAULT NULL COMMENT 'group number',
  `subscriberTitle` varchar(10) DEFAULT NULL COMMENT 'subscriber title',
  `subscriberGivenName` varchar(80) DEFAULT NULL COMMENT 'subscriber first name',
  `subscriberMiddleName` varchar(80) DEFAULT NULL COMMENT 'subscriber middle name',
  `subscriberSurname` varchar(80) DEFAULT NULL COMMENT 'subscriber last name',
  `subscriberStreet` varchar(80) DEFAULT NULL COMMENT 'subscriber address',
  `subscriberRelationship` varchar(40) DEFAULT NULL COMMENT 'subscriber relationship',
  `subscriberCity` varchar(80) DEFAULT NULL COMMENT 'subscriber city',
  `subscriberState` varchar(80) DEFAULT NULL COMMENT 'subscriber state',
  `subscriberCountry` varchar(80) DEFAULT NULL COMMENT 'subscriber country',
  `subscriberPostalCode` varchar(20) DEFAULT NULL COMMENT 'subscriber postal code',
  `subscriberPhone` varchar(20) DEFAULT NULL COMMENT 'subscriber phone',
  `subscriberEmployer` varchar(80) DEFAULT NULL COMMENT 'subscriber employer',
  `subscriberEmployerStreet` varchar(80) DEFAULT NULL COMMENT 'subscriber employer address',
  `subscriberEmployerCity` varchar(80) DEFAULT NULL COMMENT 'subscriber employer city',
  `subscriberEmployerState` varchar(80) DEFAULT NULL COMMENT 'subscriber employer state',
  `subscriberEmployerCountry` varchar(80) DEFAULT NULL COMMENT 'subscriber employer country',
  `subscriberEmployerPostalCode` varchar(20) DEFAULT NULL COMMENT 'subscriber employer postal code',
  `subscriberDob` datetime DEFAULT NULL COMMENT 'subscriber date of birth',
  `subscriberSS` varchar(80) DEFAULT NULL COMMENT 'subscriber social security',
  `copay` varchar(10) DEFAULT NULL COMMENT 'default copay',
  `type` varchar(40) DEFAULT NULL COMMENT 'main or supplemental',
  `createUid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `writeUid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `createDate` datetime DEFAULT NULL COMMENT 'create date',
  `updateDate` datetime DEFAULT NULL COMMENT 'last update date',
  `image` mediumtext COMMENT 'insurance image base64 string',
  `active` tinyint(1) DEFAULT '0' COMMENT '0=inactive, 1=active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_labs_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_lab_id` int(11) DEFAULT NULL,
  `observation_loinc` varchar(255) DEFAULT NULL,
  `observation_value` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_medications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `STR` varchar(200) DEFAULT NULL,
  `RXCUI` varchar(50) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `ICDS` varchar(255) DEFAULT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `ocurrence` varchar(50) DEFAULT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `outcome` varchar(50) DEFAULT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `dispense` varchar(255) DEFAULT NULL,
  `dose` varchar(25) DEFAULT NULL,
  `prescription_often` varchar(255) DEFAULT NULL,
  `prescription_when` varchar(255) DEFAULT NULL,
  `refill` varchar(255) DEFAULT NULL,
  `take_pills` int(11) DEFAULT NULL,
  `form` varchar(255) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `date_ordered` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `uid` bigint(20) DEFAULT NULL COMMENT 'user ID',
  `date` datetime NOT NULL,
  `body` longtext,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL COMMENT 'patient ID',
  `uid` int(11) DEFAULT NULL COMMENT 'user ID who created the order',
  `description` varchar(255) DEFAULT NULL COMMENT 'Order Text Description',
  `order_type` varchar(255) DEFAULT NULL COMMENT 'rad || lab || cvx || rx',
  `note` varchar(255) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL COMMENT 'encounter id',
  `code` varchar(255) DEFAULT NULL COMMENT 'Order code',
  `code_type` varchar(255) DEFAULT NULL COMMENT 'Order code type loinc',
  `date_ordered` datetime DEFAULT NULL COMMENT 'when the order was generated',
  `date_collected` datetime DEFAULT NULL COMMENT 'when the results were collected',
  `priority` varchar(255) DEFAULT NULL COMMENT 'order priority',
  `status` varchar(255) DEFAULT NULL COMMENT 'order status',
  `resultsDoc` varchar(255) DEFAULT NULL COMMENT 'collected results document if any',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `eid` (`eid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_chart_checkout` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `chart_out_time` datetime DEFAULT NULL,
  `chart_in_time` datetime DEFAULT NULL,
  `pool_area_id` bigint(20) DEFAULT NULL,
  `read_only` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_pools` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL COMMENT 'user id that is treating the patient',
  `eid` bigint(20) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `time_in` datetime DEFAULT NULL COMMENT 'checkin time',
  `time_out` datetime DEFAULT NULL COMMENT 'checkout time',
  `area_id` int(11) DEFAULT NULL COMMENT 'pool area id',
  `priority` varchar(255) DEFAULT NULL COMMENT 'priority 1 is the highest',
  `in_queue` tinyint(1) DEFAULT '1' COMMENT 'true = patient is in queue, false = the patient it been treated by someone',
  `checkout_timer` time DEFAULT NULL COMMENT 'timer user to automatically checkout from the pool area, and return to the previous pool area ',
  `parent_id` bigint(20) DEFAULT NULL COMMENT 'parent ID = the id of the checkin pool (this will maitain a relation between all pools of that visit)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE `patient_prescriptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `document_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_reminders` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'Patient ID',
  `uid` bigint(20) DEFAULT NULL COMMENT 'User ID',
  `date` datetime DEFAULT NULL COMMENT 'date added',
  `body` text COMMENT 'reminder body',
  `eid` bigint(20) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `patient_zone` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `zone_id` bigint(20) NOT NULL COMMENT 'zone_id = floor_plans_zones.id',
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entity that maintains patient''s account transactions ' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User/Contacts phones' AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `pool_areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `floor_plan_id` bigint(20) DEFAULT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=260 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `prices` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) DEFAULT NULL,
  `codeType` int(10) DEFAULT NULL,
  `insuranceCompanyId` bigint(20) DEFAULT NULL,
  `price` decimal(19,2) NOT NULL DEFAULT '0.00' COMMENT 'price in local currency',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  KEY `X_RXNCONSO_STR` (`STR`(767)),
  KEY `X_RXNCONSO_RXCUI` (`RXCUI`),
  KEY `X_RXNCONSO_TTY` (`TTY`),
  KEY `X_RXNCONSO_CODE` (`CODE`),
  KEY `X_RXNCONSO_SAB` (`SAB`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `RXNCUI` (
  `cui1` varchar(8) DEFAULT NULL,
  `ver_start` varchar(40) DEFAULT NULL,
  `ver_end` varchar(40) DEFAULT NULL,
  `cardinality` varchar(8) DEFAULT NULL,
  `cui2` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `RXNCUICHANGES` (
  `RXAUI` varchar(8) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  `STR` varchar(3000) DEFAULT NULL,
  `OLD_RXCUI` varchar(8) NOT NULL,
  `NEW_RXCUI` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `RXNDOC` (
  `DOCKEY` varchar(50) NOT NULL,
  `VALUE` varchar(1000) DEFAULT NULL,
  `TYPE` varchar(50) NOT NULL,
  `EXPL` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `RXNSAB` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  KEY `X_RXNSAT_ATV` (`ATV`(767)),
  KEY `X_RXNSAT_ATN` (`ATN`(767)),
  KEY `X_RXNSAT_CODE` (`CODE`),
  KEY `X_RXNSAT_SAB` (`SAB`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `RXNSTY` (
  `RXCUI` varchar(8) NOT NULL,
  `TUI` varchar(4) DEFAULT NULL,
  `STN` varchar(100) DEFAULT NULL,
  `STY` varchar(50) DEFAULT NULL,
  `ATUI` varchar(11) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `sct_concepts` (
  `ConceptId` bigint(20) NOT NULL,
  `ConceptStatus` int(11) NOT NULL,
  `FullySpecifiedName` varchar(255) NOT NULL,
  `CTV3ID` varchar(5) NOT NULL,
  `SNOMEDID` varchar(8) NOT NULL,
  `IsPrimitive` tinyint(1) NOT NULL,
  PRIMARY KEY (`ConceptId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `sct_descriptions` (
  `DescriptionId` bigint(20) NOT NULL,
  `DescriptionStatus` int(11) NOT NULL,
  `ConceptId` bigint(20) NOT NULL,
  `Term` varchar(255) NOT NULL,
  `InitialCapitalStatus` tinyint(1) NOT NULL,
  `DescriptionType` int(11) NOT NULL,
  `LanguageCode` varchar(8) NOT NULL,
  PRIMARY KEY (`DescriptionId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `sct_relationships` (
  `RelationshipId` bigint(20) NOT NULL,
  `ConceptId1` bigint(20) NOT NULL,
  `RelationshipType` bigint(20) NOT NULL,
  `ConceptId2` bigint(20) NOT NULL,
  `CharacteristicType` int(11) NOT NULL,
  `Refinability` int(11) NOT NULL,
  `RelationshipGroup` int(11) NOT NULL,
  PRIMARY KEY (`RelationshipId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `senchamodel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(50) DEFAULT NULL,
  `modelData` varchar(60000) DEFAULT NULL,
  `modelLastChange` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

CREATE TABLE `soap_snippets` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `parentId` varchar(20) DEFAULT NULL,
  `index` int(11) DEFAULT NULL,
  `text` text,
  `category` varchar(50) DEFAULT NULL,
  `leaf` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`parentId`),
  KEY `category` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `surgeries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `type_num` bigint(255) DEFAULT NULL,
  `surgery` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=369 ;

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `create_uid` int(11) DEFAULT NULL COMMENT 'create user ID',
  `update_uid` int(11) DEFAULT NULL COMMENT 'update user ID',
  `create_date` datetime DEFAULT NULL COMMENT 'create date',
  `update_date` datetime DEFAULT NULL COMMENT 'last update date',
  `username` varchar(255) DEFAULT NULL COMMENT 'username',
  `password` blob COMMENT 'password',
  `pwd_history1` blob COMMENT 'first password history backwards',
  `pwd_history2` blob COMMENT 'second password history backwards',
  `title` varchar(255) DEFAULT NULL COMMENT 'title (Mr. Mrs.)',
  `fname` varchar(255) DEFAULT NULL COMMENT 'first name',
  `mname` varchar(255) DEFAULT NULL COMMENT 'middle name',
  `lname` varchar(255) DEFAULT NULL COMMENT 'last name',
  `pin` varchar(255) DEFAULT NULL COMMENT 'pin number',
  `npi` varchar(255) DEFAULT NULL COMMENT 'National Provider Identifier',
  `fedtaxid` varchar(255) DEFAULT NULL COMMENT 'federal tax id',
  `feddrugid` varchar(255) DEFAULT NULL COMMENT 'federal drug id',
  `notes` varchar(255) DEFAULT NULL COMMENT 'notes',
  `email` varchar(255) DEFAULT NULL COMMENT 'email',
  `specialty` varchar(255) DEFAULT NULL COMMENT 'specialty',
  `taxonomy` varchar(255) DEFAULT NULL COMMENT 'taxonomy',
  `warehouse_id` int(11) DEFAULT NULL COMMENT 'default warehouse',
  `facility_id` int(11) DEFAULT NULL COMMENT 'default facility',
  `role_id` int(11) DEFAULT NULL COMMENT 'acl_user_roles relation',
  `calendar` tinyint(1) DEFAULT NULL COMMENT 'has calendar? 0=no 1=yes',
  `authorized` tinyint(1) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='User accounts' AUTO_INCREMENT=6 ;

CREATE TABLE `users_facility` (
  `tablename` varchar(64) NOT NULL,
  `table_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`tablename`,`table_id`,`facility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='joins users or patient_data to facility table';

CREATE TABLE `users_sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `login` int(11) DEFAULT NULL,
  `logout` int(11) DEFAULT NULL,
  `last_request` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

CREATE TABLE `user_settings` (
  `setting_user` bigint(20) NOT NULL DEFAULT '0',
  `setting_label` varchar(63) NOT NULL,
  `setting_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`setting_user`,`setting_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

CREATE TABLE `version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v_major` int(11) NOT NULL DEFAULT '0',
  `v_minor` int(11) NOT NULL DEFAULT '0',
  `v_patch` int(11) NOT NULL DEFAULT '0',
  `v_tag` varchar(31) NOT NULL DEFAULT '',
  `v_database` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
