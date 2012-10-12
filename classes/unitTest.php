<?php
session_name ( 'GaiaEHR' );
session_start();
session_cache_limiter('private');
$_SESSION['root'] = '/wamp/www/GaiaEHR-Official';

include_once($_SESSION['root'].'/classes/dbHelper.php');

echo "Unit Test:<br/>";
echo "-----------------------------------------------------------------------------------------------------------<br/>";

$dbHelperTest = new dbHelper();

$fields[] = "firstname";
$fields[] = "lastname";

$order[] = "firstname";
$order["DESC"] = "lastname";

$where["OR"] = "firstname='gino'";
$where[] = "lastname='rivera'";

echo $dbHelperTest->sqlSelectBuilder("patient", $fields, $order, $where);

echo "<br/>";

$fields=null;
$order=null;
$where=null;

$fields[] = "*";
echo $dbHelperTest->sqlSelectBuilder("patient", $fields, $order, $where);

?>