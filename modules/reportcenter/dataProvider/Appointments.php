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

class Appointments extends Reports
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

	public function CreateAppointmentsReport(stdClass $params)
	{
		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
		$html = "<br><h1>Clinical ($params->from - $params->to )</h1>";
		$html2 = "";
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"11\" style=\"font-weight: bold;\">" . i18nRouter::t("clinical") . "</th>
            </tr>
            <tr>
               <td colspan=\"2\">" . i18nRouter::t("patient") . "</td>
               <td>" . i18nRouter::t("pid") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("drug_name") . "</td>
               <td>" . i18nRouter::t("units") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("type") . "</td>
               <td colspan=\"3\">" . i18nRouter::t("instructed") . "</td>
            </tr>";
		$html2 = $this -> htmlAppointmentsList($params, $html2);
		$html .= $html2;
		$html .= "</table>";
		ob_end_clean();
		//$Url = $this -> ReportBuilder($html, 10);
		return array(
			'success' => true,
			'html' => $html,
			'url' => $Url
		);
	}

	public function getAppointmentsFromAndToAndPid($from, $to, $drug = null, $pid = null)
	{
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
		return $alldata;
	}

	public function htmlAppointmentsList($params, $html)
	{
		foreach ($this->getAppointmentsFromAndToAndPid($params->from,$params->to,$params->drug,$params->pid) AS $data)
		{
			foreach ($data as $data2)
			{
				$html .= "
		            <tr>
						<td colspan=\"2\">" . $this -> patient -> getPatientFullNameByPid($data2['pid']) . "</td>
						<td>" . $data2['pid'] . "</td>
						<td colspan=\"2\">" . $data2['medication'] . "</td>
						<td>" . $data2['take_pills'] . "</td>
						<td colspan=\"2\">" . $data2['type'] . "</td>
						<td colspan=\"3\">" . $data2['prescription_often'] . ' ' . $data2['prescription_when'] . "</td>
					</tr>";
			}
		}
		return $html;
	}

}

//$e = new Rx();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->createPrescriptionsDispensations($params));
