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
class Laboratories {
	/**
	 * @var PDO
	 */
	private $conn;
	/**
	 * @var MatchaCUP
	 */
	private $LO = null;
	/**
	 * @var MatchaCUP
	 */
	private $LP = null;

	function __construct() {
		$this->conn = Matcha::getConn();
		$this->db = new MatchaHelper();
        if(!isset($this->LO))
			$this->LO = MatchaModel::setSenchaModel('App.model.administration.LabObservations');
		return;
	}

	/**
	 * Main Sencha Model Getter and Setters
	 */
	public function getLoincPanels(stdClass $params) {
		$sth = $this->conn->prepare("SELECT DISTINCT l.loinc_num as id,
                                  l.long_common_name as code_text,
                                  l.loinc_num as code,
                                  l.class,
                                  'LOINC' as code_type,
                                  e.ALIAS as code_text_short,
                                  e.HAS_CHILDREN as has_children,
                                  e.ACTIVE as active
                             FROM loinc AS l
                       LEFT JOIN loinc_extra AS e ON l.loinc_num = e.LOINC_NUM
                            WHERE (l.long_common_name LIKE '%$params->query%'
                               OR e.ALIAS LIKE '$params->query%'
                               OR l.loinc_num LIKE '$params->query%')
                              AND l.status = 'ACTIVE'
                         ORDER BY l.common_order_rank");

		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getAllLoincPanels(stdClass $params) {

		$sql = "SELECT DISTINCT l.loinc_num AS id,
	                            l.shortname AS code_text,
	                            l.loinc_num AS code,
	                            l.class,
	                            l.status,
	                            'LOINC' AS code_type,
	                            e.ALIAS AS code_text_short,
	                            e.HAS_CHILDREN AS has_children,
	                            e.ACTIVE AS active
	                       FROM loinc AS l
	                   	   JOIN loinc_extra AS e ON l.loinc_num = e.loinc_num
	                   	  WHERE l.status = 'ACTIVE'";

		$where = '';

		if(isset($params->query) && $params->query != ''){
			$params->query = trim($params->query);
			$where = " AND (l.shortname LIKE '%{$params->query}%' OR e.ALIAS LIKE '{$params->query}%' OR l.loinc_num LIKE '{$params->query}%') ";
		}

		if(isset($params->active) && $params->active){
			$where .= ' AND e.ACTIVE = \'1\' ';
		}

		$sql .= "$where ORDER BY l.common_order_rank";

		$sth = $this->conn->prepare($sql);
		$sth->execute();
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}

	public function indexLoincPanels() {
		$sth = $this->conn->prepare("SELECT loinc_num FROM loinc");
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records AS $p){

			$this->db->setSQL("SELECT COUNT(ID) AS children FROM loinc_panels WHERE PARENT_LOINC = '{$p['loinc_num']}'");
			$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
			$hasChildren = $rec['children'] > 0 ? '1' : '0';
			$this->db->setSQL("SELECT COUNT(ID) AS parent FROM loinc_panels WHERE LOINC_NUM = '{$p['loinc_num']}' AND LOINC_NUM != PARENT_LOINC");
			$rec = $this->db->fetchRecord(PDO::FETCH_ASSOC);
			$hasParent = $rec['parent'] > 0 ? '1' : '0';
			$this->db->setSQL("INSERT INTO loinc_extra (LOINC_NUM, HAS_CHILDREN, HAS_PARENT, ACTIVE) VALUES ('{$p['loinc_num']}', '$hasChildren', '$hasParent', '0')");
			$this->db->execOnly();

		}
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getLabObservations(stdClass $params) {
		return $this->getLabObservationFieldsByParentId($params->selectedId);
	}

	public function updateLabPanel(stdClass $params) {
		$sth = $this->conn->prepare("UPDATE loinc_extra
		                      SET ALIAS = '$params->code_text_short',
		                          ACTIVE = '$params->active'
		                    WHERE LOINC_NUM = '$params->code'");
		$sth->execute();
		return $params;

	}

	/**
	 * @param stdClass $params
	 * @return stdClass
	 */
	public function updateLabObservation(stdClass $params) {
		$params->active = $params->active ? '1' : '0';
		$sth = $this->conn->prepare("UPDATE loinc_extra
		                      SET ALIAS = '$params->code_text_short',
		                          DEFAULT_UNIT = '$params->default_unit',
		                          RANGE_START = '$params->range_start',
		                          RANGE_END = '$params->range_end',
		                          DESCRIPTION = '$params->description',
		                          ACTIVE = '$params->active'
		                    WHERE LOINC_NUM = '{$params->id}'");
		$sth->execute();
		return $params;
	}

	public function getActiveLaboratoryTypes() {
		$records = [];
		$sqlStatement['SELECT'] = "id, code_text_short, parent_name, loinc_name";
		$sqlStatement['WHERE'] = "id = parent_id AND active = '1'";
		$sqlStatement['ORDER'] = "parent_name ASC";
		foreach($this->LO->buildSQL($sqlStatement)->all() as $row){
			$row->label = ($row->code_text_short == '' || $row->code_text_short == null) ? $row->parent_name : $row->code_text_short;
			$row->fields = $this->getLabObservationFieldsByParentId($row->id);
			$records[] = $row;
		}
		return $records;
	}

	public function getLabObservationFieldsByParentId($panelId) {

		$sth = $this->conn->prepare("SELECT DISTINCT p.LOINC_NUM AS id,
		                                  p.PARENT_LOINC AS parent_id,
		                                  p.ID AS panel_id,
		                                  e.ALIAS AS code_text_short,
		                                  l.long_common_name AS loinc_name,
		                                  p.LOINC_NUM AS loinc_number,
		                                  p.OBSERVATION_REQUIRED_IN_PANEL AS required_in_panel,
		                                  'LOINC' AS code_type,
		                                  l.unitsrequired AS units_required,
		                                  IF(e.DEFAULT_UNIT IS NOT NULL, e.DEFAULT_UNIT, l.example_ucum_units) AS default_unit,
		                                  e.RANGE_START AS range_start,
		                                  e.RANGE_END AS range_end,
		                                  e.DESCRIPTION AS description,
		                                  (SELECT COUNT(*) FROM loinc_panels as lp WHERE lp.PARENT_LOINC = p.LOINC_NUM) AS children,
		                                  e.ACTIVE AS active
		                             FROM loinc_panels AS p
		                        LEFT JOIN loinc AS l ON p.LOINC_NUM = l.LOINC_NUM
		                        LEFT JOIN loinc_extra AS e ON e.LOINC_NUM = l.LOINC_NUM
		                            WHERE p.PARENT_LOINC != p.LOINC_NUM
		                              AND p.PARENT_LOINC = '$panelId'
		                         ORDER BY SEQUENCE");

		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach($records AS $index => $row){
			$children = [];
			if($row['children'] > 0){
				$children = $this->getLabObservationFieldsByParentId($row['id']);
			}

			if(!empty($children)){
				unset($records[$index]);
				$records = array_merge($records, $children);
			}
		}

		return $records;
	}

	public function getLabLoincLiveSearch(stdClass $params) {
		$sth = $this->conn->prepare("SELECT l.loinc_num AS id,
								  IF(e.ALIAS IS NOT NULL && e.ALIAS != '', e.ALIAS, l.component) AS loinc_name,
								  l.loinc_num AS loinc_number
							 FROM loinc_extra AS e
						LEFT JOIN loinc AS l ON e.LOINC_NUM = l.loinc_num
							WHERE (l.class != 'RAD' AND l.class != 'PANEL.CARDIAC')
							  AND (l.long_common_name LIKE '%$params->query%' OR e.ALIAS LIKE '%$params->query%' OR l.loinc_num LIKE '%$params->query%')
                              AND e.active = '1'");
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return [
			'totals' => $total,
			'rows' => $records
		];
	}

	public function getRadLoincLiveSearch(stdClass $params) {
		$sth = $this->conn->prepare("SELECT l.loinc_num AS id,
								  IF(e.ALIAS IS NOT NULL && e.ALIAS != '', e.ALIAS, l.long_common_name) AS loinc_name,
								  l.loinc_num AS loinc_number
							 FROM loinc_extra AS e
						LEFT JOIN loinc AS l ON e.LOINC_NUM = l.loinc_num
							WHERE (l.class = 'RAD' OR l.class = 'PANEL.CARDIAC')
							  AND (l.long_common_name LIKE '%$params->query%'
							  		OR e.ALIAS LIKE '%$params->query%'
							  		OR l.loinc_num LIKE '$params->query%')
						      AND e.active = '1'");
		$sth->execute();
		$records = $sth->fetchAll(PDO::FETCH_ASSOC);
		$total = count($records);
		$records = array_slice($records, $params->start, $params->limit);
		return [
			'totals' => $total,
			'rows' => $records
		];
	}

}
