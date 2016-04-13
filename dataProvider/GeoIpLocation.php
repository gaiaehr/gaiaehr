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
class GeoIpLocation
{

    var $GeoIpLocationModel;

    function __construct(){
        if(!isset($this->GeoIpLocationModel))
            $this->GeoIpLocationModel = MatchaModel::setSenchaModel('App.model.administration.GeoIpLocation');
    }

	public static function getGeoLocation($ip){
		$conn = Matcha::getConn();
		$sql = 'SELECT * FROM `geo_ip_location` WHERE ? BETWEEN `ip_start_num` AND `ip_end_num`';
		$ip_number = self::isIpV4($ip) ? self::getIpV4Number($ip) : self::getIpV6Number($ip);
		$sth = $conn->prepare($sql);
		$sth->execute([$ip_number]);
		return $sth->fetch(PDO::FETCH_ASSOC);
	}

    /**
     * Simple method to bring all the rows of the records from the GeoIpLocation
     * @param $params
     */
    public function getAllLocations($params){
        return $this->GeoIpLocationModel->load($params)->all();
    }

	private static function getIpV4Number($ip)	{
		if ($ip == '') {
			return 0;
		} else {
			$ips = explode('.', $ip);
			$ip_number = 16777216 * intval($ips[0]) + 65536 * intval($ips[1]) + 256 * intval($ips[2]) + intval($ips[3]);
			return $ip_number;
		}
	}

	private static function getIpV6Number($ip)	{
		$int = inet_pton($ip);
		$bits = 15;

		$ipv6long = 0;

		while($bits >= 0){
			$bin = sprintf("%08b", (ord($int[$bits])));

			if($ipv6long){
				$ipv6long = $bin . $ipv6long;
			}else{
				$ipv6long = $bin;
			}
			$bits--;
		}

		return gmp_strval(gmp_init($ipv6long, 2), 10);
	}

	private static function isIpV4($ip){
		return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false;
	}
}
