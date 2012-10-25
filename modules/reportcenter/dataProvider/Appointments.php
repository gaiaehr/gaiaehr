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
		$html = "<br><h1>Appoinments ($params->from - $params->to )</h1>";
		$html2 = "";
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"11\" style=\"font-weight: bold;\">" . i18nRouter::t("appoinments") . "</th>
            </tr>
            <tr>
               <td colspan=\"2\">" . i18nRouter::t("provider") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("date") . "</td>
               <td>" . i18nRouter::t("time") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("patient") . "</td>
               <td>" . i18nRouter::t("id") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("type") . "</td>
               <td>" . i18nRouter::t("note") . "</td>
            </tr>";
		$html2 = $this -> htmlAppointmentsList($params, $html2);
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

	public function getAppointments($from, $to, $facility, $provider)
	{
		$alldata = '';
		$sql = " SELECT *
	               FROM calendar_events
	              WHERE start BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if (isset($facility) && $facility != '')
			$sql .= " AND facility = '$facility'";
		if (isset($provider) && $provider != '')
			$sql .= " AND user_id = '$provider'";
		$this -> db -> setSQL($sql);

		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function htmlAppointmentsList($params, $html)
	{
		foreach ($this->getAppointments($params->from,$params->to,$params->facility,$params->provider) AS $data)
		{
			$cat= $this->getCalendarCategories($data['category']);
			$html .= "
	            <tr>
					<td colspan=\"2\">" . $this->user->getUserNameById($data['user_id']) . "</td>
					<td colspan=\"2\">" . date('m-d-Y', strtotime($data['start'])). "</td>
					<td>" . date('h:i:s', strtotime($data['start'])). "</td>
					<td colspan=\"2\">" .$this -> patient -> getPatientFullNameByPid($data['patient_id']). "</td>
					<td>" . $data['patient_id'] . "</td>
					<td colspan=\"2\">" . $cat['catname'] . "</td>
					<td>" . $data['notes'] . "</td>
				</tr>";

		}
		return $html;
	}
	public function getCalendarCategories($category)
	{
		$this -> db -> setSQL("SELECT catname
		                       FROM calendar_categories
		                       WHERE catid ='$category'");
		return $this -> db -> fetchRecord(PDO::FETCH_ASSOC);
	}

}

//$e = new Appointments();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->getCalendarCategories(1));
