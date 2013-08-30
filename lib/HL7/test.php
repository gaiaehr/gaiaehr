<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/29/13
 * Time: 12:13 PM
 * To change this template use File | Settings | File Templates.
 */

//$WshShell = new COM("WScript.Shell");
//$oExec = $WshShell->Run('php -f ".\HL7Server.php" -- "C:/path/" "site" "class" "function"', 0, false);
set_time_limit(0);
$cmd = 'php -f "C:\inetpub\wwwroot\gaiaehr\lib\HL7\HL7Server.php" -- "C:/inetpub/wwwroot/gaiaehr/dataProvider" "default" "HL7Server" "Process" "9100"';
if (substr(php_uname(), 0, 7) == "Windows"){
//	print_r(exec($cmd));
	$h = popen("start /B ". $cmd, "r");
	pclose($h);
//	unset($h);
	exit;
}
else {
	exec($cmd . " > /dev/null &");
	exit;
}