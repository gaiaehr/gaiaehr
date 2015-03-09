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

include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/ACL.php');

class PoolArea {

	/**
	 * @var PDO
	 */
	private $conn;

	/**
	 * @var Patient
	 */
	private $patient;

	/**
	 * @var
	 */
	private $acl;

	/**
	 * @var MatchaCUP
	 */
	private $pa;

	/**
	 * @var MatchaCUP
	 */
	private $pp;

	function __construct(){
		$this->conn = \Matcha::getConn();
	}


	private function setPatient(){
		if(!isset($this->patient)){
			$this->patient = new Patient();
		}
	}

	private function setPaModel(){
		if(!isset($this->pa)){
			$this->pa = MatchaModel::setSenchaModel('App.model.areas.PoolArea');
		}
	}

	private function setPpModel(){
		if(!isset($this->pp)){
			$this->pp = MatchaModel::setSenchaModel('App.model.areas.PatientPool');
		}
	}

	public function getPatientsArrivalLog(stdClass $params) {
		$this->setPatient();
		$this->setPaModel();
		$visits = array();
		foreach($this->getPatientParentPools() AS $visit){
			$id = $visit['id'];
			$foo = $this->pa->sql("SELECT pp.id, pa.title AS area, pp.time_out, pp.eid
								 FROM patient_pools AS pp
						    LEFT JOIN pool_areas AS pa ON pp.area_id = pa.id
							    WHERE pp.parent_id = '$id'
							 ORDER BY pp.id DESC")->one();
			$visit['area'] = $foo['area'];
			$visit['area_id'] = $foo['id'];
			$visit['name'] = ($foo['eid'] != null ? '*' : '') . $this->patient->getPatientFullNameByPid($visit['pid']);
			$visit['warning'] = $this->patient->getPatientArrivalLogWarningByPid($visit['pid']);
			$visit['warningMsg'] = ($visit['warning'] ? 'Patient "Sex" or "Date Of Birth" not set' : '');
			if($foo['time_out'] == null){
				$visits[] = $visit;
			}
		}
		return $visits;
	}

	private function getPatientParentPools() {
		$this->setPaModel();
		$sql = "SELECT pp.id, pp.time_in AS time, pp.pid
				 FROM patient_pools AS pp
            LEFT JOIN pool_areas AS pa ON pp.area_id = pa.id
			    WHERE pp.id = pp.parent_id
			      AND pa.facility_id = '{$_SESSION['user']['facility']}'
			 ORDER BY pp.time_in ASC, pp.priority DESC
			    LIMIT 500";
		$parentPools = $this->pa->sql($sql)->all();
		return $parentPools;
	}

	private function getParentPoolId($id) {
		$this->setPaModel();
		$foo = $this->pa->sql("SELECT parent_id FROM patient_pools WHERE id = '$id'")->one();
		return $foo !== false ? $foo['parent_id'] : 0;
	}

	public function addPatientArrivalLog(stdClass $params) {
		$this->setPatient();
		if($params->isNew){
			$patient = $this->patient->createNewPatientOnlyName($params->name);
			$params->pid = $patient['patient']['pid'];
			$params->name = $patient['patient']['fullname'];
			$params->area = 'Check In';
			$params->area_id = 1;
			$params->new = true;
			$params->warning = true;
			$this->checkInPatient($params);
		} else {
			$this->checkInPatient($params);
		}
		return $params;
	}

	public function updatePatientArrivalLog(stdClass $params) {
	}

	public function removePatientArrivalLog(stdClass $params) {
		$this->setPpModel();
		$record = new stdClass();
		$record->id = $params->area_id;
		$record->time_out = date('Y-m-d H:i:s');
		$this->pp->save($record);
		unset($record);
		return array('success' => true);
	}

	public function sendPatientToPoolArea(stdClass $params) {
		$this->setPpModel();
		$prevArea = $this->getCurrentPatientPoolAreaByPid($params->pid);

		/**
		 * If patient comes from another area check him/her out
		 */
		if(!empty($prevArea)){
			$record = new stdClass();
			$record->id = $prevArea['id'];
			$record->time_out = date('Y-m-d H:i:s');
			$this->pp->save($record);

			// check out patient from any patient zone
			$sql = "UPDATE `patient_zone` SET `time_out` = '{$record->time_out}' WHERE `pid` = {$prevArea['pid']} AND `time_out` IS NULL";
			$sth = $this->conn->prepare($sql);
			$sth->execute();
			unset($record);
		}
		$record = new stdClass();
		$record->pid  = $params->pid;
		$record->uid  = $_SESSION['user']['id'];
		$record->time_in  = date('Y-m-d H:i:s');
		$record->area_id  = $params->sendTo;
		$record->in_queue  = 1;
		$record->priority  = (isset($params->priority) ? $params->priority : '');
		if(!empty($prevArea)){
			$record->parent_id  = $this->getParentPoolId($prevArea['id']);
			$record->eid  = $prevArea['eid'];
			$record->priority  = $prevArea['priority'];
		}
		$record = (object) $this->pp->save($record);

		if(empty($prevArea)){
			$record->parent_id  = $record->id;
			Matcha::pauseLog(true); // no need to log this
			$record = $this->pp->save($record);
			Matcha::pauseLog(false);
		}

		return array('record' => $record, 'floor_plan_id' => $this->getFloorPlanIdByPoolAreaId($record->area_id));

	}

	public function getPoolAreaPatients(stdClass $params) {
		return $this->getPatientsByPoolAreaId($params->area_id, 1);
	}

	public function getFacilityActivePoolAreas() {
		$this->setPaModel();
		return $this->pa->sql("SELECT * FROM pool_areas	WHERE facility_id = '{$_SESSION['user']['facility']}' AND active = '1'")->all();
	}

	public function getActivePoolAreas() {
		$this->setPaModel();
		return $this->pa->sql("SELECT * FROM pool_areas	WHERE active = '1'")->all();
	}

	/**
	 * This this return an arrays of Areas
	 * where array index equal the area ID
	 */
	public function getAreasArray(){
		$areas = array();
		foreach($this->getActivePoolAreas() as $area){
			$areas[$area['id']] = $area;
		}
		return $areas;
	}

	/******************************************************************************************************************/
	/******************************************************************************************************************/
	/******************************************************************************************************************/
	private function checkInPatient($params) {
		$this->setPpModel();
		$record = new stdClass();
		$record->pid= $params->pid;
		$record->uid= $_SESSION['user']['id'];
		$record->time_in = date('Y-m-d H:i:s');
		$record->area_id = 1;
		$record->in_queue = 1;
		$this->pp->save($record);

		$record->parent_id = $record->id;
		Matcha::pauseLog(true);
		$this->pp->save($record);
		Matcha::pauseLog(false);
	}

	public function getCurrentPatientPoolAreaByPid($pid) {
		$this->setPpModel();
		$record = $this->pp->sql("SELECT pp.*, pa.title AS poolArea
								 	FROM patient_pools AS pp
							   LEFT JOIN pool_areas AS pa ON pa.id = pp.area_id
								   WHERE pp.pid = '$pid'
								  	 AND pp.time_out IS NULL
							 	ORDER BY pp.id DESC")->one();
		return $record;
	}

	public function updateCurrentPatientPoolAreaByPid($data, $pid) {
		$this->setPpModel();
		$area = $this->getCurrentPatientPoolAreaByPid($pid);
		$data['id'] = $area['id'];
		$this->pp->save((object) $data);
		return;
	}

	private function getPatientsByPoolAreaId($area_id, $in_queue) {
		$this->setPatient();
		$this->setPpModel();
		$patients = $this->pp->sql("SELECT pp.*, p.fname, p.lname, p.mname
									  FROM `patient_pools` AS pp
								 LEFT JOIN `patient` AS p ON pp.pid = p.pid
								 	 WHERE pp.area_id = '$area_id'
									   AND pp.time_out IS NULL
									   AND pp.in_queue = '$in_queue'
									   ORDER BY pp.time_in")->all();
		foreach($patients as &$patient){
			if(isset($patient['pid'])){
				$patient['name'] = ($patient['eid'] != null ? '*' : '') . Person::fullname($patient['fname'], $patient['mname'], $patient['lname']);
			}
		}
		unset($patient);
		return $patients;
	}

	/**
	 * Form now this is just getting the latest open encounter for all the patients.
	 *
	 * @param $params
	 *
	 * @return array
	 */
	public function getPatientsByPoolAreaAccess($params) {
		if(is_numeric($params)){
			$uid = $params;
		} elseif(!is_numeric($params) && isset($params->eid)) {
			$uid = $params->eid;
		} elseif(!isset($_SESSION['user']['id'])){
			return array();
		} else {
			$uid = $_SESSION['user']['id'];
		}

		$this->acl = new ACL($uid);
		$pools = array();

		if($this->acl->hasPermission('use_pool_areas')){
			$this->setPatient();

			$activeAreas = $this->getFacilityActivePoolAreas();
			$areas = array();

			foreach($activeAreas as $activeArea){
				if(($activeArea['id'] == 1 && $this->acl->hasPermission('access_poolcheckin')) ||
					($activeArea['id'] == 2 && $this->acl->hasPermission('access_pooltriage')) ||
					($activeArea['id'] == 3 && $this->acl->hasPermission('access_poolphysician')) ||
					($activeArea['id'] == 4 && $this->acl->hasPermission('access_poolcheckout'))
				){
					$areas[] = 'pp.area_id = \'' . $activeArea['id'] . '\'';
				}
			}

			$whereAreas = '(' . implode(' OR ', $areas) . ')';

			$sql = "SELECT pp.*, p.fname, p.lname, p.mname, pa.title
					  FROM `patient_pools` AS pp
				 LEFT JOIN `patient` AS p ON pp.pid = p.pid
				 LEFT JOIN `pool_areas` AS pa ON pp.area_id = pa.id
				     WHERE $whereAreas
					   AND pp.time_out IS NULL
					   AND pp.in_queue = '1'
			      ORDER BY pp.time_in
			         LIMIT 25";

			$patientPools = $this->pa->sql($sql)->all();

			$pools = [];
			foreach($patientPools AS $patientPool){
				$patientPool['name'] = ($patientPool['eid'] != null ? '*' : '') . Person::fullname($patientPool['fname'], $patientPool['mname'], $patientPool['lname']);
				$patientPool['shortName'] = Person::ellipsis($patientPool['name'], 15);
				$patientPool['poolArea'] = $patientPool['title'];
				$patientPool['patient'] = $this->patient->getPatientDemographicDataByPid($patientPool['pid']);
				$patientPool['floorPlanId'] = $this->getFloorPlanIdByPoolAreaId($patientPool['area_id']);
				$z = $this->getPatientCurrentZoneInfoByPid($patientPool['pid']);
				$pools[] = (empty($z)) ? $patientPool : array_merge($patientPool, $z);
			}

			$pools = array_slice($pools, 0, 25);
		}

		return $pools;
	}

	public function getAreaTitleById($id) {
		$this->setPaModel();
		$area = $this->pa->sql("SELECT title FROM pool_areas WHERE id = '{$id}'")->one();
		return $area['title'];
	}

	public function getFloorPlanIdByPoolAreaId($poolAreaId) {
		$this->setPaModel();
		$area = $this->pa->sql("SELECT floor_plan_id FROM pool_areas WHERE id = '{$poolAreaId}'")->one();
		return $area['floor_plan_id'];
	}

	public function getPatientCurrentZoneInfoByPid($pid) {
		$this->setPpModel();
		$zone = $this->pp->sql("SELECT id AS patientZoneId,
								  zone_id AS zoneId,
								  time_in AS zoneTimeIn
		                     FROM patient_zone
		                    WHERE pid = '$pid' AND time_out IS NULL
		                    ORDER BY id DESC")->one();
		return $zone;
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
