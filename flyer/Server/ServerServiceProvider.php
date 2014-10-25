<?php

namespace Flyer\Components\Server;

use Flyer\Foundation\ServiceProvider;
use Flyer\Components\Server\FTP\Ftp;
use Flyer\Components\Server\SSH\Ssh;

class ServerServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->share('server.ftp', new Ftp());
		$this->share('server.ssh', new Ssh());
	}
}