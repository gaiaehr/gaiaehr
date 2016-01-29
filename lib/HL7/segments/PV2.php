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

class PV2 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'PV2';                   // PV2 Message Header Segment
		$this->rawSeg[1] = $this->getType('PL');
		$this->rawSeg[2] = $this->getType('CE');
		$this->rawSeg[3] = $this->getType('CE');
		$this->rawSeg[4] = $this->getType('CE');
		$this->rawSeg[5] = $this->getType('ST');
		$this->rawSeg[6] = $this->getType('ST');
		$this->rawSeg[7] = $this->getType('IS');
		$this->rawSeg[8] = $this->getType('TS');
		$this->rawSeg[9] = $this->getType('TS');
		$this->rawSeg[10] = $this->getType('NM');
		$this->rawSeg[11] = $this->getType('NM');
		$this->rawSeg[12] = $this->getType('ST');
		$this->rawSeg[13] = $this->getType('XCN');
		$this->rawSeg[14] = $this->getType('DT');
		$this->rawSeg[15] = $this->getType('ID');
		$this->rawSeg[16] = $this->getType('IS');
		$this->rawSeg[17] = $this->getType('DT');
		$this->rawSeg[18] = $this->getType('IS');
		$this->rawSeg[19] = $this->getType('ID');
		$this->rawSeg[20] = $this->getType('NM');
		$this->rawSeg[21] = $this->getType('IS');
		$this->rawSeg[22] = $this->getType('ID');
		$this->rawSeg[23] = $this->getType('XON');
		$this->rawSeg[24] = $this->getType('IS');
		$this->rawSeg[25] = $this->getType('IS');
		$this->rawSeg[26] = $this->getType('DT');
		$this->rawSeg[27] = $this->getType('IS');
		$this->rawSeg[28] = $this->getType('DT');
		$this->rawSeg[29] = $this->getType('DT');
		$this->rawSeg[30] = $this->getType('CE');
		$this->rawSeg[31] = $this->getType('IS');
		$this->rawSeg[32] = $this->getType('ID');
		$this->rawSeg[33] = $this->getType('TS');
		$this->rawSeg[34] = $this->getType('ID');
		$this->rawSeg[35] = $this->getType('ID');
		$this->rawSeg[36] = $this->getType('ID');
		$this->rawSeg[37] = $this->getType('ID');
		$this->rawSeg[38] = $this->getType('CE');
		$this->rawSeg[39] = $this->getType('CE');
		$this->rawSeg[40] = $this->getType('CE');
		$this->rawSeg[41] = $this->getType('CE');
		$this->rawSeg[42] = $this->getType('CE');
		$this->rawSeg[43] = $this->getType('IS');
		$this->rawSeg[44] = $this->getType('IS');
		$this->rawSeg[45] = $this->getType('CE');
		$this->rawSeg[46] = $this->getType('DT');
		$this->rawSeg[47] = $this->getType('TS');
		$this->rawSeg[48] = $this->getType('TS');
		$this->rawSeg[49] = $this->getType('IS');


	}
}