<?php
/**
 * @brief       Brief Description
 * @details      
 * Desc: Data Provider (Data Abstraction Layer)
 * This will manage all the data requierements for Client List Report.
 *
 * @author      Gino H . Rivera Falu (GI Technologies) < hrivera@gi-technologies.com >
 * @version     Vega 0.1
 * @copyright   Gnu Public License(GPLv3)
 * 
 */
 
 if(!isset($_SESSION)) {
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
$_SESSION['site']['flops'] = 0;
include_once($_SESSION['site']['root'] . '/classes/dbHelper.php');

class ClientListReport
{
	private $db;
	private $error = false;

	//-------------------------------------------------------------------------
	// First create the dbHelper object.
	//-------------------------------------------------------------------------
	function __construct()
	{
		$this->db   = new dbHelper();
		return;
	}
	
	//-------------------------------------------------------------------------
	// Get Client List dataProvider
	// By passing visit date
	//-------------------------------------------------------------------------
	public function getClientList(stdClass $params)
	{
		$sql = "SELECT
					form_data_demographics.title,
					form_data_demographics.fname + ' ' + form_data_demographics.mname + ' ' + form_data_demographics.lname As PatientName,
					form_data_demographics.pid,
					form_data_demographics.city,
					form_data_demographics.address,
					form_data_demographics.state,
					form_data_demographics.zipcode,
					form_data_demographics.home_phone,
					form_data_demographics.work_phone,
					form_data_encounter.close_date
				FROM
					form_data_demographics 
				LEFT JOIN
  					form_data_encounter 
  				ON
  					form_data_demographics.pid = form_data_encounter.pid";
  		if(isset($params->start_date) && $params->end_date) $sql .= " WHERE close_date BETWEEN  $params->start_date AND $params->end_date";
		$this->db->setSQL($sql);
		return $this->db->fetchRecords();
	}
}

?>