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

include_once(ROOT . '/classes/MatchaHelper.php');

class IpAccessRules {

	/**
	 * @var MatchaModel
	 */
	private $r;
	/**
	 * @var MatchaModel
	 */
	private $l;


	function __construct(){
		$this->r = MatchaModel::setSenchaModel('App.model.administration.IpAccessRule');
		$this->l = MatchaModel::setSenchaModel('App.model.administration.IpAccessLog');
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getIpAccessRules($params){
		return $this->r->load($params)->all();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function getIpAccessRule($params){
		return $this->r->load($params)->one();
	}

	/**
	 * @param stdClass $params
	 * @return array
	 */
	public function createIpAccessRule($params){
		return $this->r->save($params);
	}

	/**
	 * @param $params
	 * @return array
	 */
	public function updateIpAccessRule($params){
		return $this->r->save($params);
	}

	/**
	 * @param $params
	 * @return mixed
	 */
	public function deleteIpAccessRule($params){
		return $this->r->destroy($params);
	}

	/**
	 * @return bool
	 */
	public function isBlocked(){

		include_once(ROOT. '/dataProvider/GeoIpLocation.php');

		$ip = $_SERVER['REMOTE_ADDR'];

		if($ip == '::1' || $ip == '127.0.0.1'){
			return false;
		}

		$geo_data = GeoIpLocation::getGeoLocation($ip);

		$star = strpos($ip, '*');

		if($star !== false){
			$ip = substr($ip, 0, $star);
		}

		if($geo_data === false){
			$sql = 'SELECT * FROM `ip_access_rules` WHERE active = 1 AND ip = :ip1  OR ip LIKE :ip2 ORDER BY weight DESC LIMIT 1';
			$where = [];
			$where[':ip1'] = '*';
			$where[':ip2'] = $ip . '%';
		}else{
			$sql = 'SELECT * FROM `ip_access_rules` WHERE active = 1 AND ip = :ip1 OR ip LIKE :ip2 OR country_code = :country_code ORDER BY weight DESC LIMIT 1';
			$where = [];
			$where[':ip1'] = '*';
			$where[':ip2'] = $ip . '%';
			$where[':country_code'] = $geo_data['country_code'];
		}

		$conn = Matcha::getConn();
		$sth = $conn->prepare($sql);
		$sth->execute($where);
		$result = $sth->fetch(PDO::FETCH_ASSOC);

		if($result !== false){
			$blocked = $result['rule'] == 'BLK';
		}else{
			// if no rule found blocked the IP if not inside local network
			$blocked = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE) !== false;
		}

		if($blocked){
			$record = new stdClass();
			$record->ip = $ip;
			$record->country_code = $geo_data !== false ? $geo_data['country_code'] : '';
			$record->event = 'Blocked';
			$record->create_date = date('Y-m-d H:i:s');
			$this->l->save($record);
		}

		return $blocked;

	}

}
