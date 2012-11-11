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
$_SESSION['root'] = 'C:/inetpub/wwwroot/gaiaehr';
include_once($_SESSION['root'] . '/classes/dbHelper.php');
include_once($_SESSION['root'] . '/classes/Arrays.php');

/**
 * verify private key
 */

$action = $_REQUEST['action'];
$method = $_REQUEST['method'];

include_once("../dataProvider/$action.php");
$o = new $action();
print_r($o->$method());


$params = $_REQUEST['data'];

//print_r(call_user_func_array(array(
//	$o, $method
//), $params));
/**
 * verify if authorized
 */

//print_r($_REQUEST);



