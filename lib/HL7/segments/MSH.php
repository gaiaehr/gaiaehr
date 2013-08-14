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

include_once (str_replace('\\', '/',__DIR__).'/Segments.php');

class MSH extends Segments{

	function __construct(){

		$this->rawSeg = array();
		$this->rawSeg[0] = 'MSH';
		$this->rawSeg[1] = '|';
		$this->rawSeg[2] = '^~\&';
		$this->rawSeg[3] = $this->getType('HD');
		$this->rawSeg[4] = $this->getType('HD');
		$this->rawSeg[5] = $this->getType('HD');
		$this->rawSeg[6] = $this->getType('HD');
		$this->rawSeg[7] = $this->getType('TS');
		$this->rawSeg[8] = $this->getType('ST');
		$this->rawSeg[9] = $this->getType('MSG');
		$this->rawSeg[10] = $this->newUID();    // default value is GAIA-#######
		$this->rawSeg[11] = $this->getType('PT');
		$this->rawSeg[12] = $this->getType('VID');
		$this->rawSeg[13] = $this->getType('NM');
		$this->rawSeg[14] = $this->getType('ST');
		$this->rawSeg[15] = $this->getType('ID');
		/**
		 * MSH-16 Application Acknowledgment Type
		 * AL Always
		 * NE Never ER Error/reject conditions only SU Successful completion only
		 */
		$this->rawSeg[16] = $this->getType('ID');
		/**
		 * MSH-17 Country Code
		 * use 3-character (alphabetic) form of ISO 3166
		 */
		$this->rawSeg[17] = $this->getType('ID');
		$this->rawSeg[18] = $this->getType('ID');
		$this->rawSeg[19] = $this->getType('CE');
		$this->rawSeg[20] = $this->getType('ID');
		$this->rawSeg[21] = $this->getType('EI');

	}
}