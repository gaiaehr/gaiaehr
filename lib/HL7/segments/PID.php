<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (str_replace('\\', '/',__DIR__).'/Segments.php');

class PID extends Segments{

	function __construct(){

		$this->rawSeg = array();
		$this->rawSeg[0] = 'PID';                   // MSH Message Header Segment
		/**
		 * PID-1 Set ID - PID (SI)
		 */
		$this->rawSeg[1] = '';
		/**
		 * PID-2 Patient ID (CX)
		 */
		$this->rawSeg[2] = $this->getType('CX');
		/**
		 * PID-3 Patient Identifier List (CX)
		 */
		$this->rawSeg[3] = $this->getType('CX');
		/**
		 * PID-4 Alternate Patient ID - PID (CX)
		 */
		$this->rawSeg[4] = $this->getType('CX');
		/**
		 * PID-5 Patient Name (XPN)
		 */
		$this->rawSeg[5] = $this->getType('XPN');
		/**
		 * PID-6 Mother's Maiden Name (XPN)
		 */
		$this->rawSeg[6] = $this->getType('XPN');
		/**
		 * PID-7 Date/Time of Birth (TS)
		 */
		$this->rawSeg[7] = $this->getType('TS');
		/**
		 * PID-8 Administrative Sex (IS)
		 */
		$this->rawSeg[8] = '';
		/**
		 * PID-9 Patient Alias (XPN)
		 */
		$this->rawSeg[9] = $this->getType('XPN');
		/**
		 * PID-10 Race (CE)
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
		/**
		 * PID-11 Patient Address (XAD)
		 */
		$this->rawSeg[11] =  $this->getType('XAD');
		/**
		 * PID-12 PID-12 County Code (IS)
		 */
		$this->rawSeg[12] = '';
		/**
		 * PID-13 Phone Number - Home (XTN)
		 */
		$this->rawSeg[13] =  $this->getType('XTN');
		/**
		 * PID-14 Phone Number - Business (XTN)
		 */
		$this->rawSeg[14] =  $this->getType('XTN');
		/**
		 * PID-15 Primary Language (CE)
		 */
		$this->rawSeg[15] =  $this->getType('CE');
		/**
		 * PID-16 Marital Status (CE)
		 */
		$this->rawSeg[16] =  $this->getType('CE');
		/**
		 * PID-17 Religion (CE) (CE)
		 */
		$this->rawSeg[17] =  $this->getType('CE');
		/**
		 * PID-18 Patient Account Number (CX)
		 */
		$this->rawSeg[18] =  $this->getType('CX');
		/**
		 * PID-19 SSN Number - Patient (ST)
		 */
		$this->rawSeg[19] =  '';
		/**
		 * PID-20 Driver's License Number - Patient (DLN)
		 */
		$this->rawSeg[20] =  $this->getType('DLN');
		/**
		 * PID-21 Mother's Identifier (CX)
		 */
		$this->rawSeg[21] =  $this->getType('CX');
		/**
		 * PID-22 Ethnic Group (CE)
		 */
		$this->rawSeg[22] =  $this->getType('CE');
		/**
		 * PID-23 Birth Place (ST)
		 */
		$this->rawSeg[23] =  '';
		/**
		 * PID-24 Multiple Birth Indicator (ID)
		 * Y Yes
		 * N No
		 */
		$this->rawSeg[24] =  '';
		/**
		 * PID-25 Birth Order (NM)
		 */
		$this->rawSeg[25] =  '';
		/**
		 * PID-26 Citizenship (CE)
		 */
		$this->rawSeg[26] = $this->getType('CE');
		/**
		 * PID-27 Veterans Military Status (CE)
		 */
		$this->rawSeg[27] = $this->getType('CE');
		/**
		 * PID-28 Nationality (CE)
		 */
		$this->rawSeg[28] = $this->getType('CE');
		/**
		 * PID-29 Patient Death Date and Time (TS)
		 */
		$this->rawSeg[29] = $this->getType('TS');
		/**
		 * PID-30 Patient Death Indicator (ID)
		 * Y the patient is deceased
		 * N the patient is not deceased
		 */
		$this->rawSeg[30] = '';
		/**
		 * PID-31 Identity Unknown Indicator (ID)
		 * Y the patient’s/person’s identity is unknown
		 * N the patient’s/person’s identity is known
		 */
		$this->rawSeg[31] = '';
		/**
		 * PID-32 Identity Reliability Code (IS)
		 * US Unknown/Default Social Security Number
		 * UD Unknown/Default Date of Birth
		 * UA Unknown/Default Address
		 * AL Patient/Person Name is an Alias
		 */
		$this->rawSeg[32] = '';
		/**
		 * PID-33 Last Update Date/Time (TS)
		 */
		$this->rawSeg[33] = $this->getType('TS');
		/**
		 * PID-34 Last Update Facility (HD)
		 */
		$this->rawSeg[34] = $this->getType('HD');
		/**
		 * PID-35 Species Code (CE)
		 */
		$this->rawSeg[35] = $this->getType('CE');
		/**
		 * PID-36 Breed Code (CE)
		 */
		$this->rawSeg[36] = $this->getType('CE');
		/**
		 * PID-37 Strain (ST)
		 * The specific breed of animal. This field, unlike Species and Strain is specific to animals and
		 * cannot be generally used for all living organisms. SNOMED is the recommended coding system. Refer to
		 * User-defined Table 0447 - Breed Code for suggested values.
		 */
		$this->rawSeg[37] =  '';
		/**
		 * PID-38 Production Class Code (CE)
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
		/**
		 * PID-39 Tribal Citizenship (CWE)
		 */
		$this->rawSeg[39] = $this->getType('CWE');


	}
}