<?php
/**
GaiaEHR (Electronic Health Records)
Copyright (C) 2013 Certun, LLC.

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

require_once ('../classes/MatchaHelper.php');
include_once ('../dataProvider/Modules.php');
include_once ('../dataProvider/ACL.php');
require ('config.php');

if (isset($_SESSION['install']) && $_SESSION['install'] != true)
{
	$modules = new Modules();
	$API = array_merge($API, $modules->getEnabledModulesAPI());
}

class BogusAction
{
	public $action;
	public $method;
	public $data;
	public $tid;
	public $module;
}

$isForm = false;
$isUpload = false;
$module = null;
if (isset($HTTP_RAW_POST_DATA))
{
	header('Content-Type: text/javascript');
	$data = json_decode($HTTP_RAW_POST_DATA);
	if (isset($_REQUEST['module'])) $module = $_REQUEST['module'];
}
else
{
	if (isset($_POST['extAction']))
	{
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
		if (isset($_REQUEST['module']))
            $module = $_REQUEST['module'];

	}
	else
	{
		die('Invalid request.');
	}
}

function doRpc($cdata)
{
	global $API, $module;
	try
	{
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
		if (!isset($API[$cdata->action]))
		{
			throw new Exception('Call to undefined action: ' . $cdata->action);
		}
		$action = $cdata->action;
		$a = $API[$action];
		doAroundCalls($a['before'], $cdata);
		$method = $cdata->method;
		$mdef = $a['methods'][$method];
		if (!$mdef)
		{
			throw new Exception("Call to undefined method: $method on action $action");
		}
		doAroundCalls($mdef['before'], $cdata);
		$r = array(
			'type' => 'rpc',
			'tid' => $cdata->tid,
			'action' => $action,
			'method' => $method
		);
		if (isset($module))
		{
			require_once ("../modules/$module/dataProvider/$action.php");
		}
		else
		{
			require_once ("../dataProvider/$action.php");
		}
		$o = new $action();
		if (isset($mdef['len']))
		{
			$params = isset($cdata->data) && is_array($cdata->data) ? $cdata->data : array();
		}
		else
		{
			$params = array($cdata->data);
		}
		$r['result'] = call_user_func_array(array(
			$o,
			$method
		), $params);
		doAroundCalls($mdef['after'], $cdata, $r);
		doAroundCalls($a['after'], $cdata, $r);
	}
	catch(Exception $e)
	{
		$r['type'] = 'exception';
		$r['message'] = $e->getMessage();
		$r['where'] = $e->getTraceAsString();
	}
	//    $_SESSION['server']['last_tid'] = $cdata->tid;
	return $r;
}

function doAroundCalls(&$fns, &$cdata, &$returnData = null)
{
	if (!$fns)
	{
		return;
	}
	if (is_array($fns))
	{
		foreach ($fns as $f)
		{
			$f($cdata, $returnData);
		}
	}
	else
	{
		$fns($cdata, $returnData);
	}
}

$response = null;
if (is_array($data))
{
	$response = array();
	foreach ($data as $d)
	{
		$response[] = doRpc($d);
	}
}
else
{
	$response = doRpc($data);
}
if ($isForm && $isUpload)
{
	echo '<html><body><textarea>';
	echo json_encode($response);
	echo '</textarea></body></html>';
}
else
{
	echo json_encode($response);
}
