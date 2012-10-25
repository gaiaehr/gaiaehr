<?php
/*
 GaiaEHR (Electronic Health Records)
 Laboratories.php
 Laboratories dataProvider
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
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
/**
 * Created by JetBrains PhpStorm.
 * User: Plushy
 * Date: 8/19/12
 * Time: 10:12 AM
 * To change this template use File | Settings | File Templates.
 */

class Laboratories
{
	private $db;

	function __construct()
	{
		$this -> db = new dbHelper();
		return;
	}

	public function getAllLabs(stdClass $params)
	{
		$sort = isset($params -> sort) ? $params -> sort[0] -> property . ' ' . $params -> sort[0] -> direction : 'sequence ASC';
		$this -> db -> setSQL("SELECT lp.id,
								  lp.parent_id,
								  lp.parent_loinc,
								  lp.sequence,
								  lp.default_unit,
								  loinc.SHORTNAME AS code_text_short,
								  lp.parent_name AS code_text,
								  lp.loinc_number AS code,
								  lp.active
						     FROM labs_panels AS lp
						     LEFT JOIN labs_loinc AS loinc on loinc.LOINC_NUM = lp.parent_loinc
						    WHERE parent_name LIKE '%$params->query%'
					          AND id = parent_id
					     ORDER BY $sort");
		return $this -> db -> fetchRecords(PDO::FETCH_CLASS);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getLabObservations(stdClass $params)
	{
		return $this -> getLabObservationFieldsByParentId($params -> selectedId);
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateLabObservation(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		//		foreach($data as $key => $val){
		//			if($val == null || $val == '') unset($data[$key]);
		//		}
		$this -> db -> setSQL($this -> db -> sqlBind($data, 'labs_panels', 'U', "id='$params->id'"));
		$this -> db -> execLog();
		return $params;
	}

	public function getActiveLaboratoryTypes()
	{
		$records = array();
		$this -> db -> setSQL("SELECT id, code_text_short, parent_name, loinc_name
						     FROM labs_panels
						    WHERE id = parent_id
						      AND active = '1'
					     ORDER BY parent_name ASC");
		$rows = $this -> db -> fetchRecords(PDO::FETCH_CLASS);
		foreach ($rows as $row)
		{
			$row -> label = ($row -> code_text_short == '' || $row -> code_text_short == null) ? $row -> parent_name : $row -> code_text_short;
			$row -> fields = $this -> getLabObservationFieldsByParentId($row -> id);
			$records[] = $row;
		}
		return $records;
	}

	public function getLabObservationFieldsByParentId($id)
	{
		$records = array();
		$this -> db -> setSQL("SELECT lp.*,
								  loinc.SUBMITTED_UNITS
							 FROM labs_panels AS lp
						LEFT JOIN labs_loinc AS loinc ON lp.loinc_number = loinc.LOINC_NUM
							WHERE parent_id = '$id'
							  AND parent_id != id
						ORDER BY sequence");
		foreach ($this->db->fetchRecords(PDO::FETCH_CLASS) as $row)
		{
			$row -> default_unit = ($row -> default_unit == null || $row -> default_unit == '') ? $row -> SUBMITTED_UNITS : $row -> default_unit;
			$records[] = $row;
		}
		return $records;
	}

}
