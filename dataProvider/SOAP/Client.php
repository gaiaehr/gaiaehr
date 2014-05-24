<?php

print '<pre>';

try{

	ini_set('soap.wsdl_cache_enabled',0);
	ini_set('soap.wsdl_cache_ttl',0);

	$client = new SoapClient("http://24.55.126.192/gaiaehr/dataProvider/SOAP/wsdl.php?wsdl");

	$params =  new stdClass();

	$params->Patient = new stdClass();
	$params->Patient->Pid = '2';
	$params->Patient->RecordNumber = '123456';

	$params->Provider = new stdClass();
	$params->Provider->NPI = '1234567890';

	$params->Document = new stdClass();
	$params->Document->Title = 'Hello form SOAP';
	$params->Document->Base64Document = '123456rtyuio';
	$params->Document->Date = '2012-05-16';

	$params->SecureKey = '02FT-KNKX-ZV0D-K8ZC-6AKQ';

	$response = $client->UploadPatientDocument($params);

	print_r($response);

} catch(SoapFault $e){
	var_dump($e);
}




