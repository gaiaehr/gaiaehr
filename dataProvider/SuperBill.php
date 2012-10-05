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
include_once($_SESSION['site']['root'] . '/lib/tcpdf/config/lang/eng.php');
include_once($_SESSION['site']['root'] . '/lib/tcpdf/tcpdf.php');
include_once($_SESSION['site']['root'] . '/dataProvider/i18nRouter.php');


class MYPDF extends TCPDF {

    //Page header


    // Page footer
    public function Footer() {

        $this->SetLineStyle( array( 'width' => 0.2,'color' => array(0, 0, 0) ) );
        $this->Line( 15, $this->getPageHeight() - 0.5 * 15 - 2,
            $this->getPageWidth() - 15, $this->getPageHeight() - 0.5 * 15 - 2 );
        $this->SetFont('times', '', 8 );
        $this->SetY( -0.5 * 15, true );
        $this->Cell( 15, 0, 'Created by GaiaEHR (Electronic Health Record) ');
    }
}



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
        $this->pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        //$this->dompdf = new DOMPDF();
        return;
    }



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getArrayWithTokensNeededByDocumentID($id)
    {
        $this->db->setSQL("SELECT title,
                                  body
                           	 FROM documents_templates
                            WHERE id = '$id' ");
        $record = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        $regex = '(\[\w*?\])';
        $body  = $record['body'];
        preg_match_all($regex, $body, $tokensfound);
        return $tokensfound;
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getAllPatientData($pid)
    {
        $this->db->setSQL("SELECT *
                           	 FROM form_data_demographics
                            WHERE pid = '$pid' ");
        $record = $this->db->fetchRecord(PDO::FETCH_ASSOC);
        return $record;
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function setArraySizeOfTokenArray($tokens)
    {
        $givingValuesToTokens = array();
        foreach($tokens as $tok) {
            array_push($givingValuesToTokens, '');
        }
        return $givingValuesToTokens;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////


    private function CreateSuperBill($pid){
        $patientData = $this->getAllPatientData($pid);

        return 1;
    }


    public function PDFDocumentBuilder($params)
    {
        $pid           = $params->pid;
        $from          = $params->from;
        $to            = $params->to;

        $this->pdf->SetCreator('TCPDF');
        $this->pdf->SetAuthor($_SESSION['user']['name']);
        $siteLogo = '../sites/'.$_SESSION['site']['site'].'/logo.jpg';
        $logo = (file_exists($siteLogo) ? $siteLogo : '../ui_app/logo.jpg');
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

        $html = $this->CreateSuperBill($pid);

        $this->pdf->writeHTML($html);
        $this->pdf->Output($path, 'F');
        $this->pdf->Close();
        return true;
    }

    public function htmlSuperBill(){
        $html = '';
        $html .=
            "<table>
                 <tr>
                    <th>".i18nRouter::t("patient_data")."</th>
                 </tr>
                 <tr>
                    <th>".i18nRouter::t("title")."</th>
                    <th>".i18nRouter::t("first_name")."</th>
                    <th>".i18nRouter::t("middle_name")."</th>
                    <th>".i18nRouter::t("last_name")."</th>
                    <th>".i18nRouter::t("sex")."</th>
                    <th>".i18nRouter::t("ss")."</th>
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
                    <th>".i18nRouter::t("date_of_birth")."</th>
                    <th>".i18nRouter::t("street")."</th>
                    <th>".i18nRouter::t("city")."</th>
                    <th>".i18nRouter::t("state")."</th>
                    <th>".i18nRouter::t("zip")."</th>
                    <th>".i18nRouter::t("country")."</th>
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
                    <th>".i18nRouter::t("occupation")."</th>
                    <th>".i18nRouter::t("home_phone")."</th>
                    <th>".i18nRouter::t("mobile_phone")."</th>
                    <th>".i18nRouter::t("emer_phone")."</th>
                    <th>".i18nRouter::t("emer_contact")."</th>
                    <th>".i18nRouter::t("allow_email")."</th>
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
                    <th>".i18nRouter::t("allow_voice_message")."</th>
                    <th>".i18nRouter::t("allow_mail_message")."</th>
                    <th>".i18nRouter::t("allow_leave_message")."</th>
                 </tr>".
                "<tr>
                    <td>".$params['allow_voice_msg']."</td>
                    <td>".$params['allow_mail_msg']."</td>
                    <td>".$params['allow_leave_msg']."</td>
                 </tr>".
                '</table>'
        ;
        // INSURANCE DATA _~_~_~_~_~_~__~~
        $html .=
            "<table>
                 <tr>
                    <th>".i18nRouter::t("insurance_data")."</th>
                 </tr>
                 <tr>
                    <th>".i18nRouter::t("primary")."</th>
                 </tr>
                 <tr>
                    <th>".i18nRouter::t("provider")."</th>
                    <th>".i18nRouter::t("plan_name")."</th>
                    <th>".i18nRouter::t("policy_number")."</th>
                    <th>".i18nRouter::t("group_number")."</th>
                    <th>".i18nRouter::t("subscriber_first_name")."</th>
                    <th>".i18nRouter::t("subscriber_middle_name")."</th>
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
                    <th>".i18nRouter::t("subscriber_last_name")."</th>
                    <th>".i18nRouter::t("subscriber_relationship")."</th>
             /  ////     <th>".i18nRouter::t("subscriber_ss")."</th>
             //  ///     <th>".i18nRouter::t("subscriber_date_of_birth")."</th>
                    <th>".i18nRouter::t("subscriber_phone")."</th>
                    <th>".i18nRouter::t("subscriber_address")."</th>
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
                    <th>".i18nRouter::t("subscriber_zip")."</th>
                    <th>".i18nRouter::t("subscriber_city")."</th>
                    <th>".i18nRouter::t("subscriber_state")."</th>
                    <th>".i18nRouter::t("subscriber_country")."</th>
                    <th>".i18nRouter::t("subscriber_employer")."</th>
                   ///// <th>".i18nRouter::t("subscriber_employer_street")."</th>
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
                    <th>".i18nRouter::t("subscriber_employer_city")."</th>
                    <th>".i18nRouter::t("subscriber_employer_zip")."</th>
                    <th>".i18nRouter::t("subscriber_employer_state")."</th>
                    <th>".i18nRouter::t("subscriber_employer_country")."</th>
                 </tr>".
                "<tr>
                    <td>".$params['primary_subscriber_employer_city']."</td>
                    <td>".$params['primary_subscriber_employer_zip_code']."</td>
                    <td>".$params['primary_subscriber_employer_state']."</td>
                    <td>".$params['primary_subscriber_employer_country']."</td>
                 </tr>"
        ;
        if(isset($params['secondary_insurance_provider'])){
            $html .=
                "<tr>
                    <th>".i18nRouter::t("secondary")."</th>
                 </tr>
                 <tr>
                    <th>".i18nRouter::t("provider")."</th>
                    <th>".i18nRouter::t("plan_name")."</th>
                    <th>".i18nRouter::t("policy_number")."</th>
                    <th>".i18nRouter::t("group_number")."</th>
                    <th>".i18nRouter::t("subscriber_first_name")."</th>
                    <th>".i18nRouter::t("subscriber_middle_name")."</th>
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
                    <th>".i18nRouter::t("subscriber_last_name")."</th>
                    <th>".i18nRouter::t("subscriber_relationship")."</th>
            /////     <th>".i18nRouter::t("subscriber_ss")."</th>
            /////     <th>".i18nRouter::t("subscriber_date_of_birth")."</th>
                    <th>".i18nRouter::t("subscriber_phone")."</th>
                    <th>".i18nRouter::t("subscriber_address")."</th>
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
                    <th>".i18nRouter::t("subscriber_zip")."</th>
                    <th>".i18nRouter::t("subscriber_city")."</th>
                    <th>".i18nRouter::t("subscriber_state")."</th>
                    <th>".i18nRouter::t("subscriber_country")."</th>
                    <th>".i18nRouter::t("subscriber_employer")."</th>
                   ///// <th>".i18nRouter::t("subscriber_employer_street")."</th>
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
                    <th>".i18nRouter::t("subscriber_employer_city")."</th>
                    <th>".i18nRouter::t("subscriber_employer_zip")."</th>
                    <th>".i18nRouter::t("subscriber_employer_state")."</th>
                    <th>".i18nRouter::t("subscriber_employer_country")."</th>
                 </tr>".
                    "<tr>
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
                    <th>".i18nRouter::t("provider")."</th>
                    <th>".i18nRouter::t("plan_name")."</th>
                    <th>".i18nRouter::t("policy_number")."</th>
                    <th>".i18nRouter::t("group_number")."</th>
                    <th>".i18nRouter::t("subscriber_first_name")."</th>
                    <th>".i18nRouter::t("subscriber_middle_name")."</th>
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
                    <th>".i18nRouter::t("subscriber_last_name")."</th>
                    <th>".i18nRouter::t("subscriber_relationship")."</th>
             /////     <th>".i18nRouter::t("subscriber_ss")."</th>
             ////     <th>".i18nRouter::t("subscriber_date_of_birth")."</th>
                    <th>".i18nRouter::t("subscriber_phone")."</th>
                    <th>".i18nRouter::t("subscriber_address")."</th>
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
                    <th>".i18nRouter::t("subscriber_zip")."</th>
                    <th>".i18nRouter::t("subscriber_city")."</th>
                    <th>".i18nRouter::t("subscriber_state")."</th>
                    <th>".i18nRouter::t("subscriber_country")."</th>
                    <th>".i18nRouter::t("subscriber_employer")."</th>
                   ///// <th>".i18nRouter::t("subscriber_employer_street")."</th>
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
                    <th>".i18nRouter::t("subscriber_employer_city")."</th>
                    <th>".i18nRouter::t("subscriber_employer_zip")."</th>
                    <th>".i18nRouter::t("subscriber_employer_state")."</th>
                    <th>".i18nRouter::t("subscriber_employer_country")."</th>
                 </tr>".
                    "<tr>
                    <td>".$params['tertiary_subscriber_employer_city']."</td>
                    <td>".$params['tertiary_subscriber_employer_zip_code']."</td>
                    <td>".$params['tertiary_subscriber_employer_state']."</td>
                    <td>".$params['tertiary_subscriber_employer_country']."</td>
                 </tr>".
                    '</table>'
            ;
        }
        $html .="<table>
	         <tr>
	            <th>".i18nRouter::t("billing_information")."</th>
	         </tr>
	         <tr>
	            <th>".i18nRouter::t("date")."</th>
	            <th>".i18nRouter::t("provider")."</th>
	            <th>".i18nRouter::t("code")."</th>
	            <th>".i18nRouter::t("fee")."</th>
	         </tr>
	         <tr>
	            <td>".$params['date']."</td>
	            <td>".$params['provider']."</td>
	            <td>".$params['code']."</td>
	            <td>".$params['fee']."</td>
	         </tr>

	    </table>"
        ;
        $html .="<table>
	         <tr>
	            <th>".i18nRouter::t("billing_information")."</th>
	         </tr>
	         <tr>
	            <th>".i18nRouter::t("date")."</th>
	            <th>".i18nRouter::t("provider")."</th>
	            <th>".i18nRouter::t("code")."</th>
	            <th>".i18nRouter::t("fee")."</th>
	         </tr>
	         <tr>
	            <td>".$params['date']."</td>
	            <td>".$params['provider']."</td>
	            <td>".$params['code']."</td>
	            <td>".$params['fee']."</td>
	         </tr>

	    </table>
	    ----------------------------------------------------------------------------------------------------------------------
	    ";

    }

}




//$e = new Documents();
//$params = new stdClass();
//$params->pid = 1;
//$params->documentId = 7;
//$e->PDFDocumentBuilder($params,'C:/wamp/www/gaiaehr/sites/default/patients/1/DoctorsNotes/1342132079.pdf');