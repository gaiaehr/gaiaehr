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

// dynamic class and methods loading test

//$dir = dirname(__FILE__) . '/../dataProvider';
//$exclude = array(
//	'HL7Messages.php',
//	'HL7Server.php',
//	'HL7ServerHandler.php',
//	'DocumentPDF.php'
//);
//ob_start();
//$output = array();
//if ($handle = opendir($dir)) {
//	while (false !== ($entry = readdir($handle))) {
//		if ($entry != '.' && $entry != '..') {
//			if(preg_match('/.*\.php/', $entry) && !in_array($entry, $exclude)){
//				try{
//					include_once ($dir . '/' . $entry);
//					$cls = str_replace('.php', '', $entry);
//					$class = new ReflectionClass($cls);
//					$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
//					$buffer = array();
//
//					foreach ($methods as $method) {
//						if(!preg_match('/^__/', $method->getName())){
//
//							$buffer[] =  array(
//								'name' => $method->getName(),
//								'len' => $method->getNumberOfParameters() > 0 ? 1 : 0
//							);
//						}
//					}
//					$output[$cls]['methods'] = $buffer;
//				}catch (Exception $e){
//
//				}
//			}
//		}
//	}
//	closedir($handle);
//}
//ob_end_clean();
//print '<pre>';
//print_r($output);
//exit;

session_cache_limiter('private');
session_cache_expire(1);
session_regenerate_id(false);
session_name('GaiaEHR');
session_start();
setcookie(session_name(),session_id(),time()+86400, '/', null, false, true);

define('_GaiaEXEC', 1);

if(!isset($_SESSION['install']) || (isset($_SESSION['install']) && $_SESSION['install'] != true)){
	if(!defined('_GaiaEXEC'))
		define('_GaiaEXEC', 1);
	$site  = isset($_REQUEST['site']) ? $_REQUEST['site'] : 'default';
	require_once(str_replace('\\', '/', dirname(dirname(__FILE__))) . '/registry.php');
	require_once(ROOT . '/sites/' . $site . '/conf.php');
	require_once(ROOT . '/classes/MatchaHelper.php');
	include_once(ROOT . '/dataProvider/Modules.php');
	$m = new Modules();
}

/*
 * getREMOTING_API
 */
function getREMOTING_API($API, $moduleDir = false) {
	global $site;
	$actions = [];
	foreach($API as $aname => &$a){
		$methods = [];
		foreach($a['methods'] as $mname => &$m){
			if(isset($m['len'])){
				$md = [
					'name' => $mname,
					'len' => $m['len']
				];
			} else {
				$md = [
					'name' => $mname,
					'params' => $m['params']
				];
			}
			if(isset($m['formHandler']) && $m['formHandler'])
				$md['formHandler'] = true;
			$methods[] = $md;
		}
		$actions[$aname] = $methods;
	}
	$url = ($moduleDir === false ? "data/router.php?site={$site}" : "data/router.php?site={$site}&module={$moduleDir}");
	return json_encode([
		'url' => $url,
		'type' => 'remoting',
		'actions' => $actions,
		'timeout' => 760000000
	]);
}

require('config.php');
// convert API config to Ext.Direct spec
header('Content-Type: text/javascript');
echo 'Ext.ns("App.data");';
echo 'App.data = [];';
echo 'App.data.push(' . getREMOTING_API($API) . ');';
if(isset($_SESSION['install']) && $_SESSION['install'] != true){
	foreach($m->getEnabledModules() AS $module){
		echo 'App.data.push(' . getREMOTING_API($module['actionsAPI'], $module['dir']) . ');';
	}
}

