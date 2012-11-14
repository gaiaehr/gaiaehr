<?php
/*
 GaiaEHR (Electronic Health Records)
 ACL.php
 Access Control List dataProvider
 Copyright (C) 2012 Ernesto J. Rodriguez (Certun)

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ($_SESSION['root'] . '/classes/dbHelper.php');
class Applications
{
	private $db;

	public function __construct()
	{
		$this->db = new dbHelper();
	}

	public function getApplications(){
		$this->db->setSQL("SELECT * FROM applications");
		return $this->db->fetchRecords(PDO::FETCH_ASSOC);
	}

	public function addApplication(stdClass $params){
		$params->pvt_key = $this->generatePrivateKey();
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'applications', 'I'));
		$this->db->execLog();
		$params->id = $this->db->lastInsertId;
		return $params;
	}

	public function updateApplication(stdClass $params){
		$data = get_object_vars($params);
		unset($data['id']);
		$this->db->setSQL($this->db->sqlBind($data, 'applications', 'U', array('id' => $params->id)));
		$this->db->execLog();
		return $params;
	}

	public function deleteApplication(stdClass $params){
		$this->db->setSQL("DELETE FROM applications WHERE id = '$params->id'");
		$this->db->execLog();
		return $params;
	}

	public function hasAccess($pvtKey){
		$this->db->setSQL("SELECT count(*) AS total FROM applications WHERE pvt_key = '$pvtKey' AND active = '1'");
		$app = $this->db->fetchRecord(PDO::FETCH_ASSOC);
		return ($app['total'] == 0 ? false : true);
	}

	public function generatePrivateKey()
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ023456789';
		srand((double)microtime() * 1000000);
		$i      = 0;
		$AESkey = '';
		while($i <= 19){
			$num    = rand() % 35;
			$tmp    = substr($chars, $num, 1);
			$AESkey = $AESkey . $tmp;
			if($i == 3 || $i == 7 || $i == 11 || $i == 15) $AESkey = $AESkey . '-';
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
