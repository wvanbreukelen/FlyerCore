<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Authentation;
use Flyer\Components\Server\FTP\Connector;
use Flyer\Components\Server\FTP\Client;

class Ftp
{
	public $client;

	private $connection;

	public function connect($server, $user, $password, $port = 21, $ssl = false)
	{
		$ftpauth = new Authentation();

		$ftpauth->setUser($user);
		$ftpauth->setPassword($password);

		$this->connection = new Connector($ftpauth, $server, $port);
		$this->connection->connect();

		$this->client = new Client($this->connection);
	}

	public function disconnect()
	{
		$this->destroyConnection();
	}

	private function destroyConnection()
	{
		$this->connection->disconnect();

		unset($this->client);
		unset($this->connection);
	}
}