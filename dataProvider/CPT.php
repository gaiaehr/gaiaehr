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
class CPT {

	/**
	 * @var bool|MatchaCUP
	 */
	private $c;

	function __construct() {
		$this->c = MatchaModel::setSenchaModel('App.model.administration.CPT');
	}

	public function getCPTs($params){
		if(isset($params->query) && $params->query != ''){
			return $this->query($params);
		}
		return $this->c->load($params)->all();
	}

	public function getCPT($params){
		return $this->c->load($params)->one();
	}

	public function addCPT($params){
		return $this->c->save($params);
	}

	public function updateCPT($params){
		return $this->c->save($params);
	}

	public function deleteCPT($params){
		return $this->c->destroy($params);
	}

	public function query($params){
		$sql = "SELECT *, 'CPT4' as code_type FROM cpt_codes WHERE isRadiology = '1'";
		if(isset($params->onlyActive) && $params->onlyActive){
			$sql .= " AND active = '1' ";
		}
		if(isset($params->isRadiology) && $params->isRadiology){
			$sql .= " AND isRadiology = '1' ";
		}
		$this->c->reset();
        $sql .= ' AND (code LIKE '. $this->c->where($params->query.'%') .
			    ' OR code_text LIKE '. $this->c->where('%'.$params->query.'%') .
			    ' OR code_text_short LIKE '. $this->c->where('%'.$params->query.'%') .
			    ' OR code_text_medium LIKE '. $this->c->where('%'.$params->query.'%') . ')';
		$records = $this->c->sql($sql)->all();
		return array(
			'total' => count($records),
		    'data' => array_slice($records, $params->start, $params->limit)
		);
	}
}