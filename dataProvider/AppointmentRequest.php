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
class AppointmentRequest {

	private $a;

	public function __construct() {
		$this->a = MatchaModel::setSenchaModel('App.model.patient.AppointmentRequest');
	}

	public function getAppointmentRequests($params) {
		return $this->a->load($params)
			->leftJoin(['FullySpecifiedName' => 'procedure1'],'sct_concepts','procedure1_code','ConceptId')
			->leftJoin(['FullySpecifiedName' => 'procedure2'],'sct_concepts','procedure2_code','ConceptId')
			->leftJoin(['FullySpecifiedName' => 'procedure3'],'sct_concepts','procedure3_code','ConceptId')->all();
	}

	public function getAppointmentRequest($params) {
		return $this->a->load($params)
			->leftJoin(['FullySpecifiedName' => 'procedure1'],'sct_concepts','procedure1_code','ConceptId')
			->leftJoin(['FullySpecifiedName' => 'procedure2'],'sct_concepts','procedure2_code','ConceptId')
			->leftJoin(['FullySpecifiedName' => 'procedure3'],'sct_concepts','procedure3_code','ConceptId')->one();
	}

	public function addAppointmentRequest($params) {
		return $this->a->save($params);
	}

	public function updateAppointmentRequest($params) {
		return $this->a->save($params);
	}

	public function deleteAppointmentRequest($params) {
		return $this->a->destroy($params);
	}
}