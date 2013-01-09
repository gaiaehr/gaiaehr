<?php
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: origin, x-requested-with, content-type");
//header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
//----------------------------------------------------------------------------------------------------------------------
// We sould comment this code and clean it a littler bit.
// This code was a copy nd paste by Ernesto.
//----------------------------------------------------------------------------------------------------------------------
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
define('_GaiaEXEC', 1);
class BogusAction
{
	public $action;
	public $method;
	public $data;
	public $tid;
	public $module;
}
$isForm   = false;
$isUpload = false;
if(isset($HTTP_RAW_POST_DATA)){
	header('Content-Type: text/javascript');
	$data = json_decode($HTTP_RAW_POST_DATA);
	if(isset($_REQUEST['module'])) $data->module = $_REQUEST['module'];
} else {
	if(isset($_POST['extAction'])){
		$isForm       = true;
		$isUpload     = $_POST['extUpload'] == 'true';
		$data         = new BogusAction();
		$data->action = $_POST['extAction'];
		$data->method = $_POST['extMethod'];
		$data->tid    = isset($_POST['extTID']) ? $_POST['extTID'] : null;
		$data->data = array($_POST, $_FILES);
		if(isset($_REQUEST['module'])) $data->module = $_REQUEST['module'];
	} else {
		die('Invalid request.');
	}
}

if(is_array($data)){
	$site = $data[0]->server->site;
	$pvtKey = $data[0]->server->pvtKey;
	$token = $data[0]->server->token;
}else{
	$site = $data->server->site;
    $pvtKey = (isset($data->server->pvtKey)) ? $data->server->pvtKey : '';
	$token  = (isset($data->server->token))  ? $data->server->token  : '';
}

include_once('../registry.php');
include_once('../sites/'.$site.'/conf.php');
include_once('../dataProvider/Modules.php');
include_once('../dataProvider/Applications.php');
include_once('../classes/Sessions.php');
include_once('config.php');
$modules = new Modules();
$app = new Applications();
$appAccess = $app->hasAccess($pvtKey);
$API     = array_merge($API, $modules->getEnabledModulesAPI());


if(isset($token)){
	$s = new Sessions();
	$userAccess = $s->setSessionByToken($token);
}

function doRpc($cdata)
{
	global $API;
	global $appAccess;
	try{
		if(!$appAccess){
			throw new Exception('Access Denied: Please make sure API Key is typed correctly in the settings tab');
		}
		if(!isset($API[$cdata->action])){
			throw new Exception('Call to undefined action: ' . $cdata->action);
		}
		$action = $cdata->action;
		$a      = $API[$action];
		doAroundCalls($a['before'], $cdata);
		$method = $cdata->method;
		$mdef   = $a['methods'][$method];
		if(!$mdef){
			throw new Exception("Call to undefined method: $method on action $action");
		}
		doAroundCalls($mdef['before'], $cdata);
		$r = array(
			'type' => 'rpc', 'tid' => $cdata->tid, 'action' => $action, 'method' => $method
		);
		if(isset($cdata->module)){
			require_once ("../modules/$cdata->module/dataProvider/$action.php");
		} else {
			require_once ("../dataProvider/$action.php");
		}

		$o = new $action();
		if(isset($mdef['len'])){
			$params = isset($cdata->data) && is_array($cdata->data) ? $cdata->data : array();
		} else {
			$params = array($cdata->data);
		}
		$r['result'] = call_user_func_array(array(
			$o, $method
		), $params);
		doAroundCalls($mdef['after'], $cdata, $r);
		doAroundCalls($a['after'], $cdata, $r);
	} catch(Exception $e){
		$r['success'] = false;
		$r['type']    = 'exception';
		$r['message'] = $e->getMessage();
		$r['where']   = $e->getTraceAsString();
	}
	//    $_SESSION['server']['last_tid'] = $cdata->tid;
	return $r;
}

function doAroundCalls(&$fns, &$cdata, &$returnData = null)
{
	if(!$fns){
		return;
	}
	if(is_array($fns)){
		foreach($fns as $f){
			$f($cdata, $returnData);
		}
	} else {
		$fns($cdata, $returnData);
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

if($isForm && $isUpload){
	echo '<html><body><textarea>';
	echo json_encode($response);
	echo '</textarea></body></html>';
} else {
	echo json_encode($response);
}
