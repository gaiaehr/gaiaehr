<?php
/*
 GaiaEHR (Electronic Health Records)
 Prescriptions.php
 Precriptions dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!isset($_SESSION))
{
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/dataProvider/Facilities.php');
include_once ($_SESSION['root'] . '/dataProvider/Documents.php');
class Prescriptions
{

	function __construct()
	{
		$this -> db = new dbHelper();
		$this -> user = new User();
		$this -> patient = new Patient();
		$this -> services = new Services();
		$this -> facility = new Facilities();
		$this -> documents = new Documents();
		return;
	}

	public function addDocumentsPatientInfo($params)
	{
		$foo = array();
		$foo['pid'] = $_SESSION['patient']['pid'];
		$foo['uid'] = $_SESSION['user']['id'];
		$foo['created_date'] = date('Y-m-d H:i:s');
		$foo['document_id'] = $params -> document_id;
		$this -> db -> setSQL($this -> db -> sqlBind($foo, 'patient_prescriptions', 'I'));
		$this -> db -> execLog();
		$prescription_id = $this -> db -> lastInsertId;
		foreach ($params->medications as $med)
		{
			$foo = array();
			$foo['pid'] = $_SESSION['patient']['pid'];
			$foo['eid'] = $params -> eid;
			$foo['prescription_id'] = $prescription_id;
			$foo['medication'] = $med -> medication;
			$foo['medication_id'] = $med -> medication_id;
			$foo['route'] = $med -> route;
			$foo['dispense'] = $med -> dispense;
			$foo['dose'] = $med -> dose;
			$foo['dose_mg'] = $med -> dose_mg;
			$foo['prescription_often'] = $med -> prescription_often;
			$foo['prescription_when'] = $med -> prescription_when;
			$foo['refill'] = $med -> refill;
			$foo['take_pills'] = $med -> take_pills;
			$foo['type'] = $med -> type;
			$foo['begin_date'] = $med -> begin_date;
			$foo['end_date'] = $med -> end_date;
			$this -> db -> setSQL($this -> db -> sqlBind($foo, 'patient_medications', 'I'));
			$this -> db -> execLog();
		}
	}

}
