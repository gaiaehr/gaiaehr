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

class DG1 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'DG1');

		$this->setField(1, 'SI', 4, true);
		$this->setField(2, 'ID', 2, true); // TABLE 0053
		$this->setField(3, 'CE', 250); // TABLE 0051
		$this->setField(4, 'ST', 40);
		$this->setField(5, 'TS', 26);
		$this->setField(6, 'IS', 2, true); // TABLE 0052
		$this->setField(7, 'CE', 250); // TABLE 0118
		$this->setField(8, 'CE', 250); // TABLE 0055
		$this->setField(9, 'ID', 1); // TABLE 0136
		$this->setField(10, 'IS', 2); // TABLE 0056
		$this->setField(11, 'CE', 250); // TABLE 0083
		$this->setField(12, 'NM', 3);
		$this->setField(13, 'CP', 12);
		$this->setField(14, 'ST', 4);
		$this->setField(15, 'ID', 2); // TABLE 0206
		$this->setField(16, 'XCN', 250, false, true);
		$this->setField(17, 'IS', 3); // TABLE 0206
		$this->setField(18, 'ID', 1); // TABLE 0206
		$this->setField(19, 'TS', 26);
		$this->setField(20, 'EI', 427);
		$this->setField(21, 'ID', 1);  // TABLE 0206

	}
}