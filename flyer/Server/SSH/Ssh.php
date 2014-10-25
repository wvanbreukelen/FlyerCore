<?php

namespace Flyer\Components\Server\SSH;

use Flyer\Components\Server\SSH\Authentation;
use Flyer\Components\Server\SSH\Connector;
use Flyer\Components\Server\SSH\Client;

class Ssh
{
	public $client;

	private $connection;

	public function connect($server, $user, $password, $port = 22)
	{
		$sshauth = new Authentation();

		$sshauth->setUser($user);
		$sshauth->setPassword($password);

		$this->connection = new Connector($sshauth, $server, $port);
		$this->connection->connect();

		$this->client = new Client($this->connection);
	}

	public function disconnect()
	{
		$this->destroyConnection();
	}

	private function destroyConnection()
	{
		unset($this->client);
		unset($this->connection);
	}
}