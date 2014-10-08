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
include_once(ROOT .'/dataProvider/User.php');
class Insurance {

	/**
	 * @var MatchaCUP
	 */
	private $pi;

	function __construct(){
		$this->ic = MatchaModel::setSenchaModel('App.model.administration.InsuranceCompany');
		$this->in = MatchaModel::setSenchaModel('App.model.administration.InsuranceNumber');
		$this->pi = MatchaModel::setSenchaModel('App.model.patient.Insurance');
	}

	/** Companies */
	public function getInsuranceCompanies($params) {
		return $this->ic->load($params)->all();
	}

	public function getInsuranceCompany($params) {
		return $this->ic->load($params)->one();
	}

	public function addInsuranceCompany($params) {
		return $this->ic->save($params);
	}

	public function updateInsuranceCompany($params) {
		return $this->ic->save($params);
	}

	public function destroyInsuranceCompany($params) {
		return $this->ic->destroy($params);
	}

	/** Numbers */
	public function getInsuranceNumbers($params) {
		$User = new User();
		$numbers = $this->in->load($params)->all();
		foreach($numbers as $i => $number){
			$user = $User->getUser($number['provider_id']);
			$insCo = $this->getInsuranceCompany($number['insurance_company_id']);
			$numbers[$i]['insurance_company_id_text'] = $insCo['name'];
			$numbers[$i]['provider_id_text'] = $user['fullname'];
		}
		return $numbers;
	}

	public function getInsuranceNumber($params) {
		return $this->in->load($params)->one();
	}

	public function addInsuranceNumber($params) {
		return $this->in->save($params);
	}

	public function updateInsuranceNumber($params) {
		return $this->in->save($params);
	}

	public function destroyInsuranceNumber($params) {
		return $this->in->destroy($params);
	}

	/** Patient */
	public function getInsurances($params) {
		return $this->pi->load($params)->all();
	}

	public function getInsurance($params) {
		return $this->pi->load($params)->one();
	}

	public function addInsurance($params) {
		return $this->pi->save($params);
	}

	public function updateInsurance($params) {
		return $this->pi->save($params);
	}

	public function destroyInsurance($params) {
		return $this->pi->destroy($params);
	}

	public function getPatientPrimaryInsuranceByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'insurance_type';
		$params->filter[1]->value = 'P';
		return $this->getInsurance($params);
	}

	public function getPatientSecondaryInsuranceByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'insurance_type';
		$params->filter[1]->value = 'S';
		return $this->getInsurance($params);
	}


	public function getPatientComplementaryInsuranceByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'insurance_type';
		$params->filter[1]->value = 2;
		return $this->getInsurance($params);
	}
} 