<?php

namespace Flyer\Components\Server\FTP;

use Flyer\Components\Server\FTP\Exceptions\FtpConnectionException;

class Connector
{

	public $connector;

	protected $auth;

	protected $server;

	protected $port;

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

	public function disconnect()
	{
		ftp_close($this->connector);
	}

	protected function FtpExtensionExists()
	{
		return extension_loaded('ftp');
	}
}