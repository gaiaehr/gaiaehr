<?php

//********************************************************************
// ANSI X12 Utilities
// v0.0.1
//
// Description: This is one of many ANSI X12 Compliance Utilities
// to manage ANSI X12 Documents (4010 & 5010)
//
// Author: GI Technologies, 2011
// Created Date: 23/4/2011
//
// Companies:
// GI Technologies, Inc.
//********************************************************************

class x12valid_837_4010 {
	
	private $temp_buff;
	private $chr_div;
	private $reason;
	
	//-------------------------------------------------------
	// Copy the x12 data to the temporary buffer.
	//-------------------------------------------------------
	function setX12($x12){
		$this->temp_buff = $x12;
		$x12_array = explode("~", $this->temp_buff); // Break the document into a array
		$s = explode("*", $x12_array[0]); 
		$this->chr_div = $s[16]; // Extract the character separator
	}
	
	//-------------------------------------------------------
	// getReason : Get the error why the x12 failed.
	//-------------------------------------------------------
	function getReason(){
		return $this->reason;
	}
	
	// check for a ISA valid document
	private function chkISA($str){
		$v = explode("*", $str);
		if($v[0] == "ISA"){
			if( $v[1] <> "00" ){ $this->reason = "ISA - Authorization Information Qualifier"; return FALSE; } // Authorization Information Qualifier
			if( strlen($v[2]) <= 9 && strlen($v[2]) >= 11 ){ $this->reason = "ISA - Authorization Information Size"; return FALSE; } // Authorization Information
			if( $v[3] <> "00" ){ $this->reason = "ISA - Security Information Qualifier"; return FALSE; } // Security Information Qualifier
			if( strlen($v[4]) <= 9 && strlen($v[4]) >= 11 ){ $this->reason = "ISA - Security Information"; return FALSE; } // Security Information
			if( is_int($v[5]) ){ $this->reason = "ISA - Interchange ID Qualifier"; return FALSE; } // Interchange ID Qualifier
			if( strlen($v[6]) <= 14 && strlen($v[6]) >= 16 ){ $this->reason = "ISA - Interchange Sender ID"; return FALSE; } // Interchange Sender ID
			if( strlen($v[8]) <= 14 && strlen($v[8]) >= 16 ){ $this->reason = "ISA - Interchange Receiver ID"; return FALSE; } // Interchange Receiver ID
			if( strlen($v[9]) <= 6 && strlen($v[9]) >= 7 ){ $this->reason = "ISA - Interchange Date"; return FALSE; } // Interchange Date
			if( strlen($v[10]) <= 3 && strlen($v[10]) >= 5 ){ $this->reason = "ISA - Interchange Time"; return FALSE; } // Interchange Time
			if( $v[11] <> "U" ){ $this->reason = "ISA - Interchange Control Standards Identifier"; return FALSE; } // Interchange Control Standards Identifier
			if( $v[12] <> "00401" ){ $this->reason = "ISA - Interchange Control Version Number"; return FALSE; } // Interchange Control Version Number
			if( strlen($v[13]) <= 8 && strlen($v[13]) >= 10 ){ $this->reason = "ISA - Interchange Control Number"; return FALSE; } // Interchange Control Number
			if( $v[14] <> "0" && $v[14] <> "1" ){ $this->reason = "ISA - Acknowledgement Requested"; return FALSE; } // Acknowledgement Requested
			if( $v[15] <> "T" && $v[15] <> "P" ){ $this->reason = "ISA - Usage Indicator"; return FALSE; } // Usage Indicator
			if( strlen($v[16]) <= 0 && strlen($v[16]) >= 2 ){ $this->reason = "ISA - Component Element Separator"; return FALSE; } // Component Element Separator
		}
		return TRUE;
	}
	
	// check for Group GS
	private function chkGS($str){
		$v = explode("*", $str);
		if($v[0] == "GS"){
			if( $v[1] <> "HC" ){ $this->reason = "GS - Functional Identifier Code"; return FALSE; } // Functional Identifier Code
			if( !$v[2] ){ $this->reason = "GS - Application Sender's Code"; return FALSE; } // Application Sender's Code
			if( strlen($v[3]) <= 1 && strlen($v[3]) >= 16 ){ $this->reason = "GS - Application Receiver's Code"; return FALSE; } // Application Receiver's Code
			if( strlen($v[4]) <= 7 && strlen($v[4]) >= 8 ){ $this->reason = "GS - Date"; return FALSE; } // Date
			if( strlen($v[5]) <= 3 && strlen($v[5]) >= 9 ){ $this->reason = "GS - Time"; return FALSE; } // Time
			if( is_int($v[6]) ){ $this->reason = "GS - Group Control Number"; return FALSE; } // Group Control Number
			if( strlen($v[7]) <= 0 && strlen($v[7]) >= 3 ){ $this->reason = "GS - Responsible Agency Code"; return FALSE; } // Responsible Agency Code
			if( substr($v[8],0,7) <> "004010X" ){ $this->reason = "GS - Version/Release/Industry Identifier Code ".$v[8]; return FALSE; } // Version/Release/Industry Identifier Code
		}
		return TRUE;
	}

	// Check ST segment specifications
	private function chkST($str){
		$v = explode("*", $str);
		if($v[0] == "ST"){
			if( $v[1] <> "837" ){ $this->reason = "ST - Transaction Set Identifier Code"; return FALSE; } // Transaction Set Identifier Code
			if( !strlen($v[2]) ){ $this->reason = "ST - Transaction Set Control Number : " . $v[2]; return FALSE; } // Transaction Set Control Number
		}
		return TRUE;
	}
	
	// FCHP-specific requirements - BHT
	private function chkBHT($str){
		$v = explode("*", $str);
		if($v[0] == "BHT"){
			if( $v[1] <> "0019" ){ $this->reason = "BHT - Hierarchical Structure Code"; return FALSE; } // Hierarchical Structure Code
			if( $v[2] <> "00" ){ $this->reason = "BHT - Transaction Set Purpose Code"; return FALSE; } // Transaction Set Purpose Code
			if( !strlen($v[3]) ){ $this->reason = "BHT - Originator Application Transaction Identifier : " . $v[3]; return FALSE; } // Originator Application Transaction Identifier
			if( strlen($v[4]) <= 7 && strlen($v[4]) >= 9 ){ $this->reason = "BHT - Transaction Set Creation Date"; return FALSE; } // Transaction Set Creation Date
			if( strlen($v[5]) <= 3 && strlen($v[5]) >= 9 ){ $this->reason = "BHT - Transaction Set Creation Time"; return FALSE; } // Transaction Set Creation Time
			if( $v[6] <> "CH" ){ $this->reason = "BHT - Claim or Encounter Identifier : " . $v[6]; return FALSE; }// Claim or Encounter Identifier
		}
		return TRUE;
	}
	
	// Submitter Name
	private function chkNM141($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "41"){
			if( is_int($v[2]) ){ $this->reason = "NM1 41 - Entity Type Qualifier"; return FALSE; } // Entity Type Qualifier
			if( strlen($v[3]) <= 3 ){ $this->reason = "NM1 41 - Submitter Last or Organization Name"; return FALSE; } // Submitter Last or Organization Name
			if( $v[8] <> "46" ){ $this->reason = "NM1 41 - Submitter Last or Organization Name"; return FALSE; }// Identification Code Qualifier
			if( strlen($v[9]) <= 3 ){ $this->reason = "NM1 41 - Submitter TIN"; return FALSE; } // Submitter TIN
		}
		return TRUE;
	}
	
	// Submitter EDI Contact Information
	private function chkPERIC($str){
		$v = explode("*", $str);
		if( $v[0] == "PER" && $v[1] == "IC" ){ 
			if( strlen($v[2]) <= 3 ){ $this->reason = "PER IC - Submitter Contact Name"; return FALSE; } // Submitter Contact Name
			if( strlen($v[3]) < 2 ){ $this->reason = "PER IC - Communication Number Qualifier"; return FALSE; } // Communication Number Qualifier
			if( strlen($v[4]) <= 9 ){ $this->reason = "PER IC - Communication Number"; return FALSE; } // Communication Number - Telephone Number
		}
		return TRUE;
	}
	
	// Receiver Name
	private function chkNM140($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "40"){
			if( $v[2] <> "2" ){ $this->reason = "NM1 40 Receiver Name - Entity Type Qualifier"; return FALSE; } // Entity Type Qualifier
			if( strlen($v[3]) <= 3 ){ $this->reason = "NM1 40 Receiver Name - Receiver Name"; return FALSE; } // Receiver Name
			if( $v[8] == "" ){ $this->reason = "NM1 40 Receiver Name - Identification Code Qualifier"; return FALSE; } // Identification Code Qualifier
			if( $v[9] == "" ){ $this->reason = "NM1 40 Receiver Name - Receiver Primary Identifier"; return FALSE; } // Receiver Primary Identifier
		}
		return TRUE;
	}
	
	// Hierarchical Level
	private function chkHL($str){
		$v = explode("*", $str);
		if($v[0] == "HL" ){
			if( is_int($v[1]) ){ $this->reason = "HL - Hierarchical ID Number"; return FALSE; } // Hierarchical ID Number
			if( is_int($v[3]) ){ $this->reason = "HL - Hierarchical Level Code"; return FALSE; } // Hierarchical Level Code
			if( is_int($v[4]) ){ $this->reason = "HL - Hierarchical Child Code"; return FALSE; } // Hierarchical Child Code
		}
		return TRUE;
	}
	
	// Billing/Pay-to Provider Specialty Information
	// NOT MANDATORY
	private function chkPRV($str){
		$v = explode("*", $str);
		if($v[0] == "PRV" ){
			if( strlen($v[1]) <= 1 ){ $this->reason = "PRV - Provider Code"; return FALSE; } // Provider Code
			if( strlen($v[2]) <= 1 ){ $this->reason = "PRV - Reference Identification Qualifier"; return FALSE; } // Reference Identification Qualifier
			if( strlen($v[3]) <= 1 ){ $this->reason = "PRV - Reference Identification"; return FALSE; } // Reference Identification
		}
		return TRUE;
	}
	
	// Foreign Currency Information
	// NOT MANDATORY
	private function chkCUR($str){
		$v = explode("*", $str);
		if($v[0] == "CUR" ){
			if( strlen($v[1]) <= 1 ){ $this->reason = "CUR - Entity Identifier Code"; return FALSE; } // Entity Identifier Code
			if( strlen($v[2]) <= 1 ){ $this->reason = "CUR - Currency Code"; return FALSE; } // Currency Code
		}
		return TRUE;
	}
	
	// Billing Provider Name
	private function chkNM185($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "85"){
			if( $v[2] == "1" && $v[2] == "2" ){ $this->reason = "NM1 85 Billing Provider Name - Entity Type Qualifier"; return FALSE; } // Entity Type Qualifier
			if( strlen($v[3]) <= 3 ){ $this->reason = "NM1 85 Billing Provider Name - Name Last or Organization Name"; return FALSE; } // Name Last or Organization Name
			if( !$v[8] ){ $this->reason = "NM1 85 Billing Provider Name - Identification Code Qualifier"; return FALSE; } // Identification Code Qualifier
			if( !$v[9] ){ $this->reason = "NM1 85 Billing Provider Name - Identification Code"; return FALSE; } // Identification Code
		}
		return TRUE;
	}
	
	// Address Information 1
	private function chkN3($str){
		$v = explode("*", $str);
		if($v[0] == "N3" ){
			if( !$v[1] ){ $this->reason = "N3 - Address Information 1"; return FALSE; } // Address Information 1
		}
		return TRUE;
	}
	
	// City/State/ZIP Code
	private function chkN4($str){
		$v = explode("*", $str);
		if($v[0] == "N4" ){
			if( !$v[1] ){ $this->reason = "N4 City/State/ZIP Code - City Name"; return FALSE; } // City Name
			if( !$v[2] ){ $this->reason = "N4 City/State/ZIP Code - State or Province Code"; return FALSE; } // State or Province Code
			if( !$v[3] ){ $this->reason = "N4 City/State/ZIP Code - Postal Code"; return FALSE; } // Postal Code
		}	
		return TRUE;
	}
	
	// Provider Contact Information
	// NOT MANDATORY
	private function chkPER($str){
		$v = explode("*", $str);
		if($v[0] == "PER" ){
			if( $v[1] == "" ){ $this->reason = "PER - Contact Function Code"; return FALSE; } // Contact Function Code
			if( !$v[2] ){ $this->reason = "PER - Name"; return FALSE; } // Name
			if( !$v[3] ){ $this->reason = "PER - Communication Number Qualifier"; return FALSE; } // Communication Number Qualifier
			if( !$v[4] ){ $this->reason = "PER - Communication Number"; return FALSE; } // Communication Number
		}		
		return TRUE;
	}
	
	// Pay-to Provider Name
	// NOT MANDATORY
	private function chkNM187($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "87" ){
			if( $v[2] <> "1" && $v[2] <> "2" ){ $this->reason = "NM187 - Entity Type Qualifier"; return FALSE; } // Entity Type Qualifier
			if( !$v[3] ){ $this->reason = "NM187 - Name Last or Organization Name"; return FALSE; } // Name Last or Organization Name
			if( !$v[8] ){ $this->reason = "NM187 - Identification Code Qualifier"; return FALSE; } // Identification Code Qualifier
			if( !$v[9] ){ $this->reason = "NM187 - Identification Code"; return FALSE; } // Identification Code
		}
		return TRUE;
	}
	
	// Pay-to Provider Secondary Identification
	// NOT MANDATORY
	private function chkREF($str){
		$v = explode("*", $str);
		if($v[0] == "REF" ){
			if( !$v[1] ){ $this->reason = "REF - Reference Identification Qualifier"; return FALSE; }
			if( !$v[2] ){ $this->reason = "REF - Reference Identification"; return FALSE; }
		}
		return TRUE;
	}
	
	// Subscriber Information
	private function chkSBR($str){
		$v = explode("*", $str);
		if($v[0] == "SBR" ){
			if( !$v[1] ){ $this->reason = "SBR - Payer Responsibility Sequence Number Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Subscriber Name
	private function chkNM1IL($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "IL"){
			if( !$v[2] ){ $this->reason = "NM1 IL - Entity Type Qualifier"; return FALSE; }
			if( !$v[3] ){ $this->reason = "NM1 IL - Name Last or Organization Name "; return FALSE; }
		}
		return TRUE;
	}
	
	// Subscriber Demographic Information
	private function chkDMG($str){
		$v = explode("*", $str);
		if($v[0] == "DMG"){
			if( !$v[1] ){ $this->reason = "DMG - Date/Time Period Format Qualifier"; return FALSE; }
			if( !$v[2] ){ $this->reason = "DMG - Date/Time Period"; return FALSE; }
			if( !$v[3] ){ $this->reason = "DMG - Gender Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Subscriber Name
	private function chkNM1PR($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "PR"){
			if( $v[2] == "" ){ $this->reason = "NM1 PR - Entity Type Qualifier"; return FALSE; }
			if( strlen($v[3]) <= 2 ){ $this->reason = "NM1 PR - Name Last or Organization Name"; return FALSE; }
			if( strlen($v[8]) <= 1 ){ $this->reason = "NM1 PR - Identification Code Qualifier"; return FALSE; }
			if( strlen($v[9]) <= 2 ){ $this->reason = "NM1 PR - Identification Code "; return FALSE; }
		}
		return TRUE;
	}
	
	// Subscriber Name
	private function chkNM1QC($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "QC"){
			if( !$v[2] ){ $this->reason = "NM1 QC - Entity Type Qualifier"; return FALSE; }
			if( !$v[3] ){ $this->reason = "NM1 QC - Patient Last Name"; return FALSE; }
			if( !$v[4] ){ $this->reason = "NM1 QC - Patient First Name"; return FALSE; }
		}
		return TRUE;
	}
	
	// Claim Information
	private function chkCLM($str){
		$v = explode("*", $str);
		if($v[0] == "CLM"){
			if( $v[1] == "" ){ $this->reason = "CLM - Claim Submitter's Identifier"; return FALSE; }
			if( strlen($v[2]) <= 1 ){ $this->reason = "CLM - Monetary Amount"; return FALSE; }
			if( $v[5] == "" ){ $this->reason = "CLM - Health Care Service Location Information"; return FALSE; }
			$s = explode(":", $v[5]);
			if( $s[0] == "" ){ $this->reason = "CLM - Facility Code Value"; return FALSE; }
			if( $s[2] == "" ){ $this->reason = "CLM - Claim Frequency Type Code"; return FALSE; }
			if( $v[6] == "" ){ $this->reason = "CLM - Yes/No Condition or Response Code"; return FALSE; }
			if( $v[7] == "" ){ $this->reason = "CLM - Provider Accept Assignment Code"; return FALSE; }
			if( $v[8] == "" ){ $this->reason = "CLM - Yes/No Condition or Response Code"; return FALSE; }
			if( $v[9] == "" ){ $this->reason = "CLM - Release of Information Code"; return FALSE; }
		}
		return TRUE;
	}

	// Date/Time Period
	private function chkPWK($str){
		$v = explode("*", $str);
		if($v[0] == "PWK"){
			if( $v[1] == "" ){ $this->reason = "PWK - Report Type Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "PWK - Report Transmission Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Date/Time Period
	private function chkDTP($str){
		$v = explode("*", $str);
		if($v[0] == "DTP"){
			if( $v[1] == "" ){ $this->reason = "DTP - Date/Time Qualifier"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "DTP - Date/Time Period Format Qualifier"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "DTP - Date/Time Period"; return FALSE; }
		}
		return TRUE;
	}
	
	// Date/Time Period
	private function chkCN1($str){
		$v = explode("*", $str);
		if($v[0] == "CN1"){
			if( $v[1] == "" ){ $this->reason = "CN1 - Contract Type Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Ammount
	private function chkAMT($str){
		$v = explode("*", $str);
		if($v[0] == "AMT"){
			if( $v[1] == "" ){ $this->reason = "AMT - Amount Qualifier Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "AMT - Monetary Amount"; return FALSE; }
		}
		return TRUE;
	}
	
	// File Information
	private function chkK3($str){
		$v = explode("*", $str);
		if($v[0] == "K3"){
			if( $v[1] == "" ){ $this->reason = "K3 - Fixed Format Information"; return FALSE; }
		}
		return TRUE;
	}
	
	// Claim Note
	private function chkNTE($str){
		$v = explode("*", $str);
		if($v[0] == "NTE"){
			if( $v[1] == "" ){ $this->reason = "NTE - Note Reference Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "NTE - Description"; return FALSE; }
		}
		return TRUE;
	}
	
	// Ambulance Transport Information
	private function chkCR1($str){
		$v = explode("*", $str);
		if($v[0] == "CR1"){
			if( $v[3] == "" ){ $this->reason = "CR1 - Ambulance Transport Code"; return FALSE; }
			if( $v[4] == "" ){ $this->reason = "CR1 - Ambulance Transport Reason Code"; return FALSE; }
			if( $v[5] == "" ){ $this->reason = "CR1 - Unit or Basis for Measurement Code"; return FALSE; }
			if( $v[6] == "" ){ $this->reason = "CR1 - Quantity"; return FALSE; }
		}
		return TRUE;
	}
	
	// Spinal Manipulation Service Information
	private function chkCR2($str){
		$v = explode("*", $str);
		if($v[0] == "CR2"){
			if( $v[1] == "" ){ $this->reason = "CR2 - Quantity"; return FALSE; }
		}
		return TRUE;
	}
	
	// CRC
	private function chkCRC($str){
		$v = explode("*", $str);
		if($v[0] == "CRC"){
			if( $v[1] == "" ){ $this->reason = "CRC - Code Category"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "CRC - Yes/No Condition or Response Code"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "CRC - Condition Indicator"; return FALSE; }
		}
		return TRUE;
	}
	
	// Health Care Diagnosis Code
	private function chkHI($str){
		$v = explode("*", $str);
		if($v[0] == "HI"){
			if( $v[1] == "" ){ $this->reason = "HI - Health Care Code Information"; return FALSE; }
			$s = explode(":", $v[1]);
			if( $s[0] == "" ){ $this->reason = "HI - Code List Qualifier Code"; return FALSE; }
			if( $s[1] == "" ){ $this->reason = "HI - Industry Code "; return FALSE; }
		}
		return TRUE;
	}
	
	// Claim Pricing/Repricing Information
	private function chkHCP($str){
		$v = explode("*", $str);
		if($v[0] == "HCP"){
			if( $v[1] == "" ){ $this->reason = "HCP - Pricing Methodology"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "HCP - Monetary Amount"; return FALSE; }
		}
		return TRUE;
	}
	
	// Home Health Care Plan Information
	private function chkCR7($str){
		$v = explode("*", $str);
		if($v[0] == "CR7"){
			if( $v[1] == "" ){ $this->reason = "CR7 - Discipline Type Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "CR7 - Number"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "CR7 - Number"; return FALSE; }
		}
		return TRUE;
	}
	
	// Referring Provider Name
	private function chkNM1DN($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "DN"){
			if( $v[2] == "" ){ $this->reason = "NM1 DN - Entity Type Qualifier"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "NM1 DN - Entity Type Qualifier"; return FALSE; }
			if( $v[4] == "" ){ $this->reason = "NM1 DN - Name Last or Organization Name"; return FALSE; }
		}
		return TRUE;
	}
	
	// Rendering Provider Name
	private function chkNM182($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "82"){
			if( $v[2] == "" ){ $this->reason = "NM1 82 - Entity Type Qualifier"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "NM1 82 - Entity Type Qualifier"; return FALSE; }
			if( $v[4] == "" ){ $this->reason = "NM1 82 - Name Last or Organization Name"; return FALSE; }
			if( $v[8] == "" ){ $this->reason = "NM1 82 - Identification Code Qualifier"; return FALSE; }
			if( $v[9] == "" ){ $this->reason = "NM1 82 - Identification Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Rendering Provider Name
	private function chkNM177($str){
		$v = explode("*", $str);
		if($v[0] == "NM1" && $v[1] == "77"){
			if( $v[2] == "" ){ $this->reason = "NM1 77 - Entity Type Qualifier"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "NM1 77 - Entity Type Qualifier"; return FALSE; }
		}
		return TRUE;
	}
	
	// Claim Level Adjustments
	private function chkCAS($str){
		$v = explode("*", $str);
		if($v[0] == "CAS"){
			if( $v[1] == "" ){ $this->reason = "CAS - Claim Adjustment Group Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "CAS - Claim Adjustment Reason Code"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "CAS - Monetary Amount"; return FALSE; }
		}
		return TRUE;
	}

	// Other Insurance Coverage Information
	private function chkOI($str){
		$v = explode("*", $str);
		if($v[0] == "OI"){
			if( $v[3] == "" ){ $this->reason = "OI - Yes/No Condition or Response Code"; return FALSE; }
			if( $v[6] == "" ){ $this->reason = "OI - Release of Information Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Service Line
	private function chkLX($str){
		$v = explode("*", $str);
		if($v[0] == "LX"){
			if( $v[1] == "" ){ $this->reason = "LX - Assigned Number"; return FALSE; }
		}
		return TRUE;
	}
	
	// Professional Service
	private function chkSV1($str){
		$v = explode("*", $str);
		if($v[0] == "SV1"){
			if( $v[1] == "" ){ $this->reason = "SV1 - Composite Medical Procedure Identifier"; return FALSE; }
			$s = explode(":", $v[1]);
			if( $s[0] == "" ){ $this->reason = "SV1 - Product/Service ID Qualifier"; return FALSE; }
			if( $s[1] == "" ){ $this->reason = "SV1 - Product/Service ID"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "SV1 - Monetary Amount"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "SV1 - Unit or Basis for Measurement Code"; return FALSE; }
			if( $v[4] == "" ){ $this->reason = "SV1 - Quantity"; return FALSE; }
			if( $v[7] == "" ){ $this->reason = "SV1 - Diagnosis Code Pointer"; return FALSE; }
		}
		return TRUE;
	}
	
	// Durable Medical Equipment Service
	private function chkSV7($str){
		$v = explode("*", $str);
		if($v[0] == "SV7"){
			if( $v[1] == "" ){ $this->reason = "SV7 - Composite Medical Procedure Identifier"; return FALSE; }
			$s = explode(":", $v[1]);
			if( $s[0] == "" ){ $this->reason = "SV7 - Product/Service ID Qualifier"; return FALSE; }
			if( $s[1] == "" ){ $this->reason = "SV7 - Product/Service ID"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "SV7 - Monetary Amount"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "SV7 - Unit or Basis for Measurement Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Home Oxygen Therapy Information
	private function chkCR5($str){
		$v = explode("*", $str);
		if($v[0] == "CR5"){
			if( $v[1] == "" ){ $this->reason = "CR5 - Certification Type Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "CR5 - Quantity"; return FALSE; }
			if( $v[12] == "" ){ $this->reason = "CR5 - Oxygen Test Condition Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Anesthesia Modifying Units
	private function chkQTY($str){
		$v = explode("*", $str);
		if($v[0] == "QTY"){
			if( $v[1] == "" ){ $this->reason = "QTY - Quantity Qualifier"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "QTY - Quantity"; return FALSE; }
		}
		return TRUE;
	}
	
	// Test Result
	private function chkMEA($str){
		$v = explode("*", $str);
		if($v[0] == "MEA"){
			if( $v[1] == "" ){ $this->reason = "MEA - Measurement Reference ID Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "MEA - Measurement Qualifier"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "MEA - Measurement Value"; return FALSE; }
		}
		return TRUE;
	}
	
	// Purchased Service Information
	private function chkPS1($str){
		$v = explode("*", $str);
		if($v[0] == "PS1"){
			if( $v[1] == "" ){ $this->reason = "PS1 - Reference Identification"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "PS1 - Monetary Amount"; return FALSE; }
		}
		return TRUE;
	}
	
	// Drug Identification
	private function chkLIN($str){
		$v = explode("*", $str);
		if($v[0] == "LIN"){
			if( $v[2] == "" ){ $this->reason = "LIN - Product / Service ID Qualifier"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "LIN - Product / Service ID"; return FALSE; }
		}
		return TRUE;
	}
	
	// Drug Identification
	private function chkCTP($str){
		$v = explode("*", $str);
		if($v[0] == "CTP"){
			if( $v[3] == "" ){ $this->reason = "CTP - Unit Price"; return FALSE; }
			if( $v[4] == "" ){ $this->reason = "CTP - Quantity"; return FALSE; }
			if( $v[5] == "" ){ $this->reason = "CTP - Composite Unit of Measure"; return FALSE; }
			$s = explode(":", $v[5]);
			if( $s[0] == "" ){ $this->reason = "CTP - Unit or Basis for Measurement Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Drug Identification
	private function chkSVD($str){
		$v = explode("*", $str);
		if($v[0] == "SVD"){
			if( $v[1] == "" ){ $this->reason = "SVD - Identification Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "SVD - Monetary Amount"; return FALSE; }
			if( $v[3] == "" ){ $this->reason = "SVD - Composite Medical Procedure Identifier"; return FALSE; }
			$s = explode(":", $v[3]);
			if( $s[0] == "" ){ $this->reason = "SVD - Product/Service ID Qualifier"; return FALSE; }
			if( $s[1] == "" ){ $this->reason = "SVD - Product/Service ID"; return FALSE; }
			if( $v[4] == "" ){ $this->reason = "SVD - Quantity"; return FALSE; }
		}
		return TRUE;
	}
	
	// Form Identification Code
	private function chkLQ($str){
		$v = explode("*", $str);
		if($v[0] == "LQ"){
			if( $v[1] == "" ){ $this->reason = "LQ - Code List Qualifier Code"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "LQ - Industry Code"; return FALSE; }
		}
		return TRUE;
	}
	
	// Supporting Documentation
	private function chkFRM($str){
		$v = explode("*", $str);
		if($v[0] == "FRM"){
			if( $v[1] == "" ){ $this->reason = "FRM - Assigned Identification"; return FALSE; }
		}
		return TRUE;
	}
	
	// Transaction Set Trailer
	private function chkSE($str){
		$v = explode("*", $str);
		if($v[0] == "SE"){
			if( $v[1] == "" ){ $this->reason = "SE - Number of Included Segments"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "SE - Transaction Set Control Number"; return FALSE; }
		}
		return TRUE;
	}
	
	// Functional Group Trailer
	private function chkGE($str){
		$v = explode("*", $str);
		if($v[0] == "GE"){
			if( $v[1] == "" ){ $this->reason = "GE - Number of Transaction Sets Included"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "GE - Group Control Number"; return FALSE; }
		}
		return TRUE;
	}
	
	// Interchange Control Trailer
	private function chkIEA($str){
		$v = explode("*", $str);
		if($v[0] == "IEA"){
			if( $v[1] == "" ){ $this->reason = "IEA - Number of Included Functional Groups"; return FALSE; }
			if( $v[2] == "" ){ $this->reason = "IEA - Interchange Control Number"; return FALSE; }
		}
		return TRUE;
	}

		
	//-------------------------------------------------------
	// Validate a ANSI x12 version 4010A
	//-------------------------------------------------------
	function valid4010A(){
		
		$x12_array = explode("~", $this->temp_buff); // Break the document into a array

		foreach($x12_array as $value){
			if(!$this->chkISA($value)){ return FALSE; }
			if(!$this->chkGS($value)){ return FALSE; }
			if(!$this->chkST($value)){ return FALSE; }
			if(!$this->chkBHT($value)){ return FALSE; }
			if(!$this->chkNM141($value)){ return FALSE; }
			if(!$this->chkPERIC($value)){ return FALSE; }
			if(!$this->chkNM140($value)){ return FALSE; }
			if(!$this->chkHL($value)){ return FALSE; }
			if(!$this->chkPRV($value)){ return FALSE; }
			if(!$this->chkCUR($value)){ return FALSE; }
			if(!$this->chkNM185($value)){ return FALSE; }
			if(!$this->chkN3($value)){ return FALSE; }
			if(!$this->chkN4($value)){ return FALSE; }
			if(!$this->chkREF($value)){ return FALSE; }
			if(!$this->chkPER($value)){ return FALSE; }
			if(!$this->chkNM187($value)){ return FALSE; }
			if(!$this->chkSBR($value)){ return FALSE; }
			if(!$this->chkNM1IL($value)){ return FALSE; }
			if(!$this->chkDMG($value)){ return FALSE; }
			if(!$this->chkNM1PR($value)){ return FALSE; }
			if(!$this->chkNM1QC($value)){ return FALSE; }
			if(!$this->chkCLM($value)){ return FALSE; }
			if(!$this->chkDTP($value)){ return FALSE; }
			if(!$this->chkPWK($value)){ return FALSE; }
			if(!$this->chkCN1($value)){ return FALSE; }
			if(!$this->chkAMT($value)){ return FALSE; }
			if(!$this->chkK3($value)){ return FALSE; }
			if(!$this->chkNTE($value)){ return FALSE; }
			if(!$this->chkCR1($value)){ return FALSE; }
			if(!$this->chkCR2($value)){ return FALSE; }
			if(!$this->chkCRC($value)){ return FALSE; }
			if(!$this->chkHI($value)){ return FALSE; }
			if(!$this->chkHCP($value)){ return FALSE; }
			if(!$this->chkCR7($value)){ return FALSE; }
			if(!$this->chkNM1DN($value)){ return FALSE; }
			if(!$this->chkNM182($value)){ return FALSE; }
			if(!$this->chkNM177($value)){ return FALSE; }
			if(!$this->chkCAS($value)){ return FALSE; }
			if(!$this->chkOI($value)){ return FALSE; }
			if(!$this->chkLX($value)){ return FALSE; }
			if(!$this->chkSV1($value)){ return FALSE; }
			if(!$this->chkSV7($value)){ return FALSE; }
			if(!$this->chkCR5($value)){ return FALSE; }
			if(!$this->chkQTY($value)){ return FALSE; }
			if(!$this->chkMEA($value)){ return FALSE; }
			if(!$this->chkPS1($value)){ return FALSE; }
			if(!$this->chkLIN($value)){ return FALSE; }
			if(!$this->chkCTP($value)){ return FALSE; }
			if(!$this->chkSVD($value)){ return FALSE; }
			if(!$this->chkLQ($value)){ return FALSE; }
			if(!$this->chkFRM($value)){ return FALSE; }
			if(!$this->chkSE($value)){ return FALSE; }
			if(!$this->chkGE($value)){ return FALSE; }
			if(!$this->chkIEA($value)){ return FALSE; }
		}		
		
		// The document is perfect!
		return TRUE;
	}

	function tmpShow(){
		$x12_array = explode("~", $this->temp_buff); // Break the document into a array
		
		echo "<pre>";
		print_r($x12_array);
		echo "</pre>";
	}

}
	
?>