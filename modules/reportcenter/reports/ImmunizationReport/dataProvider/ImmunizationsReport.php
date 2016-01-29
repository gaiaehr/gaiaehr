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

class ImmunizationsReport extends Reports {
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

	public function createImmunizationsReport(\stdClass $params) {
		ob_end_clean();
		$Url = $this->ReportBuilder($params->html, 10);
		return array(
			'success' => true,
			'url' => $Url
		);
	}

	public function getImmunizationsReport(\stdClass $params) {
		$params->to = ($params->to == '') ? date('Y-m-d') : $params->to;
		$from = $params->from;
		$to = $params->to;
		$immu = $params->immu;
		$sql = " SELECT *
	               FROM patient_immunizations
	              WHERE create_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if(isset($immu) && $immu != '')
			$sql .= " AND immunization_id = '$immu'";
		$this->db->setSQL($sql);
		$records = $this->db->fetchRecords(\PDO::FETCH_ASSOC);
		foreach($records AS $num => $rec){
			$records[$num]['fullname'] = $this->patient->getPatientFullNameByPid($rec['pid']);
		}
		return $records;
	}

}
//$e = new ImmunizationsReport();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->htmlImmunizationList($params,''));