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
namespace modules\reportcenter\dataProvider;

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

include_once('Reports.php');
include_once(ROOT . '/classes/MatchaHelper.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/Encounter.php');
include_once(ROOT . '/dataProvider/i18nRouter.php');

class Rx extends Reports {
	private $db;
	private $user;
	private $patient;
	private $encounter;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct() {
		parent::__construct();
		$this->db = new \MatchaHelper();
		$this->user = new \User();
		$this->patient = new \Patient();
		$this->encounter = new \Encounter();

		return;
	}

	public function createPrescriptionsDispensations(\stdClass $params) {
		ob_end_clean();
		$Url = $this->ReportBuilder($params->html, 10);
		return array(
			'success' => true,
			'url' => $Url
		);
	}

	public function getPrescriptionsFromAndToAndPid(\stdClass $params) {
		$from = $params->from;
		$to = $params->to = ($params->to == '') ? date('Y-m-d') : $params->to;
		$drug = $params->drug;
		$pid = $params->pid;
		$alldata = '';
		$sql = " SELECT *
	               FROM patient_prescriptions
	              WHERE created_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if(isset($pid) && $pid != '')
			$sql .= " AND pid = '$pid'";
		$this->db->setSQL($sql);
		foreach($this->db->fetchRecords(\PDO::FETCH_ASSOC) as $key => $data){
			$id = $data['id'];
			$sql = " SELECT *
		   	           FROM patient_medications
		   	          WHERE prescription_id = '$id'";
			if(isset($drug) && $drug != '')
				$sql .= " AND medication_id = '$drug'";
			$this->db->setSQL($sql);
			$alldata[$key] = $this->db->fetchRecords(\PDO::FETCH_ASSOC);
		}
		$records = array();
		foreach($alldata as $data){
			foreach($data as $key => $rec){
				$records[$key] = $rec;
			}
		}
		foreach($records as $num => $rec){
			$records[$num]['fullname'] = $this->patient->getPatientFullNameByPid($rec['pid']);
			$records[$num]['instructions'] = $rec['directions'];
		}
		return $records;
	}

}

//$e = new Rx();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->createPrescriptionsDispensations($params));
