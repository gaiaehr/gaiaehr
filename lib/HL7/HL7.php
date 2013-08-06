<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:04 PM
 * To change this template use File | Settings | File Templates.
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
