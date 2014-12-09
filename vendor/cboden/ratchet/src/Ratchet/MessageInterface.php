<?php
namespace Ratchet;

interface MessageInterface {
    /**
     * Triggered when a client sends data through the socket
     * @param  \Ratchet\ConnectionInterface $from The socket/connection that sent the message to your application
     * @param  string                       $msg  The message received
     * @param  \Ratchet\Server\IoServer     $server  The message received
     * @throws \Exception
     */
    function onMessage(ConnectionInterface $from, $msg, Server\IoServer $server);
}
