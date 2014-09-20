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
		$parentPools = $this->pa->sql("SELECT pp.id, pp.time_in AS time, pp.pid
										 FROM patient_pools AS pp
			                        LEFT JOIN pool_areas AS pa ON pp.area_id = pa.id
									    WHERE pp.id = pp.parent_id
									      AND pa.facility_id = '{$_SESSION['user']['facility']}'
									 ORDER BY pp.time_in ASC, pp.priority DESC
									    LIMIT 500")->all();
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
		$fo = $this->getCurrentPatientPoolAreaByPid($params->pid);

		/**
		 * If patient comes from another area check him/her out
		 */
		if(!empty($fo)){
			$record = new stdClass();
			$record->id = $fo['id'];
			$record->time_out = date('Y-m-d H:i:s');
			$this->pp->save($record);
			unset($record);
		}
		$record = new stdClass();
		$record->pid  = $params->pid;
		$record->uid  = $_SESSION['user']['id'];
		$record->time_in  = date('Y-m-d H:i:s');
		$record->area_id  = $params->sendTo;
		$record->in_queue  = 1;
		$record->priority  = (isset($params->priority) ? $params->priority : '');
		if(!empty($fo)){
			$record->parent_id  = $this->getParentPoolId($fo['id']);
			$record->eid  = $fo['eid'];
			$record->priority  = $fo['priority'];
		}
		$record = (object) $this->pp->save($record);

		if(empty($fo)){
			$record->parent_id  = $record->id;
			Matcha::pauseLog(true); // no need to log this
			$this->pp->save($record);
			Matcha::pauseLog(false);
		}

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
		$patients = $this->pp->sql("SELECT pp.*
							 FROM patient_pools AS pp
							WHERE pp.area_id = '$area_id'
							  AND pp.time_out IS NULL
							  AND pp.in_queue = '$in_queue'")->all();
		$records = array();
		foreach($patients as $patient){
			if(isset($patient['pid'])){
				$patient['name'] = ($patient['eid'] != null ? '*' : '') . $this->patient->getPatientFullNameByPid($patient['pid']);
				$records[] = $patient;
			}
		}
		return $records;
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
		} else {
			$uid = $_SESSION['user']['id'];
		}

		$this->acl = new ACL($uid);
		$pools = array();

		if($this->acl->hasPermission('use_pool_areas')){
			$this->setPatient();

			foreach($this->getFacilityActivePoolAreas() AS $area){
				if(($this->acl->hasPermission('access_poolcheckin') && $area['id'] == 1) || ($this->acl->hasPermission('access_pooltriage') && $area['id'] == 2) || ($this->acl->hasPermission('access_poolphysician') && $area['id'] == 3) || ($this->acl->hasPermission('access_poolcheckout') && $area['id'] == 4)
				){
					foreach($this->getPatientsByPoolAreaId($area['id'], 1) as $p){
						$p['shortName'] = Person::ellipsis($p['name'], 16);
						$p['poolArea'] = $area['title'];
						$p['patient'] = $this->patient->getPatientDemographicDataByPid($p['pid']);
						$p['floorPlanId'] = $this->getFloorPlanIdByPoolAreaId($area['id']);
						$z = $this->getPatientCurrentZoneInfoByPid($p['pid']);
						$pools[] = (empty($z)) ? $p : array_merge($p, $z);
					}
				}
			}
			$pools = array_slice($pools, 0, 6);
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
