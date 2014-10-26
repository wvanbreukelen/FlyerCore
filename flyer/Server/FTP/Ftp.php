<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Authentation;
use Flyer\Components\Server\FTP\Connector;
use Flyer\Components\Server\FTP\Client;

class Ftp extends Client
{
	public function connect($server, $user, $password, $port = 21, $ssl = false, $timeout = 10)
	{
		$ftpauth = new Authentation();

		$ftpauth->setUser($user);
		$ftpauth->setPassword($password);

		$this->connector = new Connector($ftpauth, $server, $port);
		$this->connector->connect($timeout, $ssl);

		$this->setConnector($this->connector);
	}

	public function disconnect()
	{
		$this->destroyConnection();
	}

	private function destroyConnection()
	{
		$this->connector->disconnect();

		unset($this->client);
		unset($this->connector);
	}
}