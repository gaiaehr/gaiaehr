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
class Codes
{
	private $db;

	function __construct()
	{
		$this->db = new dbHelper();
		return;
	}

	public function ICDCodeSearch(stdClass $params)
	{
		/**
		 * get last code revision
		 */
		$revision = $this->getLastRevisionByCodeType('ICD10');
		$records = array();
		/**
		 * ICD9
		 */
		$this->db->setSQL("SELECT dx_id 			AS id,
								  formatted_dx_code AS code,
								  long_desc 		AS code_text,
								  'ICD9-DX'			AS code_type
						     FROM icd9_dx_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$params->query%'
                        	   OR long_desc 		LIKE '$params->query%'
                        	   OR dx_code			LIKE '$params->query%'
                        	   OR formatted_dx_code	LIKE '$params->query%')
                         ORDER BY code ASC");
		$records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_CLASS));
		/**
		 * ICD9 Surgery
		 */
		$this->db->setSQL("SELECT sg_id 			AS id,
								  formatted_sg_code	AS code,
								  long_desc 		AS code_text,
                                  'ICD9-SG'			AS code_type
							 FROM icd9_sg_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$params->query%'
                        	   OR long_desc 		LIKE '$params->query%'
                        	   OR sg_code			LIKE '$params->query%'
                        	   OR formatted_sg_code	LIKE '$params->query%')
                         ORDER BY code ASC");
		$records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_CLASS));
		/**
		 * ICD10 DX
		 */
		$this->db->setSQL("SELECT dx_id 			AS id,
								  formatted_dx_code AS code,
								  long_desc 		AS code_text,
								  'ICD10-DX'		AS code_type
							 FROM icd10_dx_order_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$params->query%'
                        	   OR long_desc 		LIKE '$params->query%'
                        	   OR dx_code 			LIKE '$params->query%'
                        	   OR formatted_dx_code	LIKE '$params->query%')
                         ORDER BY code ASC");
		$records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_CLASS));
		/**
		 * ICD10 PCS
		 */
		$this->db->setSQL("SELECT pcs_id 			AS id,
								  pcs_code			AS code,
								  long_desc 		AS code_text,
								  'ICD10-PCS'		AS code_type
							 FROM icd10_pcs_order_code
                        	WHERE active = '1'
                        	  AND revision = '$revision'
                        	  AND (short_desc 		LIKE '%$params->query%'
                        	   OR long_desc 		LIKE '$params->query%'
                        	   OR pcs_code 			LIKE '$params->query%')
                         ORDER BY code ASC");
		$records = array_merge($records, $this->db->fetchRecords(PDO::FETCH_CLASS));

		$total   = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'rows' => $records);
	}

	public function getLastRevisionByCodeType($codeType)
	{
		$this->db->setSQL("SELECT MAX(revision_number) AS last_revision
                        	 FROM standardized_tables_track
                        	WHERE code_type = '$codeType'");
		$record = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return $record['last_revision'];
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




