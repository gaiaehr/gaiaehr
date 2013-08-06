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
			$msg .= $segment->build() . PHP_EOL;
		}
		return $msg. PHP_EOL;
	}
}

//print '<pre>';
//$hl7 = new HL7();
//$hl7->addSegment('MSH');
//print_r($hl7->getSegment('MSH')->build());
//print_r($hl7->getSegment('MSH'));
