<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
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

class ClientList extends Reports
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

	public function createClientList(stdClass $params)
	{
		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
		$html = "<br><h1>Patient List ($params->from - $params->to )</h1>";
		$html2 = "";
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"11\" style=\"font-weight: bold;\">" . i18nRouter::t("patient_list") . "</th>
            </tr>
            <tr>
               <td colspan=\"2\">" . i18nRouter::t("last_visit") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("patient") . "</td>
               <td>" . i18nRouter::t("id") . "</td>
               <td>" . i18nRouter::t("street") . "</td>
               <td>" . i18nRouter::t("city") . "</td>
               <td>" . i18nRouter::t("state") . "</td>
               <td>" . i18nRouter::t("zip") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("home_phone") . "</td>
            </tr>";
		$html2 = $this -> htmlPatientList($params, $html2);
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

	public function htmlPatientList($params, $html)
	{
		foreach ($this->encounter->getEncounterByDateFromToAndPatient($params->from,$params->to,$params->pid) AS $data)
		{

			$html .= "
	            <tr>
					<td colspan=\"2\">" . date('m-d-Y', strtotime($data['start_date'])) . "</td>
					<td colspan=\"2\">" . $data['title'] . $data['fname'] . ' ' . $data['mname'] . ' ' . $data['lname'] . "</td>
					<td>" . $data['pid'] . "</td>
					<td>" . $data['address'] . "</td>
					<td>" . $data['city'] . "</td>
					<td>" . $data['state'] . "</td>
					<td>" . $data['zipcode'] . "</td>
					<td colspan=\"2\">" . $data['home_phone'] . "</td>
				</tr>";
		}
		return $html;
	}

}

//$e = new ClientList();
//$params = new stdClass();
//echo '<pre>';
//print_r($e->htmlPatientList($params,''));
