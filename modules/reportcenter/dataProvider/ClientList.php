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

class ClientList extends Reports {
	private $user;
	private $patient;
	private $encounter;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct() {
		parent::__construct();
		$this->user = new \User();
		$this->patient = new \Patient();
		$this->encounter = new \Encounter();

		return;
	}

	public function createClientList(\stdClass $params) {
		$Url = $this->ReportBuilder($params->html, 10);
		return array(
			'success' => true,
			'url' => $Url
		);
	}

	public function getClientList(\stdClass $params) {
		$params->to = ($params->to == '') ? date('Y-m-d') : $params->to;
		$params->pid = isset($params->pid) ? $params->pid : null;
		$records = $this->encounter->getEncounterByDateFromToAndPatient($params->from, $params->to, $params->pid);

		foreach($records AS $num => $rec){
			$records[$num]['fullname'] = $this->patient->getPatientFullNameByPid($rec['pid']);
			$records[$num]['fulladdress'] = $this->patient->getPatientFullAddressByPid($rec['pid']);
		}
		return $records;
	}

}

//$e = new ClientList();
//$params = new stdClass();
//echo '<pre>';
//print_r($e->getClientList($params));
