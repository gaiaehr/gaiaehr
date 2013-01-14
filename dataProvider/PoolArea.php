<?php
/*
 GaiaEHR (Electronic Health Records)
 PoolArea.php
 Pool Area dataProvider
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
include_once ($_SESSION['root'] . '/dataProvider/Services.php');
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/classes/Time.php');
class PoolArea
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
	/**
	 * @var
	 */
	private $acl;

	function __construct()
	{
		$this->db       = new dbHelper();
		$this->user     = new User();
		$this->patient  = new Patient();
		$this->services = new Services();
		return;
	}

	public function getPatientsArrivalLog(stdClass $params)
	{
		$visits = array();
		foreach($this->getPatientParentPools() AS $visit){
			$id = $visit['id'];
			$this->db->setSQL("SELECT pp.id, pa.title AS area, pp.time_out, pp.eid
								 FROM patient_pools AS pp
						    LEFT JOIN pool_areas AS pa ON pp.area_id = pa.id
							    WHERE pp.parent_id = $id
							 ORDER BY pp.id DESC");
			$foo                 = $this->db->fetchRecord(PDO::FETCH_ASSOC);
			$visit['area']       = $foo['area'];
			$visit['area_id']    = $foo['id'];
			$visit['name']       = ($foo['eid'] != null ? '*' : '') . $this->patient->getPatientFullNameByPid($visit['pid']);
			$visit['warning']    = $this->patient->getPatientArrivalLogWarningByPid($visit['pid']);
			$visit['warningMsg'] = ($visit['warning'] ? 'Patient \'Sex\' or \'Date Of Birth\' not set' : '');
			if($foo['time_out'] == null){
				$visits[] = $visit;
			}
		}
		return $visits;
	}

	private function getPatientParentPools()
	{
		$this->db->setSQL("SELECT id, time_in AS time, pid
							 FROM patient_pools
						    WHERE id = parent_id
						 ORDER BY time_in ASC, priority DESC
						 LIMIT 500");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	private function getParentPoolId($id)
	{
		$this->db->setSQL("SELECT parent_id
							 FROM patient_pools
						    WHERE id = $id");
		$foo = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $foo['parent_id'];
	}

	public function addPatientArrivalLog(stdClass $params)
	{
		if($params->isNew){
			$patient         = $this->patient->createNewPatientOnlyName($params->name);
			$params->pid     = $patient['patient']['pid'];
			$params->name    = $patient['patient']['fullname'];
			$params->area    = 'Check In';
			$params->area_id = 1;
			$params->new     = true;
			$params->warning = true;
			$this->checkInPatient($params);
		} else {
			$this->checkInPatient($params);
		}
		return $params;
	}

	public function updatePatientArrivalLog(stdClass $params)
	{

	}

	public function removePatientArrivalLog(stdClass $params)
	{
		$this->db->setSQL($this->db->sqlBind(array('time_out' => Time::getLocalTime()), 'patient_pools', 'U', array('id' => $params->area_id)));
		$this->db->execLog();
		return array('success' => true);
	}

	public function sendPatientToPoolArea(stdClass $params)
	{
		$fo = $this->getCurrentPatientPoolAreaByPid($params->pid);
		/**
		 * If patient comes from another area check him/her out
		 */
		if(!empty($fo)){
			$data['time_out'] = Time::getLocalTime();
			$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'U', array('id' => $fo['id'])));
			$this->db->execLog();
		}
		$data             = array();
		$data['pid']      = $params->pid;
		$data['uid']      = $_SESSION['user']['id'];
		$data['time_in']  = Time::getLocalTime();
		$data['area_id']  = $params->sendTo;
		$data['priority'] = $params->priority;
		if(!empty($fo)){
			$data['parent_id'] = $this->getParentPoolId($fo['id']);
			$data['eid']       = $fo['eid'];
			$data['priority']  = $fo['priority'];
		}
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'I'));
		$this->db->execLog();
		if(empty($fo)){
			$data['parent_id'] = $this->db->lastInsertId;
			$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'U', array('id' => $this->db->lastInsertId)));
			$this->db->execLog();
		}

	}

	public function getPoolAreaPatients(stdClass $params)
	{
		return $this->getPatientsByPoolAreaId($params->area_id, 1);
	}

	public function getActivePoolAreas()
	{
		$this->db->setSQL("SELECT * FROM pool_areas	WHERE active = '1'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/******************************************************************************************************************/
	/******************************************************************************************************************/
	/******************************************************************************************************************/
	private function checkInPatient($params)
	{
		$data['pid']     = $params->pid;
		$data['uid']     = $_SESSION['user']['id'];
		$data['time_in'] = Time::getLocalTime();
		$data['area_id'] = 1;
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'I'));
		$this->db->execLog();
		$data              = array();
		$data['parent_id'] = $this->db->lastInsertId;
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'U', array('id' => $this->db->lastInsertId)));
		$this->db->execLog();
	}

	public function getCurrentPatientPoolAreaByPid($pid)
	{
		$this->db->setSQL("SELECT pp.*,
								  pa.title AS poolArea
							 FROM patient_pools AS pp
							 LEFT JOIN pool_areas AS pa ON pa.id = pp.area_id
							WHERE pp.pid = $pid
							  AND pp.time_out IS NULL
						 ORDER BY pp.id DESC");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

	public function updateCurrentPatientPoolAreaByPid(array $data, $pid)
	{
		$area = $this->getCurrentPatientPoolAreaByPid($pid);
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'U', array('id' => $area['id'])));
		$this->db->execLog();
		return;
	}

	private function getPatientsByPoolAreaId($area_id, $in_queue)
	{
		$this->db->setSQL("SELECT pp.*
							 FROM patient_pools AS pp
							WHERE pp.area_id = '$area_id'
							  AND pp.time_out IS NULL
							  AND pp.in_queue = '$in_queue'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $patient){
			$patient['name'] = ($patient['eid'] != null ? '*' : '') . $this->patient->getPatientFullNameByPid($patient['pid']);
			$records[]       = $patient;
		}
		return $records;
	}

	/**
	 * Form now this is just getting the latest open encounter for all the patients.
	 *
	 * @param $params
	 * @return array
	 */
	public function getPatientsByPoolAreaAccess($params)
	{
		$uid = (is_object($params)) ? $params->uid : $params;
		$this->acl = new ACL($uid);
		$patients = array();
		if($this->acl->hasPermission('use_pool_areas')){
			if($this->acl->hasPermission('access_poolcheckin')){
				foreach($this->getPatientsByPoolAreaId(1, 1) as $p){
					$p['shortName']   = Person::ellipsis($p['name'], 20);
					$p['poolArea']    = 'Check In';
					$p['photoSrc']    = $this->patient->getPatientPhotoSrcIdByPid($p['pid']);
					$p['floorPlanId'] = $this->getFloorPlanIdByPoolAreaId(1);
					$z                = $this->getPatientCurrentZoneInfoByPid($p['pid']);
					$patients[]       = (empty($z)) ? $p : array_merge($p, $z);
				}
			}
			if($this->acl->hasPermission('access_pooltriage')){
				foreach($this->getPatientsByPoolAreaId(2, 1) as $p){
					$p['shortName']   = Person::ellipsis($p['name'], 20);
					$p['poolArea']    = 'Triage';
					$p['photoSrc']    = $this->patient->getPatientPhotoSrcIdByPid($p['pid']);
					$p['floorPlanId'] = $this->getFloorPlanIdByPoolAreaId(2);
					$z                = $this->getPatientCurrentZoneInfoByPid($p['pid']);
					$patients[]       = (empty($z)) ? $p : array_merge($p, $z);
				}
			}
			if($this->acl->hasPermission('access_poolphysician')){
				foreach($this->getPatientsByPoolAreaId(3, 1) as $p){
					$p['shortName']   = Person::ellipsis($p['name'], 20);
					$p['poolArea']    = 'Physician';
					$p['photoSrc']    = $this->patient->getPatientPhotoSrcIdByPid($p['pid']);
					$p['floorPlanId'] = $this->getFloorPlanIdByPoolAreaId(3);
					$z                = $this->getPatientCurrentZoneInfoByPid($p['pid']);
					$patients[]       = (empty($z)) ? $p : array_merge($p, $z);
				}
			}
			if($this->acl->hasPermission('access_poolcheckout')){
				foreach($this->getPatientsByPoolAreaId(4, 1) as $p){
					$p['shortName']   = Person::ellipsis($p['name'], 20);
					$p['poolArea']    = 'Check Out';
					$p['photoSrc']    = $this->patient->getPatientPhotoSrcIdByPid($p['pid']);
					$p['floorPlanId'] = $this->getFloorPlanIdByPoolAreaId(4);
					$z                = $this->getPatientCurrentZoneInfoByPid($p['pid']);
					$patients[]       = (empty($z)) ? $p : array_merge($p, $z);
				}
			}
		}
		$patients = array_slice($patients, 0, 6);
		return $patients;
	}

	public function getAreaTitleById($id)
	{
		$this->db->setSQL("SELECT title FROM pool_areas WHERE id = $id");
		$area = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $area['title'];
	}

	public function getFloorPlanIdByPoolAreaId($poolAreaId)
	{
		$this->db->setSQL("SELECT floor_plan_id FROM pool_areas WHERE id = $poolAreaId");
		$area = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $area['floor_plan_id'];
	}

	public function getPatientCurrentZoneInfoByPid($pid)
	{
		$this->db->setSQL("SELECT id AS patientZoneId,
								  zone_id AS zoneId,
								  time_in AS zoneTimeIn
		                     FROM patient_zone
		                    WHERE pid = '$pid' AND time_out IS NULL
		                    ORDER BY id DESC");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

}

//$e = new PoolArea();
//echo '<pre>';
//$params           = new stdClass();
//$params->pid      = 1;
//$params->priority = 'Immediate';
//$params->sendTo   = 3;
//print_r($e->sendPatientToPoolArea($params));
//print '<br><br>Session ----->>> <br><br>';
//print_r($_SESSION);
