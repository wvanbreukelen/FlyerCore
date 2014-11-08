<?php

namespace Flyer\Components\Server\Socket\Sockets;

use Ratchet\WampServerInterface;
use Ratchet\ConnectionInterface;
use Exception;

class SocketWamp extends Socket implements WampServerInterface
{
	public function onOpen(ConnectionInterface $conn) {}

	public function onMessage(ConnectionInterface $conn, $payload) {}

	public function onCall(ConnectionInterface $conn, $id, Topic $topic, array $param = array()) {}

	public function onClose(ConnectionInterface $conn) {}

	public function onError(ConnectionInterface $conn, Exception $e)
	{
		throw new Exception("Socket IO: An error has occurred: " $e->getMessage());

		$conn->close();
	}
}