<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once (dirname(__FILE__).'/Segments.php');

class PID extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'PID');

		$this->setField(1, 'SI', 4);
		$this->setField(2, 'CX', 20);
		$this->setField(3, 'CX', 250, true, true);
		$this->setField(4, 'CX', 20, false, true);
		$this->setField(5, 'XPN', 250, true, true);
		$this->setField(6, 'XPN', 250, false, true);
		$this->setField(7, 'TS', 26);
		$this->setField(8, 'IS', 1);
		$this->setField(9, 'XPN', 250, false, true);
		/**
		 * PID-10 Race
		 * User-defined Table 0005 - Race
		 * Value Description Comment
		 * 1002-5 American Indian or Alaska Native
		 * 2028-9 Asian
		 * 2054-5 Black or African American
		 * 2076-8 Native Hawaiian or Other Pacific Islander
		 * 2106-3 White
		 * 2131-1 Other Race
		 */
		$this->setField(10, 'CE', 250, false, true);
		$this->setField(11, 'XAD', 250, false, true);
		$this->setField(12, 'IS', 4);
		$this->setField(13, 'XTN', 250, false, true);
		$this->setField(14, 'XTN', 250, false, true);
		$this->setField(15, 'CE', 250);
		$this->setField(16, 'CE', 250);
		$this->setField(17, 'CE', 250);
		$this->setField(18, 'CX', 250);
		$this->setField(19, 'ST', 16);
		$this->setField(20, 'DLN', 25);
		$this->setField(21, 'CX', 250, false, true);
		$this->setField(22, 'CE', 250, false, true);
		$this->setField(23, 'ST', 250);
		/**
		 * PID-24 Multiple Birth Indicator
		 * Y Yes
		 * N No
		 */
		$this->setField(24, 'ID', 1);
		$this->setField(25, 'NM', 2);
		$this->setField(26, 'CE', 250, false, true);
		$this->setField(27, 'CE', 250);
		$this->setField(28, 'CE', 250);
		$this->setField(29, 'TS', 26);
		/**
		 * PID-30 Patient Death Indicator
		 * Y the patient is deceased
		 * N the patient is not deceased
		 */
		$this->setField(30, 'ID', 1);
		/**
		 * PID-31 Identity Unknown Indicator
		 * Y the patient’s/person’s identity is unknown
		 * N the patient’s/person’s identity is known
		 */
		$this->setField(31, 'ID', 1);
		/**
		 * PID-32 Identity Reliability Code
		 * US Unknown/Default Social Security Number
		 * UD Unknown/Default Date of Birth
		 * UA Unknown/Default Address
		 * AL Patient/Person Name is an Alias
		 */
		$this->setField(32, 'IS', 20, false, true);
		$this->setField(33, 'TS', 26);
		$this->setField(34, 'HD', 241);
		$this->setField(35, 'CE', 250);
		$this->setField(36, 'CE', 250);
		/**
		 * PID-37 Strain
		 * The specific breed of animal. This field, unlike Species and Strain is specific to animals and
		 * cannot be generally used for all living organisms. SNOMED is the recommended coding system. Refer to
		 * User-defined Table 0447 - Breed Code for suggested values.
		 */
		$this->setField(37, 'ST', 80);
		/**
		 * PID-38 Production Class Code
		 * BR Breeding/genetic stock
		 * DA Dairy
		 * DR Draft
		 * DU Dual Purpose
		 * LY Layer, Includes Multiplier flocks
		 * MT Meat
		 * OT Other
		 * PL Pleasure
		 * RA Racing
		 * SH Show
		 * NA Not Applicable
		 * U Unknown
		 */
		$this->setField(38, 'CE', 250, false, true);
		$this->setField(39, 'CWE', 250, false, true);


	}
}