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
class HL7Clients {

	/**
	 * @var MatchaCUP
	 */
	protected $c;

	function __construct() {
		$this->c = MatchaModel::setSenchaModel('App.model.administration.HL7Client');
	}

	public function getClients($params) {
		return $this->c->load($params)->all();
	}

	public function getClient($params) {
		return $this->c->load($params)->one();
	}

	public function addClient($params) {
		return $this->c->save($params);
	}

	public function updateClient($params) {
		return $this->c->save($params);
	}

	public function deleteClient($params) {
		return $this->c->save($params);
	}

}