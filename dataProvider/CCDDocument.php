<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!isset($_SESSION)){
    session_cache_limiter('private');
    session_cache_expire(1);
    session_regenerate_id(false);
    session_name('GaiaEHR');
    session_start();
    setcookie(session_name(),session_id(),time()+86400, '/', null, false, true);
}
if(!defined('_GaiaEXEC')){
	define('_GaiaEXEC', 1);
	require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
}

include_once(ROOT . '/classes/UUID.php');
include_once(ROOT . '/classes/Array2XML.php');

include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/PatientContacts.php');
include_once(ROOT . '/dataProvider/Insurance.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/Rxnorm.php');
include_once(ROOT . '/dataProvider/Encounter.php');
include_once(ROOT . '/dataProvider/PoolArea.php');
include_once(ROOT . '/dataProvider/Vitals.php');
include_once(ROOT . '/dataProvider/Immunizations.php');
include_once(ROOT . '/dataProvider/ActiveProblems.php');
include_once(ROOT . '/dataProvider/Allergies.php');
include_once(ROOT . '/dataProvider/Orders.php');
include_once(ROOT . '/dataProvider/Medications.php');
include_once(ROOT . '/dataProvider/CarePlanGoals.php');
include_once(ROOT . '/dataProvider/CognitiveAndFunctionalStatus.php');
include_once(ROOT . '/dataProvider/Procedures.php');
include_once(ROOT . '/dataProvider/SocialHistory.php');
include_once(ROOT . '/dataProvider/Services.php');
include_once(ROOT . '/dataProvider/Referrals.php');
include_once(ROOT . '/dataProvider/ReferringProviders.php');
include_once(ROOT . '/dataProvider/DiagnosisCodes.php');
include_once(ROOT . '/dataProvider/Facilities.php');
include_once(ROOT . '/dataProvider/CombosData.php');

class CCDDocument {

	/**
	 * @var int
	 */
	private $pid = null;
	/**
	 * @var int
	 */
	private $eid = null;
	/**
	 * @var string
	 */
	private $dateNow;
	/**
	 * @var string
	 */
	private $timeNow;
	/**
	 * @var Encounter
	 */
	private $Encounter;
	/**
	 * @var Facilities
	 */
	private $Facilities;
	/**
	 * @var CombosData
	 */
	private $CombosData;
	/**
	 * @var Patient
	 */
	private $Patient;
    /**
     * @var
     */
    private $PatientContacts;
	/**
	 * @var User
	 */
	private $User;

	/**
	 * @var
	 */
	private $encounter;
	/**
	 * @var
	 */
	private $encounterProvider;

	/**
	 * @var
	 */
	private $encounterFacility;

	/**
	 * @var array
	 */
	private $facility;
	/**
	 * @var array
	 */
	private $user;
	/**
	 * @var array
	 */
	private $primaryProvider;
	/**
	 * @var DomDocument
	 */
	private $xml;
	/**
	 * @var array
	 */
	private $xmlData;
	/**
	 * @var string toc | ocv | soc
	 */
	private $template = 'toc'; // transition of care
	/**
	 * @var array
	 */
	private $templateIds = [
		'toc' => '2.16.840.1.113883.10.20.22.1.1',
		// transition of Care
		'cov' => '2.16.840.1.113883.10.20.22.1.1',
		// Clinical Office Visit
		'soc' => '2.16.840.1.113883.10.20.22.1.1',
		// Summary of Care
		'ps' => '2.16.840.1.113883.3.88.11.32.1'
		// Patient Summary
	];

	/**
	 * @var array
	 */
	private $patientData;
	/**
	 * @var bool
	 */
	private $requiredAllergies;
	/**
	 * @var bool
	 */
	private $requiredVitals;
	/**
	 * @var bool
	 */
	private $requiredImmunization;
	/**
	 * @var bool
	 */
	private $requiredMedications;
	/**
	 * @var bool
	 */
	private $requiredProblems;
	/**
	 * @var bool
	 */
	private $requiredProcedures;
	/**
	 * @var bool
	 */
	private $requiredPlanOfCare;
    /**
     * @vat bool
     */
    private $requiredCareOfPlan;
	/**
	 * @var bool
	 */
	private $requiredResults;
	/**
	 * @var bool
	 */
	private $requiredEncounters;

	/**
	 * @var array
	 */
	private $exclude = [];

	/**
	 * CCDDocument constructor.
	 */
	function __construct()
	{
		$this->dateNow = date('Ymd');
		$this->timeNow = date('YmdHisO');
		$this->Encounter = new Encounter();
		$this->Facilities = new Facilities();
		$this->CombosData = new CombosData();
		$this->User = new User();
		$this->Patient = new Patient();
        $this->PatientContacts = new PatientContacts();
		$this->facility = $this->Facilities->getCurrentFacility(true);
	}

    /**
     * Return the pertinent OID of a certain code system name
     * @param $codeSystem
     * @return string
     */
    function codes($codeSystem){
        switch($codeSystem)
        {
            case 'CPT':
                return '2.16.840.1.113883.6.12';
                break;
            case 'CPT4':
            case 'CPT-4':
                return '2.16.840.1.113883.6.12';
                break;
            case 'ICD9':
            case 'ICD-9':
                return '2.16.840.1.113883.6.42';
                break;
            case 'ICD10':
            case 'ICD-10':
                return '2.16.840.1.113883.6.3';
                break;
            case 'LN':
            case 'LOINC':
                return '2.16.840.1.113883.6.1';
                break;
            case 'NDC':
                return '2.16.840.1.113883.6.6';
                break;
            case 'RXNORM':
                return '2.16.840.1.113883.6.88';
                break;
            case 'SNOMED':
            case 'SNOMEDCT':
            case 'SNOMED-CT':
                return '2.16.840.1.113883.6.96';
                break;
            case 'NPI':
                return '2.16.840.1.113883.4.6';
                break;
            case 'UNII':
                return '2.16.840.1.113883.4.9';
                break;
            case 'NCI':
                return '2.16.840.1.113883.3.26.1.1';
                break;
            case 'ActPriority':
                return '2.16.840.1.113883.1.11.16866';
                break;
            case 'TAXONOMY':
                return '2.16.840.1.114222.4.11.106';
                break;
            default:
                return '';
        }
    }

	/**
	 * @param $pid
	 */
	public function setPid($pid) {
		$this->pid = $pid;
	}

	/**
	 * @param $eid
	 */
	public function setEid($eid) {
		$this->eid = $eid == 'null' ? null : $eid;
	}

	/**
	 * @param $exclude
	 */
	public function setExcludes($exclude) {
		$this->exclude = $exclude == '' ? [] : explode(',',$exclude);
	}

	/**
	 * @param $session
	 * @return bool
	 */
	public function isExcluded($session) {
		return array_search($session, $this->exclude) !== false;
	}

	/**
	 * @param $template
	 */
	public function setTemplate($template) {
		$this->template = $template;
	}

	/**
	 * Method buildCCD()
	 */
	public function createCCD() {
		try {

			if(!isset($this->pid)){
				throw new Exception('PID variable not set');
			}

			$this->xmlData = [
				'@attributes' => [
					'xmlns' => 'urn:hl7-org:v3',
					'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
					'xsi:schemaLocation' => 'urn:hl7-org:v3 CDA.xsd'
				]
			];

			if(isset($this->eid)){
				$this->encounter = $this->Encounter->getEncounter((int)$this->eid, false, false);
				$this->encounter = isset($this->encounter['encounter']) ? $this->encounter['encounter'] : $this->encounter;
				$this->encounterProvider = $this->User->getUserByUid($this->encounter['provider_uid']);
				$this->encounterFacility = $this->Facilities->getFacility($this->encounter['facility']);
			}

			$this->setRequirements();
			$this->setHeader();

			/**
			 * Array of sections to include in CCD
			 */
			$sections = [
				'ReasonOfVisit',
				'Instructions',
				'ReasonForReferral',
				'Procedures',
				'Vitals',
				'Immunizations',
				'Medications',
				'MedicationsAdministered',
				'PlanOfCare',
				'CareOfPlan',
				'Problems',
				'Allergies',
				'SocialHistory',
				'Results',
				'FunctionalStatus'
			];

			/**
			 * Run Section method for each section
			 */
			foreach($sections AS $Section){
				call_user_func([
					$this,
					"set{$Section}Section"
				]);
			}

			/**
			 * Build the CCR XML Object
			 */
			Array2XML::init(
                '1.0',
                'UTF-8',
                true,
                ['xml-stylesheet' => 'type="text/xsl" href="' . URL . '/lib/CCRCDA/schema/cda2.xsl"']
            );
			$this->xml = Array2XML::createXML('ClinicalDocument', $this->xmlData);
		} catch(Exception $e) {
			print $e->getMessage();
		}
	}

	/**
	 * Method view()
	 */
	public function view() {
		try {
			header('Content-type: application/xml');
			print $this->xml->saveXML();
		} catch(Exception $e) {
			print $e->getMessage();
		}
	}

	/**
	 * Method view()
	 */
	public function archive() {
		try {
			header('Content-type: application/xml');
			$xml = $this->xml->saveXML();
			$name = $this->getFileName() . '.xml';
			$date = date('Y-m-d H:i:s');
			$document = new stdClass();
			$document->pid = $this->pid;
			$document->eid = $this->eid;
			$document->uid = $_SESSION['user']['id'];
			$document->docType = 'C-CDA';
			$document->name = $name;
			$document->date = $date;
			$document->note = '';
			$document->title = 'C-CDA';
			$document->encrypted = 0;
			$document->document = base64_encode($xml);
			include_once(ROOT . '/dataProvider/DocumentHandler.php');
			$DocumentHandler = new DocumentHandler();
			$DocumentHandler->addPatientDocument($document);
			unset($DocumentHandler, $document, $name, $date);
			print $xml;
		} catch(Exception $e) {
			print $e->getMessage();
		}
	}

	/**
	 * Method get()
	 */
	public function get() {
		try {
			return $this->xml->saveXML();
		} catch(Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * Method export()
	 */
	public function export() {
		try {
            // Create a ZIP archive for delivery
			$dir = site_temp_path . '/';
			$filename = $this->getFileName();
			$file = $this->zipIt($dir, $filename);
            // Stream the file to the client
			header('Content-Type: application/zip');
			header('Content-Length: ' . filesize($file));
			header('Content-Disposition: attachment; filename="' . $filename . '.zip' . '"');
			readfile($file);
			unlink($file);
		} catch(Exception $e) {
			print $e->getMessage();
		}

	}

	/**
	 * @return string
	 */
	private function getFileName(){
	    return strtolower(str_replace(
            ' ',
            '',
            $this->pid . "-" . $this->patientData['fname'] . $this->patientData['lname']
        ));
	}

	/**
	 * Method save()
	 * @param $toDir
	 * @param $fileName
	 */
	public function save($toDir, $fileName) {
		try {
			$filename = $fileName ? $fileName : $this->getFileName();
			$this->zipIt($toDir, $filename);
		} catch(Exception $e) {
			print $e->getMessage();
		}
	}

	/**
	 * @return mixed
	 */
	private function getTemplateId() {
		return $this->templateIds[$this->template];
	}

	/**
	 * Method setRequirements()
	 */
	private function setRequirements() {
		if($this->template == 'toc'){
			$this->requiredAllergies = true;
			$this->requiredVitals = true;
			$this->requiredImmunization = true;
			$this->requiredMedications = true;
			$this->requiredProblems = true;
			$this->requiredProcedures = true;
			$this->requiredPlanOfCare = true;
            $this->requiredCareOfPlan = true;
			$this->requiredResults = true;
			$this->requiredEncounters = false;
		}
	}

	/**
	 * Method zipIt()
	 */
	private function zipIt($dir, $filename) {
		$zip = new ZipArchive();
		$file = $dir . $filename . '.zip';
		if($zip->open($file, ZipArchive::CREATE) !== true)
			exit("cannot open <$filename.zip>\n");
		$zip->addFromString($filename . '.xml', $this->xml->saveXML());
		$zip->addFromString('cda2.xsl', file_get_contents(ROOT . '/lib/CCRCDA/schema/cda2.xsl'));
		$zip->close();
		return $file;
	}

	/**
	 * Method setHeader()
	 */
	private function setHeader() {
		$this->xmlData['realmCode'] = [
			'@attributes' => [
				'code' => 'US'
			]
		];
		$this->xmlData['typeId'] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.1.3',
				'extension' => 'POCD_HD000040'
			]
		];
		// QRDA templateId
		$this->xmlData['templateId'][] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.22.1.1'
			]
		];
		// QDM-based QRDA templateId
		$this->xmlData['templateId'][] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.22.1.2'
			]
		];
		// QRDA templateId
		$this->xmlData['templateId'][] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.24.1.1'
			]
		];
		// QDM-based QRDA templateId
		$this->xmlData['templateId'][] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.24.1.2'
			]
		];
		$this->xmlData['id'] = [
			'@attributes' => [
				'root' => 'MDHT',
				'extension' => '1912668293'
			]
		];
		$this->xmlData['code'] = [
			'@attributes' => [
				'code' => '34133-9',
                'displayName' => 'Summary of episode note',
                'codeSystem' => '2.16.840.1.113883.6.1',
                'codeSystemName' => 'LOINC'
			]
		];

		if(isset($this->encounter)){
			$this->xmlData['title'] = $this->facility['name'] . ' - Clinical Office Visit Summary';
		}else{
			$this->xmlData['title'] = $this->facility['name'] . ' - Continuity of Care Document';
		}

		$this->xmlData['effectiveTime'] = [
			'@attributes' => [
				'value' => $this->timeNow
			]
		];
		$this->xmlData['confidentialityCode'] = [
			'@attributes' => [
				'code' => 'N',
				'codeSystem' => '2.16.840.1.113883.5.25'
			]
		];
		$this->xmlData['languageCode'] = [
			'@attributes' => [
				'code' => 'en-US'
			]
		];

		$this->patientData = $this->Patient->getPatientDemographicDataByPid($this->pid);
		$this->user = $this->User->getCurrentUserData();
		$this->primaryProvider = $this->User->getUserByUid($this->patientData['primary_provider']);

		$this->xmlData['recordTarget'] = $this->getRecordTarget();
		$this->xmlData['author'] = $this->getAuthor();
		$this->xmlData['dataEnterer'] = $this->getDataEnterer();
		$this->xmlData['informant'] = $this->getInformant();
		$this->xmlData['custodian'] = $this->getCustodian();
		$this->xmlData['informationRecipient'] = $this->getInformationRecipient();
		$this->xmlData['legalAuthenticator'] = $this->getAuthenticator();
		$this->xmlData['authenticator'] = $this->getAuthenticator();
		$this->xmlData['documentationOf'] = $this->getDocumentationOf();

		if(isset($this->encounter)){
			$this->xmlData['componentOf'] = $this->getComponentOf();
		}

		$this->xmlData['component']['structuredBody']['component'] = [];

	}

	/**
	 * Method getRecordTarget()
     *
     * The recordTarget records the administrative and demographic data of the patient whose health information
     * is described by the clinical document; each recordTarget must contain at least one patientRole element
     *
	 * @return array
	 */
	private function getRecordTarget() {
		$patientData = $this->patientData;
		$Insurance = new Insurance();
		$insuranceData = $Insurance->getPatientPrimaryInsuranceByPid($this->pid);
        $PatientContactRecord = $this->PatientContacts->getSelfContact($this->pid);

		$recordTarget['patientRole']['id'] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.19.5',
				'extension' => $patientData['pid']
			]
		];

        // If the Self Contact information address is set, include it in the CCD
        if(isset($PatientContactRecord['street_mailing_address'])) {
            $recordTarget['patientRole']['addr'] = $this->addressBuilder(
                'HP',
                $PatientContactRecord['street_mailing_address'],
                $PatientContactRecord['city'],
                $PatientContactRecord['state'],
                $PatientContactRecord['zip'],
                $PatientContactRecord['country'],
                date('Ymd')
            );
        }

        // If the Self Contact information phone is present, include it in the CCD
        if(isset($PatientContactRecord['phone_use_code']) &&
            isset($PatientContactRecord['phone_area_code']) &&
            isset($PatientContactRecord['phone_local_number'])
        ){
            $recordTarget['patientRole']['telecom'] = $this->telecomBuilder(
                $PatientContactRecord['phone_use_code'].
                $PatientContactRecord['phone_area_code'].
                $PatientContactRecord['phone_local_number'],
                'HP'
            );
        }

		// Patient Name
		$recordTarget['patientRole']['patient']['name'] = [
			'@attributes' => [
				'use' => 'L'
			],
		];

		$recordTarget['patientRole']['patient']['name']['given'][] = $patientData['fname'];

		if($patientData['mname'] != ''){
			$recordTarget['patientRole']['patient']['name']['given'][] = $patientData['mname'];
		}

		$recordTarget['patientRole']['patient']['name']['family'] = $patientData['lname'];

		if($patientData['title'] != ''){
			$recordTarget['patientRole']['patient']['name']['suffix'] = [
				'@attributes' => [
					'qualifier' => 'TITLE'
				],
				'@value' => isset($patientData['title']) ? $patientData['title'] : ''
			];
		}

        // values are M, F, or UM more info...
        // http://phinvads.cdc.gov/vads/ViewValueSet.action?id=8DE75E17-176B-DE11-9B52-0015173D1785
		$recordTarget['patientRole']['patient']['administrativeGenderCode'] = [
			'@attributes' => [
				'code' => $patientData['sex'],
				'codeSystemName' => 'AdministrativeGender',
				'codeSystem' => '2.16.840.1.113883.5.1'
			]
		];

		// Patient Sex
		if($patientData['sex'] == 'F'){
			$recordTarget['patientRole']['patient']['administrativeGenderCode']['@attributes']['displayName'] = 'Female';
		} elseif($patientData['sex'] == 'M') {
			$recordTarget['patientRole']['patient']['administrativeGenderCode']['@attributes']['displayName'] = 'Male';
		}

		// Patient Date of Birth
		$recordTarget['patientRole']['patient']['birthTime'] = [
			'@attributes' => [
				'value' => preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2$3', $patientData['DOB'])
			]
		];

		if(isset($patientData['marital_status']) && $patientData['marital_status'] != ''){
			$recordTarget['patientRole']['patient']['maritalStatusCode'] = [
				'@attributes' => [
					'code' => $patientData['marital_status'],
					'codeSystemName' => 'MaritalStatusCode',
					'displayName' => $this->CombosData->getDisplayValueByListIdAndOptionValue(12, $patientData['marital_status']),
					'codeSystem' => '2.16.840.1.113883.5.2'
				]
			];
		} else {
			$recordTarget['patientRole']['patient']['maritalStatusCode'] = [
				'@attributes' => [
					'nullFlavor' => 'NA',
					'codeSystemName' => 'MaritalStatusCode',
					'codeSystem' => '2.16.840.1.113883.5.2'
				]
			];
		}

		// Patient Race
		if(isset($patientData['race']) && $patientData['race'] != ''){
			$recordTarget['patientRole']['patient']['raceCode'] = [
				'@attributes' => [
					'code' => $patientData['race'],
					'codeSystemName' => 'Race &amp; Ethnicity - CDC',
					'displayName' => $this->CombosData->getDisplayValueByListIdAndOptionValue(14, $patientData['race']),
					'codeSystem' => '2.16.840.1.113883.6.238'
				]
			];
		} else {
			$recordTarget['patientRole']['patient']['raceCode'] = [
				'@attributes' => [
					'nullFlavor' => 'NA',
					'codeSystemName' => 'Race &amp; Ethnicity - CDC',
					'codeSystem' => '2.16.840.1.113883.6.238'
				]
			];
		}

		// Patient Ethnicity
		if(isset($patientData['ethnicity']) && $patientData['ethnicity'] != ''){
			$recordTarget['patientRole']['patient']['ethnicGroupCode'] = [
				'@attributes' => [
					'code' => $patientData['ethnicity'] == 'H' ? '2135-2' : '2186-5',
					'codeSystemName' => 'Race &amp; Ethnicity - CDC',
					'displayName' => $this->CombosData->getDisplayValueByListIdAndOptionValue(
                        59,
                        $patientData['ethnicity']
                    ),
					'codeSystem' => '2.16.840.1.113883.6.238'
				]
			];
		} else {
			$recordTarget['patientRole']['patient']['ethnicGroupCode'] = [
				'@attributes' => [
					'nullFlavor' => 'NA',
					'codeSystemName' => 'Race &amp; Ethnicity - CDC',
					'codeSystem' => '2.16.840.1.113883.6.238'
				]
			];
		}

		$recordTarget['patientRole']['patient']['birthplace']['place']['addr'] = $this->addressBuilder(
            false,
            false,
            false,
            false,
            false,
            ''
        );

		// Patient Prefered language
		if(isset($patientData['language']) && $patientData['language'] != ''){
			$recordTarget['patientRole']
            ['patient']
            ['languageCommunication']
            ['languageCode']
            ['@attributes']
            ['code'] = $patientData['language'];
		} else {
			$recordTarget['patientRole']
            ['patient']
            ['languageCommunication']
            ['languageCode']
            ['@attributes']
            ['nullFlavor'] = 'NI';
		}

		$org = [];

		$org['id']['@attributes'] = [
			'root' => '2.16.840.1.113883.4.6',
			'assigningAuthorityName' => 'CCD-Author'
		];
		$org['name']['prefix'] = $this->facility['name'];
		$org['telecom'] = $this->telecomBuilder($this->facility['phone'], 'WP');
		$org['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );

		$recordTarget['patientRole']['providerOrganization'] = $org;

		unset($Patient, $patientData, $Insurance, $insuranceData);

		return $recordTarget;
	}

	/**
	 * Method getAuthor()
     *
     * The author element represents the creator of the clinical document.
     * The author may be a device, or a person. The person is the patient or the patient’s advocate.
     *
	 * @return array
	 */
	private function getAuthor() {
		$author = [
			'time' => [
				'@attributes' => [
					'value' => $this->timeNow
				]
			]
		];

		$author['assignedAuthor'] = [
			'id' => [
				'@attributes' => [
					'root' => '2.16.840.1.113883.4.6',
					'extension' => $this->user['npi'] == '' ? $this->user['id'] : $this->user['npi']
				]
			]
		];
		$author['assignedAuthor']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );

		$author['assignedAuthor']['telecom'] = $this->telecomBuilder(
            $this->facility['phone'],
            'WP'
        );

		$author['assignedAuthor']['assignedPerson'] = [
			'@attributes' => [
				'classCode' => 'PSN',
				'determinerCode' => 'INSTANCE'
			],
			'name' => [
				'given' => $this->user['fname'],
				'family' => $this->user['lname']
			]
		];

		$author['assignedAuthor']['representedOrganization'] = [
			'id' => [
				'@attributes' => [
					'root' => '2.16.840.1.113883.4.6'
				],
			],
			'name' => [
				'prefix' => $this->facility['name']
			]
		];

		$author['assignedAuthor']
        ['representedOrganization']
        ['telecom'] = $this->telecomBuilder($this->facility['phone'], 'WP');

		$author['assignedAuthor']['representedOrganization']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );

		return $author;
	}

	/**
	 * Method getCustodian()
     *
     * The custodian element represents the organization or person that is in charge of maintaining the document.
     * The custodian is the steward that is entrusted with the care of the document. Every CDA document has
     * exactly one custodian. The custodian participation satisfies the CDA definition of Stewardship.
     * Because CDA is an exchange standard and may not represent the original form of the authenticated document
     * (e.g., CDA could include scanned copy of original), the custodian represents the steward of the
     * original source document. The custodian may be the document originator, a health information exchange,
     * or other responsible party. Also, the custodian may be the patient or an organization acting on behalf
     * of the patient, such as a PHR organization.
     *
	 * @return array
	 */
	private function getCustodian() {
		$custodian = [
			'assignedCustodian' => [
				'representedCustodianOrganization' => [
					'id' => [
						'@attributes' => [
							'root' => '2.16.840.1.113883.4.6'
						]
					],
					'name' => [
						'prefix' => $this->facility['name']
					]
				]
			]
		];

		$custodian['assignedCustodian']['representedCustodianOrganization']['telecom'] = $this->telecomBuilder(
            $this->facility['phone'], 'WP'
        );

		$custodian['assignedCustodian']['representedCustodianOrganization']['addr'] = $this->addressBuilder(
            'WP', $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );

		return $custodian;
	}

	/**
	 * Method getInformationRecipient()
     *
     * The informationRecipient element records the intended recipient of the information at the time the document
     * is created. For example, in cases where the intended recipient of the document is the patient's
     * health chart, set the receivedOrganization to be the scoping organization for that chart.
     *
	 * @return array
	 */
	private function getInformationRecipient() {
		$recipient = [
			'intendedRecipient' => [
				'informationRecipient' => [
					'name' => [
						'given' => $this->primaryProvider['fname'],
						'family' => $this->primaryProvider['lname']
					],
				],
				'receivedOrganization' => [
					'name' => [
						'prefix' => $this->facility['name']
					]
				]
			]
		];

		return $recipient;
	}

	/**
	 * Method getAuthenticator()
     *
     * The combined @root and @extension  attributes to record the authenticator’s identity in a
     * secure, trusted, and unique way.
     *
	 * @return array
	 */
	private function getAuthenticator() {
		$authenticator = [
			'time' => [
				'@attributes' => [
					'value' => $this->timeNow
				]
			],
			'signatureCode' => [
				'@attributes' => [
					'code' => 'S'
				],
			],
			'assignedEntity' => [
				'id' => [
					'@attributes' => [
						'root' => '2.16.840.1.113883.3.225',
						'assigningAuthorityName' => $this->facility['name']
					]
				]
			]
		];

		$authenticator['assignedEntity']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );

		$authenticator['assignedEntity']['telecom'] = $this->telecomBuilder($this->facility['phone'], 'WP');
		$authenticator['assignedEntity']['assignedPerson'] = [
			'name' => [
				'given' => $this->user['fname'],
				'family' => $this->user['lname']
			]
		];

		return $authenticator;
	}

	/**
	 * Method getDocumentationOf()
	 * @return array
	 */
	private function getDocumentationOf() {
		$documentationOf = [
			'serviceEvent' => [
				'@attributes' => [
					'classCode' => 'PCPR'
				],
				'code' => [
					'@attributes' => [
						'nullFlavor' => 'UNK'
					]
				],
				'effectiveTime' => [
					'@attributes' => [
						'xsi:type' => 'IVL_TS'
					],
					'low' => [
						'@attributes' => [
							'value' => '19320924'
						]
					],
					'high' => [
						'@attributes' => [
							'value' => $this->dateNow
						]
					]
				]
			]
		];

		$documentationOf['serviceEvent']['performer'] = [
			'@attributes' => [
				'typeCode' => 'PRF'
			],
			'templateId' => [
				'@attributes' => [
					'root' => '1.3.6.1.4.1.19376.1.5.3.1.2.3'
				]
			],
			'time' => [
				'low' => [
					'@attributes' => [
						'value' => '1990'
					]
				],
				'high' => [
					'@attributes' => [
						'value' => $this->dateNow
					]
				]
			],
			'assignedEntity' => [
				'id' => [
					'@attributes' => [
						'root' => UUID::v4()
					]
				],
			]
		];

		$documentationOf['serviceEvent']['performer']['assignedEntity']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );

		$documentationOf['serviceEvent']['performer']['assignedEntity']['telecom'] = $this->telecomBuilder(
            $this->facility['phone'],
            'WP'
        );

		$documentationOf['serviceEvent']['performer']['assignedEntity']['assignedPerson'] = [
			'name' => [
				'prefix' => $this->user['title'],
				'given' => $this->user['fname'],
				'family' => $this->user['lname']
			]
		];

		$documentationOf['serviceEvent']['performer']['assignedEntity']['representedOrganization'] = [
			'id' => [
				'@attributes' => [
					'root' => '2.16.840.1.113883.4.6'
				]
			],
			'name' => [
				'prefix' => $this->facility['name']
			]
		];

		$documentationOf['serviceEvent']['performer']['assignedEntity']['representedOrganization']['telecom'] =
            $this->telecomBuilder($this->facility['phone'], 'WP');

		$documentationOf['serviceEvent']['performer']['assignedEntity']['representedOrganization']['addr'] =
            $this->addressBuilder(
                'WP',
                $this->facility['address'] . ' ' . $this->facility['address_cont'],
                $this->facility['city'],
                $this->facility['state'],
                $this->facility['postal_code'],
                $this->facility['country_code']
            );

		return $documentationOf;
	}

	/**
	 * Method getComponentOf()
     *
     * The componentOf element contains the encompassing encounter for the document. The encompassing encounter
     * represents the setting of the clinical encounter during which the document act(s) or ServiceEvent(s) occurred.
     *
     * In order to represent providers associated with a specific encounter, they are recorded within the
     * encompassingEncounter as participants.
     *
     * In a CCD, the encompassingEncounter may be used when documenting a specific encounter and its participants.
     * All relevant encounters in a CCD may be listed in the encounters section.
     *
	 * @return mixed
	 */
	private function getComponentOf() {

		$componentOf['encompassingEncounter'] = [
			'id' => [
				'@attributes' => [
					'root' => '2.16.840.1.113883.4.6'
					//'extension' => provider NPI
				]
			]
		];

		$componentOf['encompassingEncounter']['code'] = [
			'@attributes' => [
				'nullFlavor' => 'UNK'
			]
		];

		$componentOf['encompassingEncounter']['effectiveTime'] = [
			'low' => [
				'@attributes' => [
					'value' => $this->parseDate($this->encounter['service_date'])
				]
			]
		];

		$componentOf['encompassingEncounter']['effectiveTime'] = [
			'high' => [
				'@attributes' => [
					'value' => $this->parseDate($this->encounter['service_date'])
				]
			]
		];

		$responsibleParty = [
			'assignedEntity' => [
				'id' => [
					'@attributes' => [
						'root' => '2.16.840.1.113883.4.6'
					]
				],
				'assignedPerson' => [
					'name' => [
						'prefix' => $this->encounterProvider['title'],
						'given' => $this->encounterProvider['fname'],
						'family' => $this->encounterProvider['lname']
					]
				]
			]
		];
		$componentOf['encompassingEncounter']['responsibleParty'] = $responsibleParty;
		unset($responsibleParty);

		$encounterParticipant = [
			'@attributes' => [
				'typeCode' => 'ATND'
			],
			'assignedEntity' => [
				'id' => [
					'@attributes' => [
						'root' => '2.16.840.1.113883.4.6'
					]
				],
				'assignedPerson' => [
					'name' => [
						'prefix' => $this->encounterProvider['title'],
						'given' => $this->encounterProvider['fname'],
						'family' => $this->encounterProvider['lname']
					]
				]
			]
		];
		$componentOf['encompassingEncounter']['encounterParticipant'] = $encounterParticipant;
		unset($responsibleParty);

		$location = [
			'healthCareFacility' => [
				'id' => [
					'@attributes' => [
						'root' => '2.16.840.1.113883.4.6'
					]
				],
				'location' => [
					'name' => [
						'prefix' => $this->encounterFacility['name']
					],
					'addr' => $this->addressBuilder(
                        'WP',
                        $this->encounterFacility['address'] . ' ' . $this->encounterFacility['address_cont'],
                        $this->encounterFacility['city'],
                        $this->encounterFacility['state'],
                        $this->encounterFacility['postal_code'],
                        $this->encounterFacility['country_code']
                    ),
				]
			]
		];
		$componentOf['encompassingEncounter']['location'] = $location;
		unset($location);

		return $componentOf;

	}

	/**
	 * Method getInformant()
	 * @return array
	 */
	private function getInformant() {
		$informant = [];

		$informant['assignedEntity']['id']['@attributes'] = [
			'root' => '2.16.840.1.113883.4.6'
		];

		$informant['assignedEntity']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );
		$informant['assignedEntity']['telecom'] = $this->telecomBuilder($this->facility['phone'], 'WP');

		$informant['assignedEntity']['assignedPerson'] = [
			'name' => [
				'given' => $this->primaryProvider['fname'],
				'family' => $this->primaryProvider['lname']
			]
		];

		return $informant;
	}

	/**
	 * Method getInformant()
     *
     * The dataEnterer element represents the person who transferred the content, written or dictated,
     * into the clinical document. To clarify, an author provides the content found within the header or
     * body of a document, subject to their own interpretation; a dataEnterer adds an author's
     * information to the electronic system.
     *
	 * @return array
	 */
	private function getDataEnterer() {

		$dataEnterer['assignedEntity']['id']['@attributes'] = [
			'root' => '2.16.840.1.113883.4.6',
			'extension' => $this->facility['id']
		];

		$dataEnterer['assignedEntity']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'] . ' ' . $this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );
		$dataEnterer['assignedEntity']['telecom'] = $this->telecomBuilder($this->facility['phone'], 'WP');

		$dataEnterer['assignedEntity']['assignedPerson'] = [
			'name' => [
				'given' => $this->primaryProvider['fname'],
				'family' => $this->primaryProvider['lname']
			]
		];

		return $dataEnterer;
	}

	/**
	 * Method getPerformerByUid()
	 * @param $uid
	 * @return array|bool
	 */
	private function getPerformerByUid($uid) {

		$User = new User();
		$user = $User->getUser($uid);
		unset($User);

		if($user === false)
			return false;
		$user = (object)$user;

		if($user->facility_id == 0)
			return false;

		$Facilities = new Facilities();
		$facility = $Facilities->getFacility(['id' => $user->facility_id]);
		if($user === false)
			return false;
		$facility = (object)$facility;

		$performer = [
			'assignedEntity' => [
				'id' => [
					'@attributes' => [
						'root' => UUID::v4()
					]
				]
			]
		];
		$performer['assignedEntity']['addr'] = [
			'@attributes' => [
				'use' => 'HP'
			],
			'streetAddressLine' => [
				'@value' => (isset($user->street) ? $user->street : '')
			],
			'city' => [
				'@value' => (isset($user->city) ? $user->city : '')
			],
			'state' => [
				'@value' => (isset($user->state) ? $user->state : '')
			],
			'postalCode' => [
				'@value' => (isset($user->postal_code) ? $user->postal_code : '')
			],
			'country' => [
				'@value' => (isset($user->country_code) ? $user->country_code : '')
			]
		];

		$performer['assignedEntity']['telecom'] = [
			'@attributes' => [
				'value' => 'tel:' . (isset($user->phone) ? $user->phone : '')
			]
		];

		$performer['assignedEntity']['representedOrganization'] = [
			'id' => [
				'@attributes' => [
					'root' => '2.16.840.1.113883.4.6'
				]
			]
		];

		$performer['assignedEntity']['assignedPerson']['name'] = [
			'name' => [
				'prefix' => $this->primaryProvider['title'],
				'given' => $this->primaryProvider['fname'],
				'family' => $this->primaryProvider['lname']
			]
		];

		$performer['assignedEntity']['representedOrganization']['name'] = $facility->name;
		$performer['assignedEntity']['representedOrganization']['telecom'] = $this->telecomBuilder($this->facility['phone'], 'WP');
		$performer['assignedEntity']['representedOrganization']['addr'] = $this->addressBuilder(
            'WP',
            $this->facility['address'].' '.$this->facility['address_cont'],
            $this->facility['city'],
            $this->facility['state'],
            $this->facility['postal_code'],
            $this->facility['country_code']
        );


		return $performer;
	}

	/**
	 * Method addSection()
	 * @param $section
	 */
	private function addSection($section) {
		$this->xmlData['component']['structuredBody']['component'][] = $section;
	}

	private function setReasonOfVisitSection() {
		if(isset($this->encounter)){
			$reason = [
				'templateId' => [
					'@attributes' => [
						'root' => '2.16.840.1.113883.10.20.22.2.12'
					]
				],
				'code' => [
					'@attributes' => [
						'code' => '29299-5',
						'codeSystem' => '2.16.840.1.113883.6.1',
						'codeSystemName' => 'LOINC',
						'displayName' => 'Reason for Visit'
					]
				],
				'title' => 'Reason for Visit',
				'text' => $this->encounter['brief_description']
			];
			$this->addSection(['section' => $reason]);
		}
	}

	private function setInstructionsSection() {
		if(isset($this->encounter)){
			$soap = $this->Encounter->getSoapByEid($this->encounter['eid']);

			$instructions = [
				'templateId' => [
					'@attributes' => [
						'root' => '2.16.840.1.113883.10.20.22.2.45'
					]
				],
			    'code' => [
				    '@attributes' => [
					    'code' => '69730-0',
					    'codeSystem' => '2.16.840.1.113883.6.1',
					    'codeSystemName' => 'LOINC',
					    'displayName' => 'Instructions'
				    ]
			    ],
			    'title' => 'Instructions',
			    'text' => $soap['instructions'],
			    'entry' => [
				    '@attributes' => [
					    'nullFlavor' => 'NA'
				    ],
			        'act' => [
				        '@attributes' => [
					        'classCode' => 'ACT',
					        'moodCode' => 'INT'
				        ],
			            'templateId' => [
				            '@attributes' => [
					            'root' => '2.16.840.1.113883.10.20.22.4.20'
				            ]
			            ],
			            'code' => [
				            '@attributes' => [
					            'nullFlavor' => 'NA'
				            ]
			            ],
			            'statusCode' => [
				            '@attributes' => [
					            'nullFlavor' => 'NA'
				            ]
			            ]
			        ]
			    ]
			];

			$this->addSection(['section' => $instructions]);
		}
	}

	private function setReasonForReferralSection() {
		if(isset($this->encounter)){

			$Referrals = new Referrals();
			$ReferringProviders = new ReferringProviders();

			$referral = $Referrals->getPatientReferralByEid($this->encounter['eid']);
			$referringProvider = $ReferringProviders->getReferringProviderById($referral['refer_to']);

			unset($Referrals, $ReferringProviders);

			$reasonForReferral = [
				'templateId' => [
					'@attributes' => [
						'root' => '1.3.6.1.4.1.19376.1.5.3.1.3.1'
					]
				],
				'code' => [
					'@attributes' => [
						'code' => '42349-1',
						'codeSystem' => '2.16.840.1.113883.6.1',
						'codeSystemName' => 'LOINC',
						'displayName' => 'Reason for Referral'
					]
				],
				'title' => 'Reason for Referral',
				'text' => $referral['referal_reason'] . ', ' .
					$referringProvider['title'] . ' ' .
					$referringProvider['fname'] . ' ' .
					$referringProvider['lname'] . ', ' .
					$referringProvider['facilities'][0]['phone_number'] . ', ' .
					$referringProvider['facilities'][0]['name'] . ', ' .
					$referringProvider['facilities'][0]['address'] . ' ' .
					$referringProvider['facilities'][0]['address_cont'] . ', ' .
					$referringProvider['facilities'][0]['city'] . ' ' .
					$referringProvider['facilities'][0]['state'] . ' ' .
					$referringProvider['facilities'][0]['postal_code']
			];

			$this->addSection(['section' => $reasonForReferral]);
		}
	}

	/**
	 * Method setProceduresSection()
	 */
	private function setProceduresSection() {

		$Procedures = new Procedures();
		$proceduresData = $Procedures->getPatientProceduresByPid($this->pid);
		unset($Procedures);

		$procedures = [];

		if(empty($proceduresData) || $this->isExcluded('procedures')){
			$procedures['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$procedures['templateId'] = [
			'@attributes' => [
				'root' => $this->requiredProcedures ? '2.16.840.1.113883.10.20.22.2.7.1' : '2.16.840.1.113883.10.20.22.2.7'
			]
		];
		$procedures['code'] = [
			'@attributes' => [
				'code' => '47519-4',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$procedures['title'] = 'Procedures';
		$procedures['text'] = '';

		if($this->isExcluded('vitals')) {
			$this->addSection(['section' => $procedures]);
			return;
		};

		if(!empty($proceduresData)){

			$procedures['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Procedure'
									],
									[
										'@value' => 'Date'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];
			$procedures['entry'] = [];

			foreach($proceduresData as $item){
				$procedures['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['code_text']
						],
						[
							'@value' => $this->parseDateToText($item['create_date'])
						]
					]

				];

				//  Procedure Activity Procedure
				$entry = [
					'@attributes' => [
						'typeCode' => 'DRIV'
					],
					'procedure' => [
						'@attributes' => [
							'classCode' => 'PROC',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.14'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => $item['code'],
								'codeSystem' => $this->codes($item['code_type']),
								'displayName' => $item['code_text']
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						],
						'effectiveTime' => [
							'@attributes' => [
								'value' => $this->parseDate($item['create_date'])
							]
						]
					]
				];

				if($item['uid'] > 0){
					$entry['procedure']['performer'] = $this->getPerformerByUid($item['uid']);
				};

				$entry['procedure']['methodCode'] = [
					'@attributes' => [
						'nullFlavor' => 'UNK'
					]
				];

				$procedures['entry'][] = $entry;
			}
		}

		if($this->requiredProcedures || isset($procedures['entry'])){
			$this->addSection(['section' => $procedures]);
		}
		unset($proceduresData, $procedures);
	}

	/**
	 * Method setVitalsSection()
     *
     * The Vital Signs section contains relevant vital signs for the context and use case of the document type,
     * such as blood pressure, heart rate, respiratory rate, height, weight, body mass index, head circumference,
     * pulse oximetry, temperature and body surface area. The section should include notable vital signs such
     * as the most recent, maximum and/or minimum, baseline, or relevant trends.
     *
     * Vital signs are represented in the same way as other results, but are aggregated into their own section
     * to follow clinical conventions.
     */
	private function setVitalsSection() {
		$Vitals = new Vitals();
		$vitalsData = $Vitals->getVitalsByPid($this->pid);

		if(empty($vitalsData) || $this->isExcluded('vitals')){
			$vitals['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$vitals['templateId'] = [
			'@attributes' => [
				'root' => $this->requiredVitals ? '2.16.840.1.113883.10.20.22.2.4.1' : '2.16.840.1.113883.10.20.22.2.4'
			]
		];
		$vitals['code'] = [
			'@attributes' => [
				'code' => '8716-3',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$vitals['title'] = 'Vital Signs';
		$vitals['text'] = '';


		if($this->isExcluded('vitals')) {
			$this->addSection(['section' => $vitals]);
			return;
		};

		if(!empty($vitalsData)){

			$vitals['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@attributes' => [
											'align' => 'right'
										],
										'@value' => 'Date / Time:'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => [
							[
								'th' => [
									[
										'@attributes' => [
											'align' => 'left'
										],
										'@value' => 'Height'
									]
								]

							],
							[
								'th' => [
									[
										'@attributes' => [
											'align' => 'left'
										],
										'@value' => 'Weight'
									]
								]

							],
							[
								'th' => [
									[
										'@attributes' => [
											'align' => 'left'
										],
										'@value' => 'Blood Pressure'
									]
								]
							],
							[
                                'th' => [
                                    [
                                        '@attributes' => [
                                            'align' => 'left'
                                        ],
                                        '@value' => 'BMI (Body Mass Index)'
                                    ]
                                ]
							]
						]
					]
				]
			];

			$vitals['entry'] = [];

			foreach($vitalsData as $item){
                // strip date (yyyy-mm-dd hh:mm:ss => yyyymmdd)
				$date = $this->parseDate($item['date']);
                // Date
				$vitals['text']['table']['thead']['tr'][0]['th'][] = [
					'@value' => date('F j, Y', strtotime($item['date']))
				];
                // Height
				$vitals['text']['table']['tbody']['tr'][0]['td'][] = [
					'@value' => $item['height_cm'] . ' cm'
				];
                // Weight
				$vitals['text']['table']['tbody']['tr'][1]['td'][] = [
					'@value' => $item['weight_kg'] . ' kg'
				];
				// Blood Pressure
				$vitals['text']['table']['tbody']['tr'][2]['td'][] = [
					'@value' => $item['bp_systolic'] . '/' . $item['bp_diastolic'] . ' mmHg'
				];
                // BMI (Body Mass Index)
                $vitals['text']['table']['tbody']['tr'][3]['td'][] = [
                    '@value' => $item['bmi'] . ' kg/m2'
                ];
				// Code Entry
				$entry = [
					'@attributes' => [
						'typeCode' => 'DRIV'
					],
					'organizer' => [
						'@attributes' => [
							'classCode' => 'CLUSTER',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.26'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '46680005',
								'codeSystemName' => 'SNOMED CT',
								'codeSystem' => '2.16.840.1.113883.6.96',
								'displayName' => 'Vital signs'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						],
						'effectiveTime' => [
							'@attributes' => [
								'value' => $date
							]
						],
						'component' => [
							[
								'observation' => [
									'@attributes' => [
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									],
									'templateId' => [
										'@attributes' => [
											'root' => '2.16.840.1.113883.10.20.22.4.27'
										]
									],
									'id' => [
										'@attributes' => [
											'root' => UUID::v4()
										]
									],
									'code' => [
										'@attributes' => [
											'code' => '8302-2',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'Height'
										]
									],
									'statusCode' => [
										'@attributes' => [
											'code' => 'completed'
										]
									],
									'effectiveTime' => [
										'@attributes' => [
											'value' => $date
										]
									],
									'value' => [
										'@attributes' => [
											'xsi:type' => 'PQ',
											'value' => $item['height_cm'],
											'unit' => 'cm'
										]
									]
								]
							],
							[
								'observation' => [
									'@attributes' => [
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									],
									'templateId' => [
										'@attributes' => [
											'root' => '2.16.840.1.113883.10.20.22.4.2'
										]
									],
									'id' => [
										'@attributes' => [
											'root' => UUID::v4()
										]
									],
									'code' => [
										'@attributes' => [
											'code' => '3141-9',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'Weight Measured'
										]
									],
									'statusCode' => [
										'@attributes' => [
											'code' => 'completed'
										]
									],
									'effectiveTime' => [
										'@attributes' => [
											'value' => $date
										]
									],
									'value' => [
										'@attributes' => [
											'xsi:type' => 'PQ',
											'value' => $item['weight_kg'],
											'unit' => 'kg'
										]
									]
								]
							],
							[
								'observation' => [
									'@attributes' => [
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									],
									'templateId' => [
										'@attributes' => [
											'root' => '2.16.840.1.113883.10.20.22.4.2'
										]
									],
									'id' => [
										'@attributes' => [
											'root' => UUID::v4()
										]
									],
									'code' => [
										'@attributes' => [
											'code' => '8480-6',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'BP Systolic'
										]
									],
									'statusCode' => [
										'@attributes' => [
											'code' => 'completed'
										]
									],
									'effectiveTime' => [
										'@attributes' => [
											'value' => $date
										]
									],
									'value' => [
										'@attributes' => [
											'xsi:type' => 'PQ',
											'value' => $item['bp_systolic'],
											'unit' => 'mm[Hg]'
										]
									]
								]

							],
							[
								'observation' => [
									'@attributes' => [
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									],
									'templateId' => [
										'@attributes' => [
											'root' => '2.16.840.1.113883.10.20.22.4.2'
										]
									],
									'id' => [
										'@attributes' => [
											'root' => UUID::v4()
										]
									],
									'code' => [
										'@attributes' => [
											'code' => '8462-4',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'BP Diastolic'
										]
									],
									'statusCode' => [
										'@attributes' => [
											'code' => 'completed'
										]
									],
									'effectiveTime' => [
										'@attributes' => [
											'value' => $date
										]
									],
									'value' => [
										'@attributes' => [
											'xsi:type' => 'PQ',
											'value' => $item['bp_diastolic'],
											'unit' => 'mm[Hg]'
										]
									]
								]
							],
                            [
                                'observation' => [
                                    '@attributes' => [
                                        'classCode' => 'OBS',
                                        'moodCode' => 'EVN'
                                    ],
                                    'templateId' => [
                                        '@attributes' => [
                                            'root' => '2.16.840.1.113883.10.20.22.4.2'
                                        ]
                                    ],
                                    'id' => [
                                        '@attributes' => [
                                            'root' => UUID::v4()
                                        ]
                                    ],
                                    'code' => [
                                        '@attributes' => [
                                            'code' => '39156-5',
                                            'codeSystemName' => 'LOINC',
                                            'codeSystem' => '2.16.840.1.113883.6.1',
                                            'displayName' => 'Body mass index (BMI) [Ratio]'
                                        ]
                                    ],
                                    'statusCode' => [
                                        '@attributes' => [
                                            'code' => 'completed'
                                        ]
                                    ],
                                    'effectiveTime' => [
                                        '@attributes' => [
                                            'value' => $date
                                        ]
                                    ],
                                    'value' => [
                                        '@attributes' => [
                                            'xsi:type' => 'PQ',
                                            'value' => $item['bmi'],
                                            'unit' => 'kg/m2'
                                        ]
                                    ]
                                ]
                            ]
						]
					]
				];

				$vitals['entry'][] = $entry;
			}
		}

		if($this->requiredVitals || isset($vitals['entry'])){
			$this->addSection(['section' => $vitals]);
		}
		unset($vitalsData, $vitals);

	}

	/**
	 * Method setImmunizationsSection()
	 */
	private function setImmunizationsSection() {

		$Immunizations = new Immunizations();
		$immunizationsData = $Immunizations->getPatientImmunizationsByPid($this->pid);

		unset($Immunizations);

		if(empty($immunizationsData) || $this->isExcluded('immunizations')){
			$immunizations['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$immunizations['templateId'] = [
			'@attributes' => [
				'root' => $this->requiredImmunization ? '2.16.840.1.113883.10.20.22.2.2.1' : '2.16.840.1.113883.10.20.22.2.2'
			]
		];
		$immunizations['code'] = [
			'@attributes' => [
				'code' => '11369-6',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$immunizations['title'] = 'Immunizations';
		$immunizations['text'] = '';

		if($this->isExcluded('immunizations')) {
			$this->addSection(['section' => $immunizations]);
			return;
		};

		if(!empty($immunizationsData)){

			$immunizations['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Vaccine'
									],
									[
										'@value' => 'Date'
									],
									[
										'@value' => 'Status'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];
			$immunizations['entry'] = [];

			foreach($immunizationsData as $item){

				$date = preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2', $item['administered_date']);
				$administered_by = $this->User->getUserByUid($item['administered_uid']);

				$immunizations['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => ucwords($item['vaccine_name'])
						],
						[
							'@value' => date('F Y', strtotime($item['administered_date']))
						],
						[
							'@value' => 'Completed'
						]
					]
				];

				$entry['substanceAdministration'] = [
					'@attributes' => [
						'classCode' => 'SBADM',
						'moodCode' => 'EVN',
						'negationInd' => 'false',
						'nullFlavor' => 'NI'
					],
					'templateId' => [
						'@attributes' => [
							'root' => '2.16.840.1.113883.10.20.22.4.52'
						]
					],
					'id' => [
						'@attributes' => [
							'root' => UUID::v4()
						]
					],
					'code' => [
						'@attributes' => [
							'xsi:type' => 'CE',
							'code' => 'IMMUNIZ',
							'codeSystem' => '2.16.840.1.113883.5.4',
							'codeSystemName' => 'ActCode'
						]
					],
					'statusCode' => [
						'@attributes' => [
							'code' => 'completed'
						]
					],
					'effectiveTime' => [
						'@attributes' => [
							'value' => $date
						]
					]
				];

				if(isset($item['administer_amount']) && $item['administer_amount'] != ''){
					$entry['substanceAdministration']['doseQuantity'] = [
						'@attributes' => [
							'value' => $item['administer_amount'],
							'unit' => $item['administer_units']
						]
					];
				}else{
					$entry['substanceAdministration']['doseQuantity'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				$entry['substanceAdministration']['consumable'] = [
					'manufacturedProduct' => [
						'@attributes' => [
							'classCode' => 'MANU'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.54'
							]
						],
						'manufacturedMaterial' => [
							'code' => [
								'@attributes' => [
									'code' => $item['code'],
									'codeSystemName' => 'CVX',
									'codeSystem' => '2.16.840.1.113883.12.292',
									'displayName' => ucwords($item['vaccine_name'])
								]
							]
						]
					]
				];

				if(isset($item['lot_number']) && $item['lot_number'] != ''){
					$entry['substanceAdministration']['consumable']['manufacturedProduct']['manufacturedMaterial']['lotNumberText'] = $item['lot_number'];
				} else {
					$entry['substanceAdministration']['consumable']['manufacturedProduct']['manufacturedMaterial']['lotNumberText'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				if(isset($item['manufacturer']) && $item['manufacturer'] != ''){
					$entry['substanceAdministration']['consumable']['manufacturedProduct']['manufacturerOrganization'] = [
						'name' => $item['manufacturer']

					];
				} else {
					$entry['substanceAdministration']['consumable']['manufacturedProduct']['manufacturerOrganization'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				// administered by...
				$entry['substanceAdministration']['performer']['assignedEntity'] = [
					'id' => [
						'@attributes' => [
							'root' => 'NA'
						]
					]
				];
				if($administered_by !== false){
					$entry['substanceAdministration']['performer']['assignedEntity']['code'] = [
						'@attributes' => [
							'code' => $administered_by['taxonomy'],
							'codeSystem' => '2.16.840.1.114222.4.11.1066',
							'codeSystemName' => 'NUCC Health Care Provider Taxonomy',
							'displayName' => $administered_by['title'] . ' ' .
                                            $administered_by['fname'] . ' ' .
                                            $administered_by['mname'] . ' ' .
                                            $administered_by['lname']
						]
					];
				} else {
					$entry['substanceAdministration']['performer']['assignedEntity']['code'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				// immunization education\

				if(isset($item['education_date']) && $item['education_date'] != '0000-00-00'){

					$entry['substanceAdministration']['entryRelationship'] = [
						'@attributes' => [
							'typeCode' => 'SUBJ',
							'inversionInd' => 'true'
						],
						'act' => [
							'@attributes' => [
								'classCode' => 'ACT',
								'moodCode' => 'INT'
							],
							'templateId' => [
								'@attributes' => [
									'root' => '2.16.840.1.113883.10.20.22.4.20'
								]
							],
							'code' => [
								'@attributes' => [
									'code' => '171044003',
									'codeSystem' => '2.16.840.1.113883.6.96',
									'displayName' => 'immunization education'
								]
							],
							'statusCode' => [
								'@attributes' => [
									'code' => 'completed'
								]
							]
						]
					];
				}

				$immunizations['entry'][] = $entry;
			}

		}

		if($this->requiredImmunization || isset($immunizations['entry'])){
			$this->addSection(['section' => $immunizations]);
		}
		unset($immunizationsData, $immunizations);
	}

	/**
	 * Method setMedicationsSection()
     *
     * The Medications Section contains a patient's current medications and pertinent medication history.
     * At a minimum, the currently active medications are listed. An entire medication history is an option.
     * The section can describe a patient's prescription and dispense history and information about
     * intended drug monitoring.
	 */
	private function setMedicationsSection() {

		$Medications = new Medications();
		$medicationsData = $Medications->getPatientActiveMedicationsByPid($this->pid, true);
		unset($Medications);

		if(empty($medicationsData) || $this->isExcluded('medications')){
			$medications['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$medications['templateId'] = [
			'@attributes' => [
				'root' => $this->requiredMedications ? '2.16.840.1.113883.10.20.22.2.1.1' : '2.16.840.1.113883.10.20.22.2.1'
			]
		];
		$medications['code'] = [
			'@attributes' => [
				'code' => '10160-0',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$medications['title'] = 'Medications';
		$medications['text'] = '';

		if($this->isExcluded('medications')) {
			$this->addSection(['section' => $medications]);
			return;
		};

		if(!empty($medicationsData)){

			$medications['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Medication'
									],
									[
										'@value' => 'Instructions'
									],
									[
										'@value' => 'Start Date'
									],
									[
										'@value' => 'Status'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];

			$medications['entry'] = [];

			foreach($medicationsData as $item){
				$medications['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['STR'] . ' ' . $item['dose'] . ' ' . $item['form']
						],
						[
							'@value' => $item['directions']
						],
						[
							'@value' => date('F j, Y', strtotime($item['begin_date']))
						],
						[
							'@value' => isset($item['begin_date']) && $item['begin_date'] == '0000-00-00 00:00:00' ? 'No longer active' : 'Active'
						]
					]

				];

				$entry['substanceAdministration']['@attributes'] = [
					'classCode' => 'SBADM',
					'moodCode' => 'EVN'
				];

				$entry['substanceAdministration']['templateId'] = [
					'@attributes' => [
						'root' => '2.16.840.1.113883.10.20.22.4.16'
					]
				];

				$entry['substanceAdministration']['id'] = [
					'@attributes' => [
						'root' => UUID::v4()
					]
				];

				$entry['substanceAdministration']['text'] = $item['STR'];

				$entry['substanceAdministration']['statusCode'] = [
					'@attributes' => [
						'code' => 'completed'
					]
				];

				$entry['substanceAdministration']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS'
					]
				];

				$entry['substanceAdministration']['effectiveTime']['low'] = [
					'@attributes' => [
						'value' => date('Ymd', strtotime($item['begin_date']))
					]
				];

				if($item['end_date'] != '0000-00-00'){
					$entry['substanceAdministration']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => date('Ymd', strtotime($item['end_date']))
						]
					];
				} else {
					$entry['substanceAdministration']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'NI'
						]
					];
				}

				$entry['substanceAdministration']['consumable'] = [
					'manufacturedProduct' => [
						'@attributes' => [
							'classCode' => 'MANU'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.23'
							]
						],
						'manufacturedMaterial' => [
							'code' => [
								'@attributes' => [
									'code' => $item['RXCUI'],
									'codeSystem' => '2.16.840.1.113883.6.88',
									'displayName' => ucwords($item['STR']),
									'codeSystemName' => 'RxNorm'
								]
							]
						]
					]
				];

				$performer = [
					'assignedEntity' => [
						'id' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.4.6'
							]
						]
					]
				];

				$performer['assignedEntity']['addr'] = $this->addressBuilder(
					'WP',
					$this->encounterFacility['address'] . ' ' . $this->encounterFacility['address_cont'],
					$this->encounterFacility['city'],
					$this->encounterFacility['state'],
					$this->encounterFacility['postal_code'],
					$this->encounterFacility['country_code']
				);

				$performer['assignedEntity']['telecom'] = $this->telecomBuilder($this->encounterFacility['phone'], 'WP');

				$performer['assignedEntity']['representedOrganization'] = [
					'name' => $this->encounterFacility['name']
				];

				$performer['assignedEntity']['representedOrganization']['telecom'] = $this->telecomBuilder($this->encounterFacility['phone'], 'WP');
				$performer['assignedEntity']['representedOrganization']['addr'] = $this->addressBuilder(
					'WP',
					$this->encounterFacility['address'] . ' ' . $this->encounterFacility['address_cont'],
					$this->encounterFacility['city'],
					$this->encounterFacility['state'],
					$this->encounterFacility['postal_code'],
					$this->encounterFacility['country_code']
				);

				$entry['substanceAdministration']['performer'] = $performer;
				unset($performer);

				$entry['substanceAdministration']['participant'] = [
					'@attributes' => [
						'typeCode' => 'CSM'
					],
					'participantRole' => [
						'@attributes' => [
							'classCode' => 'MANU'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.24'
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '412307009',
								'codeSystem' => '2.16.840.1.113883.6.96',
								'codeSystemName' => 'SNOMED',
								'displayName' => 'drug vehicle'
							]
						],
						'playingEntity' => [
							'@attributes' => [
								'classCode' => 'MMAT'
							],
							'code' => [
								'@attributes' => [
									'nullFlavor' => 'UNK'
								]
							],
							'name' => [
								'@attributes' => [
									'nullFlavor' => 'UNK'
								]
							]
						]
					]
				];

				$medications['entry'][] = $entry;
				unset($entry);
			}

		}

		if($this->requiredMedications || isset($medications['entry'])){
			$this->addSection(['section' => $medications]);
		}
		unset($medicationsData, $medications);
	}

	/**
	 * Method setMedicationsAdministeredSection()
     *
     * The Medications Administered Section contains medications and fluids administered during a procedure.
     * The section may also contain the procedure's encounter or other activity, excluding anesthetic medications.
     * This section is not intended for ongoing medications and medication history.
	 */
	private function setMedicationsAdministeredSection() {

		$Medications = new Medications();
		$medicationsData = $Medications->getPatientAdministeredMedicationsByPidAndEid($this->encounter['pid'], $this->encounter['eid']);
		unset($Medications);

		if(empty($medicationsData) || $this->isExcluded('administered')){
			$medications['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$medications['templateId'] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.22.2.38'
			]
		];
		$medications['code'] = [
			'@attributes' => [
				'code' => '29549-3',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1',
			    'displayName' => 'Administered Medications'
			]
		];
		$medications['title'] = 'Medications Administered';
		$medications['text'] = '';

		if($this->isExcluded('administered')) {
			$this->addSection(['section' => $medications]);
			return;
		};

		if(!empty($medicationsData)){

			$medications['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'RxNorm'
									],
									[
										'@value' => 'Medication'
									],
									[
										'@value' => 'Instructions'
									],
									[
										'@value' => 'Date'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];


            // --- 3.51 Medication Activity (V2)
			$medications['entry'] = [];

			foreach($medicationsData as $item){
				$medications['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['RXCUI']
						],
						[
							'@value' => $item['STR']
						],
						[
							'@value' => $item['directions']
						],
						[
							'@value' => date('F j, Y', strtotime($item['administered_date']))
						]
					]

				];

				$entry['substanceAdministration']['@attributes'] = [
					'classCode' => 'SBADM',
					'moodCode' => 'EVN'
				];

				$entry['substanceAdministration']['templateId'] = [
					'@attributes' => [
						'root' => '2.16.840.1.113883.10.20.22.4.16'
					]
				];

				$entry['substanceAdministration']['id'] = [
					'@attributes' => [
						'root' => UUID::v4()
					]
				];

				$entry['substanceAdministration']['text'] = $item['directions'];

				$entry['substanceAdministration']['statusCode'] = [
					'@attributes' => [
						'code' => 'Active'
					]
				];

				$entry['substanceAdministration']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS'
					]
				];

				$entry['substanceAdministration']['effectiveTime']['low'] = [
					'@attributes' => [
						'nullFlavor' => 'UNK'
					]
				];

				$entry['substanceAdministration']['effectiveTime']['high'] = [
					'@attributes' => [
						'nullFlavor' => 'UNK'
					]
				];

				$entry['substanceAdministration']['consumable'] = [
					'manufacturedProduct' => [
						'@attributes' => [
							'classCode' => 'MANU'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.23'
							]
						],
						'manufacturedMaterial' => [
							'code' => [
								'@attributes' => [
									'code' => $item['RXCUI'],
									'codeSystem' => '2.16.840.1.113883.6.88',
									'displayName' => ucwords($item['STR']),
									'codeSystemName' => 'RxNorm'
								]
							]
						]
					]
				];

				$performer = [
					'assignedEntity' => [
						'id' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.4.6'
							]
						]
					]
			    ];

				$performer['assignedEntity']['addr'] = $this->addressBuilder(
					'WP',
					$this->encounterFacility['address'] . ' ' . $this->encounterFacility['address_cont'],
					$this->encounterFacility['city'],
					$this->encounterFacility['state'],
					$this->encounterFacility['postal_code'],
					$this->encounterFacility['country_code']
				);

				$performer['assignedEntity']['telecom'] = $this->telecomBuilder($this->encounterFacility['phone'], 'WP');

				$performer['assignedEntity']['representedOrganization'] = [
					'name' => $this->encounterFacility['name']
				];

				$performer['assignedEntity']['representedOrganization']['telecom'] = $this->telecomBuilder($this->encounterFacility['phone'], 'WP');
				$performer['assignedEntity']['representedOrganization']['addr'] = $this->addressBuilder(
					'WP',
					$this->encounterFacility['address'] . ' ' . $this->encounterFacility['address_cont'],
					$this->encounterFacility['city'],
					$this->encounterFacility['state'],
					$this->encounterFacility['postal_code'],
					$this->encounterFacility['country_code']
				);

				$entry['substanceAdministration']['performer'] = $performer;
				unset($performer);

				$entry['substanceAdministration']['participant'] = [
					'@attributes' => [
						'typeCode' => 'CSM'
					],
				    'participantRole' => [
					    '@attributes' => [
						    'classCode' => 'MANU'
					    ],
				        'templateId' => [
					        '@attributes' => [
						        'root' => '2.16.840.1.113883.10.20.22.4.24'
					        ]
				        ],
				        'code' => [
					        '@attributes' => [
						        'code' => '412307009',
						        'codeSystem' => '2.16.840.1.113883.6.96',
						        'codeSystemName' => 'SNOMED',
						        'displayName' => 'drug vehicle'
					        ]
				        ],
				        'playingEntity' => [
					        '@attributes' => [
						        'classCode' => 'MMAT'
					        ],
				            'code' => [
					            '@attributes' => [
						            'nullFlavor' => 'UNK'
					            ]
				            ],
				            'name' => [
					            '@attributes' => [
						            'nullFlavor' => 'UNK'
					            ]
				            ]
				        ]
				    ]
				];

			    $entry['substanceAdministration']['precondition'] = [
			        '@attributes' => [
				        'typeCode' => 'PRCN'
			        ],
		            'criterion' => [
			            'code' => [
				            '@attributes' => [
					            'nullFlavor' => 'UNK'
				            ]
			            ],
			            'value' => [
				            '@attributes' => [
					            'xsi:type' => 'CD',
					            'nullFlavor' => 'UNK'
				            ]
			            ]
		            ]
				];

				$medications['entry'][] = $entry;
				unset($entry);
			}

		}

		if($this->requiredMedications || isset($medications['entry'])){
			$this->addSection(['section' => $medications]);
		}
		unset($medicationsData, $medications);
	}

    /**
     * CARE PLAN FRAMEWORK
     *
     * A Care Plan is a consensus-driven dynamic plan that represents all of a patient’s and Care Team Members’
     * A Care Plan is a consensus-driven dynamic plan that represents all of a patient’s and Care Team Members’
     * prioritized concerns, goals, and planned interventions. It serves as a blueprint shared by all
     * Care Team Members, including the patient, to guide the Care Team Members (including Patients,
     * their caregivers, providers and patient’s care. A Care Plan integrates multiple interventions proposed by
     * multiple providers and disciplines for multiple conditions.
     *
     * A Care Plan represents one or more Plan(s) of Care and serves to reconcile and resolve conflicts between
     * the various Plans of Care developed for a specific patient by different providers. While both a plan of
     * care and a care plan include the patient’s life goals and require Care Team Members (including patients)
     * to prioritize goals and interventions, the reconciliation process becomes more complex as the number of
     * plans of care increases. The Care Plan also serves to enable longitudinal coordination of care.
     *
     * The CDA Care Plan represents an instance of this dynamic Care Plan at a point in time.
     * The CDA document itself is NOT dynamic.
     *
     * Key differentiators between a Care Plan CDA and CCD (another “snapshot in time” document):
     * •  Requires relationships between various acts:
     * o  Health Concerns
     * o  Problems
     * o  Interventions
     * o  Goals
     * o  Outcomes
     * •  Provides the ability to identify patient and provider priorities with each act
     * •  Provides a header participant to indicate occurrences of Care Plan review
     *
     */
    private function setCareOfPlanSection(){

        if(!$this->requiredCareOfPlan) return;

        // 1.1 - Care Plan (NEW)
        $careOfPlan['template'] = [
            '@attributes' => [
                'root' => '2.16.840.1.113883.10.20.22.1.15'
            ]
        ];
        $careOfPlan['id'] = [
            '@attributes' => [
                'root' => UUID::v4()
            ]
        ];
        $planOfCare['code'] = [
            '@attributes' => [
                'code' => 'CarePlan-X',
                'codeSystemName' => 'LOINC',
                'codeSystem' => '2.16.840.1.113883.6.1'
            ]
        ];

        // 1.1.1 - authenticator
        // [0..1] Zero or one
        // This authenticator represents patient agreement or sign-off of the Care Plan
        $careOfPlan['authenticator'] = [
            'time' => [
                '@attributes' => [
                    'value' => '' // Date of the patient sign-off
                ]
            ],
            'signatureCode' => [
                '@attributes' => [
                    'code' => 'S'
                ]
            ],
            'sdtc:signatureText' => [
                '@attributes' => [
                    'mediaType' => 'text/xml',
                    'representation' => 'B64'
                ],
                base64_encode('')
            ],
            'assignedEntity' => [
                'id' => [
                    '@attributes' => [
                        'extension' => '996-756-495',
                        'root' => '2.16.840.1.113883.19.5'
                    ]
                ],
                'code' => [
                    '@attributes' => [
                        'code' => 'ONESELF',
                        'displayName' => 'Oneself',
                        'codeSystem' => '2.16.840.1.113883.5.111',
                        'codeSystemName' => 'HL7 Role code'
                    ]
                ]
            ]
        ];

        // 1.1.2 - participant - Patient Itself
        // [0..*] Zero or more
        // This participant represents the Care Plan Review. If the date in the time element is in the past,
        // then this review has already taken place. If the date in the time element is in the future,
        // then this is the date of the next scheduled review.
        $careOfPlan['participant'] = [
            '@attributes' => [
                'typeCode' => 'IND'
            ],
            'functionCode' => [
                '@attributes' => [
                    'code' => '425268008',
                    'codeSystem' => '2.16.840.1.113883.6.96',
                    'codeSystemName' => 'SNOMED CT',
                    'displayName' => 'Review of Care Plan'
                ]
            ],
            'time' => [
                '@attributes' => [
                    'value' => '' // Check the participant description for more info.
                ]
            ],
            // Code	Code System	Print Name
            // ONESELF  RoleCode    self
            // MTH      RoleCode	mother
            // FTH      RoleCode	father
            // DAU      RoleCode	natural daughter
            // SON      RoleCode	natural son
            // DAUINLAW	RoleCode	daughter in-law
            // SONINLAW	RoleCode	son in-law
            // GUARD	RoleCode	guardian
            // HPOWATT	RoleCode	healthcare power of attorney
            'associatedEntity' => [
                '@attributes' => [
                    'classCode' => 'ONESELF'
                ],
                'id' => [
                    '@attributes' => [
                        'root' => UUID::v4()
                    ]
                ]
            ]
        ];

        // 1.1.3 - participant - Care giver (Mother, Father, Guardian, ect.)
        // [0..*] Zero or more
        // This participant identifies individuals who support the patient such as a relative or caregiver.
        $careOfPlan['participant'] = [
            '@attributes' => [
                'typeCode' => 'IND'
            ],
            'functionCode' => [
                '@attributes' => [
                    'code' => '407543004',
                    'displayName' => 'Primary Carer',
                    'codeSystem' => '2.16.840.1.113883.6.96',
                    'codeSystemName' => 'SNOMED-CT'
                ]
            ],
            'associatedEntity' => [
                '@attributes' => [
                    'classCode' => 'CAREGIVER'
                ],
                'code' => [
                    '@attributes' => [
                        'code' => '', // TODO: Take this information from Patient Contacts
                        'codeSystem' => '2.16.840.1.113883.5.111'
                    ]
                ],
                'addr' => [
                    'streetAddressLine' => '', // TODO: Take this information from Patient Contacts
                    'city' => '', // TODO: Take this information from Patient Contacts
                    'state' => '', // TODO: Take this information from Patient Contacts
                    'postalCode' => '', // TODO: Take this information from Patient Contacts
                    'country' => '' // TODO: Take this information from Patient Contacts
                ],
                'telecom' => [
                    'value' => '', // TODO: Take this information from Patient Contacts
                    'use' => '' // TODO: Take this information from Patient Contacts
                ],
                'associatedPerson' => [
                    'name' => [
                        'prefix' => '', // TODO: Take this information from Patient Contacts
                        'given' => '', // TODO: Take this information from Patient Contacts
                        'family' => '' // TODO: Take this information from Patient Contacts
                    ]
                ]
            ]
        ];

        // 1.1.4 - documentationOf
        // [1..1] Only one
        // The documentationOf relationship in a Care Plan contains the representation of providers who are
        // wholly or partially responsible for the safety and well-being of a subject of care.
        $careOfPlan['documentationOf'] = [
            'serviceEvent' => [
                '@attributes' => [
                    'classCode' => 'PCPR'
                ],
                'effectiveTime' => '' // TODO: ??? Don't know what date will be.
            ]
        ];

        // 1.1.5 - performer
        // [1..*] - Multiple entries
        // The performer(s) represents the healthcare providers involved in the current or historical care of
        // the patient.The patient’s key healthcare providers would be listed here which would include the
        // primary physician and any active consulting physicians, therapists, counselors, and care team members.
        $careOfPlan['performer'] = [
            '@attributes' => [
                'typeCode' => 'PRF'
            ],
            'time' => [
                '@attributes' => [
                    'value' => '' // TODO: ??? Don't know what date will be.
                ]
            ],
            'assignedEntity' => [
                'id' => [
                    '@attributes' => [
                        'extension' => '', // TODO: What value ???
                        'root' => UUID::v4()
                    ]
                ],
                'code' => [
                    '@attributes' => [
                        'code' => '59058001',
                        'codeSystem' => '2.16.840.1.113883.6.96',
                        'codeSystemName' => 'SNOMED CT',
                        'displayName' => 'General Physician'
                    ]
                ],
                'addr' => [
                    'streetAddressLine' => '', // TODO: Take this information from provider
                    'city' => '', // TODO: Take this information from provider
                    'state' => '', // TODO: Take this information from provider
                    'postalCode' => '', // TODO: Take this information from provider
                    'country' => '' // TODO: Take this information from provider
                ],
                'telecom' => [
                    'value' => '', // TODO: Take this information from provider
                    'use' => '' // TODO: Take this information from provider
                ],
                'associatedPerson' => [
                    'name' => [
                        'prefix' => '', // TODO: Take this information from provider
                        'given' => '', // TODO: Take this information from provider
                        'family' => '' // TODO: Take this information from provider
                    ]
                ]
            ]
        ];

        // 1.1.6 - relatedDocument
        // [0..1] Zero or more
        // The Care Plan is continually evolving and dynamic. The Care Plan CDA instance is NOT dynamic.
        // Each time a Care Plan CDA is generated it represents a snapshot in time of the Care Plan at that moment.
        // Whenever a care provider or patient generates a Care Plan, it should be noted through relatedDocument
        // whether the current Care Plan replaces or appends another Care Plan. The relatedDocumentTypeCode
        // indicates whether the current document is an addendum to the ParentDocument (APND (append)) or the
        // current document is a replacement of the ParentDocument (RPLC (replace)).
        $careOfPlan['relatedDocument'] = [
            '@attributes' => [
                'typeCode' => 'RPLC'
            ],
            'parentDocument' => [
                'id' => [
                    '@attributes' => [
                        'root' => UUID::v4()
                    ]
                ],
                'code' => [
                    '@attributes' => [
                        'code' => 'CarePlan-X',
                        'codeSystem' => '2.16.840.1.113883.6.1',
                        'codeSystemName' => 'LOINC',
                        'displayName' => 'Care Plan'
                    ]
                ],
                'setId' => [
                    '@attributes' => [
                        'root' => UUID::v4()
                    ]
                ],
                'versionNumber' => [
                    '@attributes' => [
                        'value' => '1'
                    ]
                ]
            ]
        ];

        // 1.1.7 - structuredBody
        //
        // * 2.22 - Health Concerns Section (NEW)
        // [1..1] Only one
        // The Health Concerns section contains data that describes an interest or worry about a health state or
        // process that has the potential to require attention, intervention or management.
        //
        // * Goals Section (NEW)
        // [1..1] Only one
        // This template represents patient Goals.  A goal is a defined outcome or condition to be achieved in
        // the process of patient care. Goals include patient-defined goals (e.g., alleviation of health concerns,
        // positive outcomes from interventions, longevity, function, symptom management, comfort) and
        // clinician-specific goals to achieve desired and agreed upon outcomes.
        //
        // * Interventions Section (V2)
        // [1..1] Only one
        // This template represents Interventions.  Interventions are actions taken to maximize the prospects of
        // achieving the patient’s or provider’s goals of care, including the removal of barriers to success.
        // Interventions can be planned, ordered, historical, etc.
        //
        // Interventions include actions that may be ongoing (e.g. maintenance medications that the patient is taking,
        // or monitoring the patient’s health status or the status of an intervention).
        //
        // Instructions are a subset of interventions and may include self-care instructions.
        // Instructions are information or directions to the patient and other providers including how to care
        // for the individual’s condition, what to do at home, when to call for help, any additional appointments,
        // testing, and changes to the medication list or medication instructions, clinical guidelines and a
        // summary of best practice.
        //
        // * Health Status Evaluations/Outcomes Section (NEW)
        // [1..1] Only one
        // This template contains observations regarding the outcome of care resulting from the interventions used to
        // treat the patient. These observations represent status, at points in time, related to established
        // care plan goals and/or interventions.
        $careOfPlan['structuredBody'] = [];


        // * 2.22 - Health Concerns Section (NEW)
        // [1..1] Only one
        // The Health Concerns section contains data that describes an interest or worry about a health state or
        // process that has the potential to require attention, intervention or management.
        $structuredBody_Section['section'] = [
            'templateId' => [
                'root' => '2.16.840.1.113883.10.20.22.2.58'
            ],
            'code' => [
                '@attributes' => [
                    'code' => '46030-3',
                    'displayName' => 'Health Conditions Section',
                    'codeSystem' => '2.16.840.1.113883.6.1',
                    'codeSystemName' => 'LOINC'
                ],
                'title' => 'Health Concerns Section',
                'text' => '',
                // 3.40	- Health Status Observation (V2)
                // This template represents  information about the overall health status of the patient.
                // To represent the impact of a specific problem or concern related to the patient's expected
                // health outcome use the Prognosis Observation Template 2.16.840.1.113883.10.20.22.4.113.
                'entry' => [
                    'observation' => [
                        '@attributes' => [
                            'classCode' => 'OBS',
                            'moodCode' => 'EVN'
                        ],
                        'templateId' => [
                            '@attributes' => [
                                'root' => '2.16.840.1.113883.10.20.22.4.5'
                            ]
                        ],
                        'code' => [
                            '@attributes' => [
                                'code' => '11323-3',
                                'codeSystem' => '2.16.840.1.113883.6.1',
                                'codeSystemName' => 'LOINC',
                                'displayName' => 'Health status'
                            ]
                        ],
                        'text' => [
                            'reference' => [
                                '@attributes' => [
                                    'value' => '"#healthstatus' // Narrated Health Status Observation
                                ]
                            ]
                        ],
                        'statusCode' => [
                            '@attributes' => [
                                'code' => 'completed'
                            ]
                        ],
                        // Value Set: 193. HealthStatus (V2) 2.16.840.1.113883.1.11.20.12.2
                        // Represents the general health status of the patient.
                        // Only one [1..1]
                        //
                        // Code	        Code System	Print Name
                        // 81323004     SNOMED CT	Alive and well
                        // 313386006	SNOMED CT	In remission
                        // 162467007	SNOMED CT	Symptom free
                        // 161901003	SNOMED CT	Chronically ill
                        // 271593001	SNOMED CT	Severely ill
                        // 21134002     SNOMED CT	Disabled
                        // 161045001	SNOMED CT	Severely disabled
                        // 135818000	SNOMED CT	General health poor
                        // 135815002	SNOMED CT	General health good
                        // 135816001	SNOMED CT	General health excellent
                        // TODO: May be we need to modify the database and GaiaEHR to support this code
                        'value' => [
                            '@attributes' => [
                                'xsi:type' => 'CD',
                                'code' => '81323004',
                                'codeSystem' => '2.16.840.1.113883.6.96',
                                'codeSystemName' => 'SNOMED CT',
                                'displayName' => 'Alive and well'
                            ]
                        ]
                    ]
                ],
                // 2.21	Goals Section
				//This template represents patient Goals.  A goal is a defined outcome or condition to be achieved
				// in the process of patient care. Goals include patient-defined goals (e.g., alleviation of health
				// concerns, positive outcomes from interventions, longevity, function, symptom management, comfort)
				// and clinician-specific goals to achieve desired and agreed upon outcomes.
                'section' => [
                    'templateId' => [
                        '@attributes' => [
                            'root' => '2.16.840.1.113883.10.20.22.2.60'
                        ]
                    ],
					'code' => [
						'code' => '61146-7',
						'displayName' => 'Goals',
						'codeSystem' => '2.16.840.1.113883.6.1',
						'codeSystemName' => 'LOINC'
					],
					'title' => 'Goals Section',
					'text' => '', // TODO: Narrative Goal Section
					'entry' => [
						// 3.37	Goal Observation
						// This template represents a patient care goal.  A Goal Observation template may have related
						// components that are acts, encounters, observations, procedures, substance administrations
						// or supplies.
						//
						// A goal may be a patient or provider goal.  If the author is set to the
						// recordTarget (patient), this is a patient goal.  If the author is set to a provider,
						// this is a provider goal. If both patient and provider are set as authors, this is a
						// negotiated goal.
						//
						// A goal usually has a related health concern and/or risk.
						//
						// A goal can have components consisting of other goals (milestones), these milestones are
						// related to the overall goal through entryRelationships.
						'observation' => [
							'@attributes' => [
								'classCode' => 'OBS',
								'moodCode' => 'GOL'
							],
							'templateId' => [
                                '@attributes' => [
                                    'root' => '2.16.840.1.113883.10.20.22.4.44.2'
                                ]
							],
                            'templateId' => [
                                '@attributes' => [
                                    'root' => '2.16.840.1.113883.10.20.22.4.121'
                                ]
                            ],
                            'id' => [
                                '@attributes' => [
                                    'root' => UUID::v4()
                                ]
                            ],
                            'code' => [
                                '@attributes' => [
                                    'code' => '252465000', // TODO: Code for the Goal Observation
                                    'codeSystem' => '2.16.840.1.113883.6.96',
                                    'codeSystemName' => 'SNOMED CT',
                                    'displayName' => 'Pulse oximetry' // TODO: Name for the Goal Observation
                                ]
                            ],
                            'statusCode' => [
                                '@attributes' => [
                                    'code' => 'active' // TODO: This should be the status of the GOAL
                                ]
                            ],
                            'effectiveTime' => [
                                '@attributes' => [
                                    'value' => '20130902' // TODO: This should be the starting date of the Goal
                                ]
                            ],
                            'value' => [
                                '@attributes' => [
                                    'xsi:type' => 'IVL_PQ' // TODO: This should be the coding for the measurement
                                ],
                                'low' => [
                                    '@attributes' => [
                                        'value' => '92', // TODO: This should be the Goal value
                                        'unit' => '%' // TODO: This should be the Goal value unit
                                    ]
                                ]
                            ],
                            // If the author is set to the recordTarget (patient), this is a patient goal.
                            // If the author is set to a provider, this is a provider goal.
                            // If both patient and provider are set as authors, this is a negotiated goal.
                            'author' => [
                                '@attributes' => [
                                    'typeCode' => 'AUT'
                                ],
                                'templateId' => [
                                    '@attributes' => [
                                        'root' => '2.16.840.1.113883.10.20.22.4.119'
                                    ]
                                ],
                                'time' => [
                                    '@attributes' => [
                                        'value' => '20130730' // TODO: This should be the when the goal was established
                                    ]
                                ],
                                'assignedAuthor' => [
                                    'id' => UUID::v4(),
                                    'code' => [
                                        'code' => '163W00000X', // TODO: This should be the care provider
                                        'displayName' => 'Registered nurse', // TODO: This should be the care provider
                                        'codeSystem' => '2.16.840.1.113883.6.101', // TODO: This should be the care provider
                                        'codeSystemName' => 'Health Care Provider Taxonomy' // TODO: This should be the care provider
                                    ],
                                    'assignedPerson' => [
                                        'name' => [
                                            'given' => 'Nurse', // TODO: This should by the care provider name
                                            'family' => 'Florence', // TODO: This should by the care provider name
                                            'suffix' => 'RN' // TODO: This should by the care provider name
                                        ]
                                    ]
                                ]
                            ],
                            'author' => [
                                '@attributes' => [
                                    'typeCode' => 'AUT'
                                ],
                                'templateId' => [
                                    '@attributes' => [
                                        'root' => '2.16.840.1.113883.10.20.22.4.119'
                                    ]
                                ],
                                'time' => '',
                                'assignedAuthor' => [
                                    // TODO: This should be a pointer back to the patient ID, so you don't have to put
                                    // all the information about the patient all over again.
                                    'id' => ''
                                ]
                            ]
						]
					]
                ]
            ]
        ];

    }

	/**
	 * Method setPlanOfCareSection() TODO
	 */
	private function setPlanOfCareSection() {

		// Table moodCode Values
        // -----------------------------------------------------------------------
        // Code             | Definition
        // -----------------------------------------------------------------------
        // EVN (event)      | The entry defines an actual occurrence of an event.
        // INT (intent)     | The entry is intended or planned.
        // PRMS (promise)   | A commitment to perform the stated entry.
        // PRP (proposal)   | A proposal that the stated entry be performed.
        // RQO (request)    | A request or order to perform the stated entry.
        // -----------------------------------------------------------------------
		$Orders = new Orders();
		$planOfCareData['OBS'] = $Orders->getOrderWithoutResultsByPid($this->pid);

		$planOfCareData['ACT'] = [];
		$planOfCareData['ENC'] = [];

		$CarePlanGoals = new CarePlanGoals();
		$planOfCareData['PROC'] = $CarePlanGoals->getPatientCarePlanGoalsByPid($this->pid);

		$hasData = !empty($planOfCareData['OBS']) ||
                    !empty($planOfCareData['ACT']) ||
                    !empty($planOfCareData['ENC']) ||
                    !empty($planOfCareData['PROC']);

		if($this->isExcluded('planofcare') || !$hasData){
			$planOfCare['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}

		$planOfCare['templateId'] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.22.2.10'
			]
		];
		$planOfCare['code'] = [
			'@attributes' => [
				'code' => '18776-5',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$planOfCare['title'] = 'Plan of Care';
		$planOfCare['text'] = '';


		if($this->isExcluded('planofcare')) {
			$this->addSection(['section' => $planOfCare]);
			return;
		};

		// if one of these are not empty
		if($hasData){
			$planOfCare['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Planned Activity'
									],
									[
										'@value' => 'Planned Date'
									]
								]
							]
						]
					]
				]
			];

			$planOfCare['text']['table']['tbody']['tr'] = [];
			$planOfCare['entry'] = [];

			// Observations
			foreach($planOfCareData['OBS'] as $item){
				$planOfCare['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['description']
						],
						[
							'@value' => $this->parseDate($item['date_ordered'])
						]
					]
				];

				$planOfCare['entry'][] = [
					'@attributes' => [
						'typeCode' => 'DRIV'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'RQO'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.44'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => $item['code'],
								'codeSystemName' => $item['code_type'],
								'codeSystem' => $this->codes($item['code_type']),
								'displayName' => $item['description']
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'new'
							]
						],
						'effectiveTime' => [
							'center' => [
								'@attributes' => [
									'value' => $this->parseDate($item['date_ordered'])
								]
							]
						]
					]
				];
			}

			// Activities
			foreach($planOfCareData['ACT'] as $item){
				$planOfCare['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => 'Test'
							//TODO
						],
						[
							'@value' => 'Ting'
							//TODO
						]
					]
				];

				$planOfCare['entry'][] = [
					'act' => [
						'@attributes' => [
							'classCode' => 'ACT',
							'moodCode' => 'RQO'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.39'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '23426006',
								//TODO
								'codeSystemName' => 'SNOMEDCT',
								'codeSystem' => '2.16.840.1.113883.6.96',
								//TODO
								'displayName' => 'Pulmonary function test',
								//TODO
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'new'
							]
						],
						'effectiveTime' => [
							'@attributes' => [
								'center' => '20000421'
								//TODO
							]
						]
					]
				];
			}

			/**
			 * Encounters...
			 */
			foreach($planOfCareData['ENC'] as $item){
				$planOfCare['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => 'Test'
							//TODO
						],
						[
							'@value' => 'Ting'
							//TODO
						]
					]
				];

				$planOfCare['entry'][] = [
					'act' => [
						'@attributes' => [
							'classCode' => 'INT',
							'moodCode' => 'INT'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.40'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '23426006',
								//TODO
								'codeSystemName' => 'SNOMEDCT',
								'codeSystem' => '2.16.840.1.113883.6.96',
								//TODO
								'displayName' => 'Pulmonary function test',
								//TODO
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'new'
							]
						],
						'effectiveTime' => [
							'@attributes' => [
								'center' => '20000421'
								//TODO
							]
						]
					]
				];
			}

			/**
			 * Procedures...
			 */
			foreach($planOfCareData['PROC'] as $item){
				$planOfCare['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['goal']
						],
						[
							'@value' => $this->parseDate($item['plan_date'])
						]
					]
				];

				$planOfCare['entry'][] = [
					'procedure' => [
						'@attributes' => [
							'moodCode' => 'RQO',
							'classCode' => 'PROC'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.41'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => $item['goal_code'],
								'codeSystemName' => $item['goal_code_type'],
								'codeSystem' => $this->codes($item['goal_code_type']),
								'displayName' => htmlentities($item['goal']),
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'new'
							]
						],
						'effectiveTime' => [
							'center' => [
								'@attributes' => [
									'value' => $this->parseDate($item['plan_date'])
								]
							]
						]
					]
				];
			}
		}

		if($this->requiredPlanOfCare || isset($planOfCare['entry'])){
			$this->addSection(['section' => $planOfCare]);
		}
		unset($planOfCareData, $planOfCare);
	}

	/**
	 * Method setProblemsSection()
     *
     * This section lists and describes all relevant clinical problems at the time the document is generated.
     * At a minimum, all pertinent current and historical problems should be listed.  Overall health status may
     * be represented in this section.
	 */
	private function setProblemsSection() {

		$ActiveProblems = new ActiveProblems();
		$problemsData = $ActiveProblems->getPatientAllProblemsByPid($this->pid);
		unset($ActiveProblems);

		if($this->isExcluded('problems') || empty($problemsData)){
			$problems['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}

		$problems['templateId'][] = [
			'@attributes' => [
				'root' => $this->requiredProblems ? '2.16.840.1.113883.10.20.22.2.5.1' : '2.16.840.1.113883.10.20.22.2.5'
			]
		];

		$problems['templateId'][] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.3.88.11.83.103'
			]
		];

		$problems['code'] = [
			'@attributes' => [
				'code' => '11450-4',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$problems['title'] = 'Problems';
		$problems['text'] = '';

		if($this->isExcluded('problems')) {
			$this->addSection(['section' => $problems]);
			return;
		};

		if(!empty($problemsData)){

			$problems['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Condition'
									],
									[
										'@value' => 'Effective Dates'
									],
									[
										'@value' => 'Condition Status'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];

			$problems['entry'] = [];

			foreach($problemsData as $item){

				$dateText = $this->parseDate($item['begin_date']) . ' - ';
				if($item['end_date'] != '0000-00-00')
					$dateText .= $this->parseDate($item['end_date']);

				$problems['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['code_text']
						],
						[
							'@value' => $dateText
						],
						[
							'@value' => $item['status']
						]
					]

				];

				$entry = [
					'act' => [
						'@attributes' => [
							'classCode' => 'ACT',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.3'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => 'CONC',
								'codeSystemName' => 'ActClass',
								'codeSystem' => '2.16.840.1.113883.5.6',
								'displayName' => 'Concern'
							]
						],
						'statusCode' => [
							'@attributes' => [
                                // active ||  suspended ||  aborted ||  completed
								'code' => 'active'
							]
						]
					]
				];

				$entry['act']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS'
					]
				];
				$entry['act']['effectiveTime']['low'] = [
					'@attributes' => [
						'value' => $this->parseDate($item['begin_date'])
					]
				];
				if($item['end_date'] != '0000-00-00'){
					$entry['act']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} else {
					$entry['act']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'NI'
						]
					];
				}

				$entry['act']['entryRelationship'] = [
					'@attributes' => [
						'typeCode' => 'SUBJ'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.4'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],

						 // 404684003    SNOMEDCT    Finding
						 // 409586006    SNOMEDCT    Complaint
						 // 282291009    SNOMEDCT    Diagnosis
						 // 64572001    SNOMEDCT    Condition
						 // 248536006    SNOMEDCT    Functional limitation
						 // 418799008    SNOMEDCT    Symptom
						 // 55607006    SNOMEDCT    Problem
						 // 373930000    SNOMEDCT    Cognitive function finding
						'code' => [
							'@attributes' => [
								'code' => '55607006',
								'displayName' => 'Problem',
								'codeSystemName' => 'SNOMED CT',
								'codeSystem' => '2.16.840.1.113883.6.96'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						]
					]
				];

				$entry['act']['entryRelationship']['observation']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS'
					]
				];
				$entry['act']['entryRelationship']['observation']['effectiveTime']['low'] = [
					'@attributes' => [
						'value' => $this->parseDate($item['begin_date'])
					]
				];
				if($item['end_date'] != '0000-00-00'){
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} else {
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'NI'
						]
					];
				}

                $entry['act']['entryRelationship']['observation']['value'] = [
                    '@attributes' => [
                        'xsi:type' => 'CD',
                        'code' => $item['code'],
                        'codeSystemName' => $item['code_type'],
                        'codeSystem' => $this->codes($item['code_type'])
                    ]
                ];

				$entry['act']['entryRelationship']['observation']['entryRelationship'] = [
					'@attributes' => [
						'typeCode' => 'REFR'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.6'
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '33999-4',
								'displayName' => 'Status',
								'codeSystemName' => 'LOINC',
								'codeSystem' => '2.16.840.1.113883.6.1'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						],

						 // 55561003     SNOMEDCT    Active
						 // 73425007     SNOMEDCT    Inactive
						 // 413322009    SNOMEDCT    Resolved
						'value' => [
							'@attributes' => [
								'xsi:type' => 'CD',
								'code' => $this->CombosData->getCodeValueByListIdAndOptionValue(112, $item['status']),
								'displayName' => $item['status'],
								'codeSystemName' => 'SNOMED CT',
								'codeSystem' => '2.16.840.1.113883.6.96'
							]
						]
					]
				];
				$problems['entry'][] = $entry;
				unset($entry);
			}

		}

		if($this->requiredProblems || !empty($problems['entry'])){
			$this->addSection(['section' => $problems]);
		}
		unset($problemsData, $problems);
	}

	/**
	 * Method setAllergiesSection()
	 */
	private function setAllergiesSection() {
		$Allergies = new Allergies();

		$allergiesData = $Allergies->getPatientAllergiesByPid($this->pid);
		unset($Allergies);

		if($this->isExcluded('allergies') || empty($allergiesData)){
			$allergies['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$allergies['templateId'] = [
			'@attributes' => [
				'root' => $this->requiredAllergies ? '2.16.840.1.113883.10.20.22.2.6.1' : '2.16.840.1.113883.10.20.22.2.6'
			]
		];
		$allergies['code'] = [
			'@attributes' => [
				'code' => '48765-2',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$allergies['title'] = 'Allergies, Adverse Reactions, Alerts';
		$allergies['text'] = '';

		if($this->isExcluded('allergies')) {
			$this->addSection(['section' => $allergies]);
			return;
		};

		if(!empty($allergiesData)){
			$allergies['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Substance'
									],
									[
										'@value' => 'Reaction'
									],
									[
										'@value' => 'Severity'
									],
									[
										'@value' => 'Status'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];

			$allergies['entry'] = [];

			foreach($allergiesData as $item){

				$hasBeginDate = preg_match('/^\d{4}-\d{2}-\d{2}/', $item['begin_date']);
				$hasEndDate = preg_match('/^\d{4}-\d{2}-\d{2}/', $item['end_date']);

				$allergies['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['allergy']
						],
						[
							'@value' => $item['reaction']
						],
						[
							'@value' => $item['severity']
						],
						[
							'@value' => 'Status Data'
						]
					]
				];

				$entry = [
					'act' => [
						'@attributes' => [
							'classCode' => 'ACT',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.30'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '48765-2',
								'codeSystemName' => 'LOINC',
								'codeSystem' => '2.16.840.1.113883.6.1'
							]
						]
					]
				];

				$entry['act']['statusCode'] = [
					'@attributes' => [
                        // use snomed code for active
                       'code' => $item['status_code'] == '55561003' ? 'active' : 'completed'
					]
				];

				$entry['act']['effectiveTime']['low'] = [
					'@attributes' => [
						'value' => $this->parseDate($item['begin_date'])
					]
				];

				if($hasEndDate){
					$entry['act']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} elseif($entry['act']['statusCode'] == 'completed' && !$hasEndDate) {
					$entry['act']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				$entry['act']['entryRelationship'] = [
					'@attributes' => [
						'typeCode' => 'SUBJ'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.7'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => 'ASSERTION',
								'codeSystem' => '2.16.840.1.113883.5.4'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						]
					]
				];

                // If it is unknown when the allergy began, this effectiveTime
                // SHALL contain low/@nullFLavor="UNK" (CONF:9103)
				$entry['act']['entryRelationship']['observation']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS',
					]
				];

				if($hasBeginDate){
					$entry['act']['entryRelationship']['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['begin_date'])
						]
					];
				} else {
					$entry['act']['entryRelationship']['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'nullFLavor' => 'UNK'
						]
					];
				}

				if($hasEndDate){
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} elseif($entry['act']['statusCode'] == 'completed' && !$hasEndDate) {
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				 // 420134006    SNOMEDCT    Propensity to adverse reactions
				 // 418038007    SNOMEDCT    Propensity to adverse reactions to substance
				 // 419511003    SNOMEDCT    Propensity to adverse reactions to drug
				 // 418471000    SNOMEDCT    Propensity to adverse reactions to food
				 // 419199007    SNOMEDCT    Allergy to substance
				 // 416098002    SNOMEDCT    Drug allergy
				 // 414285001    SNOMEDCT    Food allergy
				 // 59037007     SNOMEDCT    Drug intolerance
				 // 235719002    SNOMEDCT    Food intolerance
				$entry['act']['entryRelationship']['observation']['value'] = [
					'@attributes' => [
						'xsi:type' => 'CD',
						'code' => $item['allergy_type_code'],
						'displayName' => $item['allergy_type'],
						'codeSystemName' => $item['allergy_type_code_type'],
						'codeSystem' => $this->codes($item['allergy_type_code_type'])
					]
				];

				$entry['act']['entryRelationship']['observation']['participant'] = [
					'@attributes' => [
						'typeCode' => 'CSM'
					],
					'participantRole' => [
						'@attributes' => [
							'classCode' => 'MANU'
						],
						'playingEntity' => [
							'@attributes' => [
								'classCode' => 'MMAT'
							],
							'code' => [
								'@attributes' => [
									'code' => $item['allergy_code'],
									'displayName' => $item['allergy'],
									'codeSystemName' => $item['allergy_code_type'],
									'codeSystem' => $this->codes($item['allergy_code_type'])
								]
							]
						]
					]
				];

				// Allergy Status Observation
				$entryRelationship = [
					'@attributes' => [
						'typeCode' => 'SUBJ',
						'inversionInd' => 'true'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.28'
							]
						],
						'code' => [
							'@attributes' => [
								'code' => '33999-4',
								'codeSystemName' => 'LOINC',
								'codeSystem' => '2.16.840.1.113883.6.1'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						]
					]
				];

				$entryRelationship['observation']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS'
					]
				];

				if($hasBeginDate){
					$entryRelationship['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['begin_date'])
						]
					];
				} else {
					$entryRelationship['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'nullFLavor' => 'UNK'
						]
					];
				}

				if($hasEndDate){
					$entryRelationship['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} elseif($entry['act']['statusCode'] == 'completed' && !$hasEndDate) {
					$entryRelationship['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				$entryRelationship['observation']['value'] = [
					'@attributes' => [
						'xsi:type' => 'CE',
						'code' => $item['status_code'],
						'displayName' => $item['status'],
						'codeSystemName' => $item['status_code_type'],
						'codeSystem' => $this->codes($item['status_code_type'])
					]
				];

				$entry['act']['entryRelationship']['observation']['entryRelationship'][] = $entryRelationship;
				unset($entryRelationship);

				// Reaction Observation
				$entryRelationship = [
					'@attributes' => [
						'typeCode' => 'MFST',
						'inversionInd' => 'true'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.9'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'nullFlavor' => 'NA'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						]
					]
				];

				$entryRelationship['observation']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS',
					]
				];

				if($hasBeginDate){
					$entryRelationship['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['begin_date'])
						]
					];
				} else {
					$entryRelationship['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'nullFLavor' => 'UNK'
						]
					];
				}

				if($hasEndDate){
					$entryRelationship['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} elseif($entry['act']['statusCode'] == 'completed' && !$hasEndDate) {
					$entryRelationship['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				$entryRelationship['observation']['value'] = [
					'@attributes' => [
						'xsi:type' => 'CD',
						'code' => $item['reaction_code'],
						'displayName' => $item['reaction'],
						'codeSystemName' => $item['reaction_code_type'],
						'codeSystem' => $this->codes($item['reaction_code_type'])
					]
				];

				$entry['act']['entryRelationship']['observation']['entryRelationship'][] = $entryRelationship;
				unset($entryRelationship);

				// Severity Observation
				$entryRelationship = [
					'@attributes' => [
						'typeCode' => 'SUBJ',
						'inversionInd' => 'true'
					],
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.8'
							]
						],
						'code' => [
							'@attributes' => [
								'code' => 'SEV',
								'codeSystemName' => 'ActCode',
								'codeSystem' => '2.16.840.1.113883.5.4',
								'displayName' => 'Severity Observation'
							]
						],
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						]
					]
				];

				$entryRelationship['observation']['effectiveTime'] = [
					'@attributes' => [
						'xsi:type' => 'IVL_TS',
					]
				];

				if($hasBeginDate){
					$entryRelationship['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['begin_date'])
						]
					];
				} else {
					$entryRelationship['observation']['effectiveTime']['low'] = [
						'@attributes' => [
							'nullFLavor' => 'UNK'
						]
					];
				}

				if($hasEndDate){
					$entryRelationship['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['end_date'])
						]
					];
				} elseif($entry['act']['statusCode'] == 'completed' && !$hasEndDate) {
					$entryRelationship['observation']['effectiveTime']['high'] = [
						'@attributes' => [
							'nullFlavor' => 'UNK'
						]
					];
				}

				$entryRelationship['observation']['value'] = [
					'@attributes' => [
						'xsi:type' => 'CD',
						'code' => $item['severity_code'],
						'displayName' => $item['severity'],
						'codeSystemName' => $item['severity_code_type'],
						'codeSystem' => $this->codes($item['severity_code_type'])
					]
				];

				$entry['act']['entryRelationship']['observation']['entryRelationship'][] = $entryRelationship;
				unset($entryRelationship);

				$allergies['entry'][] = $entry;

			}
		}
		if($this->requiredAllergies || !empty($allergies['entry'])){
			$this->addSection(['section' => $allergies]);
		}
		unset($allergiesData, $allergies);
	}

	/**
	 * Method setSocialHistorySection()
	 */
	private function setSocialHistorySection() {

		$SocialHistory = new SocialHistory();

		if($this->isExcluded('social')) {
			$socialHistory['@attributes'] = [
				'nullFlavor' => 'NI'
			];
			$this->addSection(['section' => $socialHistory]);
			return;
		};

        /**
         * Smoking Status Observation - This clinical statement represents a patient's current smoking
         * status. The vocabulary selected for this clinical statement is the best approximation of the
         * statuses in Meaningful Use (MU) Stage 1.
         *
         * If the patient is a smoker (77176002), the effectiveTime/low element must be present. If the patient
         * is an ex-smoker (8517006), both the effectiveTime/low and effectiveTime/high element must be present.
         *
         * The smoking status value set includes a special code to communicate if the smoking status is unknown
         * which is different from how Consolidated CDA generally communicates unknown information.
         */
        $smokingStatus = $SocialHistory->getSocialHistoryByPidAndCode($this->pid, 'smoking_status');

		if(count($smokingStatus) > 0){
			$smokingStatus = end($smokingStatus);

            $socialHistory = [
                'templateId' => [
                    '@attributes' => [
                        'root' => '2.16.840.1.113883.10.20.22.2.17'
                    ]
                ],
                'code' => [
                    '@attributes' => [
                        'code' => '29762-2',
                        'codeSystemName' => 'LOINC',
                        'codeSystem' => '2.16.840.1.113883.6.1',
                        'displayName' => "Social History"
                    ]
                ],
                'title' => 'Social History',
                'text' => $smokingStatus['note']
            ];

			$socialHistory['entry'][] = [
				'@attributes' => [
					'typeCode' => 'DRIV'
				],
				'observation' => [
					'@attributes' => [
						'classCode' => 'OBS',
						'moodCode' => 'EVN'
					],
					'templateId' => [
						'@attributes' => [
							'root' => '2.16.840.1.113883.10.20.22.4.38.2'
						]
					],
					'code' => [
						'@attributes' => [
							'code' => 'ASSERTION',
							'codeSystemName' => 'ActCode',
							'codeSystem' => '2.16.840.1.113883.5.4'
						]
					],
					'statusCode' => [
						'@attributes' => [
							'code' => 'completed'
						]
					],
					'effectiveTime' => [
						'low' => [
							'@attributes' => [
								'value' => $this->parseDate($smokingStatus['create_date'])
							]
						]
					],

					 // Code             System      Print Name
					 // 449868002        SNOMEDCT    Current every day smoker
					 // 428041000124106  SNOMEDCT    Current some day smoker
					 // 8517006          SNOMEDCT    Former smoker
					 // 266919005        SNOMEDCT    Never smoker (Never Smoked)
					 // 77176002         SNOMEDCT    Smoker, current status unknown
					 // 266927001        SNOMEDCT    Unknown if ever smoked
					'value' => [
						'@attributes' => [
							'xsi:type' => 'CD',
							'code' => $smokingStatus['status_code'],
							'displayName' => $smokingStatus['status'],
							'codeSystemName' => $smokingStatus['status_code_type'],
							'codeSystem' => $this->codes($smokingStatus['status_code_type'])
						]
					]
				]
			];
		}
		unset($smokingStatus);

		/**
		 * This Social History Observation defines the patient's occupational, personal (e.g., lifestyle),
		 * social, and environmental history and health risk factors, as well as administrative data such
		 * as marital status, race, ethnicity, and religious affiliation.
		 */
		$socialHistories = $SocialHistory->getSocialHistoryByPidAndCode($this->pid);

		if(count($socialHistories) > 0){

			$socialHistory['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Social History Element'
									],
									[
										'@value' => 'Description'
									],
									[
										'@value' => 'Effective Dates'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];
		}

		foreach($socialHistories As $socialHistoryEntry){

			$dateText = $this->parseDate($socialHistoryEntry['start_date']) . ' - ';
			if($socialHistoryEntry['end_date'] != '0000-00-00 00:00:00')
				$dateText .= $this->parseDate($socialHistoryEntry['end_date']);

			$socialHistory['text']['table']['tbody']['tr'][] = [
				'td' => [
					[
						'@value' => $socialHistoryEntry['category_code_text']
					],
					[
						'@value' => $socialHistoryEntry['observation']
					],
					[
						'@value' => $dateText
					]
				]
			];

			$entry = [
				'@attributes' => [
					'typeCode' => 'DRIV'
				],
				'observation' => [
					'@attributes' => [
						'classCode' => 'OBS',
						'moodCode' => 'EVN'
					],
					'templateId' => [
						'@attributes' => [
							'root' => '2.16.840.1.113883.10.20.22.4.38'
						]
					],
					'id' => [
						'@attributes' => [
							'root' => UUID::v4()
						]
					],

					 // Code            System    Print Name
					 // 229819007    SNOMEDCT    Tobacco use and exposure
					 // 256235009    SNOMEDCT    Exercise
					 // 160573003    SNOMEDCT    Alcohol intake
					 // 364393001    SNOMEDCT    Nutritional observable
					 // 364703007    SNOMEDCT    Employment detail
					 // 425400000    SNOMEDCT    Toxic exposure status
					 // 363908000    SNOMEDCT    Details of drug misuse behavior
					 // 228272008    SNOMEDCT    Health-related behavior
					 // 105421008    SNOMEDCT    Educational Achievement
					'code' => [
						'@attributes' => [
							'code' => $socialHistoryEntry['category_code'],
							'codeSystem' => $this->codes($socialHistoryEntry['category_code_type']),
							'codeSystemName' => $socialHistoryEntry['category_code_text'],
							'displayName' => $socialHistoryEntry['category_code_text']
						]
					],
					'statusCode' => [
						'@attributes' => [
							'code' => 'completed'
						]
					]
				]
			];

			$entry['observation']['effectiveTime'] = [
				'@attributes' => [
					'xsi:type' => 'IVL_TS'
				]
			];

			$entry['observation']['effectiveTime']['low'] = [
				'@attributes' => [
					'value' => $this->parseDate($socialHistoryEntry['start_date'])
				]
			];

			if($socialHistoryEntry['end_date'] != '0000-00-00 00:00:00'){
				$entry['observation']['effectiveTime']['high'] = [
					'@attributes' => [
						'value' => $this->parseDate($socialHistoryEntry['end_date'])
					]
				];
			} else {
				$entry['observation']['effectiveTime']['high'] = [
					'@attributes' => [
						'nullFlavor' => 'NI'
					]
				];
			}

			$entry['observation']['value'] = [
				'@attributes' => [
					'xsi:type' => 'ST'
				],
				'@value' => $socialHistoryEntry['observation']
			];

			$socialHistory['entry'][] = $entry;

			unset($entry);

		}
		unset($socialHistories);

		//		/***************************************************************************************************************
		//		 * Pregnancy Observation - This clinical statement represents current and/or
		//		 * prior pregnancy dates enabling investigators to determine if the subject
		//		 * of the case report* was pregnant during the course of a condition.
		//		 */
		//		$socialHistory['text']['table']['tbody']['tr'][] = array(
		//			'td' => array(
		//				array(
		//					'@value' => 'Social History Element Data'
		//				),
		//				array(
		//					'@value' => 'ReactiDescriptionon Data'
		//				),
		//				array(
		//					'@value' => 'Effective Data'
		//				)
		//			)
		//		);
		//		$socialHistory['entry'][] = array(
		//			'@attributes' => array(
		//				'typeCode' => 'DRIV'
		//			),
		//			'observation' => array(
		//				'@attributes' => array(
		//					'classCode' => 'OBS',
		//					'moodCode' => 'EVN'
		//				),
		//				'templateId' => array(
		//					'@attributes' => array(
		//						'root' => '2.16.840.1.113883.10.20.15.3.8'
		//					)
		//				),
		//				'code' => array(
		//					'@attributes' => array(
		//						'code' => 'ASSERTION',
		//						'codeSystem' => '2.16.840.1.113883.5.4'
		//					)
		//				),
		//				'statusCode' => array(
		//					'@attributes' => array(
		//						'code' => 'completed',
		//					)
		//				),
		//				'value' => array(
		//					'@attributes' => array(
		//						'xsi:type' => 'CD',
		//						'code' => '77386006',
		//						'codeSystem' => '2.16.840.1.113883.6.96'
		//					)
		//				),
		//				'entryRelationship' => array(
		//					'@attributes' => array(
		//						'typeCode' => 'REFR'
		//					),
		//					'observation' => array(
		//						'@attributes' => array(
		//							'classCode' => 'OBS',
		//							'moodCode' => 'EVN'
		//						),
		//						'templateId' => array(
		//							'@attributes' => array(
		//								'root' => '2.16.840.1.113883.10.20.15.3.1'
		//							)
		//						),
		//						'code' => array(
		//							'@attributes' => array(
		//								'code' => '11778-8',
		//		                        'codeSystemName' => 'LOINC',
		//								'codeSystem' => '2.16.840.1.113883.6.1'
		//							)
		//						),
		//						'statusCode' => array(
		//							'@attributes' => array(
		//								'code' => 'completed'
		//							)
		//						),
		//						/**
		//						 * Estimated Date Of Delivery
		//						 */
		//						'value' => array(
		//							'@attributes' => array(
		//								'xsi:type' => 'TS',
		//								'value' => '20150123' // TODO
		//							)
		//						)
		//					)
		//				)
		//			)
		//		);

		if(isset($socialHistory)){
			$this->addSection(['section' => $socialHistory]);
			unset($socialHistoryData, $socialHistory);
		}

	}

	/**
	 * Method setResultsSection()
     *
     * The Results section contains observations of results generated by laboratories, imaging procedures,
     * and other procedures. These coded result observations are contained within a Results Organizer in
     * the Results Section. The scope includes observations such as
     * hematology, chemistry, serology, virology, toxicology, microbiology, plain x-ray, ultrasound, CT, MRI,
     * angiography, echocardiography, nuclear medicine, pathology, and procedure observations.
     *
     * The section often includes notable results such as abnormal values or relevant trends, and could
     * contain all results for the period of time being documented.
     *
     * Laboratory results are typically generated by laboratories providing analytic services in areas such as
     * chemistry, hematology, serology, histology, cytology, anatomic pathology, microbiology, and/or virology.
     * These observations are based on analysis of specimens obtained from the patient and submitted to the laboratory.
     *
     * Imaging results are typically generated by a clinician reviewing the output of an imaging procedure,
     * such as where a cardiologist reports the left ventricular ejection fraction based on the review of a
     * cardiac echocardiogram.
     *
     * Procedure results are typically generated by a clinician to provide more granular information about
     * component observations made during  a procedure, such as where a gastroenterologist reports the size
     * of a polyp observed during a colonoscopy.
     */
	private function setResultsSection() {

		$Orders = new Orders();
		$resultsData = $Orders->getOrderWithResultsByPid($this->pid);

		$results = [];

		if($this->isExcluded('results') || empty($resultsData)){
			$results['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$results['templateId'] = [
			'@attributes' => [
				'root' => $this->requiredResults ? '2.16.840.1.113883.10.20.22.2.3.1' : '2.16.840.1.113883.10.20.22.2.3'
			]
		];
		$results['code'] = [
			'@attributes' => [
				'code' => '30954-2',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$results['title'] = 'Results';
		$results['text'] = '';

		if($this->isExcluded('results')) {
			$this->addSection(['section' => $results]);
			return;
		};

		if(!empty($resultsData)){

			$results['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'tbody' => []
				]
			];
			$results['entry'] = [];

			foreach($resultsData as $item){

				$results['text']['table']['tbody'][] = [
					'tr' => [
						[
							'th' => [
								[
									'@value' => $item['description']
								],
								[
									'@value' => $this->parseDateToText($item['result']['result_date'])
								]
							]
						]
					]
				];

				$entry = [
					'@attributes' => [
						'typeCode' => 'DRIV'
					],
					'organizer' => [
						'@attributes' => [
                            // CLUSTER || BATTERY
							'classCode' => 'CLUSTER',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.1'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								'code' => $item['code'],
								'displayName' => $item['description'],
								'codeSystemName' => $item['code_type'],
								'codeSystem' => $this->codes($item['code_type'])
							]
						],
                        // Code         System      Print Name
                        // aborted      ActStatus   aborted
                        // active       ActStatus   active
                        // cancelled    ActStatus   cancelled
                        // completed    ActStatus   completed
						// held         ActStatus   held
                        // suspended    ActStatus   suspended
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						],
						'component' => []
					]
				];

				foreach($item['result']['observations'] as $obs){

					if($obs['value'] == '')
						continue;

					$results['text']['table']['tbody'][] = [
						'tr' => [
							[
								'td' => [
									[
										'@value' => $obs['code_text']
									],
									[
										'@attributes' => [
											'align' => 'left'
										],
										'@value' => htmlentities($obs['value'] . ' ' . $obs['units'] . ' [' . $obs['reference_rage'] . ']')
									]
								]
							]
						]
					];

					$component = [
						'observation' => [
							'@attributes' => [
								'classCode' => 'OBS',
								'moodCode' => 'EVN'
							],
							'templateId' => [
								'@attributes' => [
									'root' => '2.16.840.1.113883.10.20.22.4.2'
								]
							],
							'id' => [
								'@attributes' => [
									'root' => UUID::v4()
								]
							],
							'code' => [
								'@attributes' => [
									'code' => $obs['code'],
									'codeSystemName' => $obs['code_type'],
									'codeSystem' => $this->codes($obs['code_type']),
									'displayName' => $obs['code_text']
								]
							],

							 // Code         System      Print Name
							 // aborted      ActStatus   aborted
							 // active       ActStatus   active
							 // cancelled    ActStatus   cancelled
							 // completed    ActStatus   completed
							 // held         ActStatus   held
							 // suspended    ActStatus   suspended
							'statusCode' => [
								'@attributes' => [
									'code' => 'completed'
								]
							]
						]
					];

					$component['observation']['effectiveTime'] = [
						'@attributes' => [
							'xsi:type' => 'IVL_TS',
						],
						'low' => [
							'@attributes' => [
								'value' => $this->parseDate($item['result']['result_date'])
							]
						],
						'high' => [
							'@attributes' => [
								'value' => $this->parseDate($item['result']['result_date'])
							]
						]
					];

					if(is_numeric($obs['value'])){
						$component['observation']['value'] = [
							'@attributes' => [
								'xsi:type' => 'PQ',
								'value' => htmlentities($obs['value'])
							]
						];
						if($obs['units'] != ''){
							$component['observation']['value']['@attributes']['unit'] = htmlentities($obs['units']);
						}
					} else {
						$component['observation']['value'] = [
							'@attributes' => [
								'xsi:type' => 'ST'
							],
							'@value' => htmlentities($obs['value'])
						];
					}

					if($obs['abnormal_flag'] != ''){
						$component['observation']['interpretationCode'] = [
							'@attributes' => [
								'code' => htmlentities($obs['abnormal_flag']),
								'codeSystemName' => 'ObservationInterpretation',
								'codeSystem' => '2.16.840.1.113883.5.83'
							]
						];
					} else {
						$component['observation']['interpretationCode'] = [
							'@attributes' => [
								'nullFlavor' => 'NA'
							]
						];
					}

					$ranges = preg_split("/to|-/", $obs['reference_rage']);
					if(is_array($ranges) && count($ranges) > 2){
						$component['observation']['referenceRange'] = [
							'observationRange' => [
								'value' => [
									'@attributes' => [
										'xsi:type' => 'IVL_PQ'
									],
									'low' => [
										'@attributes' => [
											'value' => htmlentities($ranges[0]),
											'unit' => htmlentities($obs['units'])
										]
									],
									'high' => [
										'@attributes' => [
											'value' => htmlentities($ranges[1]),
											'unit' => htmlentities($obs['units'])
										]
									]
								]
							]
						];

					} else {
						$component['observation']['referenceRange']['observationRange']['text'] = [
							'@attributes' => [
								'nullFlavor' => 'NA'
							]
						];
					}

					$entry['organizer']['component'][] = $component;

				}

				$results['entry'][] = $entry;
			}

		}

		if($this->requiredResults || !empty($results['entry'])){
			$this->addSection(['section' => $results]);
		}
		unset($resultsData, $results, $order);
	}

	/**
	 * Method setFunctionalStatusSection()
	 *
	 * The Functional Status Section contains observations and assessments of a patient's physical abilities.
	 * A patient’s functional status may include information regarding the patient’s general function
	 * such as ambulation, ability to perform Activities of Daily Living (ADLs)
	 * (e.g., bathing, dressing, feeding, grooming) or Instrumental Activities of Daily Living (IADLs)
	 * (e.g., shopping, using a telephone, balancing a check book). Problems that impact function
	 * (e.g., dyspnea, dysphagia) can be contained in the section.
	 *
	 * TODO: Need some finishing...
	 */
	private function setFunctionalStatusSection() {

		$CognitiveAndFunctionalStatus = new CognitiveAndFunctionalStatus();
		$functionalStatusData = $CognitiveAndFunctionalStatus->getPatientCognitiveAndFunctionalStatusesByPid($this->pid);

		if(empty($functionalStatusData)){
			$functionalStatus['@attributes'] = [
				'nullFlavor' => 'NI'
			];
		}
		$functionalStatus['templateId'] = [
			'@attributes' => [
				'root' => '2.16.840.1.113883.10.20.22.2.14'
			]
		];
		$functionalStatus['code'] = [
			'@attributes' => [
				'code' => '47420-5',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			]
		];
		$functionalStatus['title'] = 'Functional status assessment';
		$functionalStatus['text'] = '';

		if(!empty($functionalStatusData)){
			$functionalStatus['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Functional or Cognitive Finding'
									],
									[
										'@value' => 'Observation'
									],
									[
										'@value' => 'Observation Date'
									],
									[
										'@value' => 'Condition Status'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];
			$functionalStatus['entry'] = [];

			foreach($functionalStatusData as $item){

				$functionalStatus['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => $item['category']
						],
						[
							'@value' => $item['code_text']
						],
						[
							'@value' => $this->parseDate($item['created_date'])
						],
						[
							'@value' => $item['status']
						]
					]

				];

				$entry = [
					'observation' => [
						'@attributes' => [
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						],
					]
				];

				$entry['observation']['templateId'] = [
					'@attributes' => [
						'root' => ($item['category_code'] == '363871006' ? '2.16.840.1.113883.10.20.22.4.74' : '2.16.840.1.113883.10.20.22.4.67')
					]
				];

				$entry['observation']['id'] = [
					'@attributes' => [
						'root' => UUID::v4()
					]
				];

				$entry['observation']['code'] = [
					'@attributes' => [
						'code' => $item['category_code'],
						'codeSystemName' => $item['category_code_type'],
						'codeSystem' => $this->codes($item['category_code_type']),
						'displayName' => $item['category']
					]
				];

				$entry['observation']['statusCode'] = [
					'@attributes' => [
						'code' => 'completed'
					]
				];

				if($item['begin_date'] != '0000-00-00'){
					$entry['observation']['effectiveTime'] = [
						'@attributes' => [
							'value' => $this->parseDate($item['created_date'])
						]
					];
				} elseif($item['end_date'] != '0000-00-00') {
					$entry['observation']['effectiveTime'] = [
						'@attributes' => [
							'xsi:type' => 'IVL_TS'
						],
						'low' => [
							'@attributes' => [
								'value' => $this->parseDate($item['begin_date'])
							]
						],
						'high' => [
							'@attributes' => [
								'nullFlavor' => 'NI'
							]
						]
					];
				} else {
					$entry['observation']['effectiveTime'] = [
						'@attributes' => [
							'xsi:type' => 'IVL_TS'
						],
						'low' => [
							'@attributes' => [
								'value' => $this->parseDate($item['begin_date'])
							]
						],
						'high' => [
							'@attributes' => [
								'value' => $this->parseDate($item['end_date'])
							]
						]
					];
				}

				$entry['observation']['value'] = [
					'@attributes' => [
						'xsi:type' => 'CD',
						'code' => $item['code'],
						'codeSystemName' => $item['code_type'],
						'codeSystem' => $this->codes($item['code_type']),
						'displayName' => $item['code_text']
					]
				];

				$functionalStatus['entry'][] = $entry;
			}
		}

		if($this->requiredResults || !empty($functionalStatus['entry'])){
			$this->addSection(['section' => $functionalStatus]);
		}
		unset($functionalStatusData, $functionalStatus);
	}

	/**
	 * Method setEncountersSection() TODO
	 */
	private function setEncountersSection() {
		$encounters = [
			'section' => [
				'templateId' => [
					'@attributes' => [
						'root' => $this->requiredEncounters ? '2.16.840.1.113883.10.20.22.2.22.1' : '2.16.840.1.113883.10.20.22.2.22'
					]
				],
				'code' => [
					'@attributes' => [
						'code' => '46240-8',
						'codeSystemName' => 'LOINC',
						'codeSystem' => '2.16.840.1.113883.6.1'
					]
				],
				'title' => 'Encounters',
				'text' => ''
			]
		];

		$encountersData = [];

		if(!empty($encountersData)){
			$encounters['text'] = [
				'table' => [
					'@attributes' => [
						'border' => '1',
						'width' => '100%'
					],
					'thead' => [
						'tr' => [
							[
								'th' => [
									[
										'@value' => 'Functional Condition'
									],
									[
										'@value' => 'Effective Dates'
									],
									[
										'@value' => 'Condition Status'
									]
								]
							]
						]
					],
					'tbody' => [
						'tr' => []
					]
				]
			];
			$encounters['entry'] = [];

			foreach($encountersData as $item){

				$encounters['text']['table']['tbody']['tr'][] = [
					'td' => [
						[
							'@value' => 'Functional Condition Data'
						],
						[
							'@value' => 'Effective Dates Data'
						],
						[
							'@value' => 'Condition Status Data'
						]
					]

				];

				$encounters['entry'][] = $order = [
					'encounter' => [
						'@attributes' => [
							'classCode' => 'ENC',
							'moodCode' => 'EVN'
						],
						'templateId' => [
							'@attributes' => [
								'root' => '2.16.840.1.113883.10.20.22.4.49'
							]
						],
						'id' => [
							'@attributes' => [
								'root' => UUID::v4()
							]
						],
						'code' => [
							'@attributes' => [
								// CPT4 Visit code 99200 <-> 99299
								'code' => '99200',
								'codeSystem' => $this->codes('CPT4')
							]
						],

						 // Code         System      Print Name
						 // aborted      ActStatus   aborted
						 // active       ActStatus   active
						 // cancelled    ActStatus   cancelled
						 // completed    ActStatus   completed
						 // held         ActStatus   held
						 // suspended    ActStatus   suspended
						'statusCode' => [
							'@attributes' => [
								'code' => 'completed'
							]
						],
						'effectiveTime' => [
							'@attributes' => [
								'xsi:type' => 'IVL_TS'
							],
							// low date is required
							'low' => [
								'@attributes' => [
									'value' => '19320924'
								]
							],
							'high' => [
								'@attributes' => [
									'value' => '19320924'
								]
							]
						],
                        // Encounter Diagnosis
						'entryRelationship' => [
							[
								'@attributes' => [
									'typeCode' => 'SUBJ',
								],
								'observation' => [
									'@attributes' => [
										'classCode' => 'ACT',
										'moodCode' => 'EVN'
									],
									'templateId' => [
										'@attributes' => [
											'root' => '2.16.840.1.113883.10.20.22.4.80'
										]
									],
									'code' => [
										'@attributes' => [
											'code' => '29308-4',
											'codeSystem' => '2.16.840.1.113883.6.1'
										]
									],
                                    // Problem Observation
									'entryRelationship' => [
										[
											'@attributes' => [
												'typeCode' => 'SUBJ',
											],
											'observation' => [
												'@attributes' => [
													'classCode' => 'OBS',
													'moodCode' => 'EVN'
												],
												'templateId' => [
													'@attributes' => [
														'root' => '2.16.840.1.113883.10.20.22.4.4'
													]
												],
												'id' => [
													'@attributes' => [
														'root' => UUID::v4()
													]
												],

												// Code             System      Print Name
												// 404684003        SNOMEDCT    Finding
												// 409586006        SNOMEDCT    Complaint
												// 282291009        SNOMEDCT    Diagnosis
												// 64572001         SNOMEDCT    Condition
												// 248536006        SNOMEDCT    Functional limitation
												// 418799008        SNOMEDCT    Symptom
												// 55607006         SNOMEDCT    Problem
												// 373930000        SNOMEDCT    Cognitive function finding
												'code' => [
													'@attributes' => [
														'code' => '282291009',
														'codeSystem' => '2.16.840.1.113883.6.96'
													],
													'originalText' => 'Original text'
												],
												'statusCode' => [
													'@attributes' => [
														'code' => 'completed'
													]
												],
                                                // SNOMEDCT problem list
												'value' => [
													'@attributes' => [
														'xsi:type' => 'CD',
														'value' => '20150123'
													]
												],
                                                // Problem Status
												'entryRelationship' => [
													[
														'@attributes' => [
															'typeCode' => 'REFR',
														],
														'observation' => [
															'@attributes' => [
																'classCode' => 'OBS',
																'moodCode' => 'EVN'
															],
															'templateId' => [
																'@attributes' => [
																	'root' => '2.16.840.1.113883.10.20.22.4.6'
																]
															],
															'code' => [
																'@attributes' => [
																	'code' => '33999-4',
																	'codeSystem' => '2.16.840.1.113883.6.1'
																]
															],
															'statusCode' => [
																'@attributes' => [
																	'code' => 'completed'
																]
															],
															// Code         System      Print Name
															// 55561003     SNOMEDCT    Active
															// 73425007     SNOMEDCT    Inactive
                                                            // 413322009    SNOMEDCT    Resolved
															'value' => [
																'@attributes' => [
																	'xsi:type' => 'CD',
																	'code' => '413322009'
																]
															]
														]
													],
                                                    // Health Status Observation
													[
														'@attributes' => [
															'typeCode' => 'REFR',
														],
														'observation' => [
															'@attributes' => [
																'classCode' => 'OBS',
																'moodCode' => 'EVN'
															],
															'templateId' => [
																'@attributes' => [
																	'root' => '2.16.840.1.113883.10.20.22.4.5'
																]
															],
															'code' => [
																'@attributes' => [
																	'code' => '11323-3',
																	'codeSystem' => '2.16.840.1.113883.6.1'
																]
															],
															// Code         System      Print Name
															// 81323004     SNOMEDCT    Alive and well
															// 313386006    SNOMEDCT    In remission
															// 162467007    SNOMEDCT    Symptom free
															// 161901003    SNOMEDCT    Chronically ill
															// 271593001    SNOMEDCT    Severely ill
															// 21134002     SNOMEDCT    Disabled
                                                            // 161045001    SNOMEDCT    Severely disabled
															'value' => [
																'@attributes' => [
																	'xsi:type' => 'CD',
																	'code' => '81323004'
																]
															]
														]
													]
												]
											]
										]
									]
								]
							]
						]
					]
				];
			}
		}

		if($this->requiredEncounters || !empty($encounters['entry'])){
			$this->addSection(['section' => $encounters]);
		}
		unset($encountersData, $encounters);
	}

	private function telecomBuilder($number, $use = null) {
		$phone = [];
		if($number != ''){
			$phone['@attributes'] = [
				'xsi:type' => 'TEL',
				'value' => 'tel:' . $number
			];
			if(isset($use)){
				$phone['@attributes']['use'] = $use;
			}
		} else {
			$phone['@attributes']['nullFlavor'] = 'UNK';
		}
		return $phone;
	}

	private function addressBuilder(
        $use,
        $streetAddressLine,
        $city,
        $state,
        $zipcode,
        $country,
        $useablePeriod = null) {

		$addr = [];

		if($use !== false){
			$addr['@attributes']['use'] = $use;
		}

		if($streetAddressLine === false){
			// skip...
		} elseif($streetAddressLine != '') {
			$addr['streetAddressLine']['@value'] = $streetAddressLine;
		} else {
			$addr['streetAddressLine']['@attributes']['nullFlavor'] = 'NI';
		}

		if($city === false){
			// skip...
		} elseif($city != '') {
			$addr['city']['@value'] = $city;
		} else {
			$addr['city']['@attributes']['nullFlavor'] = 'UNK';
		}

		if($state === false){
			// skip...
		} elseif($state != '') {
			$addr['state']['@value'] = $state;
		} else {
			$addr['state']['@attributes']['nullFlavor'] = 'UNK';
		}

		if($zipcode === false){
			// skip...
		} elseif($zipcode != '') {
			$addr['postalCode']['@value'] = $zipcode;
		} else {
			$addr['postalCode']['@attributes']['nullFlavor'] = 'UNK';
		}

		if($country === false){
			// skip...
		} elseif($country != '') {
			$addr['country']['@value'] = $country;
		} else {
			$addr['country']['@attributes']['nullFlavor'] = 'UNK';
		}

		if(isset($useablePeriod)){
			$addr['useablePeriod']['@attributes']['xsi:type'] = 'IVL_TS';
			$addr['useablePeriod']['low']['@attributes']['nullFlavor'] = 'NA';
			$addr['useablePeriod']['high']['@attributes']['value'] = $useablePeriod;
		}

		return $addr;
	}

	private function parseDateToText($date) {
		return date('F Y', strtotime($date));
	}

	private function parseDate($date) {
		$foo = explode(' ', $date);
		return str_replace('-', '', $foo[0]);
	}
}

/**
 * Handle the request only if pid and action is available
 */
if(isset($_REQUEST['pid']) && isset($_REQUEST['action'])){
	// Check token for security
	include_once(ROOT . '/sites/' . $_REQUEST['site'] . '/conf.php');
	include_once(ROOT . '/classes/MatchaHelper.php');
	$ccd = new CCDDocument();
	if(isset($_REQUEST['eid']))
		$ccd->setEid($_REQUEST['eid']);
	if(isset($_REQUEST['pid']))
		$ccd->setPid($_REQUEST['pid']);
	if(isset($_REQUEST['exclude']))
		$ccd->setExcludes($_REQUEST['exclude']);
	$ccd->setTemplate('toc');
	$ccd->createCCD();

	if($_REQUEST['action'] == 'view'){
		$ccd->view();
	} elseif($_REQUEST['action'] == 'export') {
		$ccd->export();
	} elseif($_REQUEST['action'] == 'archive') {
		$ccd->archive();
	}
}

