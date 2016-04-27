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
class HL7 {

	/**
	 * Stores an array of segments
	 * @var Segments array
	 */
	public  $segments = array();

	/**
	 * @var string
	 */
	public  $message;


	function __destruct()
	{
		unset($this->message);

		foreach($this->segments As $i => $seg){
			unset($this->segments[$i]);
		}
//		print 'Destroying class "'. get_class($this). '" ' .date('Y-m-d H:i:s').PHP_EOL;
	}

	/**
	 * @return mixed
	 */
	function getSendingApplication(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[3][1] != '' ? $seg->data[3][1] : $seg->data[3][2];
		return null;
	}

	/**
	 * @return mixed
	 */
	function getSendingFacility(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[4][1] != '' ? $seg->data[4][1] : $seg->data[4][2];
		return null;
	}

	/**
	 * @param string $format
	 * @return string
	 */
	function getMsgTime($format = 'Y-m-d H:i:s'){
		$seg = $this->getSegment('MSH');
		if(!isset($seg->data)) return null;
		$time = $seg->data[7][1];
		return $this->time($time, $format);
	}

	/**
	 * @return string
	 */
	function getMsgSecurity(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[8];
		return null;
	}

	/**
	 * @return array
	 */
	function getMsgType(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[9][1];
		return null;
	}

	/**
	 * @return array
	 */
	function getMsgEventType(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[9][2];
		return null;
	}

	/**
	 * @return array
	 */
	function getMsgStructure(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[9][3];
		return null;
	}

	/**
	 * @return array
	 */
	function getMsgControlId(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[10];
		return null;
	}

	/**
	 * @return array
	 */
	function getMsgProcessingId(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[11][1];
		return null;
	}

	/**
	 * @return array
	 */
	function getMsgProcessingMode(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[11][2];
		return null;
	}

	/**
	 * @return string
	 */
	function getMsgVersionId(){
		$seg = $this->getSegment('MSH');
		if(isset($seg->data)) return $seg->data[12][1];
		return null;
	}

	/**
	 * @param $segment
	 * @return Segments
	 * @throws Exception
	 */
	function addSegment($segment){
		try{
			if(!class_exists($segment)){
				include_once(dirname(__FILE__)."/segments/$segment.php");
			}
			$this->segments[] = new $segment($this);
			return end($this->segments);
		}catch (Exception $e){
			throw new Exception("$segment Segment Not Fount");
		}
	}

	/**
	 * @param $segment
	 * @return Segments
	 */
	function getSegment($segment){
		foreach($this->segments AS $s){
			if(get_class($s) == $segment) return $s;
		}
		return null;
	}

	/**
	 * @param $segment
	 * @return array
	 */
	function getSegments($segment = null){
		if($segment == null) return $this->segments;
		$segments = array();
		foreach($this->segments AS $s){
			if(get_class($s) == $segment) $segments[] = $s;
		}
		return $segments;
	}

	/**
	 * @return string
	 */
	function getMessage(){
		$msg = '';
		foreach($this->segments As $segment){
			$msg .= $segment->build();
		}
		return $msg;
	}

	/**
	 * @param $msg
	 * @return Message
	 */
	function readMessage($msg){
		$msg = trim($msg);
		$segments = explode(chr(0x0d), $msg);

		if(count($segments) < 2){
			$segments = preg_split("/\n/", $msg);
		}

		foreach($segments AS $segment){
			$this->readSegment($segment);
		}

		$type = $this->getMsgType();
		if(strlen($type) !== 3) return false;

		if(!class_exists($type)){
			$file = dirname(__FILE__)."/messages/$type.php";
			if(!file_exists($file)) return false;
			include_once ($file);
		}

		$this->message = new $type($this);

		$mType = $this->getMsgEventType();
		if($mType === null) return false;

		$this->message->readMessage($this->getMsgEventType());
		return $this->message;
	}

	/**
	 * @param $segment string
	 * @return string|Segments
	 */
	function readSegment($segment){
		$seg = substr(trim($segment), 0, 3);

		if(strlen($seg) !== 3) return false;

		if($seg != ''){
			if(!class_exists($seg)){
				$file = dirname(__FILE__)."/segments/$seg.php";
				if(!file_exists($file)) return false;
				include_once ($file);
			}
			$this->segments[] = new $seg($this);
			end($this->segments)->parse($segment);
		}
		return true;
	}

	function time($time, $format = 'Y-m-d H:i:s'){

		switch(strlen($time)){
			case 4:
				$time = preg_replace('/^([0-9]{4})$/', '$1-01-01 00:00:00', $time);
				break;
			case 6:
				$time = preg_replace('/^([0-9]{4})([0-9]{2})$/', '$1-$2-01 00:00:00', $time);
				break;
			case 8:
				$time = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', '$1-$2-$3 00:00:00', $time);
				break;
			case 10:
				$time = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1-$2-$3 $4:00:00', $time);
				break;
			case 12:
				$time = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1-$2-$3 $4:$5:00', $time);
				break;
			case 14:
				$time = preg_replace('/^([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$/', '$1-$2-$3 $4:$5:$6', $time);
				break;
		}
		if($time == '' || $format == 'Y-m-d H:i:s'){
			return $time;
		}else{
			return date($format, strtotime($time));
		}
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
				$text = 'Separated';
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
