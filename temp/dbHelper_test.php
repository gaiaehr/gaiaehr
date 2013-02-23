<?php

if (!isset($_SESSION))
{
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
	define('_GaiaEXEC', 1);
}

include_once('../registry.php');
include_once('../sites/default/conf.php');
include_once ($_SESSION['root'] . '/classes/MatchaHelper.php');


$db = new MatchaHelper();

//echo MatchaModel::__renderSenchaFieldSyntax(array('Type'=>'DOUBLE(10,2)'));

//MatchaAudit::defineLogModel(array(
//    array('name'=>'date', 'type'=>'date'),
//    array('name'=>'event','type'=>'string'),
//    array('name'=>'comments', 'type'=>'string'),
//    array('name'=>'user', 'type'=>'string'),
//    array('name'=>'checksum', 'type'=>'string'),
//    array('name'=>'facility', 'type'=>'string'),
//    array('name'=>'patient_id', 'type'=>'int'),
//    array('name'=>'ip', 'type'=>'string')
//));

//MatchaAudit::audit();
//echo MatchaAudit::auditSaveLog(array(
//    'date'=>'2012-02-21 24:00:00',
//    'event'=>'Awaesome!',
//    'comments'=>'Super duper awesome',
//    'user'=>'Gino Rivera',
//    'checksum'=>'AKJHSAKJH234234324',
//    'facility'=>'Gino Clinic',
//    'patient_id'=>'4',
//    'ip'=>'192.168.5.103'
//));

echo '<pre>';
print_r(MatchaModel::setSenchaModel('App.model.patient.Patient'));
echo '</pre>';

//$db->SenchaModel('App.model.administration.tmpUser');

//echo '<pre>';
//print_r($db->load(1, array('lname', 'fname', 'mname')));
//echo '</pre>';