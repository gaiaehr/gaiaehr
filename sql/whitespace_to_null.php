<?php
///**
// * Created by JetBrains PhpStorm.
// * User: GaiaEHR
// * Date: 3/20/12
// * Time: 8:52 AM
// * To change this template use File | Settings | File Templates.
// */
//if(!isset($_SESSION)){
//    session_name ( 'GaiaEHR' );
//    session_start();
//    session_cache_limiter('private');
//}
//include_once($_SESSION['root'].'/classes/dbHelper.php');
//
//echo '<pre>';
//$db = new dbHelper();
//
//
//function whiteToNull($table){
//	global $db;
//
//	$db->setSQL("SELECT * FROM $table");
//	$foo = $db->fetchRecords(PDO::FETCH_ASSOC);
//
//	foreach($foo as $fo){
//		foreach($fo as $key => $val){
//			if($val == '') $foo[$key] = NULL;
//		}
//		$id = $fo['id'];
//		unset($fo['id']);
//		//print $db->sqlBind($fo,$table,'U',"id = '$id'") . '<br>';
//		$db->setSQL($db->sqlBind($fo,$table,'U',"id = '$id'"));
//		$db->execOnly();
//	}
//
//	return;
//
//
//}
//
//whiteToNull('codes_icds');
////print_r(whiteToNull('preventive_care_guidelines'));
//
//print 'DONE!';