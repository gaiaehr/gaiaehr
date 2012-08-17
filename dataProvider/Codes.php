<?php
/**
 * Created by JetBrains PhpStorm.
 * User: erodriguez
 * Date: 4/14/12
 * Time: 12:24 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
$_SESSION['site']['flops'] = 0;
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
include_once($_SESSION['site']['root'] . '/classes/Time.php');
class ExternalDataUpdate
{
	private $db;

	function __construct()
	{
		$this->db   = new dbHelper();
		return;
	}






}
//
//$f = new Codes();
//print '<pre>';
//$params = new stdClass();
//$params->codeType = 'ICD9';
//$params->version = 30;
//$params->basename = 'cmsv30_master_descriptions.zip';
//$params->path = '/var/www/gaiaehr/contrib/icd9/cmsv30_master_descriptions.zip';
//print_r($f->updateCodes($params));




