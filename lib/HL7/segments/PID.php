<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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
include_once (str_replace('\\', '/',__DIR__).'/Segments.php');

class PID extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'PID';                   // PID Message Header Segment
		$this->rawSeg[1] = $this->getType('SI');
		$this->rawSeg[2] = $this->getType('CX');
		$this->rawSeg[3] = $this->getType('CX');
		$this->rawSeg[4] = $this->getType('CX');
		$this->rawSeg[5] = $this->getType('XPN');
		$this->rawSeg[6] = $this->getType('XPN');
		$this->rawSeg[7] = $this->getType('TS');
		$this->rawSeg[8] = $this->getType('IS');
		$this->rawSeg[9] = $this->getType('XPN');
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
		$this->rawSeg[10] = $this->getType('CE');
		$this->rawSeg[11] = $this->getType('XAD');
		$this->rawSeg[12] = $this->getType('IS');
		$this->rawSeg[13] = $this->getType('XTN');
		$this->rawSeg[14] = $this->getType('XTN');
		$this->rawSeg[15] = $this->getType('CE');
		$this->rawSeg[16] = $this->getType('CE');
		$this->rawSeg[17] = $this->getType('CE');
		$this->rawSeg[18] = $this->getType('CX');
		$this->rawSeg[19] = $this->getType('ST');
		$this->rawSeg[20] = $this->getType('DLN');
		$this->rawSeg[21] = $this->getType('CX');
		$this->rawSeg[22] = $this->getType('CE');
		$this->rawSeg[23] = $this->getType('ST');
		/**
		 * PID-24 Multiple Birth Indicator
		 * Y Yes
		 * N No
		 */
		$this->rawSeg[24] = $this->getType('ID');
		$this->rawSeg[25] = $this->getType('NM');
		$this->rawSeg[26] = $this->getType('CE');
		$this->rawSeg[27] = $this->getType('CE');
		$this->rawSeg[28] = $this->getType('CE');
		$this->rawSeg[29] = $this->getType('TS');
		/**
		 * PID-30 Patient Death Indicator
		 * Y the patient is deceased
		 * N the patient is not deceased
		 */
		$this->rawSeg[30] = $this->getType('ID');
		/**
		 * PID-31 Identity Unknown Indicator
		 * Y the patient’s/person’s identity is unknown
		 * N the patient’s/person’s identity is known
		 */
		$this->rawSeg[31] = $this->getType('ID');
		/**
		 * PID-32 Identity Reliability Code
		 * US Unknown/Default Social Security Number
		 * UD Unknown/Default Date of Birth
		 * UA Unknown/Default Address
		 * AL Patient/Person Name is an Alias
		 */
		$this->rawSeg[32] = $this->getType('IS');
		$this->rawSeg[33] = $this->getType('TS');
		$this->rawSeg[34] = $this->getType('HD');
		$this->rawSeg[35] = $this->getType('CE');
		$this->rawSeg[36] = $this->getType('CE');
		/**
		 * PID-37 Strain
		 * The specific breed of animal. This field, unlike Species and Strain is specific to animals and
		 * cannot be generally used for all living organisms. SNOMED is the recommended coding system. Refer to
		 * User-defined Table 0447 - Breed Code for suggested values.
		 */
		$this->rawSeg[37] =  $this->getType('ST');
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
		$this->rawSeg[38] = $this->getType('CE');
		$this->rawSeg[39] = $this->getType('CWE');


	}
}