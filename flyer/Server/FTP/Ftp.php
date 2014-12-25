<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Authentation;
use Flyer\Components\Server\FTP\Connector;
use Flyer\Components\Server\FTP\Client;

class Ftp extends Client
{

	/**
	 * Connect to the FTP server
	 * @param  string  $server   The server host to connect with
	 * @param  string  $user     The authentication user
	 * @param  string  $password The authentication password
	 * @param  integer $port     The server port
	 * @param  boolean $ssl      Use a SSL connection or not
	 * @param  integer $timeout  Timeout for connecting to the server
	 * @return boolean           Succeeded
	 */
	public function connect($server, $user, $password, $port = 21, $ssl = false, $timeout = 10)
	{
		$ftpauth = new Authentation();

		$ftpauth->setUser($user);
		$ftpauth->setPassword($password);

		$this->connector = new Connector($ftpauth, $server, $port);
		$this->connector->connect($timeout, $ssl);

		$this->setConnector($this->connector);

		return true;
	}

	/**
	 * Disconnect from the FTP server
	 * @return boolean Succeeded
	 */
	public function disconnect()
	{
		return $this->destroyConnection();
	}

	/**
	 * Destroy the FTP server connection
	 * @return boolean Succeeded
	 */
	private function destroyConnection()
	{
		if (is_object($this->connector))
		{
			$this->connector->disconnect();

			unset($this->client);
			unset($this->connector);

			return true;
		}

		return false;
	}
}