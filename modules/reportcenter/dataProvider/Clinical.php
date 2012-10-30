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

class Clinical extends Reports
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

	public function createClinicalReport(stdClass $params)
	{
//		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
//		$html = "<br><h1>Clinical ($params->from - $params->to )</h1>";
//		$html2 = "";
//		$html .= "<table  border=\"0\" width=\"100%\">
//            <tr>
//               <th colspan=\"9\" style=\"font-weight: bold;\">" . i18nRouter::t("clinical") . "</th>
//            </tr>
//            <tr>
//               <td colspan=\"2\">" . i18nRouter::t("patient") . "</td>
//               <td>" . i18nRouter::t("pid") . "</td>
//               <td>" . i18nRouter::t("age") . "</td>
//               <td>" . i18nRouter::t("gender") . "</td>
//               <td colspan=\"2\">" . i18nRouter::t("race") . "</td>
//               <td colspan=\"2\">" . i18nRouter::t("ethnicity") . "</td>
//            </tr>";
//		$html2 = $this -> htmlClinicalList($params, $html2);
//		$html .= $html2;
//		$html .= "</table>";
		ob_end_clean();
		$Url = $this -> ReportBuilder($params->html, 10);
		return array(
			'success' => true,
//			'html' => $html,
			'url' => $Url
		);
	}


	public function getClinicalList(stdClass $params)
	{
		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;

		$pid = $params->pid;
		$sex = $params->sex;
		$race= $params->race;
		$from=$params->from;
		$to= $params->to;
		$age_from= $params->age_from;
		$age_to = $params->age_to;
		$ethnicity= $params->ethnicity;


		$sql = " SELECT *
	               FROM patient_demographics
	              WHERE date_created BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if (isset($sex) && ($sex != '' && $sex != 'Both'))
			$sql .= " AND sex = '$sex'";
		if (isset($race) && $race != '')
			$sql .= " AND race = '$race'";
		if (isset($pid) && $pid != '')
			$sql .= " AND pid = '$pid'";
		if (isset($ethnicity) && $ethnicity != '')
			$sql .= " AND ethnicity= '$ethnicity'";

		$this -> db -> setSQL($sql);
		$data = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		$newarray = array();
		if(($age_from == null && $age_to == null) || ($age_from == '' && $age_to == '')){
			$age_from=0;
			$age_to=100;
		}
		foreach ($data as  $key =>$data1)
		{

			$age = $this->patient->getPatientAgeByDOB($data1['DOB']);
			$num =$age['DMY']['years'];
			if($age_from == null){
				if($age_to != null){
					if($age_to>=$num){
						array_push($newarray,$data[$key]);
					}
				}
			}
			else if($age_to == null){
				if($age_from != null){
					if($age_from<=$num){
						array_push($newarray,$data[$key]);
					}
				}
			}
			else if($age_from<=$num && $age_to>=$num ){
				array_push($newarray,$data[$key]);
			}
		}
		foreach ($newarray AS $num=>$rec)
		{
			$ethnicity= $this->getEthnicityByKey($rec['ethnicity']);
			$age = $this->patient->getPatientAgeByDOB($rec['DOB']);
			$newarray[$num]['fullname']=$this->patient->getPatientFullNameByPid($rec['pid']);
			$newarray[$num]['age']= ($age['DMY']['years']==0)?'months':$age['DMY']['years'];
			$newarray[$num]['ethnicity']= $ethnicity['option_name'];
		}


		return $newarray;
	}
	public function getEthnicityByKey($key){
		$sql = " SELECT option_name
	               FROM combo_lists_options
	              WHERE option_value ='$key'";
		$this -> db -> setSQL($sql);
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
	}


}

//$e = new Clinical();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//print_r($e->getClinical('','','','2010-03-08','2013-03-08',0,10,'',''));
