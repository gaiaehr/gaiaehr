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
class Referrals {
	/**
	 * @var MatchaCUP
	 */
	private $r;

	function __construct(){
		$this->r = MatchaModel::setSenchaModel('App.model.patient.Referral');
	}

	public function getPatientReferrals($params){
		return $this->r->load($params)->all();
	}

	public function getPatientReferral($params){
		return $this->r->load($params)->one();
	}

	public function addPatientReferral($params){
		return $this->r->save($params);
	}

	public function updatePatientReferral($params){
		return $this->r->save($params);
	}

	public function deletePatientReferral($params){
		return $this->r->destroy($params);
	}

	public function getPatientReferralsByEid($eid){
		$this->r->addFilter('eid', $eid);
		return $this->r->load()->all();
	}

	public function getPatientReferralByEid($eid){
		$this->r->addFilter('eid', $eid);
		return $this->r->load()->one();
	}

}