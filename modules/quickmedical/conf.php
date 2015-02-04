<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 1/10/15
 * Time: 11:28 AM
 */
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 0);

ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

if(!defined('QUICK_MEDICAL_WEBSERVICE_ADDRESS')){

//	define('QUICK_MEDICAL_WEBSERVICE_ADDRESS', 'http://192.168.1.122/TraNextGenWebService/');
	define('QUICK_MEDICAL_WEBSERVICE_ADDRESS', 'http://192.168.0.14/TraNextGenWebService/');
//	define('QUICK_MEDICAL_WEBSERVICE_ADDRESS', 'http://10.16.12.105/TraNextGenWebService/');
//	define('QUICK_MEDICAL_WEBSERVICE_ADDRESS', 'http://localhost:8080/TraNextGenWebService/');
	define('QUICK_MEDICAL_WEBSERVICE_USER', 'TraNextGenServiceUser');
	define('QUICK_MEDICAL_WEBSERVICE_PASSWORD', 'TWFuIGlzIGRpc3Rpbmd1aXNoZWQsI');
//	define('QUICK_MEDICAL_SERVER_USER', 'TSM\SalusSRVC');
//	define('QUICK_MEDICAL_SERVER_PASSWORD', 'Cps1500@vr');
	define('QUICK_MEDICAL_SERVER_USER', 'ernesto');
	define('QUICK_MEDICAL_SERVER_PASSWORD', 'Airport1');

}

