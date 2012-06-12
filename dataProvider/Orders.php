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
class Orders
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

    public function  addOrdersLabs($params)
    {


        foreach($params->labs as $lab){
            $foo = array();
            $foo['uid']             = $_SESSION['user']['id'];
            $foo['pid']             = $_SESSION['patient']['pid'];
            $foo['document_id'] = $params->document_id;
            $foo['labs']             = $lab->laboratories;
            $this->db->setSQL($this->db->sqlBind($foo,'patient_orders','I'));
            $this->db->execLog();
        }
    }

}





