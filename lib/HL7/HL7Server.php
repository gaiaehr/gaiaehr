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
use Ratchet\Server\IoServer;

require (str_replace('\\', '/', dirname(__FILE__)) . '/../../vendor/autoload.php');
require (str_replace('\\', '/', dirname(__FILE__)) . '/HL7ServerAbstract.php');

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$host = $_POST['host'];
$port = $_POST['port'];
$path = $_POST['path'];
$class = $_POST['class'];
$method = $_POST['method'];
$site = $_POST['site'];
$token = $_POST['token'];

define('ROOT', str_replace('lib/HL7', '', str_replace('\\', '/', dirname(__FILE__))));

/**
 * Enable the error and also set the ROOT directory for
 * the error log. But checks if the files exists and is
 * writable.
 *
 * NOTE: This should be part of Matcha::Connect
 */
ini_set('display_errors', 1);
$logPath = ROOT . 'sites/' . $site . '/log/';
if(file_exists($logPath) && is_writable($logPath))
{
    $logFile = 'error_log.txt';
    $oldUmask = umask(0);
    clearstatcache();
    if(!file_exists($logPath . $logFile)){
        touch($logPath . $logFile);
        chmod($logPath . $logFile, 0775);
    }
    if(is_writable($logPath . $logFile))
        ini_set('error_log', $logPath . $logFile);
    umask($oldUmask);
}

chdir($path);
include_once("$class.php");

gc_enable();

$server = IoServer::factory(new HL7ServerAbstract, $port);
$server->run();

exit;

// ****************************************************** //
// * Place here persistent logic ************************ //
// ****************************************************** //

chdir($path);

$cls = new $class($port, $site);

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
		try{
			if(in_array($client, $read)){
				$data = @socket_read($client, 1024 * 1000);
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

				$ack = call_user_func(array($cls,$method), $data);

				// ****************************************************** //
				// ** End message logic ********************************* //
				// ****************************************************** //
				@socket_write($client, $ack, strlen($ack));
				$request++;
				rLog('socket_write() client #' . $key . ' Request:' . $request . ' Error:' . socket_strerror(socket_last_error($sock)));
				unset($ack);
				gc_collect_cycles();
			}
		}catch (Exception $e){
			rLog($e->getMessage());
		}
	}
};
socket_close($sock);
