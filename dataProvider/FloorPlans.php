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
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/ACL.php');
include_once(ROOT . '/dataProvider/PoolArea.php');
include_once(ROOT . '/dataProvider/Services.php');
include_once(ROOT . '/classes/Time.php');

class FloorPlans {

	/**
	 * @var Patient
	 */
	private $patient;

	/**
	 * @var PoolArea
	 */
	private $pool;

	function __construct(){
		$this->fp = MatchaModel::setSenchaModel('App.model.administration.FloorPlans');
		$this->fpz = MatchaModel::setSenchaModel('App.model.administration.FloorPlanZones');

		$this->patient = new Patient();
		$this->pool = new PoolArea();
	}

	public function getFloorPlans(){
		return $this->fp->load()->all();
	}

	public function createFloorPlan($params){
		return $this->fp->save($params);
	}

	public function updateFloorPlan(stdClass $params){
		return $this->fp->save($params);
	}

	public function removeFloorPlan(stdClass $params){
		$params->floorPlanId = $params->id;
		$this->removeFloorPlanZone($params);
		return $this->fp->destroy($params);
	}

	// zones -----------

	public function getFloorPlanZones(stdClass $params){
		if(isset($params->floor_plan_id)){
			return $this->getFloorPlanZonesByFloorPlanId($params->floor_plan_id);
		}
		return  $this->fpz->load($params)->all();
	}

	public function createFloorPlanZone(stdClass $params){
		return $this->fpz->save($params);
	}

	public function updateFloorPlanZone($params){
		return $this->fpz->save($params);
	}

	public function removeFloorPlanZone(stdClass $params){
		if(isset($params->floorPlanId)){
			$filter = new stdClass();
			$filter->filter[0] = new stdClass();
			$filter->filter[0]->property = 'floor_plan_id';
			$filter->filter[0]->value = $params->floorPlanId;
			$this->fpz->destroy($params, $filter);
			unset($filter);
		} else{
			$this->fpz->destroy($params);
		}
		return $params;
	}

	public function getFloorPlanZonesByFloorPlanId($floor_plan_id){
		$sql = 'SELECT DISTINCT fpz.*, (SELECT 1 FROM `patient_zone` as pz WHERE pz.zone_id = fpz.id AND pz.time_out IS NULL LIMIT 1) as in_use
				  FROM `floor_plans_zones` as fpz
 				 WHERE fpz.floor_plan_id = ?';
		return $this->fpz->sql($sql)->all(array($floor_plan_id));
	}

}

//$e = new FloorPlans();
//echo '<pre>';
//print_r($e->getPatientsZonesByFloorPlanId(1));
//print '<br><br>Session ----->>> <br><br>';
//print_r($_SESSION);
