<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Encounter.php
 * Date: 1/21/12
 * Time: 3:26 PM
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/ACL.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/classes/Time.php');
class FloorPlans
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
		$this->db       = new dbHelper();
		$this->user     = new User();
		$this->acl      = new ACL();
		$this->patient  = new Patient();
		$this->services = new Services();
		return;
	}


	public function getFloorPlans(){
		$this->db->setSQL("SELECT * FROM floor_plans");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function createFloorPlan(stdClass $params){
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	public function updateFloorPlan(stdClass $params){
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans', 'U', array('id'=>$params->id)));
		$this->db->execLog();
		return $params;
	}

	public function getFloorPlanZones(stdClass $params){
		return $this->getFloorPlanZonesByFloorPlanId($params->floor_plan_id);
	}

	public function createFloorPlanZone(stdClass $params){
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans_zones', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	public function updateFloorPlanZone(stdClass $params){
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'floor_plans_zones', 'U', array('id'=>$params->id)));
		$this->db->execLog();
		return $params;
	}



	//******************************************************************************************************************
	//******************************************************************************************************************

	public function setPatientToZone($params){
		$params->uid = $_SESSION['user']['id'];
		$params->time_in = Time::getLocalTime();
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'patient_zone', 'I'));
		$this->db->execLog();
		$params->patientZoneId = $this->db->lastInsertId;
		return array('success' => true, 'data' => $params);
	}

	public function unSetPatientZoneByZoneId($zone_id){
		$data['time_out'] = Time::getLocalTime();
		$this->db->setSQL($this->db->sqlBind($data, 'patient_zone', 'u', array('id' => $zone_id)));
		$this->db->execLog();
	}

	public function getZonePatientDataByZoneId($zoneId){

	}


	//******************************************************************************************************************
	// private functions
	//******************************************************************************************************************

	private function getFloorPlanZonesByFloorPlanId($floor_plan_id){
		$this->db->setSQL("SELECT * FROM floor_plans_zones WHERE floor_plan_id = '$floor_plan_id'");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

}
//$e = new FloorPlans();
//echo '<pre>';
//print_r($e->getPatientsByPoolAreaAccess());
//print '<br><br>Session ----->>> <br><br>';
//print_r($_SESSION);

