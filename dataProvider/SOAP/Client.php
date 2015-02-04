<?php

ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);
//print '<pre>';
try{

	//*************************************************//
	//*************************************************//
	//*************************************************//

	$client = new SoapClient("http://192.168.1.122/TraNextGenWebService/Patients.asmx?WSDL");

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


	$request->patient = new stdClass();
	$request->patient->Pid = 3;
	$request->patient->RecordNumber = 'A-000000000000021-00';
	$request->patient->FirstName = 'RAMOS';
	$request->patient->MiddleName = '';
	$request->patient->LastName = 'JESSICA RAMOS';
	$request->patient->Sex = 'F';
	$request->patient->DateOfBirth = '1989-08-01';
//	$request->charge->Units = 1;


	$auth = (object) $auth;
	$response = $client->Update($request);

	var_dump($response);


	//*************************************************//
	//*************************************************//
	//*************************************************//

//	$client = new SoapClient("http://192.168.1.106/TraNextGenWebService/Patients.asmx?WSDL");
//
//	$auth = array(
//		'UserName'=>'SecretUser',
//		'Password'=>'SecretPassword'
//	);
//	$header = new SoapHeader('http://tranextgen.com/','AuthHeader', $auth, false);
//	$client->__setSoapHeaders($header);
//
//	$params = new stdClass();
//	$patient =  new stdClass();
//
//	$patient->FirstName = 'Ernesto';
//	$patient->LastName = 'Rodriguez Guzman';
//	$patient->DateOfBirth = '1978-01-23';
//	$patient->Sex = 'M';
//
//
//	$params->patient = $patient;
//	$response = $client->Add($params);
//
//	var_dump($response);

	//*************************************************//
	//*************************************************//
	//*************************************************//

} catch(SoapFault $e){
	var_dump($e);
}




