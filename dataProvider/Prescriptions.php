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

class Prescriptions {
	function __construct(){
		$this->db = new MatchaHelper();
		return;
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function getSigCodesByQuery($params){
		$this->db->setSQL("SELECT option_value, option_name
						     FROM combo_lists_options
							WHERE (option_value LIKE '$params->query%'
							   OR  option_name  LIKE '$params->query%')
							  AND  list_id = '86'
						 ORDER BY option_value ASC");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}
}
//print '<pre>';
//$p = new Prescriptions();
//$params = new stdClass();
//$params->query = 't';
//print_r($p->getSigCodesByQuery($params));
