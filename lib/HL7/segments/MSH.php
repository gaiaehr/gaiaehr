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

class MSH extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'MSH');

		// 2 and 3 are set by default in constructor

		$this->setField(3, 'HD', 227);
		$this->setField(4, 'HD', 227);
		$this->setField(5, 'HD', 227);
		$this->setField(6, 'HD', 227);
		$this->setField(7, 'TS', 26, true);
		$this->setField(8, 'ST', 40);
		$this->setField(9, 'MSG', 15, true);
		$this->setFieldValue(10, $this->newUID());
		$this->setField(11, 'PT', 3, true);
		$this->setField(12, 'VID', 60, true);
		$this->setField(13, 'NM', 15);
		$this->setField(14, 'ST', 180);
		$this->setField(15, 'ID', 2);
		/**
		 * MSH-16 Application Acknowledgment Type
		 *
		 * AL Always
		 * NE Never
		 * ER Error/reject conditions only
		 * SU Successful completion only
		 */
		$this->setField(16, 'ID', 2);
		/**
		 * MSH-17 Country Code
		 * use 3-character (alphabetic) form of ISO 3166
		 */
		$this->setField(17, 'ID', 3);
		$this->setField(18, 'ID', 16, false, true);
		$this->setField(19, 'CE', 250);
		$this->setField(20, 'ID', 20);
		$this->setField(21, 'EI', 427, false, true);

	}
}