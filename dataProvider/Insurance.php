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

class Insurance {

	/**
	 * @var MatchaCUP
	 */
	private $i;

	function __construct(){
		$this->i = MatchaModel::setSenchaModel('App.model.patient.Insurance');
	}

	public function getInsurances($params) {
		return $this->i->load($params)->all();
	}

	public function getInsurance($params) {
		return $this->i->load($params)->one();
	}

	public function addInsurance($params) {
		return $this->i->save($params);
	}

	public function updateInsurance($params) {
		return $this->i->save($params);
	}

	public function destroyInsurance($params) {
		return $this->i->destroy($params);
	}

	public function getPatientPrimaryInsuranceByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'type';
		$params->filter[1]->value = 1;

		$params->sort[0] = new stdClass();
		$params->sort[0]->property = 'subscriberDob';
		$params->sort[0]->direction = 'ASC';

		return $this->getInsurance($params);
	}

	public function getPatientSecondaryInsuranceByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'type';
		$params->filter[1]->value = 1;

		$params->sort[0] = new stdClass();
		$params->sort[0]->property = 'subscriberDob';
		$params->sort[0]->direction = 'DESC';

		return $this->getInsurance($params);
	}


	public function getPatientTertiaryInsuranceByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'type';
		$params->filter[1]->value = 2;

		$params->sort[0] = new stdClass();
		$params->sort[0]->property = 'subscriberDob';
		$params->sort[0]->direction = 'ASC';

		return $this->getInsurance($params);
	}
} 