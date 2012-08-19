<?php
if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');
/**
 * @brief       Services Class.
 * @details     This class will handle all services
 *
 * @author      Ernesto J. Rodriguez (Certun) <erodriguez@certun.com>
 * @version     Vega 1.0
 * @copyright   Gnu Public License (GPLv3)
 *
 */
class Services
{
	/**
	 * @var dbHelper
	 */
	private $db;

	function __construct()
	{
		return $this->db = new dbHelper();
	}





	public function getActiveProblems(stdClass $params)
	{
		return $params;
	}

	public function addActiveProblems(stdClass $params)
	{
		return $params;
	}

	public function removeActiveProblems(stdClass $params)
	{
		return $params;
	}






}
//
//$params = new stdClass();
//$params->filter = 2;
//$params->pid = '7';
//$params->eid = '1';
//$params->start = 0;
//$params->limit = 25;
//
//$t = new Services();
//print '<pre>';
//print_r($t->getLastRevisionByCode('ICD9'));
