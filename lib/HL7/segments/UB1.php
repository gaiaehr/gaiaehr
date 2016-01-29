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

class UB1 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'UB1';                   // UB1 Message Header Segment
		$this->rawSeg[1] = $this->getType('SI');
		$this->rawSeg[2] = $this->getType('NM');
		$this->rawSeg[3] = $this->getType('NM');
		$this->rawSeg[4] = $this->getType('NM');
		$this->rawSeg[5] = $this->getType('NM');
		$this->rawSeg[6] = $this->getType('NM');
		$this->rawSeg[7] = $this->getType('IS');
		$this->rawSeg[8] = $this->getType('NM');
		$this->rawSeg[9] = $this->getType('NM');
		$this->rawSeg[10] = $this->getType('UVC');
		$this->rawSeg[11] = $this->getType('NM');
		$this->rawSeg[12] = $this->getType('CE');
		$this->rawSeg[13] = $this->getType('CE');
		$this->rawSeg[14] = $this->getType('DT');
		$this->rawSeg[15] = $this->getType('DT');
		$this->rawSeg[16] = $this->getType('OCD');
		$this->rawSeg[17] = $this->getType('CE');
		$this->rawSeg[18] = $this->getType('DT');
		$this->rawSeg[19] = $this->getType('DT');
		$this->rawSeg[20] = $this->getType('ST');
		$this->rawSeg[21] = $this->getType('ST');
		$this->rawSeg[22] = $this->getType('ST');
		$this->rawSeg[23] = $this->getType('ST');
	}
}