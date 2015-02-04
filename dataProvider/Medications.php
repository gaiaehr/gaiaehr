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

class Medications {


	/**
	 * @var MatchaCUP
	 */
	private $m;

	function __construct() {
		$this->m = MatchaModel::setSenchaModel('App.model.patient.Medications');
		$this->m->setOrFilterProperties(array('id'));
	}

	public function getPatientMedications($params) {
		return $this->m->load($params)->all();
	}

	public function getPatientMedication($params) {
		return $this->m->load($params)->one();
	}

	public function addPatientMedication($params) {
		return $this->m->save($params);
	}

	public function updatePatientMedication($params) {
		return $this->m->save($params);
	}

	public function destroyPatientMedication($params) {
		return $this->m->destroy($params);
	}

	public function getPatientMedicationsByPid($pid){
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value =  $pid;
		return $this->m->load($params)->all();
	}

	public function getPatientMedicationsByEid($eid){
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'eid';
		$params->filter[0]->value =  $eid;
		return $this->m->load($params)->all();
	}

	public function getPatientActiveMedicationsByPid($pid){
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value =  $pid;
		$records = $this->m->load($params)->all();

		foreach($records as $i => $record){
			if($record['end_date'] != '0000-00-00' && strtotime($record['end_date']) <= strtotime(date('Y-m-d'))){
				unset($records[$i]);
			}
		}

		return $records;
	}

	public function getPatientActiveMedicationsByPidAndCode($pid, $code){
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value =  $pid;

		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'RXCUI';
		$params->filter[1]->value =  $code;
		$records = $this->m->load($params)->all();

		foreach($records as $i => $record){
			if($record['end_date'] != '0000-00-00' && strtotime($record['end_date']) < strtotime(date('Y-m-d'))){
				unset($records[$i]);
			}
		}

		return $records;
	}

}

