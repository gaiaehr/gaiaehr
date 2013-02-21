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
include_once ($_SESSION['root'] . '/classes/dbHelper.php');


$db = new dbHelper();
		
//$VoucherLine = Matcha::setSenchaModel('App.model.account.VoucherLine');
MatchaAudit::defineLogModel(array(
    array('name'=>'date', 'type'=>'date'),
    array('name'=>'event','type'=>'string'),
    array('name'=>'comments', 'type'=>'string'),
    array('name'=>'user', 'type'=>'string'),
    array('name'=>'checksum', 'type'=>'string'),
    array('name'=>'facility', 'type'=>'string'),
    array('name'=>'patient_id', 'type'=>'int'),
    array('name'=>'ip', 'type'=>'string')
));

echo '<pre>';
//print_r(Matcha::__setSenchaModelData('App.data.account.AccountType'));
echo '</pre>';

//$db->SenchaModel('App.model.administration.tmpUser');

//echo '<pre>';
//print_r($db->load(1, array('lname', 'fname', 'mname')));
//echo '</pre>';