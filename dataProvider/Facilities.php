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

	function __construct(){
		$this->f = MatchaModel::setSenchaModel('App.model.administration.Facility');
	}

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getFacilities($params){
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
		return $this->f->load($params)->one();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addFacility($params){
		return $facility = $this->f->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateFacility($params){
		return $this->f->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function deleteFacility($params){
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
		$resultRecord = $this->f->load(array('id' => $fid), array('name', 'phone', 'street', 'city', 'state', 'postal_code'))->one();
		return 'Facility: ' . $resultRecord['name'] . ' ' . $resultRecord['phone'] . ' ' . $resultRecord['street'] . ' ' . $resultRecord['city'] . ' ' . $resultRecord['state'] . ' ' . $resultRecord['postal_code'];
	}

	/**
	 * @return mixed
	 */
	public function getActiveFacilities(){
		return $this->f->load(array('active' => '1'))->one();
	}

	/**
	 * @param $facilityId
	 * @return mixed
	 */
	public function getActiveFacilitiesById($facilityId){
		return $this->f->load(array('active' => '1', 'id' => $facilityId))->one();
	}

	/**
	 * @return mixed
	 */
	public function getBillingFacilities(){
		return $this->f->load(array('active' => '1', 'billing_location' => '1'))->one();
	}

}
