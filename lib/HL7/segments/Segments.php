<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 5:00 PM
 * To change this template use File | Settings | File Templates.
 */

class Segments {


	public $buffer;
	public $rawSeg;
	public $seg;

	/**
	 * @param int $length
	 * @return string
	 */
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
		$this->parseArray($this->rawSeg);
		return $this->seg . "\r";
	}

	function parse($string){
		$this->parseStr($string);
		return $this->rawSeg;
	}

	/**
	 * @param $array
	 * @param int $glue
	 * @return mixed
	 */
	private function parseArray($array, $glue = 0){
		$glues = ['|','^','&'];
		$buffer = [];
		foreach($array as $index => $value) {

			if($glue == 0 || $index > 0){
				if(is_array($value)){
					$array[$index] = $this->parseArray($value, $glue + 1);
					$buffer[] = $array[$index][0];
					continue;
				}

				if($value != '|' ) $buffer[] = $value;
			}
		}

		if($glue != 0){
			$array[0] = rtrim(implode($glues[$glue], $buffer),$glues[$glue]);
		}else{
			$this->seg = rtrim(implode($glues[$glue], $buffer),$glues[$glue]);
		}

		return $array;
	}


	private function parseStr($string){
		// break the string into "fields"
		$fields = explode('|',$string);
		// fix the MSH segment
		if($fields[0] == 'MSH'){
			array_unshift($fields, 'MSH');
			$fields[1] = '|';
		}

		// handle the fields
		foreach($fields AS $i => $field){

			$subFields = explode('^', $field);
			// field only has one value
			if(count($subFields) == 1){
				if(!is_array($this->rawSeg[$i])){
					$this->rawSeg[$i] = $subFields[0];
				}else{
					$this->rawSeg[$i][0] = $subFields[0];
					$this->rawSeg[$i][1] = $subFields[0];
				}
			// field has multiple values
			}else{
				$this->rawSeg[$i][0] = $field;
				foreach($subFields AS $j => $subField){
					$subSubFields = explode('^', $subField);
					// field only has one value
					if(count($subSubFields) == 1){
						if(!is_array($this->rawSeg[$i][$j+1])){
							$this->rawSeg[$i][$j+1] = $subSubFields[0];
						}else{
							$this->rawSeg[$i][$j+1][0] = $subSubFields[0];
							$this->rawSeg[$i][$j+1][1] = $subSubFields[0];
						}

						$subSubSubFields = explode('&',$subSubFields[0]);
						if(count($subSubSubFields) > 1 && $subSubFields[0] != '~\&'){
							foreach($subSubSubFields As $l => $values){
								$this->rawSeg[$i][$j+1][$l+1] = $values;
							}
						}
					}
				}
			}
		}

		return $this->rawSeg;
	}

	/**
	 * @param $field
	 * @param $data
	 */
	function setValue($field, $data){
		$foo = explode('.',$field);
		if(count($foo) == 1){
			$this->rawSeg[$foo[0]] = $data;
		}elseif(count($foo) == 2){
			$this->rawSeg[$foo[0]][$foo[1]] = $data;
		}elseif(count($foo) == 3){
			$this->rawSeg[$foo[0]][$foo[1]][$foo[2]] = $data;
		}
	}

	/**
	 * @param $field
	 * @return mixed
	 */
	function getValue($field){
		$foo = explode('.',$field);
//		if(count($foo) == 1){
//			return $this->rawSeg[$foo[0]];
//		}elseif(count($foo) == 2){
//			return $this->rawSeg[$foo[0]][$foo[1]];
//		}elseif(count($foo) == 3){
//			return $this->rawSeg[$foo[0]][$foo[1]][$foo[2]];
//		}
		return null;
	}

	/**
	 * @param $type
	 * @return mixed
	 */
	function getType($type){

		$types = array();

		$types['ID'] = '';                  // (ID)
		$types['ST'] = '';                  // (ST)
		$types['NM'] = '';                  // (NM)
		$types['SI'] = '';                  // (SI)
		$types['TX'] = '';                  // (TX)
		$types['DT'] = '';                  // (DT)
		$types['IS'] = '';                  // (IS)

		$types['CQ'][0] = '';               // (CQ)
		$types['CQ'][1] = '';               // Identifier (ST)
		$types['CQ'][2] = '';               // Text (ST)
		$types['CQ'][3] = '';               // Name of Coding System (ID)>
		$types['CQ'][4] = '';               // Alternate Identifier (ST)>
		$types['CQ'][5] = '';               // Alternate Text (ST)>
		$types['CQ'][6] = '';               // Name of Alternate Coding System (ID)


		$types['EI'][0] = '';               // (EI)
		$types['EI'][1] = '';               // Entity Identifier (ST)
		$types['EI'][2] = '';               // Namespace ID (IS)
		$types['EI'][3] = '';               // Universal ID (ST)
		$types['EI'][4] = '';               // Universal ID Type (ID)

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

		$types['RI'][0] = '';               // (RI)
		$types['RI'][1] = $types['IS'];     // Repeat Pattern (IS)
		$types['RI'][2] = $types['ST'];     // Explicit Time Interval (ST)


		$types['MO'][0] = '';               // (MO)
		$types['MO'][1] = $types['NM'];     // Quantity (NM)
		$types['MO'][2] = $types['ID'];     // Denomination (ID)

		$types['SAD'][0] = '';              // (SAD)
		$types['SAD'][1] = $types['ST'];    // Street or Mailing Address (ST)
		$types['SAD'][2] = $types['ST'];    // Street Name (ST)
		$types['SAD'][3] = $types['ST'];    // Dwelling Number (ST)

		$types['VID'][0] = '';              // (VID)
		$types['VID'][1] = '2.5.1';         // Version ID (ID)
		$types['VID'][2] = $types['CE'];    // Internationalization Code (CE)
		$types['VID'][3] = $types['CE'];    // International Version Code (CE)

		$types['LA2'][0] = '';              // (LA2)
		$types['LA2'][1] = '';              // Point of Care (IS)
		$types['LA2'][2] = '';              // Room (IS)
		$types['LA2'][3] = '';              // Bed (IS)
		$types['LA2'][4] = $types['HD'];    // Facility (HD)
		$types['LA2'][5] = '';              // Location Status (IS)
		$types['LA2'][6] = '';              // Patient Location Type (IS)
		$types['LA2'][7] = '';              // Building (IS)
		$types['LA2'][8] = '';              // Floor (IS)
		$types['LA2'][9] = '';              // Street Address (ST)
		$types['LA2'][10] = '';             // Other Designation (ST)
		$types['LA2'][11] = '';             // City (ST)
		$types['LA2'][12] = '';             // State or Province (ST)
		$types['LA2'][13] = '';             // Zip or Postal Code (ST
		$types['LA2'][14] = '';             // Country (ID)
		$types['LA2'][15] = '';             // Address Type (ID)
		$types['LA2'][16] = '';             // Other Geographic Designation
		$types['LA2'][17] = '';             //


		$types['MSG'][0] = '';              // (MSG)
		$types['MSG'][1] = '';              // Message Code (ID)
		$types['MSG'][2] = '';              // Trigger Event (ID)
		$types['MSG'][3] = '';              // Message Structure (ID)

		$types['DLN'][0] = '';              // (DLN)
		$types['DLN'][1] = '';              // License Number (ST)
		$types['DLN'][2] = '';              // Issuing State, Province, Country (IS)
		$types['DLN'][3] = '';              // Expiration Date (DT)

		$types['JCC'][0] = '';              // (JCC)
		$types['JCC'][1] = '';              // Job Code (IS)
		$types['JCC'][2] = '';              // Job Class (IS)
		$types['JCC'][3] = '';              // Job Description Text (TX)

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

		$types['XAD'][0] = '';              // (XAD)
		$types['XAD'][1] = $types['SAD'];   // Street Address (SAD)
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

		$types['XCN'][0] = '';              // (XCN)
		$types['XCN'][1] = '';              // ID Number (ST)
		$types['XCN'][2] = $types['FN'];    // Family Name (FN)
		$types['XCN'][3] = '';              // Given Name (ST)
		$types['XCN'][4] = '';              // Second and Further Given Names or Initials Thereof (ST)>
		$types['XCN'][5] = '';              // Suffix (e.g., JR or III) (ST)
		$types['XCN'][6] = '';              // Prefix (e.g., DR) (ST)
		$types['XCN'][7] = '';              // DEPRECATED-Degree (e.g., MD) (IS)
		$types['XCN'][8] = '';              // Source Table (IS)
		$types['XCN'][9] = $types['TS'];    // Assigning Authority (HD)
		$types['XCN'][10] = '';             // Name Type Code (ID)
		$types['XCN'][11] = '';             // Identifier Check Digit (ST)
		$types['XCN'][12] = '';             // Check Digit Scheme (ID)
		$types['XCN'][13] = '';             // Identifier Type Code (ID)
		$types['XCN'][14] = '';             // Assigning Facility (HD)
		$types['XCN'][15] = '';             // Name Representation Code (ID)
		$types['XCN'][16] = $types['CE'];   // Name Context (CE)
		$types['XCN'][17] = '';             // DEPRECATED-Name Validity Range (DR)
		$types['XCN'][18] = '';             // Name Assembly Order (ID)
		$types['XCN'][19] = $types['TS'];   // Effective Date (TS)
		$types['XCN'][20] = $types['TS'];   // Expiration Date (TS)
		$types['XCN'][21] = '';             // Professional Suffix (ST)
		$types['XCN'][22] = $types['CWE'];  // Assigning Jurisdiction (CWE)
		$types['XCN'][23] = $types['CWE'];  // Assigning Agency or Department (CWE)


		$types['XPN'][0] = '';              // (XPN)
		$types['XPN'][1] = $types['FN'];    // Family Name (FN)
		$types['XPN'][2] = '';              // Given Name (ST)
		$types['XPN'][3] = '';              // Second and Further Given Names or Initials Thereof (ST)>
		$types['XPN'][4] = '';              // Suffix (e.g., JR or III) (ST)
		$types['XPN'][5] = '';              // Prefix (e.g., DR) (ST)
		$types['XPN'][6] = '';              // Degree (e.g., MD) (IS)
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
		$types['XPN'][7] = '';                  // Name Type Code (ID)
		$types['XPN'][8] = '';                  // Name	Representation Code (ID)
		$types['XPN'][9] = $types['CE'];        // Name Context (CE)
		$types['XPN'][10] = '';                 // Name Validity Range (DR)
//		$types['XPN'][10][0] = '';              // Sub-components for Name Validity Range (DR):
//		$types['XPN'][10][1] = $types['TS'];    // Range Start Date/Time (TS)
//		$types['XPN'][10][2] = $types['TS'];    // Range End Date/Time (TS)
		$types['XPN'][11] = '';                 // Name Assembly Order (ID)
		$types['XPN'][12] = $types['TS'];       // Effective Date (TS)
		$types['XPN'][13] = $types['TS'];       // Expiration Date (TS)
		$types['XPN'][14] = '';                 // Professional Suffix (ST)

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

		$types['XON'][0] = '';              // (XON)
		$types['XON'][1] = '';              // Organization Name (ST)
		$types['XON'][2] = '';              // Organization Name Type Code (IS)
		$types['XON'][3] = '';              // D Number (NM)
		$types['XON'][4] = '';              // Check Digit (NM)
		$types['XON'][5] = '';              // Check Digit Scheme (ID)
		$types['XON'][6] = $types['HD'];;   // Assigning Authority (HD)
		$types['XON'][7] = '';              // Identifier Type Code (ID)
		$types['XON'][8] = $types['HD'];    // Assigning Facility (HD)
		$types['XON'][9] = '';              // Name Representation Code (ID)
		$types['XON'][10] = '';             // Organization Identifier (ST)

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


		$types['OSD'][0] = '';               // (OSD)
		$types['OSD'][1] = $types['ID'];     // Sequence/Results Flag (ID)
		$types['OSD'][2] = $types['ST'];     // Placer Order Number: Entity Identifier (ST)
		$types['OSD'][3] = $types['IS'];     // Placer Order Number: Namespace ID (IS)
		$types['OSD'][4] = $types['ST'];     // Filler Order Number: Entity Identifier (ST)
		$types['OSD'][5] = $types['IS'];     // Filler Order Number: Namespace ID (IS)
		$types['OSD'][6] = $types['ST'];     // Sequence Condition Value (ST)
		$types['OSD'][7] = $types['NM'];     // Maximum Number of Repeats (NM)
		$types['OSD'][8] = $types['ST'];     // Placer Order Number: Universal ID (ST)
		$types['OSD'][9] = $types['ID'];     // Placer Order Number: Universal ID Type (ID)
		$types['OSD'][10] = $types['ST'];    // Filler Order Number: Universal ID (ST)
		$types['OSD'][11] = $types['ID'];    // Filler Order Number: Universal ID Type (ID)


		$types['TQ'][0] = '';                // (TQ)
		$types['TQ'][1] = $types['CQ'];      // Quantity (CQ)
		$types['TQ'][2] = $types['RI'];      // Interval (RI)
		$types['TQ'][3] = $types['ST'];      // Duration (ST)
		$types['TQ'][4] = $types['TS'];      // Start Date/Time (TS)
		$types['TQ'][5] = $types['TS'];      // End Date/Time (TS)
		$types['TQ'][6] = $types['ST'];      // Priority (ST)
		$types['TQ'][7] = $types['ST'];      // Condition (ST)
		$types['TQ'][8] = $types['TX'];      // Text (TX)
		$types['TQ'][9] = $types['ID'];      // Conjunction (ID)
		$types['TQ'][10] = $types['OSD'];    // Order Sequencing (OSD)
		$types['TQ'][11] = $types['CE'];     // Occurrence Duration (CE)
		$types['TQ'][12] = $types['NM'];     // Total Occurrences (NM)

		$types['EIP'][0] = '';               // (EIP)
		$types['EIP'][1] = $types['EI'];     // Placer Assigned Identifier (EI)
		$types['EIP'][2] = $types['EI'];     // Filler Assigned Identifier (EI)


		$types['PRL'][0] = '';               // (PRL)
		$types['PRL'][1] = $types['CE'];     // Parent Observation Identifier (CE)
		$types['PRL'][2] = $types['ST'];     // Parent Observation Sub-identifier (ST)
		$types['PRL'][3] = $types['TX'];     // Parent Observation Value Descriptor (TX)


		$types['MOC'][0] = '';               // (MOC)
		$types['MOC'][1] = $types['MO'];     // Monetary Amount (MO)
		$types['MOC'][2] = $types['CE'];     // Charge Code (CE)


		$types['SPS'][0] = '';               // (SPS)
		$types['SPS'][1] = $types['CWE'];    // Specimen Source Name or Code (CWE)
		$types['SPS'][2] = $types['CWE'];    // Additives (CWE)
		$types['SPS'][3] = $types['TX'];     // Specimen Collection Method (TX)
		$types['SPS'][4] = $types['CWE'];    // Body Site (CWE)
		$types['SPS'][5] = $types['CWE'];    // Site Modifier (CWE)
		$types['SPS'][6] = $types['CWE'];    // Collection Method Modifier Code (CWE)
		$types['SPS'][7] = $types['CWE'];    // Specimen Role (CWE)


		$types['CNN'][0] = '';               // (CNN)
		$types['CNN'][1] = $types['ST'];     // ID Number (ST)
		$types['CNN'][2] = $types['ST'];     // Family Name (ST)
		$types['CNN'][3] = $types['ST'];     // Given Name (ST)
		$types['CNN'][4] = $types['ST'];     // Second and Further Given Names or Initials Thereof (ST)
		$types['CNN'][5] = $types['ST'];     // Suffix (e.g., JR or III) (ST)
		$types['CNN'][6] = $types['ST'];     // Prefix (e.g., DR) (ST)
		$types['CNN'][7] = $types['IS'];     // Degree (e.g., MD (IS)
		$types['CNN'][8] = $types['IS'];     // Source Table (IS)
		$types['CNN'][9] = $types['IS'];     // Assigning Authority - Namespace ID (IS)
		$types['CNN'][10] = $types['ST'];    // Assigning Authority - Universal ID (ST)
		$types['CNN'][11] = $types['ID'];    // Assigning Authority - Universal ID Type (ID)


		$types['NDL'][0] = '';               // (NDL)
		$types['NDL'][1] = $types['CNN'];    // Name (CNN)
		$types['NDL'][2] = $types['TS'];     // Start Date/time (TS)
		$types['NDL'][3] = $types['TS'];     // End Date/time (TS)
		$types['NDL'][4] = $types['IS'];     // Point of Care (IS)
		$types['NDL'][5] = $types['IS'];     // Room (IS)
		$types['NDL'][6] = $types['IS'];     // Bed (IS)
		$types['NDL'][7] = $types['HD'];     // Facility (HD)
		$types['NDL'][8] = $types['IS'];     // Location Status (IS)
		$types['NDL'][9] = $types['IS'];     // Patient Location Type (IS)
		$types['NDL'][10] = $types['IS'];    // Building (IS)
		$types['NDL'][11] = $types['IS'];    // Floor (IS)

		return $types[$type];

	}

}