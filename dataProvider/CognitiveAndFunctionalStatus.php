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

class CognitiveAndFunctionalStatus {
	/**
	 * @var MatchaCUP
	 */
	private $c;

	function __construct(){
        if($this->c == NULL)
            $this->c = MatchaModel::setSenchaModel('App.model.patient.CognitiveAndFunctionalStatus');
	}

	public function getPatientCognitiveAndFunctionalStatuses($params){
		return $this->c->load($params)->all();
	}

	public function getPatientCognitiveAndFunctionalStatus($params){
		return $this->c->load($params)->one();
	}

	public function addPatientCognitiveAndFunctionalStatus($params){
		return $this->c->save($params);
	}

	public function updateCognitiveAndFunctionalStatus($params){
		return $this->c->save($params);
	}

	public function destroyCognitiveAndFunctionalStatus($params){
		return $this->c->destroy($params);
	}

	public function getPatientCognitiveAndFunctionalStatusesByPid($pid){
		$this->c->addFilter('pid', $pid);
		return $this->c->load()->all();
	}


}