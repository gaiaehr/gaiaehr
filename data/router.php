<?php
//----------------------------------------------------------------------------------------------------------------------
// We sould comment this code and clean it a littler bit.
// This code was a copy nd paste by Ernesto.
//----------------------------------------------------------------------------------------------------------------------
if(!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}
include_once ('../dataProvider/Modules.php');
require ('config.php');
if(isset($_SESSION['install']) && $_SESSION['install'] != true){
    $modules = new Modules();
    $API     = array_merge($API, $modules->getEnabledModulesAPI());
}
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
		// form post
		$isForm       = true;
		$isUpload     = $_POST['extUpload'] == 'true';
		$data         = new BogusAction();
		$data->action = $_POST['extAction'];
		$data->method = $_POST['extMethod'];
		$data->tid    = isset($_POST['extTID']) ? $_POST['extTID'] : null;
		// not set for upload
		$data->data = array(
			$_POST, $_FILES
		);
		if(isset($_REQUEST['module'])) $data->module = $_REQUEST['module'];

	} else {
		die('Invalid request.');
	}
}
function doRpc($cdata)
{
	global $API;
	try{
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
