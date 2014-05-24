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
class Applications {

	private $a;

	public function __construct() {
		$this->a = MatchaModel::setSenchaModel('App.model.administration.Applications');
	}

	public function getApplications($params) {
		return $this->a->load($params)->all();
	}

	public function getApplication($params) {
		return $this->a->load($params)->one();
	}

	public function addApplication($params) {
		$params->pvt_key = $this->generatePrivateKey();
		return $this->a->save($params);
	}

	public function updateApplication(stdClass $params) {
		return $this->a->save($params);
	}

	public function deleteApplication(stdClass $params) {
		return $this->a->destroy($params);
	}

	public function hasAccess($pvtKey) {
		$params = new stdClass();
		$params->filter[0] = new stdClass();
		$params->filter[0]->property = 'pvt_key';
		$params->filter[0]->value = $pvtKey;
		$params->filter[1] = new stdClass();
		$params->filter[1]->property = 'active';
		$params->filter[1]->value = 1;
		return $this->getApplication($params) !== false;
	}

	public function generatePrivateKey() {
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789';
		srand((double)microtime() * 1000000);
		$i = 0;
		$AESkey = '';
		while($i <= 19) {
			$num = rand() % 35;
			$tmp = substr($chars, $num, 1);
			$AESkey = $AESkey . $tmp;
			if($i == 3 || $i == 7 || $i == 11 || $i == 15)
				$AESkey = $AESkey . '-';
			$i++;
		}
		if(strlen($AESkey) == 24){
			return $AESkey;
		} else {
			return false;
		}

	}
}
//$api = new Applications();
//print $api->generatePrivateKey();
