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

// TODO: This ROUTER much be part of Matcha::Connect to handle request from the client,
// TODO: this way the Matcha::Connect is in control

session_cache_limiter('private');
session_cache_expire(1);
session_regenerate_id(false);
session_name('GaiaEHR');
session_start();
setcookie(session_name(),session_id(),time()+86400, '/', null, false, true);

define('_GaiaEXEC', 1);
$site = isset($_SESSION['user']['site']) ? $_SESSION['user']['site'] : 'default';
if(!defined('_GaiaEXEC'))
	define('_GaiaEXEC', 1);
require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
$conf = ROOT . '/sites/' . $site . '/conf.php';
if(file_exists($conf)){
	require_once(ROOT . '/sites/' . $site . '/conf.php');
	require_once(ROOT . '/classes/MatchaHelper.php');
}
require_once(ROOT . '/classes/MatchaHelper.php');
include_once(ROOT . '/dataProvider/Modules.php');
include_once(ROOT . '/dataProvider/ACL.php');
include_once(ROOT . '/dataProvider/Globals.php');
require('config.php');

/**
 * Enable the error and also set the ROOT directory for
 * the error log. But checks if the files exists and is
 * writable.
 *
 * NOTE: This should be part of Matcha::Connect
 */
error_reporting(E_ALL);
ini_set('display_errors', 'On');
if(file_exists(ROOT.'/log/error_log.txt'))
{
    if(is_writable(ROOT.'/log/error_log.txt'))
    {
        ini_set('error_log', ROOT . '/log/error_log.txt');
    }
}

if(isset($_SESSION['install']) && $_SESSION['install'] != true){
	$modules = new Modules();
	$API = array_merge($API, $modules->getEnabledModulesAPI());
}

class BogusAction {
	public $action;
	public $method;
	public $data;
	public $tid;
	public $module;
}

$isForm = false;
$isUpload = false;
$module = null;
$data = file_get_contents('php://input');

if(isset($data)){
	header('Content-Type: text/javascript');
	$data = json_decode($data);
	if(isset($_REQUEST['module'])){
		$module = $_REQUEST['module'];
	}
} else {
	if(isset($_POST['extAction'])){
		// form post
		$isForm = true;
		$isUpload = $_POST['extUpload'] == 'true';
		$data = new BogusAction();
		$data->action = $_POST['extAction'];
		$data->method = $_POST['extMethod'];
		$data->tid = isset($_POST['extTID']) ? $_POST['extTID'] : null;
		// not set for upload
		$data->data = array(
			$_POST,
			$_FILES
		);
		if(isset($_REQUEST['module']))
			$module = $_REQUEST['module'];

	} else {
		die('Invalid request.');
	}
}

function doRpc($cdata) {
	global $API, $module;
	try {
		/**
		 * Check if user is authorized/Logged in
		 */
		//		if(isset($_SESSION['user']['auth'])){
		//			if ($_SESSION['user']['auth'] != true){
		//		          throw new Exception('Authorization Required.');
		//		    }
		//		}else{
		//		      throw new Exception('Authorization Required.');
		//		}
		//        /**
		//         * Check if tdi is a valid tid (expected tid)
		//         */
		//        if($_SESSION['server']['last_tid'] != null){
		//            $expectedTid = $_SESSION['server']['last_tid'] + 1;
		//            if($cdata->tid != $expectedTid){
		//                throw new Exception('Call to unrecognize transaction ID:
		// GaiaEHR does not recognized this transaction ID.');
		//            }
		//        }
		if(!isset($cdata->action)){
			throw new Exception('Call to undefined action: ' . $cdata->action);
		}
		$action = $cdata->action;
		$a = $API[$action];

		$method = $cdata->method;

		if(
            // TODO: Create a config file for those classes and methods that not require authorization
            // TODO: Create am authorization for the SiteSetup. This has security flaws
			(isset($_SESSION['user']) && isset($_SESSION['user']['auth']) && $_SESSION['user']['auth']) ||
			($action == 'authProcedures' && $method == 'login') ||
			($action == 'CombosData' && $method == 'getActiveFacilities') ||
			($action == 'i18nRouter' && $method == 'getAvailableLanguages') ||
            ($action == 'SiteSetup' && $method == 'checkRequirements') || // Used by SiteSetup
            ($action == 'SiteSetup' && $method == 'checkDatabaseCredentials') || // Used by SiteSetup
            ($action == 'SiteSetup' && $method == 'setSiteDirBySiteId') || // Used by SiteSetup
            ($action == 'SiteSetup' && $method == 'createDatabaseStructure') || // Used by SiteSetup
            ($action == 'SiteSetup' && $method == 'createSConfigurationFile') || // Used by SiteSetup
            ($action == 'SiteSetup' && $method == 'createSiteAdmin') || // Used by SiteSetup
            ($action == 'SiteSetup' && $method == 'loadDatabaseData') || // Used by SiteSetup
            ($action == 'CombosData' && $method == 'getTimeZoneList') || // Used by SiteSetup
            ($action == 'CombosData' && $method == 'getThemes') // Used by SiteSetup
		){

			$mdef = $a['methods'][$method];
			if(!$mdef){
				throw new Exception("Call to undefined method: $method on action $action");
			}

			$r = array(
				'type' => 'rpc',
				'tid' => $cdata->tid,
				'action' => $action,
				'method' => $method
			);
			if(isset($module)){
				require_once(ROOT . "/modules/$module/dataProvider/$action.php");
				$action = "\\modules\\$module\\dataProvider\\$action";
				$o = new $action();
			} else {
				require_once(ROOT . "/dataProvider/$action.php");
				$o = new $action();
			}

			if(isset($mdef['len'])){
				$params = isset($cdata->data) && is_array($cdata->data) ? $cdata->data : array();
			} else {
				$params = array($cdata->data);
			}

			if(isset($_SESSION['hooks']) && isset($_SESSION['hooks'][$action][$method]['Before'])){
				foreach($_SESSION['hooks'][$action][$method]['Before']['hooks'] as $i => $hook){
					include_once($hook['file']);
					$Hook = new $i();
					$params = array(call_user_func_array(array($Hook, $hook['method']), $params));
					unset($Hook);
				}
			}

			$r['result'] = call_user_func_array(array($o, $method), $params);
			unset($o);

			if(isset($_SESSION['hooks']) && isset($_SESSION['hooks'][$action][$method]['After'])){
				foreach($_SESSION['hooks'][$action][$method]['After']['hooks'] as $i => $hook){
					include_once($hook['file']);
					$Hook = new $i();
					$r['result'] = call_user_func(array($Hook, $hook['method']), $r['result']);
					unset($Hook);
				}
			}
		}else{
			throw new Exception('Not Authorized');
		}

	} catch(Exception $e) {
		$r['type'] = 'exception';
		$r['message'] = $e->getMessage();
		$r['where'] = $e->getTraceAsString();
	}
	//    $_SESSION['server']['last_tid'] = $cdata->tid;
	return $r;
}

function utf8_encode_deep(&$input) {
	if (is_string($input)) {
		$input = utf8_encode($input);
	} else if (is_array($input)) {
		foreach ($input as &$value) {
			utf8_encode_deep($value);
		}
		unset($value);
	} else if (is_object($input)) {
		$vars = array_keys(get_object_vars($input));
		foreach ($vars as $var) {
			utf8_encode_deep($input->$var);
		}
	}
}

$response = null;
if(is_array($data)){
	$response = array();
	foreach($data as $d){
		$response[] = doRpc($d);
	}
} else {
	$response = doRpc($data);
}

utf8_encode_deep($response);

if($isForm && $isUpload){
	print '<html><body><textarea>';
	$json = htmlentities(json_encode($response), ENT_NOQUOTES | ENT_SUBSTITUTE , 'UTF-8');
	$json  = mb_convert_encoding($json, 'UTF-8', 'UTF-8');
	print $json;
	print '</textarea></body></html>';
} else {
	header('Content-Type: application/json; charset=utf-8');
	$json = htmlentities(json_encode($response), ENT_NOQUOTES | ENT_SUBSTITUTE , 'UTF-8');
	$json  = mb_convert_encoding($json, 'UTF-8', 'UTF-8');
	print $json;
}

Matcha::$__conn = null;
