<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class DiagnosisCodes {
	private $conn;

	function __construct() {
		$this->conn = Matcha::getConn();
	}

	public function ICDCodeSearch($params) {
		ini_set('memory_limit', '256M');
		$type = Globals::getGlobal('dx_code_type');

		$query = is_object($params) ? $params->query : $params;

		/**
		 * get last icd9 code revision
		 */
		$revision = $this->getLastRevisionByCodeType('ICD9');

		$records = array();

		$whereQuery = '';

		$queries = explode(' ', $query);


		$wheres = array();
		$wheresIndex = 0;

		foreach($queries as $q){
			$q = trim($q);
			$w0 = ':W0'. $wheresIndex;
			$wheres[$w0] = '%'.$q.'%';
			$w1 = ':W1'. $wheresIndex;
			$wheres[$w1] = $q.'%';
			$w2 = ':W2'. $wheresIndex;
			$wheres[$w2] = $q.'%';
			$w3 = ':W3'. $wheresIndex++;
			$wheres[$w3] = $q.'%';

			$whereQuery .= " AND (short_desc 	LIKE $w0
                         OR long_desc 		    LIKE $w1
                         OR dx_code			    LIKE $w2
                         OR formatted_dx_code	LIKE $w3) ";
		}

		if($type == 'ICD9' || $type == 'BOTH'){

			/**
			 * ICD9
			 */
			$sql = "SELECT dx_id AS id,
						  formatted_dx_code,
						  formatted_dx_code AS code,
						  dx_code,
						  dx_code 			AS xcode,
						  long_desc,
						  long_desc 		AS code_text,
						  short_desc,
						  'ICD9-DX'			AS code_type
				     FROM icd9_dx_code
	                WHERE active = '1'
	                  AND revision = '$revision'
	                  $whereQuery
	             ORDER BY formatted_dx_code ASC";
			$recordSet = $this->conn->prepare($sql);
			$recordSet->execute($wheres);
			$records = array_merge($records, $recordSet->fetchAll(PDO::FETCH_ASSOC));
		} elseif($type == 'ICD10' || $type == 'BOTH') {
			/**
			 *  get last icd10 code revision
			 */
			$revision = $this->getLastRevisionByCodeType('ICD10');
			/**
			 * ICD10 DX
			 */
			$sql = "SELECT dx_id AS id,
						  formatted_dx_code,
						  formatted_dx_code AS code,
						  dx_code,
						  dx_code 			AS xcode,
						  long_desc,
						  TRIM(long_desc) 		AS code_text,
						  short_desc,
						  'ICD10-CM'		AS code_type
					 FROM icd10_dx_order_code
                    WHERE active = '1'
                      AND revision = '$revision'
                     $whereQuery
                 ORDER BY formatted_dx_code ASC";
			$recordSet = $this->conn->prepare($sql);
			$recordSet->execute($wheres);
			$records = array_merge($records, $recordSet->fetchAll(PDO::FETCH_ASSOC));
		}

		if(is_object($params)){
			$total = count($records);
			if(isset($params->start) && isset($params->limit)){
				$records = array_slice($records, $params->start, $params->limit, true);
			}
			return array(
				'totals' => $total,
				'rows' => $records
			);
		} else {
			return $records;
		}

	}

	public function getICDDataByCode($code, $code_type = null) {
		$data = array();

		if($code_type == null || ($code_type == 'ICD9' || $code_type == 'ICD9-DX')){
			$revision = $this->getLastRevisionByCodeType('ICD9');
			$sql = "SELECT *, formatted_dx_code AS code, 'ICD9-DX' AS code_type
						  	 FROM icd9_dx_code
						 	WHERE (dx_code  = :c1 OR formatted_dx_code  = :c2)
						      AND revision = '$revision'";
			$recordSet = $this->conn->prepare($sql);
			$recordSet->execute(array(':c1' => $code, ':c2' => $code));
			$data[] = $recordSet->fetch(PDO::FETCH_ASSOC);
		}

		if($code_type == null || ($code_type == 'ICD10' || $code_type == 'ICD10-DX')){
			$revision = $this->getLastRevisionByCodeType('ICD10');
			$sql = "SELECT *, formatted_dx_code AS code, 'ICD10-DX' AS code_type
						  	 FROM icd10_dx_order_code
						 	WHERE (dx_code  = :c1 OR formatted_dx_code  = :c2)
						      AND revision = '$revision'";
			$recordSet = $this->conn->prepare($sql);
			$recordSet->execute(array(':c1' => $code, ':c2' => $code));
			$data[] = $recordSet->fetch(PDO::FETCH_ASSOC);

		}

		foreach($data as $foo){
			if(is_array($foo)){
				return $foo;
			}
		}
		return array();
	}

	public function getICD9CodesByICD10Code($ICD10) {
		$revision = $this->getLastRevisionByCodeType('ICD10');
		$sql = "SELECT b.formatted_dx_code AS code,
								  'ICD9-DX' AS code_type, b.*
						  	 FROM icd10_gem_dx_10_9 AS a
						LEFT JOIN icd9_dx_code AS b ON b.dx_code = a.dx_icd9_target
						 	WHERE a.dx_icd10_source = :c
						 	  AND a.revision = '$revision'";
		$recordSet = $this->conn->prepare($sql);
		$recordSet->execute(array(':c' => $ICD10));
		$records = $recordSet->fetchAll(PDO::FETCH_ASSOC);
		return $records;
	}

	public function getICD10CodesByICD9Code($ICD9) {
		$revision = $this->getLastRevisionByCodeType('ICD9');
		$sql = "SELECT b.formatted_dx_code AS code,
								  'ICD10-DX' AS code_type, b.*
						  	 FROM icd10_gem_dx_9_10 AS a
					    LEFT JOIN icd10_dx_order_code AS b ON b.dx_code = a.dx_icd10_target
						 	WHERE a.dx_icd9_source = :c
						 	  AND a.revision = '$revision'";
		$recordSet = $this->conn->prepare($sql);
		$recordSet->execute(array(':c' => $ICD9));
		$records = $recordSet->fetchAll(PDO::FETCH_ASSOC);
		return $records;
	}

	public function getLastRevisionByCodeType($codeType) {
		$sql = "SELECT MAX(revision_number) AS last_revision
                        	 FROM standardized_tables_track
                        	WHERE code_type = :c";
		$recordSet = $this->conn->prepare($sql);
		$recordSet->execute(array(':c' => $codeType));
		$record = $recordSet->fetch(PDO::FETCH_ASSOC);
		return $record['last_revision'];
	}

	public function getICDByEid($eid, $active = null) {
		$records = array();
		$sql = "SELECT *
 				  FROM encounter_dx
				 WHERE eid = :c
              ORDER BY id ASC";
		$recordSet = $this->conn->prepare($sql);
		$recordSet->execute(array(':c' => $eid));
		foreach($recordSet->fetchAll(PDO::FETCH_ASSOC) AS $foo){
			$dx = $this->getICDDataByCode($foo['code']);
			$records[] = array_merge($dx, $foo);
		}
		return $records;
	}

	public function getICDByPid($pid, $active = false) {
		$records = array();
		$sql = "SELECT *
			      FROM encounter_dx
				 WHERE pid = :c
              ORDER BY id ASC";
		$recordSet = $this->conn->prepare($sql);
		$recordSet->execute(array(':c' => $pid));
		foreach($recordSet->fetchAll(PDO::FETCH_ASSOC) AS $foo){
			$dx = $this->getICDDataByCode($foo['code']);
			$records[] = array_merge($dx, $foo);
		}
		return $records;
	}

	public function liveCodeSearch(stdClass $params) {

		include_once(ROOT . '/classes/Arrays.php');

		$records = array();
		$haystack = array();
		$queries = explode(' ', $params->query);
		foreach($queries as $query){

			foreach($this->ICDCodeSearch(trim($query)) as $row){
				if(array_key_exists($row['code'], $haystack)){
					$foo = $records[$row['code']];
					unset($records[$row['code']]);
					$foo['weight']++;
					$records[$row['code']] = $foo;
				} else {
					$row['weight'] = 1;
					$haystack[$row['code']] = 1;
					$records[$row['code']] = $row;
				}
			}
		}
		$records = array_slice($records, 0, 300, false);
		Arrays::sksort($records, 'weight', false);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit, false);
		return array(
			'totals' => $total,
			'rows' => array_values($records)
		);
	}

	public function getServiceCodeByCodeAndCodeType($code, $codeType) {
		if($code == '' || $codeType == '')
			return '';
		$codeTable = $codeType == 'ICD9-DX' ? 'icd9_dx_code' : 'icd10_dx_order_code';
		$codeColumn = $codeType == 'ICD9-DX' ? 'formatted_dx_code' : 'icd10_dx_order_code';
		$textColumn = $codeType == 'ICD9-DX' ? 'short_desc' : 'short_desc';
		$sql = "SELECT $textColumn AS code_text FROM $codeTable WHERE `$codeColumn` = :c LIMIT 1";
		$recordSet = $this->conn->prepare($sql);
		$recordSet->execute(array(':c' => $code));
		$record = $recordSet->fetch(PDO::FETCH_ASSOC);
		return isset($record['code_text']) ? $record['code_text'] : '';
	}

}

//$f = new DiagnosisCodes();
//print '<pre>';
//$params = new stdClass();
////$params->codeType = 'ICD9';
//$params->query = '205';
//$params->start = 0;
//$params->limit = 25;
//print_r($f->ICDCodeSearch($params));
