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

class AddressBook {
	/**
	 * @var MatchaCUP
	 */
	private $a;

	function __construct() {
        if($this->a == NULL)
            $this->a = \MatchaModel::setSenchaModel('App.model.miscellaneous.AddressBook');
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function getContacts($params) {
		return $this->a->load($params)->all();
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function getContact($params) {
		return $this->a->load($params)->one();
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function addContact($params) {
		return $this->a->save($params);
	}

	/**
	 * @param $params
	 *
	 * @return array
	 */
	public function updateContact($params) {
		return $this->a->save($params);
	}

	/**
	 * @param $params
	 *
	 * @return mixed
	 */
	public function destroyContact($params) {
		return $this->a->destroy($params);
	}
}
