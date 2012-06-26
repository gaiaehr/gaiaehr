<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Encounter.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Facilities.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Documents.php');
class Prescriptions
{

	function __construct()
	{
		$this->db       = new dbHelper();
		$this->user     = new User();
		$this->patient  = new Patient();
		$this->services = new Services();
		$this->facility = new Facilities();
		$this->documents = new Documents();
		return;
	}

    public function addDocumentsPatientInfo($params)
    {
        $foo = array();
        $foo['pid']             = $_SESSION['patient']['pid'];
        $foo['uid']             = $_SESSION['user']['id'];
        $foo['created_date']    = date('Y-m-d H:i:s');
        $foo['document_id'] = $params->document_id;
        $this->db->setSQL($this->db->sqlBind($foo,'patient_prescriptions','I'));
        $this->db->execLog();
        $prescription_id = $this->db->lastInsertId;
        foreach($params->medications as $med){
            $foo = array();
            $foo['pid']             = $_SESSION['patient']['pid'];
            $foo['eid']             = $params->eid;
            $foo['prescription_id'] = $prescription_id;
            $foo['medication'] = $med->medication;
            $foo['medication_id'] = $med->medication_id;
            $foo['route'] = $med->route;
            $foo['dispense'] = $med->dispense;
            $foo['dose'] = $med->dose;
            $foo['dose_mg'] = $med->dose_mg;
            $foo['prescription_often'] = $med->prescription_often;
            $foo['prescription_when'] = $med->prescription_when;
            $foo['refill'] = $med->refill;
            $foo['take_pills'] = $med->take_pills;
            $foo['type'] = $med->type;
            $foo['begin_date'] = $med->begin_date;
            $foo['end_date'] = $med->end_date;
            $this->db->setSQL($this->db->sqlBind($foo,'patient_medications','I'));
            $this->db->execLog();
        }
    }

}





