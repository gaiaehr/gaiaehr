<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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

include_once (str_replace('\\', '/',__DIR__).'/Segments.php');
class ERR extends Segments{

	function __construct($hl7){
		parent::__construct($hl7);
		$this->rawSeg = array();
		$this->rawSeg[0] = 'ERR';                   // ERR Message Header Segment
		$this->rawSeg[1] = $this->getType('ELD');
		$this->rawSeg[2] = $this->getType('ERL');
		/**
		 * 0   - Message accepted                  - Used for systems that must always return a status code. Success. Optional, as the AA conveys success.
		 * 100 - Segment sequence error            - Error: The message segments were not in the proper order, or required segments are missing.
		 * 101 - Required field missing            - Error: A required field is missing from a segment
		 * 102 - Data type error                   - Error: The field contained data of the wrong data type, e.g. an NM field contained "FOO".
		 * 103 - Table value not found             - Error: A field of data type ID or IS was compared against the corresponding table, and no match was found.
		 * 200 - Unsupported message type          - Rejection: The Message Type is not supported.
		 * 201 - Unsupported supported. event code - Rejection: The Event Code is not
		 * 202 - Unsupported processing id         - Rejection: The Processing ID is not supported.
		 * 203 - Unsupported version id            - Rejection: The Version ID is not supported.
		 * 204 - Unknown key identifier            - Rejection: The ID of the patient, order, etc., was not found. Used for transactions other than additions, e.g. transfer of a non-existent patient.
		 * 205 - Duplicate key identifier          - Rejection: The ID of the patient, order, etc., already exists. Used in response to addition transactions (Admit, New Order, etc.).
		 * 206 - Application record locked         - Rejection: The transaction could not be performed at the application store level, e.g., database locked.
		 * 207 - Application Internal Error        - Rejection: A catchall for internal errors not explicitly covered by other codes.
		 */
		$this->rawSeg[3] = $this->getType('CWE');
		/**
		 * W - Warning      - Transaction  successful, but there may issues
		 * I - Information  - Transaction  successful, but includes information e.g., inform patient
		 * E - Error        - Transaction was unsuccessful
		 */
		$this->rawSeg[4] = $this->getType('ID');
		$this->rawSeg[5] = $this->getType('CWE');
		$this->rawSeg[6] = $this->getType('ST');
		$this->rawSeg[7] = $this->getType('TX');
		$this->rawSeg[8] = $this->getType('TX');
		$this->rawSeg[9] = $this->getType('IS');
		$this->rawSeg[10] = $this->getType('CWE');
		$this->rawSeg[11] = $this->getType('CWE');
		$this->rawSeg[12] = $this->getType('XTN');

	}
}