<?php

namespace Flyer\Components\Server\SSH;

use Flyer\Components\Server\SSH\Authentation;
use Flyer\Components\Server\SSH\Connector;
use Flyer\Components\Server\SSH\Client;

class Ssh extends Client
{
	/**
	 * Connect to a SSH server
	 * @param  string  $server   The server host
	 * @param  string  $user     The user to connect with
	 * @param  string  $password The password to given user
	 * @param  integer $port     Port number to connect with
	 * @return mixed            
	 */
	public function connect($server, $user, $password, $port = 22)
	{
		$sshauth = new Authentation();

		$sshauth->setUser($user);
		$sshauth->setPassword($password);

		$this->connector = new Connector($sshauth, $server, $port);
		$this->connector->connect();

		$this->setConnector($this->connector);
	}

	/**
	 * Disconnect from the SSH server
	 * @return mixed
	 */
	public function disconnect()
	{
		$this->destroyConnection();
	}

	/**
	 * Destroy the current connection
	 * @return mixed
	 */
	private function destroyConnection()
	{
		unset($this->client);
		unset($this->connector);
	}
}