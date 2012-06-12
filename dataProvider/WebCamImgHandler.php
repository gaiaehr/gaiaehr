<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J Rodriguez
 * Date: 6/5/12
 * Time: 7:18 PM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name ( "GaiaEHR" );
    session_start();
    session_cache_limiter('private');
}
$img = $_SESSION['site']['root'] . '/sites/' . $_SESSION['site']['site'] . '/patients/' . $_SESSION['patient']['pid'] . '/patientPhotoId.jpg';
$result = file_put_contents( $img, file_get_contents('php://input') );

if (!$result) {
	print '{"success":false}';
	exit();
}else{
	print '{"success":true, "url":"'.$img.'"}';
}

