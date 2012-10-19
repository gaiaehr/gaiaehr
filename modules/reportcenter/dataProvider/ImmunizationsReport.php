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

class ImmunizationsReport extends Reports
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

	public function createImmunizationsReport(stdClass $params)
	{
		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
		$html = "<br><h1>Immunization Registry ($params->from - $params->to )</h1>";
		$html2 = "";
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"8\" style=\"font-weight: bold;\">" . i18nRouter::t("immunization_registry") . "</th>
            </tr>
            <tr>
               <td colspan=\"2\">" . i18nRouter::t("patient") . "</td>
               <td>" . i18nRouter::t("id") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("immunization_code") . "</td>
               <td colspan=\"2\">" . i18nRouter::t("immunization_name") . "</td>
               <td>" . i18nRouter::t("administered") . "</td>
            </tr>";
		$html2 = $this -> htmlImmunizationList($params, $html2);
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

	public function getImmunizationsFromAndToAndImmu($from, $to, $immu = null)
	{
		$sql = " SELECT *
	               FROM patient_immunizations
	              WHERE create_date BETWEEN '$from 00:00:00' AND '$to 23:59:59'";
		if (isset($immu) && $immu != '')
			$sql .= " AND immunization_id = '$immu'";
		$this -> db -> setSQL($sql);
		return $this -> db -> fetchRecords(PDO::FETCH_ASSOC);
	}

	public function htmlImmunizationList($params, $html)
	{
		foreach ($this->getImmunizationsFromAndToAndImmu($params->from,$params->to,$params->immu) AS $data)
		{
			$html .= "
	            <tr>
					<td colspan=\"2\">" . $this -> patient -> getPatientFullNameByPid($data['pid']) . "</td>
					<td>" . $data['pid'] . "</td>
					<td colspan=\"2\">" . $data['immunization_id'] . "</td>
					<td colspan=\"2\">" . $data['immunization_name'] . "</td>
					<td>" . $data['administered_date'] . "</td>
				</tr>";
		}
		return $html;
	}

}

//$e = new ImmunizationsReport();
//$params = new stdClass();
//$params->from ='2010-03-08';
//$params->to ='2013-03-08';
//echo '<pre>';
//echo '<pre>';
//print_r($e->htmlImmunizationList($params,''));
