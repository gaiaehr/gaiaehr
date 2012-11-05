<!--l--><?php
//$server = "localhost"; // Name or IP of database server.
//$user = "root"; // username
//$pwd = ""; // password
//$db = "mitosdb"; // Name of database to connect to.

//if (!$conn = mysql_connect($server,$user,$pwd )) {
//die("mysql_connect() failed");
//}
//
//
//if(!mysql_select_db($db)) {
//	echo "Impossible d'accer la base de donns : " . mysql_error();
//	exit;
//}
if(!isset($_SESSION)) {
    session_name('GaiaEHR');
    session_start();
    session_cache_limiter('private');
}
include_once($_SESSION['root'] . '/classes/dbHelper.php');
$db = new dbHelper();
//
//$lines = file('http://localhost/GaiaEHR/product.txt');
//
////foreach ($lines as $line_num => $line) {
////    echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";
////}
////exit;
//
//foreach ($lines as $line_num => $line) {
//	$arr  = explode("\t", $line);
//	#if your data is comma separated
//	# instead of tab separated,
//	# change the '\t' above to ';'
//	$sql = "insert into medications values ('" . implode("','", $arr) . "')";

//	mysql_query($sql);
//	echo $sql . "\n";
//	if(mysql_error()) {
//		echo mysql_error() . "\n";
//	}
//}
//
//function multiexplode ($division,$string) {
//    $ary = explode($division[0],$string);
//    array_shift($division);
//    if($division != NULL) {
//        foreach($ary as $key => $val) {
//            $ary[$key] = multiexplode($division, $val);
//        }
//    }
//    return  $ary;
//}


//$division = Array(":",";");

//$res = multiexplode($division,$string);
//function is_odd($int){
//    return ($int & 1);
//}
//$res = explode('?',$string);
////print_r($res);
//$data = array();
//foreach($res as $num => $val){
//    echo '<pre>';
//	if($num != 0){
//	    if(is_odd($num)){
//	        $data['code'] = $val;
//
//	    }else{
//		        //$surgeries=($inside);
//			 $data['description']=$val;
////			    print_r($data);
////		           print $db->sqlBind($data, 'cdt_codes', 'I');
////		           echo '<br>';
//		    $db -> setSQL($db->sqlBind($data, 'cdt_codes', 'I'));
//		    		$db -> execLog();
////        $sql=  "INSERT INTO cdt_codes (code, description) VALUES('".$data['code']."','".$data['description']."')";
////
////                mysql_query($sql);
////                echo $sql . "\n";
////                if(mysql_error()) {
////                    echo mysql_error() . "\n";
////                }
//
//	    }
//	}
//}

$filename   = 'cdt.txt';
$handle     = fopen($filename, 'r');
$string     = fread($handle, filesize($filename));
//preg_match("/(\w*?) (.*\n)/", $string, $matches, PREG_OFFSET_CAPTURE);
//
//print '<pre>';
//print_r($matches);
//
//$html = "<b>bold text</b><a href=howdy.html>click me</a>";

preg_match_all("/(.*?) (.*)\n/", $string, $matches, PREG_SET_ORDER);
foreach ($matches as $val){
	$data = array('code' => $val[1], 'text' => $val[2]);
	$db->setSQL($db->sqlBind($data,'cdt_codes','I'));
	$db->execOnly();
}

//    print_r($res);
//foreach($res as $num => $val){
//    echo '<pre>';
//    if(is_odd($num)){
//        foreach($res[$num] as $item => $inside){
//        //$surgeries=($inside);
//            $data['surgeries'] = $inside;
//
//           print $db->sqlBind($data, 'table_example', 'I');
//           echo '<br>';
////        $sql=  "INSERT INTO surgeries (type, surgery, type_num) VALUES('".$tittle."','".$surgeries."','".$type_num."')";
////
////            	mysql_query($sql);
////            	echo $sql . "\n";
////            	if(mysql_error()) {
////            		echo mysql_error() . "\n";
////                }
//    }
//
//    }else{
//        $data['title'] = ($res[$num][0]);
//        $data['type'] = $data['type']+1;
//        //$tittle =($res[$num][0]);
//        //$type_num=$type_num+1;
//
//
//    }
//    print_r($res[$num]);
//
////    print_r($res[$num]);
//
//    echo '</pre>';
//}
//echo '<pre>';
//print_r($res[0]);
//echo '</pre>';





//$string = 'April 15, 2003';
//$pattern = '/(\w+) (\d+), (\d+)/i';
//$replacement = '${1}1,$3';
//echo preg_replace($pattern, $replacement, $string);

//$string = '2012-05-23T00:00:00';
//$pattern = '/([0-9]{4}-[0-9]{2}-[0-9]{2})T([0-9]{2}:[0-9]{2}:[0-9]{2})/i';
//$replacement = '${1} ${2}';
//echo preg_replace($pattern, $replacement, $string);