<?php
/*
 GaiaEHR (Electronic Health Records)
 Medications.php
 Medications dataProvider
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

class Medications
{
	private $db;

	function __construct()
	{
		$this -> db = new dbHelper();
		return;

	}

	public function getMedications(stdClass $params)
	{
		$this -> db -> setSQL("SELECT *
                           FROM medications
                          WHERE (PRODUCTNDC LIKE '%$params->query%'
                             OR PROPRIETARYNAME LIKE '%$params->query%'
                             OR NONPROPRIETARYNAME LIKE '$params->query%')
                       ORDER BY PRODUCTNDC ASC");
		$records = $this -> db -> fetchRecords(PDO::FETCH_CLASS);
		$totals = count($records);
		$records = array_slice($records, $params -> start, $params -> limit);
		return array(
			'totals' => $totals,
			'rows' => $records
		);

	}

	public function addMedications(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$sql = $this -> db -> sqlBind($data, "medications", "I");
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		$params -> id = $this -> db -> lastInsertId;
		return array(
			'totals' => 1,
			'rows' => $params
		);
	}

	public function updateMedications(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$sql = $this -> db -> sqlBind($data, "medications", "U", "id='$params->id'");
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		return array(
			'totals' => 1,
			'rows' => $params
		);

	}

	public function removeMedications(stdClass $params)
	{
		$this -> db -> setSQL("DELETE FROM medications WHERE id ='$params->id'");
		$this -> db -> execLog();
		return array(
			'totals' => 1,
			'rows' => $params
		);
	}

	public function getMedicationNameById($id)
	{
		$this -> db -> setSQL("SELECT PROPRIETARYNAME FROM medications WHERE id='$id'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
	}

}

//$e = new Medications();
//echo '<pre>';
//	$here=$e->getMedicationsById(1);
//print_r($here['PROPRIETARYNAME']);
