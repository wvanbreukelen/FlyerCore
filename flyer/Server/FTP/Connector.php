<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Exceptions\FtpConnectionException;

class Connector
{

	/**
	 * The connector instance
	 * @var resource
	 */
	public $connector;

	/**
	 * The authentation object
	 * @var object Authentation
	 */
	protected $auth;

	/**
	 * The server to connect with
	 * @var string The server
	 */
	protected $server;

	/**
	 * The port to connect with
	 * @var integer The port
	 */
	protected $port;

	/**
	 * Initialise the connector
	 * @param Authentation $auth   The authentation instance
	 * @param string       $server The server host to connect with
	 * @param integer      $port   Server host port number
	 */
	public function __construct(Authentation $auth, $server, $port = 21)
	{
		if (!$this->FtpExtensionExists())
		{
			throw new FtpConnectionException("FTP extension cannot been not loaded, did you enabled it in php.ini?");
		}

		$this->server = $server;
		$this->port = $port;
		$this->auth = $auth;
	}

	/**
	 * Connect to the FTP server
	 * @param  integer $timeout Timeout
	 * @param  boolean $ssl     Using SSL
	 * @return object           The new connector
	 */
	public function connect($timeout = 10, $ssl = false)
	{
		if ($ssl)
		{
			$this->connector = ftp_ssl_connect($this->server, $this->port, $timeout);
		} else {
			$this->connector = ftp_connect($this->server, $this->port, $timeout);
		}

		if (!$this->connector && $ssl)
		{
			throw new FtpConnectionException("Cannot make SSL connection to FTP server with server " . $this->server . " and port " . $this->port);
		}

		if (!$this->connector && !$ssl)
		{
			throw new FtpConnectionException("Cannot make connection to FTP server with server " . $this->server . " and port " . $this->port);
		}

		if (!ftp_login($this->connector, $this->auth->getUser(), $this->auth->getPassword()))
		{
			throw new FtpConnectionException("Cannot connect to FTP server, login credentials are invalid for user " . $this->auth->getUser());
		}

		return $this->connector;
	}

	/**
	 * Disconnect from the FTP server
	 * @return mixed
	 */
	public function disconnect()
	{
		ftp_close($this->connector);
	}

	/**
	 * Check if the FTP Extension has been loaded in php
	 * @return  mixed
	 */
	protected function FtpExtensionExists()
	{
		return extension_loaded('ftp');
	}
}