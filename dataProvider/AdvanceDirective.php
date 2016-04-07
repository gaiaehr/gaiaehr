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

class AdvanceDirective {

	/**
	 * @var MatchaCUP
	 */
	private $a;


	function __construct() {
        if($this->a == NULL)
            $this->a = MatchaModel::setSenchaModel('App.model.patient.AdvanceDirective');
	}

	public function getPatientAdvanceDirectives($params){
		return $this->a->load($params)->all();
	}

	public function getPatientAdvanceDirective($params){
		return $this->a->load($params)->one();
	}

	public function addPatientAdvanceDirective($params){
		return $this->a->save($params);
	}

	public function updatePatientAdvanceDirective($params){
		return $this->a->save($params);
	}

	public function destroyPatientAdvanceDirective($params){
		return $this->a->destroy($params);
	}

	public function getPatientAdvanceDirectivesByPid($pid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		return $this->getPatientAdvanceDirectives($params);
	}

	public function getPatientAdvanceDirectivesByEid($eid) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'eid';
		$params->filter[0]->value = $eid;
		return $this->getPatientAdvanceDirectives($params);
	}
}

