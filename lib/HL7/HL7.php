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
class HL7 {

	/**
	 * @var Segments array
	 */
	public  $segments = array();

	/**
	 * @param $segment
	 * @return Segments
	 * @throws Exception
	 */
	function addSegment($segment){
		try{
			include_once (str_replace('\\', '/',__DIR__)."/segments/$segment.php");
			$this->segments[] = $seg = new $segment();
			return $seg;
		}catch (Exception $e){
			throw new Exception("$segment Segment Not Fount");
		}
	}

	/**
	 * @param $segment
	 * @return Segments
	 */
	function getSegment($segment){
		return $this->$segment;
	}

	/**
	 * @return string
	 */
	function getMessage(){
		$msg = '';
		foreach($this->segments As $segment){
			$msg .= $segment->build();
		}
		return $msg. "\r";
	}

	/**
	 * Get race text by code
	 * @param $code
	 * @return string
	 */
	function race($code){
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
		$text = '';
		switch($code){
			case '1002-5':
				$text = 'American Indian or Alaska Native';
				break;
			case '2028-9':
				$text = 'Asian';
				break;
			case '2054-5':
				$text = 'Black or African American';
				break;
			case '2076-8':
				$text = 'Native Hawaiian or Other Pacific Islander';
				break;
			case '2106-3':
				$text = 'White';
				break;
			case '2131-1':
				$text = 'Other Race';
				break;
		};
		return $text;

	}

	/**
	 * Get sex text by code
	 * @param $code
	 * @return string
	 */
	function sex($code){
		/**
		 * F Female
		 * M Male
		 * O Other
		 * U Unknown
		 * A Ambiguous
		 * N Not applicable
		 */
		$text = '';
		switch($code){
			case 'F':
				$text = 'Female';
				break;
			case 'M':
				$text = 'Male';
				break;
			case 'O':
				$text = 'Other';
				break;
			case 'U':
				$text = 'Unknown';
				break;
			case 'A':
				$text = 'Ambiguous';
				break;
			case 'N':
				$text = 'Not applicable';
				break;
		};
		return $text;
	}
	/**
	 * Get Ethnic Group text by code
	 * @param $code
	 * @return string
	 */
	function ethnic($code){
		/**
		 * H Hispanic or Latino
		 * N Not Hispanic or Latino
		 * U Unknown
		 */
		$text = '';
		switch($code){
			case 'H':
				$text = 'Hispanic or Latino';
				break;
			case 'N':
				$text = 'Not Hispanic or Latino';
				break;
			case 'U':
				$text = 'Unknown';
				break;
		};
		return $text;
	}

	/**
	 * Get marital status text by code
	 * @param $code
	 * @return string
	 */
	function marital($code){
		/**
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
		$text = '';
		switch($code){
			case 'A':
				$text = 'Hispanic or Latino';
				break;
			case 'D':
				$text = 'Divorced';
				break;
			case 'M':
				$text = 'Married';
				break;
			case 'S':
				$text = 'Single';
				break;
			case 'C':
				$text = 'Common law';
				break;
			case 'G':
				$text = 'Living together';
				break;
			case 'P':
				$text = 'Domestic partner';
				break;
			case 'R':
				$text = 'Registered domestic partner';
				break;
			case 'E':
				$text = 'Legally Separated';
				break;
			case 'N':
				$text = 'Annulled';
				break;
			case 'I':
				$text = 'Interlocutory';
				break;
			case 'B':
				$text = 'Unmarried';
				break;
			case 'O':
				$text = 'Other';
				break;
			case 'T':
				$text = 'Unreported';
				break;

		};
		return $text;
	}


}

//print '<pre>';
//$hl7 = new HL7();
//$hl7->addSegment('MSH');
//print_r($hl7->getSegment('MSH')->build());
//print_r($hl7->getSegment('MSH'));
