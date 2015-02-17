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

namespace modules\quickmedical\dataProvider;
include_once (ROOT . '/modules/quickmedical/dataProvider/TraSoapClient.php');

gc_enable();

class Sync extends TraSoapClient {

	/**
	 * @var \MatchaCUP
	 */
	private $specialtyModel;
	/**
	 * @var \MatchaCUP
	 */
	private $insuranceModel;
	/**
	 * @var \MatchaCUP
	 */
	private $rateModel;
	/**
	 * @var \MatchaCUP
	 */
	private $coverModel;
	/**
	 * @var \MatchaCUP
	 */
	private $coverLevelModel;
	/**
	 * @var \MatchaCUP
	 */
	private $procedureModel;
	/**
	 * @var array
	 */
	private $specialtiesMap;
	/**
	 * @var array
	 */
	private $insuranceMap;

	private $visits = array(
		'99201', '99202', '99203', '99204', '99205',
		'99211', '99212', '99213', '99214', '99215',
		'99241', '99242', '99243', '99244', '99245'
		);

	function __construct() {
		$this->patientModel = \MatchaModel::setSenchaModel('App.model.patient.Patient');
		$this->patientInsuranceModel = \MatchaModel::setSenchaModel('App.model.patient.Insurance');
		$this->specialtyModel = \MatchaModel::setSenchaModel('App.model.administration.Specialty');
		$this->insuranceModel = \MatchaModel::setSenchaModel('App.model.administration.InsuranceCompany');
		$this->insuranceModel = \MatchaModel::setSenchaModel('App.model.administration.InsuranceCompany');

		\Matcha::setAppDir(ROOT . '/modules');
		$this->rateModel = \MatchaModel::setSenchaModel('Modules.billing.model.BillingRate');
		$this->coverModel = \MatchaModel::setSenchaModel('Modules.billing.model.BillingCover');
		$this->coverLevelModel = \MatchaModel::setSenchaModel('Modules.billing.model.BillingCoverLevel');
		$this->procedureModel = \MatchaModel::setSenchaModel('Modules.billing.model.BillingProcedure');
		\Matcha::setAppDir(ROOT . '/app');
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Facilities($params) {
		$client = $this->SoapClient('General');
		$request = new \stdClass();
		$request->charge = new \stdClass();
		$response = $client->GetFacilities($request);
		$result = $response->GetFacilitiesResult;
		if($result->Success){
			$model = \MatchaModel::setSenchaModel('App.model.administration.Facility');
			$facilities = $result->Facilities->Facility;
			$facilities = is_array($facilities) ? $facilities : array($facilities);
			foreach($facilities as $facility){
				$record = $model->load(array('code' => $facility->Code))->one();

				if($record === false){
					$record = new \stdClass();
				} else {
					$record = (object)$record;
				}
				$record = $this->mapFacility($record, $facility);
				$model->save($record);
			}
		}

		return $response;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Specialties($params) {
		$client = $this->SoapClient('General');
		$request = new \stdClass();
		$request->charge = new \stdClass();
		$response = $client->GetSpecialties($request);
		$result = $response->GetSpecialtiesResult;

		if($result->Success){
			$this->specialtyModel = \MatchaModel::setSenchaModel('App.model.administration.Specialty');
			$specialties = $result->Specialties->Specialty;
			$specialties = is_array($specialties) ? $specialties : array($specialties);
			foreach($specialties as $specialty){
				$record = $this->specialtyModel->load(array('code' => $specialty->Code))->one();

				if($record === false){
					$record = new \stdClass();
				} else {
					$record = (object)$record;
				}
				$record = $this->mapSpecialty($record, $specialty);
				$this->specialtyModel->save($record);
			}
		}

		return $response;
	}

	/**
	 * @param \stdClass $record
	 * @param \stdClass $facility
	 * @return \stdClass
	 */
	private function mapFacility($record, $facility) {
		$record->code = $facility->Code;
		$record->name = $facility->Name;
		$record->address = $facility->PostalAddressLineOne;
		$record->address_cont = $facility->PostalAddressLineTwo;
		$record->city = $facility->PostalCity;
		$record->state = $facility->PostalState;
		$record->postal_code = $this->parseZipCode($facility->PostalZipCode);
		$record->country_code = 'USA';
		$record->service_location = false;
		$record->billing_location = false;
		$record->pos_code = $facility->PosCode;
		$record->clia = $facility->CliaNumber;
		$record->fda = $facility->FdaNumber;
		$record->ess = $facility->EssNumber;
		$record->active = $facility->IsActive;

		if(!isset($record->id)){
			$record->phone = '';
			$record->fax = '';
			$record->attn = '';
			$record->npi = '';
			$record->ein = '';
		}
		return $record;
	}

	/**
	 * @param \stdClass $record
	 * @param \stdClass $specialty
	 * @return \stdClass
	 */
	private function mapSpecialty($record, $specialty) {
		$record->code = $specialty->Code;
		$record->title = $specialty->Name;
		$record->ges = $specialty->Ges;
		$record->modality = $specialty->Modality;
		$record->taxonomy = $specialty->Taxonomy;
		$record->active = $specialty->IsActive;

		$now = date('Y-m-d H:i:s');
		if(!isset($record->id)){
			$record->create_uid = '0';
			$record->update_uid = '0';
			$record->create_date = $now;
			$record->update_date = $now;
		} else {
			$record->update_uid = '0';
			$record->update_date = $now;
		}
		return $record;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Users($params) {
		$client = $this->SoapClient('Users');
		$request = new \stdClass();
		$request->charge = new \stdClass();
		$response = $client->GetList($request);
		$result = $response->GetListResult;

		if($result->Success){
			$model = \MatchaModel::setSenchaModel('App.model.administration.User');
			$facilityModel = \MatchaModel::setSenchaModel('App.model.administration.Facility');
			$specialtyModel = \MatchaModel::setSenchaModel('App.model.administration.Specialty');

			$users = $result->Users->User;
			$users = is_array($users) ? $users : array($users);
			foreach($users as $user){
				$record = $model->load(array('code' => $user->Code))->one();

				if($record === false){
					$record = new \stdClass();
				} else {
					$record = (object)$record;
				}

				$record->code = $user->Code;
				$record->username = strtolower($user->Username);
				$record->password = $user->Password;
				$record->fname = $user->FirstName;
				$record->mname = $user->MiddleName;
				$record->lname = $user->LastName;

				$record->phone = $this->parsePhone($user->PhoneNumner);

				$record->street = $user->PostalAddressLineOne;
				$record->street_cont = $user->PostalAddressLineTwo;
				$record->city = $user->PostalCity;
				$record->state = $user->PostalState;
				$record->postal_code = $this->parseZipCode($user->PostalZipCode);;
				$record->country_code = 'USA';

				$record->active = $user->IsActive;

				/**
				 * Facility
				 */
				$record->facility_id = null;
				if(isset($user->Facility)){
					$fRecord = $facilityModel->load(array('code' => $user->Facility->Code))->one();
					if($fRecord === false){
						$fRecord = new \stdClass();
						$fRecord = $this->mapFacility($fRecord, $user->Facility);
						$fRecord = $facilityModel->save($fRecord);
					}
					$fRecord = (object)$fRecord;
					$fRecord = (object)(isset($fRecord->data) ? $fRecord->data : $fRecord);
					$record->facility_id = $fRecord->id;
					unset($fRecord);
				}

				/**
				 * Provider
				 */
				if(isset($user->Provider)){
					$record->npi = $user->Provider->Npi;
					$record->lic = $user->Provider->LicenceNumber;
					$record->ess = $user->Provider->EssPin;
					$record->upin = $user->Provider->UpinNumber;
					$record->fedtaxid = $user->Provider->SoccialSecurity;

					$record->specialty = array();
					/**
					 * Specialties
					 */
					if(isset($user->Provider->Specialties)){
						$specialties = $user->Provider->Specialties->Specialty;
						$specialties = is_array($specialties) ? $specialties : array($specialties);

						foreach($specialties as $specialty){
							$sRecord = $specialtyModel->load(array('code' => $specialty->Code))->one();
							if($sRecord === false){
								$foo = new \stdClass();
								$foo = $this->mapSpecialty($foo, $specialty);
								$record = $facilityModel->save($foo);
							}
							$sRecord = (object)$sRecord;
							$sRecord = (object)(isset($sRecord->data) ? $sRecord->data : $sRecord);
							$sRecord->specialty[] = $sRecord->id;
							unset($sRecord);
						}
					}
				}

				if(!isset($record->id)){
					$record->title = '';
					$record->role_id = null;
					$record->warehouse_id = null;
					$record->feddrugid = '';
					$record->calendar = 0;
					$record->notes = 'Imported From TRA';
					$record->email = '';
					$record->mobile = '';
					$record->direct_address = '';
					$record->authorized = false;
				}

				$now = date('Y-m-d H:i:s');
				if(!isset($record->id)){
					$record->create_uid = '0';
					$record->update_uid = '0';
					$record->create_date = $now;
					$record->update_date = $now;
				} else {
					$record->update_uid = '0';
					$record->update_date = $now;
				}

				$model->save($record);
				unset($record);
			}
		}

		return $response;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Patients($params) {
		$client = $this->SoapClient('Patients');
		$request = new \stdClass();
		$request->patientsRequest = new \stdClass();
		$request->patientsRequest->Page = 1;
		$response = $client->GetList($request);
		$result = $response->GetListResult;
		if($result->Success){

			$this->setSpecialtyMap();
			$this->setInsuranceMap();

			$page = $result->Page;
			$pages = $result->Pages;

			for(; $page <= $pages; $page++){
				if($page !== 1){
					// get page....
					$request->patientsRequest->Page = $page;
					$response = $client->GetList($request);
					$result = $response->GetListResult;
					if(!$result->Success) continue;
					$patients = $result->Patients->Patient;
				} else {
					$patients = $result->Patients->Patient;
				}
				$patients = is_array($patients) ? $patients : array($patients);
				foreach($patients as $patient){
					$this->patientHandler($patient);
				}
			}
		}
		return $response;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Patient($params) {
		$client = $this->SoapClient('Patients');
		$request = new \stdClass();
		$request->recordNumber = $params->recordNumber;
		$response = $client->GetOne($request);
		$result = $response->GetOneResult;
		if($result->Success){

			$this->setSpecialtyMap();
			$this->setInsuranceMap();

			$this->patientHandler($result->Patient);
		}
		return $response;
	}

	private function patientHandler($patient) {
		$now = date('Y-m-d H:i:s');

		$insurances = isset($patient->Insurances) ? $patient->Insurances->Insurance : false;

		$patient = $this->convertPatient($patient, true);
		$patient->home_phone = isset($patient->home_phone) ? $this->parsePhone($patient->home_phone) : '';
		$patient->mobile_phone = isset($patient->mobile_phone) ? $this->parsePhone($patient->mobile_phone) : '';
		$patient->work_phone = isset($patient->work_phone) ? $this->parsePhone($patient->work_phone) : '';
		$patient->emer_phone = isset($patient->emer_phone) ? $this->parsePhone($patient->emer_phone) : '';
		$patient->zipcode = isset($patient->zipcode) ? $this->parseZipCode($patient->zipcode) : '';

		$patientRecord = $this->patientModel->load(array('pubpid' => $patient->pubpid))->one();
		if($patientRecord !== false){
			$patient->pid = $patientRecord['pid'];
		}

		$patientRecord = (object) $this->patientModel->save($patient);

		if($insurances !== false){
			$insurances = is_array($insurances) ? $insurances : array($insurances);

			foreach($insurances as $insurance){
				$insuranceRecord = $this->patientInsuranceModel->load(array('code' => $insurance->Code))->one();
				if($insuranceRecord === false){
					$insuranceRecord = new \stdClass();
				} else {
					$insuranceRecord = (object) $insuranceRecord;
				}
				$insuranceRecord->code = $insurance->Code;
				$insuranceRecord->insurance_id = $this->insuranceMap[$insurance->InsuranceCode];
				$insuranceRecord->pid = $patientRecord->pid;
				$insuranceRecord->insurance_type = $insurance->InsuranceType; // P = primary S = supplemental C =complementary D = Disable
				$insuranceRecord->effective_date = '0000-00-00';
				$insuranceRecord->expiration_date = $insurance->InsuranceExpDate;
				$insuranceRecord->group_number = $insurance->InsuranceGroup;
				$insuranceRecord->policy_number = $insurance->InsurancePolicyNumber;

				$covers = explode('~', $insurance->InsuranceCover);
				$insuranceRecord->cover_radiology = isset($covers[0]) ? $covers[0] : '';
				$insuranceRecord->cover_medical = isset($covers[1]) ? $covers[1] : '';
				$insuranceRecord->cover_dental = isset($covers[2]) ? $covers[2] : '';
				$insuranceRecord->cover_inpatient = '';
				$insuranceRecord->cover_emergency = '';

				// patient stuff ???
				if(!isset($insuranceRecord->id)){
					$insuranceRecord->subscriber_title = '';
				}
				$insuranceRecord->subscriber_relationship = $insurance->SubscriberRelation;
				$insuranceRecord->subscriber_given_name = $insurance->SubscriberFirstName;
				$insuranceRecord->subscriber_middle_name = $insurance->SubscriberMiddleName;
				$insuranceRecord->subscriber_surname = $insurance->SubscriberLastName;
				$insuranceRecord->subscriber_dob = $insurance->SubscriberDateOfBirth;
				$insuranceRecord->subscriber_sex = $insurance->SubscriberSex;
				if(!isset($insuranceRecord->id)){
					$insuranceRecord->subscriber_ss = '';
				}
				$insuranceRecord->subscriber_address = isset($insurance->SubscriberPostalAddressOne) ? $insurance->SubscriberPostalAddressOne : '';
				$insuranceRecord->subscriber_address_cont = isset($insurance->SubscriberPostalAddressTwo) ? $insurance->SubscriberPostalAddressTwo : '';
				$insuranceRecord->subscriber_city = isset($insurance->SubscriberPostalCity) ? $insurance->SubscriberPostalCity : '';
				$insuranceRecord->subscriber_state = isset($insurance->SubscriberPostalState) ? $insurance->SubscriberPostalState : '';
				$insuranceRecord->subscriber_postal_code = isset($insurance->SubscriberPostalZipCode) ? $insurance->SubscriberPostalZipCode : '';
				$insuranceRecord->subscriber_country = 'USA';
				if(!isset($insuranceRecord->id)){
					$insuranceRecord->subscriber_phone = '';
				}
				$insuranceRecord->subscriber_employer = isset($insurance->SubscriberWork) ? $insurance->SubscriberWork : '';
				$insuranceRecord->display_order = $insurance->InsuranceDisplayOrder;
				$insuranceRecord->notes = isset($insurance->Notes) ? $insurance->Notes : '';
				$insuranceRecord->copay = '';

				if(!isset($insuranceRecord->id)){
					$insuranceRecord->create_uid = '0';
					$insuranceRecord->update_uid = '0';
					$insuranceRecord->create_date = $now;
					$insuranceRecord->update_date = $now;
				} else {
					$insuranceRecord->update_uid = '0';
					$insuranceRecord->update_date = $now;
				}

				$this->patientInsuranceModel->save($insuranceRecord);
			}
		}

	}

	private function convertPatient($data, $inbound) {

		$mapped = new \stdClass();

		if(is_array($data)){
			$data = (object) $data;
		}

		$map = array(
//			'Pid' => 'pid',
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
			'Occupation' => 'occupation'
		);

		if($inbound){
			foreach($map as $service => $gaia){
				if(isset($data->{$service})){
					$mapped->{$gaia} = $data->{$service};
					if($gaia == 'DOB' || $gaia == 'drivers_license_exp' || $gaia == 'death_date'){
						$mapped->{$gaia} = str_replace(' ', 'T', $mapped->{$gaia});
					}
				}

			}
		} else {
			foreach($map as $service => $gaia){
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
	 * TODO
	 * @param $params
	 * @return mixed
	 */
	function User($params) {
		$client = $this->SoapClient('Users');
		$request = new \stdClass();
		$request->userId = $params->userId;
		$response = $client->GetOne($request);

		return $response;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Procedures($params) {
		$client = $this->SoapClient('Billing');
		$request = new \stdClass();
		$response = $client->GetProcedureList($request);
		$result = $response->GetProcedureListResult;

		if($result->Success){
			$procedures = $result->Procedures->Procedure;
			\Matcha::$__app = ROOT . '/modules';
			$procedureModel = \MatchaModel::setSenchaModel('Modules.billing.model.BillingProcedure');
			\Matcha::$__app = ROOT . '/app';

			$now = date('Y-m-d H:i:s');

			$this->setSpecialtyMap();

			foreach($procedures as $procedure){
				$procedureRecord = $procedureModel->load(array(
					'proc_code' => $procedure->Code,
					'proc_esp' => $procedure->SpecialtyCode
				))->one();

				if($procedureRecord === false){
					$procedureRecord = new \stdClass();
				} else {
					$procedureRecord = (object)$procedureRecord;
				}
				$record = $this->mapProcedure($procedureRecord, $procedure);

				if(!isset($record->id)){
					$record->create_uid = '0';
					$record->update_uid = '0';
					$record->create_date = $now;
					$record->update_date = $now;
				} else {
					$record->update_uid = '0';
					$record->update_date = $now;
				}
				$procedureModel->save($record);
			}

			$conn = \Matcha::getConn();
			$sth =$conn->prepare("UPDATE `acc_billing_procedures`
					           SET `description` = TRIM(TRIM(TRAILING '%ï¿½' FROM `description`)),
								   `abbreviation` = TRIM(TRIM(TRAILING '%ï¿½' FROM `abbreviation`))
  							 WHERE `description` LIKE '%ï¿½' OR `abbreviation` LIKE '%ï¿½'");
			$sth->execute();
		}
		return $response;
	}

	/**
	 * @param $record
	 * @param $procedure
	 * @return mixed
	 */
	private function mapProcedure($record, $procedure) {

		$record->proc_code = $procedure->Code;
		$record->proc_esp = $procedure->SpecialtyCode;
		$record->specialty_id = isset($this->specialtiesMap[$procedure->SpecialtyCode]) ? $this->specialtiesMap[$procedure->SpecialtyCode] : '0';
		$record->modifier = $procedure->Mod;
		$record->description = $procedure->Description;
		$record->abbreviation = $procedure->Abreviation;
		$record->ucf_1 = $procedure->UcfOne;
		$record->ucf_2 = $procedure->UcfTwo;
		$record->ucf_3 = $procedure->UcfThree;
		$record->ucf_4 = $procedure->UcfFour;
		$record->unit_value = $procedure->UnitValue;
		$record->unit_rates_1 = $procedure->UnitRatesOne;
		$record->trans_cap = $procedure->TransactionCap;
		$record->trans_crdr = $procedure->TransactionCrdr;
		$record->trans_spa = $procedure->TransactionSpa;
		$record->narrative = $procedure->Narratve;
		$record->ref_cons = $procedure->RefCons;
		$record->dx_flag = $procedure->DxFlag;
		$record->area = $procedure->Area;
		$record->xray = $procedure->Xray;
		$record->type = $procedure->Type;
		$record->place = $procedure->Place;
		$record->surface = $procedure->Surface;
		$record->tooth = $procedure->Tooth;
		$record->dept = $procedure->Dept;
		$record->sex = 'B';
		$record->age_start = 0;
		$record->age_end = 999;
		$record->hcfa = $procedure->Hcfa;
		$record->no_boletas = $procedure->NoBoletas;
		$record->boletas = $procedure->Boletas;
		$record->date_to = $procedure->DateTo;
		$record->select_proc = $procedure->SelectProcedure;
		$record->proc_minutes = $procedure->ProcedureMinutes;
		$record->inv_units = $procedure->UnitValue;
		$record->wl_esp = $procedure->WlSpe;
		$record->level = $procedure->Level;
		$record->is_visit = in_array($procedure->Code, $this->visits);
		$record->is_active = true;

		return $record;
	}

	/**
	 * TODO
	 * @param $params
	 * @return mixed
	 */
	function Procedure($params) {
		$client = $this->SoapClient('Billing');
		$request = new \stdClass();
		$request->procCode = $params->procCode;
		$response = $client->GetProcedureOne($request);

		return $response;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Insurances($params) {
		$client = $this->SoapClient('Billing');
		$request = new \stdClass();
		$response = $client->GetInsuranceList($request);
		$result = $response->GetInsuranceListResult;

		if($result->Success){
			$insurances = $result->Insurances->Insurance;
			$this->setSpecialtyMap();
			foreach($insurances as $insurance){
				$this->InsuranceHandler($insurance);
			}
		}
		return $response;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Insurance($params) {
		$client = $this->SoapClient('Billing');
		$request = new \stdClass();
		$request->insuranceCode = $params->insuranceCode;
		$response = $client->GetInsurance($request);
		$result = $response->GetInsuranceResult;
		if($result->Success){
			$insurance = $result->Insurance;
			$this->setSpecialtyMap();
			$this->InsuranceHandler($insurance);
		}

		return $response;
	}


	function InsurancesCoversChanges(){
		$client = $this->SoapClient('Billing');
		$response = $client->GetInsuranceAndCoversChanges();
		$result = $response->GetInsuranceAndCoversChangesResult;

		$this->setSpecialtyMap();

		if($result->Success){

			$now = date('Y-m-d H:i:s');


			if(isset($result->InsuranceChanges)){


				if(isset($result->InsuranceChanges->InsurancesSave)){
					$insurances = is_array($result->InsuranceChanges->InsurancesSave->Insurance) ?
						$result->InsuranceChanges->InsurancesSave->Insurance :
						array($result->InsuranceChanges->InsurancesSave->Insurance);

					foreach($insurances as $insurance){
						$this->InsuranceHandler($insurance);
					}
				}


				if(isset($result->InsuranceChanges->InsurancesDeleted)){
					$insurances = is_array($result->InsuranceChanges->InsurancesDeleted->Insurance) ?
						$result->InsuranceChanges->InsurancesDeleted->Insurance :
						array($result->InsuranceChanges->InsurancesDeleted->Insurance);

					foreach($insurances as $insurance){

					}
				}
			}

			if(isset($result->CoversChanges)){


				if(isset($result->CoversChanges->CoversSave)){
					$covers = is_array($result->CoversChanges->CoversSave->Cover) ?
						$result->CoversChanges->CoversSave->Cover :
						array($result->CoversChanges->CoversSave->Cover);

					foreach($covers as $cover){

						$insurance_id = ''; // get insurance id;

						$this->coverHandler($cover, $insurance_id ,$now);
					}
				}


				if(isset($result->CoversChanges->CoversDeleted)){
					$covers = is_array($result->CoversChanges->CoversDeleted->Cover) ?
						$result->CoversChanges->CoversDeleted->Cover :
						array($result->CoversChanges->CoversDeleted->Cover);

					foreach($covers as $cover){

						$insurance_id = ''; // get insurance id;

						$coverRecord = $this->coverModel->load(array(
							'insurance_id' => $insurance_id,
							'cover' => $cover->Code
						))->one();

						if($coverRecord !== false){
							if(isset($this->specialtiesMap[$cover->SpecialtyCode])){
								$specialty_id = $this->specialtiesMap[$cover->SpecialtyCode];
							} else {
								$specialty_id = 0;
							}
							$coverLevelRecord = $this->coverLevelModel->load(array(
								'specialty_id' => $specialty_id,
								'insurance_id' => $insurance_id,
								'cover_id' => $coverRecord['id']
							))->one();
							$this->coverModel->destroy($coverRecord);
							$this->coverLevelModel->destroy($coverLevelRecord);

						}
					}
				}
			}
		}

		return $response;

	}


	/**
	 * @param $insurance
	 */
	private function InsuranceHandler($insurance) {
		$now = date('Y-m-d H:i:s');


		// skip SSS
		//if($insurance->Code == '068') return;

		$record = $this->insuranceModel->load(array('code' => $insurance->Code))->one();
		if($record === false){
			$record = new \stdClass();
		} else {
			$record = (object)$record;
		}

		$record = $this->mapInsurance($record, $insurance);

		if(!isset($record->id)){
			$record->create_uid = '0';
			$record->update_uid = '0';
			$record->create_date = $now;
			$record->update_date = $now;
		} else {
			$record->update_uid = '0';
			$record->update_date = $now;
		}

		$this->insuranceModel->save($record);
		$insurance_id = $record->id;

		/**
		 * Insurance Rates
		 */
		if(isset($insurance->Rates)){
			$page = $insurance->Rates->Page;
			$pages = $insurance->Rates->Pages;

			for(; $page <= $pages; $page++){

				if($page !== 1){
					error_log('Not Handled Rate Second Page ('. $page .') Found for Insurance Code ('. $insurance->Code .')');
					$rates = $insurance->Rates->Rate->Rate;
				} else {
					$rates = $insurance->Rates->Rate->Rate;
				}

				$rates = is_array($rates) ? $rates : array($rates);

				foreach($rates as $rate){

					if(isset($this->specialtiesMap[$rate->SpecialtyCode])){
						$specialty_id = $this->specialtiesMap[$rate->SpecialtyCode];
					} else {
						$specialty_id = 0;
					}

					$rateRecord = $this->rateModel->load(array(
						'specialty_id' => $specialty_id,
						'insurance_id' => $specialty_id,
						'proc_code' => $rate->ProcedureCode,
						'cover' => $rate->InsuranceType
					))->one();

					if($rateRecord === false){
						$rateRecord = new \stdClass();
					} else {
						$rateRecord = (object)$rateRecord;
					}

					$rateRecord->insurance_id = $insurance_id;
					$rateRecord->specialty_id = $specialty_id;
					$rateRecord = $this->mapRate($rateRecord, $rate);

					if(!isset($rateRecord->id)){
						$rateRecord->create_uid = '0';
						$rateRecord->update_uid = '0';
						$rateRecord->create_date = $now;
						$rateRecord->update_date = $now;
					} else {
						$rateRecord->update_uid = '0';
						$rateRecord->update_date = $now;
					}

					$this->rateModel->save($rateRecord);
					unset($rateRecord);

					gc_collect_cycles();
				}
			}

			unset($insurance->Rates, $rates);
		}


		/**
		 * Insurance Covers
		 */
		if(isset($insurance->Covers)){
			$page = $insurance->Covers->Page;
			$pages = $insurance->Covers->Pages;

			for(; $page <= $pages; $page++){

				if($page !== 1){
					// get page....
					unset($client);

					$client = $this->SoapClient('Billing');
					$request = new \stdClass();
					$request->coversRequest = new \stdClass();
					$request->coversRequest->InsuranceCode = $record->code;
					$request->coversRequest->Page = $page;
					$response = $client->GetCovers($request);

					unset($request);

					$result = $response->GetCoversResult;

					unset($response);

					if(!$result->Success) {
						continue;
					}

					$covers = $result->Cover->Cover;
				} else {
					$covers = $insurance->Covers->Cover->Cover;
				}

				$covers = is_array($covers) ? $covers : array($covers);

				foreach($covers as $cover){
					$this->coverHandler($cover, $insurance_id, $now);
				}

				unset($covers);

				gc_collect_cycles();
			}

			unset($insurance->Covers);
		}

		unset($record, $insurance);
	}

	private function coverHandler($cover, $insurance_id, $now){
		if(isset($this->specialtiesMap[$cover->SpecialtyCode])){
			$specialty_id = $this->specialtiesMap[$cover->SpecialtyCode];
		} else {
			$specialty_id = 0;
		}

		$_SESSION['RequestCoverCode'] = $cover->Code;

		$coverRecord = $this->coverModel->load(array(
			'insurance_id' => $insurance_id,
			'cover' => $cover->Code
		))->one();

		if($coverRecord === false){
			$coverRecord = new \stdClass();
		} else {
			$coverRecord = (object)$coverRecord;
		}

		$coverRecord->insurance_id = $insurance_id;
		$coverRecord = $this->mapCover($coverRecord, $cover);

		if(!isset($coverRecord->id)){
			$coverRecord->create_uid = '0';
			$coverRecord->update_uid = '0';
			$coverRecord->create_date = $now;
			$coverRecord->update_date = $now;
		} else {
			$coverRecord->update_uid = '0';
			$coverRecord->update_date = $now;
		}

		$coverRecord = (object)$this->coverModel->save($coverRecord)['data'];
		$cover_id = $coverRecord->id;

		$percents = explode('~', $cover->Percents);
		$percents_from = explode('~', $cover->PercentsFrom);

		foreach($percents as $i => $percent){
			$procedure = $this->procedureModel->load(array('level' => $i))->one();
			// skip if a procedure using this level is not found
			if($procedure === false)
				continue;

			$coverLevelRecord = $this->coverLevelModel->load(array(
				'specialty_id' => $specialty_id,
				'insurance_id' => $insurance_id,
				'cover_id' => $cover_id,
				'level' => $i,
			))->one();

			if($coverLevelRecord === false){
				$coverLevelRecord = new \stdClass();
			} else {
				$coverLevelRecord = (object)$coverLevelRecord;
			}

			$coverLevelRecord->specialty_id = $specialty_id;
			$coverLevelRecord->insurance_id = $insurance_id;
			$coverLevelRecord->cover_id = $cover_id;
			$coverLevelRecord->level = $i;

			$coverLevelRecord->percent = $percents[$i];
			$coverLevelRecord->pointer = $percents_from[$i];
			$coverLevelRecord->notes = isset($cover->Notes) ? $cover->Notes : '';

			if(!isset($coverLevelRecord->id)){
				$coverLevelRecord->create_uid = '0';
				$coverLevelRecord->update_uid = '0';
				$coverLevelRecord->create_date = $now;
				$coverLevelRecord->update_date = $now;
			} else {
				$coverLevelRecord->update_uid = '0';
				$coverLevelRecord->update_date = $now;
			}

			$this->coverLevelModel->save($coverLevelRecord);

			unset($coverLevelRecord);
		}

		unset($coverRecord);

	}

	/**
	 * @param \stdClass $record
	 * @param \stdClass $insurance
	 * @return \stdClass
	 */
	private function mapInsurance($record, $insurance) {
		$record->code = $insurance->Code;
		$record->name = $insurance->Name;
		$record->attn = isset($insurance->Contact) ? $insurance->Contact : '';
		$record->address1 = $insurance->PostalAddressLineOne;
		$record->address2 = isset($insurance->PostalAddressLineTwo) ? $insurance->PostalAddressLineTwo : '';
		$record->city = $insurance->PostalCity;
		$record->state = $insurance->PostalState;
		$record->zip_code = $this->parseZipCode($insurance->PostalZipCode);;
		$record->country = 'USA';
		$record->phone1 = $this->parsePhone($insurance->PhoneOne);
		$record->phone2 = $this->parsePhone($insurance->PhoneTwo);
		$record->dx_type = $insurance->DxType;
		$record->active = $insurance->IsActive;
		//$record->fax = $insurance->???;

		return $record;
	}

	/**
	 * @param $record
	 * @param $rate
	 * @return mixed
	 */
	private function mapRate($record, $rate) {
		$record->proc_code = $rate->ProcedureCode;
		$record->cover = $rate->InsuranceType;
		//$record->modifier = $rate->Code;
		$record->fee = $rate->Fee;
		$record->copay = $rate->Deductible;
		$record->in_months = $rate->InMonths;
		$record->dollar_percent = $rate->DollarPercent;
		$record->percent_from = $rate->PercentFrom;

		return $record;
	}

	/**
	 * @param $record
	 * @param $cover
	 * @return mixed
	 */
	private function mapCover($record, $cover) {
		$record->cover = $cover->Code;
		$record->copay_gen = $cover->CopayGen;
		$record->copay_spe = $cover->CopaySpe;
		$record->copay_sub = $cover->CopaySub;

		if(!isset($record->id))
			$record->deductible = 0.00;

		return $record;
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	function Covers($params) {
		$client = $this->SoapClient('General');
		$request = new \stdClass();
		$request->coversRequest = new \stdClass();
		$request->coversRequest->InsuranceCode = $params->insuranceCode;
		$request->coversRequest->Page = $params->page;
		$response = $client->GetSpecialties($request);

		return $response;
	}

	/**
	 * @param $zipCode
	 * @return mixed|string
	 */
	private function parseZipCode($zipCode) {
		$zipCode = trim(str_replace(array(
			'-',
			' '
		), '', $zipCode));
		if(strlen($zipCode) > 5){
			return preg_replace('/^(\d{5})(.*?)/', '$1-$2', $zipCode);
		}
		return $zipCode;
	}

	/**
	 * @param $phone
	 * @return string
	 */
	private function parsePhone($phone) {
		$phone = trim(str_replace(array(
			'(',
			')',
			'-',
			'_',
			' ',
			'/'
		), '', $phone));

		$len = strlen($phone);
		if($len == 10){
			return trim(preg_replace('/^(\d{3})(\d{3})(\d{4})$/', '$1-$2-$3', $phone));
		} elseif($len == 11) {
			return trim(preg_replace('/^(\d{1})(\d{3})(\d{3})(\d{4})$/', '$1-$2-$3-$4', $phone));
		} elseif($len == 7) {
			return trim(preg_replace('/^(\d{3})(\d{4})$/', '$1-$2', $phone));
		}

		return $phone;
	}

	/**
	 *
	 */
	private function setSpecialtyMap() {
		$this->specialtiesMap = [];
		$specialties = $this->specialtyModel->load()->all();
		foreach($specialties as $specialty){
			$mapCode = (string)$specialty['code'];
			$this->specialtiesMap[$mapCode] = $specialty['id'];
		}
		unset($specialties, $specialty);
	}

	/**
	 *
	 */
	private function setInsuranceMap() {
		$this->insuranceMap = [];
		$insurances = $this->insuranceModel->load()->all();
		foreach($insurances as $insurance){
			$mapCode = (string)$insurance['code'];
			$this->insuranceMap[$mapCode] = $insurance['id'];
			unset($insurances, $insurance);
		}
	}
}