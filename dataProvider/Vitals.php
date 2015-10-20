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
 include_once(ROOT.'/dataProvider/User.php');
class Vitals {

	/**
	 * @var MatchaCUP
	 */
	private $v;
	/**
	 * @var User
	 */
	private $User;

	function __construct() {
        if(!isset($this->v))
            $this->v = MatchaModel::setSenchaModel('App.model.patient.Vitals');
		$this->User = new User();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getVitals(stdClass $params){
		$records =  $this->v->load($params)->all();
		foreach($records as $i => $record){
			$records[$i]['height_in'] = isset($record['height_in']) ? intval($record['height_in']) : '';
			$records[$i]['height_cm'] = isset($record['height_cm']) ? intval($record['height_cm']) : '';
			$records[$i]['administer_by'] = $record['uid'] != null ? $this->User->getUserNameById($record['uid']) : '';
			$records[$i]['authorized_by'] = $record['auth_uid'] != null ? $this->User->getUserNameById($record['auth_uid']) : '';
		}
		return $records;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addVitals($params){
		if(is_array($params)){
			foreach($params as $i => $param){
				$params[$i] = (object) $this->v->save($param);
				$params[$i]->administer_by = $this->User->getUserNameById($param->uid);
			}
		}else{
			$params = (object) $this->v->save($params);
			$params->administer_by = $this->User->getUserNameById($params->uid);
		}
		return $params;
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateVitals($params){
		$record = $this->v->save($params);
		if(is_array($params)){
			foreach($record as $i => $rec){
				$record[$i] = $rec = (object) $rec;
				if(isset($rec->uid)){
					$record[$i]->administer_by = $rec->uid != 0 ? $this->User->getUserNameById($rec->uid) : '';
				}
				if(isset($rec->auth_uid)){
					$record[$i]->authorized_by = $rec->auth_uid != 0 ? $this->User->getUserNameById($rec->auth_uid) : '';
				}
			}
		}else{
			$record = (object) $record;
			if(isset($record->uid)){
				$record->administer_by = $record->uid != 0 ? $this->User->getUserNameById($record->uid) : '';
			}
			if(isset($record->auth_uid)){
				$record->authorized_by = $record->auth_uid != 0 ? $this->User->getUserNameById($record->auth_uid) : '';
			}
		}
		return $record;
	}

	/**
	 * @param $pid
	 * @return array
	 */
	public function getVitalsByPid($pid){
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'pid';
		$filters->filter[0]->value = $pid;

		$filters->sort[0] = new stdClass();
		$filters->sort[0]->property = 'date';
		$filters->sort[0]->direction = 'DESC';
		return $this->getVitals($filters);
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getVitalsByEid($eid){
		$filters = new stdClass();
		$filters->filter[0] = new stdClass();
		$filters->filter[0]->property = 'eid';
		$filters->filter[0]->value = $eid;

		$filters->sort[0] = new stdClass();
		$filters->sort[0]->property = 'date';
		$filters->sort[0]->direction = 'DESC';
		return $this->getVitals($filters);
	}

	public function getCodes(){
		return array(
			'8310-5' => array(
				'code' => '8310-5',
				'code_text' => 'Body Temperature',
				'code_type' => 'LOINC',
			    'mapping' => 'temp_c'
			),
			'8462-4' => array(
				'code' => '8462-4',
				'code_text' => 'BP Diastolic',
				'code_type' => 'LOINC',
				'mapping' => 'bp_diastolic'
			),
			'8480-6' => array(
				'code' => '8480-6',
				'code_text' => 'BP Systolic',
				'code_type' => 'LOINC',
				'mapping' => 'bp_systolic'
			),
			'8287-5' => array(
				'code' => '8287-5',
				'code_text' => 'Head Circumference',
				'code_type' => 'LOINC',
				'mapping' => 'head_circumference_cm'
			),
			'8867-4' => array(
				'code' => '8867-4',
				'code_text' => 'Heart Rate',
				'code_type' => 'LOINC',
				'mapping' => 'pulse'
			),
			'8302-2' => array(
				'code' => '8302-2',
				'code_text' => 'Height',
				'code_type' => 'LOINC',
				'mapping' => 'height_cm'
			),
			'8306-3' => array(
				'code' => '8306-3',
				'code_text' => 'Height (Lying)',
				'code_type' => 'LOINC',
				'mapping' => 'height_cm'
			),
			'2710-2' => array(
				'code' => '2710-2',
				'code_text' => 'O2 % BldC Oximetry',
				'code_type' => 'LOINC',
				'mapping' => 'oxygen_saturation'
			),
			'9279-1' => array(
				'code' => '9279-1',
				'code_text' => 'Respiratory Rate',
				'code_type' => 'LOINC',
				'mapping' => 'respiration'
			),
			'3141-9' => array(
				'code' => '3141-9',
				'code_text' => 'Weight Measured',
				'code_type' => 'LOINC',
				'mapping' => 'weight_kg'
			)
		);
	}

	public function G2($params){

		$pid = $params->pid;
		$start = $params->start;
		$end = $params->end;
		$provider = $params->provider;

		$records = $this->v->load($params)->all();

		return array(
			'D' => 'asdasdsadasdsadsad',
			'N' => '',
			'records' => $records
		);
	}
}

