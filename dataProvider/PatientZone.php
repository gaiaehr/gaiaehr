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

include_once(dirname(__FILE__) . '/Patient.php');
include_once(dirname(__FILE__) . '/PoolArea.php');
class PatientZone {

	private $pz;

	function __construct(){
		$this->pz = MatchaModel::setSenchaModel('App.model.patient.PatientZone');
	}

	public function addPatientToZone($params){
		$params->uid = $_SESSION['user']['id'];
		$params->time_in = date('Y-m-d H:i:s');
		return array(
			'success' => true,
			'data' => $this->pz->save($params)
		);
	}

	public function removePatientFromZone($params){
		$data['time_out'] = date('Y-m-d H:i:s');
		return array(
			'success' => true,
			'data' => $this->pz->save($params)
		);
	}

	public function removePatientFromZoneByPid($pid){
		return;
	}

	public function getPatientsZonesByFloorPlanId($FloorPlanId){
		$Patient = new Patient();
		$Pool = new PoolArea();
		$zones = $this->pz->sql("SELECT pz.id AS patientZoneId,
								  pz.pid,
								  pz.uid,
								  pz.zone_id AS zoneId,
								  time_in AS zoneTimerIn,
								  fpz.floor_plan_id AS floorPlanId
							 FROM patient_zone AS pz
						LEFT JOIN floor_plans_zones AS fpz ON pz.zone_id = fpz.id
							WHERE fpz.floor_plan_id = $FloorPlanId AND pz.time_out IS NULL")->all();
		foreach($zones as $i => $zone){
			$zone['patient'] = $Patient->getPatientDemographicDataByPid($zone['pid']);
			$zone['name'] = $Patient->getPatientFullName();
			$zone['warning'] = $Patient->getPatientArrivalLogWarningByPid($zone['pid']);
			$pool = $Pool->getCurrentPatientPoolAreaByPid($zone['pid']);
			$zone['poolArea'] = $pool['poolArea'];
			$zone['priority'] = $pool['priority'];
			$zone['eid'] = $pool['eid'];
			$zones[$i] = $zone;
		}
		unset($Patient, $Pool);
		return $zones;
	}

	public function getZonePatientSummaryByPid(){
		// not used
	}

}

//$e = new FloorPlans();
//echo '<pre>';
//print_r($e->getPatientsZonesByFloorPlanId(1));
//print '<br><br>Session ----->>> <br><br>';
//print_r($_SESSION);
