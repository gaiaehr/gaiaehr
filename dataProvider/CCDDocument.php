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

//print 'hello';
//exit;

if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
if(!defined('_GaiaEXEC')){
	define('_GaiaEXEC', 1);
	require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
}

include_once(ROOT . '/classes/UUID.php');
include_once(ROOT . '/classes/Array2XML.php');

include_once(ROOT . '/dataProvider/Patient.php');
include_once(ROOT . '/dataProvider/Insurance.php');
include_once(ROOT . '/dataProvider/User.php');
include_once(ROOT . '/dataProvider/Rxnorm.php');
include_once(ROOT . '/dataProvider/Encounter.php');
include_once(ROOT . '/dataProvider/PoolArea.php');
include_once(ROOT . '/dataProvider/Vitals.php');
include_once(ROOT . '/dataProvider/Medical.php');
include_once(ROOT . '/dataProvider/Allergies.php');
include_once(ROOT . '/dataProvider/Orders.php');
include_once(ROOT . '/dataProvider/Medications.php');
include_once(ROOT . '/dataProvider/CarePlanGoals.php');
include_once(ROOT . '/dataProvider/PreventiveCare.php');
include_once(ROOT . '/dataProvider/CognitiveAndFunctionalStatus.php');
include_once(ROOT . '/dataProvider/Procedures.php');
include_once(ROOT . '/dataProvider/SocialHistory.php');
include_once(ROOT . '/dataProvider/Services.php');
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
	 * @var Medical
	 */
	private $Medical;
	/**
	 * @var Facilities
	 */
	private $Facilities;
	/**
	 * @var CombosData
	 */
	private $CombosData;
	/**
	 * @var array
	 */
	private $facility;
	/**
	 * @var array
	 */
	private $user;
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
	private $templateIds = array(
		'toc' => '2.16.840.1.113883.10.20.22.1.1',
		// transition of Care
		'cov' => '2.16.840.1.113883.10.20.22.1.1',
		// Clinical Office Visit
		'soc' => '2.16.840.1.113883.10.20.22.1.1',
		// Summary of Care
		'ps' => '2.16.840.1.113883.3.88.11.32.1'
		// Patient Summary
	);
	/**
	 * @var array
	 */
	private $codes = array(
		'CPT' => '2.16.840.1.113883.6.12',
		'CPT4' => '2.16.840.1.113883.6.12',
		'CPT-4' => '2.16.840.1.113883.6.12',
		'ICD9' => '2.16.840.1.113883.6.42',
		'ICD-9' => '2.16.840.1.113883.6.42',
		'ICD10' => '2.16.840.1.113883.6.3',
		'ICD-10' => '2.16.840.1.113883.6.3',
		'LN' => '2.16.840.1.113883.6.1',
		'LOINC' => '2.16.840.1.113883.6.1',
		'NDC' => '2.16.840.1.113883.6.6',
		'RXNORM' => '2.16.840.1.113883.6.88',
		'SNOMED' => '2.16.840.1.113883.6.96',
		'SNOMEDCT' => '2.16.840.1.113883.6.96',
		'SNOMED-CT' => '2.16.840.1.113883.6.96',
		'NPI' => '2.16.840.1.113883.4.6',
	    'UNII' => '2.16.840.1.113883.4.9',
	    'NCI' => '2.16.840.1.113883.3.26.1.1'
	);
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
	 * @var bool
	 */
	private $requiredResults;
	/**
	 * @var bool
	 */
	private $requiredEncounters;

	function __construct(){
		$this->dateNow = date('Ymd');
		$this->timeNow = date('YmdHisO');
		$this->Encounter = new Encounter();
		$this->Medical = new Medical();
		$this->Facilities = new Facilities();
		$this->CombosData = new CombosData();
		$this->facility = $this->Facilities->getCurrentFacility(true);
	}

	/**
	 * @param $pid
	 */
	public function setPid($pid){
		$this->pid = $pid;
	}

	/**
	 * @param $eid
	 */
	public function setEid($eid){
		$this->eid = $eid;
	}

	/**
	 * @param $template
	 */
	public function setTemplate($template){
		$this->template = $template;
	}

	/**
	 * Method buildCCD()
	 */
	public function createCCD(){
		try{
			if(!isset($this->pid))
				throw new Exception('PID variable not set');

			$this->xmlData = array(
				'@attributes' => array(
					'xmlns' => 'urn:hl7-org:v3',
					'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance',
					'xsi:schemaLocation' => 'urn:hl7-org:v3 CDA.xsd'
				)
			);
			$this->setRequirements();
			$this->setHeader();

			/**
			 * Array of sections to include in CCD
			 */
			$sections = array(
				'Procedures',
				'Vitals',
				'Immunizations',
				'Medications',
				'PlanOfCare',
				'Problems',
				'Allergies',
				'SocialHistory',
				'Results',
				'FunctionalStatus'
			);

			/**
			 * Run Section method for each section
			 */
			foreach($sections AS $Section){
				call_user_func(array($this, "set{$Section}Section"));
			}

			/**
			 * Build the CCR XML Object
			 */
			Array2XML::init('1.0', 'UTF-8', true, array('xml-stylesheet' => 'type="text/xsl" href="' . URL . '/lib/CCRCDA/schema/cda2.xsl"'));
			$this->xml = Array2XML::createXML('ClinicalDocument', $this->xmlData);
		} catch(Exception $e){
			print $e->getMessage();
		}
	}

	/**
	 * Method view()
	 */
	public function view(){
		try{
			header('Content-type: application/xml');
			print $this->xml->saveXML();
		} catch(Exception $e){
			print $e->getMessage();
		}
	}

	/**
	 * Method get()
	 */
	public function get(){
		try{
			return $this->xml->saveXML();
		} catch(Exception $e){
			return $e->getMessage();
		}
	}

	/**
	 * Method export()
	 */
	public function export(){
		try{
			/**
			 * Create a ZIP archive for delivery
			 */
			$dir = site_temp_path . '/';
			$filename = $this->pid . "-" . $this->patientData['fname'] . $this->patientData['lname'];
			$file = $this->zipIt($dir, $filename);
			/**
			 * Stream the file to the client
			 */
			header('Content-Type: application/zip');
			header('Content-Length: ' . filesize($file));
			header('Content-Disposition: attachment; filename="' . $filename . '.zip' . '"');
			readfile($file);
			unlink($file);
		} catch(Exception $e){
			print $e->getMessage();
		}

	}

	/**
	 * Method save()
	 * @param $toDir
	 * @param $fileName
	 */
	public function save($toDir, $fileName){
		try{
			$filename = $fileName ? $fileName : $this->pid . "-" . $this->patientData['fname'] . $this->patientData['lname'];
			$this->zipIt($toDir, $filename);
		} catch(Exception $e){
			print $e->getMessage();
		}
	}

	/**
	 * @return mixed
	 */
	private function getTemplateId(){
		return $this->templateIds[$this->template];
	}

	/**
	 * Method setRequirements()
	 */
	private function setRequirements(){
		if($this->template == 'toc'){
			$this->requiredAllergies = true;
			$this->requiredVitals = true;
			$this->requiredImmunization = true;
			$this->requiredMedications = true;
			$this->requiredProblems = true;
			$this->requiredProcedures = true;
			$this->requiredPlanOfCare = true;
			$this->requiredResults = true;
			$this->requiredEncounters = false;
		}
	}

	/**
	 * Method zipIt()
	 */
	private function zipIt($dir, $filename){
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
	private function setHeader(){
		$this->xmlData['realmCode'] = array(
			'@attributes' => array(
				'code' => 'US'
			)
		);
		$this->xmlData['typeId'] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.1.3',
				'extension' => 'POCD_HD000040'
			)
		);
		// US Realm Header Template Id
		$this->xmlData['templateId'][] = array(
			'@attributes' => array(
				'root' => $this->getTemplateId()
			)
		);
		// QRDA templateId
		$this->xmlData['templateId'][] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.24.1.1'
			)
		);
		// QDM-based QRDA templateId
		$this->xmlData['templateId'][] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.24.1.2'
			)
		);
		$this->xmlData['id'] = array(
			'@attributes' => array(
				'root' => 'MDHT',
				'extension' => '1912668293'
			)
		);
		$this->xmlData['code'] = array(
			'@attributes' => array(
				'code' => '95483297'
			)
		);
		$this->xmlData['title'] = $this->facility['name'] . ' - Continuity of Care Document';
		$this->xmlData['effectiveTime'] = array(
			'@attributes' => array(
				'value' => $this->timeNow
			)
		);
		$this->xmlData['confidentialityCode'] = array(
			'@attributes' => array(
				'code' => 'N',
				'codeSystem' => '2.16.840.1.113883.5.25'
			)
		);
		$this->xmlData['languageCode'] = array(
			'@attributes' => array(
				'code' => 'en-US'
			)
		);
		$this->xmlData['recordTarget'] = $this->getRecordTarget();
		$this->xmlData['author'] = $this->getAuthor();
		$this->xmlData['custodian'] = $this->getCustodian();
		$this->xmlData['documentationOf'] = $this->getDocumentationOf();
		$this->xmlData['component']['structuredBody']['component'] = array();

	}

	/**
	 * Method getRecordTarget()
	 * @return array
	 */
	private function getRecordTarget(){
		$Patient = new Patient();
		$patientData = $this->patientData = $Patient->getPatientDemographicDataByPid($this->pid);
		$Insurance = new Insurance();
		$insuranceData = $Insurance->getPatientPrimaryInsuranceByPid($this->pid);


		$recordTarget['typeId'] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.1.3',
				'extension' => 'POCD_HD000040'
			)
		);

		$recordTarget['patientRole']['id'] = array(
			'@attributes' => array(
				'extension' => $patientData['pid'],
				'root' => '2.16.840.1.113883.19.5'
			)
		);

		if($patientData['address'] != ''){
			$recordTarget['patientRole']['addr'] = array(
				'@attributes' => array(
					'use' => 'HP',
				),
				'streetAddressLine' => array(
					'@value' => $patientData['address']
				),
				'city' => array(
					'@value' => $patientData['city']
				),
				'state' => array(
					'@value' => $patientData['state']
				),
				'postalCode' => array(
					'@value' => $patientData['zipcode']
				),
				'country' => array(
					'@value' => $patientData['country']
				)
			);
		}

		if($patientData['home_phone'] != ''){
			$recordTarget['patientRole']['telecom'] = array(
				'@attributes' => array(
					'xsi:type' => 'TEL',
					'value' => 'tel:' . $patientData['home_phone']
				)
			);
		}

		$recordTarget['patientRole']['patient']['name'] = array(
			'@attributes' => array(
				'use' => 'L'
			),
		);

		$recordTarget['patientRole']['patient']['name']['given'][] = $patientData['fname'];

		if($patientData['mname'] != ''){
			$recordTarget['patientRole']['patient']['name']['given'][] = $patientData['mname'];
		}

		$recordTarget['patientRole']['patient']['name']['family'] = $patientData['lname'];

		if($patientData['title'] != ''){
			$recordTarget['patientRole']['patient']['name']['suffix'] = array(
				'@attributes' => array(
					'qualifier' => 'TITLE'
				),
				'@value' => isset($patientData['title']) ? $patientData['title'] : ''
			);
		}

		$recordTarget['patientRole']['patient']['administrativeGenderCode'] = array(
			'@attributes' => array(
				'code' => $patientData['sex'],
				// values are M, F, or UM more info... http://phinvads.cdc.gov/vads/ViewValueSet.action?id=8DE75E17-176B-DE11-9B52-0015173D1785
				'codeSystemName' => 'AdministrativeGender',
				'codeSystem' => '2.16.840.1.113883.5.1'
			)
		);

		if($patientData['sex'] == 'F'){
			$recordTarget['patientRole']['patient']['administrativeGenderCode']['@attributes']['displayName'] = 'Female';
		}elseif($patientData['sex'] == 'M'){
			$recordTarget['patientRole']['patient']['administrativeGenderCode']['@attributes']['displayName'] = 'Male';
		}

		$recordTarget['patientRole']['patient']['birthTime'] = array(
			'@attributes' => array(
				'value' => preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2$3', $patientData['DOB'])
			)
		);

		if(isset($patientData['marital_status']) && $patientData['marital_status'] != ''){
			$recordTarget['patientRole']['patient']['maritalStatusCode'] = array(
				'@attributes' => array(
					'code' => $patientData['marital_status'],
					'codeSystemName' => 'MaritalStatusCode',
					'displayName' => $this->CombosData->getDisplayValueByListIdAndOptionValue(12, $patientData['marital_status']),
					'codeSystem' => '2.16.840.1.113883.5.2'
				)
			);
		}

		if(isset($patientData['race']) && $patientData['race'] != ''){
			$recordTarget['patientRole']['patient']['raceCode'] = array(
				'@attributes' => array(
					'code' => $patientData['race'],
					'codeSystemName' => 'Race &amp; Ethnicity - CDC',
					'displayName' => $this->CombosData->getDisplayValueByListIdAndOptionValue(14, $patientData['race']),
					'codeSystem' => '2.16.840.1.113883.6.238'
				)
			);
		}

		if(isset($patientData['ethnicity']) && $patientData['ethnicity'] != ''){
			$recordTarget['patientRole']['patient']['ethnicGroupCode'] = array(
				'@attributes' => array(
					'code' => $patientData['ethnicity'] == 'H' ? '2135-2' : '2186-5',
					'codeSystemName' => 'Race &amp; Ethnicity - CDC',
					'displayName' => $this->CombosData->getDisplayValueByListIdAndOptionValue(59, $patientData['ethnicity']),
					'codeSystem' => '2.16.840.1.113883.6.238'
				)
			);
		}

		unset($Patient, $patientData, $Insurance, $insuranceData);

		return $recordTarget;
	}

	/**
	 * Method getAuthor()
	 * @return array
	 */
	private function getAuthor(){
		$User = new User();
		$this->user = $User->getCurrentUserData();
		$author = array(
			'typeId' => array(
				'@attributes' => array(
					'root' => '2.16.840.1.113883.1.3',
					'extension' => 'POCD_HD000040'
				)
			),
			'time' => array(
				'@attributes' => array(
					'value' => $this->timeNow
				)
			),
			'assignedAuthor' => array(
				'id' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.4.6',
						'extension' => $this->user['npi']
					)
				),
				'addr' => array(
					'@attributes' => array(
						'use' => 'HP',
					),
					'streetAddressLine' => array(
						'@value' => $this->facility['street']
					),
					'city' => array(
						'@value' => $this->facility['city']
					),
					'state' => array(
						'@value' => $this->facility['state']
					),
					'postalCode' => array(
						'@value' => $this->facility['postal_code']
					),
					'country' => array(
						'@value' => $this->facility['country_code']
					)
				),
				'telecom' => array(
					'@attributes' => array(
						'value' => 'tel:' . $this->facility['phone'],
					    'use' => 'HP'
					)
				),
				'assignedPerson' => array(
					'name' => array(
						'prefix' => $this->user['title'],
						'given' => $this->user['fname'],
						'family' => $this->user['lname'],
					)
				),
				'representedOrganization' => array(
					'id' => array(
						'@attributes' => array(
							'root' => '2.16.840.1.113883.19.5',
							'extension' => UUID::v4()
						),
					),
					'name' => $this->facility['name'],
					'telecom' => array(
						'@attributes' => array(
							'value' => 'tel:' . $this->facility['phone'],
							'use' => 'HP'
						)
					),
					'addr' => array(
						'@attributes' => array(
							'use' => 'HP',
						),
						'streetAddressLine' => array(
							'@value' => $this->facility['street']
						),
						'city' => array(
							'@value' => $this->facility['city']
						),
						'state' => array(
							'@value' => $this->facility['state']
						),
						'postalCode' => array(
							'@value' => $this->facility['postal_code']
						),
						'country' => array(
							'@value' => $this->facility['country_code']
						)
					),
				)
			)
		);

		return $author;
	}

	/**
	 * Method getCustodian()
	 * @return array
	 */
	private function getCustodian(){
		$custodian = array(
			'assignedCustodian' => array(
				'representedCustodianOrganization' => array(
					'id' => array(
						'@attributes' => array(
							'root' => '2.16.840.1.113883.4.6',
							'extension' => '1234567'
							// TODO: Organization NPI
						)
					),
					'name' => $this->facility['name'],
					'telecom' => array(
						'@attributes' => array(
							'value' => 'tel:(000)000-0000',
						)
					),
					'addr' => array(
						'@attributes' => array(
							'use' => 'HP',
						),
						'streetAddressLine' => array(
							'@value' => $this->facility['street']
						),
						'city' => array(
							'@value' => $this->facility['city']
						),
						'state' => array(
							'@value' => $this->facility['state']
						),
						'postalCode' => array(
							'@value' => $this->facility['postal_code']
						),
						'country' => array(
							'@value' => $this->facility['country_code']
						)
					)
				)
			)
		);
		return $custodian;
	}

	/**
	 * Method getDocumentationOf()
	 * @return array
	 */
	private function getDocumentationOf(){
		$documentationOf = array(
			'serviceEvent' => array(
				'@attributes' => array(
					'classCode' => 'PCPR'
				),
				'effectiveTime' => array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					),
					'low' => array(
						'@attributes' => array(
							'value' => '19320924'
						)
					),
					'high' => array(
						'@attributes' => array(
							'value' => $this->dateNow
						)
					)
				),
				'performer' => array(
					'@attributes' => array(
						'typeCode' => 'PRF'
					),
					'functionCode' => array(
						'@attributes' => array(
							'code' => 'PCP',
							'codeSystem' => '2.16.840.1.113883.5.88'
						)
					),
					'time' => array(
						'low' => array(
							'@attributes' => array(
								'value' => '1990'
							)
						),
						'high' => array(
							'@attributes' => array(
								'value' => $this->dateNow
							)
						)
					),
					'assignedEntity' => array(
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'addr' => array(
							'@attributes' => array(
								'use' => 'HP',
							),
							'streetAddressLine' => array(
								'@value' => $this->facility['street'] //TODO provider
							),
							'city' => array(
								'@value' => $this->facility['city']//TODO provider
							),
							'state' => array(
								'@value' => $this->facility['state']//TODO provider
							),
							'postalCode' => array(
								'@value' => $this->facility['postal_code']//TODO provider
							),
							'country' => array(
								'@value' => $this->facility['country_code']//TODO provider
							)
						),
						'telecom' => array(
							'@attributes' => array(
								'value' => 'tel:(000)000-0000',//TODO provider
							)
						),
						'assignedPerson' => array(
							'name' => array(
								'prefix' => $this->user['title'],
								'given' => $this->user['fname'],
								'family' => $this->user['lname'],
							)
						),
						'representedOrganization' => array(
							'id' => array(
								'@attributes' => array(
									'root' => '2.16.840.1.113883.19.5',
									'extension' => UUID::v4()
								)
							),
							'name' => $this->facility['name'],
							'telecom' => array(
								'@attributes' => array(
									'value' => 'tel:(000)000-0000',//TODO provider
								)
							),
							'addr' => array(
								'@attributes' => array(
									'use' => 'HP',
								),
								'streetAddressLine' => array(
									'@value' => $this->facility['street'] //TODO provider
								),
								'city' => array(
									'@value' => $this->facility['city']//TODO provider
								),
								'state' => array(
									'@value' => $this->facility['state']//TODO provider
								),
								'postalCode' => array(
									'@value' => $this->facility['postal_code']//TODO provider
								),
								'country' => array(
									'@value' => $this->facility['country_code']//TODO provider
								)
							)
						)
					)
				)
			)
		);
		return $documentationOf;
	}

	/**
	 * Method getInformant()
	 * @return array
	 */
	private function getInformant(){
		//		$informant = array(
		//			'assignedEntity' => array(
		//				'id' => array(
		//					'@attributes' => array(
		//						'nullFlavor' => 'NI'
		//					)
		//				),
		//				'representedOrganization' => array(
		//					'id' => array(
		//						'@attributes' => array(
		//							'root' => '2.16.840.1.113883.19.5',
		//							'extension' => UUID::v4()
		//						)
		//					),
		//					'name' => $facility['name']
		//				)
		//			)
		//		);
		return array();
	}

	/**
	 * Method getLegalAuthenticator()
	 * @return array
	 */
	private function getLegalAuthenticator(){
		//$legalAuthenticator = array(
		//	'time' => array(
		//		'@attributes' => array(
		//			'value' => $timeNow
		//		)
		//	),
		//	'signatureCode' => array(
		//		'@attributes' => array(
		//			'code' => 'S'
		//		)
		//	),
		//	'assignedEntity' => array(
		//		'id' => array(
		//			'@attributes' => array(
		//				'nullFlavor' => 'NI'
		//			)
		//		),
		//		'representedOrganization' => array(
		//			'id' => array(
		//				'@attributes' => array(
		//					'root' => '2.16.840.1.113883.19.5',
		//					'extension' => UUID::v4()
		//				)
		//			),
		//			'name' => $facility['name']
		//		),
		//		'addr' => array(
		////			'@attributes' => array(
		////				'xsi:type' => 'USRealmAddress'
		////			),
		//		    'streetAddressLine' => array(
		//			    '@value' => $facility['street']
		//		    ),
		//		    'city' => array(
		//			    '@value' => $facility['city']
		//		    ),
		//		    'state' => array(
		//			    '@value' => $facility['state']
		//		    ),
		//		    'postalCode' => array(
		//			    '@value' => $facility['postal_code']
		//		    ),
		//		    'country' => array(
		//			    '@value' => $facility['country']
		//		    )
		//		),
		//		'telecom' => array(
		//			'@attributes' => array(
		//				'value' => 'tel:'. $facility['phone'],
		//			    'use' => 'tel'
		//			)
		//		)
		//	)
		//);
		return array();
	}

	/**
	 * Method addSection()
	 * @param $section
	 */
	private function addSection($section){
		$this->xmlData['component']['structuredBody']['component'][] = $section;
	}

	/**
	 * Method setProceduresSection()
	 */
	private function setProceduresSection(){

		$Procedures = new Procedures();
		$proceduresData = $Procedures->getPatientProceduresByPid($this->pid);
		unset($Procedures);

		$procedures = array();

		if(empty($proceduresData)){
			$procedures['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$procedures['section']['templateId'] = array(
			'@attributes' => array(
				'root' => $this->requiredProcedures ? '2.16.840.1.113883.10.20.22.2.7.1' : '2.16.840.1.113883.10.20.22.2.7'
			)
		);
		$procedures['section']['code'] = array(
			'@attributes' => array(
				'code' => '47519-4',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$procedures['section']['title'] = 'Procedures';
		$procedures['section']['text'] = '';

		if(!empty($proceduresData)){

			$procedures['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Procedure'
									),
									array(
										'@value' => 'Date'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);
			$procedures['section']['entry'] = array();

			foreach($proceduresData as $item){
				$procedures['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['code_text']
						),
						array(
							'@value' => $this->parseDateToText($item['create_date'])
						)
					)

				);

				//  Procedure Activity Procedure

				$procedures['section']['entry'][] = array(
					'@attributes' => array(
						'typeCode' => 'DRIV'
					),
					'procedure' => array(
						'@attributes' => array(
							'classCode' => 'PROC',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.14'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => $item['code'],
								'codeSystem' => $this->codes[$item['code_type']],
								'displayName' => $item['code_text']
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['create_date'])

							)
						),
						'methodCode' => array(
							'@attributes' => array(
								'nullFlavor' => 'UNK'

							)
						)
					)
				);
			}
		}

		if($this->requiredProcedures || isset($procedures['section']['entry'])){
			$this->addSection($procedures);
		}
		unset($proceduresData, $procedures);
	}

	/**
	 * Method setVitalsSection()
	 */
	private function setVitalsSection(){
		$Vitals = new Vitals();
		$vitalsData = $Vitals->getVitalsByPid($this->pid);

		if(empty($vitalsData)){
			$vitals['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$vitals['section']['templateId'] = array(
			'@attributes' => array(
				'root' => $this->requiredVitals ? '2.16.840.1.113883.10.20.22.2.4.1' : '2.16.840.1.113883.10.20.22.2.4'
			)
		);
		$vitals['section']['code'] = array(
			'@attributes' => array(
				'code' => '8716-3',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$vitals['section']['title'] = 'Vital Signs';
		$vitals['section']['text'] = '';


		if(!empty($vitalsData)){

			$vitals['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@attributes' => array(
											'align' => 'right'
										),
										'@value' => 'Date / Time:'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@attributes' => array(
											'align' => 'left'
										),
										'@value' => 'Height'
									)
								)

							),
							array(
								'th' => array(
									array(
										'@attributes' => array(
											'align' => 'left'
										),
										'@value' => 'Weight'
									)
								)

							),
							array(
								'th' => array(
									array(
										'@attributes' => array(
											'align' => 'left'
										),
										'@value' => 'Blood Pressure'
									)
								)

							)
						)
					)
				)
			);
			$vitals['section']['entry'] = array();

			foreach($vitalsData as $item){
				/**
				 * strip date (yyyy-mm-dd hh:mm:ss => yyyymmdd)
				 */
				$date = $this->parseDate($item['date']);

				/**
				 * date
				 */
				$vitals['section']['text']['table']['thead']['tr'][0]['th'][] = array(
					'@value' => date('F j, Y', strtotime($item['date']))
				);
				/**
				 * Height
				 */
				$vitals['section']['text']['table']['tbody']['tr'][0]['td'][] = array(
					'@value' => $item['height_cm'] . ' cm'
				);
				/**
				 * Weight
				 */
				$vitals['section']['text']['table']['tbody']['tr'][1]['td'][] = array(
					'@value' => $item['weight_kg'] . ' kg'
				);
				/**
				 * Blood Pressure
				 */
				$vitals['section']['text']['table']['tbody']['tr'][2]['td'][] = array(
					'@value' => $item['bp_systolic'] . '/' . $item['bp_diastolic'] . ' mmHg'
				);

				/**
				 * Code Entry
				 */
				$vitals['section']['entry'][] = array(
					'@attributes' => array(
						'typeCode' => 'DRIV'
					),
					'organizer' => array(
						'@attributes' => array(
							'classCode' => 'CLUSTER',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.26'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => '46680005',
								'codeSystemName' => 'SNOMED CT',
								'codeSystem' => '2.16.840.1.113883.6.96',
								'displayName' => 'Vital signs'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'value' => $date
							)
						),
						'component' => array(
							array(
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.27'
										)
									),
									'id' => array(
										'@attributes' => array(
											'root' => UUID::v4()
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '8302-2',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'Height'
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									'effectiveTime' => array(
										'@attributes' => array(
											'value' => $date
										)
									),
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'PQ',
											'value' => $item['height_cm'],
											'unit' => 'cm'
										)
									)
								)
							),
							array(
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.2'
										)
									),
									'id' => array(
										'@attributes' => array(
											'root' => UUID::v4()
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '3141-9',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'Weight Measured'
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									'effectiveTime' => array(
										'@attributes' => array(
											'value' => $date
										)
									),
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'PQ',
											'value' => $item['weight_kg'],
											'unit' => 'kg'
										)
									)
								)
							),
							array(
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.2'
										)
									),
									'id' => array(
										'@attributes' => array(
											'root' => UUID::v4()
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '8480-6',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'BP Systolic'
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									'effectiveTime' => array(
										'@attributes' => array(
											'value' => $date
										)
									),
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'PQ',
											'value' => $item['bp_systolic'],
											'unit' => 'mm[Hg]'
										)
									)
								)

							),
							array(
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'OBS',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.2'
										)
									),
									'id' => array(
										'@attributes' => array(
											'root' => UUID::v4()
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '8462-4',
											'codeSystemName' => 'LOINC',
											'codeSystem' => '2.16.840.1.113883.6.1',
											'displayName' => 'BP Diastolic'
										)
									),
									'statusCode' => array(
										'@attributes' => array(
											'code' => 'completed'
										)
									),
									'effectiveTime' => array(
										'@attributes' => array(
											'value' => $date
										)
									),
									'value' => array(
										'@attributes' => array(
											'xsi:type' => 'PQ',
											'value' => $item['bp_diastolic'],
											'unit' => 'mm[Hg]'
										)
									)
								)

							)
						)
					)
				);
			}
		}

		if($this->requiredVitals || isset($vitals['section']['entry'])){
			$this->addSection($vitals);
		}
		unset($vitalsData, $vitals);

	}

	/**
	 * Method setImmunizationsSection()
	 */
	private function setImmunizationsSection(){

		$immunizationsData = $this->Medical->getPatientImmunizationsByPid($this->pid);

		if(empty($immunizationsData)){
			$immunizations['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$immunizations['section']['templateId'] = array(
			'@attributes' => array(
				'root' => $this->requiredImmunization ? '2.16.840.1.113883.10.20.22.2.2.1' : '2.16.840.1.113883.10.20.22.2.2'
			)
		);
		$immunizations['section']['code'] = array(
			'@attributes' => array(
				'code' => '11369-6',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$immunizations['section']['title'] = 'Immunizations';
		$immunizations['section']['text'] = '';

		if(!empty($immunizationsData)){

			$immunizations['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Vaccine'
									),
									array(
										'@value' => 'Date'
									),
									array(
										'@value' => 'Status'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);
			$immunizations['section']['entry'] = array();

			foreach($immunizationsData as $item){
				$date = preg_replace('/(\d{4})-(\d{2})-(\d{2}) \d{2}:\d{2}:\d{2}/', '$1$2', $item['administered_date']);
				$immunizations['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => ucwords($item['vaccine_name'])
						),
						array(
							'@value' => date('F Y', strtotime($item['administered_date']))
						),
						array(
							'@value' => 'Completed'
						)
					)

				);
				$immunizations['section']['entry'][] = array(
					'substanceAdministration' => array(
						'@attributes' => array(
							'classCode' => 'SBADM',
							'moodCode' => 'EVN',
							'negationInd' => 'false',
							'nullFlavor' => 'NI'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.52'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'value' => $date
							)
						),
//						'routeCode' => array(
//							'@attributes' => array(
//								'code' => 'C28161',
//								'codeSystem' => '2.16.840.1.113883.3.26.1.1',
//								'codeSystemName' => 'NCI Thesaurus',
//								'displayName' => 'INTRAMUSCULAR',
//							)
//						),
						'consumable' => array(
							'manufacturedProduct' => array(
								'@attributes' => array(
									'classCode' => 'MANU'
								),
								'templateId' => array(
									'@attributes' => array(
										'root' => '2.16.840.1.113883.10.20.22.4.54'
									)
								),
								'manufacturedMaterial' => array(
									'code' => array(
										'@attributes' => array(
											'code' => $item['code'],
											'codeSystemName' => 'CVX',
											'codeSystem' => '2.16.840.1.113883.12.292',
											'displayName' => ucwords($item['vaccine_name'])
										)
									)
								)
							)
						)
					)
				);
			}

		}

		if($this->requiredImmunization || isset($immunizations['section']['entry'])){
			$this->addSection($immunizations);
		}
		unset($immunizationsData, $immunizations);
	}

	/**
	 * Method setMedicationsSection()
	 */
	private function setMedicationsSection(){

		$Medications = new Medications();
		$medicationsData = $Medications->getPatientActiveMedicationsByPid($this->pid);
		unset($Medications);

		if(empty($medicationsData)){
			$medications['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$medications['section']['templateId'] = array(
			'@attributes' => array(
				'root' => $this->requiredMedications ? '2.16.840.1.113883.10.20.22.2.1.1' : '2.16.840.1.113883.10.20.22.2.1'
			)
		);
		$medications['section']['code'] = array(
			'@attributes' => array(
				'code' => '10160-0',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$medications['section']['title'] = 'Medications';
		$medications['section']['text'] = '';


		if(!empty($medicationsData)){

			$medications['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Medication'
									),
									array(
										'@value' => 'Instructions'
									),
									array(
										'@value' => 'Start Date'
									),
									array(
										'@value' => 'Status'
									),
//									array(
//										'@value' => 'Indications' // diagnosis
//									),
//									array(
//										'@value' => 'Fill Instructions' //1 refill Generic Substitition Allowed
//									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);

			$medications['section']['entry'] = array();

			foreach($medicationsData as $item){
				$medications['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['STR'] . ' ' . $item['dose'] . ' ' . $item['form']
						),
						array(
							'@value' =>  $item['directions']
						),
						array(
							'@value' => date('F j, Y', strtotime($item['begin_date']))
						),
						array(
							'@value' => isset($item['begin_date']) && $item['begin_date'] == '0000-00-00 00:00:00' ? 'No longer active' : 'Active'
						)
					)

				);

				$entry['substanceAdministration']['@attributes'] = array(
					'classCode' => 'SBADM',
					'moodCode' => 'EVN'
				);

				$entry['substanceAdministration']['templateId'] = array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.4.16'
					)
				);

				$entry['substanceAdministration']['id'] = array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				);

				$entry['substanceAdministration']['text'] = $item['STR'];

				$entry['substanceAdministration']['statusCode'] = array(
					'@attributes' => array(
						'code' => 'completed'
					)
				);

				$entry['substanceAdministration']['effectiveTime'] = array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS'
					)
				);

				$entry['substanceAdministration']['effectiveTime']['low'] = array(
					'@attributes' => array(
						'value' => date('Ymd', strtotime($item['begin_date']))
					)
				);

				if($item['end_date'] != '0000-00-00'){
					$entry['substanceAdministration']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => date('Ymd', strtotime($item['end_date']))
						)
					);
				}else{
					$entry['substanceAdministration']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'nullFlavor' => 'NI'
						)
					);
				}

				$entry['substanceAdministration']['consumable'] = array(
					'manufacturedProduct' => array(
						'@attributes' => array(
							'classCode' => 'MANU'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.23'
							)
						),
						'manufacturedMaterial' => array(
							'code' => array(
								'@attributes' => array(
									'code' => $item['RXCUI'],
									'codeSystem' => '2.16.840.1.113883.6.88',
									'displayName' => ucwords($item['STR']),
									'codeSystemName' => 'RxNorm'
								)
							)
						)
					)
				);

				$medications['section']['entry'][] = $entry;
				unset($entry);
			}

		}

		if($this->requiredMedications || isset($medications['section']['entry'])){
			$this->addSection($medications);
		}
		unset($medicationsData, $medications);
	}

	/**
	 * Method setPlanOfCareSection() TODO
	 */
	private function setPlanOfCareSection(){

		/**
		 * Table moodCode Values
		 * -----------------------------------------------------------------------
		 * Code             | Definition
		 * -----------------------------------------------------------------------
		 * EVN (event)      | The entry defines an actual occurrence of an event.
		 * INT (intent)     | The entry is intended or planned.
		 * PRMS (promise)   | A commitment to perform the stated entry.
		 * PRP (proposal)   | A proposal that the stated entry be performed.
		 * RQO (request)    | A request or order to perform the stated entry.
		 * -----------------------------------------------------------------------
		 */
		$Orders = new Orders();
		$planOfCareData['OBS'] = $Orders->getOrderWithoutResultsByPid($this->pid);

		$planOfCareData['ACT'] = array();
		$planOfCareData['ENC'] = array();

		$CarePlanGoals = new CarePlanGoals();
		$planOfCareData['PROC'] = $CarePlanGoals->getPatientCarePlanGoalsByPid($this->pid);


		$hasData = !empty($planOfCareData['OBS']) ||
			!empty($planOfCareData['ACT']) ||
			!empty($planOfCareData['ENC']) ||
			!empty($planOfCareData['PROC']);

		if(!$hasData){
			$planOfCare['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$planOfCare['section']['templateId'] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.22.2.10'
			)
		);
		$planOfCare['section']['code'] = array(
			'@attributes' => array(
				'code' => '18776-5',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$planOfCare['section']['title'] = 'Plan of Care';
		$planOfCare['section']['text'] = '';

		// if one of these are not empty
		if($hasData){
			$planOfCare['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Planned Activity'
									),
									array(
										'@value' => 'Planned Date'
									)
								)
							)
						)
					)
				)
			);

			$planOfCare['section']['text']['table']['tbody']['tr'] = array();
			$planOfCare['section']['entry'] = array();

			/**
			 * Observations...
			 */
			foreach($planOfCareData['OBS'] as $item){
				$planOfCare['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['description']
						),
						array(
							'@value' => $this->parseDate($item['date_ordered'])
						)
					)
				);

				$planOfCare['section']['entry'][] = array(
					'@attributes' => array(
						'typeCode' => 'DRIV'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'RQO'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.44'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => $item['code'],
								'codeSystemName' => $item['code_type'],
								'codeSystem' => $this->codes[$item['code_type']],
								'displayName' => $item['description']
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'new'
							)
						),
						'effectiveTime' => array(
							'center' => array(
								'@attributes' => array(
									'value' => $this->parseDate($item['date_ordered'])
								)
							)
						)
					)
				);
			}

			/**
			 * Activities...
			 */
			foreach($planOfCareData['ACT'] as $item){
				$planOfCare['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => 'Test' //TODO
						),
						array(
							'@value' => 'Ting' //TODO
						)
					)
				);

				$planOfCare['section']['entry'][] = array(
					'act' => array(
						'@attributes' => array(
							'classCode' => 'ACT',
							'moodCode' => 'RQO'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.39'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => '23426006', //TODO
								'codeSystemName' => 'SNOMEDCT',
								'codeSystem' => '2.16.840.1.113883.6.96', //TODO
								'displayName' => 'Pulmonary function test', //TODO
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'new'
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'center' => '20000421'  //TODO
							)
						)
					)
				);
			}

			/**
			 * Encounters...
			 */
			foreach($planOfCareData['ENC'] as $item){
				$planOfCare['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => 'Test' //TODO
						),
						array(
							'@value' => 'Ting' //TODO
						)
					)
				);

				$planOfCare['section']['entry'][] = array(
					'act' => array(
						'@attributes' => array(
							'classCode' => 'INT',
							'moodCode' => 'INT'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.40'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => '23426006', //TODO
								'codeSystemName' => 'SNOMEDCT',
								'codeSystem' => '2.16.840.1.113883.6.96', //TODO
								'displayName' => 'Pulmonary function test', //TODO
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'new'
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'center' => '20000421'  //TODO
							)
						)
					)
				);
			}

			/**
			 * Procedures...
			 */
			foreach($planOfCareData['PROC'] as $item){
				$planOfCare['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['goal']
						),
						array(
							'@value' => $this->parseDate($item['plan_date'])
						)
					)
				);

				$planOfCare['section']['entry'][] = array(
					'procedure' => array(
						'@attributes' => array(
							'moodCode' => 'RQO',
							'classCode' => 'PROC'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.41'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => $item['goal_code'],
								'codeSystemName' => $item['goal_code_type'],
								'codeSystem' => $this->codes[$item['goal_code_type']],
								'displayName' => htmlentities($item['goal']),
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'new'
							)
						),
						'effectiveTime' => array(
							'center' => array(
								'@attributes' => array(
									'value' => $this->parseDate($item['plan_date'])
								)
							)
						)
					)
				);
			}
		}

		if($this->requiredPlanOfCare || isset($planOfCare['section']['entry'])){
			$this->addSection($planOfCare);
		}
		unset($planOfCareData, $planOfCare);
	}

	/**
	 * Method setProblemsSection()
	 */
	private function setProblemsSection(){

		$problemsData = $this->Medical->getPatientProblemsByPid($this->pid);

		if(empty($problemsData)){
			$problems['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}

		$problems['section']['templateId'][] = array(
			'@attributes' => array(
				'root' => $this->requiredProblems ? '2.16.840.1.113883.10.20.22.2.5.1' : '2.16.840.1.113883.10.20.22.2.5'
			)
		);

		$problems['section']['templateId'][] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.3.88.11.83.103'
			)
		);

		$problems['section']['code'] = array(
			'@attributes' => array(
				'code' => '11450-4',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$problems['section']['title'] = 'Problems';
		$problems['section']['text'] = '';

		if(!empty($problemsData)){

			$problems['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Condition'
									),
									array(
										'@value' => 'Effective Dates'
									),
									array(
										'@value' => 'Condition Status'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);



			$problems['section']['entry'] = array();

			foreach($problemsData as $item){

				$dateText = $this->parseDate($item['begin_date']) . ' - ';
				if($item['end_date'] != '0000-00-00') $dateText .= $this->parseDate($item['end_date']);


				$problems['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['code_text']
						),
						array(
							'@value' => $dateText
						),
						array(
							'@value' => $item['status']
						)
					)

				);

				$entry = array(
					'act' => array(
						'@attributes' => array(
							'classCode' => 'ACT',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.3'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => 'CONC',
								'codeSystemName' => 'ActClass',
								'codeSystem' => '2.16.840.1.113883.5.6',
								'displayName' => 'Concern'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'active',
								// active ||  suspended ||  aborted ||  completed
							)
						)
					)
				);

				$entry['act']['effectiveTime'] = array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					)
				);
				$entry['act']['effectiveTime']['low'] = array(
					'@attributes' => array(
						'value' => $this->parseDate($item['begin_date'])
					)
				);
				if($item['end_date'] != '0000-00-00'){
					$entry['act']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['end_date'])
						)
					);
				}else{
					$entry['act']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'nullFlavor' => 'NI'
						)
					);
				}

				$entry['act']['entryRelationship'] = array(
					'@attributes' => array(
						'typeCode' => 'SUBJ'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.4'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						/**
						 *  404684003    SNOMEDCT    Finding
						 *    409586006    SNOMEDCT    Complaint
						 *    282291009    SNOMEDCT    Diagnosis
						 *    64572001    SNOMEDCT    Condition
						 *    248536006    SNOMEDCT    Functional limitation
						 *    418799008    SNOMEDCT    Symptom
						 *    55607006    SNOMEDCT    Problem
						 *    373930000    SNOMEDCT    Cognitive function finding
						 */
						'code' => array(
							'@attributes' => array(
								'code' => '55607006',
								'displayName' => 'Problem',
								'codeSystemName' => 'SNOMED CT',
								'codeSystem' => '2.16.840.1.113883.6.96'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed',
							)
						)
					)
				);

				$entry['act']['entryRelationship']['observation']['effectiveTime'] = array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					)
				);
				$entry['act']['entryRelationship']['observation']['effectiveTime']['low'] = array(
					'@attributes' => array(
						'value' => $this->parseDate($item['begin_date'])
					)
				);
				if($item['end_date'] != '0000-00-00'){
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['end_date'])
						)
					);
				}else{
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'nullFlavor' => 'NI'
						)
					);
				}


				$entry['act']['entryRelationship']['observation']['value'] = array(
					'@attributes' => array(
						'xsi:type' => 'CD',
						'code' => $item['code'],
						'codeSystemName' => $item['code_type'],
						'codeSystem' => $this->codes[$item['code_type']]
					)
				);
				$entry['act']['entryRelationship']['observation']['entryRelationship'] = array(
					'@attributes' => array(
						'typeCode' => 'REFR'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.6'
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => '33999-4',
								'displayName' => 'Status',
								'codeSystemName' => 'LOINC',
								'codeSystem' => '2.16.840.1.113883.6.1'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						),
						/**
						 * 55561003     SNOMEDCT    Active
						 * 73425007     SNOMEDCT    Inactive
						 * 413322009    SNOMEDCT    Resolved
						 */
						'value' => array(
							'@attributes' => array(
								'xsi:type' => 'CD',
								'code' => $this->CombosData->getCodeValueByListIdAndOptionValue(112, $item['status']),
								'displayName' => $item['status'],
								'codeSystemName' => 'SNOMED CT',
								'codeSystem' => '2.16.840.1.113883.6.96'
							)
						)
					)
				);

				$problems['section']['entry'][] = $entry;
				unset($entry);
			}

		}

		if($this->requiredProblems || !empty($problems['section']['entry'])){
			$this->addSection($problems);
		}
		unset($problemsData, $problems);
	}

	/**
	 * Method setAllergiesSection()
	 */
	private function setAllergiesSection(){
		$Allergies = new Allergies();

		$allergiesData = $Allergies->getPatientAllergiesByPid($this->pid);
		unset($Allergies);

		if(empty($allergiesData)){
			$allergies['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$allergies['section']['templateId'] = array(
			'@attributes' => array(
				'root' => $this->requiredAllergies ? '2.16.840.1.113883.10.20.22.2.6.1' : '2.16.840.1.113883.10.20.22.2.6'
			)
		);
		$allergies['section']['code'] = array(
			'@attributes' => array(
				'code' => '48765-2',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$allergies['section']['title'] = 'Allergies, Adverse Reactions, Alerts';
		$allergies['section']['text'] = '';

		if(!empty($allergiesData)){
			$allergies['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Substance'
									),
									array(
										'@value' => 'Reaction'
									),
									array(
										'@value' => 'Severity'
									),
									array(
										'@value' => 'Status'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);


			$allergies['section']['entry'] = array();

			foreach($allergiesData as $item){

				$allergies['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['allergy']
						),
						array(
							'@value' => $item['reaction']
						),
						array(
							'@value' => $item['severity']
						),
						array(
							'@value' => 'Status Data'
						)
					)
				);

				$entry = array(
					'act' => array(
						'@attributes' => array(
							'classCode' => 'ACT',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.30'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => '48765-2',
								'codeSystemName' => 'LOINC',
								'codeSystem' => '2.16.840.1.113883.6.1',
							)
						)
					)
				);

				$entry['act']['statusCode'] = array(
					'@attributes' => array( // use snomed code for active
						'code' => $item['end_date'] == '0000-00-00' ? 'active' : 'completed',
					)
				);

				$entry['act']['effectiveTime'] = array(
					'low' => array(
						'@attributes' => array(
							'value' => $this->parseDate($item['begin_date'])
						)
					)
				);

				if($entry['act']['statusCode']['@attributes']['code'] == 'completed'){
					$entry['act']['effectiveTime'] = array(
						'high' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['end_date'])
							)
						)
					);
				}

				$entry['act']['entryRelationship'] = array(
					'@attributes' => array(
						'typeCode' => 'SUBJ'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.7'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => 'ASSERTION',
								'codeSystem' => '2.16.840.1.113883.5.4'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						)
					)
				);

				$entry['act']['entryRelationship']['observation']['effectiveTime'] = array(
					// If it is unknown when the allergy began, this effectiveTime SHALL contain low/@nullFLavor="UNK" (CONF:9103)
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					)
				);

				if($item['begin_date'] != '0000-00-00'){
					$entry['act']['entryRelationship']['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['begin_date'])
						)
					);
				}else{
					$entry['act']['entryRelationship']['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'nullFLavor' => 'UNK'
						)
					);
				}

				if($item['end_date'] != '0000-00-00'){
					$entry['act']['entryRelationship']['observation']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['end_date'])
						)
					);
				}

				/**
				 * 420134006    SNOMEDCT    Propensity to adverse reactions
				 * 418038007    SNOMEDCT    Propensity to adverse reactions to substance
				 * 419511003    SNOMEDCT    Propensity to adverse reactions to drug
				 * 418471000    SNOMEDCT    Propensity to adverse reactions to food
				 * 419199007    SNOMEDCT    Allergy to substance
				 * 416098002    SNOMEDCT    Drug allergy
				 * 414285001    SNOMEDCT    Food allergy
				 * 59037007     SNOMEDCT    Drug intolerance
				 * 235719002    SNOMEDCT    Food intolerance
				 */

				$entry['act']['entryRelationship']['observation']['value'] = array(
					'@attributes' => array(
						'xsi:type' => 'CD',
						'code' => $item['allergy_type_code'],
						'displayName' => $item['allergy_type'],
						'codeSystemName' => $item['allergy_type_code_type'],
						'codeSystem' => $this->codes[$item['allergy_type_code_type']]
					)
				);

				$entry['act']['entryRelationship']['observation']['participant'] = array(
					'@attributes' => array(
						'typeCode' => 'CSM'
					),
					'participantRole' => array(
						'@attributes' => array(
							'classCode' => 'MANU'
						),
						'playingEntity' => array(
							'@attributes' => array(
								'classCode' => 'MMAT'
							),
							'code' => array(
								'@attributes' => array(
									'code' => $item['allergy_code'],
									'displayName' => $item['allergy'],
									'codeSystemName' => $item['allergy_code_type'],
									'codeSystem' => $this->codes[$item['allergy_code_type']]
								)
							)
						)
					)
				);


				// Allergy Status Observation
				$entryRelationship = array(
					'@attributes' => array(
						'typeCode' => 'SUBJ',
						'inversionInd' => 'true'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.28'
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => '33999-4',
								'codeSystemName' => 'LOINC',
								'codeSystem' => '2.16.840.1.113883.6.1'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						)
					)
				);

				$entryRelationship['observation']['effectiveTime'] = array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					)
				);

				if($item['begin_date'] != '0000-00-00'){
					$entryRelationship['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['begin_date'])
						)
					);
				}else{
					$entryRelationship['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'nullFLavor' => 'UNK'
						)
					);
				}

				if($item['end_date'] != '0000-00-00'){
					$entryRelationship['observation']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['end_date'])
						)
					);
				}

				$entryRelationship['observation']['value'] = array(
					'@attributes' => array(
						'xsi:type' => 'CE',
						'code' => $item['status_code'],
						'displayName' => $item['status'],
						'codeSystemName' => $item['status_code_type'],
						'codeSystem' => $this->codes[$item['status_code_type']]
					)
				);


				$entry['act']['entryRelationship']['observation']['entryRelationship'][] = $entryRelationship;
				unset($entryRelationship);

				// Reaction Observation
				$entryRelationship = array(
					'@attributes' => array(
						'typeCode' => 'MFST',
						'inversionInd' => 'true'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.9'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'nullFlavor' => 'NA'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						)
					)
				);


				$entryRelationship['observation']['effectiveTime'] = array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					)
				);

				if($item['begin_date'] != '0000-00-00'){
					$entryRelationship['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['begin_date'])
						)
					);
				}else{
					$entryRelationship['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'nullFLavor' => 'UNK'
						)
					);
				}

				if($item['end_date'] != '0000-00-00'){
					$entryRelationship['observation']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['end_date'])
						)
					);
				}

				$entryRelationship['observation']['value'] = array(
					'@attributes' => array(
						'xsi:type' => 'CD',
						'code' => $item['reaction_code'],
						'displayName' => $item['reaction'],
						'codeSystemName' => $item['reaction_code_type'],
						'codeSystem' => $this->codes[$item['reaction_code_type']]
					)
				);


				$entry['act']['entryRelationship']['observation']['entryRelationship'][] = $entryRelationship;
				unset($entryRelationship);

				// Severity Observation
				$entryRelationship = array(
					'@attributes' => array(
						'typeCode' => 'SUBJ',
						'inversionInd' => 'true'
					),
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.8'
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => 'SEV',
								'codeSystemName' => 'ActCode',
								'codeSystem' => '2.16.840.1.113883.5.4',
							    'displayName' => 'Severity Observation'
							)
						),
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed'
							)
						)
					)
				);

				$entryRelationship['observation']['effectiveTime'] = array(
					'@attributes' => array(
						'xsi:type' => 'IVL_TS',
					)
				);

				if($item['begin_date'] != '0000-00-00'){
					$entryRelationship['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['begin_date'])
						)
					);
				}else{
					$entryRelationship['observation']['effectiveTime']['low'] = array(
						'@attributes' => array(
							'nullFLavor' => 'UNK'
						)
					);
				}

				if($item['end_date'] != '0000-00-00'){
					$entryRelationship['observation']['effectiveTime']['high'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['end_date'])
						)
					);
				}

				$entryRelationship['observation']['value'] = array(
					'@attributes' => array(
						'xsi:type' => 'CD',
						'code' => $item['severity_code'],
						'displayName' => $item['severity'],
						'codeSystemName' => $item['severity_code_type'],
						'codeSystem' => $this->codes[$item['severity_code_type']]
					)
				);

				$entry['act']['entryRelationship']['observation']['entryRelationship'][] = $entryRelationship;
				unset($entryRelationship);

				$allergies['section']['entry'][] = $entry;

			}
		}
		if($this->requiredAllergies || !empty($allergies['section']['entry'])){
			$this->addSection($allergies);
		}
		unset($allergiesData, $allergies);
	}

	/**
	 * Method setSocialHistorySection()
	 */
	private function setSocialHistorySection(){

		$SocialHistory = new SocialHistory();

		$socialHistory = array(
			'section' => array(
				'templateId' => array(
					'@attributes' => array(
						'root' => '2.16.840.1.113883.10.20.22.2.17'
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => '29762-2',
						'codeSystemName' => 'LOINC',
						'codeSystem' => '2.16.840.1.113883.6.1'
					)
				),
				'title' => 'Social History',
				'text' => ''
			)
		);

		/***************************************************************************************************************
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

			$socialHistory['section']['entry'][] = array(
				'@attributes' => array(
					'typeCode' => 'DRIV'
				),
				'observation' => array(
					'@attributes' => array(
						'classCode' => 'OBS',
						'moodCode' => 'EVN'
					),
					'templateId' => array(
						'@attributes' => array(
							'root' => '2.16.840.1.113883.10.20.22.4.78'
						)
					),
					'code' => array(
						'@attributes' => array(
							'code' => 'ASSERTION',
							'codeSystemName' => 'ActCode',
							'codeSystem' => '2.16.840.1.113883.5.4'
						)
					),
					'statusCode' => array(
						'@attributes' => array(
							'code' => 'completed',
						)
					),
					'effectiveTime' => array(
						'@attributes' => array(
							'xsi:type' => 'IVL_TS',
						),
						'low' => array(
							'@attributes' => array(
								'value' => $this->parseDate($smokingStatus['create_date'])
							)
						)
					),
					/**
					 * Code             System      Print Name
					 * 449868002        SNOMEDCT    Current every day smoker
					 * 428041000124106  SNOMEDCT    Current some day smoker
					 * 8517006          SNOMEDCT    Former smoker
					 * 266919005        SNOMEDCT    Never smoker (Never Smoked)
					 * 77176002         SNOMEDCT    Smoker, current status unknown
					 * 266927001        SNOMEDCT    Unknown if ever smoked
					 */
					'value' => array(
						'@attributes' => array(
							'xsi:type' => 'CD',
							'code' => $smokingStatus['status_code'],
							'displayName' => $smokingStatus['status'],
							'codeSystemName' => $smokingStatus['status_code_type'],
							'codeSystem' => $this->codes[$smokingStatus['status_code_type']]
						)
					)
				)
			);
		}
		unset($smokingStatus);


		/***************************************************************************************************************
		 * This Social History Observation defines the patient's occupational, personal (e.g., lifestyle),
		 * social, and environmental history and health risk factors, as well as administrative data such
		 * as marital status, race, ethnicity, and religious affiliation.
		 */
		$socialHistories = $SocialHistory->getSocialHistoryByPidAndCode($this->pid);


		if(count($socialHistories) > 0){

			$socialHistory['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Social History Element'
									),
									array(
										'@value' => 'Description'
									),
									array(
										'@value' => 'Effective Dates'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);
		}

		foreach($socialHistories As $socialHistoryEntry){

			$dateText = $this->parseDate($socialHistoryEntry['start_date']) . ' - ';
			if($socialHistoryEntry['end_date'] != '0000-00-00 00:00:00') $dateText .= $this->parseDate($socialHistoryEntry['end_date']);

			$socialHistory['section']['text']['table']['tbody']['tr'][] = array(
				'td' => array(
					array(
						'@value' => $socialHistoryEntry['category_code_text']
					),
					array(
						'@value' => $socialHistoryEntry['observation']
					),
					array(
						'@value' => $dateText
					)
				)
			);

			$entry = array(
				'@attributes' => array(
					'typeCode' => 'DRIV'
				),
				'observation' => array(
					'@attributes' => array(
						'classCode' => 'OBS',
						'moodCode' => 'EVN'
					),
					'templateId' => array(
						'@attributes' => array(
							'root' => '2.16.840.1.113883.10.20.22.4.38'
						)
					),
					'id' => array(
						'@attributes' => array(
							'root' => UUID::v4()
						)
					),
					/**
					 * Code	        System  	Print Name
					 * 229819007	SNOMEDCT	Tobacco use and exposure
					 * 256235009	SNOMEDCT	Exercise
					 * 160573003	SNOMEDCT	Alcohol intake
					 * 364393001	SNOMEDCT	Nutritional observable
					 * 364703007	SNOMEDCT	Employment detail
					 * 425400000	SNOMEDCT	Toxic exposure status
					 * 363908000	SNOMEDCT	Details of drug misuse behavior
					 * 228272008	SNOMEDCT	Health-related behavior
					 * 105421008	SNOMEDCT	Educational Achievement
					 */
					'code' => array(
						'@attributes' => array(
							'code' => $socialHistoryEntry['category_code'],
							'codeSystem' => $this->codes[$socialHistoryEntry['category_code_type']],
							'codeSystemName' => $socialHistoryEntry['category_code_text'],
						    'displayName' => $socialHistoryEntry['category_code_text']
						)
					),
					'statusCode' => array(
						'@attributes' => array(
							'code' => 'completed',
						)
					)
				)
			);

			$entry['observation']['effectiveTime'] = array(
				'@attributes' => array(
					'xsi:type' => 'IVL_TS',
				)
			);

			$entry['observation']['effectiveTime']['low'] = array(
				'@attributes' => array(
					'value' => $this->parseDate($socialHistoryEntry['start_date'])
				)
			);

			if($socialHistoryEntry['end_date'] != '0000-00-00 00:00:00'){
				$entry['observation']['effectiveTime']['high'] = array(
					'@attributes' => array(
						'value' => $this->parseDate($socialHistoryEntry['end_date'])
					)
				);
			}else{
				$entry['observation']['effectiveTime']['high'] = array(
					'@attributes' => array(
						'nullFlavor' => 'NI'
					)
				);
			}

			$entry['observation']['value'] = array(
				'@attributes' => array(
					'xsi:type' => 'ST'
				),
				'@value' => $socialHistoryEntry['observation']
			);

			$socialHistory['section']['entry'][] = $entry;

			unset($entry);

		}
		unset($socialHistories);


//		/***************************************************************************************************************
//		 * Pregnancy Observation - This clinical statement represents current and/or
//		 * prior pregnancy dates enabling investigators to determine if the subject
//		 * of the case report* was pregnant during the course of a condition.
//		 */
//		$socialHistory['section']['text']['table']['tbody']['tr'][] = array(
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
//		$socialHistory['section']['entry'][] = array(
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

		$this->addSection($socialHistory);
		unset($socialHistoryData, $socialHistory);
	}

	/**
	 * Method setResultsSection()
	 */
	private function setResultsSection(){

		$Orders = new Orders();
		$resultsData = $Orders->getOrderWithResultsByPid($this->pid);

		$results = array();

		if(empty($resultsData)){
			$results['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$results['section']['templateId'] = array(
			'@attributes' => array(
				'root' => $this->requiredResults ? '2.16.840.1.113883.10.20.22.2.3.1' : '2.16.840.1.113883.10.20.22.2.3'
			)
		);
		$results['section']['code'] = array(
			'@attributes' => array(
				'code' => '30954-2',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$results['section']['title'] = 'Results';
		$results['section']['text'] = '';

		if(!empty($resultsData)){

			$results['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
				    'tbody' => array()
				)
			);
			$results['section']['entry'] = array();

			foreach($resultsData as $item){

				$results['section']['text']['table']['tbody'][] = array(
					'tr' => array(
						array(
							'th' => array(
								array(
									'@value' => $item['description']
								),
								array(
									'@value' => $this->parseDateToText($item['result']['result_date'])
								)
							)
						)
					)
				);


				$entry = array(
					'@attributes' => array(
						'typeCode' => 'DRIV'
					),
					'organizer' => array(
						'@attributes' => array(
							'classCode' => 'CLUSTER', // CLUSTER || BATTERY
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								'root' => '2.16.840.1.113883.10.20.22.4.1'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								'code' => $item['code'],
								'displayName' => $item['description'],
								'codeSystemName' => $item['code_type'],
								'codeSystem' => $this->codes[$item['code_type']]
							)
						),
						/**
						 * Code         System      Print Name
						 * aborted      ActStatus   aborted
						 * active       ActStatus   active
						 * cancelled    ActStatus   cancelled
						 * completed    ActStatus   completed
						 * held         ActStatus   held
						 * suspended    ActStatus   suspended
						 */
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed',
							)
						),
						'component' => array()
					)
				);


				foreach($item['result']['observations'] as $obs){
					$results['section']['text']['table']['tbody'][] = array(
						'tr' => array(
							array(
								'td' => array(
									array(
										'@value' => $obs['code_text']
									),
									array(
										'@attributes' => array(
											'align' => 'left'
										),
										'@value' => htmlentities($obs['value'] . ' ' . $obs['units'] . ' ['. $obs['reference_rage'] . ']')
									)
								)
							)
						)
					);

					$component = array(
						'observation' => array(
							'@attributes' => array(
								'classCode' => 'OBS',
								'moodCode' => 'EVN'
							),
							'templateId' => array(
								'@attributes' => array(
									'root' => '2.16.840.1.113883.10.20.22.4.2'
								)
							),
							'id' => array(
								'@attributes' => array(
									'root' => UUID::v4()
								)
							),
							'code' => array(
								'@attributes' => array(
									'code' => $obs['code'],
									'codeSystemName' => $obs['code_type'],
									'codeSystem' => $this->codes[$obs['code_type']],
									'displayName' => $obs['code_text']
								)
							),
							/**
							 * Code         System      Print Name
							 * aborted      ActStatus   aborted
							 * active       ActStatus   active
							 * cancelled    ActStatus   cancelled
							 * completed    ActStatus   completed
							 * held         ActStatus   held
							 * suspended    ActStatus   suspended
							 */
							'statusCode' => array(
								'@attributes' => array(
									'code' => 'completed'
								)
							)
						)
					);

					$component['observation']['effectiveTime'] = array(
						'@attributes' => array(
							'xsi:type' => 'IVL_TS',
						),
						'low' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['result']['result_date'])
							)
						),
						'high' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['result']['result_date'])
							)
						)
					);

					if(is_numeric($obs['value'])){
						$component['observation']['value'] = array(
							'@attributes' => array(
								'xsi:type' => 'PQ',
								'value' => htmlentities($obs['value'])
							)
						);
						if($obs['units'] != ''){
							$component['observation']['value']['@attributes']['unit'] = htmlentities($obs['units']);
						}
					}else{
						$component['observation']['value'] = array(
							'@attributes' => array(
								'xsi:type' => 'ST'
							),
							'@value' => htmlentities($obs['value'])
						);
					}




					$component['observation']['interpretationCode'] = array(
						'@attributes' => array(
							'code' => htmlentities($obs['abnormal_flag']),
							'codeSystemName' => 'ObservationInterpretation',
							'codeSystem' => '2.16.840.1.113883.5.83'
						)
					);

					$ranges = preg_split("/to|-/", $obs['reference_rage']);
					if(is_array($ranges) && count($ranges) > 2){

						$component['observation']['referenceRange'] = array(
							'observationRange' => array(
								'value' => array(
									'@attributes' => array(
										'xsi:type' => 'IVL_PQ'
									),
									'low' => array(
										'@attributes' => array(
											'value' => htmlentities($ranges[0]),
											'unit' => htmlentities($obs['units'])
										)
									),
									'high' => array(
										'@attributes' => array(
											'value' => htmlentities($ranges[1]),
											'unit' => htmlentities($obs['units'])
										)
									)
								)
							)
						);

					}

					$entry['organizer']['component'][] = $component;

				}

				$results['section']['entry'][] = $entry;
			}



		}

		if($this->requiredResults || !empty($results['section']['entry'])){
			$this->addSection($results);
		}
		unset($resultsData, $results, $order);
	}

	/**
	 * Method setFunctionalStatusSection() TODO
	 */
	private function setFunctionalStatusSection(){

		$CognitiveAndFunctionalStatus = new CognitiveAndFunctionalStatus();
		$functionalStatusData = $CognitiveAndFunctionalStatus->getPatientCognitiveAndFunctionalStatusesByPid($this->pid);


		if(empty($functionalStatusData)){
			$functionalStatus['section']['@attributes'] = array(
				'nullFlavor' => 'NI'
			);
		}
		$functionalStatus['section']['templateId'] = array(
			'@attributes' => array(
				'root' => '2.16.840.1.113883.10.20.22.2.14'
			)
		);
		$functionalStatus['section']['code'] = array(
			'@attributes' => array(
				'code' => '47420-5',
				'codeSystemName' => 'LOINC',
				'codeSystem' => '2.16.840.1.113883.6.1'
			)
		);
		$functionalStatus['section']['title'] = 'Functional status assessment';
		$functionalStatus['section']['text'] = '';

		if(!empty($functionalStatusData)){
			$functionalStatus['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Functional or Cognitive Finding'
									),
									array(
										'@value' => 'Observation'
									),
									array(
										'@value' => 'Observation Date'
									),
									array(
										'@value' => 'Condition Status'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);
			$functionalStatus['section']['entry'] = array();


			foreach($functionalStatusData as $item){

				$functionalStatus['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => $item['category']
						),
						array(
							'@value' => $item['code_text']
						),
						array(
							'@value' => $this->parseDate($item['created_date'])
						),
						array(
							'@value' => $item['status']
						)
					)

				);

				$entry = array(
					'observation' => array(
						'@attributes' => array(
							'classCode' => 'OBS',
							'moodCode' => 'EVN'
						),
					)
				);

				$entry['observation']['templateId'] = array(
					'@attributes' => array(
						'root' => ($item['category_code'] == '363871006' ? '2.16.840.1.113883.10.20.22.4.74': '2.16.840.1.113883.10.20.22.4.67')
			)
				);

				$entry['observation']['id'] = array(
					'@attributes' => array(
						'root' => UUID::v4()
					)
				);

				$entry['observation']['code'] = array(
					'@attributes' => array(
						'code' => $item['category_code'],
						'codeSystemName' => $item['category_code_type'],
						'codeSystem' => $this->codes[$item['category_code_type']],
						'displayName' => $item['category']
					)
				);

				$entry['observation']['statusCode'] = array(
					'@attributes' => array(
						'code' => 'completed',
					)
				);

				if($item['begin_date'] != '0000-00-00'){
					$entry['observation']['effectiveTime'] = array(
						'@attributes' => array(
							'value' => $this->parseDate($item['created_date']),
						)
					);
				}elseif($item['end_date'] != '0000-00-00'){
					$entry['observation']['effectiveTime'] = array(
						'@attributes' => array(
							'xsi:type' => 'IVL_TS',
						),
						'low' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['begin_date']),
							)
						),
						'high' => array(
							'@attributes' => array(
								'nullFlavor' => 'NI'
							)
						)
					);
				}else{
					$entry['observation']['effectiveTime'] = array(
						'@attributes' => array(
							'xsi:type' => 'IVL_TS',
						),
						'low' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['begin_date']),
							)
						),
						'high' => array(
							'@attributes' => array(
								'value' => $this->parseDate($item['end_date']),
							)
						)
					);
				}

				$entry['observation']['value'] = array(
					'@attributes' => array(
						'xsi:type' => 'CD',
						'code' => $item['code'],
						'codeSystemName' => $item['code_type'],
						'codeSystem' => $this->codes[$item['code_type']],
						'displayName' => $item['code_text']
					)
				);

				$functionalStatus['section']['entry'][] = $entry;
			}
		}

		if($this->requiredResults || !empty($functionalStatus['section']['entry'])){
			$this->addSection($functionalStatus);
		}
		unset($functionalStatusData, $functionalStatus);
	}

	/**
	 * Method setEncountersSection() TODO
	 */
	private function setEncountersSection(){
		$encounters = array(
			'section' => array(
				'templateId' => array(
					'@attributes' => array(
						'root' => $this->requiredEncounters ? '2.16.840.1.113883.10.20.22.2.22.1' : '2.16.840.1.113883.10.20.22.2.22'
					)
				),
				'code' => array(
					'@attributes' => array(
						'code' => '46240-8',
						'codeSystemName' => 'LOINC',
						'codeSystem' => '2.16.840.1.113883.6.1'
					)
				),
				'title' => 'Encounters',
				'text' => ''
			)
		);


		$encountersData = array();


		if(!empty($encountersData)){
			$encounters['section']['text'] = array(
				'table' => array(
					'@attributes' => array(
						'border' => '1',
						'width' => '100%'
					),
					'thead' => array(
						'tr' => array(
							array(
								'th' => array(
									array(
										'@value' => 'Functional Condition'
									),
									array(
										'@value' => 'Effective Dates'
									),
									array(
										'@value' => 'Condition Status'
									)
								)
							)
						)
					),
					'tbody' => array(
						'tr' => array()
					)
				)
			);
			$encounters['section']['entry'] = array();

			foreach($encountersData as $item){

				$encounters['section']['text']['table']['tbody']['tr'][] = array(
					'td' => array(
						array(
							'@value' => 'Functional Condition Data'
						),
						array(
							'@value' => 'Effective Dates Data'
						),
						array(
							'@value' => 'Condition Status Data'
						)
					)

				);

				$encounters['section']['entry'][] = $order = array(
					'encounter' => array(
						'@attributes' => array(
							'classCode' => 'ENC',
							'moodCode' => 'EVN'
						),
						'templateId' => array(
							'@attributes' => array(
								//2.16.840.1.113883.3.88.11.83.127
								'root' => '2.16.840.1.113883.10.20.22.4.49'
							)
						),
						'id' => array(
							'@attributes' => array(
								'root' => UUID::v4()
							)
						),
						'code' => array(
							'@attributes' => array(
								// CPT4 Visit code 99200 <-> 99299
								'code' => '99200',
								'codeSystem' => $this->codes['CPT4'],
							)
						),
						/**
						 * Code         System      Print Name
						 * aborted      ActStatus   aborted
						 * active       ActStatus   active
						 * cancelled    ActStatus   cancelled
						 * completed    ActStatus   completed
						 * held         ActStatus   held
						 * suspended    ActStatus   suspended
						 */
						'statusCode' => array(
							'@attributes' => array(
								'code' => 'completed',
							)
						),
						'effectiveTime' => array(
							'@attributes' => array(
								'xsi:type' => 'IVL_TS',
							),
							// low date is required
							'low' => array(
								'@attributes' => array(
									'value' => '19320924'
								)
							),
							'high' => array(
								'@attributes' => array(
									'value' => '19320924'
								)
							)
						),
						'entryRelationship' => array(
							/*************************************
							 * Encounter Diagnosis
							 */
							array(
								'@attributes' => array(
									'typeCode' => 'SUBJ',
								),
								'observation' => array(
									'@attributes' => array(
										'classCode' => 'ACT',
										'moodCode' => 'EVN'
									),
									'templateId' => array(
										'@attributes' => array(
											'root' => '2.16.840.1.113883.10.20.22.4.80'
										)
									),
									'code' => array(
										'@attributes' => array(
											'code' => '29308-4',
											'codeSystem' => '2.16.840.1.113883.6.1',

										)
									),
									'entryRelationship' => array(
										/*************************************
										 * Problem Observation
										 */
										array(
											'@attributes' => array(
												'typeCode' => 'SUBJ',
											),
											'observation' => array(
												'@attributes' => array(
													'classCode' => 'OBS',
													'moodCode' => 'EVN'
												),
												'templateId' => array(
													'@attributes' => array(
														'root' => '2.16.840.1.113883.10.20.22.4.4'
													)
												),
												'id' => array(
													'@attributes' => array(
														'root' => UUID::v4()
													)
												),
												/**
												 * Code             System      Print Name
												 * 404684003        SNOMEDCT    Finding
												 * 409586006        SNOMEDCT    Complaint
												 * 282291009        SNOMEDCT    Diagnosis
												 * 64572001         SNOMEDCT    Condition
												 * 248536006        SNOMEDCT    Functional limitation
												 * 418799008        SNOMEDCT    Symptom
												 * 55607006         SNOMEDCT    Problem
												 * 373930000        SNOMEDCT    Cognitive function finding
												 */
												'code' => array(
													'@attributes' => array(
														'code' => '282291009',
														'codeSystem' => '2.16.840.1.113883.6.96',

													),
													'originalText' => 'Original text'
												),
												'statusCode' => array(
													'@attributes' => array(
														'code' => 'completed'
													)
												),
												'value' => array(
													'@attributes' => array(
														'xsi:type' => 'CD',
														// SNOMEDCT problem list
														'value' => '20150123'
													)
												),
												'entryRelationship' => array(
													/*************************************
													 *  Problem Status
													 */
													array(
														'@attributes' => array(
															'typeCode' => 'REFR',
														),
														'observation' => array(
															'@attributes' => array(
																'classCode' => 'OBS',
																'moodCode' => 'EVN'
															),
															'templateId' => array(
																'@attributes' => array(
																	'root' => '2.16.840.1.113883.10.20.22.4.6'
																)
															),
															'code' => array(
																'@attributes' => array(
																	'code' => '33999-4',
																	'codeSystem' => '2.16.840.1.113883.6.1',
																)
															),
															'statusCode' => array(
																'@attributes' => array(
																	'code' => 'completed'
																)
															),
															/**
															 * Code         System      Print Name
															 * 55561003     SNOMEDCT    Active
															 * 73425007     SNOMEDCT    Inactive
															 * 413322009    SNOMEDCT    Resolved
															 */
															'value' => array(
																'@attributes' => array(
																	'xsi:type' => 'CD',
																	'code' => '413322009'
																)
															)
														)
													),
													/*************************************
													 *  Health Status Observation
													 */
													array(
														'@attributes' => array(
															'typeCode' => 'REFR',
														),
														'observation' => array(
															'@attributes' => array(
																'classCode' => 'OBS',
																'moodCode' => 'EVN'
															),
															'templateId' => array(
																'@attributes' => array(
																	'root' => '2.16.840.1.113883.10.20.22.4.5'
																)
															),
															'code' => array(
																'@attributes' => array(
																	'code' => '11323-3',
																	'codeSystem' => '2.16.840.1.113883.6.1',

																)
															),
															/**
															 * Code         System      Print Name
															 * 81323004     SNOMEDCT    Alive and well
															 * 313386006    SNOMEDCT    In remission
															 * 162467007    SNOMEDCT    Symptom free
															 * 161901003    SNOMEDCT    Chronically ill
															 * 271593001    SNOMEDCT    Severely ill
															 * 21134002     SNOMEDCT    Disabled
															 * 161045001    SNOMEDCT    Severely disabled
															 */
															'value' => array(
																'@attributes' => array(
																	'xsi:type' => 'CD',
																	'code' => '81323004'
																)
															)
														)
													)
												)
											)
										)
									)
								)
							)
						)
					)
				);
			}
		}

		if($this->requiredEncounters || !empty($encounters['section']['entry'])){
			$this->addSection($encounters);
		}
		unset($encountersData, $encounters);
	}

	private function parseDateToText($date){
		return date('F Y', strtotime($date));
	}

	private function parseDate($date){
		$foo = explode(' ', $date);
		return str_replace('-', '', $foo[0]);
	}
}

/**
 * Handle the request only if pid and action is available
 */
if(isset($_REQUEST['pid']) && isset($_REQUEST['action'])){

	if(!isset($_REQUEST['token']) || str_replace(' ', '+', $_REQUEST['token']) != $_SESSION['user']['token']) die('Not Authorized!');
	/**
	 * Check token for security
	 */
	include_once(ROOT . '/sites/' . $_REQUEST['site'] . '/conf.php');
	include_once(ROOT . '/classes/MatchaHelper.php');
	$ccd = new CCDDocument();
	$ccd->setPid($_REQUEST['pid']);
	$ccd->setTemplate('toc');
	$ccd->createCCD();

	if($_REQUEST['action'] == 'view'){
		$ccd->view();
	} elseif($_REQUEST['action'] == 'export'){
		$ccd->export();
	}
}

