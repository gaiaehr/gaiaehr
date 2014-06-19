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
		$params->filters[0] = new stdClass();
		$params->filters[0]->property = 'pid';
		$params->filters[0]->value =  $pid;
		return $this->m->load($params)->all();
	}

	public function getPatientActiveMedicationsByPid($pid){
		$params =  new stdClass();
		$params->filters[0] = new stdClass();
		$params->filters[0]->property = 'pid';
		$params->filters[0]->value =  $pid;
		$params->filters[1] = new stdClass();
		$params->filters[1]->property = 'end_date';
		$params->filters[1]->operator = '!=';
		$params->filters[1]->value =  '0000-00-00';
		$records = $this->m->load($params)->all();

		foreach($records as $i => $record){
			if(strtotime($record['end_date']) < strtotime(date('Y-m-d'))){
				unset($records[$i]);
			}
		}

		return $records;
	}

	public function getPatientActiveMedicationsByPidAndCode($pid, $code){
		$params =  new stdClass();
		$params->filters[0] = new stdClass();
		$params->filters[0]->property = 'pid';
		$params->filters[0]->value =  $pid;

		$params->filters[1] = new stdClass();
		$params->filters[1]->property = 'end_date';
		$params->filters[1]->operator = '!=';
		$params->filters[1]->value =  '0000-00-00';

		$params->filters[2] = new stdClass();
		$params->filters[2]->property = 'RXCUI';
		$params->filters[2]->value =  $code;
		$records = $this->m->load($params)->all();

		foreach($records as $i => $record){
			if(strtotime($record['end_date']) < strtotime(date('Y-m-d'))){
				unset($records[$i]);
			}
		}

		return $records;
	}

}

