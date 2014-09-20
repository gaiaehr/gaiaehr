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

class Specialties {
	/**
	 * @var MatchaCUP
	 */
	private $s;

	function __construct(){
		$this->s = MatchaModel::setSenchaModel('App.model.administration.Specialty');
	}

	public function getSpecialties($params){
		return $this->s->load($params)->all();
	}

	public function getSpecialty($params){
		return $this->s->load($params)->one();
	}

	public function addSpecialty($params){
		return $this->s->save($params);
	}

	public function updateSpecialty($params){
		return $this->s->save($params);
	}

	public function deleteSpecialty($params){
		return $this->s->destroy($params);
	}
}