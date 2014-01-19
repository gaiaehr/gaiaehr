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
class DoctorsNotes {

	function __construct(){
		$this->db = new MatchaHelper();
		return;
	}

	public function addDoctorsNotes($params){
		$foo = array();
		$foo['uid'] = $_SESSION['user']['id'];
		$foo['pid'] = $params->pid;
		$foo['document_id'] = $params->document_id;
		$foo['doctors_notes'] = $params->DoctorsNote;
		$this->db->setSQL($this->db->sqlBind($foo, 'patient_doctors_notes', 'I'));
		$this->db->execLog();
	}

}
