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
include_once(ROOT.'/classes/MatchaHelper.php');

echo '<pre>';
$db = new MatchaHelper();
$file_handle = fopen("HCPC2013_A-N.csv", "r");

$rows = array();
$buff = array(
    'code' => null,
    'code_text' => null,
    'code_text_short' => null
);


$count = 0;
while (!feof($file_handle)) {
    $line = fgets($file_handle);
    $data = array();
    $foo = explode("\t", $line);

    if($count == 0){
        $buff = array(
            'code' => $foo[0],
            'code_text' => $foo[3],
            'code_text_short' => $foo[4]
        );
    }elseif($buff['code'] != $foo[0]){
        $rows[] = $buff;
        $buff = array(
            'code' => $foo[0],
            'code_text' => $foo[3],
            'code_text_short' => $foo[4]
        );
    }else{
        $buff['code_text'] = $buff['code_text'] . ' ' . $foo[3];
    }
    $count++;



//    $db->setSQL($db->sqlBind($data,'codes_icds','I'));
//    $db->execOnly();
}



if(feof($file_handle)) print 'The End!';
fclose($file_handle);

foreach($rows AS $row){
    $db->setSQL($db->sqlBind($row,'hcpcs_codes','I'));
    $db->execOnly();
}

print_r($rows);