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

class HL7Socket {

	/**
	 * @var string
	 */
	private $host = '127.0.0.1';

	/**
	 * @var int
	 */
	private $port = 9100;

	/**
	 * @var array
	 */
	private $sockets = array();

	/**
	 * @var bool
	 */
	private $rawMsg = true;

	/**
	 * @var bool|string
	 */
	private $error = false;

	/**
	 *
	 */
	function __construct(){

		/**
		 * @param $data
		 * @return string
		 */
		$this->callback = function($data){
			$ack = '';
			return $ack;
		};
	}

	/**
	 *
	 */
	public function start(){
		set_time_limit(0);
		// Ensure that every time we call "echo", the data is sent to the browser
		// IMMEDIATELY, rather than when PHP feels like it
		ob_implicit_flush();
		// Normally when the user clicks the "Stop" button in their browser, the
		// script is terminated. This line stops that happening, so that we can
		// detect the Stop button ourselves and properly close our sockets (to
		// prevent the listening socket remaining open and stealing the port)
		ignore_user_abort(true);
		// Define a function that we can call when any of our socket function calls
		// fail. This allows us to consolidate our error message XHTML and avoid
		// code repetition. If $die is set to true, the script will terminate
		function socketError($errorFunction, $die=false) {
			$errMsg = socket_strerror(socket_last_error());
			$this->error = "$errorFunction() failed! Error: $errMsg";
		}
		// Attempt to create our socket. The "@" hides PHP's standard error reporting,
		// so that we can output our own error message if it fails
		if (!($server = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
			socketError('socket_create', true);
		}
		// Set the "Reuse Address" socket option to enabled
		socket_set_option($server, SOL_SOCKET, SO_REUSEADDR, 1);
		// Attempt to bind our socket to the address and port that we're listening on.
		// Again, we suppress PHP's error reporting in favour of our own
		if (!@socket_bind($server, $this->host, $this->port)) {
			socketError('socket_bind', true);
		}
		// Start listening on the address and port that we bound our socket to above,
		// using our own error reporting code as before
		if (!@socket_listen($server)) {
			socketError('socket_listen', true);
		}
		// Create an array to store our sockets in. We use this so that we can
		// determine which socket has new incoming data with the "socket_select()"
		// function, and to properly close each socket when the script finishes
		$this->sockets = array($server);
		$null = null;
		// Start looping indefinitely. On each iteration we will make sure the browser's
		// "Stop" button hasn't been pressed and, if not, see if we have any incoming
		// client connection requests or any incoming data on existing clients
		while (true) {
			//We have to echo something to the browser or PHP won't know if the Stop
			// button has been pressed
//			if (connection_aborted()) {
//				//The Stop button has been pressed, so close all our sockets and exit
//				foreach ($this->sockets as $socket) {
//					socket_close($socket);
//				}
//
//				//Now break out of this while() loop!
//				break;
//			}
			// socket_select() is slightly strange. You have to make a copy of the array
			// of sockets you pass to it, because it changes that array when it returns
			// and the resulting array will only contain sockets with waiting data on
			// them. $write and $except are set to NULL because we aren't interested in
			// them. The last parameter indicates that socket_select will return after
			// that many seconds if no data is received in that time; this prevents the
			// script hanging forever at this point (remember, we might want to accept a
			// new connection or even exit entirely) and also pauses the script briefly
			// to prevent this tight while() loop using a lot of processor time
			$sockets = $this->sockets;

			socket_select($sockets, $null, $null, 5);

			// Now we loop over each of the sockets that socket_select() says have new
			// data on them
			foreach($sockets as $socket){
				if($socket == $server){
					// socket_select() will include our server socket in the
					// $changedSockets array if there is an incoming connection attempt
					// on it. This will only accept one incoming connection per while()
					// loop iteration, but that shouldn't be a problem given the
					// frequency that we're iterating
					if(!($client = @socket_accept($server))){
						//socket_accept() failed for some reason (again, we hid PHP's
						// standard error message), so let's say what happened...
						socketError('socket_accept', false);
					}else{
						//We've accepted the incoming connection, so add the new client
						// socket to our array of sockets
						$this->sockets[] = $client;
					}
				}else{
					//Attempt to read data from this socket
					$data = socket_read($socket, 1024*10);

					if ($data === false || $data === ''){
						// socket_read() returned FALSE, meaning that the client has
						// closed the connection. Therefore we need to remove this
						// socket from our client sockets array and close the socket
						//
						// A potential bug in PHP means that socket_read() will return
						// an empty string instead of FALSE when the connection has
						// been closed, contrary to what the documentation states. As
						// such, we look for FALSE or an empty string (an empty string
						// for the current, buggy, behaviour, and FALSE in case it ends
						// up getting fixed at some point)
						$this->stop($socket);
					}elseif($data === 'kill:secret'){
						$this->stop();
						unset($this);
					}else{
						$fn = $this->callback;
						if(!$this->rawMsg){
							include_once('HL7.php');
							$hl7 = new HL7();
							$data =  $hl7->readMessage($data);
						}
						$ack = $fn($data);
						socket_write($socket, $ack, strlen($ack));
					}
				}
			}
		}

	}

	/**
	 * @param null $socket
	 */
	public function stop($socket = null){
		if($socket == null){
			foreach($this->sockets As $socket){
				socket_shutdown($socket, 2);
				socket_close($socket);
			}
			$this->sockets = array();
		}else{
			unset($this->sockets[array_search($socket, $this->sockets)]);
			socket_shutdown($socket, 2);
			socket_close($socket);
		}
	}

	/**
	 * @param $port
	 * @return mixed
	 */
	public function setPort($port){
		return $this->port = $port;
	}

	/**
	 * @return int
	 */
	public function getPort(){
		return $this->port;
	}

	/**
	 * @param $host
	 * @return mixed
	 */
	public function setHost($host){
		return $this->host = $host;
	}

	/**
	 * @return string
	 */
	public function getHost(){
		return $this->host;
	}

	/**
	 * @param $value
	 * @return mixed
	 */
	public function setRawMsg($value){
		return $this->rawMsg = $value;
	}

	/**
	 * @return string
	 */
	public function getRawMsg(){
		return $this->rawMsg;
	}
}