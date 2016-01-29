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
include_once(dirname(__FILE__) . '/Segments.php');

class SCH extends Segments {

	function __destruct() {
		parent::__destruct();
	}

	function __construct($hl7) {
		parent::__construct($hl7, 'SCH');

		$this->setField(1, 'EI', 75);
		$this->setField(2, 'EI', 75);
		$this->setField(3, 'NM', 5);
		$this->setField(4, 'EI', 22);
		$this->setField(5, 'CE', 250);
		$this->setField(6, 'CE', 250, true);
		$this->setField(7, 'CE', 250); // TABLE 0276
		$this->setField(8, 'CE', 250); // TABLE 0277
		$this->setField(9, 'NM', 20);
		$this->setField(10, 'CE', 250);
		$this->setField(11, 'TQ', 200, false, true);
		$this->setField(12, 'XCN', 250, false, true);
		$this->setField(13, 'XTN', 250);
		$this->setField(14, 'XAD', 250, false, true);
		$this->setField(15, 'PL', 80);
		$this->setField(16, 'XCN', 250, true, true);
		$this->setField(17, 'XTN', 250);
		$this->setField(18, 'XAD', 250, false, true);
		$this->setField(19, 'PL', 80);
		$this->setField(20, 'XCN', 250, true, true);
		$this->setField(21, 'XTN', 250, false, true);
		$this->setField(22, 'PL', 80);
		$this->setField(23, 'EI', 75);
		$this->setField(24, 'EI', 75);
		$this->setField(25, 'CE', 250); // TABLE 0278
		$this->setField(26, 'EI', 22);
		$this->setField(27, 'EI', 22);


	}
}