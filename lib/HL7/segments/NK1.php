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

class NK1 extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'NK1');
		$this->setField(1, 'SI', 4);
		$this->setField(2, 'XPN', 4);
		$this->setField(3, 'CE', 4);
		$this->setField(4, 'XAD', 4);
		$this->setField(5, 'XTN', 4);
		$this->setField(6, 'XTN', 4);
		$this->setField(7, 'CE', 4);
		$this->setField(8, 'DT', 4);
		$this->setField(9, 'DT', 4);
		$this->setField(10, 'ST', 4);
		$this->setField(11, 'JCC', 4);
		$this->setField(12, 'CX', 4);
		$this->setField(13, 'XON', 4);
		$this->setField(14, 'CE', 4);
		$this->setField(15, 'IS', 4);
		$this->setField(16, 'TS', 4);
		$this->setField(17, 'IS', 4);
		$this->setField(18, 'IS', 4);
		$this->setField(19, 'CE', 4);
		$this->setField(20, 'CE', 4);
		$this->setField(21, 'IS', 4);
		$this->setField(22, 'CE', 4);
		$this->setField(23, 'ID', 4);
		$this->setField(24, 'IS', 4);
		$this->setField(25, 'CE', 4);
		$this->setField(26, 'XPN', 4);
		$this->setField(27, 'CE', 4);
		$this->setField(28, 'CE', 4);
		$this->setField(29, 'CE', 4);
		$this->setField(30, 'XPN', 4);
		$this->setField(31, 'XTN', 4);
		$this->setField(32, 'XAD', 4);
		$this->setField(33, 'CX', 4);
		$this->setField(34, 'IS', 4);
		$this->setField(35, 'CE', 4);
		$this->setField(36, 'IS', 4);
		$this->setField(37, 'ST', 4);
		$this->setField(38, 'ST', 4);
		$this->setField(39, 'IS', 4);


	}
}