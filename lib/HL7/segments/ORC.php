<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (str_replace('\\', '/',__DIR__).'/Segments.php');

class ORC extends Segments{

	function __construct(){

		$this->rawSeg = array();
		$this->rawSeg[0] = 'ROC';               // ROC Segment
		$this->rawSeg[1] = '';                  // Order Control

	}
}