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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
include_once(ROOT . '/dataProvider/ServiceCodes.php');
include_once(ROOT . '/dataProvider/DiagnosisCodes.php');
include_once(ROOT . '/dataProvider/User.php');
class Referrals {
	/**
	 * @var MatchaCUP
	 */
	private $r;
	private $service;
	private $diagnosis;
	private $user;

	function __construct(){
		$this->r = MatchaModel::setSenchaModel('App.model.patient.Referral');
		$this->service = new ServiceCodes();
		$this->diagnosis = new DiagnosisCodes();
		$this->user = new User();
	}

	public function getPatientReferrals($params){
		$records = $this->r->load($params)->all();
		foreach($records AS $i => $record){
			$records[$i] = $this->processRecord($record);
		}
		return $records;
	}

	public function getPatientReferral($params){
		$record = $this->r->load($params)->one();
		return $this->processRecord($record);
	}

	public function addPatientReferral($params){
		$records = $this->r->save($params);
		if(is_array($params)){
			foreach($records AS $i => $record){
				$records[$i] = (object) $this->processRecord((array) $record);
			}
		}else{
			$records = (object) $this->processRecord((array) $records);
		}
		return $records;
	}

	public function updatePatientReferral($params){
		$records = $this->r->save($params);
		if(is_array($records)){
			foreach($records AS $i => $record){
				$records[$i] = (object) $this->processRecord((array) $record);
			}
		}else{
			$records = (object) $this->processRecord((array) $records);
		}
		return $records;
	}

	public function deletePatientReferral($params){
		return $this->r->destroy($params);
	}

	private function processRecord($record){
		$record['service_text'] = $this->service->getServiceCodeByCodeAndCodeType($record['service_code'], $record['service_code_type']);
		$record['diagnosis_text'] = $this->diagnosis->getServiceCodeByCodeAndCodeType($record['diagnosis_code'], $record['diagnosis_code_type']);
		$record['refer_by_text'] = $this->user->getUserFullNameById($record['refer_by']);
		$record['refer_to_text'] = $this->user->getUserFullNameById($record['refer_to']);
		return $record;
	}


}