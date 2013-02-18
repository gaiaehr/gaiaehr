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
		
$tmpUser = Matcha::connect('App.model.administration.tmpUser');

echo '<pre>';
print_r($tmpUser->load());
print_r($tmpUser->getTotal());
echo '</pre>';

//$db->SenchaModel('App.model.administration.tmpUser');

//echo '<pre>';
//print_r($db->load(1, array('lname', 'fname', 'mname')));
//echo '</pre>';