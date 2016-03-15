<?php

class SoapHandler {

	/**
	 * @var stdClass
	 */
	private $params;

	private $site;

	private $facility;

	private $provider;

	private $patient;

	private $vDate = '/\d{4}-\d{2}-\d{2}/';

	private $vDateTime = '/\d{4}-\d{2}-\d{2}/ \d{2}:\d{2}:\d{2}/';

	function constructor($params)
    {
		$this->params = $params;

		$this->site = isset($params->ServerSite) ? $params->ServerSite : 'default';

		$this->facility = isset($params->Facility) ? $params->Facility : '1';

		if(!defined('_GaiaEXEC')) define('_GaiaEXEC', 1);

        define('SITE', $params->ServerSite);

		include_once(str_replace('\\', '/', dirname(__FILE__)) . '/../../registry.php');
		include_once(ROOT . "/sites/{$this->site}/conf.php");
		include_once(ROOT . '/classes/MatchaHelper.php');
		if(isset($params->Provider)) $this->getProvider($params->Provider);
		if(isset($params->Patient)) $this->getPatient($params->Patient);
	}

	/**
	 * @return bool
	 */
	protected function isAuth()
    {
		require_once(ROOT . '/dataProvider/Applications.php');
		$Applications = new Applications();
		$access = $Applications->hasAccess($this->params->SecureKey);
		unset($Applications);
		return $access;
	}


	public function PatientPortalAuthorize($params)
    {
		$this->constructor($params);
		if(!$this->isAuth())
        {
			return [
				'Success' => false,
				'Error' => 'Error: HTTP 403 Access Forbidden'
			];
		}
		$patient = $this->getPatient($params);

		$response = [
			'Success' => false,
			'Error' => 'Not Authorized'
		];

		if(!isset($patient)) return $response;

		if(	$patient->WebPortalPassword !== $params->Password ||
			substr($patient->DateOfBirth, 0, 10) !== $params->DateOfBirth )
        {
			return $response;
		}
		$response = [
			'Success' => true,
			'Patient' => $patient,
			'Error' => ''
		];
		return $response;
	}

	/**
	 * Method to handle patient amendments from the Patient Portal
	 * @param $params
	 * @return array
	 */
	public function newPatientAmendment($params)
    {
		try
        {
			$this->constructor($params);
			if(!$this->isAuth())
            {
				return [
					'Success' => false,
					'Error' => 'Error: HTTP 403 Access Forbidden'
				];
			}

			include_once (ROOT . '/dataProvider/Amendments.php');
			$Amendments = new Amendments();
			$data = json_decode($params->Data);

			if(isset($data->demographics) && count($data->demographics) > 0)
            {
				foreach($data->demographics as &$demographic)
                {
					if(array_key_exists($demographic->field_name, $this->demographicsMap))
                    {
						$demographic->field_name = $this->demographicsMap[$demographic->field_name];
					}
				}
			}

			$record = new stdClass();

			$record->portal_id = $params->PortalId;
			$record->pid = $params->Pid;
			$record->amendment_type = $params->Type;
			$record->amendment_data = $data;
			$record->amendment_message = $params->Message;
			$record->amendment_status = 'W';
			$record->is_read = '0';
			$record->is_viewed = '0';
			$record->is_synced = '1';
			$record->assigned_to_uid = '0';

			$record->create_uid = '0';
			$record->create_date = date('Y-m-d H:i:s');

			$record = $Amendments->addAmendment($record);
			$record = (object) $record['data'];

			if(isset($record->id) && $record->id > 0)
            {
				return [
					'Success' => true,
					'AmendmentId' => $record->id,
					'Error' => ''
				];
			}
            else
            {
				return [
					'Success' => false,
					'Error' => 'Unable to complete request, Please contact provider.'
				];
			}
		}
        catch (Exception $e)
        {
			return [
				'Success' => false,
				'Error' => 'Unable to complete request, Please contact provider.'
			];
		}
	}


	public function cancelPatientAmendment($params)
    {
		$this->constructor($params);
		if(!$this->isAuth())
        {
			return [
				'Success' => false,
				'Error' => 'Error: HTTP 403 Access Forbidden'
			];
		}

		include_once (ROOT . '/dataProvider/Amendments.php');
		$Amendments = new Amendments();

		$record = $Amendments->getAmendment([
			'id' => $params->AmendmentId,
			'pid' => $params->Pid
		]);


		if($record === false){
			return [
				'Success' => false,
				'Error' => 'Unable to find Amendment request'
			];

		}

		$record = (object) $record;

		if($record->amendment_status != 'W'){

			if($record->amendment_status === 'A'){
				$msg = 'Unable to cancel, Amendment previously approved';
			}elseif($record->amendment_status === 'D'){
				$msg = 'Unable to cancel, Amendment previously denied';
			}elseif($record->amendment_status === 'C'){
				$msg = 'Unable to cancel, Amendment previously canceled';
			}else{
				$msg = 'Unable to cancel, Amendment due to status (' . $record->amendment_status . ')';
			}

			return [
				'Success' => false,
				'Error' => $msg
			];
		}

		$record->is_synced = '1';
		$record->update_uid = 0;
		$record->amendment_status = 'C';
		$record->cancel_date = date('Y-m-d H:i:s');
		$record->cancel_by = 'P'. $record->pid;
		$record->update_date = date('Y-m-d H:i:s');

		$Amendments->updateAmendment($record);

		return [
			'Success' => true,
			'Error' => ''
		];

	}

	/**
	 * @param $params
	 * @return array
	 */
	public function GetCCDDocument($params) {
		$this->constructor($params);

		$_SESSION['user']['facility'] = $this->facility;

		$ccd = new CCDDocument();
		$ccd->setPid($params->pid);
		$ccd->setTemplate('toc');
		$ccd->createCCD();

		if(!$this->isAuth()){
			return [
				'Success' => false,
				'Error' => 'Error: HTTP 403 Access Forbidden'
			];
		}
		return [
			'Success' => true,
			'Document' => $ccd->get()
		];
	}

	public function AddPatient($params) {

		try {

			$this->constructor($params);

			if(!$this->isAuth()){
				throw new \Exception('Error: HTTP 403 Access Forbidden');
			}

			/**
			 * Patient class
			 */
			require_once(ROOT . '/dataProvider/Patient.php');
			$Patient = new Patient();

			/**
			 * validations
			 */
			$validations = [];
			if(!isset($params->Patient->FirstName)){
				$validations[] = 'First Name Missing';
			}
			if(!isset($params->Patient->LastName)){
				$validations[] = 'Last Name Missing';
			}
			if(!isset($params->Patient->DOB)){
				$validations[] = 'DOB Missing';
			}
			if(preg_match($this->vDate, $params->Patient->DOB) == 0){
				$validations[] = 'DOB format YYYY-MM-DD not valid';
			}
			if(!isset($params->Patient->Sex)){
				$validations[] = 'Sex Missing';
			}
			if(isset($params->Patient->DriveLicenceExpirationDate) && preg_match($this->vDate, $params->Patient->DriveLicenceExpirationDate) == 0){
				$validations[] = 'DriveLicenceExpirationDate format YYYY-MM-DD not valid';
			}
			if(isset($params->Patient->DeceaseDate) && preg_match($this->vDate, $params->Patient->DeceaseDate) == 0){
				$validations[] = 'DeceaseDate format YYYY-MM-DD not valid';
			}
			if(isset($params->Patient->DeathDate) && preg_match($this->vDate, $params->Patient->DeathDate) == 0){
				$validations[] = 'DeathDate format YYYY-MM-DD not valid';
			}
			if(isset($params->Patient->DeathDate) && preg_match($this->vDate, $params->Patient->DeathDate) == 0){
				$validations[] = 'DeathDate format YYYY-MM-DD not valid';
			}
			if (isset($params->Patient->Email) && !filter_var($params->Patient->Email, FILTER_VALIDATE_EMAIL)) {
				$validations[] = 'Invalid Email format';
			}

			// TODO validate Sex, MaritalStatus, Race, Ethnicity, Religion, and Language  HL7 values


			if(isset($params->Patient->Pid) && $Patient->getPatientByPid($params->Patient->Pid) !== false){
				$validations[] = 'Duplicated Pid found in database';
			}
			if(isset($params->Patient->RecordNumber) && $Patient->getPatientByPublicId($params->Patient->RecordNumber) !== false){
				$validations[] = 'Duplicated RecordNumber found in database';
			}
			if(!empty($validations)){
				throw new \Exception('Validation Error: ' . implode(', ', $validations));
			}

			/**
			 * Lets continue
			 */

			$patient = new stdClass();
			// basic info
			$patient->pubpid = isset($params->Patient->RecordNumber) ? $params->Patient->RecordNumber : '';
			$patient->title = isset($params->Patient->Title) ? $params->Patient->Title : '';
			$patient->fname = $params->Patient->FirstName;
			$patient->mname = isset($params->Patient->MiddleName) ? $params->Patient->MiddleName : '';
			$patient->lname = $params->Patient->LastName;
			$patient->DOB = $params->Patient->DOB;
			$patient->sex = $params->Patient->Sex;
			// extra info
			$patient->SS = isset($params->Patient->SSN) ? $params->Patient->SSN : '';
			$patient->marital_status = isset($params->Patient->MaritalStatus) ? $params->Patient->MaritalStatus : '';
			$patient->race = isset($params->Patient->Race) ? $params->Patient->Race : '';
			$patient->ethnicity = isset($params->Patient->Ethnicity) ? $params->Patient->Ethnicity : '';
			$patient->religion = isset($params->Patient->Religion) ? $params->Patient->Religion : '';
			$patient->language = isset($params->Patient->Language) ? $params->Patient->Language : '';
			// driver lic
			$patient->drivers_license = isset($params->Patient->DriverLicence) ? $params->Patient->DriverLicence : '';
			$patient->drivers_license_state = isset($params->Patient->DriverLicenceState) ? $params->Patient->DriverLicenceState : '';
			$patient->drivers_license_exp = isset($params->Patient->DriverLicenceExpirationDate) ? $params->Patient->DriverLicenceExpirationDate : '0000-00-00';
			// physical address
			$patient->address = isset($params->Patient->PhysicalAddressLineOne) ? $params->Patient->PhysicalAddressLineOne : '';
			$patient->address_cont = isset($params->Patient->PhysicalAddressLineTwo) ? $params->Patient->PhysicalAddressLineTwo : '';
			$patient->city = isset($params->Patient->PhysicalCity) ? $params->Patient->PhysicalCity : '';
			$patient->state = isset($params->Patient->PhysicalState) ? $params->Patient->PhysicalState : '';
			$patient->country = isset($params->Patient->PhysicalCountry) ? $params->Patient->PhysicalCountry : '';
			$patient->zipcode = isset($params->Patient->PhysicalZipCode) ? $params->Patient->PhysicalZipCode : '';
			// postal address
//			$patient->mname = isset($params->Patient->PostalAddressLineOne) ? $params->Patient->PostalAddressLineOne : '';
//			$patient->mname = isset($params->Patient->PostalAddressLineTwo) ? $params->Patient->PostalAddressLineTwo : '';
//			$patient->mname = isset($params->Patient->PostalCity) ? $params->Patient->PostalCity : '';
//			$patient->mname = isset($params->Patient->PostalState) ? $params->Patient->PostalState : '';
//			$patient->mname = isset($params->Patient->PostalCountry) ? $params->Patient->PostalCountry : '';
//			$patient->mname = isset($params->Patient->PostalZipCode) ? $params->Patient->PostalZipCode : '';
			// phones and email info
			$patient->home_phone = isset($params->Patient->HomePhoneNumber) ? $params->Patient->HomePhoneNumber : '';
			$patient->mobile_phone = isset($params->Patient->MobilePhoneNumber) ? $params->Patient->MobilePhoneNumber : '';
			$patient->work_phone = isset($params->Patient->WorkPhoneNumber) ? $params->Patient->WorkPhoneNumber : '';
			$patient->work_phone_ext = isset($params->Patient->WorkPhoneExt) ? $params->Patient->WorkPhoneExt : '';
			$patient->email = isset($params->Patient->Email) ? $params->Patient->Email : '';
			// image
			$patient->image = isset($params->Patient->Image) ? $params->Patient->Image : '';
			// ....
			$patient->birth_place = isset($params->Patient->BirthPlace) ? $params->Patient->BirthPlace : '';
			$patient->birth_multiple = isset($params->Patient->IsBirthMultiple) ? $params->Patient->IsBirthMultiple : '0';
			$patient->birth_order = isset($params->Patient->BirthOrder) ? $params->Patient->BirthOrder : null;
			$patient->deceased = isset($params->Patient->Deceased) ? $params->Patient->Deceased : '0';
			$patient->death_date = isset($params->Patient->DeceaseDate) ? $params->Patient->DeceaseDate : '0000-00-00';
			$patient->mothers_name = isset($params->Patient->MothersName) ? $params->Patient->MothersName : '';
			$patient->guardians_name = isset($params->Patient->GuardiansName) ? $params->Patient->GuardiansName : '';
			$patient->emer_contact = isset($params->Patient->EmergencyContact) ? $params->Patient->EmergencyContact : '';
			$patient->emer_phone = isset($params->Patient->EmergencyPhone) ? $params->Patient->EmergencyPhone : '';
			$patient->occupation = isset($params->Patient->Occupation) ? $params->Patient->Occupation : '';

			$patient = (object)$Patient->createNewPatient($patient);

			return [
				'Success' => true,
				'Pid' => $patient->pid,
				'RecordNumber' => $patient->pubpid
			];

		} catch(\Exception $e) {
			return [
				'Success' => false,
				'Error' => $e->getMessage()
			];
		}

	}

	/**
	 * @param $params
	 * @return array
	 */
	public function UploadPatientDocument($params) {
		$this->constructor($params);

		if(!$this->isAuth()){
			return [
				'Success' => false,
				'Error' => 'Error: HTTP 403 Access Forbidden'
			];
		}

		if(!$this->isPatientValid()){
			return [
				'Success' => false,
				'Error' => 'Error: No Valid Patient Found'
			];
		}

		if(!$this->isProviderValid()){
			return [
				'Success' => false,
				'Error' => 'Error: No Valid Provider Found'
			];
		}

		$document = new stdClass();
		$document->eid = 0;
		$document->pid = $this->patient->pid;
		$document->uid = $this->provider->id;
		$document->name = 'SoapUpload.pdf';

		$document->date = $params->Document->Date;
		$document->title = $params->Document->Title;
		$document->document = $params->Document->Base64Document;

		$document->docType = isset($params->Document->Category) ? $params->Document->Category : 'General';
		$document->note = isset($params->Document->Notes) ? $params->Document->Notes : '';
		$document->encrypted = isset($params->Document->Encrypted) ? $params->Document->Encrypted : false;;

		require_once(ROOT . '/dataProvider/DocumentHandler.php');
		$DocumentHandler = new DocumentHandler();
		$result = $DocumentHandler->addPatientDocument($document);
		unset($DocumentHandler);

		return [
			'Success' => isset($result['data']->id)
		];
	}

	/**
	 * @param $provider
	 *
	 * @return mixed|object
	 */
	private function getProvider($provider) {
		require_once(ROOT . '/dataProvider/User.php');
		$User = new User();
		$provider = $User->getUserByNPI($provider->NPI);
		unset($User);
		return $this->provider = $provider !== false ? (object)$provider : $provider;
	}

	/**
	 * @param $patient
	 *
	 * @return mixed|object
	 */
	private function getPatient($patient) {
		require_once(ROOT . '/dataProvider/Patient.php');
		$Patient = new Patient();
		if(isset($patient->PatientAccount))
		{
			$patient = $Patient->getPatientByUsername($patient->PatientAccount);
		}
		else
		{
			$patient = $Patient->getPatientByPid($patient->Pid);
		}
		unset($Patient);
		return $this->patient = $patient !== false ? $this->convertPatient($patient, false) : $patient;
	}

	/**
	 * @return bool
	 */
	private function isPatientValid() {
		return $this->patient !== false;
	}

	/**
	 * @return bool
	 */
	private function isProviderValid() {
		return $this->provider !== false;
	}

	/**
	 * @param $error
	 */
	private function consolelog($error) {
		ob_start();
		print_r($error);
		$contents = ob_get_contents();
		ob_end_clean();
	}

	private function convertPatient($data, $inbound) {

		$mapped = new \stdClass();

		if(is_array($data)){
			$data = (object)$data;
		}

		if($inbound){
			foreach($this->demographicsMap as $service => $gaia){
				if(isset($data->{$service})){
					$mapped->{$gaia} = $data->{$service};
					if($gaia == 'DOB' || $gaia == 'drivers_license_exp' || $gaia == 'death_date'){
						$mapped->{$gaia} = str_replace(' ', 'T', $mapped->{$gaia});
					}
				}

			}
		} else {
			foreach($this->demographicsMap as $service => $gaia){
				if(isset($data->{$gaia})){
					$mapped->{$service} = $data->{$gaia};

					if($service == 'DateOfBirth' || $service == 'DriverLicenceExpirationDate' || $service == 'DeceaseDate'){
						$mapped->{$service} = str_replace(' ', 'T', $mapped->{$service});

					} elseif($service == 'Language' && $mapped->{$service} == '') {
						unset($mapped->{$service});
					}
				}
			}
		}

		return $mapped;
	}

	/**
	 * This is the Mapping variable
	 * @var array
	 */
	private $demographicsMap = [
		'Pid' => 'pid',
		'RecordNumber' => 'pubpid',
		'AccountNumber' => 'pubaccount',
		'Title' => 'title',
		'FirstName' => 'fname',
		'MiddleName' => 'mname',
		'LastName' => 'lname',
		'DateOfBirth' => 'DOB',
		'Sex' => 'sex',
		'MaritalStatus' => 'marital_status',
		'Race' => 'race',
		'Ethnicity' => 'ethnicity',
		//'Religion' => 'pid',
		'Language' => 'language',
		'DriverLicence' => 'drivers_license',
		'DriverLicenceState' => 'drivers_license_state',
		'DriverLicenceExpirationDate' => 'drivers_license_exp',
		'PhysicalAddressLineOne' => 'address',
		'PhysicalAddressLineTwo' => 'address_cont',
		'PhysicalCity' => 'city',
		'PhysicalState' => 'state',
		'PhysicalCountry' => 'country',
		'PhysicalZipCode' => 'zipcode',
		//'PostalAddressLineOne' => 'pid',
		//'PostalAddressLineTwo' => 'pid',
		//'PostalCity' => 'pid',
		//'PostalState' => 'pid',
		//'PostalZipCode' => 'pid',
		'HomePhoneNumber' => 'home_phone',
		'MobilePhoneNumber' => 'mobile_phone',
		'WorkPhoneNumber' => 'work_phone',
		'WorkPhoneExt' => 'work_phone_ext',
		'Email' => 'email',
		'ProfileImage' => 'image',
		'IsBirthMultiple' => 'birth_multiple',
		'BirthOrder' => 'birth_order',
		'Deceased' => 'deceased',
		'DeceaseDate' => 'death_date',
		'MothersFirstName' => 'mothers_name',
		//'MothersMiddleName' => 'pid',
		//'MothersLastName' => 'pid',
		'GuardiansFirstName' => 'guardians_name',
		//'GuardiansMiddleName' => 'pid',
		//'GuardiansLastName' => 'pid',
		//'GuardiansPhone' => 'pid',
		'EmergencyContactFirstName' => 'emer_contact',
		//'EmergencyContactMiddleName' => 'pid',
		//'EmergencyContactLastName' => 'pid',
		'EmergencyContactPhone' => 'emer_phone',
		'Occupation' => 'occupation',
		'Employer' => 'employer_name',
		'WebPortalUsername' => 'portal_username',
		'WebPortalPassword' => 'portal_password',
		'WebPortalAccess' => 'allow_patient_web_portal',
	];

}
