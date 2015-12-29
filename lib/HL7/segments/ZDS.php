<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (dirname(__FILE__).'/Segments.php');


class ZDS extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'ZDS');
		$this->setField(1, 'ST', 250);
		$this->setField(2, 'ST', 250);
		$this->setField(3, 'ST', 250);
		$this->setField(4, 'ST', 250);
		$this->setField(5, 'ST', 250);
		$this->setField(6, 'ST', 250);


	}
}