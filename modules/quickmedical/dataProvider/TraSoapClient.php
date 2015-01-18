<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 1/10/15
 * Time: 11:35 AM
 */

namespace modules\quickmedical\dataProvider;

include_once(ROOT . '/modules/quickmedical/conf.php');

class TraSoapClient {

	function SoapClient($service = 'General') {
		include_once(ROOT . '/modules/quickmedical/conf.php');
		$options = array(
			'login' => QUICK_MEDICAL_SERVER_USER,
			'password' => QUICK_MEDICAL_SERVER_PASSWORD,
			'soap_version' => SOAP_1_2
		);
		$url = QUICK_MEDICAL_WEBSERVICE_ADDRESS . $service . '.asmx?WSDL';
		$client = new \SoapClient($url, $options);
		$auth = array(
			'UserName' => QUICK_MEDICAL_WEBSERVICE_USER,
			'Password' => QUICK_MEDICAL_WEBSERVICE_PASSWORD
		);
		$header = new \SoapHeader('http://tranextgen.com/', 'AuthHeader', $auth, false);
		$client->__setSoapHeaders($header);
		return $client;
	}

}