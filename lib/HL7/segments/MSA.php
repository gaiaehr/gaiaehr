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

class MSA extends Segments{

	function __destruct(){
		parent::__destruct();
	}

	function __construct($hl7){
		parent::__construct($hl7, 'MSA');

		/**
		 * MSA-1 Acknowledgment Code
		 * AA Original mode: Application Accept - Enhanced mode: Application acknowledgment: Accept
		 * AE Original mode: Application Error - Enhanced mode: Application acknowledgment: Error
		 * AR Original mode: Application Reject - Enhanced mode: Application acknowledgment: Reject CA Enhanced mode: Accept acknowledgment: Commit Accept
		 * CE Enhanced mode: Accept acknowledgment: Commit Error
		 * CR Enhanced mode: Accept acknowledgment: Commit Reject
		 */
		$this->setField(1, 'ID', 2, true);
		$this->setField(2, 'ST', 20, true);
		$this->setField(3, 'ST', 80);
		$this->setField(4, 'NM', 15);
		$this->setFieldValue(5, null); //The MSA-5 was deprecated as of v2.2 standard
		$this->setField(6, 'CE', 250);

	}
}