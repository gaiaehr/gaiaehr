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
		/*
         * define $code_table
         */

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
		/*
				 * define $code_table
				 */
		if($params->code_type == 'cpt') {
			$code_table = 'cpt_codes';
		} else {
			$code_table = 'hcpcs_codes';
		}
		/**
		 * brake the $params->query coming form sencha using into an array using "commas"
		 * example:
		 * $params->query = '123.24, 123.4, 142.0, head skin '
		 * $Str = array(
		 *      [0] => 123.34,
		 *      [1] => 123.4,
		 *      [2] => 142.0,
		 *      [3] => 'head skin '
		 * )
		 */
		$Str = explode(',', $params->query);
		/**
		 * get the las value and trim white spaces
		 * $queryStr = 'head skin'
		 */
		$queryStr = trim(end(array_values($Str)));
		/**
		 * break the $queryStr into an array usin white spaces
		 * $queries = array(
		 *      [0] => 'head',
		 *      [1] => 'skin'
		 * )
		 */
		$queries = explode(' ', $queryStr);
		//////////////////////////////////////////////////////////////////////////////////
		////////////   NO TOCAR  /////////   NO TOCAR  /////////   NO TOCAR  /////////////
		//////////////////////////////////////////////////////////////////////////////////
		//        $sql = "SELECT * FROM codes WHERE ";
		//        foreach($queries as $query){
		//            $sql .= "(code_text LIKE '%$query%' OR code_text_short LIKE '%$query%' OR code LIKE '$query%' OR related_code LIKE '$query%') AND ";
		//        }
		//        $sql .= "code_type = '2'";
		//
		//        //print $sql;
		//
		//        $this->db->setSQL($sql);
		//        $records = $this->db->fetchRecords(PDO::FETCH_ASSOC);
		///////////////////////////////////////////////////////////////////////////////////
		/**
		 * start empty array to store the records to return
		 */
		$records = array();
		/**
		 * start empty array to store the ids of the records already in $records
		 */
		$idHaystack = array();
		/**
		 * loop for every word in $queries
		 */
		foreach($queries as $query) {
			$this->db->setSQL("SELECT *
                                 FROM $code_table
                                WHERE (code_text      LIKE '%$query%'
                                   OR code            LIKE '$query%')
                             ORDER BY code ASC");
			/**
			 * loop for each sql record as $row
			 */
			foreach($this->db->fetchRecords(PDO::FETCH_ASSOC) as $row) {
				/**
				 * if the id of the IDC9 code is in $idHaystack increase its ['weight'] by 1
				 */
				if(array_key_exists($row['id'], $idHaystack)) {
					$records[$row['id']]['weight']++;
					/**
					 * else add the code ID to $idHaystack
					 * then add ['weight'] with a value of 1
					 * finally add the $row to $records
					 */
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
