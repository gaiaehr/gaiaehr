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

class Procedures {
	/**
	 * @var bool|MatchaCUP
	 */
	private $p;

	function __construct() {
        if(!isset($this->p))
            $this->p = MatchaModel::setSenchaModel('App.model.patient.encounter.Procedures');
	}

	public function getPatientProcedures($params) {
		return $this->p->load($params)->all();
	}

	public function getPatientProcedure($params) {
		return $this->p->load($params)->one();
	}

	public function saveProcedure($params) {
		return $this->p->save($params);
	}

	public function destroyProcedure($params) {
		return $this->p->destroy($params);
	}

	public function getPatientProceduresByPid($pid) {
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value =  $pid;
		return $this->p->load($params)->all();
	}

	public function getPatientProceduresByEid($eid) {
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'eid';
		$params->filter[0]->value =  $eid;
		return $this->p->load($params)->all();
	}

	public function getPatientProceduresByPidAndCode($pid, $code) {
		$params =  new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value =  $pid;

		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'code';
		$params->filter[1]->value =  $code;
		return $this->p->load($params)->all();
	}


	// TODO: REMOVE
	public function loadProcedures($params) {
		return $this->p->load($params)->all();
	}

}
//print '<pre>';
//$p = new Prescriptions();
//$params = new stdClass();
//$params->query = 't';
//print_r($p->getSigCodesByQuery($params));
