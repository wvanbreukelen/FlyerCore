<?php

namespace Flyer\Components\Server\SSH;

use Flyer\Components\Server\SSH\Exceptions\SshConnectionException;

class Connector
{

	public $connector;

	protected $auth;

	protected $server;

	protected $port;

	public function __construct(Authentation $auth, $server, $port = 22)
	{
		if (!$this->SshExtensionExists())
		{
			throw new SshConnectionException("SSH extension cannot been not loaded, did you enabled it in php.ini?");
		}

		$this->server = $server;
		$this->port = $port;
		$this->auth = $auth;
	}

	public function connect()
	{
		$this->connector = ssh2_connect($this->server, $this->port);

		if (!$this->connector)
		{
			throw new SshConnectionException("Cannot make SSH connection to host " . $this->server . " with port " . $this->port);
		}

		if (!ssh2_auth_password($this->connector, $this->auth->getUser(), $this->auth->getPassword()))
		{
			throw new SshConnectionException("Cannot connect to SSH host, login credentials are invalid for user " . $this->auth->getUser());
		}

		return $this->connector;
	}

	public function disconnect()
	{
		unset($this->connector);
	}

	protected function SshExtensionExists()
	{
		return extension_loaded('ssh2');
	}
}