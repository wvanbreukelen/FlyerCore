<?php

namespace Flyer\Components\Server\Socket\Sockets;

use Ratchet\HttpServerInferface;
use Ratchet\ConnectionInterface;
use Ratchet\RequestInterface;
use Exception;

class SocketHttp extends Socket implements HttpServerInterface
{
	public function onOpen(ConnectionInterface $conn, RequestInterface $request = null) {}

	public function onMessage(ConnectionInterface $conn, $payload) {}

	public function onClose(ConnectionInterface $conn) {}

	public function onError(ConnectionInterface $conn, Exception $e)
	{
		throw new Exception("Socket HTTP: An error has occurred: " $e->getMessage());

		$conn->close();
	}
}