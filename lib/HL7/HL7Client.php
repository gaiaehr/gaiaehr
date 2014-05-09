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

class HL7Client {

	/**
	 * @var string
	 */
	private $address = '127.0.0.1';
	/**
	 * @var string
	 */
	private $port = '9001';
	/**
	 * @var null|string
	 */
	private $msg = null;
	/**
	 * @var null
	 */
	private $socket = null;

	private $connected = false;

	private $timeout = 10; // ten seconds


	function __construct($address = null, $port = null) {
		if(isset($address)) $this->address = $address;
		if(isset($port)) $this->port = $port;
	}

	public function Save($msg = null) {

		if(isset($msg)) $this->msg = $msg;

		try{
			if(preg_match('/\/$/', $this->address)) $this->address .= '/';
			$filename = trim($this->address) . str_replace('.', '', microtime(true)) . '.hl7';

			if(!$handle = fopen($filename, 'w')){
				throw new Exception("Could not create file ($filename)");
			}
			if(fwrite($handle, $msg) === false){
				throw new Exception("Cannot write to file ($filename)");
			}
			fclose($handle);
			return array(
				'success' => true,
				'message' => "File created - $filename"
			);
		}catch (Exception $e){
			return array(
				'success' => false,
				'message' => $e->getMessage()
			);
		}

	}

	public function Send($msg = null) {

		if(isset($msg)) $this->msg = $msg;

		try {

			if(isset($this->socket) && $this->connected){
				$response = $this->socketWrite($this->msg);
				return array(
					'success' => true,
					'message' => $response
				);
			}elseif(preg_match('/^http/', $this->address)){
				$ch = curl_init($this->address . ':' . $this->port);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-Type: application/hl7-v2; charset=ISO-8859-4',
					'Content-Length: ' . strlen($msg)
				));

				$response = curl_exec($ch);
				$error = curl_errno($ch);

				if($error !== 0){
					$errorMsg = '[' . $error . '] ' . curl_error($ch);
					curl_close($ch);
					throw new Exception($errorMsg);
				}

				curl_close($ch);
				return array(
					'success' => true,
					'message' => $response
				);

			} else {

				$this->Connect();
				$response = $this->socketWrite();
				$this->Disconnect();
				$this->connected = false;

				return array(
					'success' => true,
					'message' => $response
				);
			}
		} catch(Exception $e) {
			$this->Disconnect();
			return array(
				'success' => false,
				'message' => $e->getMessage()
			);
		}
	}

	public function Connect(){
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($this->socket === false){
			throw new Exception("socket_create() failed: reason: " . socket_strerror(socket_last_error()));
		}
		$result = socket_connect($this->socket, $this->address, $this->port);
		if($result === false){
			throw new Exception("socket_connect() failed. Reason: ($result) " . socket_strerror(socket_last_error($this->socket)));
		}
		return $this->connected = true;
	}

	public function Disconnect(){
		if(isset($this->socket)) socket_shutdown($this->socket);
		if(isset($this->socket)) socket_close($this->socket);
		unset($this->socket);
		$this->connected = false;
	}

	private function socketWrite($msg = null) {
		if(!isset($msg))
			throw new Exception('Hl7 message can not be null');

		$msg = chr(0x0b) . $msg . chr(0x1c) . chr(0x0d);
		socket_write($this->socket, $msg);
		$this->msg = null;
		$response = '';
		$timeout = time() + $this->timeout; // set timeout
		while(true) {
			usleep(1000);
			if($timeout < time()) throw new Exception('Socket timed out! No response received from '.$this->address);
			$response .= @socket_read($this->socket, 4);
			if(substr($response, -2) == chr(0x1c) . chr(0x0d)) break;
		}
		return $response;
	}


	/** Setters */

	public function setAddress($address){
		return $this->address = $address;
	}

	public function setPort($port){
		return $this->port = $port;
	}

	public function setMsg($msg){
		return $this->msg = $msg;
	}

	public function setTimeout($timeout){
		return $this->timeout = $timeout;
	}

	/** Getters */

	public function getAddress(){
		return $this->address;
	}

	public function getPort(){
		return $this->port;
	}

	public function getMsg(){
		return $this->msg;
	}

	public function getTimeout(){
		return $this->timeout;
	}

}

$client = new HL7Client();
$client->Connect();
$msg = 'MSH|^~\&|REGADT|GOOD HEALTH HOSPITAL|RSP1P8|GOOD HEALTH HOSPI- TAL|200701051530|SEC|ADT^A09^ADT_A09|00000003|P|2.5.1'. chr(0x0d);
$msg .= 'EVN|A09|200701051530'. chr(0x0d);
$msg .= 'PID|||6^^^GAIA-1||EVERYWOMAN^EVE|'. chr(0x0d);
$msg .= 'PV1|1||2|||1|1|||||||||Y'. chr(0x0d);
$res = $client->Send($msg);
//$res = $client->Send($msg);
//$res = $client->Send($msg);
//$res = $client->Send($msg);
//$res = $client->Send($msg);
$client->Disconnect();
print_r($res);
print_r('<br>');


