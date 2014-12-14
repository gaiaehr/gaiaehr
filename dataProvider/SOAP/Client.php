<?php

ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);
//print '<pre>';
try{

	//*************************************************//
	//*************************************************//
	//*************************************************//

	$client = new SoapClient("http://192.168.1.106/TraNextGenWebService/Charges.asmx?WSDL");

	$auth = array(
		'UserName'=>'SecretUser',
		'Password'=>'SecretPassword'
	);
	$header = new SoapHeader('http://tranextgen.com/','AuthHeader', $auth, false);
	$client->__setSoapHeaders($header);

	$request = new stdClass();

//	$request->SecurityInfo = new stdClass();
//	$request->SecurityInfo->UserName = 'SecretUser';
//	$request->SecurityInfo->Password = 'SecretPassword';


	$request->charge = new stdClass();
//	$request->charge->ProviderNpi = '925478512';
	$request->charge->ProviderSpecialty = 'CT';
	$request->charge->ProcedureCode = '123456';
	$request->charge->ProcedureDescription = '123456';
	$request->charge->ProcedureCodeType = 'CPT4';
//	$request->charge->Units = 1;



	$auth = (object) $auth;
//	$response = $client->($request);
	$response = $client->Add($request);


	var_dump($client->__getLastRequest());
	var_dump($response);


	//*************************************************//
	//*************************************************//
	//*************************************************//

	$client = new SoapClient("http://192.168.1.106/TraNextGenWebService/Patients.asmx?WSDL");

	$auth = array(
		'UserName'=>'SecretUser',
		'Password'=>'SecretPassword'
	);
	$header = new SoapHeader('http://tranextgen.com/','AuthHeader', $auth, false);
	$client->__setSoapHeaders($header);

	$params = new stdClass();
	$patient =  new stdClass();

	$patient->FirstName = 'Ernesto';
	$patient->LastName = 'Rodriguez Guzman';
	$patient->DateOfBirth = '1978-01-23';
	$patient->Sex = 'M';


	$params->patient = $patient;
	$response = $client->Add($params);

	var_dump($response);

	//*************************************************//
	//*************************************************//
	//*************************************************//

} catch(SoapFault $e){
	var_dump($e);
}




