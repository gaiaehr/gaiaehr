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
include_once ($_SESSION['root'] . '/dataProvider/Fees.php');
include_once ($_SESSION['root'] . '/dataProvider/Encounter.php');
include_once ($_SESSION['root'] . '/dataProvider/i18nRouter.php');

class SuperBill extends Reports
{
	private $db;
	private $user;
	private $patient;
	private $fees;
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
		$this -> fees = new Fees();
		$this -> encounter = new Encounter();
		return;
	}

	public function CreateSuperBill(stdClass $params)
	{
		$params -> to = ($params -> to == '') ? date('Y-m-d') : $params -> to;
		$html = "<br><h1>Super Bill ($params->from - $params->to )</h1>";
		foreach ($this->encounter->getEncounterByDateFromToAndPatient($params->from,$params->to,$params->pid) AS $eData)
		{
			$html .= $this -> htmlSuperBill($eData);
			$html .= $this -> addCodes($eData['eid'], $eData['start_date'], $eData['prov_uid']);
		}
		ob_end_clean();

		$Url = $this -> ReportBuilder($html);
		return array(
			'success' => true,
			'html' => $html,
			'url' => $Url
		);
	}

	public function addCodes($eid, $date, $provider)
	{
		$codes = $this -> encounter -> getEncounterCodesByEid($eid);

		$html = '';
		$html .= "<table  border=\"0\" width=\"100%\">
            <tr>
               <th colspan=\"4\" style=\"font-weight: bold;\">" . i18nRouter::t("billing_information") . "</th>
            </tr>
            <tr>
               <td>" . i18nRouter::t("service_date") . "</td>
	           <td>" . i18nRouter::t("provider") . "</td>
	           <td>" . i18nRouter::t("code") . "</td>
	           <td>" . i18nRouter::t("fee") . "</td>
            </tr>";
		foreach ($codes as $code)
		{
			$html .= "<tr>
					<td>" . $date . "</td>
					<td>" . $provider . "</td>
					<td>" . $code['type'] . ': ' . $code['code'] . "</td>
					<td>" . $code['charge'] . "</td>
				</tr>";
		}
		$html .= "<hr></table>";

		return $html;
	}

	public function htmlSuperBill($params)
	{
		$html = '';
		$html .= "<table border=\"0\" width=\"100%\" >
                 <tr>
                    <th colspan=\"3\" style=\"font-weight: bold;\">" . i18nRouter::t("patient") . "</th>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("name") . ': ' . $params['title'] . ' ' . $params['fname'] . ' ' . $params['mname'] . ' ' . $params['lname'] . "</td>
                    <td>" . i18nRouter::t("sex") . ': ' . $params['sex'] . "</td>
                    <td>" . i18nRouter::t("emer_contact") . ': ' . $params['emer_contact'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("date_of_birth") . ': ' . date('m-d-Y', strtotime($params['DOB'])) . "</td>
                    <td>" . i18nRouter::t("occupation") . ': ' . $params['occupation'] . "</td>
                    <td>" . i18nRouter::t("emer_phone") . ': ' . $params['emer_phone'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("address") . ': ' . $params['address'] . ' ' . $params['city'] . ', ' . $params['state'] . ' ' . $params['zipcode'] . "</td>
                    <td>" . i18nRouter::t("social_security") . ': ' . $params['SS'] . "</td>
                    <td>" . i18nRouter::t("home_phone") . ': ' . $params['home_phone'] . "<br>" . i18nRouter::t("mobile_phone") . ': ' . $params['mobile_phone'] . "</td>

                 </tr>
                 <tr><td>
                 </td></tr>" . '</table>';
		$html .= "<table  border=\"0\" width=\"100%\">
                 <tr>
                    <th colspan=\"3\" style=\"font-weight: bold;\">" . i18nRouter::t("insurance_data") . " (" . i18nRouter::t("primary") . ")</th>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("provider") . ': ' . $params['primary_insurance_provider'] . "</td>
                    <td>" . i18nRouter::t("subscriber_name") . ': ' . $params['primary_subscriber_fname'] . ' ' . $params['primary_subscriber_mname'] . ' ' . $params['primary_subscriber_lname'] . "</td>
                    <td>" . i18nRouter::t("subscriber_employer") . ': ' . $params['primary_subscriber_employer'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("plan_name") . ': ' . $params['primary_plan_name'] . '<br>' . i18nRouter::t("effective_date") . ': ' . $params['primary_effective_date'] . "</td>
                    <td>" . i18nRouter::t("subscriber_address") . ': ' . $params['primary_subscriber_street'] . ' ' . $params['primary_subscriber_city'] . ', ' . $params['primary_subscriber_state'] . ' ' . $params['primary_subscriber_zip_code'] . "</td>
                    <td>" . i18nRouter::t("employer_address") . ': ' . $params['primary_subscriber_employer_street'] . ' ' . $params['primary_subscriber_employer_city'] . ', ' . $params['primary_subscriber_employer_state'] . ' ' . $params['primary_subscriber_employer_zip_code'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("group_number") . ': ' . $params['primary_group_number'] . "</td>
                    <td>" . i18nRouter::t("phone") . ': ' . $params['primary_subscriber_phone'] . "</td>
                    <td>" . i18nRouter::t("subscriber_employer") . ': ' . $params['primary_subscriber_employer'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("policy_number") . ': ' . $params['primary_policy_number'] . '<br>' . "</td>
                 </tr>
                 <tr>";
		if ($params['secondary_insurance_provider'] != '')
		{
			$html .= "<table  border=\"0\" width=\"100%\">
                 <tr>
                    <th colspan=\"3\" style=\"font-weight: bold;\">" . i18nRouter::t("insurance_data") . " (" . i18nRouter::t("secondary") . ")</th>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("provider") . ': ' . $params['secondary_insurance_provider'] . "</td>
                    <td>" . i18nRouter::t("subscriber_name") . ': ' . $params['secondary_subscriber_fname'] . ' ' . $params['secondary_subscriber_mname'] . ' ' . $params['secondary_subscriber_lname'] . "</td>
                    <td>" . i18nRouter::t("subscriber_employer") . ': ' . $params['secondary_subscriber_employer'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("plan_name") . ': ' . $params['secondary_plan_name'] . '<br>' . i18nRouter::t("effective_date") . ': ' . $params['secondary_effective_date'] . "</td>
                    <td>" . i18nRouter::t("subscriber_address") . ': ' . $params['secondary_subscriber_street'] . ' ' . $params['secondary_subscriber_city'] . ', ' . $params['secondary_subscriber_state'] . ' ' . $params['secondary_subscriber_zip_code'] . "</td>
                    <td>" . i18nRouter::t("employer_address") . ': ' . $params['secondary_subscriber_employer_street'] . ' ' . $params['secondary_subscriber_employer_city'] . ', ' . $params['secondary_subscriber_employer_state'] . ' ' . $params['secondary_subscriber_employer_zip_code'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("group_number") . ': ' . $params['secondary_group_number'] . "</td>
                    <td>" . i18nRouter::t("phone") . ': ' . $params['secondary_subscriber_phone'] . "</td>
                    <td>" . i18nRouter::t("subscriber_employer") . ': ' . $params['secondary_subscriber_employer'] . "</td>
                 </tr>
                 <tr>
                    <td>" . i18nRouter::t("policy_number") . ': ' . $params['secondary_policy_number'] . '<br>' . "</td>
                 </tr>
                 <tr>"; ;
		}
		if ($params['tertiary_insurance_provider'] != '')
		{
			$html .= "<table  border=\"0\" width=\"100%\">
                <tr>
                   <th colspan=\"3\" style=\"font-weight: bold;\">" . i18nRouter::t("insurance_data") . " (" . i18nRouter::t("tertiary") . ")</th>
                </tr>
                <tr>
                   <td>" . i18nRouter::t("provider") . ': ' . $params['tertiary_insurance_provider'] . "</td>
                   <td>" . i18nRouter::t("subscriber_name") . ': ' . $params['tertiary_subscriber_fname'] . ' ' . $params['tertiary_subscriber_mname'] . ' ' . $params['tertiary_subscriber_lname'] . "</td>
                   <td>" . i18nRouter::t("subscriber_employer") . ': ' . $params['tertiary_subscriber_employer'] . "</td>
                </tr>
                <tr>
                   <td>" . i18nRouter::t("plan_name") . ': ' . $params['tertiary_plan_name'] . '<br>' . i18nRouter::t("effective_date") . ': ' . $params['tertiary_effective_date'] . "</td>
                   <td>" . i18nRouter::t("subscriber_address") . ': ' . $params['tertiary_subscriber_street'] . ' ' . $params['tertiary_subscriber_city'] . ', ' . $params['tertiary_subscriber_state'] . ' ' . $params['tertiary_subscriber_zip_code'] . "</td>
                   <td>" . i18nRouter::t("employer_address") . ': ' . $params['tertiary_subscriber_employer_street'] . ' ' . $params['tertiary_subscriber_employer_city'] . ', ' . $params['tertiary_subscriber_employer_state'] . ' ' . $params['tertiary_subscriber_employer_zip_code'] . "</td>
                </tr>
                <tr>
                   <td>" . i18nRouter::t("group_number") . ': ' . $params['tertiary_group_number'] . "</td>
                   <td>" . i18nRouter::t("phone") . ': ' . $params['tertiary_subscriber_phone'] . "</td>
                   <td>" . i18nRouter::t("subscriber_employer") . ': ' . $params['tertiary_subscriber_employer'] . "</td>
                </tr>
                <tr>
                   <td>" . i18nRouter::t("policy_number") . ': ' . $params['tertiary_policy_number'] . '<br>' . "</td>
                </tr>";
		}
		$html .= "</table>";
		return $html;
	}

}

//$e = new SuperBill();
//$params = new stdClass();
//$params->pid = 1;
//echo '<pre>';
//print_r($e->CreateSuperBill($params));
