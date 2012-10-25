<?php
  /*
 GaiaEHR (Electronic Health Records)
 Facilities.php
 Facilities dataProvider
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
$_SESSION['site']['flops'] = 0;
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class Facilities
{
	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * Creates the dbHelper instance
	 */
	function __construct()
	{
		$this -> db = new dbHelper();
		return;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getFacilities(stdClass $params)
	{

		if (isset($params -> active))
		{
			$wherex = 'active = ' . $params -> active;
		}
		else
		{
			$wherex = 'active = 1';
		}
		if (isset($params -> sort))
		{
			$orderx = $params -> sort[0] -> property . ' ' . $params -> sort[0] -> direction;
		}
		else
		{
			$orderx = 'name';
		}
		$sql = "SELECT * FROM facility WHERE $wherex ORDER BY $orderx LIMIT $params->start,$params->limit";
		$this -> db -> setSQL($sql);
		$rows = array();
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{

			if (strlen($row['pos_code']) <= 1)
			{
				$row['pos_code'] = '0' . $row['pos_code'];
			}
			array_push($rows, $row);
		}

		return $rows;

	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addFacility(stdClass $params)
	{

		$data = get_object_vars($params);
		unset($data['id']);
		foreach ($data AS $key => $val)
		{
			if ($val == '')
				unset($data[$key]);
		}
		$sql = $this -> db -> sqlBind($data, 'facility', 'I');
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		$params -> id = $this -> db -> lastInsertId;
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateFacility(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$sql = $this -> db -> sqlBind($data, 'facility', 'U', array('id' => $params -> id));
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		return $params;
	}

	/**
	 * Not in used. For Now you can only set the Facility "inactive"
	 *
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function deleteFacility(stdClass $params)
	{
		$data['active'] = 0;
		unset($data['id']);
		$sql = $this -> db -> sqlBind($data, 'facility', 'U', array('id' => $params -> id));
		$this -> db -> setSQL($sql);
		$this -> db -> execLog();
		return $params;
	}

	public function getFacilityInfo($fid)
	{

		$this -> db -> setSQL("SELECT name, phone, street, city, state, postal_code
                        	 FROM facility
                            WHERE id = '$fid'");
		$i = $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
		$facilityInfo = 'Facility: ' . $i['name'] . ' ' . $i['phone'] . ' ' . $i['street'] . ' ' . $i['city'] . ' ' . $i['state'] . ' ' . $i['postal_code'];

		return $facilityInfo;
	}

	public function getActiveFacilities()
	{
		$this -> db -> setSQL("SELECT * FROM facility WHERE active = '1'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
	}

	public function getActiveFacilitiesById($facilityId)
	{
		$this -> db -> setSQL("SELECT * FROM facility WHERE active = '1' AND id='$facilityId'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
	}

	public function getBillingFacilities()
	{
		$this -> db -> setSQL("SELECT * FROM facility WHERE active = '1' AND billing_location = '1'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);

	}

}
