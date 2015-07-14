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

class FamilyHistory {

	/**
	 * @var MatchaCUP
	 */
	private $fh;

	function __construct(){
		$this->fh = MatchaModel::setSenchaModel('App.model.patient.FamilyHistory');
	}

    //------------------------------------------------------------------------------------------------------------------
    // Main Sencha Model Getter and Setters
    //------------------------------------------------------------------------------------------------------------------
	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getFamilyHistory($params){
		return $this->fh->load($params)->all();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function addFamilyHistory($params){
		return $facility = $this->fh->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateFamilyHistory($params){
		return $this->fh->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function deleteFamilyHistory($params){
		return $this->fh->destroy($params);
	}

	/**
	 * @param $pid
	 * @return mixed
	 */
	public function getFamilyHistoryByPid($pid){
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'pid';
		$filters->filter[0]->value = $pid;
		return $this->getFamilyHistory($filters);
	}
}
