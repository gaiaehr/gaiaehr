#!php -q
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
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$address = '127.0.0.1';

// ****************************************************** //
// * Place here persistent logic ************************ //
// ****************************************************** //

$path   = $argv[1];
$site   = $argv[2];
$class  = $argv[3];
$method = $argv[4];
$port   = $argv[5];
chdir($path);
include_once("$class.php");
$cls = new $class($site);

// ****************************************************** //
// * End persistent logic ******************************* //
// ****************************************************** //


if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
	echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) === false) {
	echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock, 5) === false) {
	echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

//clients array
$clients = array();

while(true){
	usleep(1);

	$read = array();
	$read[] = $sock;

	$read = array_merge($read,$clients);
	$null = null;
	// Set up a blocking call to socket_select
	if(@socket_select($read, $null, $null, $tv_sec = 5) < 1){
		continue;
	}

	// Handle new Connections
	if (in_array($sock, $read)) {

		if (($msgsock = @socket_accept($sock)) === false) {
			echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
			break;
		}
		$clients[] = $msgsock;
		$key = array_keys($clients, $msgsock);

	}

	// Handle Input
	foreach ($clients as $key => $client) { // for each client
		if (in_array($client, $read)) {

			$data = @socket_read($client, 1024*10);


			if (!$data = trim($data)) {
				continue;
			}

			if ($data == 'quit') {
				unset($clients[$key]);
				socket_close($client);
				break;
			}

			if ($data == 'shutdown') {
				socket_close($client);
				break 2;
			}

			// ****************************************************** //
			// ** Place message logic ******************************* //
			// ****************************************************** //


			$ack = call_user_func(array($cls, $method), $data);


			// ****************************************************** //
			// ** End message logic ********************************* //
			// ****************************************************** //

			set_time_limit(0);
			@socket_write($client, $ack, strlen($ack));
		}
	}
};

socket_close($sock);