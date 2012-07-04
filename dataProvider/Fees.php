<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Encounter.php
 * Date: 1/21/12
 * Time: 3:26 PM
 */
if(!isset($_SESSION)){
    session_name ("GaiaEHR");
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'].'/classes/dbHelper.php');
include_once($_SESSION['site']['root'].'/dataProvider/Patient.php');
include_once($_SESSION['site']['root'].'/dataProvider/User.php');
include_once($_SESSION['site']['root'].'/dataProvider/Encounter.php');


class Fees extends Encounter {
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

    function __construct()
    {
        $this->db = new dbHelper();
        $this->user = new User();
        $this->patient = new Patient();
        return;
    }


    public function getFilterEncountersBillingData(stdClass $params){

        $sql = "SELECT enc.eid,
                       enc.pid,
                       enc.prov_uid AS encounterProviderUid,
                       enc.start_date,
                       enc.billing_stage,
                       demo.title,
                       demo.fname,
                       demo.mname,
                       demo.lname,
                       demo.provider AS primaryProviderUid
                  FROM form_data_encounter AS enc
             LEFT JOIN form_data_demographics AS demo ON demo.pid = enc.pid
              ORDER BY enc.start_date ASC ";
        $this->db->setSQL($sql);
        $encounters = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){

            $row['primaryProvider'] = $row['primaryProviderUid'] == null ? 'None' : $this->user->getUserNameById($row['primaryProviderUid']);
            $row['encounterProvider'] = $row['encounterProviderUid'] == null ? 'None' : $this->user->getUserNameById($row['encounterProviderUid']);

            $row['patientName'] = $row['title'].' '.Person::fullname($row['fname'],$row['mname'],$row['lname']);
            $encounters[] = $row;
        }
        $total = count($encounters);
        $encounters = array_slice($encounters, $params->start, $params->limit);
        return array('totals' => $total, 'encounters' => $encounters);

    }

    public function getEncountersByPayment(stdClass $params){

        $sql = "SELECT enc.eid,
                       enc.pid,
                       enc.prov_uid AS encounterProviderUid,
                       enc.start_date,
                       enc.billing_stage,
                       demo.title,
                       demo.fname,
                       demo.mname,
                       demo.lname,
                       demo.provider AS primaryProviderUid
                  FROM form_data_encounter AS enc
             LEFT JOIN form_data_demographics AS demo ON demo.pid = enc.pid
              ORDER BY enc.start_date ASC ";
        $this->db->setSQL($sql);
        $encounters = array();
        foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){

            $row['primaryProvider'] = $row['primaryProviderUid'] == null ? 'None' : $this->user->getUserNameById($row['primaryProviderUid']);
            $row['encounterProvider'] = $row['encounterProviderUid'] == null ? 'None' : $this->user->getUserNameById($row['encounterProviderUid']);

            $row['patientName'] = $row['title'].' '.Person::fullname($row['fname'],$row['mname'],$row['lname']);
            $encounters[] = $row;
        }
        $total = count($encounters);
        $encounters = array_slice($encounters, $params->start, $params->limit);
        return array('totals' => $total, 'encounters' => $encounters);

    }

	public function addPayment(stdClass $params){
		$data = get_object_vars($params);
		$this->db->setSQL($this->db->sqlBind($data, "payment_transactions", "I"));
		$this->db->execLog();
		if($this->db->lastInsertId == 0){
			return array('success' => false);
		}else{
			return array('success' => true);
		}
	}

	public function getPatientBalance(stdClass $params)
	{

		return $this->getPatientBalanceByPid($params->pid);
	}

    public function getPatientBalanceByPid($pid)
	{
		$balance = 0;
		$this->db->setSQL("SELECT * FROM payment_transactions WHERE payer_id = '$pid'");

		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row){

			$balance = $balance + $row['amount'];


		}
		return $balance;
	}
}



//$params = new stdClass();
//
//$p = new Fees($params);
//echo '<pre>';
//print_r($p->getEncountersByPayment($params));

