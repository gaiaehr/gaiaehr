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

class AIG extends Segments {

	function __destruct() {
		parent::__destruct();
	}

	function __construct($hl7) {
		parent::__construct($hl7, 'AIG');

		$this->setField(1, 'SI', 4, true);
		$this->setField(2, 'ID', 3); // TABLE 0206
		$this->setField(3, 'CE', 250);
		$this->setField(4, 'CE', 250, true);
		$this->setField(5, 'CE', 250, false, true);
		$this->setField(6, 'NM', 5);
		$this->setField(7, 'CE', 250);
		$this->setField(8, 'TS', 26);
		$this->setField(9, 'NM', 20);
		$this->setField(10, 'CE', 250);
		$this->setField(11, 'NM', 20);
		$this->setField(12, 'CE', 250);
		$this->setField(13, 'IS', 10); // TABLE 0279
		$this->setField(14, 'CE', 250); // TABLE 0278

	}
}