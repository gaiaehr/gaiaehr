<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
if (!isset($_SESSION)){
	session_name('GaiaEHR');
	session_start();
	session_cache_limiter('private');
}

if( isset($_SESSION['user']) &&
	isset($_SESSION['user']['auth']) &&
	$_SESSION['user']['auth'] &&
	isset($_SESSION['user']['token']) &&
	$_SESSION['user']['token'] != $_REQUEST['token']){

	function checkStatus($port){
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		return @socket_connect($socket, '127.0.0.1', $port);
	}

	$port = $_SESSION['site']['hl7']['port'];

	switch($_REQUEST['action']){
		case 'start':
			$cmd = 'php -f "C:\inetpub\wwwroot\gaiaehr\lib\HL7\HL7Server.php" -- "C:/inetpub/wwwroot/gaiaehr/dataProvider" "default" "HL7Server" "Process" "9100"';
			if (substr(php_uname(), 0, 7) == "Windows"){
				pclose(popen("start /B ". $cmd, "r"));
			}
			else {
				exec($cmd . " > /dev/null &");
			}
			break;
		case 'stop':
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			@socket_connect($socket, '127.0.0.1', $port);
			$msg = 'shutdown';
			@socket_write($socket, $msg, strlen($msg));
			@socket_recv($socket, $response, 1024*10, MSG_WAITALL);
			@socket_close($socket);
			print json_encode(array('online'=>checkStatus($port)));
			break;
		case 'status':
			print json_encode(array('online'=>checkStatus($port)));
			break;
		default:
			die('Action Error!');
			break;
	}

}else{
	die('Not Authorized!');
}


//print '<pre>';
//$hl7 = new HL7Messages();
//print_r($hl7->sendVXU());