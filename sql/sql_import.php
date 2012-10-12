<?php
/**
 * Created by JetBrains PhpStorm.
 * User: GaiaEHR
 * Date: 3/20/12
 * Time: 8:52 AM
 * To change this template use File | Settings | File Templates.
 */
if(!isset($_SESSION)){
    session_name ( 'GaiaEHR' );
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['root'].'/classes/dbHelper.php');

echo '<pre>';
$db = new dbHelper();
$file_handle = fopen("icds.txt", "r");
while (!feof($file_handle)) {
    $line = fgets($file_handle);
    $data = array();
    $foo = explode("\t", $line);
    $data['id']             = $foo[0];
    $data['parent_id']      = $foo[1];
    $data['code_text']      = $foo[2];
    $data['code_text_short']= $foo[3];
    $data['code']           = $foo[4];
    $data['digits']         = $foo[5];
    $data['sequence']       = $foo[6];
    $data['category_range'] = $foo[7];
    $data['billing_status'] = $foo[8];
    $data['status']         = $foo[9];
    $data['is_custom_code'] = $foo[10];
//    $data['doc_url3'] = $foo[11];
//    $data['r'] = $foo[12];
//    $data['s'] = $foo[13];
//    $data['t'] = $foo[14];
//    $data['u'] = $foo[15];
//    $data['v'] = $foo[16];
//    $data['w'] = $foo[17];
//    $data['x'] = $foo[18];
    $db->setSQL($db->sqlBind($data,'codes_icds','I'));
    $db->execOnly();
}
if(feof($file_handle)) print 'The End!';
fclose($file_handle);