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

include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/Encounter.php');

class Fees {
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

	private $enc;

	function __construct(){
		// Declare all the variables that we are gone to use
		// within the class.
		$this->db = new MatchaHelper();
		$this->user = new User();
		$this->patient = new Patient();
		$this->enc = new Encounter();
		return;
	}

	/**
	 * Function: getFilterEncountersBillingData
	 * The first call to populate the dataGrid on the Billing panel
	 * also it will be used to filter the data by passing parameters
	 * from extjs.
	 */
	public function getFilterEncountersBillingData(stdClass $params){

		$encounters = array();
		$whereClause = '';

		// Look between service date
		if(isset($params->datefrom) && isset($params->dateto))
			$whereClause .= chr(13) . " AND encounters.service_date BETWEEN '" . substr($params->datefrom, 0, -9) . " 00:00:00' AND '" . substr($params->dateto, 0, -9) . " 23:00:00'";

		// Look for a specific patient
		if(isset($params->patient))
			$whereClause .= chr(13) . " AND encounters.pid = '" . $params->patient . "'";

		// Look for a specific provider
		if(isset($params->provider) && $params->provider != 'all')
			$whereClause .= chr(13) . " AND encounters.provider_uid = '" . $params->patient . "'";

		// Look for the primary insurance
		//		if (isset($params->insurance) && $params->insurance != '1')
		//			$whereClause .= chr(13) . " AND patient.primary_insurance_provider = '" . $params->insurance . "'";

		// Look for pastDue dates
		// TODO: Consider the payment on the SQL statement
		if(isset($params->pastDue))
			$whereClause .= chr(13) . " AND DATEDIFF(NOW(),encounters.service_date) >= " . $params->pastDue;

		// Eliminate the first 6 characters of the where clause
		// this to eliminate and extra AND from the SQL statement
		$whereClause = substr($whereClause, 6);

		// If the whereClause variable is used go ahead and
		// and add the where command.
		if($whereClause != '')
			$whereClause = 'WHERE ' . $whereClause;

		$sql = "SELECT encounters.eid,
					   encounters.pid,
					   If(encounters.provider_uid Is Null, 'None', encounters.provider_uid) As encounterProviderUid,
					   If(patient.provider Is Null, 'None', patient.provider) As primaryProviderUid,
					   encounters.service_date,
					   encounters.billing_stage,
					   patient.title,
					   patient.fname,
					   patient.mname,
					   patient.lname,
					   encounters.close_date,
					   encounters.supervisor_uid,
					   encounters.provider_uid,
					   encounters.open_uid
				  FROM encounters
			 LEFT JOIN patient ON patient.pid = encounters.pid
				       $whereClause
			  ORDER BY encounters.service_date";

		$this->db->setSQL($sql);
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$row['patientName'] = $row['title'] . ' ' . Person::fullname($row['fname'], $row['mname'], $row['lname']);
			$encounters[] = $row;
		}
		$total = count($encounters);
		$encounters = array_slice($encounters, $params->start, $params->limit);
		return array('totals' => $total, 'encounters' => $encounters);

	}

	/**
	 * Function: getEncountersByPayment
	 */
	public function getEncountersByPayment(stdClass $params){

		$encounters = array();

		$sql = "SELECT
					encounters.eid,
					encounters.pid,
					If(encounters.provider_uid Is Null, 'None', encounters.provider_uid) As encounterProviderUid,
					If(patient.provider Is Null, 'None', patient.provider) As primaryProviderUid,
					encounters.service_date,
					encounters.billing_stage,
					patient.primary_insurance_provider,
					patient.title,
					patient.fname,
					patient.mname,
					patient.lname,
					encounters.close_date,
					encounters.supervisor_uid,
					encounters.provider_uid,
					encounters.open_uid
				FROM
					encounters
				LEFT JOIN
					patient
				ON patient.pid = encounters.pid
				ORDER BY
  					encounters.service_date";

		$this->db->setSQL($sql);

		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$row['patientName'] = $row['title'] . ' ' . Person::fullname($row['fname'], $row['mname'], $row['lname']);
			$encounters[] = $row;
		}
		$total = count($encounters);
		$encounters = array_slice($encounters, $params->start, $params->limit);
		return array('totals' => $total, 'encounters' => $encounters);

	}

	/**
	 * Function: getPaymentsBySearch
	 */
	public function getPaymentsBySearch(stdClass $params){

		$patientPayments = array();
		$whereClause = '';

		// Look between date the payment was created
		if(isset($params->datefrom) && isset($params->dateto))
			$whereClause .= chr(13) . " AND date_created BETWEEN '" . substr($params->datefrom, 0, -9) . " 00:00:00' AND '" . substr($params->dateto, 0, -9) . " 23:00:00'";

		// Look for the Paying Entity
		if(isset($params->payingEntityCombo))
			$whereClause .= chr(13) . " AND paying_entity = '" . $params->payingEntityCombo . "'";

		// Look for the Patient
		if(isset($params->patientSearch))
			$whereClause .= chr(13) . " AND paying_entity = '" . $params->payingEntityCombo . "'";

		// If the whereClause variable is used go ahead and
		// and add the where command.
		if($whereClause != '')
			$whereClause = 'WHERE ' . $whereClause;

		$sql = "SELECT
					patient.fname,
					patient.mname,
					patient.lname,
					patient.pid,
					payments.dtime,
					payments.encounter,
					payments.user,
					payments.method,
					payments.source,
					payments.amount1,
					payments.amount2,
					payments.posted1,
					payments.posted2
				FROM
					payments
				INNER JOIN
					patient
				ON
					patient.pid = payments.pid
				$whereClause";
		$this->db->setSQL($sql);

		// Loop through the results.
		// in here you can do calculations or other stuff.
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){
			$patientPayments[] = $row;
		}

		$total = count($patientPayments);

		// Return the results back to ExtJS.
		return array('totals' => $total, 'rows' => $patientPayments);
	}

	/**
	 * Function: addPayment
	 */
	public function addPayment(stdClass $params){
		$data = get_object_vars($params);
		$this->db->setSQL($this->db->sqlBind($data, "payment_transactions", "I"));
		$this->db->execLog();
		if($this->db->lastInsertId == 0){
			return (array)$success = 'false';
		} else{
			return (array)$success = 'true';
		}
	}

	/**
	 * Function: getPatientBalance
	 */
	public function getPatientBalance(stdClass $params){
		return $this->getPatientBalanceByPid($params->pid);
	}

	/**
	 * Function: getPatientBalanceByPid
	 */
	public function getPatientBalanceByPid($pid){

		$this->db->setSQL("SELECT SUM(amount) as balance FROM payment_transactions WHERE payer_id = '$pid'");
		$balance_total = $this->db->fetchRecord();

		return $balance_total['balance'];
	}

}
