<?php

namespace Flyer\Components\Server\Socket\Sockets;

use Flyer\Components\Server\Socket;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use SplObjectStorage;
use Exception;

class SocketIO extends Socket implements MessageComponentInterface
{

	protected $clients;

	public function __construct()
	{
		$this->clients = new SplObjectStorage;
	}

	public function onOpen(ConnectionInterface $conn) 
	{
		$this->clients->attach($conn);

		echo "New Connection!";
	}

	public function onMessage(ConnectionInterface $from, $payload) 
	{
		foreach ($this->clients as $client)
		{
			if ($from !== $client)
			{
				$client->send($payload);
			}
		}
	}

	public function onClose(ConnectionInterface $conn) 
	{
		$this->clients->detach($conn);
	}

	public function onError(ConnectionInterface $conn, Exception $e)
	{
		$conn->close();
		
		throw new Exception("Socket IO: An error has occurred: " . $e->getMessage());
	}
}