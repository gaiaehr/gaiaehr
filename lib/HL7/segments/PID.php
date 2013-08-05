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
		 * Table 0001 - Administrative Sex
		 * Value Description Comment
	 	 * F Female
		 * M Male
		 * O Other
		 * U Unknown
		 * A Ambiguous
		 * N Not applicable
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
		$this->rawSeg[12][0] = '';
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
		 * Value Description Comment
		 * A Separated
		 * D Divorced
		 * M Married
		 * S Single
		 * C Common law
		 * G Living together
		 * P Domestic partner
		 * R Registered domestic partner
	 	 * E Legally Separated
		 * N Annulled
		 * I Interlocutory
		 * B Unmarried
		 * O Other
		 * T Unreported
		 */
		$this->rawSeg[16] =  $this->getType('CE');
		/**
		 * PID-17 Religion (CE) (CE)
		 * Value Description Comment
		 * AGN Agnostic
		 * ATH Atheist
		 * BAH Baha'i
		 * BUD Buddhist
		 * BMA Buddhist: Mahayana
		 * BTH Buddhist: Theravada
		 * BTA Buddhist: Tantrayana
		 * BOT Buddhist: Other
		 * CFR Chinese Folk Religionist
		 * CHR Christian
		 * ABC Christian: American Baptist Church
		 * AMT Christian: African Methodist Episcopal
		 * AME Christian: African Methodist Episcopal Zion
		 * ANG Christian: Anglican
		 * AOG Christian: Assembly of God
		 * BAP Christian: Baptist
		 * CAT Christian: Roman Catholic
		 * CRR Christian: Christian Reformed
		 * CHS Christian: Christian Science
		 * CMA Christian: Christian Missionary Alliance
		 * COC Christian: Church of Christ
		 * COG Christian: Church of God
		 * COI Christian: Church of God in Christ
		 * COM Christian: Community
		 * COL Christian: Congregational
		 * EOT Christian: Eastern Orthodox
		 * EVC Christian: Evangelical Church
		 * EPI Christian: Episcopalian
		 * FWB Christian: Free Will Baptist
		 * FRQ Christian: Friends
		 * GRE Christian: Greek Orthodox
		 * JWN Christian: Jehovah's Witness
		 * LUT Christian: Lutheran
		 * LMS Christian: Lutheran Missouri Synod
		 * MEN Christian: Mennonite
		 * MET Christian: Methodist
		 * MOM Christian: Latter-day Saints
		 * NAZ Christian: Church of the Nazarene
		 * ORT Christian: Orthodox
		 * COT Christian: Other
		 * PRC Christian: Other Protestant
		 * PEN Christian: Pentecostal
		 * COP Christian: Other Pentecostal
		 * PRE Christian: Presbyterian
		 * PRO Christian: Protestant
		 * QUA Christian: Friends
		 * REC Christian: Reformed Church
		 * REO Christian: Reorganized Church of Jesus Christ-LDS
		 * SAA Christian: Salvation Army
		 * SEV Christian: Seventh Day Adventist
		 * SOU Christian: Southern Baptist
		 * UCC Christian: United Church of Christ
		 * UMD Christian: United Methodist
		 * UNI Christian: Unitarian
		 * UNU Christian: Unitarian Universalist
		 * WES Christian: Wesleyan
		 * WMC Christian: Wesleyan Methodist
		 * CNF Confucian
		 * ERL Ethnic Religionist
		 * HIN Hindu
		 * HVA Hindu: Vaishnavites
		 * HSH Hindu: Shaivites
		 * HOT Hindu: Other
		 * JAI Jain
		 * JEW Jewish
		 * JCO Jewish: Conservative
		 * JOR Jewish: Orthodox
		 * JOT Jewish: Other
		 * JRC Jewish: Reconstructionist
		 * JRF Jewish: Reform
		 * JRN Jewish: Renewal
		 * MOS Muslim
		 * MSU Muslim: Sunni
		 * MSH Muslim: Shiite
		 * MOT Muslim: Other
		 * NAM Native American
		 * NRL New Religionist
		 * NOE Nonreligious
		 * OTH Other
		 * SHN Shintoist
		 * SIK Sikh
		 * SPI Spiritist
		 * VAR Unknown
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
		 * Value Description Comment
		 * H Hispanic or Latino
		 * N Not Hispanic or Latino
		 * U Unknown
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



//		print '<pre>';
//		print_r($this->rawSeg);
	}
}