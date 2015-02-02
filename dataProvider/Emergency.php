<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

include_once (ROOT . '/dataProvider/Patient.php');
include_once (ROOT . '/dataProvider/User.php');
include_once (ROOT . '/dataProvider/Services.php');
include_once (ROOT . '/dataProvider/PoolArea.php');
include_once (ROOT . '/dataProvider/Medical.php');
include_once (ROOT . '/dataProvider/Encounter.php');
include_once (ROOT . '/dataProvider/PreventiveCare.php');

class Emergency
{
	/**
	 * @var MatchaHelper
	 */
	private $db;
	/**
	 * @var User
	 */
	private $user;
	/**
	 * @var Patient
	 */
	private $patient;
	/**
	 * @var Services
	 */
	private $services;
	/**
	 * @var PoolArea
	 */
	private $poolArea;
	/**
	 * @var Medical
	 */
	private $medical;
	/**
	 * @var Encounter
	 */
	private $encounter;
	/**
	 * @var PreventiveCare
	 */
	private $preventiveCare;

	private $pid;
	private $eid;
	private $emergencyId;
	private $priority = 'Immediate';

	function __construct()
	{
		$this->db             = new MatchaHelper();
		$this->user           = new User();
		$this->patient        = new Patient();
		$this->services       = new Services();
		$this->poolArea       = new PoolArea();
		$this->medical        = new Medical();
		$this->encounter      = new Encounter();
		$this->preventiveCare = new PreventiveCare();
		return;
	}

	public function createNewEmergency()
	{
		$patient = $this->patient->createNewPatientOnlyName('EMER');
		if($patient['success']){
			$this->pid = $patient['patient']['pid'];
			/**
			 * send new patient to the emergency pool area
			 */
			$params           = new stdClass();
			$params->pid      = $this->pid;
			$params->priority = $this->priority;
			$params->sendTo   = 3;
			$this->poolArea->sendPatientToPoolArea($params);
			/**
			 * create new encounter
			 */
			$params                    = new stdClass();
			$params->pid               = $this->pid;
			$params->brief_description = '***EMERGENCY***';
			$params->visit_category    = 'Emergency';
			$params->priority          = $this->priority;
			$params->service_date      = Time::getLocalTime();
			$params->open_uid          = $_SESSION['user']['id'];
			$encounter                 = $this->encounter->createEncounter($params);
			$this->eid                 = $encounter['encounter']['eid'];
			/**
			 * log the emergency
			 */
			$this->logEmergency();

			/*
			 * update patient first name to EMERGENCY- encounter id
			 */
			$data['fname'] = 'EMER-' . $this->emergencyId;
			$this->db->setSQL($this->db->sqlBind($data, 'patient', 'U', array('pid' => $this->pid)));
			$this->db->execOnly();
			return array(
				'success' => true, 'emergency' => array(
					'pid' => $this->pid, 'eid' => $this->eid, 'name' => 'EMER-' . $this->emergencyId, 'priority' => $params->priority
				)
			);
		} else {
			return array(
				'success' => false, 'error' => $patient['error']
			);
		}
	}

	public function logEmergency()
	{
		$data['pid']          = $this->pid;
		$data['eid']          = $this->eid;
		$data['uid']          = $_SESSION['user']['id'];
		$data['date_created'] = Time::getLocalTime();
		$this->db->setSQL($this->db->sqlBind($data, 'emergencies', 'I'));
		$this->db->execLog();
		$this->emergencyId = $this->db->lastInsertId;
	}

}

//$params = new stdClass();
//$params->pid = 2;
//$params->date = '2012-06-25 10:48:00';
//$e = new Emergency();
//echo '<pre>';
//print_r($e->createNewEmergency());
