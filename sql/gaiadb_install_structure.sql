SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `acl_permissions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `perm_key` varchar(100) CHARACTER SET latin1 NOT NULL,
  `perm_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `perm_cat` varchar(100) CHARACTER SET latin1 NOT NULL,
  `seq` int(5) NOT NULL COMMENT 'sequence',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permKey` (`perm_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=90 ;

CREATE TABLE IF NOT EXISTS `acl_roles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) CHARACTER SET latin1 NOT NULL,
  `role_key` varchar(40) NOT NULL,
  `seq` int(5) NOT NULL COMMENT 'Sequence',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `acl_role_perms` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_key` varchar(50) NOT NULL,
  `perm_key` varchar(50) NOT NULL,
  `value` int(5) NOT NULL DEFAULT '0',
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=296 ;

CREATE TABLE IF NOT EXISTS `acl_user_perms` (
  `id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `perm_key` varchar(50) NOT NULL,
  `value` tinyint(1) NOT NULL DEFAULT '0',
  `add_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `acl_user_roles` (
  `user_id` bigint(20) NOT NULL,
  `role_id` bigint(20) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line1` varchar(255) DEFAULT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(35) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `plus_four` varchar(4) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foreign_id` (`foreign_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS `allergies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `summary` varchar(255) DEFAULT NULL,
  `allergy_name` varchar(255) DEFAULT NULL,
  `allergy_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

CREATE TABLE IF NOT EXISTS `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) NOT NULL,
  `pvt_key` varchar(255) NOT NULL,
  `active` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `billing` (
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

CREATE TABLE IF NOT EXISTS `calendar_categories` (
  `catid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catname` varchar(100) DEFAULT NULL,
  `catcolor` varchar(50) DEFAULT NULL,
  `catdesc` text,
  `duration` bigint(20) NOT NULL DEFAULT '0',
  `cattype` int(11) NOT NULL COMMENT 'Used in grouping categories',
  PRIMARY KEY (`catid`),
  KEY `basic_cat` (`catname`,`catcolor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `calendar_events` (
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

CREATE TABLE IF NOT EXISTS `cdt_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `text` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Dental Codes' AUTO_INCREMENT=594 ;

CREATE TABLE IF NOT EXISTS `claims` (
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

CREATE TABLE IF NOT EXISTS `combo_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = active and 0 = deactive',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

CREATE TABLE IF NOT EXISTS `combo_lists_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `list_id` varchar(31) NOT NULL DEFAULT '',
  `option_value` varchar(31) NOT NULL DEFAULT '' COMMENT 'Value',
  `option_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Name or Title',
  `seq` int(11) DEFAULT '0',
  `notes` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT '1' COMMENT '1 = active and  0 = deactive',
  PRIMARY KEY (`id`,`list_id`,`option_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=721 ;

CREATE TABLE IF NOT EXISTS `cpt_codes` (
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

CREATE TABLE IF NOT EXISTS `cpt_icd` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cpt` varchar(40) NOT NULL,
  `icd` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=450629 ;

CREATE TABLE IF NOT EXISTS `cvx_codes` (
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

CREATE TABLE IF NOT EXISTS `cvx_mvx` (
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

CREATE TABLE IF NOT EXISTS `drugs` (
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

CREATE TABLE IF NOT EXISTS `drug_inventory` (
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

CREATE TABLE IF NOT EXISTS `drug_sales` (
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

CREATE TABLE IF NOT EXISTS `drug_templates` (
  `drug_id` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL DEFAULT '',
  `dosage` varchar(10) DEFAULT NULL,
  `period` int(11) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL DEFAULT '0',
  `refills` int(11) NOT NULL DEFAULT '0',
  `taxrates` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`drug_id`,`selector`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `eligibility_response` (
  `response_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `response_description` varchar(255) DEFAULT NULL,
  `response_status` enum('A','D') NOT NULL DEFAULT 'A',
  `response_vendor_id` bigint(20) DEFAULT NULL,
  `response_create_date` date DEFAULT NULL,
  `response_modify_date` date DEFAULT NULL,
  PRIMARY KEY (`response_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `eligibility_verification` (
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

CREATE TABLE IF NOT EXISTS `emergencies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounters` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_codes_cpt` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `code` varchar(255) DEFAULT NULL COMMENT 'code number',
  `charge` varchar(255) DEFAULT NULL,
  `days_of_units` text,
  `emergency` tinyint(1) NOT NULL DEFAULT '0',
  `essdt_plan` text,
  `modifiers` text,
  `place_of_service` text,
  `status` int(1) NOT NULL DEFAULT '0' COMMENT 'billing status of this cpt',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_codes_hcpcs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `code` varchar(255) DEFAULT NULL COMMENT 'code number',
  `code_type` int(11) DEFAULT NULL COMMENT 'CPT4 = 1, ICD9= 2, HCPCS = 3 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_codes_icdx` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `code` varchar(255) DEFAULT NULL COMMENT 'code number',
  `code_type` int(11) DEFAULT NULL COMMENT 'CPT4 = 1, ICD9= 2, HCPCS = 3 ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_dictation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `eid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  `dictation` longtext,
  `additional_notes` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_hcfa_1500_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `employment_related` text,
  `auto_accident` text,
  `state` text,
  `other_accident` text,
  `similar_illness_date` text,
  `unable_to_work_from` text,
  `unable_to_work_to` text,
  `hosp_date_from` text,
  `hops_date_to` text,
  `out_lab_used` text,
  `amount_charges` text,
  `medicaid_resubmission_code` text,
  `medicaid_original_reference_number` text,
  `prior_authorization_number` text,
  `replacement_claim` text,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `date` datetime DEFAULT NULL COMMENT 'date created',
  `user` varchar(255) DEFAULT NULL,
  `event` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_review_of_systems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `weight_change` varchar(255) DEFAULT NULL,
  `weakness` varchar(255) DEFAULT NULL,
  `fatigue` varchar(255) DEFAULT NULL,
  `anorexia` varchar(255) DEFAULT NULL,
  `fever` varchar(255) DEFAULT NULL,
  `chills` varchar(255) DEFAULT NULL,
  `night_sweats` varchar(255) DEFAULT NULL,
  `insomnia` varchar(255) DEFAULT NULL,
  `irritability` varchar(255) DEFAULT NULL,
  `heat_or_cold` varchar(255) DEFAULT NULL,
  `intolerance` varchar(255) DEFAULT NULL,
  `change_in_vision` varchar(255) DEFAULT NULL,
  `eye_pain` varchar(255) DEFAULT NULL,
  `family_history_of_glaucoma` varchar(255) DEFAULT NULL,
  `irritation` varchar(255) DEFAULT NULL,
  `redness` varchar(255) DEFAULT NULL,
  `excessive_tearing` varchar(255) DEFAULT NULL,
  `double_vision` varchar(255) DEFAULT NULL,
  `blind_spots` varchar(255) DEFAULT NULL,
  `photophobia` varchar(255) DEFAULT NULL,
  `hearing_loss` varchar(255) DEFAULT NULL,
  `discharge` varchar(255) DEFAULT NULL,
  `pain` varchar(255) DEFAULT NULL,
  `vertigo` varchar(255) DEFAULT NULL,
  `tinnitus` varchar(255) DEFAULT NULL,
  `frequent_colds` varchar(255) DEFAULT NULL,
  `sore_throat` varchar(255) DEFAULT NULL,
  `sinus_problems` varchar(255) DEFAULT NULL,
  `post_nasal_drip` varchar(255) DEFAULT NULL,
  `nosebleed` varchar(255) DEFAULT NULL,
  `snoring` varchar(255) DEFAULT NULL,
  `apnea` varchar(255) DEFAULT NULL,
  `breast_mass` varchar(255) DEFAULT NULL,
  `abnormal_mammogram` varchar(255) DEFAULT NULL,
  `biopsy` varchar(255) DEFAULT NULL,
  `cough` varchar(255) DEFAULT NULL,
  `sputum` varchar(255) DEFAULT NULL,
  `shortness_of_breath` varchar(255) DEFAULT NULL,
  `wheezing` varchar(255) DEFAULT NULL,
  `hemoptysis` varchar(255) DEFAULT NULL,
  `asthma` varchar(255) DEFAULT NULL,
  `copd` varchar(255) DEFAULT NULL,
  `thyroid_problems` varchar(255) DEFAULT NULL,
  `diabetes` varchar(255) DEFAULT NULL,
  `abnormal_blood_test` varchar(255) DEFAULT NULL,
  `chest_pain` varchar(255) DEFAULT NULL,
  `palpitation` varchar(255) DEFAULT NULL,
  `syncope` varchar(255) DEFAULT NULL,
  `pnd` varchar(255) DEFAULT NULL,
  `doe` varchar(255) DEFAULT NULL,
  `orthopnea` varchar(255) DEFAULT NULL,
  `peripheral` varchar(255) DEFAULT NULL,
  `edema` varchar(255) DEFAULT NULL,
  `leg_pain_cramping` varchar(255) DEFAULT NULL,
  `arrythmia` varchar(255) DEFAULT NULL,
  `heart_problem` varchar(255) DEFAULT NULL,
  `history_of_heart_murmur` varchar(255) DEFAULT NULL,
  `polyuria` varchar(255) DEFAULT NULL,
  `polydypsia` varchar(255) DEFAULT NULL,
  `dysuria` varchar(255) DEFAULT NULL,
  `hematuria` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `urgency` varchar(255) DEFAULT NULL,
  `utis` varchar(255) DEFAULT NULL,
  `incontinence` varchar(255) DEFAULT NULL,
  `renal_stones` varchar(255) DEFAULT NULL,
  `hesitancy` varchar(255) DEFAULT NULL,
  `dribbling` varchar(255) DEFAULT NULL,
  `stream` varchar(255) DEFAULT NULL,
  `nocturia` varchar(255) DEFAULT NULL,
  `erections` varchar(255) DEFAULT NULL,
  `ejaculations` varchar(255) DEFAULT NULL,
  `cancer` varchar(255) DEFAULT NULL,
  `psoriasis` varchar(255) DEFAULT NULL,
  `acne` varchar(255) DEFAULT NULL,
  `disease` varchar(255) DEFAULT NULL,
  `other` varchar(255) DEFAULT NULL,
  `anemia` varchar(255) DEFAULT NULL,
  `hiv` varchar(255) DEFAULT NULL,
  `f_h_blood_problems` varchar(255) DEFAULT NULL,
  `hai_status` text,
  `allergies` text,
  `bleeding_problems` text,
  `frequent_illness` text,
  `dysphagia` text,
  `heartburn` text,
  `food_intolerance` text,
  `belching` text,
  `bloating` text,
  `flatulence` text,
  `nausea` text,
  `vomiting` text,
  `jaundice` text,
  `h_o_hepatitis` text,
  `hematemesis` text,
  `diarrhea` text,
  `hematochezia` text,
  `changed_bowel` text,
  `constipation` text,
  `female_g` text,
  `female_p` text,
  `female_ap` text,
  `lmp` text,
  `female_lc` text,
  `menopause` text,
  `flow` text,
  `abnormal_hair_growth` text,
  `menarche` text,
  `symptoms` text,
  `f_h_female_hirsutism_striae` text,
  `anxiety` text,
  `depression` text,
  `psychiatric_medication` text,
  `social_difficulties` text,
  `psychiatric_diagnosis` text,
  `fms` text,
  `swelling` text,
  `Warm` text,
  `muscle` text,
  `stiffness` text,
  `aches` text,
  `arthritis` text,
  `chronic_joint_pain` text,
  `loc` text,
  `stroke` text,
  `paralysis` text,
  `tia` text,
  `numbness` text,
  `memory_problems` text,
  `seizures` text,
  `intellectual_decline` text,
  `dementia` text,
  `headache` text,
  `cons_weakness` text,
  `brest_discharge` varchar(255) DEFAULT NULL,
  `fem_frequency` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_review_of_systems_check` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_soap` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `encounter_vitals` (
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

CREATE TABLE IF NOT EXISTS `facility` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 if the facility is active and 0 if is inactive',
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `postal_code` varchar(11) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `federal_ein` varchar(15) DEFAULT NULL,
  `service_location` tinyint(1) NOT NULL DEFAULT '1',
  `billing_location` tinyint(1) NOT NULL DEFAULT '0',
  `accepts_assignment` tinyint(1) NOT NULL DEFAULT '0',
  `pos_code` tinyint(4) DEFAULT NULL,
  `x12_sender_id` varchar(25) DEFAULT NULL,
  `attn` varchar(65) DEFAULT NULL,
  `domain_identifier` varchar(60) DEFAULT NULL,
  `facility_npi` varchar(15) DEFAULT NULL,
  `tax_id_type` varchar(31) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `fee_sheet_options` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `fs_category` varchar(63) DEFAULT NULL,
  `fs_option` varchar(63) DEFAULT NULL,
  `fs_codes` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `floor_plans` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `floor_plans_zones` (
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

CREATE TABLE IF NOT EXISTS `forms_fields` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `form_id` bigint(11) DEFAULT NULL,
  `xtype` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `parentId` bigint(11) DEFAULT NULL,
  `pos` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_bin AUTO_INCREMENT=1146 ;

CREATE TABLE IF NOT EXISTS `forms_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` text COMMENT 'Field ID',
  `options` text COMMENT 'Field options data stored as JSON string',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1521 ;

CREATE TABLE IF NOT EXISTS `forms_layout` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'form title',
  `form_data` varchar(255) NOT NULL COMMENT 'database table saving all the data for this form',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

CREATE TABLE IF NOT EXISTS `form_misc_billing_options` (
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

CREATE TABLE IF NOT EXISTS `geo_country_reference` (
  `countries_id` int(5) NOT NULL AUTO_INCREMENT,
  `countries_name` varchar(64) DEFAULT NULL,
  `countries_iso_code_2` char(2) NOT NULL DEFAULT '',
  `countries_iso_code_3` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`countries_id`),
  KEY `IDX_COUNTRIES_NAME` (`countries_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=240 ;

CREATE TABLE IF NOT EXISTS `geo_zone_reference` (
  `zone_id` int(5) NOT NULL AUTO_INCREMENT,
  `zone_country_id` int(5) NOT NULL DEFAULT '0',
  `zone_code` varchar(5) DEFAULT NULL,
  `zone_name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`zone_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

CREATE TABLE IF NOT EXISTS `globals` (
  `gl_name` varchar(63) NOT NULL,
  `gl_index` int(11) NOT NULL DEFAULT '0',
  `gl_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`gl_name`,`gl_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` longtext,
  `user` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `hcpcs_codes` (
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
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `icd9_dx_code` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd9_dx_long_code` (
  `dx_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_code` varchar(5) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `dx_id` (`dx_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd9_sg_code` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd9_sg_long_code` (
  `sq_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sg_code` varchar(5) DEFAULT NULL,
  `long_desc` varchar(300) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `sq_id` (`sq_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_dx_order_code` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_gem_dx_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_icd9_source` varchar(5) DEFAULT NULL,
  `dx_icd10_target` varchar(7) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_gem_dx_10_9` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `dx_icd10_source` varchar(7) DEFAULT NULL,
  `dx_icd9_target` varchar(5) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_gem_pcs_9_10` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_icd9_source` varchar(5) DEFAULT NULL,
  `pcs_icd10_target` varchar(7) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_gem_pcs_10_9` (
  `map_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pcs_icd10_source` varchar(7) DEFAULT NULL,
  `pcs_icd9_target` varchar(5) DEFAULT NULL,
  `flags` varchar(5) DEFAULT NULL,
  `active` tinyint(4) DEFAULT '0',
  `revision` int(11) DEFAULT '0',
  UNIQUE KEY `map_id` (`map_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_pcs_order_code` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_reimbr_dx_9_10` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `icd10_reimbr_pcs_9_10` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `immunizations` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28778 ;

CREATE TABLE IF NOT EXISTS `immunizations_relations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `immunization_id` bigint(20) DEFAULT NULL,
  `foreign_id` bigint(20) DEFAULT NULL,
  `code_type` varchar(255) DEFAULT NULL COMMENT 'medication,active problem or labs',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

CREATE TABLE IF NOT EXISTS `insurance_companies` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `attn` varchar(255) DEFAULT NULL,
  `cms_id` varchar(15) DEFAULT NULL,
  `freeb_type` tinyint(2) DEFAULT NULL,
  `x12_receiver_id` varchar(25) DEFAULT NULL,
  `x12_default_partner_id` int(11) DEFAULT NULL,
  `alt_cms_id` varchar(15) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `insurance_data` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `insurance_numbers` (
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

CREATE TABLE IF NOT EXISTS `issue_encounter` (
  `pid` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `encounter` int(11) NOT NULL,
  `resolved` tinyint(1) NOT NULL,
  PRIMARY KEY (`pid`,`list_id`,`encounter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `laboratories` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `transmit_method` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `labs_guidelines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) DEFAULT NULL,
  `less_than` float DEFAULT NULL,
  `greater_than` float DEFAULT NULL,
  `equal_to` float DEFAULT NULL,
  `preventive_care_id` int(11) DEFAULT NULL,
  `value_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

CREATE TABLE IF NOT EXISTS `labs_loinc` (
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

CREATE TABLE IF NOT EXISTS `labs_panels` (
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
  `type_of_entry` text,
  `description` text,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `labs_preventive_care` (
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

CREATE TABLE IF NOT EXISTS `log` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `medications` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48886 ;

CREATE TABLE IF NOT EXISTS `messages` (
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

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `enable` tinyint(1) DEFAULT '0',
  `installed_version` varchar(25) DEFAULT NULL,
  `licensekey` varchar(255) DEFAULT NULL,
  `localkey` longblob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;

CREATE TABLE IF NOT EXISTS `modules_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` int(11) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `data` longtext,
  PRIMARY KEY (`id`),
  KEY `data` (`data`(767))
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='this table if a convivnient table to store module related data' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `notes` (
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

CREATE TABLE IF NOT EXISTS `notification_log` (
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

CREATE TABLE IF NOT EXISTS `notification_settings` (
  `SettingsId` int(3) NOT NULL AUTO_INCREMENT,
  `Send_SMS_Before_Hours` int(3) NOT NULL,
  `Send_Email_Before_Hours` int(3) NOT NULL,
  `SMS_gateway_username` varchar(100) NOT NULL,
  `SMS_gateway_password` varchar(100) NOT NULL,
  `SMS_gateway_apikey` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`SettingsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `onotes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `body` longtext,
  `user` varchar(255) DEFAULT NULL,
  `facility_id` bigint(20) DEFAULT NULL,
  `activity` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

CREATE TABLE IF NOT EXISTS `patients_documents_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_account` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT '0',
  `eid` bigint(20) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Entity that maintains patient''s account transactions ' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_active_problems` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `code` varchar(255) DEFAULT NULL,
  `code_text` varchar(255) DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_allergies` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `allergy_type` varchar(50) DEFAULT NULL,
  `allergy` varchar(50) DEFAULT NULL,
  `allergy_id` bigint(20) DEFAULT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `reaction` varchar(50) DEFAULT NULL,
  `severity` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_demographics` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table holds all the Demographics form data for all the patie' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_dental` (
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

CREATE TABLE IF NOT EXISTS `patient_disclosures` (
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

CREATE TABLE IF NOT EXISTS `patient_doctors_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `pid` int(11) DEFAULT NULL,
  `document_id` varchar(255) DEFAULT NULL,
  `doctors_notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_documents` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `eid` int(11) DEFAULT NULL,
  `pid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `docType` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT 'No title',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_immunizations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `immunization_name` varchar(255) DEFAULT NULL,
  `administered_date` datetime DEFAULT NULL,
  `immunization_id` bigint(20) DEFAULT NULL COMMENT 'immunization ID from codes table',
  `manufacturer` varchar(100) DEFAULT NULL,
  `administered_by` varchar(255) DEFAULT NULL,
  `lot_number` varchar(50) DEFAULT NULL,
  `education_date` datetime DEFAULT NULL,
  `note` text,
  `dosis` int(11) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_labs` (
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

CREATE TABLE IF NOT EXISTS `patient_labs_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_lab_id` int(11) DEFAULT NULL,
  `observation_loinc` varchar(255) DEFAULT NULL,
  `observation_value` varchar(255) DEFAULT NULL,
  `unit` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_medications` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `STR` varchar(200) DEFAULT NULL,
  `RXCUI` varchar(50) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `ICDS` varchar(255) DEFAULT NULL,
  `begin_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `ocurrence` varchar(50) DEFAULT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_uid` bigint(20) DEFAULT NULL COMMENT 'created by User ID',
  `updated_uid` bigint(20) DEFAULT NULL COMMENT 'updated by User ID',
  `outcome` varchar(50) DEFAULT NULL,
  `prescription_id` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `dispense` int(11) DEFAULT NULL,
  `dose` varchar(25) DEFAULT NULL,
  `prescription_often` varchar(255) DEFAULT NULL,
  `prescription_when` varchar(255) DEFAULT NULL,
  `refill` int(11) DEFAULT NULL,
  `take_pills` int(11) DEFAULT NULL,
  `form` varchar(255) DEFAULT NULL,
  `codingsystem` varchar(255) NOT NULL DEFAULT 'RXNORM',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_notes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'patient ID',
  `eid` bigint(20) DEFAULT NULL COMMENT 'encounter ID',
  `uid` bigint(20) DEFAULT NULL COMMENT 'user ID',
  `date` datetime NOT NULL,
  `body` longtext,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `laboratory_id` bigint(20) DEFAULT NULL COMMENT 'laboratory facility ID',
  `document_id` int(11) DEFAULT NULL COMMENT 'patien document ID',
  `order_type` varchar(100) DEFAULT NULL COMMENT 'lab or xray',
  `order_items` varchar(255) DEFAULT NULL COMMENT 'order LOINCs serialize by comma  ei. 1234-1,4123-1',
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_out_chart` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `chart_out_time` datetime DEFAULT NULL,
  `chart_in_time` datetime DEFAULT NULL,
  `pool_area_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_pools` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL COMMENT 'user id that is treating the patient',
  `eid` bigint(20) DEFAULT NULL,
  `time_in` datetime DEFAULT NULL COMMENT 'checkin time',
  `time_out` datetime DEFAULT NULL COMMENT 'checkout time',
  `area_id` int(11) DEFAULT NULL COMMENT 'pool area id',
  `priority` varchar(255) DEFAULT NULL COMMENT 'priority 1 is the highest',
  `in_queue` tinyint(1) DEFAULT '1' COMMENT 'true = patient is in queue, false = the patient it been treated by someone',
  `checkout_timer` time DEFAULT NULL COMMENT 'timer user to automatically checkout from the pool area, and return to the previous pool area ',
  `parent_id` bigint(20) NOT NULL COMMENT 'parent ID = the id of the checkin pool (this will maitain a relation between all pools of that visit)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_prescriptions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL,
  `eid` bigint(20) DEFAULT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `document_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_referrals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL COMMENT 'Patient ID',
  `date_created` date NOT NULL COMMENT 'date form saved for the first time',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table holds all the Referrals form data for all the patients' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_reminders` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) DEFAULT NULL COMMENT 'Patient ID',
  `uid` bigint(20) DEFAULT NULL COMMENT 'User ID',
  `date` datetime DEFAULT NULL COMMENT 'date added',
  `body` text COMMENT 'reminder body',
  `eid` bigint(20) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `patient_surgery` (
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

CREATE TABLE IF NOT EXISTS `patient_zone` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) NOT NULL,
  `uid` bigint(20) DEFAULT NULL,
  `zone_id` bigint(20) NOT NULL COMMENT 'zone_id = floor_plans_zones.id',
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `payments` (
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

CREATE TABLE IF NOT EXISTS `payment_transactions` (
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

CREATE TABLE IF NOT EXISTS `pharmacies` (
  `id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `transmit_method` int(11) NOT NULL DEFAULT '1',
  `email` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `phone_numbers` (
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

CREATE TABLE IF NOT EXISTS `pnotes` (
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

CREATE TABLE IF NOT EXISTS `pool_areas` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `floor_plan_id` bigint(20) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `prescriptions` (
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

CREATE TABLE IF NOT EXISTS `preventive_care_guidelines` (
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `prices` (
  `pr_id` varchar(11) NOT NULL DEFAULT '',
  `pr_selector` varchar(255) NOT NULL DEFAULT '' COMMENT 'template selector for drugs, empty for codes',
  `pr_level` varchar(31) NOT NULL DEFAULT '',
  `pr_price` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'price in local currency',
  PRIMARY KEY (`pr_id`,`pr_selector`,`pr_level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `procedure_order` (
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

CREATE TABLE IF NOT EXISTS `procedure_report` (
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

CREATE TABLE IF NOT EXISTS `procedure_result` (
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

CREATE TABLE IF NOT EXISTS `procedure_type` (
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

CREATE TABLE IF NOT EXISTS `rxnatomarchive` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxnconso` (
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
  KEY `X_RXNCONSO_STR` (`STR`(1000)),
  KEY `X_RXNCONSO_RXCUI` (`RXCUI`),
  KEY `X_RXNCONSO_TTY` (`TTY`),
  KEY `X_RXNCONSO_CODE` (`CODE`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxncui` (
  `cui1` varchar(8) DEFAULT NULL,
  `ver_start` varchar(20) DEFAULT NULL,
  `ver_end` varchar(20) DEFAULT NULL,
  `cardinality` varchar(8) DEFAULT NULL,
  `cui2` varchar(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxncuichanges` (
  `RXAUI` varchar(8) DEFAULT NULL,
  `CODE` varchar(50) DEFAULT NULL,
  `SAB` varchar(20) DEFAULT NULL,
  `TTY` varchar(20) DEFAULT NULL,
  `STR` varchar(3000) DEFAULT NULL,
  `OLD_RXCUI` varchar(8) NOT NULL,
  `NEW_RXCUI` varchar(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxndoc` (
  `DOCKEY` varchar(50) NOT NULL,
  `VALUE` varchar(1000) DEFAULT NULL,
  `TYPE` varchar(50) NOT NULL,
  `EXPL` varchar(1000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxnrel` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxnsab` (
  `VCUI` varchar(8) DEFAULT NULL,
  `RCUI` varchar(8) DEFAULT NULL,
  `VSAB` varchar(20) DEFAULT NULL,
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxnsat` (
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
  KEY `X_RXNSAT_ATV` (`ATV`(1000)),
  KEY `X_RXNSAT_ATN` (`ATN`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `rxnsty` (
  `RXCUI` varchar(8) NOT NULL,
  `TUI` varchar(4) DEFAULT NULL,
  `STN` varchar(100) DEFAULT NULL,
  `STY` varchar(50) DEFAULT NULL,
  `ATUI` varchar(11) DEFAULT NULL,
  `CVF` varchar(50) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `sct_concepts` (
  `ConceptId` bigint(20) NOT NULL,
  `ConceptStatus` int(11) NOT NULL,
  `FullySpecifiedName` varchar(255) NOT NULL,
  `CTV3ID` varchar(5) NOT NULL,
  `SNOMEDID` varchar(8) NOT NULL,
  `IsPrimitive` tinyint(1) NOT NULL,
  PRIMARY KEY (`ConceptId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `sct_descriptions` (
  `DescriptionId` bigint(20) NOT NULL,
  `DescriptionStatus` int(11) NOT NULL,
  `ConceptId` bigint(20) NOT NULL,
  `Term` varchar(255) NOT NULL,
  `InitialCapitalStatus` tinyint(1) NOT NULL,
  `DescriptionType` int(11) NOT NULL,
  `LanguageCode` varchar(8) NOT NULL,
  PRIMARY KEY (`DescriptionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `sct_relationships` (
  `RelationshipId` bigint(20) NOT NULL,
  `ConceptId1` bigint(20) NOT NULL,
  `RelationshipType` bigint(20) NOT NULL,
  `ConceptId2` bigint(20) NOT NULL,
  `CharacteristicType` int(11) NOT NULL,
  `Refinability` int(11) NOT NULL,
  `RelationshipGroup` int(11) NOT NULL,
  PRIMARY KEY (`RelationshipId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `standardized_tables_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_type` varchar(50) DEFAULT NULL,
  `imported_date` datetime DEFAULT NULL,
  `revision_name` varchar(255) DEFAULT NULL COMMENT 'name of standardized tables such as RXNORM',
  `revision_number` varchar(255) DEFAULT NULL,
  `revision_version` varchar(255) DEFAULT NULL COMMENT 'revision of standardized tables that were imported',
  `revision_date` varchar(255) DEFAULT NULL COMMENT 'revision of standardized tables that were imported',
  `file_checksum` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `surgeries` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `type_num` bigint(255) DEFAULT NULL,
  `surgery` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=369 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` blob,
  `authorized` tinyint(1) NOT NULL DEFAULT '0',
  `info` longtext,
  `source` tinyint(4) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `mname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `federaltaxid` varchar(255) DEFAULT NULL,
  `federaldrugid` varchar(255) DEFAULT NULL,
  `upin` varchar(255) DEFAULT NULL,
  `facility` varchar(255) DEFAULT NULL,
  `facility_id` int(11) NOT NULL DEFAULT '0',
  `see_auth` int(11) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `npi` varchar(15) DEFAULT NULL,
  `title` varchar(30) DEFAULT NULL,
  `specialty` varchar(255) DEFAULT NULL,
  `billname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `assistant` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `valedictory` varchar(255) DEFAULT NULL,
  `street` varchar(60) DEFAULT NULL,
  `streetb` varchar(60) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `street2` varchar(60) DEFAULT NULL,
  `streetb2` varchar(60) DEFAULT NULL,
  `city2` varchar(30) DEFAULT NULL,
  `state2` varchar(30) DEFAULT NULL,
  `zip2` varchar(20) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `phonew1` varchar(30) DEFAULT NULL,
  `phonew2` varchar(30) DEFAULT NULL,
  `phonecell` varchar(30) DEFAULT NULL,
  `notes` text,
  `cal_ui` tinyint(4) NOT NULL DEFAULT '1',
  `taxonomy` varchar(30) NOT NULL DEFAULT '207Q00000X',
  `ssi_relayhealth` varchar(64) DEFAULT NULL,
  `calendar` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = appears in calendar',
  `abook_type` varchar(31) DEFAULT NULL,
  `pwd_expiration_date` date DEFAULT NULL,
  `pwd_history1` longtext,
  `pwd_history2` longtext,
  `default_warehouse` varchar(31) DEFAULT NULL,
  `irnpool` varchar(31) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

CREATE TABLE IF NOT EXISTS `users_facility` (
  `tablename` varchar(64) NOT NULL,
  `table_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`tablename`,`table_id`,`facility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='joins users or patient_data to facility table';

CREATE TABLE IF NOT EXISTS `users_sessions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sid` varchar(255) NOT NULL,
  `uid` bigint(20) NOT NULL,
  `login` int(11) DEFAULT NULL,
  `logout` int(11) DEFAULT NULL,
  `last_request` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user_settings` (
  `setting_user` bigint(20) NOT NULL DEFAULT '0',
  `setting_label` varchar(63) NOT NULL,
  `setting_value` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`setting_user`,`setting_label`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `vector_graphs` (
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

CREATE TABLE IF NOT EXISTS `version` (
  `v_major` int(11) NOT NULL DEFAULT '0',
  `v_minor` int(11) NOT NULL DEFAULT '0',
  `v_patch` int(11) NOT NULL DEFAULT '0',
  `v_tag` varchar(31) NOT NULL DEFAULT '',
  `v_database` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `x12_partners` (
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