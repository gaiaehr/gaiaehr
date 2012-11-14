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
define('_GaiaEXEC', 1);
$_SESSION['root'] = 'C:/inetpub/wwwroot/gaiaehr';
include_once($_SESSION['root'] . '/classes/Arrays.php');
/**
 * verify private key
 */
function appHasAccess($pvtKey){
	global $db;
	$db->setSQL("SELECT count(*) AS app FROM applications WHERE pvt_key = '$pvtKey' AND active = '1'");
	$record = $db->fetchRecord(PDO::FETCH_ASSOC);
	return ($record['app'] == 1 ? true : false);
}
try{
	if(isset($_REQUEST['action']) && isset($_REQUEST['method']) && isset($_REQUEST['pvtKey']) && isset($_REQUEST['site'])){
		$action = $_REQUEST['action'];
		$method = $_REQUEST['method'];
		$pvtKey = $_REQUEST['pvtKey'];
		$siteId = $_REQUEST['siteId'];
		include_once('../registry.php');
		$confFile = "../sites/$siteId/conf.php";
		if(file_exists($confFile)){
			include_once($confFile);
			include_once('../classes/dbHelper.php');
			$db = new dbHelper();
			if(appHasAccess($pvtKey)){
				$actionFile = "../dataProvider/$action.php";
				if(file_exists($actionFile)){
					include_once($actionFile);
					if(class_exists($action)){
					    $controller = new $action();
						if(function_exists($controller->$method())){
							$result = $controller->$method();
						}else{
							throw new Exception('Invalid Method');
						}
					}else{
						throw new Exception('Invalid Action');
					}
				}else{
					throw new Exception("Unable to find \"$action\" file");
				}
			}else{
				throw new Exception('Access Denied, please make sure API private key is installed correctly and active');
			}
		}else{
			throw new Exception("Unable to find \"$siteId\" configuration file");
		}
	}else{
		throw new Exception('Missing required params');
	}
} catch(Exception $e){
	$result['success'] = false;
	$result['type']    = 'exception';
	$result['message'] = $e->getMessage();
	$result['where']   = $e->getTraceAsString();
}
$callback = $_REQUEST['callback'];
if ($callback) {
    header('Content-Type: text/javascript');
	print $callback . '(' . json_encode($result) . ');';
} else {
    header('Content-Type: application/x-json');
	print json_encode($result);
}