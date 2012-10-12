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
include_once($_SESSION['root'] . '/classes/dbHelper.php');
include_once($_SESSION['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['root'] . '/dataProvider/User.php');
include_once($_SESSION['root'] . '/dataProvider/Encounter.php');
include_once($_SESSION['root'] . '/dataProvider/Fees.php');
include_once($_SESSION['root'] . '/dataProvider/PreventiveCare.php');
include_once($_SESSION['root'] . '/dataProvider/Medical.php');
include_once($_SESSION['root'] . '/dataProvider/Services.php');
include_once($_SESSION['root'] . '/dataProvider/Facilities.php');
include_once($_SESSION['root'] . '/dataProvider/DocumentPDF.php');

include_once($_SESSION['root'] . '/lib/tcpdf/config/lang/eng.php');
include_once($_SESSION['root'] . '/dataProvider/i18nRouter.php');



class Documents
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
	/**
	 * @var Services
	 */
	private $services;
	/**
	 * @var Facilities
	 */
	private $facility;

	private $fees;

	private $preventiveCare;

	private $medical;

	private $encounter;


	private $i18n;


//	private $dompdf;
    public $pdf;

	function __construct()
	{
		$this->db       = new dbHelper();
		$this->user     = new User();
		$this->patient  = new Patient();
		$this->services = new Services();
		$this->facility = new Facilities();
		$this->encounter = new Encounter();
		$this->medical = new Medical();
		$this->preventiveCare = new PreventiveCare();
		$this->fees = new Fees();
		$this->i18n = new i18nRouter();



        $this->pdf = new DocumentPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        //$this->dompdf = new DOMPDF();
		return;
	}

	public function createSuperBillDoc(stdClass $params)
	{
		return;
	}

	/**
	 * @param stdClass $params
	 * @return mixed
	 */
	public function createOrder(stdClass $params)
	{
		return;
	}

	/**
	 * @param stdClass $params
	 * @return mixed
	 */
	public function createReferral(stdClass $params)
	{
		return;
	}

	/**
	 * @param stdClass $params
	 * @return mixed
	 */
	public function createDrNotes(stdClass $params)
	{
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
	public function getTemplateBodyById($id)
	{
		$this->db->setSQL("SELECT title,
                                  body
                           	 FROM documents_templates
                            WHERE id = '$id' ");
		return $this->db->fetchRecord(PDO::FETCH_ASSOC);
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
	public function updateDocumentsTitle(stdClass $params)
	{
        $data = get_object_vars($params);
        $id = $data['id'];
        unset($data['id'],$data['date']);
        $this->db->setSQL($this->db->sqlBind($data, "patient_documents", "U", "id='$id'"));
        $this->db->execLog();
        return $params;
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
	public function get_PatientTokensData($pid, $allNeededInfo, $tokens)
	{
		$patientData       = $this->getAllPatientData($pid);
		$age               = $this->patient->getPatientAgeByDOB($patientData['DOB']);

		$patienInformation = array
		(
			'[PATIENT_NAME]'                      => $patientData['fname'],
			'[PATIENT_ID]'                        => $pid,
			'[PATIENT_FULL_NAME]'                 => $this->patient->getPatientFullNameByPid($patientData['pid']),
			'[PATIENT_LAST_NAME]'                 => $patientData['lname'],
            '[PATIENT_SEX]'                       => $patientData['sex'],
			'[PATIENT_BIRTHDATE]'                 => $patientData['DOB'],
			'[PATIENT_MARITAL_STATUS]'            => $patientData['marital_status'],
            '[PATIENT_SOCIAL_SECURITY]'           => $patientData['SS'],
            '[PATIENT_EXTERNAL_ID]'               => $patientData['pubpid'],
            '[PATIENT_DRIVERS_LICENSE]'           => $patientData['drivers_license'],
            '[PATIENT_ADDRESS]'                   => $patientData['address'],
            '[PATIENT_CITY]'                      => $patientData['city'],
            '[PATIENT_STATE]'                     => $patientData['state'],
            '[PATIENT_COUNTRY]'                   => $patientData['country'],
            '[PATIENT_ZIPCODE]'                   => $patientData['zipcode'],
			'[PATIENT_HOME_PHONE]'                => $patientData['home_phone'],
			'[PATIENT_MOBILE_PHONE]'              => $patientData['mobile_phone'],
			'[PATIENT_WORK_PHONE]'                => $patientData['work_phone'],
			'[PATIENT_EMAIL]'                     => $patientData['email'],
			'[PATIENT_MOTHERS_NAME]'              => $patientData['mothers_name'],
			'[PATIENT_GUARDIANS_NAME]'            => $patientData['guardians_name'],
			'[PATIENT_EMERGENCY_CONTACT]'         => $patientData['emer_contact'],
			'[PATIENT_EMERGENCY_PHONE]'           => $patientData['emer_phone'],
			'[PATIENT_PROVIDER]'                  => $this->user->getUserFullNameById($patientData['provider']),
			'[PATIENT_PHARMACY]'                  => $patientData['pharmacy'],
			'[PATIENT_AGE]'                       => $age['DMY']['years'],
			'[PATIENT_OCCUPATION]'                => $patientData['occupation'],
			'[PATIENT_EMPLOYEER]'                 => $patientData['employer_name'],
			'[PATIENT_RACE]'                      => $patientData['race'],
			'[PATIENT_ETHNICITY]'                 => $patientData['ethnicity'],
			'[PATIENT_LENGUAGE]'                  => $patientData['lenguage'],
			'[PATIENT_REFERRAL]'                  => $patientData['referral'], /////////////////////////////////////
			'[PATIENT_REFERRAL_DATE]'             => $patientData['referral_date'], ////////////////////////////////
            '[PATIENT_TABACCO]'                   => 'tabaco', //////////////////////////////////////////////////////
            '[PATIENT_ALCOHOL]'                   => 'alcohol', ////////////////////////////////////////////////////
			'[PATIENT_BALANCE]'                   => '$'.$this->fees->getPatientBalanceByPid($pid),
			'[PATIENT_PRIMARY_PLAN]'              => $patientData['primary_plan_name'],
            '[PATIENT_PRIMARY_EFFECTIVE_DATE]'    => $patientData['primary_effective_date'],
			'[PATIENT_PRIMARY_SUBSCRIBER]'        => $patientData['primary_subscriber_title'].$patientData['primary_subscriber_fname'] . ' ' . $patientData['primary_subscriber_mname']. ' ' . $patientData['primary_subscriber_lname'],
			'[PATIENT_PRIMARY_POLICY_NUMBER]'     => $patientData['primary_policy_number'],
			'[PATIENT_PRIMARY_GROUP_NUMBER]'      => $patientData['primary_group_number'],
			'[PATIENT_PRIMARY_SUBSCRIBER_STREET]' => $patientData['primary_subscriber_street'],
			'[PATIENT_PRIMARY_SUBSCRIBER_CITY]'   => $patientData['primary_subscriber_city'],
			'[PATIENT_PRIMARY_SUBSCRIBER_STATE]'  => $patientData['primary_subscriber_state'],
			'[PATIENT_PRIMARY_SUBSCRIBER_COUNTRY]'       => $patientData['primary_subscriber_country'],
			'[PATIENT_PRIMARY_SUBSCRIBER_ZIPCODE]'       => $patientData['primary_subscriber_zip_code'],
			'[PATIENT_PRIMARY_SUBSCRIBER_RELATIONSHIP]'  => $patientData['primary_subscriber_relationship'],
			'[PATIENT_PRIMARY_SUBSCRIBER_PHONE]'         => $patientData['primary_subscriber_phone'],
			'[PATIENT_PRIMARY_SUBSCRIBER_EMPLOYER]'      => $patientData['primary_subscriber_employer'],
			'[PATIENT_PRIMARY_SUBSCRIBER_EMPLOYER_CITY]' => $patientData['primary_subscriber_employer_city'],
			'[PATIENT_PRIMARY_SUBSCRIBER_EMPLOYER_STATE]'=> $patientData['primary_subscriber_employer_state'],
			'[PATIENT_PRIMARY_SUBSCRIBER_EMPLOYER_COUNTRY]'=> $patientData['primary_subscriber_employer_country'],
			'[PATIENT_PRIMARY_SUBSCRIBER_EMPLOYER_ZIPCODE]'=> $patientData['primary_subscriber_zip_code'],
			'[PATIENT_SECONDARY_PLAN]'              => $patientData['secondary_plan_name'],
            '[PATIENT_SECONDARY_EFFECTIVE_DATE]'    => $patientData['secondary_effective_date'],
			'[PATIENT_SECONDARY_SUBSCRIBER]'        => $patientData['secondary_subscriber_title'].$patientData['primary_subscriber_fname'] . ' ' . $patientData['primary_subscriber_mname']. ' ' . $patientData['primary_subscriber_lname'],
			'[PATIENT_SECONDARY_POLICY_NUMBER]'     => $patientData['secondary_policy_number'],
			'[PATIENT_SECONDARY_GROUP_NUMBER]'      => $patientData['secondary_group_number'],
			'[PATIENT_SECONDARY_SUBSCRIBER_STREET]' => $patientData['secondary_subscriber_street'],
			'[PATIENT_SECONDARY_SUBSCRIBER_CITY]'   => $patientData['secondary_subscriber_city'],
			'[PATIENT_SECONDARY_SUBSCRIBER_STATE]'  => $patientData['secondary_subscriber_state'],
			'[PATIENT_SECONDARY_SUBSCRIBER_COUNTRY]'       => $patientData['secondary_subscriber_country'],
			'[PATIENT_SECONDARY_SUBSCRIBER_ZIPCODE]'       => $patientData['secondary_subscriber_zip_code'],
			'[PATIENT_SECONDARY_SUBSCRIBER_RELATIONSHIP]'  => $patientData['secondary_subscriber_relationship'],
			'[PATIENT_SECONDARY_SUBSCRIBER_PHONE]'         => $patientData['secondary_subscriber_phone'],
			'[PATIENT_SECONDARY_SUBSCRIBER_EMPLOYER]'      => $patientData['secondary_subscriber_employer'],
			'[PATIENT_SECONDARY_SUBSCRIBER_EMPLOYER_CITY]' => $patientData['secondary_subscriber_employer_city'],
			'[PATIENT_SECONDARY_SUBSCRIBER_EMPLOYER_STATE]'=> $patientData['secondary_subscriber_employer_state'],
			'[PATIENT_SECONDARY_SUBSCRIBER_EMPLOYER_COUNTRY]'=> $patientData['secondary_subscriber_employer_country'],
			'[PATIENT_SECONDARY_SUBSCRIBER_EMPLOYER_ZIPCODE]'=> $patientData['secondary_subscriber_zip_code'],
			'[PATIENT_TERTIARY_PLAN]'              => $patientData['tertiary_plan_name'],
            '[PATIENT_TERTIARY_EFFECTIVE_DATE]'    => $patientData['tertiary_effective_date'],
			'[PATIENT_TERTIARY_SUBSCRIBER]'        => $patientData['tertiary_subscriber_title'].$patientData['primary_subscriber_fname'] . ' ' . $patientData['primary_subscriber_mname']. ' ' . $patientData['primary_subscriber_lname'],
			'[PATIENT_TERTIARY_POLICY_NUMBER]'     => $patientData['tertiary_policy_number'],
			'[PATIENT_TERTIARY_GROUP_NUMBER]'      => $patientData['tertiary_group_number'],
			'[PATIENT_TERTIARY_SUBSCRIBER_STREET]' => $patientData['tertiary_subscriber_street'],
			'[PATIENT_TERTIARY_SUBSCRIBER_CITY]'   => $patientData['tertiary_subscriber_city'],
			'[PATIENT_TERTIARY_SUBSCRIBER_STATE]'  => $patientData['tertiary_subscriber_state'],
			'[PATIENT_TERTIARY_SUBSCRIBER_COUNTRY]'       => $patientData['tertiary_subscriber_country'],
			'[PATIENT_TERTIARY_SUBSCRIBER_ZIPCODE]'       => $patientData['tertiary_subscriber_zip_code'],
			'[PATIENT_TERTIARY_SUBSCRIBER_RELATIONSHIP]'  => $patientData['tertiary_subscriber_relationship'],
			'[PATIENT_TERTIARY_SUBSCRIBER_PHONE]'         => $patientData['tertiary_subscriber_phone'],
			'[PATIENT_TERTIARY_SUBSCRIBER_EMPLOYER]'      => $patientData['tertiary_subscriber_employer'],
			'[PATIENT_TERTIARY_SUBSCRIBER_EMPLOYER_CITY]' => $patientData['tertiary_subscriber_employer_city'],
			'[PATIENT_TERTIARY_SUBSCRIBER_EMPLOYER_STATE]'=> $patientData['tertiary_subscriber_employer_state'],
			'[PATIENT_TERTIARY_SUBSCRIBER_EMPLOYER_COUNTRY]'=> $patientData['tertiary_subscriber_employer_country'],
			'[PATIENT_TERTIARY_SUBSCRIBER_EMPLOYER_ZIPCODE]'=> $patientData['tertiary_subscriber_zip_code']
        );
		$pos               = 0;
		foreach($tokens[0] as $tok) {
			if($allNeededInfo[$pos] == '' || $allNeededInfo[$pos] == null) {
				$allNeededInfo[$pos] = $patienInformation[$tok];
			}
			;
			$pos = $pos + 1;

		}
		return $allNeededInfo;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function get_EncounterTokensData(  $eid,  $allNeededInfo, $tokens)
	{


        $encounterid = new stdClass();
        $encounterid->eid=$eid;
        $encounterdata=$this->encounter->getEncounter($encounterid);
        $encountercodes=$this->encounter->getEncounterCodes($encounterid);



        $vitals = end($encounterdata['encounter']['vitals']);
        $soap = $encounterdata['encounter']['soap'];
        $reviewofsystemschecks = $encounterdata['encounter']['reviewofsystemschecks'][0];
        unset($reviewofsystemschecks['pid'],$reviewofsystemschecks['eid'],$reviewofsystemschecks['uid'],$reviewofsystemschecks['id'],$reviewofsystemschecks['date']);
        foreach($reviewofsystemschecks as $rosc=>$num){
            if($num == '' || $num ==null || $num==0){

                unset($reviewofsystemschecks[$rosc]);
            }

        }
        $reviewofsystems = $encounterdata['encounter']['reviewofsystems'];
        unset($reviewofsystems['pid'],$reviewofsystems['eid'],$reviewofsystems['uid'],$reviewofsystems['id'],$reviewofsystems['date']);
        foreach($reviewofsystems as $ros=>$num){
            if($num == '' || $num ==null|| $num =='null'){

                unset($reviewofsystems[$ros]);
            }

        }
        $cpt = array();
        $icd = array();
        $hcpc = array();
        foreach($encountercodes as $code){
            if($code['type']=='CPT'){
                $cpt[]=$code;
            }elseif($code['type']=="ICD"){
                $icd[]=$code;
            }elseif($code['type']=="HCPC"){
                $hcpc[]=$code;
            }
        }

        $medications = $this->medical->getPatientMedicationsByEncounterID($eid);
        $immunizations = $this->medical->getImmunizationsByEncounterID($eid);
        $allergies = $this->medical->getAllergiesByEncounterID($eid);
        $surgery = $this->medical->getPatientSurgeryByEncounterID($eid);
        $dental = $this->medical->getPatientDentalByEncounterID($eid);
        $activeProblems = $this->medical->getMedicalIssuesByEncounterID($eid);
        $preventivecaredismiss = $this->preventiveCare->getPreventiveCareDismissPatientByEncounterID($eid);
        $encounterdata=$encounterdata['encounter'];


		$encounterInformation = array
		(
			'[ENCOUNTER_START_DATE]'                 =>$encounterdata['start_date'],
			'[ENCOUNTER_END_DATE]'                   =>$encounterdata['end_date'],
			'[ENCOUNTER_BRIEF_DESCRIPTION]'          =>$encounterdata['brief_description'],
			'[ENCOUNTER_SENSITIVITY]'                =>$encounterdata['sensitivity'],
			'[ENCOUNTER_WEIGHT_LBS]'                 =>$vitals['weight_lbs'],
			'[ENCOUNTER_WEIGHT_KG]'                  =>$vitals['weight_kg'],
			'[ENCOUNTER_HEIGHT_IN]'                  =>$vitals['height_in'],
			'[ENCOUNTER_HEIGHT_CM]'                  =>$vitals['height_cm'],
			'[ENCOUNTER_BP_SYSTOLIC]'                =>$vitals['bp_systolic'],
			'[ENCOUNTER_BP_DIASTOLIC]'               =>$vitals['bp_diastolic'],
			'[ENCOUNTER_PULSE]'                      =>$vitals['pulse'],
			'[ENCOUNTER_RESPIRATION]'                =>$vitals['respiration'],
			'[ENCOUNTER_TEMP_FAHRENHEIT]'            =>$vitals['temp_f'],
			'[ENCOUNTER_TEMP_CELSIUS]'               =>$vitals['temp_c'],
			'[ENCOUNTER_TEMP_LOCATION]'              =>$vitals['temp_location'],
			'[ENCOUNTER_OXYGEN_SATURATION]'          =>$vitals['oxygen_saturation'],
			'[ENCOUNTER_HEAD_CIRCUMFERENCE_IN]'      =>$vitals['head_circumference_in'],
			'[ENCOUNTER_HEAD_CIRCUMFERENCE_CM]'      =>$vitals['head_circumference_cm'],
            '[ENCOUNTER_WAIST_CIRCUMFERENCE_IN]'     =>$vitals['waist_circumference_in'],
            '[ENCOUNTER_WAIST_CIRCUMFERENCE_CM]'     =>$vitals['waist_circumference_cm'],
            '[ENCOUNTER_BMI]'                        =>$vitals['bmi'],
			'[ENCOUNTER_BMI_STATUS]'                 =>$vitals['bmi_status'],
			'[ENCOUNTER_SUBJECTIVE]'                 =>$soap['subjective'],
			'[ENCOUNTER_OBJECTIVE]'                  =>$soap['objective'],
			'[ENCOUNTER_ASSESMENT]'                  =>$soap['assessment'],
            '[ENCOUNTER_PLAN]'                       =>$soap['plan'],
			'[ENCOUNTER_CPT_CODES]'                  =>$this->tokensForEncountersList($cpt,1),
			'[ENCOUNTER_ICD_CODES]'                  =>$this->tokensForEncountersList($icd,2),
			'[ENCOUNTER_HCPC_CODES]'                 =>$this->tokensForEncountersList($hcpc,3),
            '[ENCOUNTER_ALLERGIES_LIST]'             =>$this->tokensForEncountersList($allergies,4),
            '[ENCOUNTER_MEDICATIONS_LIST]'           =>$this->tokensForEncountersList($medications,5),
            '[ENCOUNTER_ACTIVE_PROBLEMS_LIST]'       =>$this->tokensForEncountersList($activeProblems,6),
            '[ENCOUNTER_IMMUNIZATIONS_LIST]'         =>$this->tokensForEncountersList($immunizations,7),
//            '[ENCOUNTER_DENTAL_LIST]'                =>$this->tokensForEncountersList($dental,8),
//            '[ENCOUNTER_SURGERY_LIST]'               =>$this->tokensForEncountersList($surgery,9),
            '[ENCOUNTER_PREVENTIVECARE_DISMISS]'     =>$this->tokensForEncountersList($preventivecaredismiss,10),
            '[ENCOUNTER_REVIEWOFSYSTEMSCHECKS]'      =>$this->tokensForEncountersList($reviewofsystemschecks,11),
            '[ENCOUNTER_REVIEWOFSYSTEMS]'            =>$this->tokensForEncountersList($reviewofsystems,12),
//            '[]'     =>$this->tokensForEncountersList($hcpc,13),
//            '[]'     =>$this->tokensForEncountersList($hcpc,14),
//            '[]'     =>$this->tokensForEncountersList($hcpc,15),
//            '[]'     =>$this->tokensForEncountersList($preventivecaredismiss,16),
//            '[]'     =>$this->tokensForEncountersList($reviewofsystemschecks,17),
//            '[]'     =>$this->tokensForEncountersList($preventivecaredismiss,16),
//            '[]'     =>$this->tokensForEncountersList($preventivecaredismiss,16)
		);

		$pos                  = 0;
		foreach($tokens[0] as $tok) {
			if($allNeededInfo[$pos] == '' || $allNeededInfo[$pos] == null) {
				$allNeededInfo[$pos] = $encounterInformation[$tok];
			}
			;
			$pos = $pos + 1;
		}
		return $allNeededInfo;
	}
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    private function tokensForEncountersList($Array,$typeoflist){
        $html = '';
        if($typeoflist == 1){
            $html .= '<table>';
            $html .= "<tr><th>"."CPT"."</th><th>"."Code text"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['code']."</td><td>".$row['code_text_short']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 2){
            $html .= '<table>';
            $html .= "<tr><th>"."ICD"."</th><th>"."Code text"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['code']."</td><td>".$row['code_text']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 3){
            $html .= '<table>';
            $html .= "<tr><th>"."HCPC"."</th><th>"."Code text"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['code']."</td><td>".$row['code_text']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 4){
            $html .= '<table>';
            $html .= "<tr><th>"."Allergies"."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 5){
            $html .= '<table>';
            $html .= "<tr><th>"."Medications"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['medication']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 6){
            $html .= '<table>';
            $html .= "<tr><th>"."Active Problems"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['code_text']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 7){
            $html .= '<table>';
            $html .= "<tr><th>"."Immunizations"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['immunization_name']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 8){
//            $html .= '<table>';
//            $html .= "<tr><th>"." Dental "."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
//            foreach($Array as $row) {
//                $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
//            }
//            $html .= '</table>';
        }
        elseif($typeoflist == 9){
//            $html .= '<table>';
//            $html .= "<tr><th>"." Surgeries "."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
//            foreach($Array as $row) {
//                $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
//            }
//            $html .= '</table>';
        }
        elseif($typeoflist == 10){
            $html .= '<table>';
            $html .= "<tr><th>"."Preventive Care"."</th><th>"."Reason"."</th></tr>";
            foreach($Array as $row) {
                $html .= "<tr><td>".$row['description']."</td><td>".$row['reason']."</td></tr>";
            }
            $html .= '</table>';
        }
        elseif($typeoflist == 11){
            $html .= '<table width="300">';
            $html .= "<tr><th>"."Review of systems checks"."</th><th>"."Active?"."</th></tr>";
            foreach($Array as $key=>$val) {
                $html .= "<tr><td>".str_replace('_',' ',$key)."</td><td>".(($val===1 || $val ==='1')?'Yes':'No')."</td></tr>";
            }
            $html .= '</table>';


        }
        elseif($typeoflist == 12){
            $html .= '<table width="300">';
            $html .= "<tr><th>"."Review of systems"."</th><th>"."Active?"."</th></tr>";
            foreach($Array as $key=>$val) {
                $html .= "<tr><td>".str_replace('_',' ',$key)."</td><td>".(($val==1 || $val =='1')?'Yes':'No')."</td></tr>";
            }
            $html .= '</table>';


        }
//        elseif($typeoflist == 13){
//            $html .= '<table>';
//                        $html .= "<tr><th>"." Allergies "."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
//                        foreach($Array as $row) {
//                            $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
//                        }
//                        $html .= '</table>';
//        }
//        elseif($typeoflist == 14){
//            $html .= '<table>';
//                        $html .= "<tr><th>"." Allergies "."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
//                        foreach($Array as $row) {
//                            $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
//                        }
//                        $html .= '</table>';
//        }
//        elseif($typeoflist == 15){
//            $html .= '<table>';
//                        $html .= "<tr><th>"." Allergies "."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
//                        foreach($Array as $row) {
//                            $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
//                        }
//                        $html .= '</table>';
//        }
//        elseif($typeoflist == 16){
//            $html .= '<table>';
//                        $html .= "<tr><th>"." Allergies "."</th><th>"."Type"."</th><th>"."Severity"."</th></tr>";
//                        foreach($Array as $row) {
//                            $html .= "<tr><td>".$row['allergy']."</td><td>".$row['allergy_type']."</td><td>".$row['severity']."</td></tr>";
//                        }
//                        $html .= '</table>';
//        }




        return ($Array == null || $Array == '') ? '' : $html;
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private function get_currentTokensData($allNeededInfo, $tokens)
	{

		$currentInformation = array(
			'[CURRENT_DATE]'     => date('d-m-Y'),
			'[CURRENT_USER_NAME]'=> $_SESSION['user']['name'],
			'[CURRENT_USER_FULL_NAME]'=> $_SESSION['user']['name'],
			'[CURRENT_USER_LICENSE_NUMBER]',
			'[CURRENT_USER_DEA_LICENSE_NUMBER]',
			'[CURRENT_USER_DM_LICENSE_NUMBER]',
			'[CURRENT_USER_NPI_LICENSE_NUMBER]',
			'[LINE]'             => '<hr>'
		);
		$pos                = 0;
		foreach($tokens[0] as $tok) {
			if($allNeededInfo[$pos] == '' || $allNeededInfo[$pos] == null) {
				$allNeededInfo[$pos] = $currentInformation[$tok];
			}
			;
			$pos = $pos + 1;
		}
		return $allNeededInfo;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	private function get_ClinicTokensData($allNeededInfo, $tokens)
	{
        $facilityInfo =$this->facility->getActiveFacilitiesById($_SESSION['facilities']['id']);
		$clinicInformation = array(
            '[FACILITY_NAME]'=>$facilityInfo['name'],
            '[FACILITY_PHONE]'=>$facilityInfo['phone'],
            '[FACILITY_FAX]'=>$facilityInfo['fax'],
            '[FACILITY_STREET]'=>$facilityInfo['street'],
            '[FACILITY_CITY]'=>$facilityInfo['city'],
            '[FACILITY_STATE]'=>$facilityInfo['state'],
            '[FACILITY_POSTALCODE]'=>$facilityInfo['postal_code'],
            '[FACILITY_COUNTRYCODE]'=>$facilityInfo['country_code'],
            '[FACILITY_FEDERAL_EIN]'=>$facilityInfo['federal_ein'],
            '[FACILITY_SERVICE_LOCATION]'=>$facilityInfo['service_location'],
            '[FACILITY_BILLING_LOCATION]'=>$facilityInfo['billing_location'],
            '[FACILITY_FACILITY_NPI]'=>$facilityInfo['facility_npi']

		);
		$pos               = 0;
		foreach($tokens[0] as $tok) {
			if($allNeededInfo[$pos] == '' || $allNeededInfo[$pos] == null) {
				$allNeededInfo[$pos] = $clinicInformation[$tok];
			}

			$pos = $pos + 1;
		}
		return $allNeededInfo;
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private function tokensForPrescriptions($params,$tokens,$allNeededInfo){
        $html = '';
        foreach($params->medications as $med) {
            $html .= "
                    <p>
                    $med->medication $med->dose  $med->dose_mg<br>
                    Instruction: $med->take_pills $med->type $med->by $med->prescription_often $med->prescription_when<br>
                    Dispense: $med->dispense  Refill: $med->refill
                    </p>";
        }
        foreach($tokens[0] as $index=>$tok) {
            if($allNeededInfo[$index] == '' || $allNeededInfo[$index] == null) {
                if($tok == '[MEDICATIONS_LIST]') {
                    $allNeededInfo[$index] = $html;
                }
            }
        }
        return $allNeededInfo;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private function tokensForLabs($params,$tokens,$allNeededInfo){
        $html = '';
        foreach($params->labs as $lab) {
            $html .= "
                    <p>
                    $lab->laboratories
                    </p>";
        }
        foreach($tokens[0] as $index=>$tok) {
            if($allNeededInfo[$index] == '' || $allNeededInfo[$index] == null) {
                if($tok == '[LABS_LIST]') {
                    $allNeededInfo[$index] = $html;
                }
            }
        }
        return $allNeededInfo;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private function tokensForXrays($params,$tokens,$allNeededInfo){
        $html = '';
        foreach($params->labs as $lab) {
            $html .= "
                    <p>
                    $lab->xrays
                    </p>";
        }
        foreach($tokens[0] as $index=>$tok) {
            if($allNeededInfo[$index] == '' || $allNeededInfo[$index] == null) {
                if($tok == '[XRAYS_LIST]') {
                    $allNeededInf [$index] = $html;
                }
            }
        }
        return $allNeededInfo;
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function PDFDocumentBuilder($params,$path)
	{
		$pid           = $params->pid;
        $eid           = $params->eid;
		$documentId    = $params->documentId;
        $regex = '(\[\w*?\])';
        $this->pdf->SetCreator('TCPDF');
        $this->pdf->SetAuthor($_SESSION['user']['name']);
		$siteLogo = $_SESSION['site']['path'].'/logo.jpg';
		$logo = (file_exists($siteLogo) ? $siteLogo : $_SESSION['root'] .'/resources/images/logo.jpg');

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
        $this->pdf->SetFontSize(12);
        $this->pdf->SetAutoPageBreak(true, 25);
        $this->pdf->setFontSubsetting(true);
        $this->pdf->AddPage();

        if(isset($params->DoctorsNote)){
            $body  = $params->DoctorsNote;
            preg_match_all($regex, $body, $tokensfound);
            $tokens= $tokensfound;
        }else{
            $tokens        = $this->getArrayWithTokensNeededByDocumentID($documentId); //getting the template
            $body          = $this->getTemplateBodyById($documentId);
        }
		$allNeededInfo = $this->setArraySizeOfTokenArray($tokens);
		$allNeededInfo = $this->get_PatientTokensData($pid, $allNeededInfo, $tokens);
		$allNeededInfo = $this->get_EncounterTokensData( 1,$allNeededInfo, $tokens);
		$allNeededInfo = $this->get_currentTokensData($allNeededInfo, $tokens);
		$allNeededInfo = $this->get_ClinicTokensData($allNeededInfo, $tokens);

        foreach($tokens[0] as $index=>$tok) {

            if($tok == '[PATIENT_PICTURE]') {
                $this->pdf->Image( $_SESSION['site']['path']. '/patients/' . $pid . '/' . 'patientPhotoId.jpg', 150, 55, 35, 35, 'jpg', 'www.gaiaehr.org', '', true, 150, '', false, false, 1, false, false, false);
            }

        }
		///////////////////////RX PART /////////////////////////////////////
		if(isset($params->medications)) {
             $allNeededInfo =  $this->tokensForPrescriptions($params,$tokens,$allNeededInfo);
		}
        ///////////////////////LABS PART /////////////////////////////////////
        elseif(isset($params->labs)) {
             $allNeededInfo =  $this->tokensForLabs($params,$tokens,$allNeededInfo);
        }
        ///////////////////////XRAYS PART /////////////////////////////////////
        elseif(isset($params->xrays)) {
             $allNeededInfo =  $this->tokensForXrays($params,$tokens,$allNeededInfo);
        }
		$html = str_replace($tokens[0], $allNeededInfo, $body);


        $this->pdf->writeHTML((isset($params->DoctorsNote))?$html:$html['body']);
        $this->pdf->Output($path, 'F');
        $this->pdf->Close();
		return true;
    }

}




//$e = new Documents();
//$params = new stdClass();
//$params->pid = 1;
//$params->documentId = 7;
//echo'<pre>';
//$e->get_PatientTokensData(1,'','');
