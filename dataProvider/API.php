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
class API
{
	private $db;
	private $request;

	public function __construct()
	{
		global $_REQUEST;
		$this->db = new dbHelper();
		$this->request = $_REQUEST;

	}


	public function generatePrivateKey()
	{
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz023456789';
		srand((double)microtime() * 1000000);
		$i      = 0;
		$AESkey = 'PVT_';
		while($i <= 27){
			$num    = rand() % 33;
			$tmp    = substr($chars, $num, 1);
			$AESkey = $AESkey . $tmp;
			$i++;
		}
		if(strlen($AESkey) == 32){
			return $AESkey;
		} else {
			return false;
		}

	}
}

$api = new API();
print $api->generatePrivateKey();
