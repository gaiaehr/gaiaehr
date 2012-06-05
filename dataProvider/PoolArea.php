<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Encounter.php
 * Date: 1/21/12
 * Time: 3:26 PM
 */
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/classes/Time.php');
class PoolArea
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
	/**
	 * @var Services
	 */
	private $services;

	function __construct()
	{
		$this->db      = new dbHelper();
		$this->user    = new User();
		$this->patient = new Patient();
		$this->services = new Services();
		return;
	}


	public function getPatientsArrivalLog(stdClass $params){


	}

	public function addPatientArrivalLog(stdClass $params){

		if($params->isNew){
			$params->pid = $this->patient->createNewPatientOnlyName($params->name);
			$this->checkInPatient($params);
		}else{
			$this->checkInPatient($params);
		}
		return;
	}

	public function updatePatientArrivalLog(stdClass $params){

	}

	public function removePatientArrivalLog(stdClass $params){

	}



	public function sendPatientToPoolArea(stdClass $params){

		$fo = $this->getCurrentPatientPoolAreaByPid($params->pid);
		$id = $fo['id'];
		$data['time_out'] = Time::getLocalTime();
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'U', "id='$id'"));
		$this->db->execLog();

		$data = array();
		$data['pid'] = $params->pid;
		$data['uid'] = $_SESSION['user']['id'];
		$data['time_in'] = Time::getLocalTime();
		$data['area_id'] = $params->sendTo;
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'I'));
		$this->db->execLog();
	}

	public function getPoolAreaPatients(stdClass $params){
		return $this->getPatientsByPoolAreaId($params->area_id, 1);
	}

	public function getActivePoolAreas(){
		$this->db->setSQL("SELECT * FROM pool_areas	WHERE active = '1'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	/******************************************************************************************************************/
	/******************************************************************************************************************/
	/******************************************************************************************************************/

	private function checkInPatient($params){
		$data['pid'] = $params->pid;
		$data['uid'] = $_SESSION['user']['id'];
		$data['time_in'] = Time::getLocalTime();
		$data['area_id'] = 1;
		$this->db->setSQL($this->db->sqlBind($data, 'patient_pools', 'I'));
		$this->db->execLog();
	}


	public function getCurrentPatientPoolAreaByPid($pid){
		$this->db->setSQL("SELECT *
							 FROM patient_pools
							WHERE pid = $pid
							  AND time_out IS NULL
						 ORDER BY id DESC");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}

	private function getPatientsByPoolAreaId($area_id, $in_queue){
		$this->db->setSQL("SELECT *
							 FROM patient_pools
							WHERE area_id = $area_id
							  AND time_out IS NULL
							  AND in_queue = '$in_queue'");
		$records = array();
		foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $patient){
			$patient['name'] = $this->patient->getPatientFullNameByPid($patient['pid']);
			$records[] = $patient;
		}

		return $records;
	}

}
//$e = new PoolArea();
//echo '<pre>';
//print_r($e->getCurrentPatientPoolAreaByPid(1));

