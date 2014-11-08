<?php

namespace Flyer\Components\Server;

use Flyer\Components\Server\Socket\SocketConnector;

class Socket
{
	public function create($socket, $port = 8080, $force = false)
	{
		$this->connector = new SocketConnector($port, $force);

		if (!is_object($socket))
		{
			throw new Exception("Cannot create socket, please give a socket object");

			return false;
		}

		$this->connector->connect($socket, $force);
		$this->connector->run();

		return true;
	}

	public function close()
	{
		if (is_object($this->connector))
		{
			unset($this->connector);

			return true;
		}

		return false;
	}
}