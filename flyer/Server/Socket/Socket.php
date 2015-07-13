<?php

namespace Flyer\Components\Server;

use Flyer\Components\Server\Socket\SocketConnector;

class Socket
{
	/**
	 * The connector instance
	 * @var resource
	 */
	public $connector;

	/**
	 * Create the socket
	 * @param  object  $socket The socket the use
	 * @param  integer $port   The port that the socket will be running at, the default is port 8080
	 * @param  boolean $force  Force the connection
	 * @return boolean         The result of the socket generation
	 */
	public function create($socket, $port = 8080, $force = false)
	{
		$this->connector = new SocketConnector($port);

		if (!is_object($socket))
		{
			// @wvanbreukelen Maybe write this exception message to a log and return false?
			throw new Exception("Cannot create socket, please give a socket object");
		}

		$this->connector->connect($socket, $force);
		$this->connector->run();

		return true;
	}

	/**
	 * Close the socket
	 * @return boolean The closing socket results
	 */
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
