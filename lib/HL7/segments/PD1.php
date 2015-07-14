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
		parent::__construct($hl7, 'PD1');
		$this->setField(1, 'IS', 2, false, true);    // TABLE 0223
		$this->setField(2, 'IS', 2);    // TABLE 0220
		$this->setField(3, 'XON', 250, false, true);
		$this->setField(4, 'XCN', 250, false, true);
		$this->setField(5, 'IS', 2);    // TABLE 0231
		$this->setField(6, 'IS', 2);    // TABLE 0295
		$this->setField(7, 'IS', 2);    // TABLE 0315
		$this->setField(8, 'IS', 2);    // TABLE 0316
		$this->setField(9, 'ID', 1);    // TABLE 0136
		$this->setField(10, 'CX', 250, false, true);
		$this->setField(11, 'CE', 250);   // TABLE 0215
		$this->setField(12, 'ID', 1);   // TABLE 0136
		$this->setField(13, 'DT', 8);
		$this->setField(14, 'XON', 250, false, true);
		$this->setField(15, 'CE', 250, false, true);   // TABLE 0435
		$this->setField(16, 'IS', 1);   // TABLE 0441
		$this->setField(17, 'DT', 8);
		$this->setField(18, 'DT', 8);
		$this->setField(19, 'IS', 5);   // TABLE 0140
		$this->setField(20, 'IS', 2);   // TABLE 0141
		$this->setField(21, 'IS', 3);   // TABLE 0142
	}
}