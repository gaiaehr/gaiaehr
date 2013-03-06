<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, inc.

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
include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');
class Facilities
{
	/**
	 * @var MatchaHelper
	 */
	private $db;

    private $Facilities = null;

	/**
	 * Creates the MatchaHelper instance
	 */
	function __construct()
	{
		$this -> db = new MatchaHelper();
        $this->Facilities = MatchaModel::setSenchaModel('App.model.administration.Facility');
		return;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getFacilities(stdClass $params)
	{
		$rows = array();
		foreach ($this->Facilities->load( array('active'=>($params->active?$params->active:1)) )->all() as $row)
		{
			if (strlen($row['pos_code']) <= 1) $row['pos_code'] = '0' . $row['pos_code'];
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
		foreach ($data AS $key => $val) if ($val == '') unset($data[$key]);
        $params->id = $this->Facilities->save($data)['pid'];
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateFacility(stdClass $params)
	{
		$data = get_object_vars($params);
        $params->id = $this->Facilities->save($data)['pid'];
		return $params;
	}

	/**
	 * Not in use. For Now you can only set the Facility "inactive"
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
        $resultRecord = $this->Facilities->load( array('id'=>$fid), array('name', 'phone', 'street', 'city', 'state', 'postal_code') )->one();
		return 'Facility: ' . $resultRecord['name'] . ' ' . $resultRecord['phone'] . ' ' . $resultRecord['street'] . ' ' . $resultRecord['city'] . ' ' . $resultRecord['state'] . ' ' . $resultRecord['postal_code'];
	}

	public function getActiveFacilities()
	{
		return $this->Facilities->load( array('active'=>'1') )->one();
	}

	public function getActiveFacilitiesById($facilityId)
	{
        return $this->Facilities->load( array('active'=>'1', 'id'=>$facilityId) )->one();
	}

	public function getBillingFacilities()
	{
        return $this->Facilities->load( array('active'=>'1', 'billing_location'=>'1') )->one();
	}

}
