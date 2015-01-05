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

ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);


class Sync {

	function SoapClient($service = 'General'){
		$options = array(
			'login' => 'ernesto',
			'password' => 'Airport1',
			'soap_version' => SOAP_1_2
		);

		$client = new \SoapClient("http://192.168.137.16/TraNextGenWebService/$service.asmx?WSDL", $options);
		$auth = array(
			'UserName'=>'SecretUser',
			'Password'=>'SecretPassword'
		);
		$header = new \SoapHeader('http://tranextgen.com/','AuthHeader', $auth, false);
		$client->__setSoapHeaders($header);

		return $client;
	}

	function Patients($params) {

		$client = $this->SoapClient('Patients');

		$request = new \stdClass();
		$request->charge = new \stdClass();


		$response = $client->GetList($request);


		return $response;
	}

	function Users($params) {

		$client = $this->SoapClient('Users');

		$request = new \stdClass();
		$request->charge = new \stdClass();


		$response = $client->GetList($request);


		return $response;
	}

	function User($params) {

		$client = $this->SoapClient('Users');

		$request = new \stdClass();
		$request->userId = $params->userId;
		$response = $client->GetOne($request);


		return $response;
	}

	function Procedures($params) {

		$client = $this->SoapClient('Billing');

		$request = new \stdClass();

		$response = $client->GetProcedureList($request);


		return $response;
	}

	function Procedure($params) {

		$client = $this->SoapClient('Billing');

		$request = new \stdClass();
		$request->procCode = $params->procCode;
		$response = $client->GetProcedureOne($request);

		return $response;
	}

	function Insurances($params) {

		$client = $this->SoapClient('Billing');

		$request = new \stdClass();
		$response = $client->GetInsuranceList($request);


		return $response;
	}

	function Insurance($params) {

		$client = $this->SoapClient('Billing');

		$request = new \stdClass();
		$request->insuranceCode = $params->insuranceCode;

		$response = $client->GetInsurance($request);

		return $response;
	}

	function Covers($params) {

		$client = $this->SoapClient('General');

		$request = new \stdClass();
		$request->coversRequest = new \stdClass();
		$request->coversRequest->InsuranceCode = $params->insuranceCode;
		$request->coversRequest->Page = $params->page;

		$response = $client->GetSpecialties($request);

		return $response;
	}



}