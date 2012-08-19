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


	public function getServices(stdClass $params)
	{
		if($params->code_type == 'CPT4') {
			$tableX = 'cpt_codes';
		} elseif($params->code_type == 'HCPCS'){
			$tableX = 'hcpcs_codes';
		}elseif($params->code_type == 'Immunizations') {
			$tableX = 'immunizations';
		} else {
			return $this->getAllLabs($params);
		}

		$sortX = $params->sort ? $params->sort[0]->property . ' ' . $params->sort[0]->direction : 'code ASC';
		if($params->query == ''){
			$this->db->setSQL("SELECT DISTINCT * FROM $tableX WHERE code IS NOT NULL AND active = '$params->active' ORDER BY $sortX");
		}else{
			$this->db->setSQL("SELECT DISTINCT * FROM $tableX WHERE code IS NOT NULL AND active = '$params->active' AND (code_text LIKE '%$params->query%' OR code LIKE '$params->query%') ORDER BY $sortX");
		}
		$records = $this->db->fetchRecords(PDO::FETCH_CLASS);
		$total   = count($records);
		$recs = array_slice($records,$params->start,$params->limit);
		$records = array();
		foreach($recs as $rec) {
			$rec->code_type = $params->code_type;
			$records[]      = $rec;
		}
		return array('totals'=> $total,
		             'rows'  => $records);
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function addService(stdClass $params)
	{
		if($params->code_type == 'CPT4') {
			$tableX = 'cpt_codes';
		} elseif($params->code_type == 'HCPCS'){
			$tableX = 'hcpcs_codes';
		}elseif($params->code_type == 'Immunizations') {
			$tableX = 'immunizations';
		} else {
			$tableX = 'labs';
		}

		$data = get_object_vars($params);

		foreach($data as $key=>$val ){
			if($val == null || $val == '')
			unset($data[$key]);
		}
		unset($data['id']);
		$sql = $this->db->sqlBind($data, $tableX, 'I');
		$this->db->setSQL($sql);
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return array('totals'=> 1, 'rows'  => $params);
	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateService(stdClass $params)
	{
		$data = get_object_vars($params);

		foreach($data as $key=>$val ){
			if($val == null || $val == '')
			unset($data[$key]);
		}

		if($params->code_type == 'CPT4') {
			$tableX = 'cpt_codes';
		} elseif($params->code_type == 'HCPCS'){
			$tableX = 'hcpcs_codes';
		}elseif($params->code_type == 'Immunizations') {
			$tableX = 'immunizations';
		} else {
			$tableX = 'labs_panels';
			$data['code_text_short'] = $params->code_text_short;
			unset($data['code_text'],$data['code_type'],$data['code']);
		}
		unset($data['id']);
		$sql = $this->db->sqlBind($data, $tableX, 'U', "id='$params->id'");
		$this->db->setSQL($sql);
		$this->db->execLog();
		return $params;
	}

	public function liveCodeSearch(stdClass $params)
	{
		if($params->code_type == 'cpt') {
			$code_table = 'cpt_codes';
		} else {
			$code_table = 'hcpcs_codes';
		}
		$Str = explode(',', $params->query);
		$queryStr = trim(end(array_values($Str)));
		$queries = explode(' ', $queryStr);
		$records = array();
		$idHaystack = array();
		foreach($queries as $query) {
			$this->db->setSQL("SELECT *
                                 FROM $code_table
                                WHERE (code_text      LIKE '%$query%'
                                   OR code            LIKE '$query%')
                             ORDER BY code ASC");
			foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
				if(array_key_exists($row['id'], $idHaystack)) {
					$records[$row['id']]['weight']++;
				} else {
					$idHaystack[$row['id']] = true;
					$row['weight']          = 1;
					$records[$row['id']]    = $row;
				}
			}
		}
		function cmp($a, $b)
		{
			if($a['weight'] === $b['weight']) {
				return 0;
			} else {
				return $a['weight'] < $b['weight'] ? 1 : -1; // reverse order
			}
		}

		usort($records, 'cmp');
		$total   = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals'=> $total,
		             'rows'  => $records);
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

	public function getMedications(stdClass $params)
	{
		$this->db->setSQL("SELECT *
                           FROM medications
                          WHERE (PRODUCTNDC LIKE '%$params->query%'
                             OR PROPRIETARYNAME LIKE '%$params->query%'
                             OR NONPROPRIETARYNAME LIKE '$params->query%')
                       ORDER BY PRODUCTNDC ASC");
		$records = $this->db->fetchRecords(PDO::FETCH_CLASS);
		$totals  = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals'=> $totals, 'rows'  => $records);

	}

	public function updateMedications(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$sql = $this->db->sqlBind($data, "medications", "U", "id='$params->id'");
		$this->db->setSQL($sql);
		$this->db->execLog();
		return array('totals'=> 1, 'rows'  => $params);

	}

	public function addMedications(stdClass $params)
	{
		$data = get_object_vars($params);
		unset($data['id']);
		$sql = $this->db->sqlBind($data, "medications", "I");
		$this->db->setSQL($sql);
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return array('totals'=> 1, 'rows'  => $params);
	}

	public function removeMedications(stdClass $params)
	{
		$this->db->setSQL("DELETE FROM medications WHERE id ='$params->id'");
		$this->db->execLog();
		return array('totals'=> 1, 'rows'  => $params);
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
