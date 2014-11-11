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
	}

	public function getStrengthByCODE($CODE){
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DST'
		                      AND SAB    = 'RXNORM'");
		$sth->execute(array(':c' => $CODE));
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDrugRouteByCODE($CODE){
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DRT'
		                      AND SAB    = 'RXNORM'");
		$sth->execute(array(':c' => $CODE));
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDoseformByCODE($CODE){
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DDF'
		                      AND SAB    = 'RXNORM'");
		$sth->execute(array(':c' => $CODE));
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDoseformAbbreviateByCODE($CODE){
		$sth = $this->db->prepare("SELECT ATV
		                     FROM rxnsat
		                    WHERE `CODE` = :c
		                      AND ATN    = 'DDFA'
		                      AND SAB    = 'RXNORM'");
		$sth->execute(array(':c' => $CODE));
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['ATV'];
	}

	public function getDatabaseShortNameByCODE($CODE){
		$sth = $this->db->prepare("SELECT SAB
		                     FROM rxnsat
		                    WHERE `CODE` = :c
                              AND SAB    = 'RXNORM'");
		$sth->execute(array(':c' => $CODE));
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['SAB'];
	}

	public function getMedicationNameByRXCUI($RXCUI){
		$sth = $this->db->prepare("SELECT STR
		                     FROM rxnconso
		                    WHERE RXCUI = :c
		                 GROUP BY RXCUI");
		$sth->execute(array(':c' => $RXCUI));
		$rec = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $rec['STR'];
	}

	public function getRXNORMLiveSearch(stdClass $params){
		$sth = $this->db->prepare("SELECT rxnconso.*, rxnsat.ATV as NDC
                             FROM rxnconso
                       RIGHT JOIN rxnsat ON rxnconso.RXCUI = rxnsat.RXCUI
                            WHERE (rxnconso.SAB = 'RXNORM' AND (rxnconso.TTY = 'PSN' OR rxnconso.TTY = 'SY'))
                              AND rxnsat.ATN = 'NDC'
                              AND rxnconso.STR LIKE :q
                         GROUP BY rxnconso.STR
                         LIMIT 100");

		$sth->execute(array(':q' => '%'.$params->query.'%'));
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'rows' => $records);
	}

	public function getRXNORMList(stdClass $params){
		if(isset($params->query)){
			$sth = $this->db->prepare("SELECT * FROM rxnconso WHERE (SAB = 'RXNORM' AND TTY = 'BD') AND STR LIKE :q GROUP BY RXCUI LIMIT 500");
			$sth->execute(array(':q' => $params->query.'%'));
		} else{
			$sth = $this->db->prepare("SELECT * FROM rxnconso WHERE (SAB = 'RXNORM' AND TTY = 'BD') GROUP BY RXCUI LIMIT 500");
			$sth->execute();
		}
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'data' => $records);
	}

	public function getRXNORMAllergyLiveSearch(stdClass $params){
		$sth = $this->db->prepare("SELECT *
								 	 FROM rxnconso
									WHERE (TTY = 'IN' OR TTY = 'PIN') AND STR LIKE :q
							 	 GROUP BY RXCUI LIMIT 100");
		$sth->execute(array(':q' => $params->query.'%'));
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array('totals' => $total, 'rows' => $records);
	}

	public function getMedicationAttributesByRxcui($rxcui){
		$response = array();

		$sth = $this->db->prepare("SELECT `ATV`, `ATN`
 								   FROM rxnsat
								  WHERE `RXCUI` = :c
								    AND `ATN` = 'RXN_AVAILABLE_STRENGTH'
								    AND `SAB` = 'RXNORM'");
		$sth->execute(array(':c' => $rxcui));
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		if($result !== false){
			$response[$result['ATN']] = $result['ATV'];
		}

		$sth = $this->db->prepare("SELECT `rxnconso`.*
								   FROM `rxnrel`
						      LEFT JOIN `rxnconso` ON `rxnconso`.`RXCUI` = `rxnrel`.`RXCUI2`
								  WHERE `rxnrel`.`RXCUI1` = :c
								    AND `rxnrel`.`RELA` = 'dose_form_of'");

		$sth->execute(array(':c' => $rxcui));
		$result = $sth->fetch(PDO::FETCH_ASSOC);
		return $result;
	}

	public function IndexActiveIngredients(){
		$this->db->exec('TRUNCATE TABLE rxnconsoindex');
		$sth = $this->db->prepare("SELECT id, STR FROM rxnconso WHERE TTY = 'IN' AND SAB = 'RXNORM' GROUP BY RXCUI");
		$sth->execute();
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
