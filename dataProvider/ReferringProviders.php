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

	function __construct(){
		$this->r = MatchaModel::setSenchaModel('App.model.administration.ReferringProvider');
	}

	public function getReferringProviders($params){
		return $this->r->load($params)->all();
	}

	public function getReferringProvider($params){
		return $this->r->load($params)->one();
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
}