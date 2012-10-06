<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Fees.php');
include_once($_SESSION['site']['root'] . '/dataProvider/DocumentPDF.php');

include_once($_SESSION['site']['root'] . '/lib/tcpdf/config/lang/eng.php');
include_once($_SESSION['site']['root'] . '/dataProvider/i18nRouter.php');

class SuperBill
{
    /**
     * @var dbHelper
     */
    private $db;
    /**
     * @var user
     */
    private $user;
    /**
     * @var Patient
     */
    private $patient;

    private $fees;

    private $i18n;


//	private $dompdf;
    public $pdf;

    function __construct()
    {
        $this->db       = new dbHelper();
        $this->user     = new User();
        $this->patient  = new Patient();
        $this->fees = new Fees();
        $this->i18n = new i18nRouter();
        $this->pdf = new DocumentPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        return;
    }


    public function CreateSuperBill(stdClass $params){
        $html = '';
        foreach($this->getEncounterByDateFromToAndPatient($params->from,$params->to,$params->pid) as $rec=>$num) {
            $html .= $this->htmlSuperBill($num);
        }
        ob_end_clean();
	    //print $html;
        $this->PDFDocumentBuilder($html);
        return $html;
    }

    public function getEncounterByDateFromToAndPatient($from,$to,$pid = null)
    {
	    $sql = "SELECT form_data_encounter.pid,
                       form_data_encounter.eid,
                       form_data_encounter.start_date,
                       form_data_demographics.*
               	  FROM form_data_encounter
             LEFT JOIN form_data_demographics ON form_data_encounter.pid = form_data_demographics.pid
                 WHERE form_data_encounter.start_date BETWEEN '$from' AND '$to'";
	    if($pid == null) $sql .= "AND form_data_encounter.pid = '$pid'";
        $this->db->setSQL($sql);
        return $this->db->fetchRecords(PDO::FETCH_ASSOC);
    }


    public function PDFDocumentBuilder($html)
    {

        $this->pdf->SetCreator('TCPDF');
        $this->pdf->SetAuthor($_SESSION['user']['name']);
        $siteLogo = $_SESSION['site']['root'] .'/sites/'.$_SESSION['site']['site'].'/logo.jpg';
        $logo = (file_exists($siteLogo) ? $siteLogo : $_SESSION['site']['root'] .'/resources/images/logo.jpg');

	   // TODO: set from admin area
        $this->pdf->SetHeaderData(
	        $logo,
            '20',
            'Ernesto\'s Clinic',
            "Cond. Capital Center\nPDO Suite 205\nAve. Arterial Hostos 239                                                                                                                                   Tel: 787-787-7878\nCarolina PR. 00987                                                                                                                                         Fax: 787-787-7878");//need to be change
        $this->pdf->setHeaderFont(Array('helvetica', '', 14));
        $this->pdf->setFooterFont(Array('helvetica', '', 8));
        $this->pdf->SetDefaultMonospacedFont('courier');
        $this->pdf->SetMargins(15, 27, 15);
        $this->pdf->SetHeaderMargin(5);
        $this->pdf->SetFooterMargin(10);
        $this->pdf->SetFontSize(10);
        $this->pdf->SetAutoPageBreak(true, 25);
        $this->pdf->setFontSubsetting(true);
        $this->pdf->AddPage();
        $this->pdf->writeHTML($html, true, false, false, false, '');
        $this->pdf->Output('testing', 'i');
        $this->pdf->Close();
        return true;
    }

    public function htmlSuperBill($params){
        $html = '';
        $html .=
            "<table border=\"1\" >
                 <tr>
                    <th colspan=\"6\">".i18nRouter::t("patient_data")."</th>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("title")."</td>
                    <td>".i18nRouter::t("first_name")."</td>
                    <td>".i18nRouter::t("middle_name")."</td>
                    <td>".i18nRouter::t("last_name")."</td>
                    <td>".i18nRouter::t("sex")."</td>
                    <td>".i18nRouter::t("ss")."</td>
                 </tr>
                 <tr>
                    <td>".$params['title']."</td>
                    <td>".$params['fname']."</td>
                    <td>".$params['mname']."</td>
                    <td>".$params['lname']."</td>
                    <td>".$params['sex']."</td>
                    <td>".$params['SS']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("date_of_birth")."</td>
                    <td>".i18nRouter::t("street")."</td>
                    <td>".i18nRouter::t("city")."</td>
                    <td>".i18nRouter::t("state")."</td>
                    <td>".i18nRouter::t("zip")."</td>
                    <td>".i18nRouter::t("country")."</td>
                 </tr>
                 <tr>
                    <td>".$params['DOB']."</td>
                    <td>".$params['address']."</td>
                    <td>".$params['city']."</td>
                    <td>".$params['state']."</td>
                    <td>".$params['zipcode']."</td>
                    <td>".$params['country']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("occupation")."</td>
                    <td>".i18nRouter::t("home_phone")."</td>
                    <td>".i18nRouter::t("mobile_phone")."</td>
                    <td>".i18nRouter::t("emer_phone")."</td>
                    <td>".i18nRouter::t("emer_contact")."</td>
                    <td>".i18nRouter::t("allow_email")."</td>
                 </tr>
                 <tr>
                    <td>".$params['occupation']."</td>
                    <td>".$params['home_phone']."</td>
                    <td>".$params['mobile_phone']."</td>
                    <td>".$params['lname']."</td>
                    <td>".$params['sex']."</td>
                    <td>".$params['SS']."</td>
                 </tr>
                 <tr>
                    <td colspan=\"2\">".i18nRouter::t("allow_voice_message")."</td>
                    <td colspan=\"2\">".i18nRouter::t("allow_mail_message")."</td>
                    <td colspan=\"2\">".i18nRouter::t("allow_leave_message")."</td>
                 </tr>
                <tr>
                    <td colspan=\"2\">".$params['allow_voice_msg']."</td>
                    <td colspan=\"2\">".$params['allow_mail_msg']."</td>
                    <td colspan=\"2\">".$params['allow_leave_msg']."</td>
                 </tr>".
                '</table>'
        ;
        // INSURANCE DATA _~_~_~_~_~_~__~~
        $html .=
            "<table  border=\"1\">
                 <tr>
                    <th colspan=\"6\">".i18nRouter::t("insurance_data")." (".i18nRouter::t("primary").")</th>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("provider")."</td>
                    <td>".i18nRouter::t("plan_name")."</td>
                    <td>".i18nRouter::t("policy_number")."</td>
                    <td>".i18nRouter::t("group_number")."</td>
                    <td>".i18nRouter::t("subscriber_first_name")."</td>
                    <td>".i18nRouter::t("subscriber_middle_name")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_insurance_provider']."</td>
                    <td>".$params['primary_plan_name']."</td>
                    <td>".$params['primary_policy_number']."</td>
                    <td>".$params['primary_group_number']."</td>
                    <td>".$params['primary_subscriber_fname']."</td>
                    <td>".$params['primary_subscriber_mname']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_last_name")."</td>
                    <td>".i18nRouter::t("subscriber_relationship")."</td>
                    <td>".i18nRouter::t("subscriber_ss")."</td>
                    <td>".i18nRouter::t("subscriber_date_of_birth")."</td>
                    <td>".i18nRouter::t("subscriber_phone")."</td>
                    <td>".i18nRouter::t("subscriber_address")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_subscriber_lname']."</td>
                    <td>".$params['primary_subscriber_relationship']."</td>
                    <td>".$params['city']."</td>
                    <td>".$params['state']."</td>
                    <td>".$params['primary_subscriber_phone']."</td>
                    <td>".$params['primary_subscriber_street']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_zip")."</td>
                    <td>".i18nRouter::t("subscriber_city")."</td>
                    <td>".i18nRouter::t("subscriber_state")."</td>
                    <td>".i18nRouter::t("subscriber_country")."</td>
                    <td>".i18nRouter::t("subscriber_employer")."</td>
                    <td>".i18nRouter::t("subscriber_employer_street")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_subscriber_zip_code']."</td>
                    <td>".$params['primary_subscriber_city']."</td>
                    <td>".$params['primary_subscriber_state']."</td>
                    <td>".$params['primary_subscriber_country']."</td>
                    <td>".$params['primary_subscriber_employer']."</td>
                    <td>".$params['primary_subscriber_employer_city']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_employer_city")."</td>
                    <td>".i18nRouter::t("subscriber_employer_zip")."</td>
                    <td>".i18nRouter::t("subscriber_employer_state")."</td>
                    <td colspan=\"3\">".i18nRouter::t("subscriber_employer_country")."</td>
                 </tr>
                 <tr>
                    <td>".$params['primary_subscriber_employer_city']."</td>
                    <td>".$params['primary_subscriber_employer_zip_code']."</td>
                    <td>".$params['primary_subscriber_employer_state']."</td>
                    <td colspan=\"3\">".$params['primary_subscriber_employer_country']."</td>
                 </tr>";
        if(isset($params['secondary_insurance_provider'])){
            $html .=
                "<tr>
                    <th colspan=\"6\">".i18nRouter::t("secondary")."</th>
                </tr>
                <tr>
                    <td>".i18nRouter::t("provider")."</td>
                    <td>".i18nRouter::t("plan_name")."</td>
                    <td>".i18nRouter::t("policy_number")."</td>
                    <td>".i18nRouter::t("group_number")."</td>
                    <td>".i18nRouter::t("subscriber_first_name")."</td>
                    <td>".i18nRouter::t("subscriber_middle_name")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_insurance_provider']."</td>
                    <td>".$params['secondary_plan_name']."</td>
                    <td>".$params['secondary_policy_number']."</td>
                    <td>".$params['secondary_group_number']."</td>
                    <td>".$params['secondary_subscriber_fname']."</td>
                    <td>".$params['secondary_subscriber_mname']."</td>
                </tr>
                <tr>
                    <td>".i18nRouter::t("subscriber_last_name")."</td>
                    <td>".i18nRouter::t("subscriber_relationship")."</td>
                    <td>".i18nRouter::t("subscriber_ss")."</td>
                    <td>".i18nRouter::t("subscriber_date_of_birth")."</td>
                    <td>".i18nRouter::t("subscriber_phone")."</td>
                    <td>".i18nRouter::t("subscriber_address")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_subscriber_lname']."</td>
                    <td>".$params['secondary_subscriber_relationship']."</td>
                    <td>".$params['secondary_subscriber_city']."</td>
                    <td>".$params['secondary_subscriber_state']."</td>
                    <td>".$params['secondary_subscriber_phone']."</td>
                    <td>".$params['secondary_subscriber_street']."</td>
                </tr>
                <tr>
                    <td>".i18nRouter::t("subscriber_zip")."</td>
                    <td>".i18nRouter::t("subscriber_city")."</td>
                    <td>".i18nRouter::t("subscriber_state")."</td>
                    <td>".i18nRouter::t("subscriber_country")."</td>
                    <td>".i18nRouter::t("subscriber_employer")."</td>
                    <td>".i18nRouter::t("subscriber_employer_street")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_subscriber_zip_code']."</td>
                    <td>".$params['secondary_subscriber_city']."</td>
                    <td>".$params['secondary_subscriber_state']."</td>
                    <td>".$params['secondary_subscriber_country']."</td>
                    <td>".$params['secondary_subscriber_employer']."</td>
                    <td>".$params['secondary_subscriber_employer_city']."</td>
                </tr>
                <tr>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_city")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_zip")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_state")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_country")."</td>
                </tr>
                <tr>
                    <td>".$params['secondary_subscriber_employer_city']."</td>
                    <td>".$params['secondary_subscriber_employer_zip_code']."</td>
                    <td>".$params['secondary_subscriber_employer_state']."</td>
                    <td>".$params['secondary_subscriber_employer_country']."</td>
                </tr>"
            ;
        }
        if(isset($params['tertiary_insurance_provider'])){
            $html .=
                "<tr>
                    <th>".i18nRouter::t("tertiary")."</th>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("provider")."</td>
                    <td>".i18nRouter::t("plan_name")."</td>
                    <td>".i18nRouter::t("policy_number")."</td>
                    <td>".i18nRouter::t("group_number")."</td>
                    <td>".i18nRouter::t("subscriber_first_name")."</td>
                    <td>".i18nRouter::t("subscriber_middle_name")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_insurance_provider']."</td>
                    <td>".$params['tertiary_plan_name']."</td>
                    <td>".$params['tertiary_policy_number']."</td>
                    <td>".$params['tertiary_group_number']."</td>
                    <td>".$params['tertiary_subscriber_fname']."</td>
                    <td>".$params['tertiary_subscriber_mname']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_last_name")."</td>
                    <td>".i18nRouter::t("subscriber_relationship")."</td>
                    <td>".i18nRouter::t("subscriber_ss")."</td>
                    <td>".i18nRouter::t("subscriber_date_of_birth")."</td>
                    <td>".i18nRouter::t("subscriber_phone")."</td>
                    <td>".i18nRouter::t("subscriber_address")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_subscriber_lname']."</td>
                    <td>".$params['tertiary_subscriber_relationship']."</td>
                    <td>".$params['tertiary_subscriber_city']."</td>
                    <td>".$params['tertiary_subscriber_state']."</td>
                    <td>".$params['tertiary_subscriber_phone']."</td>
                    <td>".$params['tertiary_subscriber_street']."</td>
                 </tr>
                 <tr>
                    <td>".i18nRouter::t("subscriber_zip")."</td>
                    <td>".i18nRouter::t("subscriber_city")."</td>
                    <td>".i18nRouter::t("subscriber_state")."</td>
                    <td>".i18nRouter::t("subscriber_country")."</td>
                    <td>".i18nRouter::t("subscriber_employer")."</td>
                    <td>".i18nRouter::t("subscriber_employer_street")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_subscriber_zip_code']."</td>
                    <td>".$params['tertiary_subscriber_city']."</td>
                    <td>".$params['tertiary_subscriber_state']."</td>
                    <td>".$params['tertiary_subscriber_country']."</td>
                    <td>".$params['tertiary_subscriber_employer']."</td>
                    <td>".$params['tertiary_subscriber_employer_city']."</td>
                 </tr>
                 <tr>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_city")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_zip")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_state")."</td>
                    <td colspan=\"2\">>".i18nRouter::t("subscriber_employer_country")."</td>
                 </tr>
                 <tr>
                    <td>".$params['tertiary_subscriber_employer_city']."</td>
                    <td>".$params['tertiary_subscriber_employer_zip_code']."</td>
                    <td>".$params['tertiary_subscriber_employer_state']."</td>
                    <td>".$params['tertiary_subscriber_employer_country']."</td>
                 </tr>";
        }
	    $html .="</table>";
        $html .=
	        "<table border=\"1\" >
	         <tr>
	            <th>".i18nRouter::t("billing_information")."</th>
	         </tr>
	         <tr>
	            <td>".i18nRouter::t("date")."</td>
	            <td>".i18nRouter::t("provider")."</td>
	            <td>".i18nRouter::t("code")."</td>
	            <td>".i18nRouter::t("fee")."</td>
	         </tr>
	         <tr>
	            <td>".$params['date']."</td>
	            <td>".$params['provider']."</td>
	            <td>".$params['code']."</td>
	            <td>".$params['fee']."</td>
	         </tr>

	         </table>
	    ---------------------------------------------------------------------------------------------------------------------------------------------------------
		";

        return $html;

    }

}




$e = new SuperBill();
$params = new stdClass();
$params->pid = 1;
$params->from = '2011-09-05';
$params->to = '2013-09-05';
echo '<pre>';
print_r($e->CreateSuperBill($params));