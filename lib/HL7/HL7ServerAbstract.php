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

class HL7ServerAbstract implements MessageComponentInterface
{
	protected $clients;

	private $class;
	private $method;
	private $port;
	private $site;
	private $token;

	public function __construct()
    {
		global $class, $method, $port, $site, $token;

		$this->port = $port;
		$this->site = $site;
		$this->class = $class;
		$this->method = $method;
		$this->token = $token;

		//TODO hard coded for now
		date_default_timezone_set('America/Puerto_Rico');
		$this->clients = new \SplObjectStorage;
        error_log('HL7 Server PID: '.getmypid());
	}

	public function onOpen(ConnectionInterface $conn)
    {
		// Store the new connection to send messages to later
		$conn->handler = new $this->class($this->port, $this->site);
		$this->clients->attach($conn);
	}

	public function onMessage(ConnectionInterface $conn, $message, IoServer $server)
    {
		try
        {
			if($message == '') $conn->send('');
            if($message == $this->token)
            {
				$server->loop->stop();
				$server->socket->shutdown();
				die();
			}
			$ack = call_user_func(array($conn->handler, $this->method), $message);
			$conn->send($ack);
		}
        catch (\Exception $e)
        {
			error_log($e->getMessage(), 3);
		}


	}

	public function onClose(ConnectionInterface $conn)
    {
		unset($conn->handler);
		$this->clients->detach($conn);
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
    {
		unset($conn->handler);
		$conn->close();
	}

}
