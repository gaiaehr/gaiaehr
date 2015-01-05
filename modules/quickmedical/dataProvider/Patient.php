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

class Patient {

	private $map;

	function Before_Patient_savePatient($patient) {

		$mappedPatient = $this->mapPatient($patient, false);

		$client = new \SoapClient("http://192.168.1.132/TraNextGenWebService/Patients.asmx?WSDL");
		$auth = array(
			'UserName'=>'SecretUser',
			'Password'=>'SecretPassword'
		);
		$header = new \SoapHeader('http://tranextgen.com/','AuthHeader', $auth, false);
		$client->__setSoapHeaders($header);

		$request = new \stdClass();

		$request->patient = $mappedPatient;
		$response = $client->Update($request);

		return $patient;
	}

	/**
	 * @param \stdClass $data
	 * @param bool $inbound
	 * @return \stdClass
	 */
	private function mapPatient($data, $inbound) {

		$mapped = new \stdClass();

		if(is_array($data)){
			$data = (object)$data;
		}

		$map = array(
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
			'Occupation' => 'occupation'
		);

		if($inbound){
			foreach($map as $service => $gaia){
				if(isset($data->{$service})) {
					$mapped->{$gaia} = $data->{$service};
					if($gaia == 'DOB' || $gaia == 'drivers_license_exp' || $gaia == 'death_date'){
						$mapped->{$gaia} = str_replace(' ', 'T',$mapped->{$gaia});
					}
				}


			}
		} else {
			foreach($map as $service => $gaia){
				if(isset($data->{$gaia})){
					$mapped->{$service} = $data->{$gaia};

					if($service == 'DateOfBirth' || $service == 'DriverLicenceExpirationDate' || $service == 'DeceaseDate'){
						$mapped->{$service} = str_replace(' ', 'T',$mapped->{$service});

					}elseif($service == 'Language' && $mapped->{$service} == ''){
						unset($mapped->{$service});
					}
				}
			}
		}

		return $mapped;
	}
}