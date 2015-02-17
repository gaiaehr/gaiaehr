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
use modules\billing\dataProvider\BillingProcedures;

include_once (ROOT . '/modules/quickmedical/dataProvider/TraSoapClient.php');
include_once (ROOT . '/dataProvider/User.php');
include_once (ROOT . '/dataProvider/Patient.php');
include_once (ROOT . '/dataProvider/Encounter.php');
include_once (ROOT . '/dataProvider/Facilities.php');
include_once (ROOT . '/dataProvider/ReferringProviders.php');
include_once (ROOT . '/modules/dental/dataProvider/DentalPlans.php');

class Service extends TraSoapClient {

	function updateQuickMedicalCharge($charge){
		$client = $this->SoapClient('Charges');
		$request = new \stdClass();
		$request->charge = $this->mapChargeQuickMedical($charge);
		$response = $client->Update($request);

		return $charge;
	}

	function addQuickMedicalCharge($charge) {
		$client = $this->SoapClient('Charges');
		$request = new \stdClass();
		$request->charge = $this->mapChargeQuickMedical($charge);
		$response = $client->Add($request);
		if($response->AddResult->Success){
			$charge->external_id = $response->AddResult->Charge->EntityId;
		}
		return $charge;
	}

	/**
	 * @param  $charge
	 * @return \stdClass
	 */
	private function mapChargeQuickMedical($charge){

		$quickMedical = new \stdClass();

		$Patient = new \Patient($charge->pid);
		$patientData = (object) $Patient->getPatient();
		unset($Patient);

		$Provider = new \User();
		$providerData = (object) $Provider->getUserByUid($charge->provider_id);
		$userData = (object) $Provider->getUserByUid($charge->create_uid);
		$technicianData = $Provider->getUserByUid($charge->technician_id);
		unset($Provider);

		$Facilities = new \Facilities();
		$facilityData = (object) $Facilities->getFacility($charge->facility_id);
		$departmentData = (object) $Facilities->getDepartment($charge->department_id);
		unset($Facilities);

		$Insurance = new \Insurance();
		$InsuranceDataPatient = (object) $Insurance->getInsurance($charge->patient_insurance_id);
		$InsuranceData = (object) $Insurance->getInsuranceCompany($InsuranceDataPatient->insurance_id);
		unset($Insurance);

		$Specialties = new \Specialties();
		$SpecialtyData = (object) $Specialties->getSpecialty($charge->specialty_id);
		unset($Specialties);

		$ReferringProviders = new \ReferringProviders();
		$ReferringProviderData = (object) $ReferringProviders->getReferringProvider($charge->referring_id);
		unset($ReferringProviders);

		$DiagnosisCodes = new \DiagnosisCodes();
		$DiagnosisCodesData = $DiagnosisCodes->getICDByEid($charge->eid, $charge->dx_group);
		unset($DiagnosisCodes);

		$BillingProcedure = new BillingProcedures();
		$BillingProcedureData = (object) $BillingProcedure->getBillingProcedureByCodeAndSpecialty($charge->proc_code, $charge->specialty_id);
		unset($BillingProcedure);


		$quickMedical->EntityId = $charge->external_id;

		$quickMedical->EncounterId = $charge->eid;
		$quickMedical->RecordNumber = $patientData->pubpid;

		$quickMedical->DateFrom = $this->parseDate($charge->service_date_from, true);
		$quickMedical->DateTo = $this->parseDate($charge->service_date_to, true);

		$quickMedical->ProcedureCode = $charge->proc_code;
		$quickMedical->Units = $charge->units;
		$quickMedical->Minutes = $charge->minutes;
		$quickMedical->ProcedureType = $BillingProcedureData->type;
		$quickMedical->ProcedurePlace = $charge->place_of_service;

		if($charge->status == 'HLD'){
			$quickMedical->Status = 'A';
		}else{
			$quickMedical->Status = 'F';
		}

		$quickMedical->IsPregnant = false;
		// actors
		$quickMedical->ProviderNpi = $providerData->npi;
		$quickMedical->ReferrerCode = isset($ReferringProviderData->code) ? $ReferringProviderData->code : '';
		$quickMedical->TechnicianUserCode = isset($technicianData->code) ? $technicianData->code : '';
		$quickMedical->EnterByUserCode = $userData->code;
		// referral
		$quickMedical->ReferralNumber = '';
		// insurance
		$quickMedical->InsuranceCode = isset($InsuranceData->code) ? $InsuranceData->code : '';
		if($departmentData->code == '300'){
			$quickMedical->InsuranceType = isset($InsuranceDataPatient->cover_dental) ? $InsuranceDataPatient->cover_dental : '';
		}else{
			$quickMedical->InsuranceType = isset($InsuranceDataPatient->cover_medical) ? $InsuranceDataPatient->cover_medical : '';
		}

		$InsuranceDataPatientCode =  explode('~', $InsuranceDataPatient->code);
		$quickMedical->PatientInsuracneOrder = $InsuranceDataPatientCode[0];
		$quickMedical->PatientInsuracneType = isset($InsuranceDataPatient->insurance_type) ? $InsuranceDataPatient->insurance_type : '';

		// facility
		$quickMedical->ProcedurePlace = $charge->place_of_service;
		$quickMedical->FacilityCode = $facilityData->code;
		// specialty
		$quickMedical->ProcedureSpecialtyCode = $SpecialtyData->code;
		$quickMedical->DepartmentCode = $departmentData->code;
		// dental
		$quickMedical->Tooth = $charge->tooth;
		$quickMedical->Surface = $charge->surface . '~' . $charge->cavity_quadrant;
		// fees
		$quickMedical->DoctorFee = $charge->doctor_fee;
		$quickMedical->PatientFee = $charge->patient_fee;
		$quickMedical->InsuranceFee = $charge->insurance_fee;
		// prior authorization number
		$quickMedical->PriorAuthorizationNumber = $charge->pro_no;
		// radiology
		$quickMedical->AccessionNumber = '';

		// diagnoses
		$group = new \stdClass();
		foreach($DiagnosisCodesData as $diagnosis){
			$group->Sequence = $diagnosis['dx_group'];
			$dx = new \stdClass();
			$dx->Code = $diagnosis['code'];
			$dx->CodeType = ['code_type'];
			$group->Diagnoses[] = $dx;
		}

		//		$pointer = new \stdClass();
		//		$pointer->Active = 1;
		//		$pointer->Diagnosis = $dx;

		//		$quickMedical->DiagnosesGroup = $group;
		//		$quickMedical->DiagnosesPionters[] = array();

		return $quickMedical;
	}

	private function parseDate($date, $cleanHour = false){
		$dateTimeRegex = '/^(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})/';
		$dateRegex = '/^(\d{4}-\d{2}-\d{2})/';

		if(!$cleanHour && preg_match($dateTimeRegex, $date)){
			return preg_replace($dateTimeRegex, '$1T$2',$date);
		}elseif($cleanHour || preg_match($dateRegex, $date)){
			return preg_replace($dateTimeRegex, '$1T00:00:00',$date);
		}else{
			return $date;
		}

	}

}