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

class x12parse_4010 {
	
	private $temp_buff;
	private $chr_div;
	private $posi;
	private $totalClaims;
	private $XMLdicc;

	//-------------------------------------------------------
	// Load Diccionary
	//-------------------------------------------------------
	function setDicc($xmlDicc){
		$this->XMLdicc = $xmlDicc;
	}	
	
	//-------------------------------------------------------
	// getCodeDicc
	// look for the passed code on the diccionary
	// and return the meaning of the code.
	//-------------------------------------------------------
	private function getCodeDicc($code_array, $code){
		foreach($code_array as $value){
			if($value->tagName == strtolower($code)){ return $value->tagData; }
		}
		return FALSE;
	}
	
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
	// docType
	// return: The current type of the document 
	// 837, 270, ect.
	// Transaction Set Header
	//-------------------------------------------------------
	function docType(){
		preg_match("/ST.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("ST", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$ret = explode("*", $ret);
		return $ret[1];
	}
	
	//-------------------------------------------------------
	// docControlNumber
	// return: The current type of the document 
	// 837, 270, ect.
	// Transaction Set Header
	//-------------------------------------------------------
	function docControlNumber(){
		preg_match("/ST.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("ST", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$ret = explode("*", $ret);
		return $ret[2];
	}
	
	//-------------------------------------------------------
	// docISA
	// Interchange Control Header
	//-------------------------------------------------------
	function docISA(){
		preg_match("/ISA.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("ISA", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		
		// Parsing Results...
		$info['ISA_AUTHINFO'] 	= $arr[1]; 		// Authorization Information Qualifier
		$info['ISA_AUTHNUM']	= $arr[2];		// Authorization Number
		$info['ISA_SECINFOQ']	= $arr[3]; 		// Security Information Qualifier
		$info['ISA_SECINFO'] 	= $arr[4]; 		// Security Information
		$info['ISA_SENDERIDQ'] 	= $arr[5];	 	// Interchange Sender Id Qualifier
		$info['ISA_SENDERID']	= $arr[6]; 		// Interchange Send Id
		$info['ISA_RECVIDQ']	= $arr[7]; 		// Interchange Receiver Id Qualifier
		$info['ISA_RECVID'] 	= $arr[8]; 		// Interchange Receiver Id
		$info['ISA_DATE'] 		= $arr[9]; 		// Interchange Date
		$info['ISA_TIME']		= $arr[10]; 	// Interchange Time
		$info['ISA_REPSEP']		= $arr[11]; 	// Repetition Separator
		$info['ISA_VERSION'] 	= $arr[12]; 	// Interchange Control Version Number
		$info['ISA_ISACONTROL'] = $arr[13]; 	// Interchange Control Number
		$info['ISA_ACKREQ'] 	= $arr[14]; 	// Acknowledgment Requested
		$info['ISA_ISAUSAGE'] 	= $arr[15]; 	// Interchange Usage Indicator
		$info['ISA_ELSEP'] 		= $arr[16]; 	// Component Element Separator

		return $info;
	}
	
	//-------------------------------------------------------
	// doc_InterchangeInfo
	// Functional Group Header
	//-------------------------------------------------------
	function docGS(){
		preg_match("/GS.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("GS", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		
		// Parsing Results...
		$info['GS_TRANSTYPE'] = $arr[1]; 	// Transfer Type
		$info['GS_SENDER'] = $arr[2]; 		// Seder's Qualifier ID
		$info['GS_RECEIVER'] = $arr[3]; 	// Receiver's Qualifier ID
		$info['GS_DATE'] = $arr[4]; 		// Date fo the document
		$info['GS_TIME'] = $arr[5]; 		// Time
		$info['GS_CONTROL'] = $arr[6]; 		// Control number
		$info['GS_VERSION'] = $arr[8]; 		// Standard Version

		return $info;
	}
	
	//-------------------------------------------------------
	// Beginning of Hierarchical Transaction
	//-------------------------------------------------------
	function docBHT(){
		preg_match("/BHT.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("BHT", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		
		// Parsing Results...
		$info['BHT_HSCODE'] = $arr[1]; 		//Hierarchical Structure Code
		$info['BHT_TSPCODE'] = $arr[2]; 	// Transaction Set Purpose Code
		$info['BHT_REFID'] = $arr[3]; 		// Reference Identification
		$info['BHT_DATE'] = $arr[4]; 		// Transaction Set Creation Date
		$info['BHT_TIME'] = $arr[5]; 		// Transaction Set Creation Time
		$info['BHT_TYPE_CODE'] = $arr[5]; 	// Transaction Type Code
		
		preg_match("/REF\*87.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		
		// Parsing Results...
		$info['REF87_REFIDQ'] = $arr[1]; 	// Reference Identification Qualifier
		
		// Reference Identification
		if ( trim($arr[2]) == '004010X098A1' ){
			$info['REF87_ID'] = 'Production';
		} elseif ( trim($arr[2]) == '004010X098DA1' ){
			$info['REF87_ID'] = 'Test';
		}
		
		return $info;
	}
	
	//-------------------------------------------------------
	// Submitter Information
	//-------------------------------------------------------
	function getSubmitter(){
		preg_match("/NM1\*41.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("NM1", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);

		$info['NM141_ENTIDCODE'] 	= $arr[1]; 	// Entity Identifier Code
		$info['NM141_ENTTYPE'] 		= $arr[2]; 	// Entity Type Qualifier
		$info['NM141_SUBNAME'] 		= $arr[3]; 	// Submitter Name
		$info['NM141_SUBFIRST'] 	= $arr[4]; 	// First Name
		$info['NM141_SUBLASTNAME'] 	= $arr[5]; 	// Middle Name
		$info['NM141_RESERVED1'] 	= $arr[6]; 	// 
		$info['NM141_RESERVED2'] 	= $arr[7]; 	// 
		$info['NM141_IDCODEQ'] 		= $arr[8]; 	// Identification Code Qualifier
		$info['NM141_IDCODE'] 		= $arr[9]; 	// Identification Code
		
		return $info;
	}

	//-------------------------------------------------------
	// Loop 1000B - Receiver - Payer
	//-------------------------------------------------------
	function getReceiver(){
		preg_match("/NM1\*40.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("NM1", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);

		$info['NM140_ENTIDCODE'] 	= $arr[1]; 	// Entity Identifier Code
		$info['NM140_ENTTYPE'] 		= $arr[2]; 	// Entity Type Qualifier
		$info['NM140_SUBNAME'] 		= $arr[3]; 	// Submitter Name
		$info['NM140_SUBFIRST'] 	= $arr[4]; 	// First Name
		$info['NM140_SUBLASTNAME'] 	= $arr[5]; 	// Middle Name
		$info['NM140_RESERVED1'] 	= $arr[6]; 	// 
		$info['NM140_RESERVED2'] 	= $arr[7]; 	// 
		$info['NM140_IDCODEQ'] 		= $arr[8]; 	// Identification Code Qualifier
		$info['NM140_IDCODE'] 		= $arr[9]; 	// Identification Code
	
		return $info;
	}

	//-------------------------------------------------------
	// Loop 2010AA - This loop is used to report information 
	// specific to the billing provider
	//-------------------------------------------------------
	function getBillingProvider(){
		// Billing/Pay-to Provider Specialty Information
		preg_match("/PRV.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("PRV", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		
		$info['PRO_PRVCODE'] 	= $arr[1]; 	// Provider Code
		$info['PRO_REFIDQ'] 	= $arr[2]; 	// Reference Identification Qualifier
		$info['PRO_REFID']	 	= $arr[3]; 	// Reference Identification
		
		// Contact Information
		preg_match("/PER\*IC.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("PER", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		
		$info['PER_ENTIDCODE'] 	= $arr[1]; 	// Entity Identifier Code
		$info['PER_NAME'] 		= $arr[2]; 	// Name
		$info['PER_COMMNUMQ'] 	= $this->getCodeDicc($this->XMLdicc->document->per03[0]->tagChildren, $arr[3]);	// Communication Number Qualifier
		$info['PER_PHONE'] 		= $arr[4]; 	// Communication Number
		
		// Used to identify the billing provider for this hierarchical level
		preg_match("/NM1\*85.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("NM1", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);

		$info['NM185_ENTID'] 	= $arr[1]; 	// Entity Identification Code
		$info['NM185_ENTTYPE']	= $this->getCodeDicc($this->XMLdicc->document->nm18502[0]->tagChildren, "n".$arr[2]); 	// Entity Type Qualifier
		$info['NM185_BILLPROV']	= $arr[3]; 	// Billing Provider Name
		$info['NM185_NAME'] 	= $arr[4]; 	// Name First
		$info['NM185_MIDNAME']	= $arr[5]; 	// Name Middle
		$info['NM185_RESERVED1']= $arr[6]; 	// Not Used
		$info['NM185_NAMESUF']	= $arr[7]; 	// Name Suffix
		$info['NM185_IDCODEQ']	= $arr[8]; 	// Identification Code Qualifier
		$info['NM185_IDCODE']	= $arr[9]; 	// Identification Code
		
		// Used to address information for the billing provider
		preg_match("/N3.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("N3", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);

		$info['N3_ADDRESS1'] 		= $arr[1]; 	// Address Information
		$info['N3_ADDRESS2']		= $arr[2]; 	// Address Information
		
		// Used to report city, state, and zip code information for the billing provider
		preg_match("/N4.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("N4", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);

		$info['N4_CITY'] 		= $arr[1]; 	// Provider City
		$info['N4_STATE']		= $arr[2]; 	// Provider State
		$info['N4_ZIPCODE']		= $arr[3]; 	// Provider Zip Code
		
		// Employer's Indentification Number
		preg_match("/REF\*EI.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		$info['REFEI_EMPLOYERID']		= $arr[2];		
		
		// Provider Commercial Number
		preg_match("/REF\*G2.*?~/", $this->temp_buff, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);
		$info['REFG2_PROVIDERID']		= $arr[2];
		
		return $info;
	}

	//-------------------------------------------------------
	// Check if the string has a HL
	//-------------------------------------------------------
	private function isHL($str){
		if(substr($str, 0, 2) == "HL"){ return TRUE; } else { return FALSE; }
	}

	//-------------------------------------------------------
	// Convert from string to array 
	// SBR -> Subscriber Information
	//-------------------------------------------------------
	private function extSBR($str){
		$m = preg_match("/SBR.*$/", $str, $matches);
		$ret = str_replace("SBR", NULL, $matches[0]);
		$arr = explode("*", $ret);

		$r = strtoupper($this->getCodeDicc($this->XMLdicc->document->sbr01[0]->tagChildren, $arr[1]));
		$sbr['SBR_PAUERRESPONSABILITY'] = $r; // 	Payer Responsibility Sequence Number Code
		$sbr['SBR_INDVRELAT']	= $arr[2]; // Individual Relationship Code
		$sbr['SBR_REFID'] 		= $arr[3]; // Reference Identification
		$sbr['SBR_NAME'] 		= $arr[4]; // Name
		$sbr['SBR_INSUTYPE'] 	= $arr[5]; // Insurance Type Code
		$sbr['SBR_NOTUSED1'] 	= $arr[6]; // Not Used
		$sbr['SBR_NOTUSED2'] 	= $arr[7]; // Not Used
		$sbr['SBR_NOTUSED3'] 	= $arr[8]; // Not Used
		$sbr['SBR_CLAIMFILL'] 	= $arr[9]; // Claim Filing Indicator
		$info['SBR_'.$r] = $sbr;
		
		
		if (!$m){ return FALSE; } else { return $info; }
	}

	//-------------------------------------------------------
	// Insured or Subcriber
	//-------------------------------------------------------
	private function extNM1IL($str){
		// Insured or Subcriber
		$m = preg_match("/NM1\*IL.*$/", $str, $matches);
		$ret = str_replace("NM1", NULL, $matches[0]);
		$arr = explode("*", $ret);
			
		$info['NM1IL_CLAIMFILL'] 	= $arr[1]; // Entity Identification Code
		$info['NM1IL_ENTTYPEQ']		= $arr[2]; // Entity Type Qualifier
		$info['NM1IL_LASTNAME'] 	= $arr[3]; // Subscriber Last Name
		$info['NM1IL_FIRSTNAME'] 	= $arr[4]; // Subscriber First Name
		$info['NM1IL_MIDDLENAME'] 	= $arr[5]; // Subscriber Middle Name
		$info['NM1IL_NOTUSED1']		= $arr[6]; // Not Used
		$info['NM1IL_SUFNAME']		= $arr[7]; // Name Suffix
		$info['NM1IL_IDCODEQ']		= $arr[8]; // Identification Code Qualifier
		$info['NM1IL_IDCODE']	 	= $arr[9]; // Identification Code
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Primary Insured Address
	//-------------------------------------------------------
	private function extN3($str){
		$m = preg_match("/N3.*$/", $str, $matches);
		$ret = str_replace("N3", NULL, $matches[0]);
		$arr = explode("*", $ret);
		
		 $info['N3_ADDR1'] 	= $arr[1]; // Address Information Line 1
		 $info['N3_ADDR2'] 	= $arr[2]; // Address Information Line 2
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Primary Insured Address ...continued...
	//-------------------------------------------------------
	private function extN4($str){
		$m = preg_match("/N4.*$/", $str, $matches);
		$ret = str_replace("N4", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['N4_City'] 		= $arr[1]; // City
		$info['N4_State'] 		= $arr[2]; // State
		$info['N4_ZipCode'] 	= $arr[3]; // Zip Code
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Demographics
	//-------------------------------------------------------
	private function extDMG($str){
		$m = preg_match("/DMG.*$/", $str, $matches);
		$ret = str_replace("DMG", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['DMG_DATEFORMAT']	= $arr[1]; // Date Format Qualifier
		$info['DMG_PERIOD'] 	= $arr[2]; // Date Period
		$info['DMG_GENDER'] 	= $arr[3]; // Gender Code
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// This loop is used to report information specific 
	// to the payer
	//-------------------------------------------------------
	private function extNM1PR($str){
		$m = preg_match("/NM1\*PR.*$/", $str, $matches);
		$ret = str_replace("NM1", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['NM1PR_ENTIDCODE']= $arr[1]; // Entity Identifier Code
		$info['NM1PR_ENTTYPEQ']	= $arr[2]; // Entity Type Qualifier
		$info['NM1PR_PAYERNAME']= $arr[3]; // Payer Name
		$info['NM1PR_NOTUSED1']	= $arr[4]; // Not Used
		$info['NM1PR_NOTUSED2']	= $arr[5]; // Not Used
		$info['NM1PR_NOTUSED3']	= $arr[6]; // Not Used
		$info['NM1PR_NOTUSED4']	= $arr[7]; // Not Used
		$info['NM1PR_IDCODEQ']	= $arr[8]; // Identification Code Qualifier
		$info['NM1PR_IDCODE']	= $arr[9]; // Identification Code
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Patient Information
	//-------------------------------------------------------
	private function extPAT($str){
		$m = preg_match("/PAT.*$/", $str, $matches);
		$ret = str_replace("PAT", NULL, $matches[0]);
		$arr = explode("*", $ret);
		
		$r = strtoupper($this->getCodeDicc($this->XMLdicc->document->pat01[0]->tagChildren, "n" . $arr[1]));
		$info['PAT_RELATIONSHIP_CODE']= $r; // Individual Relationship Code
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Patient Information 
	//-------------------------------------------------------
	private function extNM1QC($str){
		$m = preg_match("/NM1\*QC.*$/", $str, $matches);
		$ret = str_replace("NM1", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['NM1QC_ENTIDCODE']	= $arr[1]; // Entity Identifier Code
		$info['NM1QC_ENTTYPEQ']		= $arr[2]; // Entity Type Qualifier
		$info['NM1QC_LASTNAME']		= $arr[3]; // Patient Last Name
		$info['NM1QC_FIRSTNAME']	= $arr[4]; // Patient First Name
		$info['NM1QC_MIDNAME']		= $arr[5]; // Middle Name
		$info['NM1QC_NAMEPREFIX']	= $arr[6]; // Name Prefix
		$info['NM1QC_NAMESUFIX']	= $arr[7]; // Name Suffix
		$info['NM1QC_IDCODEQ']		= $arr[8]; // Identification Code Qualifier
		$info['NM1QC_IDCODE']		= $arr[9]; // Identification Code
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Patient Social Security Number 
	//-------------------------------------------------------
	private function extREFSY($str){
		$m = preg_match("/REF\*SY.*$/", $str, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['REFSY_SOCSEC']		= $arr[2]; // Social Security Number
				
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Health Claim 
	//-------------------------------------------------------
	private function extCLM($str){
		$m = preg_match("/CLM.*$/", $str, $matches);
		$ret = str_replace("CLM", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['CLM_PATID']		= $arr[1]; // Claim Submitter's Identifier
		$info['CLM_AMOUNT']		= $arr[2]; // Amount Billed
		$info['CLM_NOTUSED1']	= $arr[3]; // Not Used
		$info['CLM_NOTUSED2']	= $arr[4]; // Not Used
		$info['CLM_PLACE']		= $arr[5]; // Place of Service
		$info['CLM_NOTUSED3']	= $arr[6]; // Not Used
		$info['CLM_FREQTYPE']	= $arr[7]; // Claim Frequency Type Code
		$info['CLM_CONDRES']	= $arr[8]; // Yes/No Condition or Response Code
		$info['CLM_MEDASS']		= $arr[9]; // Medicare Assignment Code
		$info['CLM_BENEFITS']	= $arr[10]; // Assignment of Benefits Indicator
		$info['CLM_RELINFOCODE']= $arr[11]; // Release of Information Code
		$info['CLM_PATSIG']		= $arr[12]; // Patient Signature Source Code
		$info['CLM_ACCEMP1']	= $arr[13]; // Accident/Employment Related Cause
		$info['CLM_ACCEMP2']	= $arr[14]; // Accident/Employment Related Cause
		$info['CLM_ACCEMP3']	= $arr[15]; // Accident/Employment Related Cause
		$info['CLM_AUTOACC']	= $arr[16]; // Auto Accident State or Province Code
			
		if (!$m){ return FALSE; } else { return $info; }
	}

	//-------------------------------------------------------
	// Patient Social Security Number 
	//-------------------------------------------------------
	private function extAMTF5($str){
		$m = preg_match("/AMT\*F5.*$/", $str, $matches);
		$ret = str_replace("AMT", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['AMT_PAID']		= $arr[2]; // Patient Amount Paid
				
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Medical Record Identification 
	//-------------------------------------------------------
	private function extREFEA($str){
		$m = preg_match("/REF\*EA.*$/", $str, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['REFEA_RECID']		= $arr[2]; // Medical Record Identification Number or Record ID
				
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Medical Record Identification 
	//-------------------------------------------------------
	private function extHI($str){
		$m = preg_match("/HI.*$/", $str, $matches);
		$ret = str_replace("HI", NULL, $matches[0]);
		$ret = str_replace("~", NULL, $ret);
		$arr = explode("*", $ret);			

		$info['HI_PDIAG']		= $arr[1]; // Principal Diagnosis
				
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Rendering Provider 
	//-------------------------------------------------------
	private function extNM182($str){
		$m = preg_match("/MN1\*82.*$/", $str, $matches);
		$ret = str_replace("MN1", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['NM185_ENTIDCODE']	= $arr[1]; // Entity Identification Code
		$info['NM185_ENTTYPEQ']		= $arr[2]; // Entity Type Qualifier
		$info['NM185_LASTNAME']		= $arr[3]; // Last Name
		$info['NM185_FIRSTNAME']	= $arr[4]; // First Name
		$info['NM185_MIDNAME']		= $arr[5]; // Name Middle
		$info['NM185_NAMEPRE']		= $arr[6]; // Name Prefix
		$info['NM185_NAMESUF']		= $arr[7]; // NName Suffix
		$info['NM185_IDCODEQ']		= $arr[8]; // Identification Code Qualifier
		$info['NM185_IDCODE']		= $arr[9]; // Identification Code
				
		if (!$m){ return FALSE; } else { return $info; }
	}

	//-------------------------------------------------------
	// Provider Information 
	//-------------------------------------------------------
	private function extPRV($str){
		$m = preg_match("/PRV.*$/", $str, $matches);
		$ret = str_replace("PRV", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['PRV_PERF']	= $arr[1]; // Performing
		$info['PRV_REFIDQ']	= $arr[2]; // Reference Identification Qualifier
		$info['PRV_REFID']	= $arr[3]; // Reference Identification
				
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Provider Commercial Number 
	//-------------------------------------------------------
	private function extREFG2($str){
		$m = preg_match("/REF\*G2.*$/", $str, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$info['REFG2_COMNUM']		= $arr[1]; // Provider Commercial Number
				
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Assigned Number 
	// Service Provided or Product Provided
	// RELATED: SV1, DTP, REF6R
	//-------------------------------------------------------
	private function extLX($str){
		$m = preg_match("/LX.*$/", $str, $matches);
		$ret = str_replace("LX", NULL, $matches[0]);
		$arr = explode("*", $ret);			
		
		$info['LX_NUM_'.$arr[1]] = $arr[1]; // Assigned Number
		
		$info = $c['CLAIM_'.$arr[1]]; 

		if (!$m){ return FALSE; } else { $this->posi = $arr[1]; return $info; }
	}
	
	//-------------------------------------------------------
	// Profetional Service
	//-------------------------------------------------------
	private function extSV1($str){
		$m = preg_match("/SV1.*$/", $str, $matches);
		$ret = str_replace("SV1", NULL, $matches[0]);
		$arr = explode("*", $ret);			
		
		$c['SV1_NUM']			= $arr[1]; // Composite Medical Procedure Identifier
		$c['SV1_AMOUNT']		= $arr[2]; // Monetary Amount
		$c['SV1_UNIT']			= $arr[3]; // Unit or Basis for Measurement Code
		$c['SV1_QTY']			= $arr[4]; // Quantity
		$c['SV1_FACCODE']		= $arr[5]; // Facility Code Value
		$c['SV1_SERVTYPE']		= $arr[6]; // Service Type Code
		$c['SV1_COMPDIAGCODE']	= $arr[7]; // Composite Diagnosis Code Pointer
		
		$info['CLAIM_'.$this->posi] = $c; 
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Profetional Service
	//-------------------------------------------------------
	private function extDTP($str){
		$m = preg_match("/DTP.*$/", $str, $matches);
		$ret = str_replace("DTP", NULL, $matches[0]);
		$arr = explode("*", $ret);			

		$c['DTP_Q']		= $arr[1]; // Date/Time Qualifier
		$c['DTP_FORMAT']= $arr[2]; // Date/Time Period Format Qualifier
		$c['DTP_PERIOD']= $arr[3]; // Date/Time Period
		
		$info['CLAIM_'.$this->posi] = $c;
		
		if (!$m){ return FALSE; } else { return $info; }
	}

	//-------------------------------------------------------
	// Provider Control Number
	//-------------------------------------------------------
	private function extREF6R($str){
		$m = preg_match("/REF\*6R.*$/", $str, $matches);
		$ret = str_replace("REF", NULL, $matches[0]);
		$arr = explode("*", $ret);			
		
		$c['REF_CONNUM']			= $arr[2]; // Provider Control Number
		
		$info['CLAIM_'.$this->posi] = $c;
		
		if (!$m){ return FALSE; } else { return $info; }
	}
	
	//-------------------------------------------------------
	// Unify two arrays on the same key
	//-------------------------------------------------------
	private function extUnify($main_arr, $add_arr){
		foreach ($main_arr as $key => $value){ $t[$key] = $value; }
		foreach ($add_arr as $key => $value){ $t[$key] = $value; }
		return $t;
	}
	
	//-------------------------------------------------------
	// Compute the total claims in a x12
	//-------------------------------------------------------
	function getTotalClaims(){
		preg_match_all('/CLM.*?~/', $this->temp_buff, $matches, PREG_PATTERN_ORDER);
		$matches = $matches[0];
		return count($matches); // Minus 1: Removes the provider
	}

	//-------------------------------------------------------
	// This procedure will get all the loops of: 
	// 1. Medic Plan
	// 2. Patient Demographics
	// 3. Claims
	//-------------------------------------------------------
	function getClaims(){
		
		// Get all the HL lines
		$temp_hl = explode("~", $this->temp_buff); // Convert the x12 to an array
		$start = 0;
		foreach ($temp_hl as &$value) { // Delete those unnecesary lines, we want to start at the claim records
			if ( substr($value, 0, 4) == "HL*2" ){ break; } else { unset($temp_hl[$start]);	$start++; }
		}

		// Extract all the claims
		$rec = -1;
		
		foreach ($temp_hl as $key => $value){
			if ($this->isHL($temp_hl[$key])){$rec++;} // Count the records
			foreach ($this->extSBR($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extNM1IL($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extN3($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extN4($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extDMG($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extNM1PR($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extPAT($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extNM1QC($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extREFSY($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extCLM($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extAMTF5($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extREFEA($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extHI($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extNM182($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extPRV($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extREFG2($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $value; }
			foreach ($this->extLX($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $this->extUnify($info[$rec][$key], $value); }
			foreach ($this->extSV1($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $this->extUnify($info[$rec][$key], $value); }
			foreach ($this->extDTP($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $this->extUnify($info[$rec][$key], $value); }
			foreach ($this->extREF6R($temp_hl[$key]) as $key => $value){ $info[$rec][$key] = $this->extUnify($info[$rec][$key], $value); }
		}
		return $info;
		
	}
}

?>