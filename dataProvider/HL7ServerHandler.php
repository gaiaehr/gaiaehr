<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, inc.
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
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $_SESSION['url'].'/dataProvider/HL7Server.php');
			curl_setopt($ch, CURLOPT_TIMEOUT_MS, 250);
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
					'action' => 'start',
					'site' => $_SESSION['site']['id'],
					'port' => $port
				)
			);
			curl_exec($ch);
			curl_close($ch);
			print json_encode(array('online'=>checkStatus($port)));
			break;
		case 'stop':
			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			@socket_connect($socket, '127.0.0.1', $port);
			$msg = 'kill:secret';
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