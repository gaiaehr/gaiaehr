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
		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
		$html = "<br><h1>Clinical ($params->from - $params->to )</h1>";
		$html2 = "";
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"9\" style=\"font-weight: bold;\">" . i18nRouter::t("clinical") . "</th>
            </tr>
            <tr>
               <td colspan=\"2\">" . i18nRouter::t("patient") . "</td>
               <td>" . i18nRouter::t("pid") . "</td>
               <td>" . i18nRouter::t("age") . "</td>
               <td>" . i18nRouter::t("gender") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("race") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("ethnicity") . "</td>
            </tr>";
		$html2 = $this -> htmlClinicalList($params, $html2);
		$html .= $html2;
		$html .= "</table>";
		ob_end_clean();
		$Url = $this -> ReportBuilder($html, 10);
		return array(
			'success' => true,
			'html' => $html,
			'url' => $Url
		);
	}

	public function getClinical($pid, $sex, $race, $from, $to, $age_from = null, $age_to = null, $ethnicity, $icd)
	{
		$sql = " SELECT *
	               FROM form_data_demographics
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

		foreach ($data as  $key =>$data1)
		{

			$age = $this->patient->getPatientAgeByDOB($data1['DOB']);

			print 'from'.$age_from;
			print 'to'.$age_to;
			print 'age'.$age['DMY']['years'];

			if($age_from < $age['DMY']['years'] && $age_to < $age['DMY']['years'] ){
				unset($data[$key]);
			}
			else if($age_from <= $age['DMY']['years']){
				unset($data[$key]);
			}
			else if($age_to <= $age['DMY']['years']){
				unset($data[$key]);
			}


		}

		return $data;
	}

	public function htmlClinicalList($params, $html)
	{
		foreach ($this->getClinical($params->pid,
									$params->sex,
									$params->race,
									$params->from,
									$params->to,
									$params->age_from,
									$params->age_to,
									$params->ethnicity,
									$params->icd) AS $data)
		{
			$age               = $this->patient->getPatientAgeByDOB($data['DOB']);
			$html .= "
	            <tr>
					<td colspan=\"2\">" . $this -> patient -> getPatientFullNameByPid($data['pid']) . "</td>
					<td>" . $data['pid'] . "</td>
					<td>" . $age['DMY']['years'] . "</td>
					<td>" . $data['sex'] . "</td>
					<td colspan=\"2\">" . $data['race'] . "</td>
					<td colspan=\"2\">" . $data['ethnicity']. "</td>
				</tr>";
		}
		return $html;
	}

}

//$e = new Clinical();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//print_r($e->getClinical('','','','2010-03-08','2013-03-08',3,15,'',''));
