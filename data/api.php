<?php
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['site']['root'] . '/dataProvider/Modules.php');
$m = new Modules();
function getREMOTING_API($API, $moduleDir = false){
	$actions = array();
	foreach($API as $aname=>&$a){
		$methods = array();
		foreach($a['methods'] as $mname=>&$m){
		    if (isset($m['len'])) {
			    $md = array(
				    'name'=>$mname,
				    'len'=>$m['len']
			    );
			} else {
			    $md = array(
			        'name'=>$mname,
			        'params'=>$m['params']
			    );
			}
			if(isset($m['formHandler']) && $m['formHandler']){
				$md['formHandler'] = true;
			}
			$methods[] = $md;
		}
		$actions[$aname] = $methods;
	}

	$url = ($moduleDir === false ? 'data/router.php' : "data/router.php?module=$moduleDir" );

	return json_encode(array(
		'url' => $url,
	    'type' => 'remoting',
		'actions' => $actions,
		'timeout' => 7200000
	));
}
require('config.php');
// convert API config to Ext.Direct spec
header('Content-Type: text/javascript');
echo 'Ext.ns("App.data");';
echo 'App.data = [];';
echo 'App.data.push('.getREMOTING_API($API).');';
foreach($m->getEnabledModules() AS $module){
	echo 'App.data.push('.getREMOTING_API($module['actionsAPI'], $module['dir']).');';
}