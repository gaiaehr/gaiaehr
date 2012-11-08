<?php
/*
 GaiaEHR (Electronic Health Records)
 Fees.php
 Fees dataProvider
 Copyright (C) 2012 Certun, Inc.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see
 <http://www.gnu.org/licenses/>
 .
 */

if (!isset($_SESSION))
{
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}

include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');

class Fees
{
	/**
	 * @var dbHelper
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

	private $enc;

	function __construct()
	{
		// Declare all the variables that we are gone to use
		// within the class.
		(object)$this -> db = new dbHelper();
		(object)$this -> user = new User();
		(object)$this -> patient = new Patient();
		(object)$this -> enc = new Encounter();
		return;
	}

	/**
	 * function: getFilterEncountersBillingData
	 * The first call to populate the dataGrid on the Billing panel.
	 */
	public function getFilterEncountersBillingData(stdClass $params)
	{
		// Declare all the variables that we are gone to use.
		(string)$whereClause = '';
		(string)$whereCommand = '';
		(array)$encounters = '';
		(int)$total = 0;
		(string)$sql = '';

		// Check for the passed parameters from extjs and apply them to the where clause.
		if ($params -> datefrom && $params -> dateto)
		{
			$whereCommand = 'WHERE';
			$whereClause .= chr(13) . " AND encounters.onset_date BETWEEN '" . $params -> datefrom . "' AND '" . $params -> dateto . "'";
		}
		if ($params -> patient)
		{
			$whereCommand = 'WHERE';
			$whereClause .= chr(13) . " AND encounters.pid = '" . $params -> patient . "'";
		}
		if ($params -> provider && $params -> provider <> 'All')
		{
			$whereCommand = 'WHERE';
			$whereClause .= chr(13) . " AND encounters.provider_uid = '" . $params -> patient . "'";
		}
		
		// Eliminate the first 6 characters of the where clause
		// this to eliminate and extra AND from the SQL statement
		$whereClause = substr($whereClause, 6);

		$sql = "Select
					encounters.eid,
					encounters.pid,
					If(encounters.provider_uid Is Null, 'None', encounters.provider_uid)
					As encounterProviderUid,
					If(patient_demographics.provider Is Null, 'None',
					patient_demographics.provider) As primaryProviderUid,
					encounters.service_date,
					encounters.billing_stage,
					patient_demographics.title,
					patient_demographics.fname,
					patient_demographics.mname,
					patient_demographics.lname,
					encounters.onset_date,
					encounters.close_date,
					encounters.supervisor_uid,
					encounters.provider_uid,
					encounters.open_uid
				From
					encounters Left Join
					patient_demographics On patient_demographics.pid = encounters.pid
				$whereCommand $whereClause
				Order By
  					encounters.service_date";

		$this -> db -> setSQL($sql);
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{
			$row['patientName'] = $row['title'] . ' ' . Person::fullname($row['fname'], $row['mname'], $row['lname']);
			$encounters[] = $row;
		}
		$total = count($encounters);
		$encounters = array_slice($encounters, $params -> start, $params -> limit);
		return array(
			'totals' => $total,
			'encounters' => $encounters
		);

	}

	/**
	 * Function: getEncountersByPayment
	 */
	public function getEncountersByPayment(stdClass $params)
	{
		// Declare all the variables that we are gone to use.
		(string)$sql = '';
		(array)$encounters = '';
		(int)$total = 0;

		$sql = "SELECT enc.eid,
                       enc.pid,
                       if(enc.provider_uid is null, 'None', enc.provider_uid) AS encounterProviderUid,
                       enc.service_date,
                       enc.billing_stage,
                       demo.title,
                       demo.fname,
                       demo.mname,
                       demo.lname,
                       if(demo.provider is null, 'None', demo.provider) AS primaryProviderUid
                  FROM encounters AS enc
             LEFT JOIN patient_demographics AS demo ON demo.pid = enc.pid
              ORDER BY enc.service_date ASC";

		$this -> db -> setSQL($sql);

		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{
			$row['patientName'] = $row['title'] . ' ' . Person::fullname($row['fname'], $row['mname'], $row['lname']);
			$encounters[] = $row;
		}
		$total = count($encounters);
		$encounters = array_slice($encounters, $params -> start, $params -> limit);
		return array(
			'totals' => $total,
			'encounters' => $encounters
		);

	}

	/**
	 * Function: getPaymentsBySearch
	 */
	public function getPaymentsBySearch(stdClass $params)
	{
		//TODO: Payment search function

		return array(
			'totals' => 0,
			'rows' => array()
		);
	}

	/**
	 * Function: addPayment
	 */
	public function addPayment(stdClass $params)
	{
		$data = get_object_vars($params);
		$this -> db -> setSQL($this -> db -> sqlBind($data, "payment_transactions", "I"));
		$this -> db -> execLog();
		if ($this -> db -> lastInsertId == 0)
		{
			return (array)$success = 'false';
		}
		else
		{
			return (array)$success = 'true';
		}
	}

	/**
	 * Function: getPatientBalance
	 */
	public function getPatientBalance(stdClass $params)
	{
		return $this -> getPatientBalanceByPid($params -> pid);
	}

	/**
	 * Function: getPatientBalanceByPid
	 */
	public function getPatientBalanceByPid($pid)
	{
		// Declare all the variables that we are gone to use.
		(array)$balance_total = '';

		$this -> db -> setSQL("SELECT SUM(amount) as balance FROM payment_transactions WHERE payer_id = '$pid'");
		$balance_total = $this -> db -> fetchRecord();

		return $balance_total['balance'];
	}

}

//$params = new stdClass();
//
//$p = new Fees($params);
//echo '<pre>';
//print_r($p->getEncountersByPayment($params));
