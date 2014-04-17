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
include_once (dirname(__FILE__).'/Segments.php');

class IN2 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'IN2';                   // IN2 Message Header Segment
		$this->rawSeg[1] = $this->getType('CX');
		$this->rawSeg[2] = $this->getType('ST');
		$this->rawSeg[3] = $this->getType('XCN');
		$this->rawSeg[4] = $this->getType('IS');
		$this->rawSeg[5] = $this->getType('IS');
		$this->rawSeg[6] = $this->getType('ST');
		$this->rawSeg[7] = $this->getType('XPN');
		$this->rawSeg[8] = $this->getType('ST');
		$this->rawSeg[9] = $this->getType('XPN');
		$this->rawSeg[10] = $this->getType('ST');
		$this->rawSeg[11] = $this->getType('CE');
		$this->rawSeg[12] = $this->getType('ST');
		$this->rawSeg[13] = $this->getType('ST');
		$this->rawSeg[14] = $this->getType('IS');
		$this->rawSeg[15] = $this->getType('IS');
		$this->rawSeg[16] = $this->getType('IS');
		$this->rawSeg[17] = $this->getType('DT');
		$this->rawSeg[18] = $this->getType('ID');
		$this->rawSeg[19] = $this->getType('ID');
		$this->rawSeg[20] = $this->getType('ID');
		$this->rawSeg[21] = $this->getType('ST');
		$this->rawSeg[22] = $this->getType('XPN');
		$this->rawSeg[23] = $this->getType('ST');
		$this->rawSeg[24] = $this->getType('IS');
		$this->rawSeg[25] = $this->getType('CX');
		$this->rawSeg[26] = $this->getType('CX');
		$this->rawSeg[27] = $this->getType('IS');
		$this->rawSeg[28] = $this->getType('RMC');
		$this->rawSeg[29] = $this->getType('PTA');
		$this->rawSeg[30] = $this->getType('DDI');
		$this->rawSeg[31] = $this->getType('IS');
		$this->rawSeg[32] = $this->getType('IS');
		$this->rawSeg[33] = $this->getType('CE');
		$this->rawSeg[34] = $this->getType('CE');
		$this->rawSeg[35] = $this->getType('IS');
		$this->rawSeg[36] = $this->getType('CE');
		$this->rawSeg[37] = $this->getType('ID');
		$this->rawSeg[38] = $this->getType('IS');
		$this->rawSeg[39] = $this->getType('CE');
		$this->rawSeg[40] = $this->getType('XPN');
		$this->rawSeg[41] = $this->getType('CE');
		$this->rawSeg[42] = $this->getType('CE');
		$this->rawSeg[43] = $this->getType('CE');
		$this->rawSeg[44] = $this->getType('DT');
		$this->rawSeg[45] = $this->getType('DT');
		$this->rawSeg[46] = $this->getType('ST');
		$this->rawSeg[47] = $this->getType('JCC');
		$this->rawSeg[48] = $this->getType('IS');
		$this->rawSeg[49] = $this->getType('XPN');
		$this->rawSeg[50] = $this->getType('XTN');
		$this->rawSeg[51] = $this->getType('IS');
		$this->rawSeg[52] = $this->getType('XPN');
		$this->rawSeg[53] = $this->getType('XTN');
		$this->rawSeg[54] = $this->getType('IS');
		$this->rawSeg[55] = $this->getType('DT');
		$this->rawSeg[56] = $this->getType('DT');
		$this->rawSeg[57] = $this->getType('IS');
		$this->rawSeg[58] = $this->getType('XTN');
		$this->rawSeg[59] = $this->getType('IS');
		$this->rawSeg[60] = $this->getType('IS');
		$this->rawSeg[61] = $this->getType('CX');
		$this->rawSeg[62] = $this->getType('CE');
		$this->rawSeg[63] = $this->getType('XTN');
		$this->rawSeg[64] = $this->getType('XTN');
		$this->rawSeg[65] = $this->getType('CE');
		$this->rawSeg[66] = $this->getType('ID');
		$this->rawSeg[67] = $this->getType('ID');
		$this->rawSeg[68] = $this->getType('ID');
		$this->rawSeg[69] = $this->getType('XON');
		$this->rawSeg[70] = $this->getType('XON');
		$this->rawSeg[71] = $this->getType('CE');
		$this->rawSeg[72] = $this->getType('CE');
	}
}