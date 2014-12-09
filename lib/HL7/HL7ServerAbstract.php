<?php
/**
 * Created by IntelliJ IDEA.
 * User: ernesto
 * Date: 8/5/14
 * Time: 6:48 PM
 */

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;

class HL7ServerAbstract implements MessageComponentInterface {
	protected $clients;

	private $class;
	private $method;
	private $port;
	private $site;

	public function __construct() {
		global $class, $method, $port, $site;

		$this->port = $port;
		$this->site = $site;
		$this->class = $class;
		$this->method = $method;

		//TODO hard coded for now
		date_default_timezone_set('America/Puerto_Rico');
		$this->clients = new \SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn) {
		// Store the new connection to send messages to later
		$conn->handler = new $this->class($this->port, $this->site);
		$this->clients->attach($conn);
	}

	public function onMessage(ConnectionInterface $conn, $message, IoServer $server) {

		if($message == ''){
			$conn->send('');
		}if($message == MatchaUtils::encrypt(site_aes_key)){
			$server->loop->stop();
			$server->socket->shutdown();
			die();
		}

		$ack = call_user_func(array($conn->handler, $this->method), $message);
		$conn->send($ack);
	}

	public function onClose(ConnectionInterface $conn) {
		unset($conn->handler);
		$this->clients->detach($conn);
	}

	public function onError(ConnectionInterface $conn, \Exception $e) {
		unset($conn->handler);
		$conn->close();
	}

}