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
include_once (ROOT . '/classes/MatchaHelper.php');

$db = new MatchaHelper();

//echo MatchaUtils::Carbon()->now();

//$firephp = FirePHP::getInstance(true);
//$var = array('i'=>10, 'j'=>20);
//$firephp->log($var, 'Iterators');

// Enable the audit feature in Matcha::connect
MatchaAudit::audit(array(
    array('name' => 'eid', 'type' => 'int'),
    array('name' => 'date','type' => 'date'),
    array('name' => 'user', 'type' => 'string'),
    array('name' => 'user_id', 'type' => 'int'),
    array('name' => 'event', 'type' => 'string'),
    array('name' => 'patient_id', 'type' => 'int'),
    array('name' => 'facility_id', 'type' => 'int')
), NULL, 'id', 'encounter_history');

MatchaAudit::$eventLogData = array(
    'date' => Time::getLocalTime('Y-m-d H:i:s'),
    'eid' => 1,
    'user' => 'Mr. Gino Rivera',
    'facility_id' => 2,
    'patient_id' => 0,
    'event' => 'Immunisation View'
);
MatchaAudit::auditSaveLog();


//$data = (object)array(
//    'name' => 'Test',
//    'active' => true,
//    'phone' => '787-360-3150',
//    'fax' => '787-360-3150',
//    'street' => 'Meaow'
//);
//$Facilities->save($data);

//$array = array(
//    'model' => "App.model.patient.Patient",
//    'field' => array(
//        'name' => 'test',
//        'type' => 'string'
//    )
//);
//MatchaModel::addFieldsToModel($array);

//MatchaModel::__SenchaModel('App.model.account.VoucherLine');

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

//echo '<pre>';
//MatchaModel::addFieldsToModel('App.model.administration.testMedications', array(array('name'=>'test', 'type'=>'string')));
//echo '</pre>';

//$db->SenchaModel('App.model.administration.tmpUser');

//echo '<pre>';
//print_r($db->load(1, array('lname', 'fname', 'mname')));
//echo '</pre>';