<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/4/13
 * Time: 4:06 PM
 * To change this template use File | Settings | File Templates.
 */
include_once (dirname(__FILE__).'/Segments.php');

class SPM extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);

		$this->rawSeg = array();
		$this->rawSeg[0] = 'SPM';
		$this->rawSeg[1] = $this->getType('SI');
		$this->rawSeg[2] = $this->getType('EIP');
		$this->rawSeg[3] = $this->getType('EIP');
		$this->rawSeg[4] = $this->getType('CWE');
		$this->rawSeg[5] = $this->getType('CWE');
		$this->rawSeg[6] = $this->getType('CWE');
		$this->rawSeg[7] = $this->getType('CWE');
		$this->rawSeg[8] = $this->getType('CWE');
		$this->rawSeg[9] = $this->getType('CWE');
		$this->rawSeg[10] = $this->getType('CWE');
		$this->rawSeg[11] = $this->getType('CWE');
		$this->rawSeg[12] = $this->getType('CQ');
		$this->rawSeg[13] = $this->getType('NM');
		$this->rawSeg[14] = $this->getType('ST');
		$this->rawSeg[15] = $this->getType('CWE');
		$this->rawSeg[16] = $this->getType('CWE');
		$this->rawSeg[17] = $this->getType('DR');
		$this->rawSeg[18] = $this->getType('TS');
		$this->rawSeg[19] = $this->getType('TS');
		$this->rawSeg[20] = $this->getType('ID');
		$this->rawSeg[21] = $this->getType('CWE');
		$this->rawSeg[22] = $this->getType('CWE');
		$this->rawSeg[23] = $this->getType('CWE');
		$this->rawSeg[24] = $this->getType('CWE');
		$this->rawSeg[25] = $this->getType('CQ');
		$this->rawSeg[26] = $this->getType('NM');
		$this->rawSeg[27] = $this->getType('CWE');
		$this->rawSeg[28] = $this->getType('CWE');
		$this->rawSeg[29] = $this->getType('CWE');

	}
}