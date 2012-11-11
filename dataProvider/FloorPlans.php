<?php
/*
 GaiaEHR (Electronic Health Records)
 Encounter.php
 Encounter dataProvider
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
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/ACL.php');
include_once ($_SESSION['root'] . '/dataProvider/PoolArea.php');
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/classes/Time.php');
class FloorPlans
{
	/**
	 * @var dbHelper
	 */
	private $db;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var Patient
	 */
	private $patient;
	/**
	 * @var Services
	 */
	private $services;

	private $pool;

	function __construct()
	{
		$this->db       = new dbHelper();
		$this->user     = new User();
		$this->acl      = new ACL();
		$this->patient  = new Patient();
		$this->services = new Services();
		$this->pool     = new PoolArea();
		return;
	}

	public function getFloorPlans()
	{
		$this->db->setSQL("SELECT * FROM floor_plans");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function createFloorPlan(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	public function updateFloorPlan(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans', 'U', array('id' => $params->id)));
		$this->db->execLog();
		return $params;
	}
	public function removeFloorPlan(stdClass $params)
	{
		$params->floorPlanId = $params->id;
		$this->removeFloorPlanZone($params);
		$this->db->setSQL("DELETE FROM floor_plans WHERE id = '$params->id'");
		$this->db->execLog();
		return $params;
	}

	public function getFloorPlanZones(stdClass $params)
	{
		return $this->getFloorPlanZonesByFloorPlanId($params->floor_plan_id);
	}

	public function createFloorPlanZone(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans_zones', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	public function updateFloorPlanZone($params)
	{
		if(is_array($params)){
			foreach($params AS $zone){
				$data = get_object_vars($zone);
				unset($data['id']);
				$this->db->setSQL($this->db->sqlBind($data, 'floor_plans_zones', 'U', array('id' => $params->id)));
				$this->db->execLog();
			}
		}else{
			$data = get_object_vars($params);
			unset($data['id']);
			$this->db->setSQL($this->db->sqlBind($data, 'floor_plans_zones', 'U', array('id' => $params->id)));
			$this->db->execLog();
		}
		return $params;
	}

	public function removeFloorPlanZone(stdClass $params)
	{
		if(isset($params->floorPlanId)){
			$this->db->setSQL("DELETE FROM floor_plans_zones WHERE floor_plan_id = '$params->floorPlanId'");
		}else{
			$this->db->setSQL("DELETE FROM floor_plans_zones WHERE id = '$params->id'");
		}
		$this->db->execLog();
		return $params;
	}

	//******************************************************************************************************************
	//******************************************************************************************************************
	public function setPatientToZone($params)
	{
		$params->uid     = $_SESSION['user']['id'];
		$params->time_in = Time::getLocalTime();
		$data            = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'patient_zone', 'I'));
		$this->db->execLog();
		$params->patientZoneId = $this->db->lastInsertId;
		//$params->patientSummary = $this->patient->ge
		return array(
			'success' => true, 'data' => $params
		);
	}

	public function unSetPatientZoneByPatientZoneId($PatientZoneId)
	{
		$data['time_out'] = Time::getLocalTime();
		$this->db->setSQL($this->db->sqlBind($data, 'patient_zone', 'U', array('id' => $PatientZoneId)));
		$this->db->execLog();
	}

	public function unSetPatientFromZoneByPid($pid)
	{
		return;

	}

	public function getPatientsZonesByFloorPlanId($FloorPlanId)
	{
		$zones = array();
		$this->db->setSQL("SELECT pz.id AS patientZoneId,
								  pz.pid,
								  pz.uid,
								  pz.zone_id AS zoneId,
								  time_in AS zoneTimerIn,
								  fpz.floor_plan_id AS floorPlanId
							 FROM patient_zone AS pz
						LEFT JOIN floor_plans_zones AS fpz ON pz.zone_id = fpz.id
							WHERE fpz.floor_plan_id = $FloorPlanId AND pz.time_out IS NULL");
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $zone){
			$zone['patient']  = $this->patient->getPatientDemographicDataByPid($zone['pid']);
			$zone['warning']  = $this->patient->getPatientArrivalLogWarningByPid($zone['pid']);
			$pool             = $this->pool->getCurrentPatientPoolAreaByPid($zone['pid']);
			$zone['poolArea'] = $pool['poolArea'];
			$zone['priority'] = $pool['priority'];
			$zone['eid']      = $pool['eid'];
			$zones[]          = $zone;
		}
		return $zones;
	}

	public function getZonePatientSummaryByPid(){

	}
	//******************************************************************************************************************
	// private functions
	//******************************************************************************************************************
	private function getFloorPlanZonesByFloorPlanId($floor_plan_id)
	{
		$this->db->setSQL("SELECT * FROM floor_plans_zones WHERE floor_plan_id = '$floor_plan_id'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

}

//$e = new FloorPlans();
//echo '<pre>';
//print_r($e->getPatientsZonesByFloorPlanId(1));
//print '<br><br>Session ----->>> <br><br>';
//print_r($_SESSION);
