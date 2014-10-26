<?php

namespace Flyer\Components\Server\SSH;

use Flyer\Components\Server\SSH\Connector;
use Exceptions\SshTransportException;

/**
 * The SSH client, a wrapper for php's SSH extension
 */

class Client
{

	/**
	 * The SSH connector, contains login details
	 * @var object
	 */
	protected $connector;

	/**
	 * Execute a command over SSH
	 * @param  string $command Command to execute
	 * @param  string $data    Data to begin with
	 * @return string          Command output
	 */
	public function execute($command, $data = "")
	{
		if (!$stream = ssh2_exec($this->getConnector(), $command))
		{
			throw new SshTransportException("Cannot execute SSH command!");
		} else {
			stream_set_blocking($stream, true);

			while ($buffer = fread($stream, 4096))
			{
				$data .= $buffer;
			}

			fclose($stream);
		}

		return $data;
	}

	/**
	 * Set the SSH connector
	 * @param Connector $connector The SSH connector, contains login details
	 */
	public function setConnector(Connector $connector)
	{
		$this->connector = $connector;
	}

	/**
	 * Get the SSH connector
	 * @return Connector The SSH connector, contains login details
	 */
	public function getConnector()
	{
		return $this->connector->connector;
	}
}