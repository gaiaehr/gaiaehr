<?php
/**
 * Created by JetBrains PhpStorm.
 * User: orodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ('Reports.php');
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
include_once ($_SESSION['root'] . '/dataProvider/Patient.php');
include_once ($_SESSION['root'] . '/dataProvider/User.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');
include_once ($_SESSION['root'] . '/dataProvider/i18nRouter.php');

class Rx extends Reports
{
	private $db;
	private $user;
	private $patient;
	private $encounter;

	/*
	 * The first thing all classes do, the construct.
	 */
	function __construct()
	{
		parent::__construct();
		$this -> db = new dbHelper();
		$this -> user = new User();
		$this -> patient = new Patient();
		$this -> encounter = new Encounter();

		return;
	}

	public function createPrescriptionsDispensations(stdClass $params)
	{
		ob_end_clean();
		$Url = $this -> ReportBuilder($params->html, 10);
		return array(
			'success' => true,
			'url' => $Url
		);
	}

	public function getPrescriptionsFromAndToAndPid(stdClass $params)
	{
		$from = $params->from;
		$to = $params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
		$drug = $params->drug;
		$pid = $params->pid;
		$alldata = '';
		$sql = " SELECT *
	               FROM patient_prescriptions
	              WHERE created_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if (isset($pid) && $pid != '')
			$sql .= " AND pid = '$pid'";
		$this -> db -> setSQL($sql);
		foreach ($this->db->fetchRecords(PDO::FETCH_ASSOC) as $key => $data)
		{
			$id = $data['id'];
			$sql = " SELECT *
		   	           FROM patient_medications
		   	          WHERE prescription_id = '$id'";
			if (isset($drug) && $drug != '')
				$sql .= " AND medication_id = '$drug'";
			$this -> db -> setSQL($sql);
			$alldata[$key] = $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
		}
		$records = array();
		foreach($alldata as $data){
			foreach($data as $key=>$rec){
				$records[$key]=$rec;
			}
		}
		foreach($records as $num=>$rec){
				$records[$num]['fullname']=$this->patient->getPatientFullNameByPid($rec['pid']);
				$records[$num]['instructions']=($rec['prescription_often'].' '.$rec['prescription_when']);
		}
		return $records;
	}

}

//$e = new Rx();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->createPrescriptionsDispensations($params));
