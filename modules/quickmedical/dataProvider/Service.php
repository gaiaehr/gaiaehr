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

class Service {


	function Before_Services_addEncounterService($service) {

		$client = new \SoapClient("http://192.168.1.132/TraNextGenWebService/Charges.asmx?WSDL");

		$auth = array(
			'UserName'=>'SecretUser',
			'Password'=>'SecretPassword'
		);
		$header = new \SoapHeader('http://tranextgen.com/','AuthHeader', $auth, false);
		$client->__setSoapHeaders($header);

		$request = new \stdClass();
		$request->charge = new \stdClass();

		// dates
		$dateFrom = date('Y-m-d');
		$dateTo = date('Y-m-d');

		// get patient record number....
		$recordNumber = 'A-000000000000021-00';

		// get provider NPI
		$providerNpi = '1184707960';

		// get user codes
		$userCode = 'rcastro';
		$technicianCode = '012';
		$referrerCode = '0000011';

		// get insurance data
		$insuranceCode = '056';
		$insuranceType = '111';

		// facility and department
		$facilityCode = '23';
		$departmentCode = '100';

		// encounter info
		$request->charge->EncounterId = $service->eid;
		$request->charge->DateFrom = $dateFrom;
		$request->charge->DateTo = $dateTo;

		// procedure
		$request->charge->RecordNumber = $recordNumber;
		$request->charge->ProcedureCode = $service->code;
		$request->charge->ProcedureSpecialty = '';
		$request->charge->Units = $service->units;
		$request->charge->Minutes = '0';
		$request->charge->ProcedureType = '4'; //4000?
		$request->charge->ProcedurePlace = '22'; //4000?
		$request->charge->Status = 'T';
		$request->charge->IsPregnant = false;
		// actors
		$request->charge->ProviderNpi = $providerNpi;
		$request->charge->TechnicianUserCode = $technicianCode;
		$request->charge->EnterByUserCode = $userCode;
		// referral
		$request->charge->ReferrerCode = $referrerCode;
		$request->charge->ReferralNumber = '';
		$request->charge->PriorAuthorizationNumber = '';
		// insurance
		$request->charge->InsuranceCode = $insuranceCode;
		$request->charge->InsuranceType = $insuranceType;
		// fees
		$request->charge->DoctorFee = 0.0;
		$request->charge->PatientFee = 0.0;
		$request->charge->InsuranceFee = 0.0;
		// facility / department
		$request->charge->FacilityCode = $facilityCode;
		$request->charge->DepartmentCode = $departmentCode;
		// dental
		$request->charge->Tooth = '';
		$request->charge->Surface = '';
		// radiology
		$request->charge->AccessionNumber = '';


		// diagnoses
		$dx = new \stdClass();
		$dx->Code = '12405';
		$dx->CodeType = 'ICD10';

		$group = new \stdClass();
		$group->Sequence = 1;
		$group->Diagnoses[] = $dx;

		$dx = new \stdClass();
		$dx->Code = '12405';
		$dx->CodeType = 'ICD10';

		$pointer = new \stdClass();
		$pointer->Active = 1;
		$pointer->Diagnosis = $dx;


		$request->charge->DiagnosesGroup = $group;
		$request->charge->DiagnosesPionters[] = $pointer;


		$response = $client->Add($request);

		return $service;
	}



}