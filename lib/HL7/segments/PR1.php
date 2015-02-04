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

class PR1 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'PR1';                   // PR1 Message Header Segment
		$this->rawSeg[1] = $this->getType('SI');
		$this->rawSeg[2] = $this->getType('IS');    // TABLE 0089
		$this->rawSeg[3] = $this->getType('CE');    // TABLE 0088
		$this->rawSeg[4] = $this->getType('ST');
		$this->rawSeg[5] = $this->getType('TS');
		$this->rawSeg[6] = $this->getType('IS');    // TABLE 0230
		$this->rawSeg[7] = $this->getType('NM');
		$this->rawSeg[8] = $this->getType('XCN');   // TABLE 0010
		$this->rawSeg[9] = $this->getType('IS');    // TABLE 0019
		$this->rawSeg[10] = $this->getType('NM');
		$this->rawSeg[11] = $this->getType('XCN');  // TABLE 0010
		$this->rawSeg[12] = $this->getType('XCN');  // TABLE 0010
		$this->rawSeg[13] = $this->getType('CE');   // TABLE 0059
		$this->rawSeg[14] = $this->getType('ID');   // TABLE 0418
		$this->rawSeg[15] = $this->getType('CE');   // TABLE 0051
		$this->rawSeg[16] = $this->getType('CE');   // TABLE 0340
		$this->rawSeg[17] = $this->getType('IS');   // TABLE 0416
		$this->rawSeg[18] = $this->getType('CE');   // TABLE 0417
		$this->rawSeg[19] = $this->getType('EI');
		$this->rawSeg[20] = $this->getType('ID');   // TABLE 0206

	}
}