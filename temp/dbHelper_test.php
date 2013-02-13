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

$db->SechaModel('App.model.administration.User');
echo '<pre>';
print_r($db->Model);
echo '</pre>';
