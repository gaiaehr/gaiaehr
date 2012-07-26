<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File: Encounter.php
 * Date: 1/21/12
 * Time: 3:26 PM
 */
if(!isset($_SESSION)){
    session_name ('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}

include_once($_SESSION['site']['root'].'/classes/dbHelper.php');
include_once($_SESSION['site']['root'].'/dataProvider/Patient.php');
include_once($_SESSION['site']['root'].'/dataProvider/User.php');
include_once($_SESSION['site']['root'].'/dataProvider/Services.php');
include_once($_SESSION['site']['root'].'/dataProvider/PoolArea.php');
include_once($_SESSION['site']['root'].'/dataProvider/Medical.php');
include_once($_SESSION['site']['root'].'/dataProvider/Encounter.php');
include_once($_SESSION['site']['root'].'/dataProvider/PreventiveCare.php');


class Emergency {
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
        $this->db = new dbHelper();
        $this->user = new User();
        $this->patient = new Patient();
        $this->services = new Services();
        $this->poolArea = new PoolArea();
        $this->medical = new Medical();
        $this->encounter = new Encounter();
        $this->preventiveCare = new PreventiveCare();
        return;
    }

    public function createNewEmergency(){
	    $patient = $this->patient->createNewPatientOnlyName('EMERGENCY');
	    if($patient['success']){
		    $this->pid = $patient['patient']['pid'];
		    /**
		     * send new patient to the emergency pool area
		     */
		    $params = new stdClass();
		    $params->pid = $this->pid;
		    $params->priority = $this->priority;
		    $params->sendTo = 3;
		    $this->poolArea->sendPatientToPoolArea($params);
		    /**
		     * create new encounter
    	     */
		    $params = new stdClass();
		    $params->pid = $this->pid;
		    $params->brief_description = '***EMERGENCY***';
		    $params->visit_category = 'Emergency';
		    $params->priority = $this->priority;
		    $params->start_date = Time::getLocalTime();
		    $encounter = $this->encounter->createEncounter($params);
		    $this->eid = $encounter['encounter']->eid;
		    /**
		     * log the emergency
		     */
		    $this->logEmergency();
		    /*
		     * update patient first name to EMERGENCY- encounter id
		     */
		    $data['fname'] = 'EMERGENCY-'.$this->emergencyId;
		    $this->db->setSQL($this->db->sqlBind($data, 'form_data_demographics', 'U', array('pid' => $this->pid)));
			$this->db->execOnly();

		    return array('success'=>true, 'emergency' => array(
			    'pid' => $this->pid,
			    'eid' => $this->eid,
			    'name' => 'EMERGENCY-'.$this->emergencyId,
			    'priority' => $params->priority));
	    }
	    return array('success'=>false, 'error' => 'Unable to create emergency');
    }

	public function logEmergency(){
		$data['pid'] = $this->pid;
		$data['eid'] = $this->eid;
		$data['uid'] = $_SESSION['user']['id'];
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
