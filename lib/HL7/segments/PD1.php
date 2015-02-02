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

class PD1 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'PD1';                   // PD1 Message Header Segment
		$this->rawSeg[1] = $this->getType('IS');    // TABLE 0223
		$this->rawSeg[2] = $this->getType('IS');    // TABLE 0220
		$this->rawSeg[3] = $this->getType('XON');
		$this->rawSeg[4] = $this->getType('XCN');
		$this->rawSeg[5] = $this->getType('IS');    // TABLE 0231
		$this->rawSeg[6] = $this->getType('IS');    // TABLE 0295
		$this->rawSeg[7] = $this->getType('IS');    // TABLE 0315
		$this->rawSeg[8] = $this->getType('IS');    // TABLE 0316
		$this->rawSeg[9] = $this->getType('ID');    // TABLE 0136
		$this->rawSeg[10] = $this->getType('CX');
		$this->rawSeg[11] = $this->getType('CE');   // TABLE 0215
		$this->rawSeg[12] = $this->getType('ID');   // TABLE 0136
		$this->rawSeg[13] = $this->getType('DT');
		$this->rawSeg[14] = $this->getType('XON');
		$this->rawSeg[15] = $this->getType('CE');   // TABLE 0435
		$this->rawSeg[16] = $this->getType('IS');   // TABLE 0441
		$this->rawSeg[17] = $this->getType('DT');
		$this->rawSeg[18] = $this->getType('DT');
		$this->rawSeg[19] = $this->getType('IS');   // TABLE 0140
		$this->rawSeg[20] = $this->getType('IS');   // TABLE 0141
		$this->rawSeg[21] = $this->getType('IS');   // TABLE 0142
	}
}