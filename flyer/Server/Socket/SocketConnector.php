<?php

namespace Flyer\Components\Server\Socket;

use Flyer\Components\Server\Socket\Exceptions\SocketConnectorException;
use Ratchet\Server\IoServer;

class SocketConnector
{

	protected $port;
	protected $server;

	public function __construct($port)
	{
		$this->port = $port;
	}

	public function connect($ioclass, $force)
	{
		if ($this->getPortStatus($this->port) && !$force)
		{
			throw new SocketConnectorException("Cannot open new socket server, port " . $this->port . " is already in use!");
		}

		$this->ioclass = $ioclass;

		$this->server = IoServer::factory($ioclass, $this->port);
	}

	public function run()
	{
		$this->server->run();
	}

	protected function getPortStatus($port)
	{
		$connection = @fsockopen($_SERVER['SERVER_ADDR'], $port);
		$open = is_resource($connection);

		return $open;
	}
}