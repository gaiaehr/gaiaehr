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

class Rxnorm {
	/**
	 * @var PDO
	 */
	private $db;

	/**
	 * @var Patient
	 */
	//private $patient;
	//private $medications;

	function __construct(){
		$this->db = Matcha::getConn();
		return;
	}

	public function getStrengthByCODE($CODE){
		$sth = $this->db->query("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DST'
		                      AND SAB    = 'RXNORM'");
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDrugRouteByCODE($CODE){
		$sth = $this->db->query("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DRT'
		                      AND SAB    = 'RXNORM'");
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDoseformByCODE($CODE){
		$sth = $this->db->query("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DDF'
		                      AND SAB    = 'RXNORM'");
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDoseformAbbreviateByCODE($CODE){
		$sth = $this->db->query("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
		                      AND ATN    = 'DDFA'
		                      AND SAB    = 'RXNORM'");
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDatabaseShortNameByCODE($CODE){
		$sth = $this->db->query("SELECT SAB
		                     FROM rxnsat
		                    WHERE `CODE` = '$CODE'
                              AND SAB    = 'RXNORM'");
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['SAB'];
	}

	public function getMedicationNameByRXCUI($RXCUI){
		$sth = $this->db->query("SELECT STR
		                     FROM rxnconso
		                    WHERE RXCUI = '$RXCUI'
		                 GROUP BY RXCUI");
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['STR'];
	}

	public function getRXNORMLiveSearch(stdClass $params){
		$sth = $this->db->query("SELECT rxnconso.*, rxnsat.ATV as NDC
                             FROM rxnconso
                       RIGHT JOIN rxnsat ON rxnconso.RXCUI = rxnsat.RXCUI
                            WHERE (rxnconso.SAB = 'RXNORM' AND (rxnconso.TTY = 'PSN' OR rxnconso.TTY = 'SY'))
                              AND rxnsat.ATN = 'NDC'
                              AND rxnconso.STR LIKE '%$params->query%'
                         GROUP BY rxnconso.RXCUI
                         LIMIT 100");

		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'rows' => $records);
	}

	public function getRXNORMList(stdClass $params){
		if(isset($params->query)){
			$sth = $this->db->query("SELECT * FROM rxnconso WHERE (SAB = 'RXNORM' AND TTY = 'BD') AND STR LIKE '$params->query%' GROUP BY RXCUI LIMIT 500");
		} else{
			$sth = $this->db->query("SELECT * FROM rxnconso WHERE (SAB = 'RXNORM' AND TTY = 'BD') GROUP BY RXCUI LIMIT 500");
		}
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'data' => $records);
	}

	public function getRXNORMAllergyLiveSearch(stdClass $params){
		$sth = $this->db->query("SELECT *
							 FROM rxnconso
							WHERE (TTY = 'IN' OR TTY = 'PIN') AND STR LIKE '$params->query%'
						 GROUP BY RXCUI LIMIT 100");
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'rows' => $records);
	}

	public function getMedicationAttributesByRxcui($rxcui){
		$response = array();

		$sth = $this->db->query("SELECT `ATV`, `ATN`
 								   FROM rxnsat
								  WHERE `RXCUI` = '$rxcui'
								    AND `ATN` = 'RXN_AVAILABLE_STRENGTH'
								    AND `SAB` = 'RXNORM'");
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		if($result !== false){
			$response[$result['ATN']] = $result['ATV'];
		}

		$sth = $this->db->query("SELECT `rxnconso`.*
								   FROM `rxnrel`
						      LEFT JOIN rxnconso ON `rxnconso`.`RXCUI` = `rxnrel`.`RXCUI2`
								  WHERE `rxnrel`.`RXCUI1` = '$rxcui'
								    AND `rxnrel`.`RELA` = 'dose_form_of'");
		$result = $sth->fetch(PDO::FETCH_ASSOC);


		return $result;
	}

	public function IndexActiveIngredients(){

		$this->db->exec('TRUNCATE TABLE rxnconsoindex');

		$sth = $this->db->query("SELECT id, STR FROM rxnconso WHERE TTY = 'IN' AND SAB = 'RXNORM' GROUP BY RXCUI");
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records As $record){
			$this->db->exec("INSERT INTO rxnconsoindex (`rxnid`, `STR`) VALUES ('{$record['id']}', '{$record['STR']}')");
		}

	}
}


//$e = new Rxnorm();
//$p = new stdClass();
//$p->query = 'meta';
//$p->start = 0;
//$p->limit = 25;
//echo '<pre>';
//print_r($e->getRXNORMLiveSearch($p));
