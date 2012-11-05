<?php
/*
 GaiaEHR (Electronic Health Records)
 Fees.php
 Fees dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

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
		$this -> db = new dbHelper();
		$this -> user = new User();
		$this -> patient = new Patient();
		$this -> enc = new Encounter();
		return;
	}

	public function getFilterEncountersBillingData(stdClass $params)
	{

		/*
		 * MySQL can hadle ternary conditions too, it will be much faster that way.
		 * 
		 * $sql = "SELECT enc.eid,
                       enc.pid,
                       enc.prov_uid AS encounterProviderUid,
                       enc.service_date,
                       enc.billing_stage,
                       demo.title,
                       demo.fname,
                       demo.mname,
                       demo.lname,
                       demo.provider AS primaryProviderUid
                  FROM encounters AS enc
             LEFT JOIN patient_demographics AS demo ON demo.pid = enc.pid
              ORDER BY enc.service_date ASC ";
		 */
		 
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
		$encounters = array();
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{

			//$row['primaryProvider'] = $row['primaryProviderUid'] == null ? 'None' : $this -> user -> getUserNameById($row['primaryProviderUid']);
			//$row['encounterProvider'] = $row['encounterProviderUid'] == null ? 'None' : $this -> user -> getUserNameById($row['encounterProviderUid']);

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

	public function getEncountersByPayment(stdClass $params)
	{

		/**
		 * MySQL can hadle ternary conditions too, it will be much faster that way.
		 * 
		 * $sql = "SELECT enc.eid,
                       enc.pid,
                       enc.prov_uid AS encounterProviderUid,
                       enc.service_date,
                       enc.billing_stage,
                       demo.title,
                       demo.fname,
                       demo.mname,
                       demo.lname,
                       demo.provider AS primaryProviderUid
                  FROM encounters AS enc
             LEFT JOIN patient_demographics AS demo ON demo.pid = enc.pid
              ORDER BY enc.service_date ASC ";
		 */
			  
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
		$encounters = array();
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		{

			//$row['primaryProvider'] = $row['primaryProviderUid'] == null ? 'None' : $this -> user -> getUserNameById($row['primaryProviderUid']);
			//$row['encounterProvider'] = $row['encounterProviderUid'] == null ? 'None' : $this -> user -> getUserNameById($row['encounterProviderUid']);

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

	public function getPaymentsBySearch(stdClass $params)
	{
		//TODO: Payment search function

		return array(
			'totals' => 0,
			'rows' => array()
		);
	}

	public function addPayment(stdClass $params)
	{
		$data = get_object_vars($params);
		$this -> db -> setSQL($this -> db -> sqlBind($data, "payment_transactions", "I"));
		$this -> db -> execLog();
		if ($this -> db -> lastInsertId == 0)
		{
			return array('success' => false);
		}
		else
		{
			return array('success' => true);
		}
	}

	public function getPatientBalance(stdClass $params)
	{
		return $this -> getPatientBalanceByPid($params -> pid);
	}

	public function getPatientBalanceByPid($pid)
	{

		/**
		 * MySQL Server can do math, and obviously doing the math in SQL is much faster
		 * than doing a foreach in php code, also less code.
		 *
		 * $balance = 0;
		 * $this -> db -> setSQL("SELECT * FROM payment_transactions WHERE payer_id = '$pid'");
		 * foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row)
		 * {
		 * $balance = $balance + $row['amount'];
		 * }
		 * return $balance;
		 *
		 */

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
