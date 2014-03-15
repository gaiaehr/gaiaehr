<?php

print '<pre>';

try{

	ini_set('soap.wsdl_cache_enabled',0);
	ini_set('soap.wsdl_cache_ttl',0);

	$client = new SoapClient("http://localhost/gaiaehr/dataProvider/SOAP/wsdl.php?wsdl");

	$auth = array(
         'Username'=> 'admin',
         'Password'=> 'pass'
	);

	$header = new SOAPHeader('org.gaiaehr.soap', 'Auth', $auth);
	$client->__setSoapHeaders($header);


	$response = $client->getDocument(array(
		'pid' => '1',
	    'document' => '12.46.3.66.764.3434.1'
	));


	print_r($response);

} catch(SoapFault $e){
	var_dump($e);
}




