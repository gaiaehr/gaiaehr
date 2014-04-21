#!php -q
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
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
gc_enable();

// ****************************************************** //
// * Place here persistent logic ************************ //
// ****************************************************** //
$host = $argv[1];
$port = $argv[2];
$path = $argv[3];
$class = $argv[4];
$method = $argv[5];
$site = $argv[6];
chdir($path);
include_once("$class.php");
$cls = new $class($site);

function rLog($msg) {
	$msg = "[" . date('Y-m-d H:i:s') . "] " . $msg . PHP_EOL;
	error_log($msg, 3, dirname(__FILE__). '/serve.log');
}

// ****************************************************** //
// * End persistent logic ******************************* //
// ****************************************************** //
if(($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false){
	$error = "socket_create() failed: reason: " . socket_strerror(socket_last_error());
	rLog($error);
	die;
}
if(socket_bind($sock, $host, $port) === false){
	$error = "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock));
	rLog($error);
	die;
}
if(socket_listen($sock, 5) === false){
	$error = "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock));
	rLog($error);
	die;
}

rLog('Server Started');
//clients array
$clients = array();
$request = 0;
while(true) {
	usleep(200000);
	$read = array();
	$read[] = $sock;
	$read = array_merge($read, $clients);
	//$null = null;
	// Set up a blocking call to socket_select
	if(@socket_select($read, $write = null, $except = null, $tv_sec = null) < 1){
		continue;
	}
	// Handle new Connections
	if(in_array($sock, $read)){
		if(($msgsock = @socket_accept($sock)) === false){
			rLog('socket_accept() failed: reason: ' . socket_strerror(socket_last_error($sock)));
			break;
		}
		$clients[] = $msgsock;
		$key = array_keys($clients, $msgsock);
	}
	// Handle Input
	foreach($clients as $key => $client){ // for each client
		if(in_array($client, $read)){
			$data = @socket_read($client, 1024 * 1000);
//			if(!$data) continue;
			if(!$data){
				socket_close($client[$key]);
				rLog('socket_close() Client #' . $key . ' Total Clients: ' . count($clients));
				unset($clients[$key]);
				continue;
			}
			if($data == 'shutdown'){
				socket_close($client[$key]);
				socket_close($sock);
				unset($client, $sock, $cls, $data);
				rLog('shutdown()');
				die;
			}
			// ****************************************************** //
			// ** Place message logic ******************************* //
			// ****************************************************** //
			try{
				$ack = call_user_func(array($cls,$method), $data);
			}catch (Exception $e){
				rLog($e->getMessage());
			}
			// ****************************************************** //
			// ** End message logic ********************************* //
			// ****************************************************** //
			@socket_write($client, $ack, strlen($ack));
//			@socket_close($clients[$key]);
//			unset($clients[$key]);
			$request++;
			rLog('socket_write() client #' . $key . ' Request:' . $request . ' Error:' . socket_strerror(socket_last_error($sock)));
			unset($ack);
			gc_collect_cycles();
		}
	}
	//	unset($clients);
};
socket_close($sock);
//exit;
//
//// PHP SOCKET SERVER
//error_reporting(E_ALL);
//// Configuration variables
//
//$host = $argv[1];
//$port = $argv[2];
//$path = $argv[3];
//$class = $argv[4];
//$method = $argv[5];
//$site = $argv[6];
//chdir($path);
//include_once("$class.php");
//$cls = new $class($site);
//$max = 100;
//$client = array();
//
//// No timeouts, flush content immediatly
//set_time_limit(0);
//ob_implicit_flush();
//
//// Server functions
//function rLog($msg) {
//	$msg = "[" . date('Y-m-d H:i:s') . "] " . $msg . PHP_EOL;
//	error_log($msg, 3, "/Applications/MAMP/htdocs/gaiaehr/lib/HL7/serve.log");
//
//}
//
//// Create socket
//$sock = socket_create(AF_INET, SOCK_STREAM, 0) or die("[" . date('Y-m-d H:i:s') . "] Could not create socket\n");
//// Bind to socket
//socket_bind($sock, $host, $port) or die("[" . date('Y-m-d H:i:s') . "] Could not bind to socket\n");
//// Start listening
//socket_listen($sock) or die("[" . date('Y-m-d H:i:s') . "] Could not set up socket listener\n");
//
//rLog("Server started at " . $host . ":" . $port);
//// Server loop
//while(true) {
////	sleep(1);
////	socket_set_block($sock);
//	// Setup clients listen socket for reading
//	$read[0] = $sock;
//	for($i = 0; $i < $max; $i++){
//		if($client[$i]['sock'] != null)
//			$read[$i + 1] = $client[$i]['sock'];
//	}
//	// Set up a blocking call to socket_select()
//	$ready = socket_select($read, $write = null, $except = null, $tv_sec = null);
//	// If a new connection is being made add it to the clients array
//	if(in_array($sock, $read)){
//		for($i = 0; $i < $max; $i++){
//			if($client[$i]['sock'] == null){
//				if(($client[$i]['sock'] = socket_accept($sock)) < 0){
//					rLog("socket_accept() failed: " . socket_strerror($client[$i]['sock']));
//				} else {
//					rLog("Client #" . $i . " connected");
//				}
//				break;
//			} elseif($i == $max - 1) {
//				rLog("Too many clients");
//			}
//		}
//		if(--$ready <= 0)
//			continue;
//	}
//	for($i = 0; $i < $max; $i++){
//		if(in_array($client[$i]['sock'], $read)){
//			$input = socket_read($client[$i]['sock'], 1024);
//			if($input == null){
//				unset($client[$i]);
//			}
//			$n = trim($input);
//			$com = split(' ', $n);
//			if($n == 'exit'){
//				if($client[$i]['sock'] != null){
//					socket_close($client[$i]['sock']);
//					unset($client[$i]['sock']);
//					for($p = 0; $p < count($client); $p++){
//						socket_write($client[$p]['sock'], "DISC " . $i . chr(0));
//					}
//					if($i == $adm){
//						$adm = -1;
//					}
//				}
//			} elseif($n == 'shutdown') {
//				// Server termination requested
//				socket_close($sock);
//				exit();
//			} elseif($n == 'ping'){
//					socket_write($client[$i]['sock'], 'pong' . chr(0));
//			}else{
//				$ack = call_user_func(array($cls, $method), $n);
//				socket_write($client[$i]['sock'], $ack, strlen($ack));
//				socket_close($client[$i]['sock']);
//				unset($client[$i], $ack);
//			}
//		} else {
//			//if($client[$i]['sock']!=null){
//			// Close the socket
//			//socket_close($client[$i]['sock']);
//			//unset($client[$i]);
//			//rLog("Disconnected(1) client #".$i);
//			//}
//		}
//	}
//}
//// Close the master sockets
//socket_close($sock);