<?php

namespace Flyer\Components\Server\Socket;

use Flyer\Components\Server\Socket\Exceptions\SocketConnectorException;
use Ratchet\Server\IoServer;

class SocketConnector
{

	/**
	 * The port to connect with
	 * @var integer
	 */
	protected $port;

	/**
	 * The IO server to use
	 * @var resource
	 */
	protected $server;

	/**
	 * The IO class to use
	 * @var resource
	 */
	protected $ioclass;

	/**
	 * The Socket connector needs a port to create a connection
	 * @param integer $port Port to be used
	 */
	public function __construct($port)
	{
		$this->port = $port;
	}

	/**
	 * Connect to a given socket
	 * @param  object $ioclass The IO class to use
	 * @param  boolean $force  Force the connection
	 * @return mixed          
	 */
	public function connect($ioclass, $force)
	{
		if ($this->getPortStatus($this->port) && !$force)
		{
			throw new SocketConnectorException("Cannot open new socket server, port " . $this->port . " is already in use!");
		}

		$this->ioclass = $ioclass;

		$this->server = IoServer::factory($ioclass, $this->port);
	}

	/**
	 * Run the connector
	 * @return mixed
	 */
	public function run()
	{
		$this->server->run();
	}

	/**
	 * Get the port status for any port
	 * @param  integer $port The port for the check
	 * @return boolean       The port status
	 */
	protected function getPortStatus($port)
	{
		$connection = @fsockopen($_SERVER['SERVER_ADDR'], $port);
		$open = is_resource($connection);

		return $open;
	}
}