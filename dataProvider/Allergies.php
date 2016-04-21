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

class Allergies {

	/**
	 * @var MatchaCUP
	 */
	private $a;
	/**
	 * @var MatchaCUP
	 */
	private $d;

	function __construct() {
        if($this->a == NULL)
            $this->a = MatchaModel::setSenchaModel('App.model.patient.Allergies');
	}

	private function setAdminAllergyModel(){
		if(is_null($this->d)){
			$this->d = MatchaModel::setSenchaModel('App.model.administration.Allergies');
		}
	}

	public function getPatientAllergies($params){
		if(isset($params->reconciled) && $params->reconciled == true){
			$groups = new stdClass();
			$groups->group[0] = new stdClass();
			$groups->group[0]->property = 'allergy_code';
			return $this->a->load($params)->group($groups)->all();
		}
		return $this->a->load($params)->all();
	}

	public function getPatientAllergy($params){
		return $this->a->load($params)->one();
	}

	public function addPatientAllergy($params){
		return $this->a->save($params);
	}

	public function updatePatientAllergy($params){
		return $this->a->save($params);
	}

	public function destroyPatientAllergy($params){
		return $this->a->destroy($params);
	}

	public function getPatientAllergiesByPid($pid)
    {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;
		return $this->getPatientAllergies($params);
	}

	public function getPatientAllergiesByEid($eid)
    {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'eid';
		$params->filter[0]->value = $eid;
		return $this->getPatientAllergies($params);
	}

	public function getPatientActiveDrugAllergiesByPid($pid)
    {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;

		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'allergy_code_type';
		$params->filter[1]->value = 'RXNORM';
		return $this->getPatientAllergies($params);
	}

	public function getPatientActiveDrugAllergiesByPidAndCode($pid, $code)
    {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pid';
		$params->filter[0]->value = $pid;

		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'allergy_code';
		$params->filter[1]->value = $code;

		$params->filter[2] = new stdClass();
		$params->filter[2]->property = 'allergy_code_type';
		$params->filter[2]->value = 'RXNORM';
		return $this->getPatientAllergies($params);
	}

	public function searchAllergiesData($params)
    {
		if(!isset($params->query)) return array();
		$this->setAdminAllergyModel();
		$sql = "SELECT * 
                FROM `allergies` 
                WHERE `allergy` LIKE '%{$params->query}%' 
                GROUP BY `allergy_code`	
                LIMIT 100";
		$records = $this->d->sql($sql)->all();
		return array(
			'total' => count($records),
		    'data' => array_slice($records, $params->start, $params->limit)
		);
	}
}

