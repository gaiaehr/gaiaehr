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
include_once($_SESSION['site']['root'].'/dataProvider/Services.php');
include_once($_SESSION['site']['root'].'/dataProvider/PoolArea.php');
include_once($_SESSION['site']['root'].'/dataProvider/Medical.php');
include_once($_SESSION['site']['root'].'/dataProvider/PreventiveCare.php');


class VisitCheckout {
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
     * @var
     */
    private $eid;
	/**
	 * @var PoolArea
	 */
	private $poolArea;
	/**
	 * @var Medical
	 */
	private $medical;
	/**
	 * @var PreventiveCare
	 */
	private $preventiveCare;

    function __construct()
    {
        $this->db = new dbHelper();
        $this->user = new User();
        $this->patient = new Patient();
        return;
    }



}
//
//$params = new stdClass();
//$params->pid = 2;
//$params->date = '2012-06-25 10:48:00';
//
//$e = new Encounter();
//echo '<pre>';
//print_r($e->checkForAnOpenedEncounterByPid($params));
