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

include_once(ROOT . '/classes/Arrays.php');

class SnomedCodes {

	private $conn;

	function __construct() {
		$this->conn = MatchaHelper::getConn();
	}

	public function liveProblemCodeSearch($params) {

		$sql = "SELECT ConceptId, FullySpecifiedName, OCCURRENCE
			     FROM sct_concepts
		   RIGHT JOIN sct_problem_list ON sct_concepts.ConceptId = sct_problem_list.SNOMED_CID
	            WHERE sct_concepts.ConceptStatus = '0'
	              AND (sct_concepts.FullySpecifiedName LIKE '%{$params->query}%'
	              OR sct_concepts.ConceptId LIKE '{$params->query}%')
	         ORDER BY sct_problem_list.OCCURRENCE DESC";

		$sth = $this->conn->query($sql);
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($results),
		    'data' => array_slice($results, $params->start, $params->limit)
		);
	}

	public function liveProcedureCodeSearch($params) {

		$sql = "SELECT ConceptId, FullySpecifiedName, Occurrence
			     FROM sct_procedure_list
	            WHERE FullySpecifiedName 	LIKE '%{$params->query}%'
	               OR ConceptId 			LIKE '{$params->query}%'
	         ORDER BY Occurrence DESC";

		$sth = $this->conn->query($sql);
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($results),
		    'data' => array_slice($results, $params->start, $params->limit)
		);
	}

	public function liveCodeSearch($params) {

		$sql = "SELECT ConceptId, FullySpecifiedName
			     FROM sct_concepts
	            WHERE sct_concepts.ConceptStatus = '0'
	              AND sct_concepts.FullySpecifiedName LIKE '%{$params->query}%'
	              OR sct_concepts.ConceptId LIKE '{$params->query}%'";

		$sth = $this->conn->query($sql);
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($results),
		    'data' => array_slice($results, $params->start, $params->limit)
		);
	}

	public function updateLiveProcedureCodeSearch($params) {
		$sql = "UPDATE sct_procedure_list
				   SET Occurrence = '{$params->Occurrence}'
			     WHERE ConceptId = '{$params->ConceptId}'";
		$this->conn->exec($sql);
		return $params;
	}

	public function updateLiveProblemCodeSearch($params) {

		$sql = "UPDATE sct_problem_list
				   SET OCCURRENCE = '{$params->Occurrence}'
			     WHERE SNOMED_CID = '{$params->ConceptId}'";
		$this->conn->exec($sql);
		return $params;
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
