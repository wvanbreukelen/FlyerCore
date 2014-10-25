<?php

namespace Flyer\Components\Server\SSH;

use Exceptions\SshTransportException;

class Client
{

	protected $connector;

	public function __construct(Connector $connector)
	{
		$this->connector = $connector;
	}

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

	public function getConnector()
	{
		return $this->connector->connector;
	}
}