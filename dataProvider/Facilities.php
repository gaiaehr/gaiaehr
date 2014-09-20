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

class Facilities {

	/**
	 * @var MatchaCUP
	 */
	private $f;

	/**
	 * @var MatchaCUP
	 */
	private $c;

	/**
	 * @var MatchaCUP
	 */
	private $d;


	private function setFacilityModel(){
		if(!isset($this->f)){
			$this->f = MatchaModel::setSenchaModel('App.model.administration.Facility');
		}
	}

	private function setFacilityConfigModel(){
		if(!isset($this->c)){
			$this->c = MatchaModel::setSenchaModel('App.model.administration.FacilityStructure');
		}
	}

	private function setDepartmentModel(){
		if(!isset($this->d)){
			$this->d = MatchaModel::setSenchaModel('App.model.administration.Department');
		}
	}

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
	/**
	 * @param $params
	 * @return array
	 */
	public function getFacilities($params){
		$this->setFacilityModel();
		$rows = array();
		foreach($this->f->load($params)->all() as $row){
			$row['pos_code'] = str_pad($row['pos_code'], 2, '0', STR_PAD_LEFT);
			array_push($rows, $row);
		}
		return $rows;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function getFacility($params){
		$this->setFacilityModel();
		return $this->f->load($params)->one();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addFacility($params){
		$this->setFacilityModel();
		return $facility = $this->f->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateFacility($params){
		$this->setFacilityModel();
		return $this->f->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function deleteFacility($params){
		$this->setFacilityModel();
		return $this->f->destroy($params);
	}

	/**
	 * @param $facilityId
	 * @return mixed
	 */
	public function setFacility($facilityId){
		return $_SESSION['user']['facility'] = $facilityId;
	}

	/**
	 * @param bool $getData
	 * @return mixed
	 */
	public function getCurrentFacility($getData = false){
		$this->setFacilityModel();
		if($getData) return $this->f->load($_SESSION['user']['facility'])->one();
		return $_SESSION['user']['facility'];
	}

	//------------------------------------------------------------------------------------------------------------------
	// Extra methods
	// This methods are used by the view to gather extra data from the store or the model
	//------------------------------------------------------------------------------------------------------------------
	/**
	 * @param $fid
	 * @return string
	 */
	public function getFacilityInfo($fid){
		$this->setFacilityModel();
		$resultRecord = $this->f->load(array('id' => $fid), array('name', 'phone', 'street', 'city', 'state', 'postal_code'))->one();
		return 'Facility: ' . $resultRecord['name'] . ' ' . $resultRecord['phone'] . ' ' . $resultRecord['street'] . ' ' . $resultRecord['city'] . ' ' . $resultRecord['state'] . ' ' . $resultRecord['postal_code'];
	}

	/**
	 * @return mixed
	 */
	public function getActiveFacilities(){
		$this->setFacilityModel();
		return $this->f->load(array('active' => '1'))->one();
	}

	/**
	 * @param $facilityId
	 * @return mixed
	 */
	public function getActiveFacilitiesById($facilityId){
		$this->setFacilityModel();
		return $this->f->load(array('active' => '1', 'id' => $facilityId))->one();
	}

	/**
	 * @return mixed
	 */
	public function getBillingFacilities(){
		$this->setFacilityModel();
		return $this->f->load(array('active' => '1', 'billing_location' => '1'))->one();
	}

	///////////////////////////////////////////

	/**
	 * @param $params
	 * @return mixed
	 */
	public function getFacilityConfigs($params){
		$this->setFacilityConfigModel();
		$records = array();
		$facilities = $this->getFacilities(array('active' => 1));

		foreach($facilities as $facility){
			$facility = (object) $facility;
			$sql = "SELECT f.*, d.title as `text`, false AS `leaf`, true AS `expanded`, false AS `expandable`, true AS `loaded`
					 FROM `facility_structures` AS f
				LEFT JOIN `departments` AS d ON f.foreign_id = d.id
				    WHERE  f.foreign_type = 'D' AND f.parentId = 'f{$facility->id}'";

			$departments = $this->c->sql($sql)->all();


			foreach($departments as $i => $department){
				$department = (object) $department;
				$sql = "SELECT f.*, s.title as `text`, true AS `leaf`, true AS `expanded`, true AS `loaded`
					 FROM `facility_structures` AS f
				LEFT JOIN `specialties` AS s ON f.foreign_id = s.id
				    WHERE  f.foreign_type = 'S' AND f.parentId = '{$department->id}'";

				$specialties = $this->c->sql($sql)->all();
				$departments[$i]['children'] = $specialties;
			}

			$records[] = array(
				'id' => 'f' . $facility->id,
				'text' => $facility->name,
				'leaf' => false,
				'expanded' => true,
				'expandable' => false,
				'children' => $departments
			);
		}

		return $records;
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getFacilityConfig($params){
		$this->setFacilityConfigModel();
		return $this->c->load($params)->one();
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function addFacilityConfig($params){
		$this->setFacilityConfigModel();
		return $this->c->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateFacilityConfig($params){
		$this->setFacilityConfigModel();
		return $this->c->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function deleteFacilityConfig($params){
		$this->setFacilityConfigModel();
		return $this->c->destroy($params);
	}

	///////////////////////////////////////////

	/**
	 * @param $params
	 * @return mixed
	 */
	public function getDepartments($params){
		$this->setDepartmentModel();
		return $this->d->load($params)->all();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getDepartment($params){
		$this->setDepartmentModel();
		return $this->d->load($params)->one();
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function addDepartment($params){
		$this->setDepartmentModel();
		return $this->d->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateDepartment($params){
		$this->setDepartmentModel();
		return $this->d->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function deleteDepartment($params){
		$this->setDepartmentModel();
		return $this->d->destroy($params);
	}

}
