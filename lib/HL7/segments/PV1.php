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

class PV1 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'PV1');
		$this->setField(1, 'SI', 1);
		$this->setField(2, 'IS', 1, true);
		$this->setField(3, 'PL', 1);
		$this->setField(4, 'IS', 1);
		$this->setField(5, 'CX', 1);
		$this->setField(6, 'PL', 1);
		$this->setField(7, 'XCN', 1, false, true);
		$this->setField(8, 'XCN', 1, false, true);
		$this->setField(9, 'XCN', 1, false, true);
		$this->setField(10, 'IS', 1);
		$this->setField(11, 'PL', 1);
		$this->setField(12, 'IS', 1);
		$this->setField(13, 'IS', 1);
		$this->setField(14, 'IS', 1);
		$this->setField(15, 'IS', 1, false, true);
		$this->setField(16, 'IS', 1);
		$this->setField(17, 'XCN', 1, false, true);
		$this->setField(18, 'IS', 1);
		$this->setField(19, 'CX', 1);
		$this->setField(20, 'FC', 1, false, true);
		$this->setField(21, 'IS', 1);
		$this->setField(22, 'IS', 1);
		$this->setField(23, 'IS', 1);
		$this->setField(24, 'IS', 1, false, true);
		$this->setField(25, 'DT', 1, false, true);
		$this->setField(26, 'NM', 1, false, true);
		$this->setField(27, 'NM', 1, false, true);
		$this->setField(28, 'IS', 1);
		$this->setField(29, 'IS', 1);
		$this->setField(30, 'DT', 1);
		$this->setField(31, 'IS', 1);
		$this->setField(32, 'NM', 1);
		$this->setField(33, 'NM', 1);
		$this->setField(34, 'IS', 1);
		$this->setField(35, 'DT', 1);
		$this->setField(36, 'IS', 1);
		$this->setField(37, 'DLD', 1);
		$this->setField(38, 'CE', 1);
		$this->setField(39, 'IS', 1);
		$this->setField(40, 'IS', 1);
		$this->setField(41, 'IS', 1);
		$this->setField(42, 'PL', 1);
		$this->setField(43, 'PL', 1);
		$this->setField(44, 'TS', 1);
		$this->setField(45, 'TS', 1, false, true);
		$this->setField(46, 'NM', 1);
		$this->setField(47, 'NM', 1);
		$this->setField(48, 'NM', 1);
		$this->setField(49, 'NM', 1);
		$this->setField(50, 'CX', 1);
		$this->setField(51, 'IS', 1);
		$this->setField(52, 'XCN', 1, false, true);
	}
}