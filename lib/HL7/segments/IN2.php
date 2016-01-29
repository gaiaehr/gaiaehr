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
		parent::__construct($hl7, 'IN2');
		$this->setField(1, 'CX', 4);
		$this->setField(2, 'ST', 4);
		$this->setField(3, 'XCN', 4);
		$this->setField(4, 'IS', 4);
		$this->setField(5, 'IS', 4);
		$this->setField(6, 'ST', 4);
		$this->setField(7, 'XPN', 4);
		$this->setField(8, 'ST', 4);
		$this->setField(9, 'XPN', 4);
		$this->setField(10, 'ST', 4);
		$this->setField(11, 'CE', 4);
		$this->setField(12, 'ST', 4);
		$this->setField(13, 'ST', 4);
		$this->setField(14, 'IS', 4);
		$this->setField(15, 'IS', 4);
		$this->setField(16, 'IS', 4);
		$this->setField(17, 'DT', 4);
		$this->setField(18, 'ID', 4);
		$this->setField(19, 'ID', 4);
		$this->setField(20, 'ID', 4);
		$this->setField(21, 'ST', 4);
		$this->setField(22, 'XPN', 4);
		$this->setField(23, 'ST', 4);
		$this->setField(24, 'IS', 4);
		$this->setField(25, 'CX', 4);
		$this->setField(26, 'CX', 4);
		$this->setField(27, 'IS', 4);
		$this->setField(28, 'RMC', 4);
		$this->setField(29, 'PTA', 4);
		$this->setField(30, 'DDI', 4);
		$this->setField(31, 'IS', 4);
		$this->setField(32, 'IS', 4);
		$this->setField(33, 'CE', 4);
		$this->setField(34, 'CE', 4);
		$this->setField(35, 'IS', 4);
		$this->setField(36, 'CE', 4);
		$this->setField(37, 'ID', 4);
		$this->setField(38, 'IS', 4);
		$this->setField(39, 'CE', 4);
		$this->setField(40, 'XPN', 4);
		$this->setField(41, 'CE', 4);
		$this->setField(42, 'CE', 4);
		$this->setField(43, 'CE', 4);
		$this->setField(44, 'DT', 4);
		$this->setField(45, 'DT', 4);
		$this->setField(46, 'ST', 4);
		$this->setField(47, 'JCC', 4);
		$this->setField(48, 'IS', 4);
		$this->setField(49, 'XPN', 4);
		$this->setField(50, 'XTN', 4);
		$this->setField(51, 'IS', 4);
		$this->setField(52, 'XPN', 4);
		$this->setField(53, 'XTN', 4);
		$this->setField(54, 'IS', 4);
		$this->setField(55, 'DT', 4);
		$this->setField(56, 'DT', 4);
		$this->setField(57, 'IS', 4);
		$this->setField(58, 'XTN', 4);
		$this->setField(59, 'IS', 4);
		$this->setField(60, 'IS', 4);
		$this->setField(61, 'CX', 4);
		$this->setField(62, 'CE', 4);
		$this->setField(63, 'XTN', 4);
		$this->setField(64, 'XTN', 4);
		$this->setField(65, 'CE', 4);
		$this->setField(66, 'ID', 4);
		$this->setField(67, 'ID', 4);
		$this->setField(68, 'ID', 4);
		$this->setField(69, 'XON', 4);
		$this->setField(70, 'XON', 4);
		$this->setField(71, 'CE', 4);
		$this->setField(72, 'CE', 4);
	}
}