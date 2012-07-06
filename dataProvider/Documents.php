<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name("GaiaEHR");
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Patient.php');
include_once($_SESSION['site']['root'] . '/dataProvider/User.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Encounter.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Fees.php');
include_once($_SESSION['site']['root'] . '/dataProvider/PreventiveCare.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Medical.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Services.php');
include_once($_SESSION['site']['root'] . '/dataProvider/Facilities.php');
include_once($_SESSION['site']['root'] . '/lib/dompdf_0-6-0_beta3/dompdf_config.inc.php');
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

	private $dompdf;

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
		$this->dompdf   = new DOMPDF();
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
	private function get_PatientTokensData($pid, $allNeededInfo, $tokens)
	{
		$patientData       = $this->getAllPatientData($pid);
		$age               = $this->patient->getPatientAgeByDOB($patientData['DOB']);
		$root              = $_SESSION['site']['root'];
		$site              = $_SESSION['site']['site'];
		$path              = $root . '/sites/' . $site . '/patients/' . $pid . '/' . 'patientPhotoId.jpg';
		$img               = $img = '
        <style type="text/css">
        div.leftpane {
                position: fixed;
        }
        </style>
        <div class="leftpane">
		        <img src="' . $path . '"width="120"style="margin: 1cm;";/>
        </div>';
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
			'[PATIENT_AGE]'                       => $age['years'],
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
			'[PATIENT_PICTURE]'                   => $img,
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
        unset($encounterdata['reviewofsystems'],$encounterdata['vitals'],$encounterdata['soap'],$encounterdata['reviewofsystemschecks'],$encounterdata['speechdictation']);


        print_r($encounterdata);
        print_r($vitals);
        print_r($soap);
        print_r($reviewofsystemschecks);
        print_r($reviewofsystems);
        print_r($cpt);
        print_r($icd);
        print_r($hcpc);
        print_r('$medications');
        print_r($medications);
        print_r('$immunizations');
        print_r($immunizations);
        print_r('$allergies');
        print_r($allergies);
        print_r('$surgery');
        print_r($surgery);
        print_r('$dental');
        print_r($dental);
        print_r('$activeProblems');
        print_r($activeProblems);
        print_r('$preventivecaredismiss');
        print_r($preventivecaredismiss);

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
            '[ENCOUNTER_WAIST_CIRCUMFERENCE_CM]'     => $vitals['waist_circumference_cm'],
            '[PATIENT_HEIGHT]'                      => '',
            '[PATIENT_PULSE]'                     => '',
            '[PATIENT_RESPIRATORY_RATE]'          => '',

            '[ENCOUNTER_DATE]'               =>'         ',
			'[ENCOUNTER_SUBJECTIVE]'         =>'         ',
			'[ENCOUNTER_OBJECTIVE]'          =>'         ',
			'[ENCOUNTER_ASSESMENT]'          =>'         ',
			'[ENCOUNTER_ASSESMENT_LIST]'     =>'         ',
			'[ENCOUNTER_ASSESMENT_CODE_LIST]'=>'         ',
			'[ENCOUNTER_ASSESMENT_FULL_LIST]'=>'         ',
			'[ENCOUNTER_PLAN]'               =>'         ',
			'[ENCOUNTER_MEDICATIONS]'        =>'         ',
			'[ENCOUNTER_IMMUNIZATIONS]'      =>'         ',
			'[ENCOUNTER_ALLERGIES]'          =>'         ',
			'[ENCOUNTER_ACTIVE_PROBLEMS]'    =>'         ',
			'[ENCOUNTER_SURGERIES]'          =>'         ',
			'[ENCOUNTER_DENTAL]'             =>'         ',
			'[ENCOUNTER_LABORATORIES]'       =>'         ',
			'[ENCOUNTER_PROCEDURES_TERMS]'   =>'         ',
			'[ENCOUNTER_CPT_CODES]'          =>'         ',
			'[ENCOUNTER_SIGNATURE]'          =>'         ',
            '[PATIENT_REFERRAL_REASON]'      =>'         ',
            '[PATIENT_HEAD_CIRCUMFERENCE]'        => '',
            '[PATIENT_HEIGHT]'                    => '',
            '[PATIENT_PULSE]'                     => '',
            '[PATIENT_RESPIRATORY_RATE]'          => '',
            '[PATIENT_TEMPERATURE]'               => '',
            '[PATIENT_WEIGHT]'                    => '',
            '[PATIENT_PULSE_OXIMETER]'            => '',
            '[PATIENT_BLOOD_PREASURE]'            => '',
            '[PATIENT_BMI]'                       => '',
            '[PATIENT_ACTIVE_ALLERGIES_LIST]'     => '',
            '[PATIENT_INACTIVE_ALLERGIES_LIST]'   => '',
            '[PATIENT_ACTIVE_MEDICATIONS_LIST]'   => '',
            '[PATIENT_INACTIVE_MEDICATIONS_LIST]' => '',
            '[PATIENT_ACTIVE_PROBLEMS_LIST]'      => '',
            '[PATIENT_INACTIVE_PROBLEMS_LIST]'    => '',
            '[PATIENT_ACTIVE_IMMUNIZATIONS_LIST]' => '',
            '[PATIENT_INACTIVE_IMMUNIZATIONS_LIST]' => '',
            '[PATIENT_ACTIVE_DENTAL_LIST]'        => '',
            '[PATIENT_INACTIVE_DENTAL_LIST]'      => '',
            '[PATIENT_ACTIVE_SURGERY_LIST]'       => '',
            '[PATIENT_INACTIVE_SURGERY_LIST]'     => ''
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
			'[LINE]'             => '<hr>',
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
		$clinicInformation = array(
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
                    $allNeededInfo[$index] = $html;
                }
            }
        }
        return $allNeededInfo;
    }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	public function PDFDocumentBuilder($params)
	{
		$pid           = $params->pid;
        $eid           = $params->eid;
		$documentId    = $params->documentId;
		$tokens        = $this->getArrayWithTokensNeededByDocumentID($documentId); //getting the template
		$body          = $this->getTemplateBodyById($documentId);
		$allNeededInfo = $this->setArraySizeOfTokenArray($tokens);
		$allNeededInfo = $this->get_PatientTokensData($pid, $allNeededInfo, $tokens);
		$allNeededInfo = $this->get_EncounterTokensData( $eid,$allNeededInfo, $tokens);
		$allNeededInfo = $this->get_currentTokensData($allNeededInfo, $tokens);
		$allNeededInfo = $this->get_ClinicTokensData($allNeededInfo, $tokens);

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

		$rawHTML = str_replace($tokens[0], $allNeededInfo, $body);
		$this->dompdf->load_html($rawHTML['body']);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$pdf = $this->dompdf->output();
		return $pdf;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function PDFDocumentBuilderDoctors($params)
	{
		$pid           = $params->pid;
		$eid           = $params->eid;
        $regex = '(\[\w*?\])';
        $body  = $params->DoctorsNote;
        preg_match_all($regex, $body, $tokensfound);
        $tokens= $tokensfound;

		$allNeededInfo = $this->setArraySizeOfTokenArray($tokens);
		$allNeededInfo = $this->get_PatientTokensData($pid, $allNeededInfo, $tokens);
		$allNeededInfo = $this->get_EncounterTokensData( $eid, $allNeededInfo, $tokens);
		$allNeededInfo = $this->get_currentTokensData($allNeededInfo, $tokens);
		$allNeededInfo = $this->get_ClinicTokensData($allNeededInfo, $tokens);
		$rawHTML = str_replace($tokens[0], $allNeededInfo, $body);
		$this->dompdf->load_html($rawHTML);
		$this->dompdf->set_paper('letter', 'portrait');
		$this->dompdf->render();
		$pdf = $this->dompdf->output();
		return $pdf;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
}

$e = new Documents();
echo '<pre>';
$e->get_EncounterTokensData(1,3,3);
