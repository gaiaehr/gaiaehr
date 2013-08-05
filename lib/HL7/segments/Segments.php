<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 5:00 PM
 * To change this template use File | Settings | File Templates.
 */
ini_set('memory_limit', '512M');
class Segments {


	public $rawSeg;
	public $seg;

	function newUID($length = 15){
		$n = '';
		for($i=0; $i < $length; $i++){
			$n = $n . rand(0,9);
		}
		return 'GAIA-'. $n;
	}

	/**
	 * Build the segment message string from the $this->rawSeg array
	 * @return string
	 */
	function build(){
		$seg = '';

		// this buffer is to hold empty values
		// this prevent the message to print unnecessary
		// positions or components
		$buffer = '';

		foreach($this->rawSeg AS $i => $pos){
			if(is_string($pos)){
				$buffer .= $pos == '|' || $pos == 'MSH' ? $pos : $pos . '|';
				if($pos != '|'){
					$seg .= $buffer;
					$buffer = '';
				}
			}else{

				unset($pos[0]);
				$subArrayBuffer = array();
				foreach($pos As $j => $sub){

					if(is_string($sub)){
						$subArrayBuffer[] = $sub;
					}else{

						unset($sub[0]);
						$subSubArrayBuffer = array();
						foreach($sub As $k => $subSub){

							if($subSub != ''){
								$subSubArrayBuffer[] = $subSub;
							}
							$this->rawSeg[$i][$j][0] = implode('&', $subSubArrayBuffer);

						}
						if(preg_match("/[^&]/", $this->rawSeg[$i][$j][0])){
							$subArrayBuffer[] = $this->rawSeg[$i][$j][0];
//                            $buffer = '';
						}
					}
				}

				$this->rawSeg[$i][0] = rtrim(implode('^', $subArrayBuffer), '^');
				$buffer .=  $this->rawSeg[$i][0] . '|';

				// if not empty
				if(preg_match("/[^\^&~]/", $this->rawSeg[$i][0])){
					$seg .= $buffer;
					$buffer = '';
				}
			}
		}

//        print_r($this->rawSeg);

		return $this->seg = rtrim($seg, '|');

	}

	function setValue($pos, $data){
		$foo = explode('.',$pos);
		if(count($foo) == 1){
			$this->rawSeg[$foo[0]] = $data;
		}elseif(count($foo) == 2){
			$this->rawSeg[$foo[0]][$foo[1]] = $data;
		}elseif(count($foo) == 3){
			$this->rawSeg[$foo[0]][$foo[1]][$foo[2]] = $data;
		}
	}

	function getType($type){

		$types = array();

		$types['PT'][0] = '';               // (PT)
		$types['PT'][1] = '';               // Processing Code (ID)
		$types['PT'][2] = '';               // Processing Mode (ID)

		$types['TS'][0] = '';               // Date (TS)
		$types['TS'][1] = '';               // Time (DTM)
		$types['TS'][2] = '';               // Degree of Precision (ID)

		$types['CE'][0] = '';               // (CE)
		$types['CE'][1] = '';               // Identifier (ST)
		$types['CE'][2] = '';               // Text (ST)
		$types['CE'][3] = '';               // Name of Coding System (ID)
		$types['CE'][4] = '';               // Alternate Identifier (ST)
		$types['CE'][5] = '';               // Alternate Text (ST)
		$types['CE'][6] = '';               // Name of Alternate Coding System (ID)

		$types['FN'][0] = '';               // (FN)
		$types['FN'][1] = '';               // Surname (ST)
		$types['FN'][2] = '';               // Own Surname Prefix (ST)
		$types['FN'][3] = '';               // Own Surname (ST)
		$types['FN'][4] = '';               // Surname Prefix From Partner/Spouse (ST)
		$types['FN'][5] = '';               // Surname From Partner/Spouse (ST)

		$types['HD'][0] = '';               // (HD)
		$types['HD'][1] = '';               // Namespace ID (IS)
		$types['HD'][2] = '';               // Universal ID (ST)
		$types['HD'][3] = '';               // Universal ID Type (ID)

		$types['VID'][0] = '';              // (VID)
		$types['VID'][1] = '2.5.1';         // Version ID (ID)
		$types['VID'][2] = $types['CE'];    // Internationalization Code (CE)
		$types['VID'][3] = $types['CE'];    // International Version Code (CE)

		$types['LA2'][0] = '';             // (LA2)
		$types['LA2'][1] = '';             // Point of Care (IS)
		$types['LA2'][2] = '';             // Room (IS)
		$types['LA2'][3] = '';             // Bed (IS)
		$types['LA2'][4] = $types['HD'];   // Facility (HD)
		$types['LA2'][5] = '';             // Location Status (IS)
		$types['LA2'][6] = '';             // Patient Location Type (IS)
		$types['LA2'][7] = '';             // Building (IS)
		$types['LA2'][8] = '';             // Floor (IS)
		$types['LA2'][9] = '';             // Street Address (ST)
		$types['LA2'][10] = '';            // Other Designation (ST)
		$types['LA2'][11] = '';            // City (ST)
		$types['LA2'][12] = '';            // State or Province (ST)
		$types['LA2'][13] = '';            // Zip or Postal Code (ST
		$types['LA2'][14] = '';            // Country (ID)
		$types['LA2'][15] = '';            // Address Type (ID)
		$types['LA2'][16] = '';            // Other Geographic Designation
		$types['LA2'][17] = '';            //


		$types['MSG'][0] = '';              // (MSG)
		$types['MSG'][1] = '';              // Message Code (ID)
		$types['MSG'][2] = '';              // Trigger Event (ID)
		$types['MSG'][3] = '';              // Message Structure (ID)

		$types['DLN'][0] = '';              // (DLN)
		$types['DLN'][1] = '';              // License Number (ST)>
		$types['DLN'][2] = '';              // Issuing State, Province, Country (IS)>
		$types['DLN'][3] = '';              // Expiration Date (DT)

		$types['CWE'][0] = '';              // (CWE)
		$types['CWE'][1] = '';              // Identifier (ST)
		$types['CWE'][2] = '';              // Text (ST)
		$types['CWE'][3] = '';              // Name of Coding System (ID)
		$types['CWE'][4] = '';              // Alternate Identifier (ST)
		$types['CWE'][5] = '';              // Alternate Text (ST)
		$types['CWE'][6] = '';              // Name of Alternate Coding System (ID)
		$types['CWE'][7] = '';              // Coding System Version ID (ST)
		$types['CWE'][8] = '';              // Alternate Coding System Version ID (ST)
		$types['CWE'][9] = '';              // Original Text (ST)

		$types['XAD'][0] = '';              // (SAD)
		$types['XAD'][1] = '';              // Street Address (SAD)
		$types['XAD'][2] = '';              // Other Designation (ST)>
		$types['XAD'][3] = '';              // City (ST)>
		$types['XAD'][4] = '';              // State or Province (ST)
		$types['XAD'][5] = '';              // Zip or Postal Code (ST)>
		$types['XAD'][6] = '';              // Country (ID)>
		$types['XAD'][7] = '';              // Address Type (ID)
		$types['XAD'][8] = '';              // Other Geographic Designation (ST)>
		$types['XAD'][9] = '';              // County/Parish Code (IS)
		$types['XAD'][10] = '';             // Census Tract (IS)>
		$types['XAD'][11] = '';             // Address Representation Code (ID)>
		$types['XAD'][12] = '';             // Address Validity Range (DR)
		$types['XAD'][13] = $types['TS'];   // Effective Date (TS)>
		$types['XAD'][14] = $types['TS'];   // Expiration Date (TS)>

		$types['XPN'][0] = '';               // (XCN)
		$types['XPN'][1] = '';               // ID Number (ST)>
		$types['XPN'][2] = $types['FN'];     // Family Name (FN)
		$types['XPN'][3] = '';               // Given Name (ST)
		$types['XPN'][4] = '';               // Second and Further Given Names or Initials Thereof (ST)>
		$types['XPN'][5] = '';               // Suffix (e.g., JR or III) (ST)
		$types['XPN'][6] = '';               // Prefix (e.g., DR) (ST)
		$types['XPN'][7] = '';               // DEPRECATED-Degree (e.g., MD) (IS)
		$types['XPN'][8] = '';               // Source Table (IS)>
		$types['XPN'][9] = $types['TS'];     // Assigning Authority (HD)>
		$types['XPN'][10] = '';               // Name Type Code (ID)
		$types['XPN'][11] = '';               // Identifier Check Digit (ST)>
		$types['XPN'][12] = '';               // <Check Digit Scheme (ID)>
		$types['XPN'][13] = '';               // Identifier Type Code (ID)
		$types['XPN'][14] = '';               // Assigning Facility (HD)>
		$types['XPN'][15] = '';               // Name Representation Code (ID)
		$types['XPN'][16] = $types['CE'];     // Name Context (CE)
		$types['XPN'][17] = '';              // DEPRECATED-Name Validity Range (DR)>
		$types['XPN'][18] = '';              // Name Assembly Order (ID)
		$types['XPN'][19] = $types['TS'];    // Effective Date (TS)
		$types['XPN'][20] = $types['TS'];    // Expiration Date (TS)
		$types['XPN'][21] = '';              // Professional Suffix (ST)
		$types['XPN'][22] = $types['CWE'];   // Assigning Jurisdiction (CWE)
		$types['XPN'][23] = $types['CWE'];   // Assigning Agency or Department (CWE)


		$types['XCN'][0] = '';               // (XPN)
		$types['XCN'][1] = $types['FN'];     // Family Name (FN)
		$types['XCN'][2] = '';               // Given Name (ST)
		$types['XCN'][3] = '';               // Second and Further Given Names or Initials Thereof (ST)>
		$types['XCN'][4] = '';               // Suffix (e.g., JR or III) (ST)
		$types['XCN'][5] = '';               // Prefix (e.g., DR) (ST)
		$types['XCN'][6] = '';               // Degree (e.g., MD) (IS)
		/**
		 * Value Description Comment
		 * HL7 Table 0200 - Name Type
		 * A Alias Name
		 * B Name at Birth
		 * C Adopted Name
		 * D Display Name
		 * I Licensing Name
		 * L Legal Name
		 * M Maiden Name
		 * N Nickname /”Call me” Name/Street Name
		 * P Name of Partner/Spouse - obsolete Deprecated in V2.4
		 * R Registered Name (animals only)
		 * S Coded Pseudo-Name to ensure anonymity
		 * T Indigenous/Tribal/Community Name
		 * U Unspecified
		 * For animals, if a Name Type of “R” is used, use “Name Context” to identify the authority with
		 */
		$types['XCN'][7] = '';                  // Name Type Code (ID)
		$types['XCN'][8] = '';                  // Name	Representation Code (ID)
		$types['XCN'][9] = $types['CE'];        // Name Context (CE)
		$types['XCN'][10] = '';                 // Name Validity Range (DR)
//		$types['XPN'][10][0] = '';              // Sub-components for Name Validity Range (DR):
//		$types['XPN'][10][1] = $types['TS'];    // Range Start Date/Time (TS)
//		$types['XPN'][10][2] = $types['TS'];    // Range End Date/Time (TS)
		$types['XCN'][11] = '';                 // Name Assembly Order (ID)
		$types['XCN'][12] = $types['TS'];       // Effective Date (TS)
		$types['XCN'][13] = $types['TS'];       // Expiration Date (TS)
		$types['XCN'][14] = '';                 // Professional Suffix (ST)



		$types['XTN'][0] = '';              // (XTN)
		$types['XTN'][1] = '';              // Telephone Number (ST)
		$types['XTN'][2] = '';              // Telecommunication Use Code (ID)
		$types['XTN'][3] = '';              // Telecommunication Equipment Type (ID)
		$types['XTN'][4] = '';              // Email Address (ST)
		$types['XTN'][5] = '';              // Country Code (NM)
		$types['XTN'][6] = '';              // Area/City Code (NM)
		$types['XTN'][7] = '';              // Local Number (NM)
		$types['XTN'][8] = '';              // Extension (NM)
		$types['XTN'][9] = '';              // Any Text (ST)
		$types['XTN'][10] = '';             // Extension Prefix (ST)
		$types['XTN'][11] = '';             // Speed Dial Code (ST)
		$types['XTN'][12] = '';             // Un-formatted Telephone number (ST)

		$types['CX'][0] = '';               // (CX)
		$types['CX'][1] = '';               // Number (ST)
		$types['CX'][2] = '';               // heck Digit (ST)>
		$types['CX'][3] = '';               // Check Digit Scheme (ID)
		$types['CX'][4] = $types['HD'];     // Assigning Authority (HD)
		$types['CX'][5] = '';               // Identifier Type Code (ID)
		$types['CX'][6] = $types['HD'];     // Assigning Facility (HD)
		$types['CX'][7] = '';               // Effective Date (DT)
		$types['CX'][8] = '';               // Expiration Date (DT)
		$types['CX'][9] = $types['CWE'];    // Assigning Jurisdiction (CWE)
		$types['CX'][10] = $types['CWE'];   // Assigning Agency or Department (CWE)




		return $types[$type];

	}

}