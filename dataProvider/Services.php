<?php

/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2012 Ernesto Rodriguez
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
class Services {

	/**
	 * @var PDO
	 */
	private $conn;

	/**
	 * @var MatchaCUP
	 */
	private $s;


	function __construct() {
		$this->conn = Matcha::getConn();
        if(!isset($this->s))
            $this->s = MatchaModel::setSenchaModel('App.model.patient.EncounterService');
	}

	public function getEncounterServices($params){
		return $this->s->sql("SELECT *,
                                CLO.option_name as financial_name,
                                CLO.code_type as code_type
                            FROM
                                encounter_services as ES
                            LEFT JOIN
                                combo_lists_options as CLO
                            ON
                                (ES.financial_class = CLO.option_value) AND (CLO.list_id=135)
                            WHERE
                                ES.eid=".$params->filter[0]->value)->all();
	}


    public function getEncounterServicesByEIDandPID($params){
        return $this->s->sql("SELECT *,
                                CLO.option_name as financial_name,
                                CLO.code_type as code_type
                            FROM
                                encounter_services as ES
                            LEFT JOIN
                                combo_lists_options as CLO
                            ON
                                (ES.financial_class = CLO.option_value) AND (CLO.list_id=135)
                            WHERE
                                ES.eid=".$params->filter[0]->value." AND
                                ES.pid=".$params->filter[1]->value)->all();
    }

	public function getEncounterService($params){
		return $this->s->load($params)->one();
	}

	public function addEncounterService($params){

		include_once(ROOT.'/dataProvider/HL7Messages.php');
		$HL7Messages = new HL7Messages();

		if(is_array($params)){
			$services = array();
			foreach($params as $param){
				$service = $this->s->save($param);
				$service = (object) $service;
				$HL7Messages->sendServiceORM(1,1, $service, 'NW');
				$services[] = $service;
			}

		}else{
			$service = $this->s->save($params);
			$service = (object) $service;
			$HL7Messages->sendServiceORM(1,1, $service, 'NW');
			$services = $service;

		}

		return $services;
	}

	public function updateEncounterService($params){

		include_once(ROOT.'/dataProvider/HL7Messages.php');
		$HL7Messages = new HL7Messages();

		if(is_array($params)){
			$services = array();
			foreach($params as $param){
				$service = $this->s->save($param);
				$service = (object) $service;
				$HL7Messages->sendServiceORM(1,1, $service, 'XX');
				$services[] = $service;
			}

		}else{
			$service = $this->s->save($params);
			$service = (object) $service;
			$HL7Messages->sendServiceORM(1,1, $service, 'XX');
			$services = $service;
		}

		return $services;
	}

	public function removeEncounterService($params){
		return $this->s->destroy($params);
	}

	public function getEncounterServicesByEid($eid){
		$this->s->addFilter('eid', $eid);
		return $this->s->load()->all();
	}

	/**
	 * @param stdClass $params
	 * @return array|stdClass
	 */
	public function getCptCodesList($params) {
		$params->active = (isset($params->active) && $params->active ? 1 : 0);
		$where = $params->active ? 'WHERE active = \'1\'' : '';
		$sort = (isset($params->sort[0]) ? 'ORDER BY ' . $params->sort[0]->property . ' ' . $params->sort[0]->direction : '');
		$sql = "SELECT * FROM cpt_codes {$where} $sort";
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getHCPCList(stdClass $params) {
		$params->active = (isset($params->active) ? $params->active : 1);
		$sort = (isset($params->sort[0]) ? 'ORDER BY ' . $params->sort[0]->property . ' ' . $params->sort[0]->direction : '');
		$sql = "SELECT * FROM hcpcs_codes WHERE active = '{$params->active}' $sort";
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getCptCodes(stdClass $params) {
		if($params->filter === 0){
			$record = $this->getCptRelatedByEidIcds($params->eid);
		} elseif($params->filter === 1) {
			$record = $this->getCptUsedByPid($params->pid);
		} elseif($params->filter === 2) {
			$record = $this->getCptUsedByClinic($params->pid);
		} else {
			$record = $this->getCptByEid($params->eid);
		}
		return $record;
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getCptRelatedByEidIcds($eid) {
		$sql = "SELECT DISTINCT 'CPT' AS code_type, cpt.code, cpt.code_text
                         FROM cpt_codes AS cpt
                   RIGHT JOIN cpt_icd AS ci ON ci.cpt = cpt.code
                    LEFT JOIN encounter_dx AS eci ON eci.code = ci.icd
                        WHERE eci.eid = '$eid'";
		$records = array();
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$results = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			if($row['code'] != null || $row['code'] != ''){
				$records[] = $row;
			}
		}
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @param $eid
	 * @return array
	 */
	public function getCptByEid($eid) {
		$sql = "SELECT DISTINCT ecc.*,
					   'CPT' AS code_type,
                       cpt.code,
                       cpt.code_text,
                       cpt.code_text_medium,
                       cpt.code_text_short
                  FROM encounter_services AS ecc
             LEFT JOIN cpt_codes AS cpt ON ecc.code = cpt.code
                 WHERE ecc.eid = '$eid'
              ORDER BY ecc.id ASC";
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @param $pid
	 * @return array
	 */
	public function getCptUsedByPid($pid) {
		$sql = "SELECT DISTINCT 'CPT' AS code_type,
					   cpt.code,
					   cpt.code_text,
					   cpt.code_text_medium,
					   cpt.code_text_short,
					   e.service_date
                  FROM encounter_services AS ecc
             LEFT JOIN cpt_codes AS cpt ON ecc.code = cpt.code
             LEFT JOIN encounters AS e ON ecc.eid = e.eid
                 WHERE e.pid = '$pid'
              ORDER BY e.service_date DESC";
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	/**
	 * @return array
	 */
	public function getCptUsedByClinic() {
		$sql = "SELECT DISTINCT 'CPT' AS code_type, cpt.code, cpt.code_text, cpt.code_text_medium, cpt.code_text_short
                  FROM encounter_services AS ecc
             LEFT JOIN cpt_codes AS cpt ON ecc.code = cpt.code
              ORDER BY cpt.code DESC";
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		return array(
			'totals' => count($records),
			'rows' => $records
		);
	}

	public function liveCodeSearch(stdClass $params) {
		/*
		 * define $code_table
		 */
		if($params->code_type == 'cpt'){
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
		$Str = array_values(explode(',', $params->query));
		/**
		 * get the las value and trim white spaces
		 * $queryStr = 'head skin'
		 */
		$queryStr = trim(end($Str));
		/**
		 * break the $queryStr into an array usin white spaces
		 * $queries = array(
		 *      [0] => 'head',
		 *      [1] => 'skin'
		 * )
		 */
		$queries = explode(' ', $queryStr);
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
		foreach($queries as $query){
			$sql = "SELECT *
					  FROM $code_table
                     WHERE (code_text LIKE '%$query%'
                        OR `code` LIKE '$query%')
                  ORDER BY `code` ASC";
			/**
			 * loop for each sql record as $row
			 */
			$sth = $this->conn->prepare($sql);
			$sth->execute();
			$results = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach($results as $i => $row){
				/**
				 * if the id of the IDC9 code is in $idHaystack increase its ['weight'] by 1
				 */
				if(array_key_exists($row['id'], $idHaystack)){
					$records[$i]['weight']++;
					/**
					 * else add the code ID to $idHaystack
					 * then add ['weight'] with a value of 1
					 * finally add the $row to $records
					 */
				} else {
					$idHaystack[$row['id']] = true;
					$row['weight'] = 1;
					$records[$row['id']] = $row;
				}
			}
		}

		function cmp($a, $b) {
			if($a['weight'] === $b['weight']){
				return 0;
			} else {
				return $a['weight'] < $b['weight'] ? 1 : -1;
			}
		}

		usort($records, 'cmp');
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return array(
			'totals' => $total,
			'rows' => $records
		);
	}

	public function getServiceCodeByCodeAndCodeType($code, $codeType) {
		$codeTable = $codeType == 'HCPCS' ? 'hcpcs_codes' : 'cpt_codes';
		$sql = "SELECT * FROM $codeTable WHERE `code` = '$code' LIMIT 1";
		$sth = $this->conn->prepare($sql);
		$sth->execute();
		$record = $sth->fetch(PDO::FETCH_ASSOC);
		return isset($record['code_text']) ? $record['code_text'] : '';
	}

}
