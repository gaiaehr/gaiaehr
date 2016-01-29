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

class ACC extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'ACC');

		$this->setField(1, 'TS', 26);
		$this->setField(2, 'CE', 250);
		$this->setField(3, 'ST', 25);
		$this->setField(4, 'CE', 250);
		$this->setField(5, 'ID', 1);
		$this->setField(6, 'ID', 12);
		$this->setField(7, 'XCN', 250);
		$this->setField(8, 'ST', 25);
		$this->setField(9, 'ST', 80);
		$this->setField(10, 'ID', 1);
		$this->setField(11, 'XAD', 250);

	}
}