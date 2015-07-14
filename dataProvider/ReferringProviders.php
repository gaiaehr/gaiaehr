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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class ReferringProviders {
	/**
	 * @var MatchaCUP
	 */
	private $r;
	/**
	 * @var MatchaCUP
	 */
	private $f;

	function __construct(){
		$this->r = MatchaModel::setSenchaModel('App.model.administration.ReferringProvider');
		$this->f = MatchaModel::setSenchaModel('App.model.administration.ReferringProviderFacility');
	}

	public function getReferringProviders($params){
		return $this->r->load($params)->all();
	}

	public function getReferringProvider($params){
		// $this->getFacilities will try to find the facilities for the record
		return $this->getFacilities($this->r->load($params)->one());
	}

	public function addReferringProvider($params){
		return $this->r->save($params);
	}

	public function updateReferringProvider($params){
		return $this->r->save($params);
	}

	public function deleteReferringProvider($params){
		return $this->r->destroy($params);
	}

	public function getReferringProviderFacilities($params){
		return $this->f->load($params)->all();
	}

	public function getReferringProviderFacility($params){
		return $this->f->load($params)->one();
	}

	public function addReferringProviderFacility($params){
		return $this->f->save($params);
	}

	public function updateReferringProviderFacility($params){
		return $this->f->save($params);
	}

	public function deleteReferringProviderFacility($params){
		return $this->f->destroy($params);
	}

	public function getReferringProviderById($id){
		return $this->getReferringProvider($id);
	}

	public function getFacilities($record){
		$foo = new stdClass();
		$foo->filter[0] = new stdClass();
		$foo->filter[0]->property = 'referring_provider_id';
		if(isset($record['data']) && $record['data'] !== false){
			$foo->filter[0]->value = $record['data']['id'];
			$record['data']['facilities'] = $this->f->load($foo)->all();
		}elseif($record !== false){
			$foo->filter[0]->value = $record['id'];
			$record['facilities'] = $this->f->load($foo)->all();
		}
		return $record;
	}
}