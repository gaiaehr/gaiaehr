<?php

/**
 *
 * SOAP SERVER v.0.1
 *
 * @file
 * Provides a simple SOAP server for demo purposes
 *
 */

include_once('SoapHandler.php');
ini_set('soap.wsdl_cache_enabled', 0);
ini_set('soap.wsdl_cache_ttl', 0);

$server = new SoapServer('http://10.23.150.10/GaiaEHR/dataProvider/SOAP/wsdl.php?wsdl');
$server->setClass('SoapHandler');
$server->handle();
