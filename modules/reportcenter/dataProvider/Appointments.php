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

class Appointments extends Reports {
	private $db;
	private $user;
	private $patient;
	private $encounter;
	private $facilities;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct() {
		parent::__construct();
		$this ->db = new \MatchaHelper();
		$this ->user = new \User();
		$this ->patient = new \Patient();
		$this ->encounter = new \Encounter();
		$this ->facilities = new \Facilities();

		return;
	}

	public function CreateAppointmentsReport(\stdClass $params) {
		ob_end_clean();
		$Url = $this ->ReportBuilder($params->html, 10);
		return array(
			'success' => true,
			'url' => $Url
		);
	}

	public function getAppointmentsList(\stdClass $params) {
		$params ->to = ($params ->to == '') ? date('Y-m-d') : $params ->to;
		$from = $params->from;
		$to = $params->to;
		$facility = $params->facility;
		$provider = $params->provider;
		$sql = " SELECT *
	               FROM calendar_events
	              WHERE start BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if(isset($facility) && $facility != '')
			$sql .= " AND facility = '$facility'";
		if(isset($provider) && $provider != '')
			$sql .= " AND user_id = '$provider'";
		$this ->db ->setSQL($sql);
		$alldata = $this->db->fetchRecords(\PDO::FETCH_ASSOC);
		foreach($alldata as $val => $data){
			$facilityname = $this->facilities->getActiveFacilitiesById($data['facility']);
			$cat = $this->getCalendarCategories($data['category']);
			$alldata[$val]['provider'] = $this->user->getUserNameById($data['user_id']);
			$alldata[$val]['fullname'] = $this ->patient ->getPatientFullNameByPid($data['patient_id']);
			$alldata[$val]['start_time'] = date('Y-m-d h:i:s a', strtotime($data['start']));
			$alldata[$val]['catname'] = $cat['catname'] == null ? '' : $cat['catname'];
			$alldata[$val]['facility'] = $facilityname['name'] == null ? '' : $facilityname['name'];
			$alldata[$val]['notes'] = $data['notes'];

		}
		return $alldata;
	}

	public function getCalendarCategories($category) {
		$this ->db ->setSQL("SELECT catname
		                       FROM calendar_categories
		                       WHERE catid ='$category'");
		return $this ->db ->fetchRecord(PDO::FETCH_ASSOC);
	}

}

//$e = new Appointments();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->getCalendarCategories(1));
